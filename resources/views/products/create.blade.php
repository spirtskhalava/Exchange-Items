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
            <label for="name" class="form-label">Name</label>
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
        <option value="electronics" {{ old('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
        <option value="furniture" {{ old('category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
        <option value="clothing" {{ old('category') == 'clothing' ? 'selected' : '' }}>Clothing & Fashion</option>
        <option value="books" {{ old('category') == 'books' ? 'selected' : '' }}>Books & Literature</option>
        <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>Sports & Outdoors</option>
        <option value="gaming" {{ old('category') == 'gaming' ? 'selected' : '' }}>Gaming & Consoles</option>
        <option value="mobiles" {{ old('category') == 'mobiles' ? 'selected' : '' }}>Mobile Phones</option>
        <option value="home-garden" {{ old('category') == 'home-garden' ? 'selected' : '' }}>Home & Garden</option>
        <option value="toys" {{ old('category') == 'toys' ? 'selected' : '' }}>Toys & Hobbies</option>
        <option value="vehicles" {{ old('category') == 'vehicles' ? 'selected' : '' }}>Vehicles & Parts</option>
        <option value="music" {{ old('category') == 'music' ? 'selected' : '' }}>Music & Instruments</option>
        <option value="art" {{ old('category') == 'art' ? 'selected' : '' }}>Art & Collectibles</option>
        <option value="beauty" {{ old('category') == 'beauty' ? 'selected' : '' }}>Health & Beauty</option>
        <option value="pets" {{ old('category') == 'pets' ? 'selected' : '' }}>Pets & Supplies</option>
        <option value="office" {{ old('category') == 'office' ? 'selected' : '' }}>Office & School</option>
        <option value="baby" {{ old('category') == 'baby' ? 'selected' : '' }}>Baby & Kids</option>
        <option value="tools" {{ old('category') == 'tools' ? 'selected' : '' }}>Tools & DIY</option>
        <option value="fashion" {{ old('category') == 'fashion' ? 'selected' : '' }}>Fashion</option>
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
            <label for="images" class="form-label">Images</label>
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

        <button type="submit" class="btn btn-primary">Create</button>
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