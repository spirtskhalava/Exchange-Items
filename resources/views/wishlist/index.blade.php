@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold text-center">Your Wishlist</h2>

    @if($wishlistItems->isEmpty())
        <div class="text-center mt-5">
            <img src="{{ asset('images/empty-wishlist.svg') }}" alt="Empty Wishlist" style="max-width: 300px;" class="img-fluid mb-3">
            <p class="lead">You haven’t added anything to your wishlist yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Browse Products</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($wishlistItems as $item)
                <div class="col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                        <img 
                            src="{{ $item->product->image_paths[0] ? asset('storage/' . $item->product->image_paths[0]) : asset('images/fallback.png') }}" 
                            class="card-img-top object-fit-cover" 
                            style="height: 220px;" 
                            alt="{{ $item->product->name }}"
                        >

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('products.show', $item->product->id) }}" class="text-decoration-none text-dark">
                                    {{ $item->product->name }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small">
                                {{ \Illuminate\Support\Str::limit($item->product->description, 100) }}
                            </p>
                            <div class="mt-auto">
                                <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-heartbreak-fill me-1"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
