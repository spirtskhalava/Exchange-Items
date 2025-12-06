@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- LEFT COLUMN: Images & Details -->
        <div class="col-lg-7">
            
            <!-- IMAGE SECTION -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <!-- Main Image -->
                <div class="position-relative">
                    @php
                        // 1. Decode JSON safely
                        $imagePaths = !empty($product->image_paths) ? json_decode($product->image_paths, true) : [];
                        
                        // 2. Determine Main Image URL
                        $mainImageUrl = 'https://placehold.co/600x600?text=No+Image';
                        
                        // Check if we have images
                        if (!empty($imagePaths) && isset($imagePaths[0])) {
                             if (str_starts_with($imagePaths[0], 'http')) {
                                 $mainImageUrl = $imagePaths[0];
                             } else {
                                 $mainImageUrl = asset('storage/' . $imagePaths[0]);
                             }
                        }
                    @endphp

                    <img src="{{ $mainImageUrl }}" 
                         class="w-100 product-main-image" 
                         alt="{{ $product->name }}" 
                         style="height: 500px; object-fit: contain; background: #f8f9fa;" 
                         data-bs-toggle="modal" 
                         data-bs-target="#imageModal">

                    <!-- Floating Action Buttons -->
                    <div class="position-absolute top-0 end-0 p-3">
                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 40px; height: 40px;" data-bs-toggle="tooltip" data-bs-placement="left" title="Share">
                            <i class="fas fa-share-alt"></i>
                        </button>
                        @auth
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm mt-2" style="width: 40px; height: 40px;" data-bs-toggle="tooltip" data-bs-placement="left" title="Save to wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                        @endauth
                    </div>
                </div>

                <!-- Thumbnail Gallery -->
                <div class="p-3 bg-white">
                    <div class="d-flex flex-wrap gap-2">
                        @if(!empty($imagePaths))
                            @foreach($imagePaths as $index => $path)
                                @php
                                    if (str_starts_with($path, 'http')) {
                                        $thumbUrl = $path;
                                    } else {
                                        $thumbUrl = asset('storage/' . $path);
                                    }
                                @endphp
                                
                                <div class="thumbnail-container position-relative" style="width: 80px; height: 80px;">
                                    <img src="{{ $thumbUrl }}" 
                                         alt="Thumbnail {{ $index + 1 }}" 
                                         class="img-thumbnail w-100 h-100 object-fit-cover cursor-pointer"
                                         style="{{ $index === 0 ? 'border: 2px solid #0d6efd;' : '' }}"
                                         onclick="changeMainImage(this, '{{ $thumbUrl }}')">
                                    
                                    @if($index === 0)
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center active-overlay" style="background-color: rgba(13, 110, 253, 0.1);">
                                            <i class="fas fa-check-circle text-primary"></i>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                             <!-- Placeholder if no images -->
                             <div class="thumbnail-container position-relative" style="width: 80px; height: 80px;">
                                <img src="https://placehold.co/600x600?text=No+Image" class="img-thumbnail w-100 h-100 object-fit-cover">
                             </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TABS: Description & Reviews -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                                Reviews ({{ $product->review_count }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content pt-4" id="productTabsContent">
                        
                        <!-- DETAILS TAB -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <h5 class="mb-3">About this item</h5>
                            <div class="product-description text-muted">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>

                        <!-- REVIEWS TAB -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <!-- Summary Section -->
                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                                <div class="me-4 text-center">
                                    <span class="display-4 fw-bold text-dark">{{ number_format($product->average_rating, 1) }}</span>
                                    <span class="text-muted d-block small">out of 5</span>
                                </div>
                                <div>
                                    <div class="mb-1 text-warning fs-5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($product->average_rating) ? '' : 'text-muted opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="text-muted small">Based on {{ $product->review_count }} reviews</div>
                                </div>
                            </div>
                            
                            <!-- Review Form -->
                            @auth
                                @if(Auth::id() !== $product->user_id)
                                    @php
                                        // Check if user already reviewed via the relationship
                                        $userReview = $product->reviews->where('user_id', Auth::id())->first();
                                    @endphp

                                    @if(!$userReview)
                                        <div class="card border-0 shadow-sm mb-5">
                                            <div class="card-body p-4">
                                                <h6 class="mb-3 fw-bold">Write a review</h6>
                                                <form action="{{ route('products.review.store', $product->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted">Your Rating</label>
                                                        <!-- Star Rating Input -->
                                                        <div class="rating-input">
                                                            <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="Excellent"><i class="fas fa-star"></i></label>
                                                            <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="Good"><i class="fas fa-star"></i></label>
                                                            <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="Average"><i class="fas fa-star"></i></label>
                                                            <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="Poor"><i class="fas fa-star"></i></label>
                                                            <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="Very Poor"><i class="fas fa-star"></i></label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Submit Review</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-success mb-4"><i class="fas fa-check-circle me-2"></i> You have already reviewed this item.</div>
                                    @endif
                                @endif
                            @else
                                <div class="alert alert-secondary mb-4">Please <a href="{{ route('login') }}" class="alert-link">login</a> to write a review.</div>
                            @endauth
                            
                            <!-- Reviews List -->
                            <div class="review-list">
                                @forelse($product->reviews as $review)
                                    <div class="review-item border-bottom pb-4 mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random" class="rounded-circle me-3" width="45" height="45" alt="User">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $review->user->name }}</h6>
                                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted opacity-25' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($review->comment)
                                            <p class="mb-0 text-secondary">{{ $review->comment }}</p>
                                        @else
                                            <p class="mb-0 text-muted fst-italic small">No comment provided.</p>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <div class="text-muted mb-3"><i class="far fa-comment-dots fa-3x"></i></div>
                                        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Sidebar Action -->
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 20px;">
                <!-- Product Info Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="h3 fw-bold mb-0 text-break">{{ $product->name }}</h1>
                            <span class="badge bg-{{ strtolower($product->condition) == 'new' ? 'success' : (strtolower($product->condition) == 'used' ? 'warning text-dark' : 'info') }} bg-opacity-10 text-{{ strtolower($product->condition) == 'new' ? 'success' : (strtolower($product->condition) == 'used' ? 'warning' : 'info') }} ms-2">
                                {{ ucfirst($product->condition) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <span class="h4 fw-bold me-2 text-primary">
                                    {{ $product->price > 0 ? '$' . number_format($product->price, 2) : 'Price Negotiable' }}
                                </span>
                            </div>
                            <div class="text-success small mt-1">
                                <i class="fas fa-check-circle"></i> Available for exchange
                            </div>
                        </div>

                        <div class="mb-4 text-muted small">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt me-2 text-center" style="width: 20px;"></i>
                                <span>{{ $product->location ?? 'Location not specified' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-eye me-2 text-center" style="width: 20px;"></i>
                                <span>{{ number_format($product->views) }} views</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2 text-center" style="width: 20px;"></i>
                                <span>Posted {{ $product->created_at->diffForHumans() }}</span>
                            </div>
                        </div>


                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if(!Auth::check() && !$product->user_id === Auth::id())
                                <!-- If Visitor: Show Offer/Contact Buttons -->
                                <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-warning btn-lg py-3 fw-bold">
                                    <i class="fas fa-exchange-alt me-2"></i> Make an Offer
                                </a>
                                
                                <a href="{{ route('messages.openChatWithSeller', $product->user->id) }}" class="btn btn-outline-secondary btn-lg py-3">
                                    <i class="fas fa-comment-dots me-2"></i> Contact Seller
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Seller Information Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4 fw-bold">Seller Information</h5>
                        <div class="d-flex align-items-center mb-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($product->user->name) }}&background=random" class="rounded-circle me-3" width="60" height="60" alt="Seller">
                            <div>
                                <h6 class="mb-1 fw-bold">
                                    <a href="{{ route('seller.items', $product->user->id) }}" class="text-decoration-none text-dark">{{ $product->user->name }}</a>
                                </h6>
                                <div class="text-muted small">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-store me-1"></i>
                                        <span>Member since {{ $product->user->created_at->format('M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('seller.items', $product->user->id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-box-open me-1"></i> View all items
                            </a>
                        </div>
                    </div>
                </div>
                
                 <!-- Safety Tips Card -->
                 <div class="card border-0 shadow-sm rounded-4 bg-light">
                    <div class="card-body">
                        <h6 class="card-title mb-3 fw-bold"><i class="fas fa-shield-alt text-primary me-2"></i>Safety Tips</h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Meet in public places</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Check items before exchange</li>
                            <li><i class="fas fa-check text-success me-2"></i>Don't transfer money blindly</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modal-image" src="" class="img-fluid rounded-3" style="max-height: 85vh; object-fit: contain;" alt="Product Image">
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .product-main-image { cursor: zoom-in; transition: transform 0.3s ease; }
    .product-main-image:hover { transform: scale(1.02); }
    .thumbnail-container { cursor: pointer; transition: all 0.3s ease; }
    .thumbnail-container:hover { transform: translateY(-3px); }
    .rounded-4 { border-radius: 1rem !important; }
    .nav-tabs .nav-link { border: none; color: #6c757d; font-weight: 500; padding: 0.5rem 1rem; margin-right: 1rem; }
    .nav-tabs .nav-link.active { color: #0d6efd; background: none; border-bottom: 3px solid #0d6efd; }
    .sticky-top { z-index: 1; }
    .product-description { white-space: pre-line; }
    .object-fit-cover { object-fit: cover; }
    
    /* Star Rating CSS */
    .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; }
    .rating-input input { display: none; }
    .rating-input label { color: #ddd; font-size: 1.5rem; cursor: pointer; transition: color 0.2s; }
    .rating-input input:checked ~ label,
    .rating-input label:hover,
    .rating-input label:hover ~ label { color: #ffc107; }
</style>
@endpush

@push('scripts')
<script>
    function changeMainImage(thumbnail, newSrc) {
        // Update Main Image
        const mainImage = document.querySelector('.product-main-image');
        mainImage.src = newSrc;
        
        // Remove active styling from all thumbnails
        document.querySelectorAll('.thumbnail-container').forEach(container => {
            const img = container.querySelector('img');
            img.style.border = '1px solid #dee2e6';
            const overlay = container.querySelector('.active-overlay');
            if(overlay) overlay.remove();
        });
        
        // Add active styling to clicked thumbnail
        const container = thumbnail.closest('.thumbnail-container');
        const img = container.querySelector('img');
        img.style.border = '2px solid #0d6efd';
        
        const overlay = document.createElement('div');
        overlay.className = 'position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center active-overlay';
        overlay.style.backgroundColor = 'rgba(13, 110, 253, 0.1)';
        overlay.innerHTML = '<i class="fas fa-check-circle text-primary"></i>';
        container.appendChild(overlay);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Update Modal Image when opening
        const modalEl = document.getElementById('imageModal');
        const modalImage = document.getElementById('modal-image');
        
        if (modalEl) {
            modalEl.addEventListener('show.bs.modal', function (event) {
                const mainImage = document.querySelector('.product-main-image');
                modalImage.src = mainImage.src;
            });
        }
        
        // Auto-switch to Reviews tab if URL has #reviews
        if(window.location.hash === '#reviews') {
            const reviewTabBtn = document.querySelector('#reviews-tab');
            if(reviewTabBtn) {
                const tab = new bootstrap.Tab(reviewTabBtn);
                tab.show();
                reviewTabBtn.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>
@endpush