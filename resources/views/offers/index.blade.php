@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary">My Trade Offers</h1>
        <p class="lead text-muted">Manage your incoming and outgoing trade proposals</p>
    </div>

    <div class="row g-4">
        <!-- Received Offers Column -->
        <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 fw-bold text-success">
                    <i class="bi bi-inbox-fill me-2"></i>Received Offers
                </h2>
                <span class="badge bg-success rounded-pill">{{ $receivedOffers->count() }}</span>
            </div>

            @forelse($receivedOffers as $offer)
                <div class="card offer-card mb-4 border-0 shadow-sm hover-effect">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h3 class="h5 card-title mb-1">
                                    Offer from 
                                    <span class="text-dark">{{ $offer->requester->username }}</span>
                                </h3>
                                <small class="text-muted">Received {{ $offer->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge {{ $offer->status == 'pending' ? 'bg-warning text-dark' : ($offer->status == 'accepted' ? 'bg-success' : 'bg-danger') }} rounded-pill">
                                {{ ucfirst($offer->status) }}
                            </span>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-2">
                                    <h4 class="h6 text-muted mb-2">You Give</h4>
                                    <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="text-decoration-none stretched-link">
                                        <p class="mb-0 fw-bold text-dark">{{ $offer->requestedProduct->name }}</p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-2">
                                    <h4 class="h6 text-muted mb-2">You Get</h4>
                                    @if(isset($offer->offeredProduct) && isset($offer->offeredProduct->id))
                                        <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="text-decoration-none stretched-link">
                                            <p class="mb-0 fw-bold text-dark">{{ $offer->offeredProduct->name }}</p>
                                        </a>
                                    @else
                                        <p class="mb-0">Cash Offer</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($offer->money_offer)
                        <div class="alert alert-secondary py-2 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small">Additional Cash Offer:</span>
                                <span class="fw-bold text-success">${{ number_format($offer->money_offer, 2) }}</span>
                            </div>
                        </div>
                        @endif

                        @if ($offer->status == 'pending')
                        <div class="d-flex gap-2 mt-3">
                            <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}" class="flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="btn btn-success w-100 py-2">
                                    <i class="bi bi-check-circle me-2"></i>Accept Offer
                                </button>
                            </form>
                            <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}" class="flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="declined">
                                <button type="submit" class="btn btn-outline-danger w-100 py-2">
                                    <i class="bi bi-x-circle me-2"></i>Decline
                                </button>
                            </form>
                        </div>
                        @elseif($offer->status == 'accepted')
                        <div class="alert alert-success mt-3 mb-0 py-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <span>You accepted this offer on {{ $offer->updated_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-light rounded-3">
                    <div class="mb-3">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h5 text-muted mb-2">No Received Offers</h3>
                    <p class="text-muted small">When someone makes an offer on your items, it will appear here.</p>
                </div>
            @endforelse
        </div>

        <!-- Sent Offers Column -->
        <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 fw-bold text-primary">
                    <i class="bi bi-send-fill me-2"></i>Sent Offers
                </h2>
                <span class="badge bg-primary rounded-pill">{{ $sentOffers->count() }}</span>
            </div>

            @forelse($sentOffers as $offer)
                <div class="card offer-card mb-4 border-0 shadow-sm hover-effect">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h3 class="h5 card-title mb-1">
                                    Offer to 
                                    <span class="text-dark">{{ $offer->responder->username }}</span>
                                </h3>
                                <small class="text-muted">Sent {{ $offer->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge {{ $offer->status == 'pending' ? 'bg-warning text-dark' : ($offer->status == 'accepted' ? 'bg-success' : 'bg-danger') }} rounded-pill">
                                {{ ucfirst($offer->status) }}
                            </span>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-2">
                                    <h4 class="h6 text-muted mb-2">You Offer</h4>
                                    @if(isset($offer->offeredProduct) && isset($offer->offeredProduct->id))
                                        <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="text-decoration-none stretched-link">
                                            <p class="mb-0 fw-bold text-dark">{{ $offer->offeredProduct->name }}</p>
                                        </a>
                                    @else
                                        <p class="mb-0">Cash Offer</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-2">
                                    <h4 class="h6 text-muted mb-2">You Request</h4>
                                    <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="text-decoration-none stretched-link">
                                        <p class="mb-0 fw-bold text-dark">{{ $offer->requestedProduct->name }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($offer->money_offer)
                        <div class="alert alert-secondary py-2 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small">Additional Cash Offer:</span>
                                <span class="fw-bold text-success">${{ number_format($offer->money_offer, 2) }}</span>
                            </div>
                        </div>
                        @endif

                        @if ($offer->status == 'pending' && $offer->requester_id === Auth::id())
                        <form method="POST" action="{{ route('exchanges.cancel', $offer) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 py-2">
                                <i class="bi bi-trash me-2"></i>Cancel Offer
                            </button>
                        </form>
                        @elseif($offer->status == 'accepted')
                        <div class="alert alert-success mt-3 mb-0 py-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <span>Offer accepted on {{ $offer->updated_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-light rounded-3">
                    <div class="mb-3">
                        <i class="bi bi-send text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h5 text-muted mb-2">No Sent Offers</h3>
                    <p class="text-muted small">When you make offers on items, they will appear here.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Browse Items</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Modern CSS for offers page */
    .offer-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .offer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .hover-effect {
        transition: all 0.3s ease;
    }
    
    .hover-effect:hover {
        transform: translateY(-2px);
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .text-primary {
        color: #4361ee !important;
    }
    
    .text-success {
        color: #2ecc71 !important;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .btn-success {
        background-color: #2ecc71;
        border-color: #2ecc71;
    }
    
    .btn-outline-danger {
        border-color: #e74c3c;
        color: #e74c3c;
    }
    
    .btn-outline-danger:hover {
        background-color: #e74c3c;
        color: white;
    }
    
    .alert-secondary {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Add any interactive functionality here
    document.addEventListener('DOMContentLoaded', function() {
        // Example: Confirmation for canceling offers
        const cancelButtons = document.querySelectorAll('form[action*="cancel"] button');
        
        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to cancel this offer?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush