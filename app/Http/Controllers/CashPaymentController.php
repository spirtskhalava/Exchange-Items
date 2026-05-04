<?php
namespace App\Http\Controllers;

use App\Models\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class CashPaymentController extends Controller
{
    /* ─── PayPal path ──────────────────────────────────────────── */

    /** Create PayPal order and redirect */
    public function create(Exchange $exchange)
    {
        $this->authorizeRequester($exchange);
        $this->guardCashOffer($exchange);

        if ($exchange->cash_payment_captured) {
            return back()->with('error', 'Cash top-up already paid.');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $itemName = $exchange->requestedProduct->name ?? 'Item';

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'description' => "Bartaro cash top-up for: {$itemName}",
                'amount' => [
                    'currency_code' => 'USD',
                    'value'         => number_format($exchange->money_offer, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url' => route('cash-payment.success', $exchange),
                'cancel_url' => route('offers.index'),
            ],
        ]);

        if (!isset($order['id'])) {
            return back()->with('error', 'Could not create PayPal order. Please try again.');
        }

        $exchange->cash_paypal_order_id  = $order['id'];
        $exchange->cash_payment_method   = 'paypal';
        $exchange->cash_payment_captured = false;
        $exchange->save();

        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect($link['href']);
            }
        }

        return back()->with('error', 'Could not redirect to PayPal. Please try again.');
    }

    /** PayPal redirects here after approval */
    public function success(Request $request, Exchange $exchange)
    {
        $this->authorizeRequester($exchange);

        if (!$exchange->cash_paypal_order_id) {
            return redirect()->route('offers.index')->with('error', 'No PayPal order found.');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $result = $provider->capturePaymentOrder($exchange->cash_paypal_order_id);

        if (!isset($result['status']) || $result['status'] !== 'COMPLETED') {
            return redirect()->route('offers.index')
                ->with('error', 'PayPal payment could not be confirmed. Please try again.');
        }

        $exchange->cash_payment_captured = true;
        $exchange->save();

        return redirect()->route('offers.index')
            ->with('success', 'Cash top-up of $' . number_format($exchange->money_offer, 2) . ' paid via PayPal ✓');
    }

    /* ─── Physical cash path ───────────────────────────────────── */

    /**
     * Requester declares they will pay in cash in person.
     * Sets method = 'cash', notifies responder to confirm.
     */
    public function chooseCash(Exchange $exchange)
    {
        $this->authorizeRequester($exchange);
        $this->guardCashOffer($exchange);

        if ($exchange->cash_payment_captured) {
            return back()->with('error', 'Cash top-up already confirmed.');
        }

        $exchange->cash_payment_method   = 'cash';
        $exchange->cash_payment_captured = false;
        $exchange->save();

        return back()->with('success', 'Marked as cash payment. The other party must confirm receipt.');
    }

    /**
     * Responder confirms they received the cash in person.
     */
    public function confirmCash(Exchange $exchange)
    {
        $this->authorizeResponder($exchange);

        if ($exchange->cash_payment_method !== 'cash') {
            return back()->with('error', 'This exchange is not set to cash payment.');
        }

        if ($exchange->cash_payment_captured) {
            return back()->with('error', 'Cash already confirmed.');
        }

        $exchange->cash_payment_captured = true;
        $exchange->save();

        return back()->with('success', 'Cash payment of $' . number_format($exchange->money_offer, 2) . ' confirmed ✓');
    }

    /* ─── Helpers ──────────────────────────────────────────────── */

    private function guardCashOffer(Exchange $exchange): void
    {
        if ($exchange->status !== 'accepted') {
            abort(403, 'Exchange must be accepted first.');
        }
        if (!$exchange->money_offer || $exchange->money_offer <= 0) {
            abort(403, 'No cash top-up on this exchange.');
        }
    }

    private function authorizeRequester(Exchange $exchange): void
    {
        if (Auth::id() !== $exchange->requester_id) abort(403);
    }

    private function authorizeResponder(Exchange $exchange): void
    {
        if (Auth::id() !== $exchange->responder_id) abort(403);
    }
}
