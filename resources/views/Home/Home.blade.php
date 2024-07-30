@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>
    <div class="row mt-3">
        @foreach($products as $product)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">View</a>
                        @if($product->user_id !== Auth::id())
                            <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-warning">Offer Exchange</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
