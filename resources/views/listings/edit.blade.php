@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:720px;">

    {{-- Header --}}
    <div class="mb-4">
        <a href="{{ route('listings.index') }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-1 mb-2">
            <i class="bi bi-arrow-left"></i> My Listings
        </a>
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">Edit Listing</h1>
        <p class="text-muted small mt-1 mb-0">Update your item details</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2 px-3 small mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="e.g. Sony PlayStation 5" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe your item..." required>{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <div class="position-relative">
                        <i class="bi bi-geo-alt position-absolute" style="top:50%;transform:translateY(-50%);left:.75rem;color:var(--muted);"></i>
                        <input type="text" name="location" value="{{ old('location', $product->location) }}"
                               class="form-control @error('location') is-invalid @enderror"
                               style="padding-left:2.2rem!important;"
                               placeholder="City, Country" required>
                    </div>
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="" disabled>Select category</option>
                            @foreach(['electronics'=>'Electronics','furniture'=>'Furniture','clothing'=>'Clothing & Fashion','books'=>'Books','sports'=>'Sports & Outdoors','gaming'=>'Gaming','mobiles'=>'Mobile Phones','home-garden'=>'Home & Garden','toys'=>'Toys & Hobbies','vehicles'=>'Vehicles','music'=>'Music & Instruments','art'=>'Art & Collectibles','beauty'=>'Health & Beauty','pets'=>'Pets','office'=>'Office & School','baby'=>'Baby & Kids','tools'=>'Tools & DIY','fashion'=>'Fashion','other'=>'Other'] as $val => $label)
                                <option value="{{ $val }}" {{ old('category', $product->category) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                            <option value="" disabled>Select condition</option>
                            <option value="New"         {{ old('condition', $product->condition) == 'New'         ? 'selected' : '' }}>New</option>
                            <option value="Used"        {{ old('condition', $product->condition) == 'Used'        ? 'selected' : '' }}>Used</option>
                            <option value="Refurbished" {{ old('condition', $product->condition) == 'Refurbished' ? 'selected' : '' }}>Refurbished</option>
                        </select>
                        @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Existing images --}}
                @if($product->image_paths)
                    @php $imagePaths = is_string($product->image_paths) ? json_decode($product->image_paths, true) : $product->image_paths; @endphp
                    @if(!empty($imagePaths))
                    <div class="mb-3">
                        <label class="form-label">Current Photos</label>
                        <div class="row g-2" id="image-preview-container">
                            @foreach($imagePaths as $imagePath)
                            <div class="col-4 col-md-3">
                                <div class="position-relative rounded-2 overflow-hidden" style="height:90px;">
                                    <img src="{{ asset('storage/' . $imagePath) }}" class="w-100 h-100" style="object-fit:cover;" alt="Photo">
                                    <button type="button" onclick="this.closest('.col-4, .col-md-3').remove()"
                                            class="btn btn-sm btn-danger position-absolute" style="top:4px;right:4px;width:22px;height:22px;padding:0;line-height:1;font-size:.7rem;border-radius:50%;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <input type="hidden" name="existing_images[]" value="{{ $imagePath }}">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif

                {{-- Upload new images --}}
                <div class="mb-4">
                    <label class="form-label">Add New Photos <span class="text-muted fw-normal">(optional)</span></label>
                    <label for="images" class="d-block border rounded-2 text-center p-4"
                           style="border-style:dashed!important;background:var(--bg);cursor:pointer;transition:background .15s;"
                           onmouseover="this.style.background='#edf0fb'" onmouseout="this.style.background='var(--bg)'">
                        <i class="bi bi-cloud-arrow-up text-muted" style="font-size:1.8rem;opacity:.5;"></i>
                        <div class="fw-semibold mt-2" style="font-size:.875rem;">Click to add more photos</div>
                        <div class="text-muted small mt-1">JPG, PNG · Max 5 MB each</div>
                        <input type="file" name="images[]" id="images" accept="image/*" multiple class="d-none">
                    </label>
                    <div class="row g-2 mt-2" id="new-image-preview"></div>
                </div>

                <div class="d-flex gap-3 pt-2 border-top">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-light px-4" style="border-radius:.65rem;">Cancel</a>
                    <button type="submit" class="btn btn-primary flex-1 px-4" style="border-radius:.65rem;flex:1;">
                        <i class="bi bi-check2 me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('images').addEventListener('change', function() {
    const preview = document.getElementById('new-image-preview');
    preview.innerHTML = '';
    [...this.files].slice(0, 5).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            const col = document.createElement('div');
            col.className = 'col-4 col-md-3';
            col.innerHTML = `<img src="${e.target.result}" class="w-100 rounded-2" style="height:90px;object-fit:cover;">`;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
