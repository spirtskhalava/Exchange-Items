@extends('layouts.app')

@section('content')
<style>
    .card {
        border-radius: 0.75rem; /* Rounded corners */
    }

    .card-body {
        background-color: #f8f9fa; /* Light background for card body */
    }

    .btn-success, .btn-danger {
        border-radius: 0.25rem; /* Rounded corners for buttons */
    }

    .badge {
        font-size: 0.9rem; /* Slightly smaller badge font */
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
                        <p class="card-text">Offered Product: <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="text-decoration-none">{{ $offer->offeredProduct->name }}</a></p>
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
                        <p class="card-text">Offered Product: <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="text-decoration-none">{{ $offer->offeredProduct->name }}</a></p>
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