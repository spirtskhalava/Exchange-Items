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
                <img class="d-block w-100" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22800%22%20height%3D%22400%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20800%20400%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_19192bcc3b7%20text%20%7B%20fill%3A%23555%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A40pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_19192bcc3b7%22%3E%3Crect%20width%3D%22800%22%20height%3D%22400%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22285.9000015258789%22%20y%3D%22217.76000022888184%22%3EFirst%20slide%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Great Deals on Electronics</h5>
                    <p>Save up to 50% on select items</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22800%22%20height%3D%22400%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20800%20400%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_19192bcc3b7%20text%20%7B%20fill%3A%23555%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A40pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_19192bcc3b7%22%3E%3Crect%20width%3D%22800%22%20height%3D%22400%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22285.9000015258789%22%20y%3D%22217.76000022888184%22%3EFirst%20slide%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" alt="Second slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Top Fashion Picks</h5>
                    <p>Update your wardrobe with the latest trends</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22800%22%20height%3D%22400%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20800%20400%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_19192bcc3b7%20text%20%7B%20fill%3A%23555%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A40pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_19192bcc3b7%22%3E%3Crect%20width%3D%22800%22%20height%3D%22400%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22285.9000015258789%22%20y%3D%22217.76000022888184%22%3EFirst%20slide%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" alt="Third slide">
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
            <a href="/products?search=&category=electronics&condition=" class="text-decoration-none">
                <div class="card border-light">
                    <img class="card-img-top" src="category1.jpg" alt="Electronics">
                    <div class="card-body">
                        <h5 class="card-title">Electronics</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/products?search=&category=fashion&condition=" class="text-decoration-none">
                <div class="card border-light">
                    <img class="card-img-top" src="category2.jpg" alt="Fashion">
                    <div class="card-body">
                        <h5 class="card-title">Fashion</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/products?search=&category=home-garden&condition=" class="text-decoration-none">
                <div class="card border-light">
                    <img class="card-img-top" src="category3.jpg" alt="Home & Garden">
                    <div class="card-body">
                        <h5 class="card-title">Home & Garden</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Featured Products -->
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