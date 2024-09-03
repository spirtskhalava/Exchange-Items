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
                'category' => $request->category,
                'condition' => $request->condition,
                'location' => $request->location,
            ]);

            $product->save();

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
        return view('products.show', compact('product'));
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
}