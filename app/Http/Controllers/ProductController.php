<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductVerification;
use App\Notifications\ProductVerificationRequest;
use App\Notifications\TradeMatchFound;
use Illuminate\Http\Request;
use App\Models\SavedSearch;
use App\Notifications\SavedSearchMatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $allCategories = config('categories');

        $query = Product::query()->where('hide', 0);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $activeCat = $request->input('category');
        $activeSub = $request->input('sub');

        if ($activeCat) {
            $query->where('category', $activeCat);
        }

        if ($activeSub) {
            $query->where('sub_category', $activeSub);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        // Geo-prioritization: show listings from user's country first
        $userCountry = $this->detectUserCountry($request);
        if ($userCountry && $request->input('sort') !== 'views') {
            $query->orderByRaw('CASE WHEN location LIKE ? THEN 0 ELSE 1 END', ["%{$userCountry}%"])
                  ->latest();
        } elseif ($request->input('sort') === 'views') {
            $query->orderBy('views', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'allCategories', 'activeCat', 'activeSub', 'userCountry'));
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to list an item.');
        }

        $allCats = config('categories');
        return view('products.create', compact('allCats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category'    => 'required|string',
            'condition'   => 'required|in:New,Like New,Good,Fair,Poor',
            'location'    => 'required|string|max:255',
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image && $image->isValid()) {
                    $imagePaths[] = $this->storeAsWebP($image);
                }
            }
        }

        // Geocode location (cached, non-blocking on failure)
        $coords = \App\Http\Controllers\MapController::geocode($request->location ?? '');

        $product = Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'user_id'     => Auth::id(),
            'hide'        => 0,
            'image_paths' => json_encode($imagePaths),
            'category'                  => $request->category,
            'sub_category'             => $request->sub_category ?: null,
            'looking_for'              => $request->looking_for ?: null,
            'preferred_offer_category'     => $request->preferred_offer_category ?: null,
            'preferred_offer_sub_category' => $request->preferred_offer_sub_category ?: null,
            'condition'                => $request->condition,
            'location'                 => $request->location,
            'latitude'                 => $coords['lat'] ?? null,
            'longitude'                => $coords['lng'] ?? null,
        ]);

        // Smart matching: find products whose owner wants what I have and has what I want
        if ($product->looking_for) {
            $matches = Product::where('category', $product->looking_for)
                ->where('looking_for', $product->category)
                ->where('user_id', '!=', Auth::id())
                ->where('hide', 0)
                ->with('user')
                ->limit(5)
                ->get();

            foreach ($matches as $match) {
                // Notify me about the match
                Auth::user()->notify(new TradeMatchFound($product, $match));
                // Notify the match owner about my product
                if ($match->user) {
                    $match->user->notify(new TradeMatchFound($match, $product));
                }
            }
        }

        // Pick 5 random users (excluding the product owner) for verification
        $randomUsers = User::where('id', '!=', Auth::id())
            ->inRandomOrder()
            ->limit(5)
            ->get();

        foreach ($randomUsers as $user) {
            ProductVerification::create([
                'product_id' => $product->id,
                'user_id'    => $user->id,
                'status'     => 'pending',
            ]);

            $user->notify(new ProductVerificationRequest($product));
        }

        // Saved-search matching: notify users whose saved search matches this new product
        SavedSearch::whereNot('user_id', Auth::id())
            ->get()
            ->each(function (SavedSearch $search) use ($product) {
                if ($search->matches($product)) {
                    $search->user?->notify(new SavedSearchMatch($product, $search));
                    $search->update(['last_notified_at' => now()]);
                }
            });

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('products.edit_listing', compact('product'));
    }

    public function show(Product $product)
    {
        $product->increment('views');
        $products = Product::with(['user.reviewsReceived', 'reviews.user'])->findOrFail($product->id);
        return view('products.show', compact('product', 'products'));
    }

    public function removeImage(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'image_path' => 'required|string',
        ]);

        $imagePath = $request->input('image_path');

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $images = json_decode($product->image_paths, true);
        $images = array_filter($images, fn($path) => $path !== $imagePath);
        $product->image_paths = json_encode(array_values($images));
        $product->save();

        return response()->json(['success' => true]);
    }

    public function showSellerItems($id)
    {
        $seller = User::findOrFail($id);
        $items  = Product::where('user_id', $id)->get();

        return view('seller.items', compact('seller', 'items'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Authorization
        if (Auth::id() !== $product->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validation
        $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'required|string|max:5000',
            'category'        => 'required|string',
            'condition'       => 'required|in:New,Like New,Good,Fair,Poor',
            'location'        => 'required|string|max:255',
            'existing_images' => 'nullable|array',
        ]);

        // Retained images sent from the form
        $retainedImages = $request->input('existing_images', []);

        // Handle new uploads — validate manually to avoid empty-input errors
        $newImages = [];
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image && $image->isValid() && in_array($image->getMimeType(), $allowedMimes)) {
                    if ($image->getSize() <= 10 * 1024 * 1024) { // 10 MB cap for edits
                        $newImages[] = $this->storeAsWebP($image);
                    }
                }
            }
        }

        // Merge retained + new
        $finalImages = array_values(array_merge($retainedImages, $newImages));

        $product->update([
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'condition'   => $request->condition,
            'location'    => $request->location,
            'image_paths' => json_encode($finalImages),
        ]);

        return redirect()->route('products.show', $product->id)
                         ->with('success', 'Product updated successfully.');
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($id);

        // Prevent reviewing own product
        if (Auth::id() == $product->user_id) {
            return back()->with('error', 'You cannot review your own product.');
        }

        // Prevent duplicate reviews
        $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                            ->where('product_id', $id)
                                            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        \App\Models\Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return redirect()->to(url()->previous() . '#reviews')->with('success', 'Review submitted successfully!');
    }

    /**
     * Convert any uploaded image to WebP using cwebp and store it.
     * Falls back to storing the original if conversion fails.
     */
    private function storeAsWebP(\Illuminate\Http\UploadedFile $image): string
    {
        $uuid     = \Illuminate\Support\Str::uuid();
        $filename = 'images/' . $uuid . '.webp';

        Storage::disk('public')->makeDirectory('images');
        $destPath = Storage::disk('public')->path($filename);

        $srcPath = $image->getRealPath();

        // cwebp -q 82: good quality/size balance; -quiet suppresses stdout
        $cmd    = sprintf('cwebp -q 82 -metadata none -quiet %s -o %s 2>/dev/null', escapeshellarg($srcPath), escapeshellarg($destPath));
        $retval = null;
        exec($cmd, $out, $retval);

        if ($retval === 0 && file_exists($destPath) && filesize($destPath) > 0) {
            return $filename;
        }

        // Fallback: store original if cwebp fails
        return $image->store('images', 'public');
    }

    /**
     * Detect user's country from Cloudflare header or IP-based lookup.
     * Returns country name (e.g. "Georgia") or null.
     */
    private function detectUserCountry(\Illuminate\Http\Request $request): ?string
    {
        // Cloudflare provides ISO 2-letter country code in this header
        $code = $request->header('CF-IPCountry');

        if (!$code || $code === 'XX' || $code === 'T1') {
            return null; // unknown or Tor exit node
        }

        $map = [
            'GE' => 'Georgia',   'US' => 'United States', 'GB' => 'United Kingdom',
            'DE' => 'Germany',   'FR' => 'France',         'IT' => 'Italy',
            'ES' => 'Spain',     'PL' => 'Poland',         'NL' => 'Netherlands',
            'TR' => 'Turkey',    'UA' => 'Ukraine',        'RU' => 'Russia',
            'AM' => 'Armenia',   'AZ' => 'Azerbaijan',     'BY' => 'Belarus',
            'KZ' => 'Kazakhstan','MD' => 'Moldova',        'UZ' => 'Uzbekistan',
            'AE' => 'UAE',       'CA' => 'Canada',         'AU' => 'Australia',
            'SE' => 'Sweden',    'NO' => 'Norway',         'DK' => 'Denmark',
            'FI' => 'Finland',   'PT' => 'Portugal',       'CZ' => 'Czech Republic',
            'HU' => 'Hungary',   'RO' => 'Romania',        'SK' => 'Slovakia',
            'AT' => 'Austria',   'CH' => 'Switzerland',    'BE' => 'Belgium',
            'GR' => 'Greece',    'IL' => 'Israel',         'JP' => 'Japan',
            'KR' => 'South Korea','CN' => 'China',         'IN' => 'India',
            'BR' => 'Brazil',    'MX' => 'Mexico',         'AR' => 'Argentina',
            'ZA' => 'South Africa',
        ];

        return $map[strtoupper($code)] ?? null;
    }

    /**
     * Location autocomplete — proxies Nominatim, cached 7 days.
     */
    public function locationSuggest(\Illuminate\Http\Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'loc_suggest_' . md5(strtolower($q));
        $results  = Cache::remember($cacheKey, now()->addDays(7), function () use ($q) {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent'      => 'Bartaro/1.0 (bartaro.com)',
                'Accept-Language' => 'en',
            ])->timeout(4)->get('https://nominatim.openstreetmap.org/search', [
                'q'               => $q,
                'format'          => 'json',
                'limit'           => 6,
                'addressdetails'  => 1,
                'accept-language' => 'en',
            ]);

            if (!$response->ok()) return [];

            return collect($response->json())->map(function ($item) {
                $addr    = $item['address'] ?? [];
                $city    = $addr['city'] ?? $addr['town'] ?? $addr['village'] ?? $addr['county'] ?? '';
                $country = $addr['country'] ?? '';
                $display = $city ? "{$city}, {$country}" : $item['display_name'];
                return ['label' => $display, 'lat' => $item['lat'], 'lng' => $item['lon']];
            })->unique('label')->values()->toArray();
        });

        return response()->json($results);
    }
}