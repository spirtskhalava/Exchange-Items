@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Your Wishlist</h2>
    @if($wishlistItems->isEmpty())
        <p>You have no items in your wishlist.</p>
    @else
        <div class="row">
            @foreach($wishlistItems as $item)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm border-0">
                        <img src="{{ asset('storage/' . $item->product->image_paths[0]) }}" class="card-img-top img-fluid" alt="{{ $item->product->name }}">
                        <div class="card-body">
                            <h5><a href="{{ route('products.show', $item->product->id) }}">{{ $item->product->name }}</a></h5>
                            <p class="card-text">{{ $item->product->description }}</p>
                            <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove from Wishlist</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection