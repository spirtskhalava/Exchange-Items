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
</style>    
<div class="container">
    <h1 class="mt-5 mb-4 text-center text-primary">Create New Product</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-4">
            <label for="images" class="form-label">Product Images</label>
            <input type="file" name="images[]" id="images" class="form-control @error('images.*') is-invalid @enderror" accept="image/*" multiple>
            @error('images.*')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">You can upload multiple images. Supported formats: jpg, jpeg, png.</small>
        </div>

        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>
</div>
@endsection