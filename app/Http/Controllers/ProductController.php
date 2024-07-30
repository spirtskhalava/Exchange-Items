<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $product = new Product([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

     public function edit(Request $request)
    {
        dd($request->id);
        if (!Auth::check()) {
            return redirect()->route('login');
        }

       return view('products.edit_listing', compact('product'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}