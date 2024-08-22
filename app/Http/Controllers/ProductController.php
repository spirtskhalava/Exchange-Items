<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // $products = Product::all();
        // return view('products.index', compact('products'));
         $query = Product::query();

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('description', 'like', '%' . $request->input('search') . '%');
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Apply condition filter
        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        // Fetch the filtered products with pagination
        $products = $query->paginate(9);

        return view('products.index', compact('products'));
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
                'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->store('images', 'public');
                }
            }
            
            $product = new Product([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => Auth::id(),
                'image_paths' => json_encode($imagePaths),
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
        $product->increment('views');
        return view('products.show', compact('product'));
    }
}