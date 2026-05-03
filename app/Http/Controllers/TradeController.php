<?php
namespace App\Http\Controllers;

use App\Models\Exchange;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    public function index()
    {
        $me = Auth::id();

        $trades = Exchange::where('status', 'accepted')
            ->where(function ($q) use ($me) {
                $q->where('requester_id', $me)->orWhere('responder_id', $me);
            })
            ->with(['requester', 'responder', 'requestedProduct', 'offeredProduct'])
            ->latest('updated_at')
            ->paginate(10);

        return view('trades.index', compact('trades'));
    }
}
