<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
                $categories = collect([
            (object)['name' => 'Electronics', 'slug' => 'electronics', 'icon' => 'fa-laptop'],
            (object)['name' => 'Fashion', 'slug' => 'fashion', 'icon' => 'fa-fashion'],
            (object)['name' => 'Furniture', 'slug' => 'furniture', 'icon' => 'fa-couch'],
            (object)['name' => 'Clothing', 'slug' => 'clothing', 'icon' => 'fa-tshirt'],
            (object)['name' => 'Books', 'slug' => 'books', 'icon' => 'fa-book'],
            (object)['name' => 'Sports', 'slug' => 'sports', 'icon' => 'fa-basketball-ball'],
            (object)['name' => 'Gaming', 'slug' => 'gaming', 'icon' => 'fa-gamepad'],
            (object)['name' => 'Mobiles', 'slug' => 'mobiles', 'icon' => 'fa-mobile-alt'],
            (object)['name' => 'Home & Garden', 'slug' => 'home-garden', 'icon' => 'fa-leaf'],
            (object)['name' => 'Toys', 'slug' => 'toys', 'icon' => 'fa-puzzle-piece'],
            (object)['name' => 'Vehicles', 'slug' => 'vehicles', 'icon' => 'fa-car'],
            (object)['name' => 'Music', 'slug' => 'music', 'icon' => 'fa-guitar'],
            (object)['name' => 'Art', 'slug' => 'art', 'icon' => 'fa-palette'],
            (object)['name' => 'Beauty', 'slug' => 'beauty', 'icon' => 'fa-spa'],
            (object)['name' => 'Pets', 'slug' => 'pets', 'icon' => 'fa-paw'],
            (object)['name' => 'Office', 'slug' => 'office', 'icon' => 'fa-pen'],
            (object)['name' => 'Baby', 'slug' => 'baby', 'icon' => 'fa-baby-carriage'],
            (object)['name' => 'Tools', 'slug' => 'tools', 'icon' => 'fa-tools'],
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
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        // Increased max size to 5MB (5120KB) to match the PHP settings
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', 
    ]);

    $imagePaths = [];

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            // Ensure the file is valid before attempting storage
            if ($image->isValid()) {
                $imagePaths[] = $image->store('images', 'public');
            }
        }
    }
    
    Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'user_id' => Auth::id(),
        'hide' => 0,
        'image_paths' => json_encode($imagePaths), // Stores ["images/filename.jpg", ...]
        'category' => $request->category,
        'condition' => $request->condition,
        'location' => $request->location,
    ]);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}

     public function edit(Request $request)
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
        return view('products.show', compact('product','products'));
    }

    public function removeImage(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'image_path' => 'required|string'
        ]);

        $imagePath = $request->input('image_path');
        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        // Remove image path from the product record
        $images = json_decode($product->image_paths, true);
        $images = array_filter($images, fn($path) => $path !== $imagePath);
        $product->image_paths = json_encode($images);
        $product->save();

        return response()->json(['success' => true]);
    }
    public function showSellerItems($id)
    {
        $seller = User::findOrFail($id);
        $items = Product::where('user_id', $id)->get();

        return view('seller.items', compact('seller', 'items'));
    }

public function update(Request $request, $id)
{
    dd($request->all());
    $product = Product::findOrFail($id);

    // 1. Authorization
    if (Auth::id() !== $product->user_id) {
        abort(403, 'Unauthorized action.');
    }

    // 2. Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'nullable|numeric',
        'category' => 'required|string',
        'condition' => 'required|string',
        'location' => 'required|string',
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        'existing_images' => 'nullable|array', 
    ]);

    // 3. Handle Images

    // A. GET RETAINED IMAGES (Fixes the overwrite issue)
    // We get the list from the FORM input, not the database.
    // This ensures that if you clicked "Remove" in the UI, it is actually removed here.
   $retainedImages = $request->input('existing_images', []);

    // B. HANDLE NEW UPLOADS
    $newImages = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            if ($image->isValid()) {
                $newImages[] = $image->store('images', 'public');
            }
        }
    }

    // C. MERGE
    $finalImages = array_merge($retainedImages, $newImages);

    // 4. Update Database
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'category' => $request->category,
        'condition' => $request->condition,
        'location' => $request->location,
        // Use array_values to ensure it saves as a JSON array ["a","b"] not an object {"0":"a"}
        'image_paths' => json_encode(array_values($finalImages)),
    ]);

    return redirect()->route('products.show', $product->id)
                     ->with('success', 'Product updated successfully.');
}
    public function storeReview(Request $request, $id)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $product = Product::findOrFail($id);

    // 1. Check if user is trying to review their own product
    if (Auth::id() == $product->user_id) {
        return back()->with('error', 'You cannot review your own product.');
    }

    // 2. Check if user already reviewed this product
    $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                        ->where('product_id', $id)
                                        ->first();

    if ($existingReview) {
        return back()->with('error', 'You have already reviewed this product.');
    }

    // 3. Create Review
    \App\Models\Review::create([
        'user_id' => Auth::id(),
        'product_id' => $id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    // Redirect back with a hash so the page scrolls down to reviews
    return redirect()->to(url()->previous() . '#reviews')->with('success', 'Review submitted successfully!');
}
}