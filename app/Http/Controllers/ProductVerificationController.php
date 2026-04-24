<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVerification;
use App\Models\User;
use App\Notifications\ProductFlaggedAsScam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductVerificationController extends Controller
{
public function verify(Request $request, Product $product)
    {
        $request->validate(['verdict' => 'required|in:real,fake']);

        $verification = ProductVerification::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$verification) {
            return response()->json(['message' => 'No pending verification found.'], 404);
        }

        $verification->update(['status' => $request->verdict]);

        if ($request->verdict === 'fake') {
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $admin->notify(new ProductFlaggedAsScam($product, Auth::user()));
            }
        }

        return response()->json(['message' => 'Thank you for your verification!']);
    }
}