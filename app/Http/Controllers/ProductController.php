<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductVerification;
use App\Notifications\ProductVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = collect([
            (object)['name' => 'Electronics',  'slug' => 'electronics',  'icon' => 'fa-laptop'],
            (object)['name' => 'Fashion',       'slug' => 'fashion',      'icon' => 'fa-fashion'],
            (object)['name' => 'Furniture',     'slug' => 'furniture',    'icon' => 'fa-couch'],
            (object)['name' => 'Clothing',      'slug' => 'clothing',     'icon' => 'fa-tshirt'],
            (object)['name' => 'Books',         'slug' => 'books',        'icon' => 'fa-book'],
            (object)['name' => 'Sports',        'slug' => 'sports',       'icon' => 'fa-basketball-ball'],
            (object)['name' => 'Gaming',        'slug' => 'gaming',       'icon' => 'fa-gamepad'],
            (object)['name' => 'Mobiles',       'slug' => 'mobiles',      'icon' => 'fa-mobile-alt'],
            (object)['name' => 'Home & Garden', 'slug' => 'home-garden',  'icon' => 'fa-leaf'],
            (object)['name' => 'Toys',          'slug' => 'toys',         'icon' => 'fa-puzzle-piece'],
            (object)['name' => 'Vehicles',      'slug' => 'vehicles',     'icon' => 'fa-car'],
            (object)['name' => 'Music',         'slug' => 'music',        'icon' => 'fa-guitar'],
            (object)['name' => 'Art',           'slug' => 'art',          'icon' => 'fa-palette'],
            (object)['name' => 'Beauty',        'slug' => 'beauty',       'icon' => 'fa-spa'],
            (object)['name' => 'Pets',          'slug' => 'pets',         'icon' => 'fa-paw'],
            (object)['name' => 'Office',        'slug' => 'office',       'icon' => 'fa-pen'],
            (object)['name' => 'Baby',          'slug' => 'baby',         'icon' => 'fa-baby-carriage'],
            (object)['name' => 'Tools',         'slug' => 'tools',        'icon' => 'fa-tools'],
        ]);

        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('description', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        $query->where('hide', '=', 0);
        $products = $query->paginate(9);

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        if (Auth::check()) {
            return view('products.create');
        } else {
            return redirect()->route('login')->with('error', 'Please log in to offer an exchange.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imagePaths[] = $image->store('images', 'public');
                }
            }
        }

        // FIX: Capture the returned model so $product is defined below
        $product = Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'user_id'     => Auth::id(),
            'hide'        => 0,
            'image_paths' => json_encode($imagePaths),
            'category'    => $request->category,
            'condition'   => $request->condition,
            'location'    => $request->location,
        ]);

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
            'description'     => 'nullable|string',
            'price'           => 'nullable|numeric',
            'category'        => 'required|string',
            'condition'       => 'required|string',
            'location'        => 'required|string',
            'images.*'        => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'existing_images' => 'nullable|array',
        ]);

        // Retained images sent from the form
        $retainedImages = $request->input('existing_images', []);

        // Handle new uploads
        $newImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $newImages[] = $image->store('images', 'public');
                }
            }
        }

        // Merge retained + new
        $finalImages = array_values(array_merge($retainedImages, $newImages));

        $product->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
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
}