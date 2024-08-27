@extends('layouts.app')

@section('content')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 0.375rem; /* Rounded corners for input fields */
    }

    .form-text {
        font-size: 0.875rem; /* Smaller font size for helper text */
    }

    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .image-preview img {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
        border-radius: 0.375rem;
    }
</style>    
<div class="container">
    <h1 class="mt-5 mb-4 text-center text-primary">Create New Product</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-4">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group mb-4">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required>
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="category" class="form-label">Category</label>
            <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                <option value="" disabled selected>Select Category</option>
                <!-- Example categories; adjust as needed -->
                <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                <option value="Clothing" {{ old('category') == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                <!-- Add more categories as needed -->
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="condition" class="form-label">Condition</label>
            <select name="condition" id="condition" class="form-control @error('condition') is-invalid @enderror" required>
                <option value="" disabled selected>Select Condition</option>
                <!-- Example conditions; adjust as needed -->
                <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
                <option value="Used" {{ old('condition') == 'Used' ? 'selected' : '' }}>Used</option>
                <option value="Refurbished" {{ old('condition') == 'Refurbished' ? 'selected' : '' }}>Refurbished</option>
                <!-- Add more conditions as needed -->
            </select>
            @error('condition')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="images" class="form-label">Product Images</label>
            <input type="file" name="images[]" id="images" class="form-control @error('images.*') is-invalid @enderror" accept="image/*" multiple>
            @error('images.*')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">You can upload multiple images. Supported formats: jpg, jpeg, png.</small>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Image Preview</label>
            <div id="image-preview" class="image-preview"></div>
        </div>

        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>
</div>

<script>
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; // Clear previous previews

        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };
                
                reader.readAsDataURL(file);
            }
        }
    });
</script>
@endsection