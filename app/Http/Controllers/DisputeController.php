<?php
namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\InsuranceDispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    public function create(Exchange $exchange)
    {
        $this->authorizeParty($exchange);

        $insurance = $exchange->insurance;
        if (!$insurance || $insurance->escrow_status !== 'locked') {
            return redirect()->route('offers.index')->with('error', 'You can only open a dispute when escrow is locked.');
        }

        if ($insurance->dispute) {
            return redirect()->route('offers.index')->with('error', 'A dispute is already open for this exchange.');
        }

        return view('insurance.dispute_form', compact('exchange', 'insurance'));
    }

    public function store(Request $request, Exchange $exchange)
    {
        $this->authorizeParty($exchange);

        $request->validate([
            'description' => 'required|string|min:20|max:2000',
            'evidence'    => 'nullable|array|max:5',
            'evidence.*'  => 'file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
        ]);

        $insurance = $exchange->insurance;
        if (!$insurance || $insurance->escrow_status !== 'locked') {
            return back()->with('error', 'Escrow is not locked.');
        }

        if ($insurance->dispute) {
            return back()->with('error', 'A dispute already exists for this exchange.');
        }

        $evidencePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $evidencePaths[] = $file->store('disputes', 'public');
            }
        }

        InsuranceDispute::create([
            'exchange_id'    => $exchange->id,
            'filed_by'       => Auth::id(),
            'description'    => $request->description,
            'evidence_paths' => $evidencePaths,
            'status'         => 'pending',
        ]);

        $insurance->escrow_status = 'disputed';
        $insurance->save();

        return redirect()->route('offers.index')
            ->with('success', 'Dispute filed. Admin will review and resolve the case.');
    }

    public function adminIndex()
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $disputes = InsuranceDispute::with([
            'exchange.requester', 'exchange.responder',
            'exchange.requestedProduct', 'exchange.offeredProduct',
            'exchange.insurance', 'filer',
        ])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.disputes', compact('disputes'));
    }

    public function adminResolve(Request $request, InsuranceDispute $dispute)
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $request->validate([
            'resolution'  => 'required|in:resolved_filer,resolved_other,dismissed',
            'admin_notes' => 'required|string|min:10|max:2000',
        ]);

        $dispute->status      = $request->resolution;
        $dispute->admin_notes = $request->admin_notes;
        $dispute->resolved_by = Auth::id();
        $dispute->save();

        $insurance = $dispute->exchange->insurance;
        if ($insurance) {
            $insurance->escrow_status = 'released';
            $insurance->save();
            // Production: process PayPal transfers based on resolution here
        }

        return back()->with('success', 'Dispute resolved.');
    }

    private function authorizeParty(Exchange $exchange): void
    {
        $userId = Auth::id();
        if ($userId !== $exchange->requester_id && $userId !== $exchange->responder_id) {
            abort(403);
        }
    }
}
