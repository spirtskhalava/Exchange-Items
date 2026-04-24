<?php
namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\ExchangeInsurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class InsuranceController extends Controller
{
    // Opt into insurance for an accepted exchange
    public function optIn(Exchange $exchange)
    {
        $this->authorizeParty($exchange);

        if ($exchange->status !== 'accepted') {
            return back()->with('error', 'Insurance is only available for accepted exchanges.');
        }

        $insurance = ExchangeInsurance::firstOrCreate(
            ['exchange_id' => $exchange->id],
            ['escrow_status' => 'none']
        );

        $userId = Auth::id();

        if ($userId === $exchange->requester_id) {
            if ($insurance->req_opted) {
                return back()->with('error', 'You already opted in.');
            }
            $insurance->req_opted = true;
        } else {
            if ($insurance->resp_opted) {
                return back()->with('error', 'You already opted in.');
            }
            $insurance->resp_opted = true;
        }

        if ($insurance->bothOpted() && $insurance->escrow_status === 'none') {
            $insurance->escrow_status = 'negotiating';
        }

        $insurance->save();

        return back()->with('success', 'Insurance opted in! Set the value of your item to begin negotiations.');
    }

    // Submit or counter a valuation for your own item
    public function submitValuation(Request $request, Exchange $exchange)
    {
        $this->authorizeParty($exchange);

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:999999',
        ]);

        $insurance = $exchange->insurance;

        if (!$insurance || !$insurance->bothOpted()) {
            return back()->with('error', 'Both parties must opt in before submitting valuations.');
        }

        if (in_array($insurance->escrow_status, ['locked', 'released', 'disputed'])) {
            return back()->with('error', 'Valuations are already finalised.');
        }

        $userId = Auth::id();
        $amount = (float) $request->amount;

        if ($userId === $exchange->requester_id) {
            $insurance->req_item_value       = $amount;
            $insurance->req_item_proposed_by = 'requester';
            $insurance->req_item_agreed      = false;
        } else {
            $insurance->resp_item_value       = $amount;
            $insurance->resp_item_proposed_by = 'responder';
            $insurance->resp_item_agreed      = false;
        }

        $insurance->escrow_status = 'negotiating';
        $insurance->save();

        return back()->with('success', 'Valuation submitted. Waiting for the other party to respond.');
    }

    // Respond to the other party's valuation: accept / counter / reject
    public function respondValuation(Request $request, Exchange $exchange)
    {
        $this->authorizeParty($exchange);

        $request->validate([
            'item'   => 'required|in:req,resp',
            'action' => 'required|in:accept,counter,reject',
            'amount' => 'nullable|numeric|min:0.01|max:999999',
        ]);

        $insurance = $exchange->insurance;

        if (!$insurance || !in_array($insurance->escrow_status, ['negotiating'])) {
            return back()->with('error', 'Nothing to respond to.');
        }

        $userId  = Auth::id();
        $item    = $request->item;
        $action  = $request->action;
        $myRole  = ($userId === $exchange->requester_id) ? 'requester' : 'responder';

        $proposerField = $item . '_item_proposed_by';
        if ($insurance->$proposerField === $myRole) {
            return back()->with('error', 'You proposed this valuation — wait for the other party to respond.');
        }

        if ($action === 'accept') {
            $insurance->{$item . '_item_agreed'} = true;
        } elseif ($action === 'counter') {
            $request->validate(['amount' => 'required|numeric|min:0.01']);
            $insurance->{$item . '_item_value'}       = (float) $request->amount;
            $insurance->{$item . '_item_proposed_by'} = $myRole;
            $insurance->{$item . '_item_agreed'}      = false;
        } elseif ($action === 'reject') {
            $insurance->req_item_value       = null;
            $insurance->req_item_proposed_by = null;
            $insurance->req_item_agreed      = false;
            $insurance->resp_item_value      = null;
            $insurance->resp_item_proposed_by= null;
            $insurance->resp_item_agreed     = false;
            $insurance->save();
            return back()->with('success', 'Valuation rejected. Both parties can re-submit valuations.');
        }

        // Both valuations agreed → move to payment stage
        if ($insurance->req_item_agreed && $insurance->resp_item_agreed) {
            $insurance->escrow_status = 'pending_payment';
        }

        $insurance->save();

        return back()->with('success', $action === 'accept'
            ? 'Valuation accepted! Now complete your PayPal payment to lock escrow.'
            : 'Counter-offer submitted.');
    }

    // Create PayPal order for escrow payment (item value + $5 fee)
    public function createPayment(Exchange $exchange)
    {
        $this->authorizeParty($exchange);

        $insurance = $exchange->insurance;

        if (!$insurance || $insurance->escrow_status !== 'pending_payment') {
            return back()->with('error', 'Payments are not ready yet — both valuations must be agreed first.');
        }

        $userId   = Auth::id();
        $isReq    = ($userId === $exchange->requester_id);
        $amount   = $isReq ? $insurance->requesterLockedAmount() : $insurance->responderLockedAmount();
        $myItem   = $isReq
            ? ($exchange->offeredProduct->name ?? 'Your item')
            : ($exchange->requestedProduct->name ?? 'Your item');

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token    = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'description' => "Insurance escrow for: {$myItem}",
                'amount'      => [
                    'currency_code' => 'USD',
                    'value'         => number_format($amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url' => route('insurance.paymentSuccess', $exchange),
                'cancel_url' => route('offers.index'),
            ],
        ]);

        if (isset($order['id'])) {
            // Save order ID
            if ($isReq) {
                $insurance->req_paypal_order_id = $order['id'];
            } else {
                $insurance->resp_paypal_order_id = $order['id'];
            }
            $insurance->save();

            // Redirect to PayPal approval URL
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect($link['href']);
                }
            }
        }

        return back()->with('error', 'Could not create PayPal order. Please try again.');
    }

    // PayPal redirects back here after approval
    public function paymentSuccess(Request $request, Exchange $exchange)
    {
        $this->authorizeParty($exchange);

        $insurance = $exchange->insurance;
        $userId    = Auth::id();
        $isReq     = ($userId === $exchange->requester_id);

        $orderId = $isReq ? $insurance->req_paypal_order_id : $insurance->resp_paypal_order_id;

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token    = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $result = $provider->capturePaymentOrder($orderId);

        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            // Check if both sides have paid
            $reqPaid  = $insurance->req_paypal_order_id && ($isReq || $this->isOrderCaptured($insurance->req_paypal_order_id, $provider));
            $respPaid = $insurance->resp_paypal_order_id && (!$isReq || $this->isOrderCaptured($insurance->resp_paypal_order_id, $provider));

            if ($reqPaid && $respPaid) {
                $insurance->escrow_status = 'locked';
            }

            $insurance->save();

            return redirect()->route('offers.index')
                ->with('success', 'Payment received! '
                    . ($insurance->escrow_status === 'locked'
                        ? 'Escrow is now locked. Both parties can confirm receipt.'
                        : 'Waiting for the other party to complete payment.'));
        }

        return redirect()->route('offers.index')->with('error', 'Payment could not be confirmed. Please try again.');
    }

    // Mark that you received your item and it's good
    public function markReceived(Exchange $exchange)
    {
        $this->authorizeParty($exchange);

        $insurance = $exchange->insurance;

        if (!$insurance || $insurance->escrow_status !== 'locked') {
            return back()->with('error', 'Escrow is not locked yet.');
        }

        $userId = Auth::id();

        if ($userId === $exchange->requester_id) {
            $insurance->req_received = true;
        } else {
            $insurance->resp_received = true;
        }

        if ($insurance->req_received && $insurance->resp_received) {
            $insurance->escrow_status = 'released';
            // In production: trigger PayPal refunds to both parties here
        }

        $insurance->save();

        return back()->with('success', $insurance->escrow_status === 'released'
            ? 'Both parties confirmed receipt. Escrow released — funds returned to both!'
            : 'Marked as received. Waiting for the other party to confirm.');
    }

    private function isOrderCaptured(string $orderId, PayPalClient $provider): bool
    {
        $details = $provider->showOrderDetails($orderId);
        return isset($details['status']) && $details['status'] === 'COMPLETED';
    }

    private function authorizeParty(Exchange $exchange): void
    {
        $userId = Auth::id();
        if ($userId !== $exchange->requester_id && $userId !== $exchange->responder_id) {
            abort(403);
        }
    }
}
