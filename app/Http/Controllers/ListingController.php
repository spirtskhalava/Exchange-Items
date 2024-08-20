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
        $products = Product::where('id',$id)->get();
        return view('listings.edit', ['products' => $products]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $product->update($request->all());

        return redirect()->route('listings.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        //dd($product);
         $product->delete();

        return redirect()->route('listings.index')->with('success', 'Product deleted successfully.');
    }
}
