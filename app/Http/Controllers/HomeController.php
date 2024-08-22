<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
          
         // $products = Product::orderBy('views', 'desc')->paginate(9);
          $products = Product::where('hide', 0)
          ->orderBy('views', 'desc')
          ->paginate(9);
          return view('home', compact('products'));
    }
}
