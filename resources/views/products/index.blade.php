@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Advertisement Banner Slider -->
    <div class="mb-4">
        <div id="advertisementSlider" class="carousel slide shadow-lg rounded-lg overflow-hidden" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://via.placeholder.com/1920x300.png?text=Ad+1" class="d-block w-100" alt="Ad 1" style="height: 300px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img src="https://via.placeholder.com/1920x300.png?text=Ad+2" class="d-block w-100" alt="Ad 2" style="height: 300px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img src="https://via.placeholder.com/1920x300.png?text=Ad+3" class="d-block w-100" alt="Ad 3" style="height: 300px; object-fit: cover;">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#advertisementSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#advertisementSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Filter Block -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4 text-primary">Filter Products</h4>
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Search Bar -->
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" id="search" name="search" class="form-control" placeholder="Search products...">
                                <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                        <!-- Category Filter -->
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
                        <!-- Condition Filter -->
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

            <!-- Weather Banner -->
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">Current Weather</h5>
                    <img src="https://via.placeholder.com/200x200.png?text=Weather+Icon" alt="Weather Icon" class="img-fluid mb-2">
                    <p class="card-text">25°C, Sunny</p>
                    <small class="text-muted">Location: New York, USA</small>
                </div>
            </div>

            <!-- Additional Banner Block -->
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <img src="https://via.placeholder.com/200x200.png?text=Your+Ad+Here" alt="Advertisement" class="img-fluid rounded-lg">
            </div>
        </div>

        <!-- Main Product Listings -->
        <div class="col-lg-9 col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-primary font-weight-bold">Browse Our Products</h1>
                <a href="{{ route('products.create') }}" class="btn btn-success">Add New Product</a>
            </div>

            <div class="row">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-lg overflow-hidden hover-shadow">
                            @if($product->image_paths)
                                @php
                                    $imagePaths = json_decode($product->image_paths, true);
                                    $firstImagePath = $imagePaths[0] ?? null;
                                @endphp
                                @if($firstImagePath)
                                    <img src="{{ $firstImagePath }}" alt="Product Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="https://source.unsplash.com/200x200/?product" alt="Placeholder Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @endif
                            @else
                                <img src="https://source.unsplash.com/200x200/?product" alt="Placeholder Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-dark mb-2">{{ $product->name }}</h5>
                                <p class="card-text text-muted mb-2">{{ $product->category }}</p>
                                <p class="card-text text-muted mb-3">{{ Str::limit($product->description, 100, '...') }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm">View Details</a>
                                    @auth
                                        @if($product->user_id !== Auth::id())
                                            <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-warning btn-sm">Offer Exchange</a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                            <div class="card-footer bg-light text-muted text-center">
                                <small>Posted on {{ $product->created_at->format('F j, Y') }}</small>
                                <span class="badge bg-secondary ms-2">{{ $product->condition }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .rounded-lg {
        border-radius: 1rem;
    }
</style>
@endsection