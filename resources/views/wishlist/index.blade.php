@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">My Wishlist</h1>
        <p class="text-muted small mt-1 mb-0">Items you've saved to trade for later</p>
    </div>

    @if($wishlistItems->total() === 0)
        <div class="card text-center py-5">
            <div class="card-body">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:rgba(239,71,111,.07);border-radius:1rem;">
                    <i class="bi bi-heart text-danger" style="font-size:1.8rem;opacity:.5;"></i>
                </div>
                <h5 class="fw-bold mb-1">Your wishlist is empty</h5>
                <p class="text-muted small mb-4">Browse products and tap the heart icon to save items here.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="border-radius:.65rem;">
                    <i class="bi bi-grid me-1"></i> Browse Products
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($wishlistItems as $item)
            @php
                $product = $item->product;
                $imageUrl = 'https://placehold.co/400x280/f5f6fa/adb5bd?text=No+Image';
                $paths = !empty($product->image_paths) ? json_decode($product->image_paths, true) : null;
                if (is_array($paths) && !empty($paths[0]) && !str_starts_with($paths[0], 'http')) {
                    $imageUrl = asset('storage/' . $paths[0]);
                }
            @endphp
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 wish-card">
                    <div class="position-relative overflow-hidden" style="border-radius:.9rem .9rem 0 0;height:190px;">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                             style="width:100%;height:100%;object-fit:cover;transition:transform .3s;"
                             class="wish-img">
                        @php
                            $cmap = ['new'=>['label'=>'New','class'=>'bg-success'],'used'=>['label'=>'Used','class'=>'bg-warning text-dark']];
                            $cond = $cmap[strtolower($product->condition ?? '')] ?? ['label'=>ucfirst($product->condition ?? ''), 'class'=>'bg-secondary'];
                        @endphp
                        <span class="badge {{ $cond['class'] }} position-absolute" style="top:.6rem;left:.6rem;font-size:.72rem;">{{ $cond['label'] }}</span>
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge bg-light text-muted border mb-2" style="font-size:.7rem;align-self:flex-start;">{{ ucfirst(str_replace('-',' ',$product->category ?? '')) }}</span>
                        <h6 class="fw-bold text-truncate mb-1" title="{{ $product->name }}">
                            <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
                        </h6>
                        <p class="text-muted small mb-3" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;line-height:1.5;">{{ $product->description }}</p>
                        <div class="mt-auto row g-2">
                            <div class="col-6">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary w-100 btn-sm py-2" style="border-radius:.55rem;font-size:.82rem;">View</a>
                            </div>
                            <div class="col-6">
                                <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light w-100 btn-sm py-2 text-danger" style="border-radius:.55rem;font-size:.82rem;">
                                        <i class="bi bi-heartbreak me-1"></i>Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($wishlistItems->hasPages())
        <div class="pagination-wrap mt-4 pt-3 border-top">
            <div class="pagination-info">
                Showing {{ $wishlistItems->firstItem() }}–{{ $wishlistItems->lastItem() }} of {{ $wishlistItems->total() }}
            </div>
            {{ $wishlistItems->withQueryString()->links() }}
        </div>
        @endif
    @endif
</div>
@endsection

@push('styles')
<style>
    .wish-card { transition: all .2s; }
    .wish-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(67,97,238,.1) !important; }
    .wish-card:hover .wish-img { transform: scale(1.04); }
</style>
@endpush
