@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Advertisement Banner -->
    <div class="mb-4">
        <div class="card border-0 shadow-sm">
            <img src="{{ asset('storage/images/banner-placeholder.jpg') }}" alt="Advertisement Banner" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
        </div>
    </div>

    <div class="row">
        <!-- Filter Block -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Filter Products</h5>
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Example filter options -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select id="category" name="category" class="form-select">
                                <option value="">All Categories</option>
                                <option value="electronics">Electronics</option>
                                <option value="furniture">Furniture</option>
                                <option value="clothing">Clothing</option>
                                <!-- Add more categories as needed -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="condition" class="form-label">Condition</label>
                            <select id="condition" name="condition" class="form-select">
                                <option value="">All Conditions</option>
                                <option value="new">New</option>
                                <option value="used">Used</option>
                                <option value="refurbished">Refurbished</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Product Listings -->
        <div class="col-lg-9 col-md-12">
            <h1 class="text-center mb-4 text-primary font-weight-bold">Browse Our Products</h1>
            <a href="{{ route('products.create') }}" class="btn btn-success mb-4">Add New Product</a>

            <div class="row">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm border-0 rounded">
                            @if($product->image_paths)
                                @php
                                    $imagePaths = json_decode($product->image_paths, true);
                                    $firstImagePath = $imagePaths[0] ?? null;
                                @endphp
                                @if($firstImagePath)
                                    <img src="{{ asset('storage/' . $firstImagePath) }}" alt="Product Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/images/placeholder.png') }}" alt="Placeholder Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @endif
                            @else
                                <img src="{{ asset('storage/images/placeholder.png') }}" alt="Placeholder Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-dark mb-2">{{ $product->name }}</h5>
                                <p class="card-text text-muted mb-3">{{ Str::limit($product->description, 100, '...') }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View Details</a>
                                    @if($product->user_id !== Auth::id())
                                        <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-warning">Offer Exchange</a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-light text-muted text-center">
                                <small>Posted on {{ $product->created_at->format('F j, Y') }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection