@extends('layouts.app')

@section('content')
<style>
    .card {
        border-radius: 1rem; /* More rounded corners */
        transition: transform 0.3s ease; /* Smooth transition for hover effect */
    }

    .card:hover {
        transform: scale(1.05); /* Slight zoom on hover */
    }

    .card-body {
        background-color: #ffffff; /* White background for card body */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
        border-radius: 1rem; /* More rounded corners */
    }

    .btn-success, .btn-danger, .btn-outline-danger {
        border-radius: 0.5rem; /* More rounded corners for buttons */
        transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for hover effect */
    }

    .btn-success:hover, .btn-danger:hover, .btn-outline-danger:hover {
        background-color: #007bff; /* Primary color on hover for all buttons */
        color: #fff; /* White text on hover */
    }

    .badge {
        font-size: 0.85rem; /* Slightly smaller badge font */
        padding: 0.5em 0.75em; /* More padding for badges */
    }

    .text-primary {
        color: #007bff !important; /* Primary color */
    }

    .text-success {
        color: #28a745 !important; /* Success color */
    }

    .text-center {
        text-align: center !important; /* Center alignment */
    }

    .mt-5, .mb-4, .mt-3 {
        margin-top: 3rem !important; /* More spacing for top margin */
        margin-bottom: 1.5rem !important; /* More spacing for bottom margin */
    }

    .alert-info {
        background-color: #d1ecf1; /* Light blue background for info alert */
        border-color: #bee5eb; /* Light blue border for info alert */
        color: #0c5460; /* Dark blue text for info alert */
        border-radius: 1rem; /* More rounded corners */
    }
</style>

<div class="container">
    <h1 class="mt-5 mb-4 text-center text-primary">My Offers</h1>

    <div class="row">
        <!-- Received Offers Column -->
        <div class="col-md-6 mb-4">
            <h2 class="text-success">Received Offers</h2>
            @forelse($receivedOffers as $offer)
                <div class="card shadow-sm border-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Offer from {{ $offer->requester->username }}</h5>
                        <p class="card-text">Requested Product: <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="text-decoration-none">{{ $offer->requestedProduct->name }}</a></p>
                        @if(isset($offer->offeredProduct) && isset($offer->offeredProduct->id))
                            <p class="card-text">
                                Offered Product: 
                                <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="text-decoration-none">
                                    {{ $offer->offeredProduct->name }}
                                </a>
                            </p>
                        @else
                            <p class="card-text">Offered Product: Not Available</p>
                        @endif
                        <p class="card-text">Money Offer: {{ $offer->money_offer ? '$' . number_format($offer->money_offer, 2) : 'N/A' }}</p>
                        <p class="card-text">Status: <span class="badge {{ $offer->status == 'pending' ? 'bg-warning text-dark' : ($offer->status == 'accepted' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($offer->status) }}</span></p>

                        @if ($offer->status == 'pending')
                            <div class="d-flex justify-content-between mt-3">
                                <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="btn btn-success">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="declined">
                                    <button type="submit" class="btn btn-danger">Decline</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info" role="alert">
                    You have no received offers.
                </div>
            @endforelse
        </div>

        <!-- Sent Offers Column -->
        <div class="col-md-6 mb-4">
            <h2 class="text-primary">Sent Offers</h2>
            @forelse($sentOffers as $offer)
                <div class="card shadow-sm border-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Offer to {{ $offer->responder->username }}</h5>
                        <p class="card-text">Requested Product: <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="text-decoration-none">{{ $offer->requestedProduct->name }}</a></p>
                        @if(isset($offer->offeredProduct) && isset($offer->offeredProduct->id))
                            <p class="card-text">
                                Offered Product: 
                                <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="text-decoration-none">
                                    {{ $offer->offeredProduct->name }}
                                </a>
                            </p>
                        @else
                            <p class="card-text">Offered Product: Not Available</p>
                        @endif
                        <p class="card-text">Money Offer: {{ $offer->money_offer ? '$' . number_format($offer->money_offer, 2) : 'N/A' }}</p>
                        <p class="card-text">Status: <span class="badge {{ $offer->status == 'pending' ? 'bg-warning text-dark' : ($offer->status == 'accepted' ? 'bg-success' : 'bg-danger') }}">{{ ucfirst($offer->status) }}</span></p>

                        @if ($offer->status == 'pending' && $offer->requester_id === Auth::id())
                            <form method="POST" action="{{ route('exchanges.cancel', $offer) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Cancel Offer</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info" role="alert">
                    You have no sent offers.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection