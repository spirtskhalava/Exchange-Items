@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0" style="font-size:1.5rem;">My Listings</h1>
            <p class="text-muted small mt-1 mb-0">Manage your inventory · <strong>{{ $totalProducts }}</strong> items listed</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.65rem;">
            <i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline">Add Listing</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 py-2 px-3 small" role="alert">
            <i class="bi bi-check-circle-fill text-success"></i> {{ session('success') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:var(--bg);border-radius:1rem;">
                    <i class="bi bi-box-seam text-muted" style="font-size:1.8rem;opacity:.5;"></i>
                </div>
                <h5 class="fw-bold mb-1">No Listings Yet</h5>
                <p class="text-muted small mb-4">Start selling by adding your first item.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary" style="border-radius:.65rem;">
                    <i class="bi bi-plus-lg me-1"></i> Create First Listing
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
            @php
                $imageUrl = 'https://placehold.co/400x280/f5f6fa/adb5bd?text=No+Image';
                $paths = !empty($product->image_paths) ? json_decode($product->image_paths, true) : null;
                if (is_array($paths) && !empty($paths[0]) && !str_starts_with($paths[0], 'http')) {
                    $imageUrl = asset('storage/' . $paths[0]);
                }
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 listing-card">
                    {{-- Image --}}
                    <div class="position-relative overflow-hidden" style="border-radius:.9rem .9rem 0 0;height:180px;">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                             style="width:100%;height:100%;object-fit:cover;transition:transform .3s;"
                             class="listing-img">
                        <span class="badge bg-white text-dark position-absolute shadow-sm"
                              style="top:.6rem;right:.6rem;font-size:.7rem;padding:.3rem .6rem;">
                            {{ ucfirst($product->condition) }}
                        </span>
                    </div>

                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="fw-bold text-truncate mb-1" title="{{ $product->name }}">{{ $product->name }}</h6>
                        <p class="text-muted small mb-3" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;line-height:1.5;">{{ $product->description }}</p>

                        {{-- Stats --}}
                        <div class="d-flex gap-3 mb-3 mt-auto p-2 rounded-2" style="background:var(--bg);">
                            <div class="d-flex align-items-center gap-1 text-muted" style="font-size:.8rem;">
                                <i class="bi bi-eye text-primary" style="font-size:.85rem;"></i>
                                <span><strong>{{ $product->views }}</strong> views</span>
                            </div>
                            <div class="d-flex align-items-center gap-1 text-muted" style="font-size:.8rem;">
                                <i class="bi bi-clock" style="font-size:.85rem;"></i>
                                <span>{{ $product->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="row g-2">
                            <div class="col-12">
                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-outline-primary w-100 btn-sm py-2" style="border-radius:.55rem;font-size:.83rem;">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> View
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('listings.edit', $product) }}"
                                   class="btn btn-light w-100 btn-sm py-2 text-secondary" style="border-radius:.55rem;font-size:.83rem;">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                            </div>
                            <div class="col-6">
                                <form action="{{ route('listings.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Delete this listing? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light w-100 btn-sm py-2 text-danger" style="border-radius:.55rem;font-size:.83rem;">
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

        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $products->onEachSide(1)->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

@push('styles')
<style>
    .listing-card { transition: all .2s; }
    .listing-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(67,97,238,.1) !important; }
    .listing-card:hover .listing-img { transform: scale(1.04); }
</style>
@endpush
