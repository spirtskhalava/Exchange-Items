@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Offer Exchange for {{ $product->name }}</h1>
    <form action="{{ route('exchanges.store', $product->id) }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="offered_product_id">Select a product to offer (optional):</label>
            <select name="offered_product_id" id="offered_product_id" class="form-control">
                <option value="">-- None --</option>
                @foreach($userProducts as $userProduct)
                    <option value="{{ $userProduct->id }}">{{ $userProduct->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mt-3">
            <label for="money_offer">Amount of Money to Offer:</label>
            <input type="number" step="0.01" name="money_offer" id="money_offer" class="form-control" placeholder="Enter amount" min="0">
        </div>
        <button type="submit" class="btn btn-primary">Submit Offer</button>
    </form>
</div>
@endsection