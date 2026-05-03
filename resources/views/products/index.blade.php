@extends('layouts.app')

@section('content')

{{-- Page hero bar --}}
<div class="browse-hero">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="browse-title">Browse Items</h1>
                <p class="browse-sub">Find something you love — then trade for it.</p>
            </div>
            @auth
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> List an item
            </a>
            @endauth
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4 align-items-start">

        {{-- ── SIDEBAR FILTERS ─────────────────────────────── --}}
        <div class="col-lg-3">
            <div class="filter-card sticky-top" style="top:76px;">
                <div class="filter-header">
                    <span class="fw-700" style="font-size:.875rem;">Filters</span>
                    <a href="{{ route('products.index') }}" class="filter-reset">Reset all</a>
                </div>

                <form action="{{ route('products.index') }}" method="GET" id="filterForm">

                    {{-- Search --}}
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <div class="filter-search-wrap">
                            <i class="bi bi-search filter-search-icon"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="filter-input ps-search"
                                   placeholder="What are you looking for?">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <select name="category" class="filter-input" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Condition --}}
                    <div class="filter-group">
                        <label class="filter-label">Condition</label>
                        <div class="d-flex flex-column gap-2 mt-1">
                            @foreach(['' => 'Any condition', 'New' => 'New', 'Used' => 'Used', 'Refurbished' => 'Refurbished'] as $val => $label)
                            <label class="cond-radio">
                                <input type="radio" name="condition" value="{{ $val }}"
                                       {{ request('condition', '') === $val ? 'checked' : '' }}
                                       onchange="document.getElementById('filterForm').submit()">
                                <span class="cond-dot"></span>
                                <span>{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-1" style="border-radius:.65rem;">
                        <i class="bi bi-funnel me-1"></i> Apply
                    </button>
                </form>

                @auth
                @if(request('search') || request('category') || request('condition'))
                <form method="POST" action="{{ route('saved-searches.store') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="query"     value="{{ request('search') }}">
                    <input type="hidden" name="category"  value="{{ request('category') }}">
                    <input type="hidden" name="condition" value="{{ request('condition') }}">
                    <button type="submit" class="btn btn-outline-primary w-100" style="border-radius:.65rem;font-size:.83rem;">
                        <i class="bi bi-bell me-1"></i> Save &amp; Get Alerts
                    </button>
                </form>
                @endif
                @endauth
            </div>
        </div>

        {{-- ── PRODUCT GRID ─────────────────────────────────── --}}
        <div class="col-lg-9">

            {{-- Results bar --}}
            <div class="results-bar">
                <span class="results-count">
                    @if($products->total() > 0)
                        <strong>{{ $products->total() }}</strong> items found
                        @if(request('search'))
                            for "<em>{{ request('search') }}</em>"
                        @endif
                    @else
                        No items found
                    @endif
                </span>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-arrow-down-up me-1"></i>
                        {{ request('sort') === 'views' ? 'Most viewed' : 'Newest first' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item {{ request('sort') !== 'views' ? 'active' : '' }}"
                               href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest first</a></li>
                        <li><a class="dropdown-item {{ request('sort') === 'views' ? 'active' : '' }}"
                               href="{{ request()->fullUrlWithQuery(['sort' => 'views']) }}">Most viewed</a></li>
                    </ul>
                </div>
            </div>

            {{-- Active filters chips --}}
            @if(request('search') || request('category') || request('condition'))
            <div class="d-flex flex-wrap gap-2 mb-3">
                @if(request('search'))
                    <span class="filter-chip">
                        <i class="bi bi-search"></i> {{ request('search') }}
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="filter-chip-x"><i class="bi bi-x"></i></a>
                    </span>
                @endif
                @if(request('category'))
                    <span class="filter-chip">
                        <i class="bi bi-tag"></i> {{ ucfirst(str_replace('-',' ',request('category'))) }}
                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="filter-chip-x"><i class="bi bi-x"></i></a>
                    </span>
                @endif
                @if(request('condition'))
                    <span class="filter-chip">
                        <i class="bi bi-check-circle"></i> {{ request('condition') }}
                        <a href="{{ request()->fullUrlWithQuery(['condition' => null]) }}" class="filter-chip-x"><i class="bi bi-x"></i></a>
                    </span>
                @endif
            </div>
            @endif

            {{-- Grid --}}
            <div class="row g-4">
                @forelse($products as $product)
                    @include('_partials.product-card', ['product' => $product])
                @empty
                    <div class="col-12">
                        <div class="empty-state-box">
                            <i class="bi bi-search"></i>
                            <h5>No items match your search</h5>
                            <p>Try different keywords or clear your filters.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm mt-1">Clear all filters</a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-info">
                    Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
                </div>
                {{ $products->withQueryString()->links() }}
            </div>
            @endif

        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ── Browse hero bar ── */
.browse-hero {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 1.75rem 0;
}
.browse-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -.025em;
    margin: 0;
}
.browse-sub { font-size: .84rem; color: var(--muted); margin: .2rem 0 0; }

/* ── Filter sidebar ── */
.filter-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    box-shadow: var(--shadow-sm);
}
.filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
    padding-bottom: .85rem;
    border-bottom: 1px solid var(--border);
}
.filter-reset {
    font-size: .75rem;
    font-weight: 600;
    color: var(--muted);
    text-decoration: none;
    transition: color var(--transition);
}
.filter-reset:hover { color: var(--p); }

.filter-group { margin-bottom: 1.1rem; }
.filter-label { font-size: .75rem; font-weight: 700; color: var(--text2); letter-spacing: .04em; text-transform: uppercase; display: block; margin-bottom: .5rem; }

.filter-search-wrap { position: relative; }
.filter-search-icon { position: absolute; top: 50%; left: .7rem; transform: translateY(-50%); color: var(--muted2); font-size: .85rem; pointer-events: none; }
.ps-search { padding-left: 2.1rem !important; }

.filter-input {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: .6rem;
    padding: .5rem .75rem;
    font-size: .84rem;
    color: var(--text);
    background: var(--bg);
    transition: border-color var(--transition), box-shadow var(--transition);
    outline: none;
    appearance: auto;
}
.filter-input:focus { border-color: var(--p); box-shadow: 0 0 0 3px var(--p-ring); background: var(--surface); }

/* Radio buttons */
.cond-radio {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .84rem;
    color: var(--text2);
    cursor: pointer;
    padding: .2rem 0;
}
.cond-radio input { accent-color: var(--p); width: 15px; height: 15px; cursor: pointer; }
.cond-radio span:last-child { font-weight: 500; }

/* ── Results bar ── */
.results-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}
.results-count { font-size: .84rem; color: var(--muted); }
.results-count strong { color: var(--text); }

/* ── Filter chips ── */
.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: var(--p-light);
    color: var(--p);
    border: 1px solid #c7d2fe;
    border-radius: 99px;
    padding: .25rem .7rem;
    font-size: .75rem;
    font-weight: 600;
}
.filter-chip-x {
    color: var(--p);
    text-decoration: none;
    display: flex;
    align-items: center;
    opacity: .6;
    transition: opacity .15s;
}
.filter-chip-x:hover { opacity: 1; }

/* ── Pagination wrap ── */
.pagination-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .75rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border);
}
.pagination-info { font-size: .8rem; color: var(--muted); }

/* ── Empty state ── */
.empty-state-box {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--surface);
    border: 1.5px dashed var(--border2);
    border-radius: var(--radius-lg);
    color: var(--muted);
}
.empty-state-box i { font-size: 2.5rem; opacity: .25; display: block; margin-bottom: 1rem; color: var(--p); }
.empty-state-box h5 { font-weight: 700; color: var(--text2); margin-bottom: .4rem; }
.empty-state-box p { font-size: .875rem; margin: 0; }

/* ── Force 3-col grid inside sidebar layout ── */
@media (min-width: 992px) {
    .col-lg-9 .col-xl-3,
    .col-lg-9 .col-lg-4,
    .col-lg-9 .col-sm-6 { width: 33.333%; }
}
@media (max-width: 991px) and (min-width: 576px) {
    .col-lg-9 .col-sm-6 { width: 50%; }
}
@media (max-width: 575px) {
    .col-lg-9 .col-xl-3,
    .col-lg-9 .col-lg-4,
    .col-lg-9 .col-sm-6 { width: 100%; }
}

/* Dropdown active item */
.dropdown-item.active { background: var(--p-light) !important; color: var(--p) !important; }
</style>
@endpush
