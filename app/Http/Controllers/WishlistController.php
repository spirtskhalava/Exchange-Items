<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product')->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function store($productId)
    {
        $product = Product::findOrFail($productId);

        $wishlist = new Wishlist();
        $wishlist->user_id = Auth::id();
        $wishlist->product_id = $product->id;
        $wishlist->save();

        return redirect()->back()->with('success', 'Product added to wishlist!');
    }

    public function destroy($id)
    {
        $wishlistItem = Wishlist::findOrFail($id);
        $wishlistItem->delete();

        return redirect()->back()->with('success', 'Product removed from wishlist!');
    }
}
