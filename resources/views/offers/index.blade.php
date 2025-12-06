@extends('layouts.app')

@section('content')

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- Minimalist Background -->
<div style="background-color: #f9fafb; min-height: 100vh; position: fixed; top: 0; left: 0; width: 100%; z-index: -1;"></div>

<div class="container py-5">
    
    <!-- Minimal Header -->
    <div class="mb-5 border-bottom pb-3">
        <h1 class="h3 fw-bold text-dark mb-0">Trade Offers</h1>
        <p class="text-muted small mt-1">Manage exchanges and negotiations</p>
    </div>

    <div class="row g-5">
        
        <!-- ==========================
             1. INCOMING (RECEIVED)
        ========================== -->
        <div class="col-lg-6">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="text-uppercase fw-bold text-secondary small ls-1 mb-0">Incoming</h6>
                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">{{ $receivedOffers->count() }}</span>
            </div>

            @forelse($receivedOffers as $offer)
                <div class="card border-0 rounded-3 mb-4 card-minimal">
                    <div class="card-body p-4">
                        
                        <!-- Header: Who & When -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($offer->requester->username) }}&background=f3f4f6&color=6b7280&size=32" class="rounded-circle" width="32" height="32">
                                <div>
                                    <span class="fw-bold text-dark d-block lh-1">{{ $offer->requester->username }}</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $offer->created_at->diffForHumans(null, true) }} ago</small>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            @php
                                $statusClass = match($offer->status) {
                                    'accepted' => 'text-success bg-success',
                                    'declined' => 'text-danger bg-danger',
                                    default => 'text-warning bg-warning',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} bg-opacity-10 rounded-pill fw-medium px-3">
                                {{ ucfirst($offer->status) }}
                            </span>
                        </div>

                        <!-- Trade Logic: Visual Flow -->
                        <div class="d-flex align-items-center justify-content-between mb-4 position-relative">
                            
                            <!-- They Get (Your Item) -->
                            <div class="text-center" style="width: 40%;">
                                <div class="mb-2">
                                    <span class="badge bg-light text-muted border">You Give</span>
                                </div>
                                <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="d-block text-dark fw-bold text-decoration-none text-truncate">
                                    {{ $offer->requestedProduct->name }}
                                </a>
                            </div>

                            <!-- Exchange Icon -->
                            <div class="text-muted opacity-25">
                                <i class="bi bi-arrow-left-right fs-4"></i>
                            </div>

                            <!-- You Get (Their Item) -->
                            <div class="text-center" style="width: 40%;">
                                <div class="mb-2">
                                    <span class="badge bg-light text-muted border">You Receive</span>
                                </div>
                                @if(isset($offer->offeredProduct) && isset($offer->offeredProduct->id))
                                    <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="d-block text-dark fw-bold text-decoration-none text-truncate">
                                        {{ $offer->offeredProduct->name }}
                                    </a>
                                @else
                                    <span class="d-block text-dark fw-bold">Cash Only</span>
                                @endif
                            </div>
                        </div>

                        <!-- Cash Top Up -->
                        @if($offer->money_offer)
                            <div class="bg-light rounded-2 p-2 text-center mb-4">
                                <small class="text-muted">
                                    <i class="bi bi-plus-circle me-1"></i> Includes 
                                    <span class="fw-bold text-dark">${{ number_format($offer->money_offer, 2) }}</span> cash
                                </small>
                            </div>
                        @endif

                        <!-- Actions -->
                        @if ($offer->status == 'pending')
                        <div class="row g-2">
                            <div class="col-6">
                                <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="declined">
                                    <button type="submit" class="btn btn-light w-100 btn-sm text-muted py-2">Decline</button>
                                </form>
                            </div>
                            <div class="col-6">
                                <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="btn btn-dark w-100 btn-sm py-2">Accept</button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="text-muted opacity-25 mb-2"><i class="bi bi-inbox fs-1"></i></div>
                    <p class="text-muted small">No incoming offers</p>
                </div>
            @endforelse
        </div>

        <!-- ==========================
             2. OUTGOING (SENT)
        ========================== -->
        <div class="col-lg-6">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="text-uppercase fw-bold text-secondary small ls-1 mb-0">Outgoing</h6>
                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">{{ $sentOffers->count() }}</span>
            </div>

            @forelse($sentOffers as $offer)
                <div class="card border-0 rounded-3 mb-4 card-minimal">
                    <div class="card-body p-4">
                        
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-arrow-up-right text-muted" style="font-size: 0.8rem;"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block lh-1">To: {{ $offer->responder->username }}</span>
                                    <small class="text-muted opacity-50" style="font-size: 0.7rem;">{{ $offer->created_at->format('M d') }}</small>
                                </div>
                            </div>
                            
                            <!-- Status -->
                            @php
                                $statusClass = match($offer->status) {
                                    'accepted' => 'text-success bg-success',
                                    'declined' => 'text-danger bg-danger',
                                    default => 'text-warning bg-warning',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} bg-opacity-10 rounded-pill fw-medium px-3">
                                {{ ucfirst($offer->status) }}
                            </span>
                        </div>

                        <!-- Trade Logic -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                             <!-- You Offer -->
                             <div class="text-center" style="width: 40%;">
                                <div class="mb-2">
                                    <span class="badge bg-light text-muted border">You Offer</span>
                                </div>
                                @if(isset($offer->offeredProduct) && isset($offer->offeredProduct->id))
                                    <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="d-block text-dark fw-bold text-decoration-none text-truncate">
                                        {{ $offer->offeredProduct->name }}
                                    </a>
                                @else
                                    <span class="d-block text-dark fw-bold">Cash Only</span>
                                @endif
                            </div>

                            <div class="text-muted opacity-25">
                                <i class="bi bi-arrow-right fs-5"></i>
                            </div>

                            <!-- You Want -->
                            <div class="text-center" style="width: 40%;">
                                <div class="mb-2">
                                    <span class="badge bg-light text-muted border">For</span>
                                </div>
                                <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="d-block text-dark fw-bold text-decoration-none text-truncate">
                                    {{ $offer->requestedProduct->name }}
                                </a>
                            </div>
                        </div>
                        
                         <!-- Cash Top Up -->
                         @if($offer->money_offer)
                         <div class="bg-light rounded-2 p-2 text-center mb-4">
                             <small class="text-muted">
                                 You added <span class="fw-bold text-dark">${{ number_format($offer->money_offer, 2) }}</span> cash
                             </small>
                         </div>
                        @endif

                        @if ($offer->status == 'pending' && $offer->requester_id === Auth::id())
                        <form method="POST" action="{{ route('exchanges.cancel', $offer) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary w-100 btn-sm py-2" style="border-style: dashed;">
                                Cancel Offer
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="text-muted opacity-25 mb-2"><i class="bi bi-send fs-1"></i></div>
                    <p class="text-muted small">No sent offers</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Minimalist Typography */
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .ls-1 { letter-spacing: 0.05em; }

    /* Minimal Card Logic */
    .card-minimal {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.04) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-minimal:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.04);
        border-color: rgba(0,0,0,0.08) !important;
    }

    /* Soft Badges */
    .badge {
        font-weight: 500;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
    }

    /* Buttons */
    .btn-dark {
        background-color: #1a1a1a;
        border-color: #1a1a1a;
    }
    .btn-dark:hover {
        background-color: #000;
        border-color: #000;
    }
    
    .btn-light {
        background-color: #f3f4f6;
        border-color: transparent;
        color: #4b5563;
    }
    .btn-light:hover {
        background-color: #e5e7eb;
        color: #111827;
    }

    /* Text Utilities */
    .text-truncate {
        max-width: 120px;
        margin: 0 auto;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelButtons = document.querySelectorAll('form[action*="cancel"] button');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Withdraw this offer?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush