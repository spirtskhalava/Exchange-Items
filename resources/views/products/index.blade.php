@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Hero Banner with Call-to-Action -->
    <div class="hero-banner mb-5 rounded-4 overflow-hidden position-relative" style="height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container h-100 d-flex align-items-center">
            <div class="text-white col-md-7">
                <h1 class="display-5 fw-bold mb-3">Discover Amazing Products</h1>
                <p class="lead mb-4">Find what you need or exchange items you no longer use with our community.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.create') }}" class="btn btn-light btn-lg px-4 rounded-pill fw-bold">List an Item</a>
                    <a href="#featured-products" class="btn btn-outline-light btn-lg px-4 rounded-pill">Browse Items</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Filter Block - Improved Sticky Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="sticky-top" style="top: 20px;">
                <!-- Filter Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title mb-0 text-dark fw-bold">Filters</h4>
                            <button class="btn btn-sm btn-outline-secondary reset-filters">Reset</button>
                        </div>
                        <form action="{{ route('products.index') }}" method="GET">
                            <!-- Search Bar -->
                            <div class="mb-3">
                                <label for="search" class="form-label small text-muted">Search products</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" id="search" name="search" class="form-control border-start-0" placeholder="What are you looking for?">
                                </div>
                            </div>
                            
                            <!-- Category Filter with Icons -->
                            <div class="mb-3">
                                <label for="category" class="form-label small text-muted">Category</label>
                                <select id="category" name="category" class="form-select form-select-lg">
                                    <option value="">All Categories</option>
                                    <option value="electronics" data-icon="fa-laptop"> Electronics</option>
                                    <option value="furniture" data-icon="fa-couch"> Furniture</option>
                                    <option value="clothing" data-icon="fa-tshirt"> Clothing</option>
                                    <option value="books" data-icon="fa-book"> Books</option>
                                    <option value="sports" data-icon="fa-basketball-ball"> Sports</option>
                                </select>
                            </div>
                            
                            <!-- Price Range Slider -->
                            <div class="mb-3">
                                <label class="form-label small text-muted">Price Range</label>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-light text-dark">$0</span>
                                    <span class="badge bg-light text-dark">$1000+</span>
                                </div>
                                <input type="range" class="form-range" min="0" max="1000" step="10" id="priceRange">
                                <div class="d-flex justify-content-between">
                                    <input type="number" class="form-control form-control-sm w-45" placeholder="Min" name="min_price">
                                    <input type="number" class="form-control form-control-sm w-45" placeholder="Max" name="max_price">
                                </div>
                            </div>
                            
                            <!-- Condition Filter as Chips -->
                            <div class="mb-4">
                                <label class="form-label small text-muted">Condition</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="condition" id="condition-all" value="" checked>
                                    <label class="btn btn-outline-secondary" for="condition-all">All</label>
                                    
                                    <input type="radio" class="btn-check" name="condition" id="condition-new" value="new">
                                    <label class="btn btn-outline-secondary" for="condition-new">New</label>
                                    
                                    <input type="radio" class="btn-check" name="condition" id="condition-used" value="used">
                                    <label class="btn btn-outline-secondary" for="condition-used">Used</label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Apply Filters</button>
                        </form>
                    </div>
                </div>
                
                <!-- Weather Widget - More Detailed -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body text-center p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0 text-dark fw-bold">Weather</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary">Live</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-sun fa-3x text-warning me-3"></i>
                            <div class="text-start">
                                <h2 class="mb-0 fw-bold">25°C</h2>
                                <small class="text-muted">Sunny</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-center">
                            <div>
                                <div class="text-muted small">Humidity</div>
                                <div class="fw-bold">65%</div>
                            </div>
                            <div>
                                <div class="text-muted small">Wind</div>
                                <div class="fw-bold">12 km/h</div>
                            </div>
                            <div>
                                <div class="text-muted small">UV</div>
                                <div class="fw-bold">6</div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="text-start">
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Location:</span>
                                <span class="fw-bold">New York, USA</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Updated:</span>
                                <span class="fw-bold">Just now</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Promo Banner -->
                <div class="card border-0 rounded-4 overflow-hidden mb-4">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Special Offer" class="img-fluid">
                        <div class="position-absolute bottom-0 start-0 p-3 text-white">
                            <h5 class="mb-1 fw-bold">Summer Sale</h5>
                            <p class="small mb-0">Up to 50% off</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Product Listings -->
        <div class="col-lg-9 col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-dark fw-bold mb-1">Featured Products</h1>
                    <p class="text-muted small mb-0">{{ $products->total() }} items available</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sort me-1"></i> Sort By
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item" href="#">Newest First</a></li>
                            <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
                            <li><a class="dropdown-item" href="#">Price: High to Low</a></li>
                            <li><a class="dropdown-item" href="#">Most Popular</a></li>
                        </ul>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-th-large"></i></button>
                        <button type="button" class="btn btn-outline-secondary active"><i class="fas fa-list"></i></button>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                            <!-- Product Image with Badges -->
                            <div class="position-relative">
                                @if($product->image_paths)
                                    @php
                                        $imagePaths = json_decode($product->image_paths, true);
                                        $firstImagePath = $imagePaths[0] ?? null;
                                    @endphp
                                    @if($firstImagePath)
                                        <img src="{{ $firstImagePath }}" alt="Product Image" class="card-img-top" style="height: 220px; object-fit: cover;">
                                    @else
                                        <img src="https://source.unsplash.com/random/300x300/?product,{{ rand(1,1000) }}" alt="Placeholder Image" class="card-img-top" style="height: 220px; object-fit: cover;">
                                    @endif
                                @else
                                    <img src="https://source.unsplash.com/random/300x300/?product,{{ rand(1,1000) }}" alt="Placeholder Image" class="card-img-top" style="height: 220px; object-fit: cover;">
                                @endif
                                
                                <!-- Condition Badge -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if($product->condition == 'New')
                                        <span class="badge bg-success bg-opacity-90 text-white">New</span>
                                    @elseif($product->condition == 'used')
                                        <span class="badge bg-warning bg-opacity-90 text-dark">Used</span>
                                    @else
                                        <span class="badge bg-info bg-opacity-90 text-white">Refurbished</span>
                                    @endif
                                </div>
                                
                                <!-- Favorite Button -->
                                <button class="btn btn-sm position-absolute bottom-0 end-0 m-2 bg-white rounded-circle shadow-sm" style="width: 36px; height: 36px;">
                                    <i class="far fa-heart text-danger"></i>
                                </button>
                            </div>
                            
                            <!-- Product Details -->
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0 text-dark fw-bold">{{ $product->name }}</h5>
                                    <span class="badge bg-light text-dark">{{ $product->category }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <i class="far fa-star"></i>
                                        <span class="text-muted ms-1">(24)</span>
                                    </div>
                                </div>
                                
                                <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 80, '...') }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div>
                                        <span class="text-dark fw-bold h5 mb-0">${{ number_format($product->price, 2) }}</span>
                                        @if($product->original_price)
                                            <span class="text-muted text-decoration-line-through small ms-1">${{ number_format($product->original_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                                        @auth
                                            @if($product->user_id !== Auth::id())
                                                <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">Offer</a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Footer -->
                            <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($product->user->name) }}&background=random" class="rounded-circle me-2" width="24" height="24" alt="Seller">
                                        <small class="text-muted">{{ $product->user->name }}</small>
                                    </div>
                                    <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png" width="120" class="mb-4 opacity-50">
                                <h4 class="text-muted mb-3">No products found</h4>
                                <p class="text-muted mb-4">Try adjusting your search or filter to find what you're looking for.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4">Reset Filters</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Page navigation">
                        <ul class="pagination rounded-pill shadow-sm">
                            <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link rounded-start-pill" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @foreach(range(1, $products->lastPage()) as $i)
                                <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                </li>
                            @endforeach
                            <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link rounded-end-pill" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hero-banner {
        background-size: cover;
        background-position: center;
    }
    
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .page-link {
        color: #0d6efd;
    }
    
    .dropdown-toggle::after {
        margin-left: 0.5em;
        vertical-align: 0.15em;
    }
    
    .form-range::-webkit-slider-thumb {
        background: #0d6efd;
    }
    
    .form-range::-moz-range-thumb {
        background: #0d6efd;
    }
    
    .form-range::-ms-thumb {
        background: #0d6efd;
    }
    
    .btn-check:checked + .btn-outline-secondary {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Reset filters button
        document.querySelector('.reset-filters').addEventListener('click', function() {
            document.querySelectorAll('input[type="text"], input[type="number"], select').forEach(el => {
                el.value = '';
            });
            document.querySelectorAll('input[type="radio"]').forEach(el => {
                el.checked = false;
            });
            document.getElementById('condition-all').checked = true;
        });
        
        // Add icons to select options
        const categorySelect = document.getElementById('category');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const iconClass = selectedOption.getAttribute('data-icon');
                if (iconClass) {
                    this.style.backgroundImage = `none`; // Remove default arrow if needed
                }
            });
        }
    });
</script>
@endsection