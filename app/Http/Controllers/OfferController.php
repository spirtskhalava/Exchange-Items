<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Exchange;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $receivedOffers = Exchange::where('responder_id', Auth::id())->get();
            $sentOffers = Exchange::where('requester_id', Auth::id())->get();
            
            return view('offers.index', compact('receivedOffers', 'sentOffers'));
        } else {
            return redirect()->route('login')->with('error', 'Please log in to offer an exchange.');
        }
       
    }

    public function accept(Exchange $offer)
    {
        DB::transaction(function () use ($offer) {
            // Mark the selected offer as accepted
            $offer->status = 'accepted';
            $offer->save();
    
            // Cancel all other pending offers for the same requested product
            Exchange::where('requested_product_id', $offer->requested_product_id)
                ->where('id', '!=', $offer->id) // Exclude the currently accepted offer
                ->where('status', 'pending')
                ->update(['status' => 'canceled']);
        });
    
        return redirect()->route('offers.index')->with('success', 'Offer accepted and all other pending offers for this product canceled.');
    }

    public function decline(Exchange $offer)
    {
        $offer->status = 'declined';
        $offer->save();

        return redirect()->route('offers.index')->with('success', 'Offer declined successfully.');
    }
    
}