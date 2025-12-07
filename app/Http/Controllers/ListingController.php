<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function index()
    {
        
    if (!Auth::check()) {
        return redirect()
            ->route('login')
            ->with('error', 'Please log in to offer an exchange.');
    }

    // Total count for header
    $totalProducts = Auth::user()
        ->products()
        ->where('hide', 0) // optional, if you use hide
        ->count();

    // Paginated products for current page
    $products = Auth::user()
        ->products()
        ->where('hide', 0) // optional
        ->latest()         // or orderBy('created_at', 'desc')
        ->paginate(9);     // 9 per page

    return view('listings.index', compact('products', 'totalProducts'));
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        Auth::user()->products()->create($request->all());

        return redirect()->route('listings.index')->with('success', 'Product listed successfully.');
    }

    public function edit(int $id)
    {
         if (!Auth::check()) {
            return redirect()->route('login');
        }
        $product = Product::findOrFail($id);
        return view('listings.edit', compact('product'));
    }

    // public function update(Request $request, Product $product)
    // {
    //     $this->authorize('update', $product);

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'required|string|max:1000',
    //         'category' => 'required|string|max:255',
    //         'condition' => 'required|string|max:255',
    //         'new_images.*' => 'image|mimes:jpg,jpeg,png|max:5120', // match form input name
    //     ]);

    //     // Update basic fields
    //     $product->update([
    //         'name' => $request->input('name'),
    //         'description' => $request->input('description'),
    //         'category' => $request->input('category'),
    //         'condition' => $request->input('condition'),
    //     ]);

    //     // Handle images
    //     $existingImages = $request->input('existing_images', []); // keep only those not removed
    //     $currentImages = json_decode($product->image_paths, true) ?? [];

    //     // Filter only those kept in the form
    //     $keptImages = array_intersect($currentImages, $existingImages);

    //     // Add new uploaded images
    //     if ($request->hasFile('new_images')) {
    //         foreach ($request->file('new_images') as $image) {
    //             $path = $image->store('images', 'public');
    //             $keptImages[] = '/storage/' . $path;
    //         }
    //     }

    //     // Enforce max 5 images
    //     $keptImages = array_slice($keptImages, 0, 5);

    //     $product->image_paths = json_encode($keptImages);
    //     $product->save();

    //     return redirect()->route('listings.index')->with('success', 'Product updated successfully.');
    // }


        public function update(Request $request, $id)
    {
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

    public function destroy(Product $product)
    {
        //dd($product);
         $product->delete();

        return redirect()->route('listings.index')->with('success', 'Product deleted successfully.');
    }
}
