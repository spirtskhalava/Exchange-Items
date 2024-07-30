@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-5 mb-4">My Offers</h1>

    <div class="row">
        <div class="col-md-6">
            <h2>Received Offers</h2>
            @foreach($receivedOffers as $offer)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Offer from {{ $offer->requester->username }}</h5>
                        <p class="card-text">Requested Product: {{ $offer->requestedProduct->name }}</p>
                        <p class="card-text">Offered Product: {{ $offer->offeredProduct->name }}</p>
                        <p class="card-text">Status: {{ $offer->status }}</p>
                        @if ($offer->status == 'pending')
                            <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="btn btn-success mr-2">Accept</button>
                            </form>
                            <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="declined">
                                <button type="submit" class="btn btn-danger">Decline</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sent Offers Column -->
        <div class="col-md-6">
            <h2>Sent Offers</h2>
            @foreach($sentOffers as $offer)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Offer to {{ $offer->responder->username }}</h5>
                        <p class="card-text">Requested Product: {{ $offer->requestedProduct->name }}</p>
                        <p class="card-text">Offered Product: {{ $offer->offeredProduct->name }}</p>
                        <p class="card-text">Status: {{ $offer->status }}</p>
                        <!-- Optionally add buttons to cancel sent offers -->
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
