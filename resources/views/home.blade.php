@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-5 mb-4 text-center text-primary">Popular Products</h1>

    @if($products->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            No products available at the moment.
        </div>
    @else
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card border-light shadow-sm">
                        @if($product->image_paths)
                            @php
                                //asset('storage/' . $firstImagePath)
                                $imagePaths = json_decode($product->image_paths, true);
                                $firstImagePath = $imagePaths[0] ?? 'default-image.jpg';
                            @endphp
                            <img src="{{ $imagePaths[0]  }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('storage/default-image.jpg') }}" class="card-img-top" alt="Default Image" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text">
                                <small class="text-muted">Views: {{ $product->views }}</small>
                            </p>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary">View Details</a>
                        </div>
                        <div class="card-footer text-muted text-center">
                            @if($product->views > 100)
                                <span class="badge bg-warning text-dark">Popular</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection