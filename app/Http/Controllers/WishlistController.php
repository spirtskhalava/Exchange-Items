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

    // UPDATED STORE METHOD
    public function store(Request $request, $productId)
    {
        $user = Auth::user();
        
        // 1. Check if it already exists
        $existingWishlist = Wishlist::where('user_id', $user->id)
                                    ->where('product_id', $productId)
                                    ->first();

        if ($existingWishlist) {
            // REMOVE IT (Toggle Off)
            $existingWishlist->delete();
            $status = 'removed';
            $message = 'Product removed from wishlist!';
        } else {
            // ADD IT (Toggle On)
            $wishlist = new Wishlist();
            $wishlist->user_id = $user->id;
            $wishlist->product_id = $productId;
            $wishlist->save();
            $status = 'added';
            $message = 'Product added to wishlist!';
        }

        // 2. Return JSON if the request is from JavaScript (Heart Icon)
        if ($request->wantsJson()) {
            return response()->json(['status' => $status]);
        }

        // 3. Fallback for normal page requests
        return redirect()->back()->with('success', $message);
    }

    public function destroy($id)
    {
        $wishlistItem = Wishlist::findOrFail($id);
        $wishlistItem->delete();
        return redirect()->back()->with('success', 'Product removed from wishlist!');
    }
}