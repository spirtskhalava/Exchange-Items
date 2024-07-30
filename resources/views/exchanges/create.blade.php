@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Offer Exchange for {{ $product->name }}</h1>
    <form action="{{ route('exchanges.store', $product->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="offered_product_id">Select a product to offer:</label>
            <select name="offered_product_id" id="offered_product_id" class="form-control" required>
                @foreach($userProducts as $userProduct)
                    <option value="{{ $userProduct->id }}">{{ $userProduct->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit Offer</button>
    </form>
</div>
@endsection