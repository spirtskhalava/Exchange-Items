@extends('layouts.app')

@section('content')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary mb-3">
            <i class="bi bi-pencil-square me-2"></i>Edit Your Listing
        </h1>
        <p class="lead text-muted">Update your product details to attract more buyers</p>
    </div>

    <!-- Edit Form -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-4 p-md-5">
            <!-- Ensure your route is correct here -->
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
                @csrf
                @method('PUT')

                <!-- Product Name -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-card-heading text-muted"></i>
                        </span>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" 
                               placeholder="Enter product name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Product Description -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light align-items-start">
                            <i class="bi bi-text-paragraph text-muted mt-1"></i>
                        </span>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4"
                                  placeholder="Describe your product in detail" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Product Location -->
                <div class="mb-4">
                    <label for="location" class="form-label fw-bold">Location</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-geo-alt text-muted"></i>
                        </span>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location', $product->location) }}"
                               placeholder="Where is the item located?" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Category & Condition Row -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="category" class="form-label fw-bold">Category</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-tags text-muted"></i>
                            </span>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="" disabled>Select Category</option>
                                <option value="electronics" {{ old('category', $product->category) == 'electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="furniture" {{ old('category', $product->category) == 'furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="clothing" {{ old('category', $product->category) == 'clothing' ? 'selected' : '' }}>Clothing</option>
                                <option value="home-garden" {{ old('category', $product->category) == 'home-garden' ? 'selected' : '' }}>Home & Garden</option>
                                <option value="sports" {{ old('category', $product->category) == 'sports' ? 'selected' : '' }}>Sports</option>
                                <option value="gaming" {{ old('category', $product->category) == 'gaming' ? 'selected' : '' }}>Gaming</option>
                                <option value="mobiles" {{ old('category', $product->category) == 'mobiles' ? 'selected' : '' }}>Mobiles</option>
                                <option value="toys" {{ old('category', $product->category) == 'toys' ? 'selected' : '' }}>Toys</option>
                                <option value="vehicles" {{ old('category', $product->category) == 'vehicles' ? 'selected' : '' }}>Vehicles</option>
                                <option value="music" {{ old('category', $product->category) == 'music' ? 'selected' : '' }}>Music</option>
                                <option value="art" {{ old('category', $product->category) == 'art' ? 'selected' : '' }}>Art</option>
                                <option value="beauty" {{ old('category', $product->category) == 'beauty' ? 'selected' : '' }}>Beauty</option>
                                <option value="pets" {{ old('category', $product->category) == 'pets' ? 'selected' : '' }}>Pets</option>
                                <option value="office" {{ old('category', $product->category) == 'office' ? 'selected' : '' }}>Office</option>
                                <option value="baby" {{ old('category', $product->category) == 'baby' ? 'selected' : '' }}>Baby</option>
                                <option value="tools" {{ old('category', $product->category) == 'tools' ? 'selected' : '' }}>Tools</option>
                                <option value="other" {{ old('category', $product->category) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="condition" class="form-label fw-bold">Condition</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-clipboard-check text-muted"></i>
                            </span>
                            <select name="condition" id="condition" class="form-select @error('condition') is-invalid @enderror" required>
                                <option value="" disabled>Select Condition</option>
                                <option value="New" {{ old('condition', $product->condition) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Used" {{ old('condition', $product->condition) == 'Used' ? 'selected' : '' }}>Used</option>
                                <option value="Refurbished" {{ old('condition', $product->condition) == 'Refurbished' ? 'selected' : '' }}>Refurbished</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Images</label>
                    <p class="text-muted small mb-3">Upload high-quality images to showcase your product (max 5 images)</p>
                    
                    <!-- Image Preview Grid -->
                    <div class="row g-3 mb-3" id="image-preview-container">
                        @if($product->image_paths)
                            @php
                                $imagePaths = is_string($product->image_paths) ? json_decode($product->image_paths, true) : $product->image_paths;
                            @endphp
                            @if($imagePaths)
                                @foreach($imagePaths as $index => $imagePath)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="image-preview-card position-relative rounded-3 overflow-hidden">
                                            <!-- Assuming storage link is set up correctly -->
                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Product Image" class="img-fluid w-100" style="height: 180px; object-fit: cover;">
                                            <div class="image-actions position-absolute top-0 end-0 p-2">
                                                <!-- NOTE: Real removal requires AJAX or a hidden input to track deletions -->
                                                <button type="button" class="btn btn-danger btn-sm rounded-circle shadow" onclick="removeImage(this)" data-bs-toggle="tooltip" title="Remove image (Visual only)">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                            <!-- Hidden input to keep track of existing images if your controller supports it -->
                                            <input type="hidden" name="existing_images[]" value="{{ $imagePath }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div>

                    <!-- File Upload with Preview -->
                    <div class="file-upload-wrapper">
                        <input type="file" name="images[]" id="images" class="form-control d-none" accept="image/*" multiple>
                        <label for="images" class="file-upload-label w-100">
                            <div class="border-2 border-dashed rounded-3 p-4 text-center cursor-pointer">
                                <i class="bi bi-cloud-arrow-up fs-1 text-muted mb-2"></i>
                                <h5 class="mb-1">Drag & drop images or click to browse</h5>
                                <p class="small text-muted mb-0">Supports JPG, PNG (Max 5MB each)</p>
                            </div>
                        </label>
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Update Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Modern form styling */
    .card { border: none; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
    .form-control, .form-select { padding: 0.75rem 1rem; border: 1px solid #e0e0e0; }
    .form-control:focus, .form-select:focus { border-color: #4361ee; box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15); }
    .input-group-text { background-color: #f8f9fa; border-right: none; }
    .input-group .form-control, .input-group .form-select { border-left: none; }
    .image-preview-card { border: 1px solid #e0e0e0; transition: all 0.3s ease; }
    .image-preview-card:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.08); }
    .image-actions { opacity: 0; transition: opacity 0.2s ease; }
    .image-preview-card:hover .image-actions { opacity: 1; }
    .file-upload-label { cursor: pointer; }
    .file-upload-label:hover { background-color: #f8f9fa; }
    .border-dashed { border-style: dashed !important; }
    .cursor-pointer { cursor: pointer; }
    @media (max-width: 768px) {
        .display-5 { font-size: 2rem; }
        .lead { font-size: 1.1rem; }
    }
</style>
@endpush

@push('scripts')
<script>
    function removeImage(button) {
        const previewCard = button.closest('.image-preview-card');
        const colDiv = previewCard.closest('[class*="col-"]'); 
        if (colDiv) colDiv.remove();
        // Note: For real deletion of existing images, you likely need an AJAX call 
        // to your removeImage route here.
    }

    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        const fileInput = document.getElementById('images');
        const previewContainer = document.getElementById('image-preview-container');

        if(fileInput) {
            fileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                const existingImages = previewContainer.querySelectorAll('.image-preview-card').length;

                if (files.length + existingImages > 5) {
                    alert('You can upload a maximum of 5 images');
                    this.value = '';
                    return;
                }

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (!file.type.match('image.*')) continue;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const colDiv = document.createElement('div');
                        colDiv.className = 'col-6 col-md-4 col-lg-3';
                        
                        // Simplified preview structure
                        colDiv.innerHTML = `
                            <div class="image-preview-card position-relative rounded-3 overflow-hidden">
                                <img src="${e.target.result}" class="img-fluid w-100" style="height: 180px; object-fit: cover;">
                                <div class="image-actions position-absolute top-0 end-0 p-2">
                                    <button type="button" class="btn btn-danger btn-sm rounded-circle shadow" onclick="removeImage(this)">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        previewContainer.appendChild(colDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush

@endsection  <!-- THIS WAS MISSING -->