<?php
namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
    public function create(Product $product)
    {
        // Ensure user is authenticated
        if (Auth::check()) {
            // User is authenticated, proceed to fetch their products
            $userProducts = Auth::user()->products;
            return view('exchanges.create', compact('product', 'userProducts'));
        } else {
            // User is not authenticated, handle the scenario accordingly
            // For example, redirect to login page
            return redirect()->route('login')->with('error', 'Please log in to offer an exchange.');
        }
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'offered_product_id' => 'nullable|exists:products,id',
            'money_offer' => 'nullable|numeric|min:0',
        ]);

        if (is_null($request->offered_product_id) && is_null($request->money_offer)) {
            return redirect()->back()->withErrors(['offer' => 'You must offer either a product or money.']);
        }

        $exchange = new Exchange([
            'requester_id' => Auth::id(),
            'responder_id' => $product->user_id,
            'requested_product_id' => $product->id,
            'offered_product_id' => $request->offered_product_id,
            'status' => 'pending',
            'money_offer' => $request->money_offer,
        ]);
        $exchange->save();

        return redirect()->route('home');
    }

    public function index()
    {
        $exchanges = Exchange::where('responder_id', Auth::id())->get();
        return view('exchanges.index', compact('exchanges'));
    }

    public function updateStatus(Request $request, Exchange $exchange)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);

        $exchange->status = $request->status;
        $exchange->save();

        return redirect()->route('exchanges.index')->with('success', 'Exchange status updated successfully.');
    }


    public function cancel(Exchange $exchange)
    {
        // Ensure the user is authorized to cancel this offer
        if (Auth::id() !== $exchange->requester_id) {
            return redirect()->route('offers.index')->with('error', 'You are not authorized to cancel this offer.');
        }

        // Delete the exchange offer
        $exchange->delete();

        // Redirect back with success message
        return redirect()->route('offers.index')->with('success', 'Offer cancelled successfully.');
    }
}