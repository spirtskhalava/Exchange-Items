<?php
namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Product;
use App\Notifications\ExchangeStatusChanged;
use App\Notifications\NewExchangeOffer;
use App\Notifications\CancelRequested;
use App\Notifications\CancelDecided;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
    public function create(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to offer an exchange.');
        }
        $userProducts = Auth::user()->products;
        return view('exchanges.create', compact('product', 'userProducts'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'offered_product_id' => 'nullable|exists:products,id',
            'money_offer'        => 'nullable|numeric|min:0',
        ]);

        if (is_null($request->offered_product_id) && is_null($request->money_offer)) {
            return redirect()->back()->withErrors(['offer' => 'You must offer either a product or money.']);
        }

        // ── Feature: preferred offer category / subcategory auto-reject ──
        if ($product->preferred_offer_category && $request->offered_product_id) {
            $offeredProduct = Product::find($request->offered_product_id);

            if ($offeredProduct) {
                $wantedCat    = $product->preferred_offer_category;
                $wantedSub    = $product->preferred_offer_sub_category;
                $catLabel     = config("categories.{$wantedCat}.label") ?? $wantedCat;
                $subLabel     = $wantedSub
                    ? (config("categories.{$wantedCat}.subs.{$wantedSub}") ?? $wantedSub)
                    : null;

                // Category mismatch
                if ($offeredProduct->category !== $wantedCat) {
                    $required = $subLabel ? "\"{$subLabel}\" (under {$catLabel})" : "\"{$catLabel}\"";
                    return redirect()->back()->with(
                        'error',
                        "The owner only accepts offers from the {$required} category. Your offer was not sent."
                    );
                }

                // Subcategory mismatch (only if owner specified one)
                if ($wantedSub && $offeredProduct->sub_category !== $wantedSub) {
                    return redirect()->back()->with(
                        'error',
                        "The owner only accepts offers specifically from \"{$subLabel}\" (under {$catLabel}). Your offer was not sent."
                    );
                }
            }
        }

        $exchange = Exchange::create([
            'requester_id'         => Auth::id(),
            'responder_id'         => $product->user_id,
            'requested_product_id' => $product->id,
            'offered_product_id'   => $request->offered_product_id,
            'status'               => 'pending',
            'money_offer'          => $request->money_offer,
        ]);

        $exchange->load('requester', 'requestedProduct');
        $exchange->responder->notify(new NewExchangeOffer($exchange));

        return redirect()->route('home')->with('success', 'Offer sent successfully!');
    }

    public function index()
    {
        $exchanges = Exchange::where('responder_id', Auth::id())->get();
        return view('exchanges.index', compact('exchanges'));
    }

    public function updateStatus(Request $request, Exchange $exchange)
    {
        $request->validate(['status' => 'required|in:accepted,declined']);

        $exchange->status = $request->status;
        $exchange->save();

        $exchange->load('requester', 'responder', 'requestedProduct');
        $exchange->requester->notify(new ExchangeStatusChanged($exchange));

        return redirect()->route('exchanges.index')->with('success', 'Exchange status updated.');
    }

    /* ── Cancellation flow ───────────────────────────────────── */

    /**
     * Requester submits a cancellation request with a reason.
     * The offer is NOT deleted yet — responder must approve.
     */
    public function requestCancel(Request $request, Exchange $exchange)
    {
        if (Auth::id() !== $exchange->requester_id) {
            abort(403);
        }

        if ($exchange->status !== 'pending') {
            return back()->with('error', 'Only pending offers can be cancelled this way.');
        }

        if ($exchange->hasPendingCancelRequest()) {
            return back()->with('error', 'A cancellation request is already pending.');
        }

        $request->validate([
            'cancel_reason' => 'required|string|min:5|max:500',
        ]);

        $exchange->cancel_reason        = $request->cancel_reason;
        $exchange->cancel_requested_at  = now();
        $exchange->cancel_approved      = null; // pending
        $exchange->save();

        // Notify responder
        $exchange->load('requester', 'requestedProduct');
        $exchange->responder->notify(new CancelRequested($exchange));

        return back()->with('success', 'Cancellation request sent. Waiting for the other party to approve.');
    }

    /**
     * Responder approves the cancellation → exchange is deleted.
     */
    public function approveCancel(Exchange $exchange)
    {
        if (Auth::id() !== $exchange->responder_id) {
            abort(403);
        }

        if (!$exchange->hasPendingCancelRequest()) {
            return back()->with('error', 'No pending cancellation request found.');
        }

        // Notify requester before deleting
        $exchange->load('requester', 'requestedProduct');
        $exchange->requester->notify(new CancelDecided($exchange, true));

        $exchange->delete();

        return back()->with('success', 'Cancellation approved. The offer has been removed.');
    }

    /**
     * Responder rejects the cancellation → offer stays, reason cleared.
     */
    public function rejectCancel(Exchange $exchange)
    {
        if (Auth::id() !== $exchange->responder_id) {
            abort(403);
        }

        if (!$exchange->hasPendingCancelRequest()) {
            return back()->with('error', 'No pending cancellation request found.');
        }

        $exchange->load('requester', 'requestedProduct');
        $exchange->requester->notify(new CancelDecided($exchange, false));

        $exchange->cancel_reason       = null;
        $exchange->cancel_requested_at = null;
        $exchange->cancel_approved     = null;
        $exchange->save();

        return back()->with('success', 'Cancellation rejected. The offer remains active.');
    }
}
