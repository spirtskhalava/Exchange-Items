<!-- resources/views/products/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Product</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Product Name -->
            <div class="form-group mb-4">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Product Description -->
            <div class="form-group mb-4">
                <label for="description" class="form-label">Product Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Category -->
            <div class="form-group mb-4">
                <label for="category" class="form-label">Category</label>
                <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                    <option value="" disabled>Select Category</option>
                    <option value="Electronics" {{ old('category', $product->category) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                    <option value="Furniture" {{ old('category', $product->category) == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="Clothing" {{ old('category', $product->category) == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                    <!-- Add more categories as needed -->
                </select>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Condition -->
            <div class="form-group mb-4">
                <label for="condition" class="form-label">Condition</label>
                <select name="condition" id="condition" class="form-control @error('condition') is-invalid @enderror" required>
                    <option value="" disabled>Select Condition</option>
                    <option value="New" {{ old('condition', $product->condition) == 'New' ? 'selected' : '' }}>New</option>
                    <option value="Used" {{ old('condition', $product->condition) == 'Used' ? 'selected' : '' }}>Used</option>
                    <option value="Refurbished" {{ old('condition', $product->condition) == 'Refurbished' ? 'selected' : '' }}>Refurbished</option>
                    <!-- Add more conditions as needed -->
                </select>
                @error('condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Product Images -->
            <div class="form-group mb-4">
                <label for="images" class="form-label">Product Images</label>
                <div id="image-preview-container" class="mb-3">
                    @if($product->image_paths)
                        @php
                            $imagePaths = json_decode($product->image_paths, true);
                        @endphp
                        @foreach($imagePaths as $imagePath)
                            <div class="image-preview d-inline-block position-relative me-2 mb-2">
                                <img src="{{ $imagePath }}" alt="Product Image" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="removeImage(this)">X</button>
                                <input type="hidden" name="existing_images[]" value="{{ $imagePath }}">
                            </div>
                        @endforeach
                    @endif
                </div>
                <input type="file" name="new_images[]" id="images" class="form-control @error('new_images.*') is-invalid @enderror" accept="image/*" multiple>
                @error('new_images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">You can upload multiple images. Supported formats: jpg, jpeg, png.</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>

    <script>
        function removeImage(button) {
            const imagePreview = button.closest('.image-preview');
            imagePreview.remove();
        }
    </script>
@endsection