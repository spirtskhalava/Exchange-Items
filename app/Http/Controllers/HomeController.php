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
          // Fetch products ordered by views in descending order
          $products = Product::orderBy('views', 'desc')->paginate(9); // Adjust pagination as needed

          // Pass products to the view
          return view('home', compact('products'));
    }
}
