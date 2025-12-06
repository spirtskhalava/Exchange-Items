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
        <!-- Product Images Section -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <!-- Main Image with Floating Actions -->
                <div class="position-relative">
                    @if($product->image_paths)
                        @php
                            $imagePaths = json_decode($product->image_paths, true);
                            $firstImagePath = $imagePaths[0] ?? asset('storage/default-image.jpg');
                        @endphp
                        <img src="{{ $firstImagePath }}" class="w-100 product-main-image" alt="{{ $product->name }}" style="height: 500px; object-fit: contain; background: #f8f9fa;" data-bs-toggle="modal" data-bs-target="#imageModal">
                    @else
                        <img src="{{ asset('storage/default-image.jpg') }}" class="w-100 product-main-image" alt="Default Image" style="height: 500px; object-fit: contain; background: #f8f9fa;">
                    @endif

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
                <div class="p-3">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($imagePaths as $index => $image)
                            <div class="thumbnail-container position-relative" style="width: 80px; height: 80px;">
                                <img src="{{ $image }}" alt="{{ $product->name }}" 
                                     class="img-thumbnail w-100 h-100 object-fit-cover cursor-pointer"
                                     style="{{ $index === 0 ? 'border: 2px solid #0d6efd;' : '' }}"
                                     onclick="changeMainImage(this, '{{ $image }}')">
                                @if($index === 0)
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(13, 110, 253, 0.1);">
                                        <i class="fas fa-check-circle text-primary"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Product Details Tabs -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Details</button>
                        </li>
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">Specifications</button>
                        </li> -->
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews</button>
                        </li> -->
                    </ul>
                    <div class="tab-content pt-3" id="productTabsContent">
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <h5 class="mb-3">About this item</h5>
                            <div class="product-description">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="specs" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Category</th>
                                            <td>{{ $product->category }}</td>
                                        </tr>
                                        <tr>
                                            <th>Condition</th>
                                            <td>
                                                <span class="badge bg-{{ $product->condition == 'new' ? 'success' : ($product->condition == 'used' ? 'warning text-dark' : 'info') }}">
                                                    {{ ucfirst($product->condition) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Posted On</th>
                                            <td>{{ $product->created_at->format('M d, Y') }} ({{ $product->created_at->diffForHumans() }})</td>
                                        </tr>
                                        <tr>
                                            <th>Views</th>
                                            <td>{{ number_format($product->views) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <span class="display-6 fw-bold">4.8</span>
                                    <span class="text-muted">/5</span>
                                </div>
                                <div>
                                    <div class="text-warning mb-1">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <div class="text-muted small">Based on 24 reviews</div>
                                </div>
                            </div>
                            
                            <!-- Review Form (for authenticated users) -->
                            @auth
                                <div class="card bg-light border-0 mb-4">
                                    <div class="card-body">
                                        <h6 class="mb-3">Write a review</h6>
                                        <form>
                                            <div class="mb-3">
                                                <div class="rating-input">
                                                    <input type="radio" id="star5" name="rating" value="5">
                                                    <label for="star5"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star4" name="rating" value="4">
                                                    <label for="star4"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star3" name="rating" value="3">
                                                    <label for="star3"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star2" name="rating" value="2">
                                                    <label for="star2"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star1" name="rating" value="1">
                                                    <label for="star1"><i class="fas fa-star"></i></label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <textarea class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
                                        </form>
                                    </div>
                                </div>
                            @endauth
                            
                            <!-- Reviews List -->
                            <div class="review-list">
                                <div class="review-item mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" class="rounded-circle me-2" width="40" height="40" alt="User">
                                            <div>
                                                <h6 class="mb-0">John Doe</h6>
                                                <small class="text-muted">Verified Buyer • 2 days ago</small>
                                            </div>
                                        </div>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <p class="mb-0">Great product! Exactly as described. The seller was very responsive and shipping was fast.</p>
                                </div>
                                
                                <!-- More review items would go here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Action Section -->
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 20px;">
                <!-- Product Info Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                                <h1 class="h3 fw-bold mb-2">{{ $product->name }}</h1>
                            <span class="badge bg-{{ $product->condition == 'new' ? 'success' : ($product->condition == 'used' ? 'warning text-dark' : 'info') }} bg-opacity-10 text-{{ $product->condition == 'new' ? 'success' : ($product->condition == 'used' ? 'warning' : 'info') }}">
                                {{ ucfirst($product->condition) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <div class="text-success small mt-1">
                                <i class="fas fa-check-circle"></i> Available for exchange
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                <span>Location: {{ $product->location ?? 'Not specified' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-eye text-muted me-2"></i>
                                <span>{{ number_format($product->views) }} views</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                <span>Posted {{ $product->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if($product->user_id !== Auth::id())
                                <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-warning btn-lg py-3 fw-bold">
                                    <i class="fas fa-exchange-alt me-2"></i> Make an Offer
                                </a>
                            @endif

                            <!-- @auth
                                <form action="{{ route('wishlist.store', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-lg py-3">
                                        <i class="far fa-heart me-2"></i> Add to Wishlist
                                    </button>
                                </form>
                            @endauth -->

                            <a href="{{ route('messages.openChatWithSeller', $product->user->id) }}" class="btn btn-outline-secondary btn-lg py-3">
                                <i class="fas fa-comment-dots me-2"></i> Contact Seller
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Seller Information Card -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Seller Information</h5>
                        <div class="d-flex align-items-center mb-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($product->user->name) }}&background=random" class="rounded-circle me-3" width="60" height="60" alt="Seller">
                            <div>
                                <h6 class="mb-1"><a href="{{ route('seller.items', $product->user->id) }}" class="text-decoration-none">{{ $product->user->name }}</a></h6>
                                <div class="text-muted small">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        <span>4.8 (24 reviews)</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-store me-1"></i>
                                        <span>Member since {{ $product->user->created_at->format('M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('seller.items', $product->user->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-box-open me-1"></i> View all items
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Safety Tips Card -->
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Safety Tips</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <div class="d-flex">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <span>Meet in public places</span>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="d-flex">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <span>Check the item before exchanging</span>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="d-flex">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <span>Don't share personal financial information</span>
                                </div>
                            </li>
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
        <div class="modal-content border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modal-image" src="" class="img-fluid w-100" style="max-height: 80vh; object-fit: contain;" alt="Product Image">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .product-main-image {
        cursor: zoom-in;
        transition: transform 0.3s ease;
    }
    
    .product-main-image:hover {
        transform: scale(1.02);
    }
    
    .thumbnail-container {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .thumbnail-container:hover {
        transform: translateY(-3px);
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.5rem 1rem;
        margin-right: 1rem;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background: none;
        border-bottom: 3px solid #0d6efd;
    }
    
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    
    .rating-input input {
        display: none;
    }
    
    .rating-input label {
        color: #ddd;
        font-size: 1.5rem;
        padding: 0 0.2rem;
        cursor: pointer;
    }
    
    .rating-input input:checked ~ label,
    .rating-input label:hover,
    .rating-input label:hover ~ label {
        color: #ffc107;
    }
    
    .sticky-top {
        z-index: 1;
    }
    
    .product-description {
        white-space: pre-line;
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<script>
    // Change main image when thumbnail is clicked
    function changeMainImage(thumbnail, newSrc) {
        const mainImage = document.querySelector('.product-main-image');
        mainImage.src = newSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-container').forEach(container => {
            container.querySelector('img').style.border = '1px solid #dee2e6';
            container.querySelector('.position-absolute')?.remove();
        });
        
        thumbnail.style.border = '2px solid #0d6efd';
        thumbnail.insertAdjacentHTML('afterend', 
            `<div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(13, 110, 253, 0.1);">
                <i class="fas fa-check-circle text-primary"></i>
            </div>`);
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Set modal image when clicked
        const clickableImages = document.querySelectorAll('.product-main-image, .img-thumbnail');
        clickableImages.forEach(img => {
            img.addEventListener('click', function() {
                document.getElementById('modal-image').src = this.src;
            });
        });
    });
</script>
@endsection