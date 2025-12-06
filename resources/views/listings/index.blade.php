@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- Background decoration -->
<div class="position-fixed top-0 start-0 w-100 h-100" style="z-index: -1; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);"></div>

<div class="container py-5">
    <!-- Page Header with Illustration -->
    <div class="text-center mb-5 pt-3">
        <h1 class="display-5 fw-bold text-dark mb-2">Your Listings</h1>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">Manage your inventory, track views, and update your product details from your personal dashboard.</p>
    </div>

    <!-- Action Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-5 p-3 bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold text-secondary text-uppercase small ls-1">Total Inventory</span>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold">{{ $products->count() }} Items</span>
            </div>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm hover-lift">
                <i class="bi bi-plus-lg me-2"></i>Add New Product
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center mb-4" role="alert">
            <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bi bi-check-lg"></i>
            </div>
            <div class="fw-medium">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 border border-dashed shadow-sm mt-4">
            <div class="mb-3 p-4 d-inline-block bg-light rounded-circle">
                <i class="bi bi-box-seam text-muted opacity-50" style="font-size: 3rem;"></i>
            </div>
            <h3 class="h4 text-dark mb-2">No Listings Yet</h3>
            <p class="text-muted mb-4">Your inventory is empty. Start selling by adding your first product.</p>
            <a href="{{ route('products.create') }}" class="btn btn-outline-primary px-4 rounded-pill">
                Create First Listing
            </a>
        </div>
    @else
        <!-- Changed to 3 columns on large screens for better density -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card listing-card h-100 border-0 shadow-sm bg-white">
                        
                        <!-- Product Image Wrapper -->
                        <div class="position-relative">
                            <!-- Floating Condition Badge -->
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-dark shadow-sm py-2 px-3 rounded-pill text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    {{ ucfirst($product->condition) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <!-- Product Info -->
                            <div class="mb-3">
                                <h3 class="h6 card-title fw-bold text-dark mb-2 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
                                <p class="card-text text-muted small lh-sm" style="height: 40px; overflow: hidden;">{{ Str::limit($product->description, 90) }}</p>
                            </div>
                            
                            <!-- Stats Row -->
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 mb-4 mt-auto">
                                <div class="d-flex align-items-center text-muted small" title="Total Views">
                                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
                                    <span class="fw-semibold">{{ $product->views }}</span> Views
                                </div>
                                <div class="text-muted small" title="Date Listed">
                                    <i class="bi bi-clock me-1"></i> {{ $product->created_at->diffForHumans(null, true) }}
                                </div>
                            </div>
                            
                            <!-- Action Buttons Grid -->
                            <div class="row g-2">
                                <div class="col-12">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary w-100 rounded-3 fw-medium btn-sm py-2">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> View Details
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('listings.edit', $product) }}" class="btn btn-light text-secondary w-100 rounded-3 btn-sm py-2 border hover-dark">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </a>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('listings.destroy', $product) }}" method="POST" class="w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light text-danger w-100 rounded-3 btn-sm py-2 border hover-danger">
                                            <i class="bi bi-trash3 me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* UI/UX Improvements */
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .ls-1 {
        letter-spacing: 1px;
    }

    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
        border-color: #dee2e6 !important;
    }

    /* Card Styling */
    .listing-card {
        border-radius: 1rem;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
    }
    
    .listing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(67, 97, 238, 0.1) !important;
    }
    
    .listing-image-container {
        height: 220px;
        overflow: hidden;
        position: relative;
    }
    
    .listing-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .listing-card:hover .listing-image-container img {
        transform: scale(1.08);
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: #4361ee;
        border-color: #4361ee;
        transition: all 0.2s ease;
    }
    
    .btn-primary:hover {
        background-color: #3f37c9;
        border-color: #3f37c9;
        transform: translateY(-2px);
    }
    
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.25) !important;
    }

    .hover-dark:hover {
        background-color: #e9ecef;
        color: #212529 !important;
        border-color: #dee2e6;
    }

    .hover-danger:hover {
        background-color: #fff5f5;
        color: #dc3545 !important;
        border-color: #ffc9c9;
    }
    
    .text-primary {
        color: #4361ee !important;
    }

    .bg-primary {
        background-color: #4361ee !important;
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    @media (max-width: 768px) {
        .listing-image-container {
            height: 180px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Improved confirmation message
                if (!confirm('⚠️ Are you sure you want to delete this listing?\n\nThis action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush