<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Exchange;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('hide', 0)
            ->orderBy('views', 'desc')
            ->take(6)
            ->get();

        $deals = Product::where('hide', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Cache stats for 10 minutes so every page load doesn't hit the DB
        $stats = Cache::remember('home.stats', 600, function () {
            return [
                'items'   => Product::where('hide', 0)->count(),
                'trades'  => Exchange::where('status', 'accepted')->count(),
                'members' => User::where('status', 'active')->count(),
            ];
        });

        return view('home', compact('products', 'deals', 'stats'));
    }
}
