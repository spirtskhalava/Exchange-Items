{{--
    Insurance panel partial.
    $offer   — Exchange (with ->insurance loaded)
    $myRole  — 'requester' or 'responder'
--}}
@php
    $ins            = $offer->insurance;
    $isReq          = ($myRole === 'requester');
    $myOpted        = $isReq ? ($ins?->req_opted  ?? false) : ($ins?->resp_opted  ?? false);
    $theirOpted     = $isReq ? ($ins?->resp_opted ?? false) : ($ins?->req_opted   ?? false);
    $myRoleStr      = $myRole;
    $theirRoleStr   = $isReq ? 'responder' : 'requester';
    $theirItemKey   = $isReq ? 'resp' : 'req';
    $myItemKey      = $isReq ? 'req'  : 'resp';

    $myValField         = $myItemKey   . '_item_value';
    $myProposerField    = $myItemKey   . '_item_proposed_by';
    $myAgreedField      = $myItemKey   . '_item_agreed';
    $theirValField      = $theirItemKey . '_item_value';
    $theirProposerField = $theirItemKey . '_item_proposed_by';
    $theirAgreedField   = $theirItemKey . '_item_agreed';

    $myItemName    = $isReq
        ? ($offer->offeredProduct->name  ?? 'Your item')
        : ($offer->requestedProduct->name ?? 'Your item');
    $theirItemName = $isReq
        ? ($offer->requestedProduct->name ?? 'Their item')
        : ($offer->offeredProduct->name  ?? 'Their item');

    $myVal      = $ins?->$myValField;
    $myAgreed   = $ins?->$myAgreedField   ?? false;
    $myProposer = $ins?->$myProposerField;

    $theirVal      = $ins?->$theirValField;
    $theirAgreed   = $ins?->$theirAgreedField   ?? false;
    $theirProposer = $ins?->$theirProposerField;

    $escrow  = $ins?->escrow_status ?? 'none';
    $dispute = $ins?->dispute ?? null;
@endphp

