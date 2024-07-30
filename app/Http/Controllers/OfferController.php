<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Exchange;

class OfferController extends Controller
{
    public function index()
    {
        $receivedOffers = Exchange::where('responder_id', Auth::id())->get();
        $sentOffers = Exchange::where('requester_id', Auth::id())->get();
        
        return view('offers.index', compact('receivedOffers', 'sentOffers'));
    }

    public function accept(Exchange $offer)
    {
        $offer->status = 'accepted';
        $offer->save();

        return redirect()->route('offers.index')->with('success', 'Offer accepted successfully.');
    }

    public function decline(Exchange $offer)
    {
        $offer->status = 'declined';
        $offer->save();

        return redirect()->route('offers.index')->with('success', 'Offer declined successfully.');
    }
}