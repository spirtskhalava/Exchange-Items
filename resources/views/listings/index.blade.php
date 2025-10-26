@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<div class="container py-5">
    <!-- Page Header with Illustration -->
    <div class="text-center mb-5">
        <div class="mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#4361ee" class="bi bi-box-seam" viewBox="0 0 16 16">
                <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
            </svg>
        </div>
        <h1 class="display-5 fw-bold text-primary mb-3">Your Listings</h1>
        <p class="lead text-muted">Manage all your listed products in one place</p>
    </div>

    <!-- Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <span class="badge bg-primary rounded-pill">{{ $products->count() }} items</span>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add New Product
        </a>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2" style="font-size: 1.25rem;"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="text-center py-5 bg-light rounded-4">
            <div class="mb-4">
                <i class="bi bi-box text-muted" style="font-size: 3.5rem;"></i>
            </div>
            <h3 class="h4 text-muted mb-3">No Listings Yet</h3>
            <p class="text-muted mb-4">Start by adding your first product to showcase</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-2"></i>Create Listing
            </a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card listing-card h-100 border-0 shadow-sm overflow-hidden">
                        <!-- Product Image -->
                        @if($product->image_paths)
                            @php
                                $imagePaths = json_decode($product->image_paths, true);
                                $firstImagePath = $imagePaths[0] ?? 'default-image.jpg';
                            @endphp
                            <div class="listing-image-container">
                                <img src="{{ $firstImagePath }}" class="card-img-top" alt="{{ $product->name }}" loading="lazy">
                            </div>
                        @else
                            <div class="listing-image-container bg-light">
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        @endif

                        <div class="card-body">
                            <!-- Product Info -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h3 class="h5 card-title mb-0">{{ $product->name }}</h3>
                                <span class="badge bg-light text-dark">{{ ucfirst($product->condition) }}</span>
                            </div>
                            
                            <p class="card-text text-muted mb-4">{{ Str::limit($product->description, 120) }}</p>
                            
                            <!-- Stats -->
                            <div class="d-flex justify-content-between text-muted small mb-4">
                                <span><i class="bi bi-eye me-1"></i> {{ $product->views }} views</span>
                                <span>Listed {{ $product->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary flex-grow-1">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                                <a href="{{ route('listings.edit', $product) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('listings.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
    /* Modern CSS for listings page */
    .listing-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .listing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .listing-image-container {
        height: 200px;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    
    .listing-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .listing-card:hover .listing-image-container img {
        transform: scale(1.05);
    }
    
    .text-primary {
        color: #4361ee !important;
    }
    
    .btn-primary {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .btn-primary:hover {
        background-color: #3a56d4;
        border-color: #3a56d4;
    }
    
    .btn-outline-primary {
        color: #4361ee;
        border-color: #4361ee;
    }
    
    .btn-outline-primary:hover {
        background-color: #4361ee;
        color: white;
    }
    
    .alert-success {
        border-left: 4px solid #2ecc71;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }
        
        .lead {
            font-size: 1.1rem;
        }
        
        .listing-image-container {
            height: 160px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Add confirmation for delete actions
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this listing?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush