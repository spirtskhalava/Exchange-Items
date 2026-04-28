@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0" style="font-size:1.5rem;">Browse Items</h1>
            <p class="text-muted small mb-0 mt-1">Find something you want to trade for</p>
        </div>
        @auth
        <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:.65rem;">
            <i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline">List an Item</span>
        </a>
        @endauth
    </div>

    <div class="row g-4">
        {{-- Sidebar Filters --}}
        <div class="col-lg-3">
            <div class="card sticky-top" style="top:76px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold" style="font-size:.9rem;">Filters</span>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-light text-muted" style="border-radius:.5rem;font-size:.78rem;padding:.25rem .65rem;">Reset</a>
                    </div>
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;">Search</label>
                            <div class="position-relative">
                                <i class="bi bi-search position-absolute" style="top:50%;transform:translateY(-50%);left:.75rem;color:var(--muted);font-size:.85rem;"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       class="form-control ps-4" placeholder="Item name..."
                                       style="padding-left:2.2rem!important;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;">Condition</label>
                            <div class="d-flex flex-column gap-2 mt-1">
                                @foreach([''=>'All', 'New'=>'New', 'Used'=>'Used', 'Refurbished'=>'Refurbished'] as $val => $label)
                                <label class="d-flex align-items-center gap-2 cursor-pointer" style="cursor:pointer;">
                                    <input type="radio" name="condition" value="{{ $val }}" class="form-check-input m-0"
                                           {{ request('condition', '') === $val ? 'checked' : '' }}>
                                    <span style="font-size:.875rem;color:#374151;">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" style="border-radius:.65rem;">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="col-lg-9">
            {{-- Results bar --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">
                    @if($products->total() > 0)
                        Showing <strong>{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong> of <strong>{{ $products->total() }}</strong> items
                    @else
                        No items found
                    @endif
                </span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius:.55rem;font-size:.8rem;">
                        <i class="bi bi-sort-down me-1"></i>Sort
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort'=>'newest']) }}">Newest First</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort'=>'views']) }}">Most Viewed</a></li>
                    </ul>
                </div>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    @include('_partials.product-card', ['product' => $product])
                @empty
                    <div class="col-12">
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <i class="bi bi-search text-muted" style="font-size:3rem;opacity:.3;"></i>
                                <h5 class="mt-3 fw-normal text-muted">No items match your filters</h5>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm mt-3" style="border-radius:.55rem;">Clear Filters</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Override product grid columns for this page (sidebar layout) */
    @media (min-width: 992px) {
        .col-lg-9 .col-xl-3 { width: 33.333%; }
        .col-lg-9 .col-lg-4 { width: 33.333%; }
        .col-lg-9 .col-md-6 { width: 33.333%; }
    }
    @media (max-width: 991px) {
        .col-lg-9 .col-xl-3, .col-lg-9 .col-lg-4, .col-lg-9 .col-md-6 { width: 50%; }
    }
    @media (max-width: 575px) {
        .col-lg-9 .col-xl-3, .col-lg-9 .col-lg-4, .col-lg-9 .col-md-6 { width: 100%; }
    }
</style>
@endpush
