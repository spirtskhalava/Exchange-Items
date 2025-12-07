@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <!-- Breadcrumb / Back Button -->
    <div class="mb-4">
        <a href="{{ url('/products') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i> Back to Listings
        </a>
    </div>

    <div class="row g-5">
        
        <!-- LEFT SIDEBAR: Seller Info & Reviews -->
        <div class="col-lg-4">
            
            <!-- Seller Profile Card -->
            <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <!-- Initials Avatar -->
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($seller->name, 0, 1)) }}
                        </div>
                    </div>
                    <h3 class="fw-bold">{{ $seller->name }}</h3>
                    <div class="mb-2">
                        <span class="text-warning fw-bold fs-4">
                            {{ number_format($seller->reviewsReceived->avg('rating'), 1) }} ★
                        </span>
                        <span class="text-muted small">
                            ({{ $seller->reviewsReceived->count() }} reviews)
                        </span>
                    </div>
                    <p class="text-muted small">Member since {{ $seller->created_at->format('M Y') }}</p>
                </div>
            </div>

            <!-- Review Form Section -->
             <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-bold">Write a Review</div>
    <div class="card-body">
        @auth
            @if(auth()->id() === $seller->id)
                <p class="text-muted small text-center mb-0">You cannot review yourself.</p>
            
            {{-- NEW CHECK: Check if the logged-in user has already reviewed this seller --}}
            @elseif($seller->reviewsReceived->where('reviewer_id', auth()->id())->count() > 0)
                <div class="alert alert-success text-center mb-0">
                    <i class="bi bi-check-circle"></i> You have already reviewed this user.
                </div>
                
            @else
                <!-- SHOW FORM IF NO REVIEW EXISTS -->
                <form action="{{ route('user.reviews.store', $seller->id) }}" method="POST">
                    @csrf
                    <!-- ... inputs ... -->
                    <div class="mb-3">
                        <label class="form-label small text-muted">Rating</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">★★★★★ (Excellent)</option>
                            <option value="4">★★★★ (Good)</option>
                            <option value="3">★★★ (Average)</option>
                            <option value="2">★★ (Poor)</option>
                            <option value="1">★ (Terrible)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Comment</label>
                        <textarea name="comment" rows="3" class="form-control" placeholder="How was your experience?"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark btn-sm">Submit Review</button>
                    </div>
                </form>
            @endif
        @else
            <div class="text-center">
                <p class="small text-muted mb-2">Login to leave a review</p>
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
            </div>
        @endauth
    </div>
</div>

            <!-- Recent Reviews List (Scrollable) -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Recent Feedback</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                        @forelse($seller->reviewsReceived as $review)
                            <div class="list-group-item p-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong class="small">{{ $review->reviewer->name ?? 'User' }}</strong>
                                    <span class="text-warning small">{{ str_repeat('★', $review->rating) }}</span>
                                </div>
                                <p class="mb-1 small text-secondary">{{ $review->comment }}</p>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted small">
                                No reviews yet. Be the first!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT MAIN CONTENT: Items Grid -->
        <div class="col-lg-8">
            <h2 class="mb-4 fw-bold">Items by {{ $seller->name }} <span class="badge bg-secondary fs-6 align-middle">{{ $items->count() }}</span></h2>
            
            @if($items->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    @foreach($items as $item)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 product-card">
                                <!-- Image Container -->
                                <div class="position-relative" style="height: 200px; overflow: hidden;">
                                    @php
                                        $imagePaths = json_decode($item->image_paths, true);
                                        $firstImagePath = !empty($imagePaths) && isset($imagePaths[0]) 
                                            ? asset($imagePaths[0]) 
                                            : asset('storage/default-image.jpg'); 
                                            // Ensure this default image exists or use a placeholder URL like https://via.placeholder.com/300
                                    @endphp
                                    <img src="{{ $firstImagePath }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="{{ $item->name }}">
                                    
                                    <!-- Optional Badge for freshness -->
                                    @if($item->created_at->diffInDays() < 7)
                                        <span class="position-absolute top-0 start-0 badge bg-success m-2">New</span>
                                    @endif
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate" title="{{ $item->name }}">{{ $item->name }}</h5>
                                    <p class="card-text text-muted small flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->description }}
                                    </p>
                                    
                                    <div class="d-grid gap-2 mt-3">
                                        <a href="{{ route('exchanges.create', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-arrow-left-right"></i> Exchange
                                        </a>
                                        <a href="{{ route('products.show', $item->id) }}" class="btn btn-outline-secondary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <h4>No items found</h4>
                    <p>This seller hasn't listed any items for exchange yet.</p>
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    /* Slight hover effect for product cards */
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    
    /* Scrollbar styling for reviews */
    .list-group::-webkit-scrollbar {
        width: 6px;
    }
    .list-group::-webkit-scrollbar-thumb {
        background-color: #dee2e6;
        border-radius: 4px;
    }
</style>
@endsection