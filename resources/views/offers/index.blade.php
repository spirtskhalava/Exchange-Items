@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">Trade Offers</h1>
        <p class="text-muted small mt-1 mb-0">Review incoming offers and track your sent offers</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 py-2 px-3" role="alert">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="row g-4">

        {{-- ============================
             INCOMING OFFERS
        ============================ --}}
        <div class="col-lg-6">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="fw-bold" style="font-size:.95rem;">Incoming</span>
                <span class="badge rounded-pill" style="background:rgba(79,70,229,.1);color:var(--p);font-size:.75rem;">{{ $receivedOffers->total() }}</span>
            </div>

            @forelse($receivedOffers as $offer)
            <div class="card mb-3 offer-card">
                <div class="card-body p-4">

                    {{-- Offer header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                                 style="width:34px;height:34px;font-size:.85rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));flex-shrink:0;">
                                {{ strtoupper(substr($offer->requester->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem;line-height:1.2;">{{ $offer->requester->name ?? 'Unknown' }}</div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $offer->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @php
                            $sc = match($offer->status) {
                                'accepted' => 'success',
                                'declined' => 'danger',
                                default    => 'warning',
                            };
                        @endphp
                        <span class="badge" style="background:rgba(var(--bs-{{ $sc }}-rgb),.1);color:var(--bs-{{ $sc }});border-radius:50rem;padding:.35rem .8rem;font-size:.72rem;">
                            {{ ucfirst($offer->status) }}
                        </span>
                    </div>

                    {{-- Trade visual --}}
                    <div class="d-flex align-items-stretch gap-2 mb-3">
                        <div class="flex-1 p-2 rounded-2 text-center" style="background:var(--bg);flex:1;">
                            <div class="text-muted" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.2rem;">You Give</div>
                            <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="font-size:.83rem;">{{ $offer->requestedProduct->name }}</a>
                        </div>
                        <div class="d-flex align-items-center px-1 text-muted" style="opacity:.35;font-size:1rem;">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div class="flex-1 p-2 rounded-2 text-center" style="background:var(--bg);flex:1;">
                            <div class="text-muted" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.2rem;">You Receive</div>
                            @if(isset($offer->offeredProduct->id))
                                <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="font-size:.83rem;">{{ $offer->offeredProduct->name }}</a>
                            @else
                                <span class="fw-semibold text-dark" style="font-size:.83rem;">Cash Only</span>
                            @endif
                        </div>
                    </div>

                    {{-- Cash top-up --}}
                    @if($offer->money_offer)
                        @if($offer->cash_payment_captured)
                        {{-- ✅ Payment confirmed --}}
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:rgba(6,214,160,.07);border:1px solid rgba(6,214,160,.2);">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:.9rem;"></i>
                            <span style="font-size:.82rem;color:#374151;">
                                Cash top-up <strong>${{ number_format($offer->money_offer, 2) }}</strong>
                                {{ $offer->cash_payment_method === 'cash' ? 'received in cash' : 'received via PayPal' }} ✓
                            </span>
                        </div>
                        @elseif($offer->status === 'accepted' && $offer->cash_payment_method === 'cash')
                        {{-- 💵 Cash chosen — responder must confirm receipt --}}
                        <div class="mb-3 p-3 rounded-3" style="background:#fffbeb;border:1px solid #fcd34d;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-cash-coin" style="color:#d97706;font-size:.95rem;"></i>
                                <span style="font-size:.82rem;font-weight:600;color:#92400e;">
                                    Expecting <strong>${{ number_format($offer->money_offer, 2) }}</strong> cash in person
                                </span>
                            </div>
                            <form method="POST" action="{{ route('cash-payment.confirm-cash', $offer) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm w-100 fw-semibold"
                                        style="background:#198754;color:#fff;border-radius:.55rem;font-size:.82rem;"
                                        onclick="return confirm('Confirm you received ${{ number_format($offer->money_offer, 2) }} in cash?')">
                                    <i class="bi bi-check-lg me-1"></i> Confirm Cash Received
                                </button>
                            </form>
                        </div>
                        @elseif($offer->status === 'accepted')
                        {{-- ⏳ Accepted — waiting for requester to choose / pay --}}
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:#fffbeb;border:1px solid #fcd34d;">
                            <i class="bi bi-clock-fill" style="color:#d97706;font-size:.9rem;"></i>
                            <span style="font-size:.82rem;color:#92400e;">
                                Waiting for <strong>${{ number_format($offer->money_offer, 2) }}</strong> cash top-up payment
                            </span>
                        </div>
                        @else
                        {{-- Pending offer, not accepted yet --}}
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:rgba(6,214,160,.07);border:1px solid rgba(6,214,160,.2);">
                            <i class="bi bi-cash-coin text-success" style="font-size:.9rem;"></i>
                            <span style="font-size:.82rem;color:#374151;">Includes <strong>${{ number_format($offer->money_offer, 2) }}</strong> cash top-up</span>
                        </div>
                        @endif
                    @endif

                    {{-- ── Cancellation request from requester ── --}}
                    @if($offer->hasPendingCancelRequest())
                    <div class="mb-3 p-3 rounded-2" style="background:#fff7ed;border:1px solid #fed7aa;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-exclamation-triangle-fill" style="color:#ea580c;font-size:.9rem;"></i>
                            <span style="font-size:.82rem;font-weight:600;color:#9a3412;">Cancellation Requested</span>
                        </div>
                        <p style="font-size:.8rem;color:#7c2d12;margin-bottom:.75rem;">
                            <strong>Reason:</strong> {{ $offer->cancel_reason }}
                        </p>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('exchanges.approveCancel', $offer) }}" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-sm w-100 fw-semibold"
                                        style="background:#16a34a;color:#fff;border-radius:.55rem;font-size:.8rem;"
                                        onclick="return confirm('Approve cancellation? The offer will be permanently removed.')">
                                    <i class="bi bi-check-lg me-1"></i> Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('exchanges.rejectCancel', $offer) }}" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-sm w-100 fw-semibold"
                                        style="background:#dc2626;color:#fff;border-radius:.55rem;font-size:.8rem;">
                                    <i class="bi bi-x-lg me-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- Actions (accept/decline) --}}
                    @if($offer->status === 'pending' && !$offer->hasPendingCancelRequest())
                    <div class="row g-2">
                        <div class="col-6">
                            <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="declined">
                                <button type="submit" class="btn btn-light w-100 btn-sm text-muted py-2" style="border-radius:.55rem;">Decline</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="btn btn-dark w-100 btn-sm py-2" style="border-radius:.55rem;">Accept</button>
                            </form>
                        </div>
                    </div>
                    @endif

                    @if($offer->status === 'accepted')
                        <div class="mt-3 pt-3 border-top">
                            @include('insurance._panel', ['offer' => $offer, 'myRole' => 'responder'])
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                    <p class="text-muted small mt-2 mb-0">No incoming offers yet</p>
                </div>
            </div>
            @endforelse

            @if($receivedOffers->hasPages())
            <div class="mt-2 d-flex justify-content-center">{{ $receivedOffers->withQueryString()->links() }}</div>
            @endif
        </div>

        {{-- ============================
             SENT OFFERS
        ============================ --}}
        <div class="col-lg-6">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="fw-bold" style="font-size:.95rem;">Sent</span>
                <span class="badge rounded-pill" style="background:rgba(79,70,229,.1);color:var(--p);font-size:.75rem;">{{ $sentOffers->total() }}</span>
            </div>

            @forelse($sentOffers as $offer)
            <div class="card mb-3 offer-card">
                <div class="card-body p-4">

                    {{-- Offer header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:34px;height:34px;background:var(--bg);flex-shrink:0;">
                                <i class="bi bi-arrow-up-right text-muted" style="font-size:.8rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem;line-height:1.2;">To: {{ $offer->responder->name ?? 'Unknown' }}</div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $offer->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @php
                            $sc = match($offer->status) {
                                'accepted' => 'success',
                                'declined' => 'danger',
                                default    => 'warning',
                            };
                        @endphp
                        <span class="badge" style="background:rgba(var(--bs-{{ $sc }}-rgb),.1);color:var(--bs-{{ $sc }});border-radius:50rem;padding:.35rem .8rem;font-size:.72rem;">
                            {{ ucfirst($offer->status) }}
                        </span>
                    </div>

                    {{-- Trade visual --}}
                    <div class="d-flex align-items-stretch gap-2 mb-3">
                        <div class="flex-1 p-2 rounded-2 text-center" style="background:var(--bg);flex:1;">
                            <div class="text-muted" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.2rem;">You Offer</div>
                            @if(isset($offer->offeredProduct->id))
                                <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="font-size:.83rem;">{{ $offer->offeredProduct->name }}</a>
                            @else
                                <span class="fw-semibold text-dark" style="font-size:.83rem;">Cash Only</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center px-1 text-muted" style="opacity:.35;font-size:1rem;">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        <div class="flex-1 p-2 rounded-2 text-center" style="background:var(--bg);flex:1;">
                            <div class="text-muted" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.2rem;">For</div>
                            <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="font-size:.83rem;">{{ $offer->requestedProduct->name }}</a>
                        </div>
                    </div>

                    {{-- Cash top-up --}}
                    @if($offer->money_offer)
                        @if($offer->cash_payment_captured)
                        {{-- ✅ Paid (any method) --}}
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:rgba(6,214,160,.07);border:1px solid rgba(6,214,160,.2);">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:.9rem;"></i>
                            <span style="font-size:.82rem;color:#374151;">
                                Cash top-up <strong>${{ number_format($offer->money_offer, 2) }}</strong>
                                {{ $offer->cash_payment_method === 'cash' ? 'paid in cash' : 'paid via PayPal' }} ✓
                            </span>
                        </div>
                        @elseif($offer->status === 'accepted' && $offer->cash_payment_method === 'cash')
                        {{-- 💵 Cash chosen — waiting for responder to confirm --}}
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:#fffbeb;border:1px solid #fcd34d;">
                            <i class="bi bi-clock-fill" style="color:#d97706;font-size:.9rem;"></i>
                            <span style="font-size:.82rem;color:#92400e;">
                                Cash payment of <strong>${{ number_format($offer->money_offer, 2) }}</strong> marked — waiting for the other party to confirm receipt.
                            </span>
                        </div>
                        @elseif($offer->status === 'accepted' && $offer->cash_payment_method === 'paypal')
                        {{-- 💳 PayPal chosen — redirect to PayPal --}}
                        <div class="mb-3 p-3 rounded-3" style="background:#fffbeb;border:1px solid #fcd34d;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-exclamation-circle-fill" style="color:#d97706;font-size:.95rem;"></i>
                                <span style="font-size:.82rem;font-weight:600;color:#92400e;">
                                    Cash top-up of <strong>${{ number_format($offer->money_offer, 2) }}</strong> pending payment
                                </span>
                            </div>
                            <a href="{{ route('cash-payment.create', $offer) }}"
                               class="btn btn-sm w-100 fw-semibold"
                               style="background:#0070ba;color:#fff;border-radius:.55rem;font-size:.82rem;">
                                <i class="bi bi-paypal me-1"></i> Pay ${{ number_format($offer->money_offer, 2) }} via PayPal
                            </a>
                        </div>
                        @elseif($offer->status === 'accepted')
                        {{-- 🔀 Accepted, no method chosen yet — show both options --}}
                        <div class="mb-3 p-3 rounded-3" style="background:#fffbeb;border:1px solid #fcd34d;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-exclamation-circle-fill" style="color:#d97706;font-size:.95rem;"></i>
                                <span style="font-size:.82rem;font-weight:600;color:#92400e;">
                                    Cash top-up of <strong>${{ number_format($offer->money_offer, 2) }}</strong> — choose how to pay:
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('cash-payment.create', $offer) }}"
                                   class="btn btn-sm flex-fill fw-semibold"
                                   style="background:#0070ba;color:#fff;border-radius:.55rem;font-size:.8rem;">
                                    <i class="bi bi-paypal me-1"></i> PayPal
                                </a>
                                <form method="POST" action="{{ route('cash-payment.choose-cash', $offer) }}" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-sm w-100 fw-semibold"
                                            style="background:#198754;color:#fff;border-radius:.55rem;font-size:.8rem;">
                                        <i class="bi bi-cash-coin me-1"></i> Cash in Person
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        {{-- Pending offer, not accepted yet --}}
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:rgba(6,214,160,.07);border:1px solid rgba(6,214,160,.2);">
                            <i class="bi bi-cash-coin text-success" style="font-size:.9rem;"></i>
                            <span style="font-size:.82rem;color:#374151;">You added <strong>${{ number_format($offer->money_offer, 2) }}</strong> cash top-up</span>
                        </div>
                        @endif
                    @endif

                    {{-- ── Cancellation flow (sent offers) ── --}}
                    @if($offer->status === 'pending' && $offer->requester_id === Auth::id())
                        @if($offer->hasPendingCancelRequest())
                        {{-- Already requested — waiting for responder --}}
                        <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:#fff7ed;border:1px solid #fed7aa;">
                            <i class="bi bi-hourglass-split" style="color:#ea580c;font-size:.9rem;"></i>
                            <span style="font-size:.8rem;color:#9a3412;">
                                Cancellation request sent — waiting for the other party to approve.
                            </span>
                        </div>
                        @else
                        {{-- Show reason form --}}
                        <div x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="btn btn-sm btn-outline-secondary w-100 py-2"
                                    style="border-radius:.55rem;border-style:dashed;font-size:.8rem;">
                                <i class="bi bi-x me-1"></i> Withdraw Offer
                            </button>
                            <div x-show="open" x-transition class="mt-2 p-3 rounded-2" style="background:#fef2f2;border:1px solid #fecaca;">
                                <p style="font-size:.8rem;color:#991b1b;margin-bottom:.6rem;">
                                    <strong>Reason required.</strong> The other party must approve before the offer is removed.
                                </p>
                                <form method="POST" action="{{ route('exchanges.requestCancel', $offer) }}">
                                    @csrf
                                    <textarea name="cancel_reason" rows="2" required minlength="5" maxlength="500"
                                              class="form-control mb-2" style="font-size:.82rem;border-radius:.55rem;"
                                              placeholder="Why do you want to cancel? (e.g. found another deal, item no longer available)"></textarea>
                                    <button type="submit" class="btn btn-danger btn-sm w-100" style="border-radius:.55rem;font-size:.8rem;">
                                        <i class="bi bi-send me-1"></i> Send Cancellation Request
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endif

                    @if($offer->status === 'accepted')
                        <div class="mt-3 pt-3 border-top">
                            @include('insurance._panel', ['offer' => $offer, 'myRole' => 'requester'])
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-send text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                    <p class="text-muted small mt-2 mb-0">No sent offers yet</p>
                </div>
            </div>
            @endforelse

            @if($sentOffers->hasPages())
            <div class="mt-2 d-flex justify-content-center">{{ $sentOffers->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .offer-card { transition: box-shadow .2s, transform .2s; }
    .offer-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.06) !important; }
</style>
@endpush
