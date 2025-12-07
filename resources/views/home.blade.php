@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Carousel - Modernized -->
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner rounded-3 overflow-hidden">
            <div class="carousel-item active">
                <div class="carousel-image-container">
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" class="d-block w-100" alt="Electronics" loading="lazy">
                </div>
                <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                    <h2 class="display-5 fw-bold mb-3">Great Deals on Electronics</h2>
                    <p class="lead d-none d-md-block">Save up to 50% on select items</p>
                    <a href="/products?category=electronics" class="btn btn-primary btn-lg align-self-center mt-3">Shop Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-image-container">
                    <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" class="d-block w-100" alt="Fashion" loading="lazy">
                </div>
                <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                    <h2 class="display-5 fw-bold mb-3">Top Fashion Picks</h2>
                    <p class="lead d-none d-md-block">Update your wardrobe with the latest trends</p>
                    <a href="/products?category=fashion" class="btn btn-primary btn-lg align-self-center mt-3">Discover Styles</a>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-image-container">
                    <img src="https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" class="d-block w-100" alt="Home Essentials" loading="lazy">
                </div>
                <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                    <h2 class="display-5 fw-bold mb-3">Home Essentials</h2>
                    <p class="lead d-none d-md-block">Everything you need for a cozy home</p>
                    <a href="/products?category=home-garden" class="btn btn-primary btn-lg align-self-center mt-3">Explore Now</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Category Cards - Modern Grid -->
    <section class="container my-5">
        <h2 class="text-center mb-4 fw-bold">Shop by Category</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="/products?category=electronics" class="text-decoration-none category-card">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden hover-effect">
                        <div class="category-image-container">
                            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" class="card-img-top" alt="Electronics" loading="lazy">
                        </div>
                        <div class="card-body text-center">
                            <h3 class="h5 card-title mb-0">Electronics</h3>
                            <p class="text-muted small mt-2">Latest gadgets & devices</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="/products?category=fashion" class="text-decoration-none category-card">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden hover-effect">
                        <div class="category-image-container">
                            <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" class="card-img-top" alt="Fashion" loading="lazy">
                        </div>
                        <div class="card-body text-center">
                            <h3 class="h5 card-title mb-0">Fashion</h3>
                            <p class="text-muted small mt-2">Trendy styles for everyone</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="/products?category=home-garden" class="text-decoration-none category-card">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden hover-effect">
                        <div class="category-image-container">
                            <img src="https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" class="card-img-top" alt="Home & Garden" loading="lazy">
                        </div>
                        <div class="card-body text-center">
                            <h3 class="h5 card-title mb-0">Home & Garden</h3>
                            <p class="text-muted small mt-2">Create your perfect space</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products - Modern Card Design -->
    <section class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Popular Products</h2>
            <a href="/products" class="btn btn-outline-primary">View All</a>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-5">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#6c757d" class="bi bi-box-seam" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
                <h3 class="h4">No products available</h3>
                <p class="text-muted">Check back later for new listings</p>
                @auth
                    <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">List a Product</a>
                @endauth
            </div>
        @else
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card product-card h-100 border-0 shadow-sm overflow-hidden">
                            <div class="position-relative">
                            @php
                                    // 1. Set the default placeholder first
                                    $imageUrl = 'https://placehold.co/400x300?text=No+Image';

                                    // 2. Decode the JSON safely
                                    $paths = !empty($product->image_paths) ? json_decode($product->image_paths, true) : null;

                                    // 3. Check if we actually have a valid path in the array
                                    if (is_array($paths) && count($paths) > 0 && !empty($paths[0])) {
                                        $path = $paths[0];

                                        // 4. Check if it is a remote URL (starts with http)
                                        if (str_starts_with($path, 'http')) {
                                            $imageUrl = 'https://placehold.co/400x300?text=No+Image';
                                        } else {
                                            // It is a local file, add the storage prefix
                                            $imageUrl = asset('storage/' . $path);
                                        }
                                    }
                                @endphp
                                    <img src="{{ $imageUrl }}" class="card-img-top product-image" alt="{{ $product->name }}" loading="lazy">
                                
                                <!-- Product badges -->
                                <div class="product-badges">
                                    @if($product->views > 100)
                                        <span class="badge bg-warning text-dark">Popular</span>
                                    @endif
                                    @if($product->condition=='New')
                                        <span class="badge bg-success">New</span>
                                    @elseif($product->condition === 'Used')
                                        <span class="badge bg-info text-dark">Used</span>
                                    @else
                                       <span class="badge bg-info text-dark">Refurbished</span>
                                    @endif
                                </div>
                            
                            </div>
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h3 class="h5 card-title mb-0">{{ Str::limit($product->name, 40) }}</h3>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="badge bg-light text-dark">{{ ucfirst(str_replace('-', ' ', $product->category)) }}</span>
                                </div>
                                
                                <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <small class="text-muted">
                                        <i class="bi bi-eye"></i> {{ $product->views }} views
                                    </small>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary stretched-link">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination - Centered with improved styling -->
            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Page navigation">
                    {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        @endif
    </section>
</div>

<!-- Quick View Modal (should be included in your layout file) -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <!-- Modal content would be loaded via AJAX -->
</div>

@endsection

@push('styles')
<style>
    /* Modern CSS for improved UI/UX */
    .carousel-image-container {
        height: 500px;
        overflow: hidden;
        position: relative;
    }
    
    .carousel-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    .carousel-caption {
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        bottom: 0;
        padding-bottom: 3rem;
    }
    
    .category-image-container {
        height: 200px;
        overflow: hidden;
    }
    
    .category-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .hover-effect:hover img {
        transform: scale(1.05);
    }
    
    .hover-effect:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .product-card {
        transition: all 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .product-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    
    .product-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .btn-quick-view {
        position: absolute;
        bottom: -100%;
        left: 0;
        right: 0;
        background: rgba(255,255,255,0.9);
        border: none;
        padding: 8px;
        width: 100%;
        transition: bottom 0.3s ease;
        font-size: 0.9rem;
    }
    
    .product-card:hover .btn-quick-view {
        bottom: 0;
    }
    
    @media (max-width: 768px) {
        .carousel-image-container {
            height: 300px;
        }
        
        .carousel-caption h2 {
            font-size: 1.5rem;
        }
        
        .carousel-caption .btn {
            font-size: 0.9rem;
            padding: 0.375rem 0.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Quick view functionality
    document.addEventListener('DOMContentLoaded', function() {
        const quickViewButtons = document.querySelectorAll('.btn-quick-view');
        
        quickViewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.getAttribute('data-product-id');
                // Here you would typically make an AJAX call to load the product details
                // into the quick view modal
                console.log('Quick view for product:', productId);
                
                // Example AJAX call (implementation depends on your backend):
                /*
                fetch(`/products/${productId}/quick-view`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate modal with data
                        document.getElementById('quickViewModal').innerHTML = data.html;
                        
                        // Show modal
                        var modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
                        modal.show();
                    });
                */
            });
        });
    });
</script>
@endpush