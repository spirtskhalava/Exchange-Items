@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:720px;">

    {{-- Header --}}
    <div class="mb-4">
        <a href="{{ route('listings.index') }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-1 mb-2">
            <i class="bi bi-arrow-left"></i> My Listings
        </a>
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">Create New Listing</h1>
        <p class="text-muted small mt-1 mb-0">Fill in the details below to list your item for trade</p>
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
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="e.g. Sony PlayStation 5" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe your item — condition details, what's included, why you're trading it..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <div class="position-relative">
                        <i class="bi bi-geo-alt position-absolute" style="top:50%;transform:translateY(-50%);left:.75rem;color:var(--muted);"></i>
                        <input type="text" name="location" value="{{ old('location') }}"
                               class="form-control ps-4 @error('location') is-invalid @enderror"
                               style="padding-left:2.2rem!important;"
                               placeholder="City, Country" required>
                    </div>
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                @php $allCats = config('categories'); @endphp
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Category</label>
                        <select name="category" id="categorySelect" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="" disabled selected>Select category</option>
                            @foreach($allCats as $slug => $cat)
                                <option value="{{ $slug }}" {{ old('category') == $slug ? 'selected' : '' }}>{{ $cat['label'] }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Subcategory <span class="text-muted" style="font-weight:400;">(optional)</span></label>
                        <select name="sub_category" id="subCategorySelect" class="form-select">
                            <option value="">— Select subcategory —</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                            <option value="" disabled selected>Select condition</option>
                            <option value="New"       {{ old('condition') == 'New'       ? 'selected' : '' }}>New</option>
                            <option value="Like New"  {{ old('condition') == 'Like New'  ? 'selected' : '' }}>Like New</option>
                            <option value="Good"      {{ old('condition') == 'Good'      ? 'selected' : '' }}>Good</option>
                            <option value="Fair"      {{ old('condition') == 'Fair'      ? 'selected' : '' }}>Fair</option>
                            <option value="Poor"      {{ old('condition') == 'Poor'      ? 'selected' : '' }}>Poor</option>
                        </select>
                        @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Looking For + Preferred Offer Category --}}
                <div class="mb-3 p-3 rounded-2" style="background:var(--p-light);border:1px solid #c7d2fe;">
                    <label class="form-label fw-semibold" style="color:var(--p);">
                        <i class="bi bi-stars me-1"></i> What do you want in return?
                        <span class="fw-normal text-muted">(enables smart matching)</span>
                    </label>
                    <select name="looking_for" class="form-select mb-2">
                        <option value="">Any category</option>
                        @foreach($allCats as $slug => $cat)
                            <option value="{{ $slug }}" {{ old('looking_for') == $slug ? 'selected' : '' }}>{{ $cat['label'] }}</option>
                        @endforeach
                    </select>
                    <div class="text-muted mb-3" style="font-size:.76rem;">We'll instantly notify you when someone lists exactly what you want and wants what you have.</div>

                    <label class="form-label fw-semibold" style="color:var(--p);">
                        <i class="bi bi-shield-check me-1"></i> Only accept offers from
                        <span class="fw-normal text-muted">(optional — others auto-rejected)</span>
                    </label>
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <select name="preferred_offer_category" id="prefCatSelect" class="form-select">
                                <option value="">Any category</option>
                                @foreach($allCats as $slug => $cat)
                                    <option value="{{ $slug }}" {{ old('preferred_offer_category') == $slug ? 'selected' : '' }}>
                                        {{ $cat['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="preferred_offer_sub_category" id="prefSubSelect" class="form-select">
                                <option value="">Any subcategory</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-muted mt-1" style="font-size:.76rem;">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Offers with items outside your chosen category/subcategory will be automatically blocked.
                    </div>
                </div>

                {{-- Image Upload --}}
                <div class="mb-4">
                    <label class="form-label">Photos <span class="text-muted fw-normal">(up to 5)</span></label>
                    <label for="images" class="d-block border rounded-2 text-center p-4 cursor-pointer"
                           style="border-style:dashed!important;background:var(--bg);cursor:pointer;transition:background .15s;"
                           onmouseover="this.style.background='#edf0fb'" onmouseout="this.style.background='var(--bg)'">
                        <i class="bi bi-cloud-arrow-up text-muted" style="font-size:2rem;opacity:.5;"></i>
                        <div class="fw-semibold mt-2" style="font-size:.9rem;">Drop images here or click to browse</div>
                        <div class="text-muted small mt-1">JPG, PNG · Max 5 MB each</div>
                        <input type="file" name="images[]" id="images" accept="image/*" multiple class="d-none">
                    </label>
                    @error('images.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    <div class="row g-2 mt-2" id="image-preview"></div>
                </div>

                <div class="d-flex gap-3 pt-2 border-top">
                    <a href="{{ route('listings.index') }}" class="btn btn-light px-4" style="border-radius:.65rem;">Cancel</a>
                    <button type="submit" class="btn btn-primary flex-1 px-4" style="border-radius:.65rem;flex:1;">
                        <i class="bi bi-check2 me-1"></i> Publish Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Shared category data ──────────────────────────────────
const categories = @json(collect(config('categories'))->map(fn($c) => $c['subs']));

// ── Item subcategory dropdown ─────────────────────────────
const oldSub = "{{ old('sub_category') }}";

function updateSubs(catSlug) {
    const sel  = document.getElementById('subCategorySelect');
    const subs = categories[catSlug] || {};
    sel.innerHTML = '<option value="">— Select subcategory —</option>';
    Object.entries(subs).forEach(([slug, label]) => {
        const opt = document.createElement('option');
        opt.value = slug; opt.textContent = label;
        if (slug === oldSub) opt.selected = true;
        sel.appendChild(opt);
    });
}
const catSel = document.getElementById('categorySelect');
if (catSel.value) updateSubs(catSel.value);
catSel.addEventListener('change', () => updateSubs(catSel.value));

// ── Preferred offer subcategory dropdown ──────────────────
const oldPrefSub = "{{ old('preferred_offer_sub_category') }}";

function updatePrefSubs(catSlug) {
    const sel  = document.getElementById('prefSubSelect');
    const subs = categories[catSlug] || {};
    sel.innerHTML = '<option value="">Any subcategory</option>';
    Object.entries(subs).forEach(([slug, label]) => {
        const opt = document.createElement('option');
        opt.value = slug; opt.textContent = label;
        if (slug === oldPrefSub) opt.selected = true;
        sel.appendChild(opt);
    });
}
const prefCatSel = document.getElementById('prefCatSelect');
if (prefCatSel.value) updatePrefSubs(prefCatSel.value);
prefCatSel.addEventListener('change', () => updatePrefSubs(prefCatSel.value));

// ── Image preview ─────────────────────────────────────────
document.getElementById('images').addEventListener('change', function() {
    const preview = document.getElementById('image-preview');
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
