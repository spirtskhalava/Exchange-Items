@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Hero Banner -->
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
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="sticky-top" style="top: 20px;">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title mb-0 text-dark fw-bold">Filters</h4>
                            <button class="btn btn-sm btn-outline-secondary reset-filters">Reset</button>
                        </div>
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="mb-3">
                                <label for="search" class="form-label small text-muted">Search products</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" id="search" name="search" class="form-control border-start-0" placeholder="What are you looking for?">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label small text-muted">Category</label>
                                <select id="category" name="category" class="form-select form-select-lg">
                                    <option value="">All Categories</option>
        
                                            @foreach($categories as $cat)
                                            <option value="{{ $cat->slug }}" ... > {{ $cat->name }} </option>
                                            @endforeach
                                </select>
                            </div>
                            
                            <!-- <div class="mb-3">
                                <label class="form-label small text-muted">Price Range</label>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-light text-dark">$0</span>
                                    <span class="badge bg-light text-dark">$1000+</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <input type="number" class="form-control form-control-sm w-45" placeholder="Min" name="min_price">
                                    <input type="number" class="form-control form-control-sm w-45" placeholder="Max" name="max_price">
                                </div>
                            </div> -->
                            
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
            </div>
        </div>

        <!-- Main Product Listings -->
        <div class="col-lg-9 col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-dark fw-bold mb-1">Featured Products</h1>
                </div>
                <!-- Sort Dropdown Omitted for brevity, kept same structure -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Sort By
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Newest First</a></li>
                        <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
                    </ul>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                            
                            <!-- Product Image Logic -->
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

                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                                
                                <!-- Condition Badge -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if(strtolower($product->condition) == 'new')
                                        <span class="badge bg-success bg-opacity-90 text-white">New</span>
                                    @elseif(strtolower($product->condition) == 'used')
                                        <span class="badge bg-warning bg-opacity-90 text-dark">Used</span>
                                    @else
                                        <span class="badge bg-info bg-opacity-90 text-white">{{ ucfirst($product->condition) }}</span>
                                    @endif
                                </div>
                                
                                <!-- Favorite Button (Initial Color Logic) -->
                                <div class="position-absolute bottom-0 end-0 m-2">
                                    @auth
                                        @php
                                            $isWishlisted = Auth::user()->wishlist->contains('product_id', $product->id);
                                        @endphp
                                        <button class="btn btn-sm bg-white rounded-circle shadow-sm toggle-wishlist" 
                                                style="width: 36px; height: 36px; display: grid; place-items: center;" 
                                                data-id="{{ $product->id }}"
                                                type="button">
                                            
                                            <!-- 
                                            If you want an OUTLINE (Empty heart): Use 'far' 
                                            If you want a FILLED Gray heart: Use 'fas' 
                                            -->
                                            <i class="wishlist-icon fa-heart {{ $isWishlisted ? 'fas text-danger' : 'far text-secondary' }}"></i>
                                        </button>
                                    @else
                                        <!-- Guest User Button -->
                                        <a href="{{ route('login') }}" 
                                        class="btn btn-sm bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" 
                                        style="width: 36px; height: 36px;">
                                            <i class="far fa-heart text-secondary"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                            
                            <!-- Product Details -->
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0 text-dark fw-bold text-truncate">{{ $product->name }}</h5>
                                    <span class="badge bg-light text-dark">{{ $product->category }}</span>
                                </div>
                                
                                <!-- <div class="d-flex align-items-center mb-2">
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                        <span class="text-muted ms-1">(24)</span>
                                    </div>
                                </div> -->
                                
                                <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 80, '...') }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto">
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
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png" width="120" class="mb-4 opacity-50">
                                <h4 class="text-muted mb-3">No products found</h4>
                                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4">Reset Filters</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            @if($products->hasPages())
                <div class="d-flex flex-column align-items-center mt-5 gap-2">
                    <div class="text-muted small">
                        Showing 
                        <strong>{{ $products->firstItem() }}</strong>–<strong>{{ $products->lastItem() }}</strong> 
                        of 
                        <strong>{{ $products->total() }}</strong> products
                    </div>

                    <div>
                        {{ $products->withQueryString()->links() }}
                        {{-- or if you use Bootstrap 5 pagination views: --}}
                        {{-- {{ $products->withQueryString()->links('pagination::bootstrap-5') }} --}}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hero-banner { background-size: cover; background-position: center; }
    .product-card { transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05); }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .rounded-4 { border-radius: 1rem !important; }
    
    /* Toggle Wishlist Transition */
    .toggle-wishlist i { transition: transform 0.2s, color 0.2s; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Filter Logic
        const resetBtn = document.querySelector('.reset-filters');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                document.querySelectorAll('input[type="text"], input[type="number"], select').forEach(el => el.value = '');
                document.querySelectorAll('input[type="radio"]').forEach(el => el.checked = false);
                const defaultCond = document.getElementById('condition-all');
                if(defaultCond) defaultCond.checked = true;
            });
        }

        // 2. Wishlist Logic (Color & SVG Fix)
        const wishlistButtons = document.querySelectorAll('.toggle-wishlist');

        wishlistButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                const productId = this.getAttribute('data-id');
                const btn = this;
                
                // Select either <i> or <svg> (FontAwesome compatibility)
                const icon = this.querySelector('.fa-heart'); 

                if (!icon) return;

                // Check current state by class
                const isHeartFilled = icon.classList.contains('fas'); 
                
                // 1. Optimistic UI Update
                if (isHeartFilled) {
                    // REMOVE (Make Gray Outline)
                    icon.classList.remove('fas'); 
                    icon.classList.add('far');
                    
                    // Color Logic: Remove Red, Add Gray
                    icon.classList.remove('text-danger');
                    icon.classList.add('text-secondary');
                    
                    if(icon.tagName === 'svg') icon.setAttribute('data-prefix', 'far');
                } else {
                    // ADD (Make Red Solid)
                    icon.classList.remove('far');
                    icon.classList.add('fas');

                    // Color Logic: Add Red, Remove Gray
                    icon.classList.add('text-danger');
                    icon.classList.remove('text-secondary');
                    
                    if(icon.tagName === 'svg') icon.setAttribute('data-prefix', 'fas');

                    // Pop Animation
                    btn.style.transform = 'scale(1.2)';
                    setTimeout(() => btn.style.transform = 'scale(1)', 200);
                }

                // 2. AJAX Request
                fetch('/wishlist/' + productId, { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = '{{ route("login") }}';
                    }
                    return response.json();
                })
                .then(data => {
                    // 3. Sync with Server State (Ensure correct color/shape)
                    if (data.status === 'added') {
                        icon.classList.remove('far', 'text-secondary');
                        icon.classList.add('fas', 'text-danger');
                        if(icon.tagName === 'svg') icon.setAttribute('data-prefix', 'fas');
                    } else if (data.status === 'removed') {
                        icon.classList.remove('fas', 'text-danger');
                        icon.classList.add('far', 'text-secondary');
                        if(icon.tagName === 'svg') icon.setAttribute('data-prefix', 'far');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert UI on error
                    if (isHeartFilled) {
                        icon.classList.add('fas', 'text-danger');
                        icon.classList.remove('far', 'text-secondary');
                        if(icon.tagName === 'svg') icon.setAttribute('data-prefix', 'fas');
                    } else {
                        icon.classList.add('far', 'text-secondary');
                        icon.classList.remove('fas', 'text-danger');
                        if(icon.tagName === 'svg') icon.setAttribute('data-prefix', 'far');
                    }
                });
            });
        });
    });
</script>
@endsection