<hr class="my-3 opacity-10">
<div class="insurance-panel small">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-shield-check text-primary"></i>
        <span class="fw-semibold text-dark">Item Insurance</span>
        @if($escrow === 'locked')
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill ms-auto">Escrow Locked</span>
        @elseif($escrow === 'pending_payment')
            <span class="badge bg-info bg-opacity-10 text-info rounded-pill ms-auto">Awaiting Payment</span>
        @elseif($escrow === 'released')
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill ms-auto">Released</span>
        @elseif($escrow === 'disputed')
            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill ms-auto">Disputed</span>
        @elseif($escrow === 'negotiating')
            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill ms-auto">Negotiating</span>
        @endif
    </div>

    {{-- Neither opted in --}}
    @if(!$myOpted && !$theirOpted && $escrow === 'none')
        <div class="bg-light rounded-2 p-3 text-center">
            <p class="text-muted mb-2">Protect this trade. Funds held in escrow until both confirm receipt.<br>
                <strong>$5 fee</strong> per participant.</p>
            <form method="POST" action="{{ route('insurance.optIn', $offer) }}">
                @csrf
                <button class="btn btn-dark btn-sm px-4">
                    <i class="bi bi-shield-plus me-1"></i> Enable Insurance
                </button>
            </form>
        </div>
    @endif

    {{-- I opted in, waiting for them --}}
    @if($myOpted && !$theirOpted)
        <div class="alert alert-info py-2 px-3 mb-2">
            <i class="bi bi-hourglass-split me-1"></i>
            You opted in. Waiting for the other party to enable insurance.
        </div>
    @endif

    {{-- They opted in, I haven't --}}
    @if(!$myOpted && $theirOpted && $escrow === 'none')
        <div class="bg-light rounded-2 p-3 text-center">
            <p class="text-muted mb-2">The other party wants insurance. Join for <strong>$5</strong>?</p>
            <form method="POST" action="{{ route('insurance.optIn', $offer) }}">
                @csrf
                <button class="btn btn-warning btn-sm w-100">Join Insurance</button>
            </form>
        </div>
    @endif

    {{-- Both opted in → negotiation --}}
    @if($ins && $ins->bothOpted() && in_array($escrow, ['negotiating', 'pending_payment', 'locked', 'released', 'disputed']))

        {{-- My item valuation --}}
        <div class="border rounded-2 p-3 mb-2">
            <p class="fw-semibold mb-2"><i class="bi bi-tag me-1"></i> Your item: <em>{{ $myItemName }}</em></p>

            @if(is_null($myVal) && $escrow === 'negotiating')
                <form method="POST" action="{{ route('insurance.submitValuation', $offer) }}" class="d-flex gap-2">
                    @csrf
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">$</span>
                        <input type="number" name="amount" class="form-control" placeholder="Value in USD" min="0.01" step="0.01" required>
                        <button class="btn btn-dark">Set Value</button>
                    </div>
                </form>

            @elseif(!is_null($myVal))
                @if($myProposer === $myRoleStr)
                    {{-- I proposed, awaiting response --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fw-bold">${{ number_format($myVal, 2) }}</span>
                        @if($myAgreed)
                            <span class="badge bg-success bg-opacity-10 text-success">Agreed ✓</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning">Awaiting response</span>
                            @if($escrow === 'negotiating')
                            <button class="btn btn-outline-secondary btn-sm ms-auto"
                                onclick="document.getElementById('revalue-my-{{ $offer->id }}').classList.toggle('d-none')">Edit</button>
                            @endif
                        @endif
                    </div>
                    <div id="revalue-my-{{ $offer->id }}" class="d-none mt-2">
                        <form method="POST" action="{{ route('insurance.submitValuation', $offer) }}" class="d-flex gap-2">
                            @csrf
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" name="amount" class="form-control" value="{{ $myVal }}" min="0.01" step="0.01" required>
                                <button class="btn btn-dark">Update</button>
                            </div>
                        </form>
                    </div>
                @else
                    {{-- They countered my item's value --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="text-muted">Counter-offer: </span>
                            <span class="fw-bold">${{ number_format($myVal, 2) }}</span>
                        </div>
                        @if(!$myAgreed && $escrow === 'negotiating')
                        <div class="d-flex gap-1 flex-wrap">
                            <form method="POST" action="{{ route('insurance.respondValuation', $offer) }}">
                                @csrf
                                <input type="hidden" name="item" value="{{ $myItemKey }}">
                                <input type="hidden" name="action" value="accept">
                                <button class="btn btn-success btn-sm">Accept</button>
                            </form>
                            <button class="btn btn-outline-secondary btn-sm"
                                onclick="document.getElementById('counter-my-{{ $offer->id }}').classList.toggle('d-none')">Counter</button>
                            <form method="POST" action="{{ route('insurance.respondValuation', $offer) }}">
                                @csrf
                                <input type="hidden" name="item" value="{{ $myItemKey }}">
                                <input type="hidden" name="action" value="reject">
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </div>
                        @elseif($myAgreed)
                            <span class="badge bg-success bg-opacity-10 text-success">Agreed ✓</span>
                        @endif
                    </div>
                    <div id="counter-my-{{ $offer->id }}" class="d-none mt-2">
                        <form method="POST" action="{{ route('insurance.respondValuation', $offer) }}" class="d-flex gap-2">
                            @csrf
                            <input type="hidden" name="item" value="{{ $myItemKey }}">
                            <input type="hidden" name="action" value="counter">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" name="amount" class="form-control" placeholder="Counter amount" min="0.01" step="0.01" required>
                                <button class="btn btn-dark">Send</button>
                            </div>
                        </form>
                    </div>
                @endif
            @endif
        </div>

        {{-- Their item valuation --}}
        <div class="border rounded-2 p-3 mb-2">
            <p class="fw-semibold mb-2"><i class="bi bi-tag me-1"></i> Their item: <em>{{ $theirItemName }}</em></p>

            @if(is_null($theirVal))
                <p class="text-muted mb-0">Waiting for them to set a value…</p>
            @else
                @if($theirProposer === $theirRoleStr)
                    {{-- They proposed → I respond --}}
                    <div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <span class="text-muted">Proposed: </span>
                                <span class="fw-bold">${{ number_format($theirVal, 2) }}</span>
                            </div>
                            @if(!$theirAgreed && $escrow === 'negotiating')
                            <div class="d-flex gap-1 flex-wrap">
                                <form method="POST" action="{{ route('insurance.respondValuation', $offer) }}">
                                    @csrf
                                    <input type="hidden" name="item" value="{{ $theirItemKey }}">
                                    <input type="hidden" name="action" value="accept">
                                    <button class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <button class="btn btn-outline-secondary btn-sm"
                                    onclick="document.getElementById('counter-their-{{ $offer->id }}').classList.toggle('d-none')">Counter</button>
                                <form method="POST" action="{{ route('insurance.respondValuation', $offer) }}">
                                    @csrf
                                    <input type="hidden" name="item" value="{{ $theirItemKey }}">
                                    <input type="hidden" name="action" value="reject">
                                    <button class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                            @elseif($theirAgreed)
                                <span class="badge bg-success bg-opacity-10 text-success">Agreed ✓</span>
                            @endif
                        </div>
                        <div id="counter-their-{{ $offer->id }}" class="d-none mt-2">
                            <form method="POST" action="{{ route('insurance.respondValuation', $offer) }}" class="d-flex gap-2">
                                @csrf
                                <input type="hidden" name="item" value="{{ $theirItemKey }}">
                                <input type="hidden" name="action" value="counter">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="amount" class="form-control" placeholder="Counter amount" min="0.01" step="0.01" required>
                                    <button class="btn btn-dark">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- I countered → they respond --}}
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Your counter: </span>
                        <span class="fw-bold">${{ number_format($theirVal, 2) }}</span>
                        @if($theirAgreed)
                            <span class="badge bg-success bg-opacity-10 text-success">Agreed ✓</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning">Awaiting response</span>
                        @endif
                    </div>
                @endif
            @endif
        </div>

        {{-- Pending PayPal payment --}}
        @if($escrow === 'pending_payment')
            @php $iPaid = $isReq ? ($ins->req_payment_captured ?? false) : ($ins->resp_payment_captured ?? false); @endphp
            @if(!$iPaid)
                <div class="alert alert-info py-2 px-3 mb-2">
                    <i class="bi bi-credit-card me-1"></i>
                    Both valuations agreed! Pay <strong>${{ number_format($isReq ? $ins->requesterLockedAmount() : $ins->responderLockedAmount(), 2) }}</strong>
                    (item value + $5 fee) via PayPal to lock escrow.
                </div>
                <a href="{{ route('insurance.pay', $offer) }}" class="btn btn-primary btn-sm w-100 mb-2">
                    <i class="bi bi-paypal me-1"></i> Pay via PayPal
                </a>
            @else
                <div class="alert alert-success py-2 px-3 mb-2">
                    <i class="bi bi-check-circle me-1"></i> Your payment received. Waiting for the other party to pay.
                </div>
            @endif
        @endif

        {{-- Escrow locked --}}
        @if($escrow === 'locked')
            <div class="alert alert-primary py-2 px-3 mb-2">
                <i class="bi bi-lock-fill me-1"></i>
                Escrow locked. Funds secured until both confirm receipt.
                Your locked amount: <strong>${{ number_format($isReq ? $ins->requesterLockedAmount() : $ins->responderLockedAmount(), 2) }}</strong>
            </div>

            @php $iReceived = $isReq ? $ins->req_received : $ins->resp_received; @endphp
            @if(!$iReceived)
                <form method="POST" action="{{ route('insurance.markReceived', $offer) }}">
                    @csrf
                    <button class="btn btn-success btn-sm w-100 mb-2">
                        <i class="bi bi-check-circle me-1"></i> I Received My Item — It's Good
                    </button>
                </form>
            @else
                <div class="alert alert-success py-2 px-3 mb-2">
                    <i class="bi bi-check-circle me-1"></i> You confirmed receipt. Waiting for the other party.
                </div>
            @endif

            @if(!$dispute)
                <a href="{{ route('disputes.create', $offer) }}" class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-exclamation-triangle me-1"></i> Open Dispute (received bad item?)
                </a>
            @else
                <div class="alert alert-danger py-2 px-3 mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Dispute filed — status: <strong>{{ ucfirst(str_replace('_', ' ', $dispute->status)) }}</strong>
                </div>
            @endif
        @endif

        {{-- Released --}}
        @if($escrow === 'released')
            <div class="alert alert-success py-2 px-3 mb-0">
                <i class="bi bi-check2-all me-1"></i>
                Exchange complete! Funds returned to both parties.
            </div>
        @endif

        {{-- Disputed --}}
        @if($escrow === 'disputed' && $dispute)
            <div class="alert alert-danger py-2 px-3 mb-0">
                <i class="bi bi-shield-exclamation me-1"></i>
                Dispute under admin review.
                @if($dispute->status !== 'pending')
                    <br><strong>Outcome:</strong> {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                    @if($dispute->admin_notes)
                        <br><em>{{ $dispute->admin_notes }}</em>
                    @endif
                @endif
            </div>
        @endif

    @endif
</div>
