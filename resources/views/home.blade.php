@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div id="carouselExampleIndicators" class="carousel slide mb-4" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="hero1.jpg" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Great Deals on Electronics</h5>
                    <p>Save up to 50% on select items</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="hero2.jpg" alt="Second slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Top Fashion Picks</h5>
                    <p>Update your wardrobe with the latest trends</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="hero3.jpg" alt="Third slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Home Essentials</h5>
                    <p>Everything you need for a cozy home</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Product Categories -->
    <div class="row text-center mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="card border-light">
                    <img class="card-img-top" src="category1.jpg" alt="Category 1">
                    <div class="card-body">
                        <h5 class="card-title">Electronics</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="card border-light">
                    <img class="card-img-top" src="category2.jpg" alt="Category 2">
                    <div class="card-body">
                        <h5 class="card-title">Fashion</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="card border-light">
                    <img class="card-img-top" src="category3.jpg" alt="Category 3">
                    <div class="card-body">
                        <h5 class="card-title">Home & Garden</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Featured Products -->
    <h2 class="mb-4">Featured Products</h2>
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
                            <p class="card-text">{{ $product->category }}</p>
                            <p class="card-text">{{ $product->condition }}</p>
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