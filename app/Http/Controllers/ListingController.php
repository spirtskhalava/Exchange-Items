<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function index()
    {
        

         if (Auth::check()) {
            $products = Auth::user()->products;
            return view('listings.index', compact('products'));
        } else {
            return redirect()->route('login')->with('error', 'Please log in to offer an exchange.');
        }
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

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'new_images.*' => 'image|mimes:jpg,jpeg,png|max:5120', // match form input name
        ]);

        // Update basic fields
        $product->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
            'condition' => $request->input('condition'),
        ]);

        // Handle images
        $existingImages = $request->input('existing_images', []); // keep only those not removed
        $currentImages = json_decode($product->image_paths, true) ?? [];

        // Filter only those kept in the form
        $keptImages = array_intersect($currentImages, $existingImages);

        // Add new uploaded images
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('images', 'public');
                $keptImages[] = '/storage/' . $path;
            }
        }

        // Enforce max 5 images
        $keptImages = array_slice($keptImages, 0, 5);

        $product->image_paths = json_encode($keptImages);
        $product->save();

        return redirect()->route('listings.index')->with('success', 'Product updated successfully.');
    }


    public function destroy(Product $product)
    {
        //dd($product);
         $product->delete();

        return redirect()->route('listings.index')->with('success', 'Product deleted successfully.');
    }
}
