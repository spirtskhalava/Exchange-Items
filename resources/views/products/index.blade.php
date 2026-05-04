@extends('layouts.app')

@section('meta_title', $activeCat ? ((config('categories.'.$activeCat.'.label') ?? ucfirst($activeCat)) . ' — Bartaro') : 'Browse Items to Trade — Bartaro')
@section('meta_description', 'Explore items available for trade on Bartaro. Search by category, condition, or keyword and make an offer today — no cash needed.')
@section('meta_canonical', route('products.index'))

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "Browse Items — Bartaro",
  "description": "Browse items available for trade on Bartaro.",
  "url": "{{ route('products.index') }}"
}
</script>
@endpush

@section('content')

{{-- ══ HERO BAR ══════════════════════════════════════════════ --}}
<div class="browse-hero">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                @if($activeCat && isset($allCategories[$activeCat]))
                    @php $catData = $allCategories[$activeCat]; @endphp
                    <nav aria-label="breadcrumb" class="mb-1">
                        <ol class="breadcrumb mb-0" style="font-size:.78rem;">
                            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">All Categories</a></li>
                            <li class="breadcrumb-item {{ $activeSub ? '' : 'active' }}">
                                @if($activeSub)
                                    <a href="{{ route('products.index', ['category'=>$activeCat]) }}" class="text-decoration-none text-muted">{{ $catData['label'] }}</a>
                                @else
                                    {{ $catData['label'] }}
                                @endif
                            </li>
                            @if($activeSub && isset($catData['subs'][$activeSub]))
                                <li class="breadcrumb-item active">{{ $catData['subs'][$activeSub] }}</li>
                            @endif
                        </ol>
                    </nav>
                    <h1 class="browse-title">
                        <i class="bi {{ $catData['icon'] }} me-2" style="color:{{ $catData['color'] }};font-size:1.2rem;"></i>
                        {{ $activeSub && isset($catData['subs'][$activeSub]) ? $catData['subs'][$activeSub] : $catData['label'] }}
                    </h1>
                @else
                    <h1 class="browse-title">Browse Items</h1>
                    <p class="browse-sub">Find something you love — then trade for it.</p>
                @endif
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

        {{-- ══ LEFT SIDEBAR ══════════════════════════════════ --}}
        <div class="col-lg-3">
            <div class="filter-card sticky-top" style="top:76px;">

                {{-- Search --}}
                <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                    @if($activeCat)<input type="hidden" name="category" value="{{ $activeCat }}">@endif
                    @if($activeSub)<input type="hidden" name="sub" value="{{ $activeSub }}">@endif

                    <div class="filter-group">
                        <div class="filter-search-wrap">
                            <i class="bi bi-search filter-search-icon"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="filter-input ps-search" placeholder="Search items...">
                        </div>
                    </div>

                    {{-- Condition --}}
                    <div class="filter-group">
                        <label class="filter-label">Condition</label>
                        <div class="d-flex flex-column gap-1 mt-1">
                            @foreach(['' => 'Any condition', 'New' => 'New', 'Like New' => 'Like New', 'Good' => 'Good', 'Fair' => 'Fair', 'Poor' => 'Poor'] as $val => $label)
                            <label class="cond-radio">
                                <input type="radio" name="condition" value="{{ $val }}"
                                       {{ request('condition', '') === $val ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <span>{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-sm mb-3" style="border-radius:.65rem;">
                        <i class="bi bi-funnel me-1"></i> Apply filters
                    </button>
                </form>

                {{-- Save search --}}
                @auth
                @if(request('search') || $activeCat || request('condition'))
                <form method="POST" action="{{ route('saved-searches.store') }}" class="mb-3">
                    @csrf
                    <input type="hidden" name="query"     value="{{ request('search') }}">
                    <input type="hidden" name="category"  value="{{ $activeCat }}">
                    <input type="hidden" name="condition" value="{{ request('condition') }}">
                    <button type="submit" class="btn btn-outline-primary w-100 btn-sm" style="border-radius:.65rem;">
                        <i class="bi bi-bell me-1"></i> Save &amp; Get Alerts
                    </button>
                </form>
                @endif
                @endauth

                <div class="sidebar-divider"></div>

                {{-- ── CATEGORIES TREE ── --}}
                <div class="cat-tree-header">
                    <i class="bi bi-grid-3x3-gap-fill me-1"></i> Categories
                    @if($activeCat)
                        <a href="{{ route('products.index') }}" class="cat-tree-reset">All</a>
                    @endif
                </div>

                <ul class="cat-tree">
                    @foreach($allCategories as $slug => $cat)
                    @php $isActive = ($activeCat === $slug); @endphp
                    <li class="cat-tree-item {{ $isActive ? 'active' : '' }}">
                        <a href="{{ route('products.index', ['category' => $slug]) }}"
                           class="cat-tree-link {{ $isActive ? 'active' : '' }}">
                            <i class="bi {{ $cat['icon'] }} cat-tree-icon" style="color:{{ $cat['color'] }};background:{{ $cat['bg'] }};"></i>
                            <span>{{ $cat['label'] }}</span>
                            <i class="bi bi-chevron-right cat-tree-arrow {{ $isActive ? 'd-none' : '' }}"></i>
                            <i class="bi bi-chevron-down cat-tree-arrow {{ $isActive ? '' : 'd-none' }}"></i>
                        </a>

                        {{-- Subcategories --}}
                        @if($isActive)
                        <ul class="sub-tree">
                            <li>
                                <a href="{{ route('products.index', ['category' => $slug]) }}"
                                   class="sub-tree-link {{ !$activeSub ? 'active' : '' }}">
                                    All {{ $cat['label'] }}
                                </a>
                            </li>
                            @foreach($cat['subs'] as $subSlug => $subLabel)
                            <li>
                                <a href="{{ route('products.index', ['category' => $slug, 'sub' => $subSlug]) }}"
                                   class="sub-tree-link {{ $activeSub === $subSlug ? 'active' : '' }}">
                                    {{ $subLabel }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>

            </div>
        </div>

        {{-- ══ MAIN CONTENT ══════════════════════════════════ --}}
        <div class="col-lg-9">

            {{-- ── Category grid (shown only when no category selected) ── --}}
            @if(!$activeCat && !request('search') && !request('condition'))
            <div class="cat-grid mb-4">
                @foreach($allCategories as $slug => $cat)
                <a href="{{ route('products.index', ['category' => $slug]) }}"
                   class="cat-tile text-decoration-none">
                    <div class="cat-tile-icon" style="background:{{ $cat['bg'] }};color:{{ $cat['color'] }};">
                        <i class="bi {{ $cat['icon'] }}"></i>
                    </div>
                    <span class="cat-tile-label">{{ $cat['label'] }}</span>
                </a>
                @endforeach
            </div>
            <div class="sidebar-divider mb-4"></div>
            @endif

            {{-- ── Subcategory pills (shown when in a main category, no sub selected) ── --}}
            @if($activeCat && !$activeSub && isset($allCategories[$activeCat]))
            <div class="sub-pills mb-4">
                @foreach($allCategories[$activeCat]['subs'] as $subSlug => $subLabel)
                <a href="{{ route('products.index', ['category' => $activeCat, 'sub' => $subSlug]) }}"
                   class="sub-pill">
                    {{ $subLabel }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- ── Results bar ── --}}
            <div class="results-bar">
                <span class="results-count">
                    @if($products->total() > 0)
                        <strong>{{ $products->total() }}</strong> items
                        @if(request('search')) for "<em>{{ request('search') }}</em>"@endif
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

            {{-- ── Active filter chips ── --}}
            @if(request('search') || $activeCat || request('condition'))
            <div class="d-flex flex-wrap gap-2 mb-3">
                @if(request('search'))
                <span class="filter-chip">
                    <i class="bi bi-search"></i> {{ request('search') }}
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="filter-chip-x"><i class="bi bi-x"></i></a>
                </span>
                @endif
                @if($activeSub && isset($allCategories[$activeCat]['subs'][$activeSub]))
                <span class="filter-chip">
                    <i class="bi bi-tag"></i> {{ $allCategories[$activeCat]['subs'][$activeSub] }}
                    <a href="{{ route('products.index', ['category' => $activeCat]) }}" class="filter-chip-x"><i class="bi bi-x"></i></a>
                </span>
                @elseif($activeCat && isset($allCategories[$activeCat]))
                <span class="filter-chip">
                    <i class="bi bi-tag"></i> {{ $allCategories[$activeCat]['label'] }}
                    <a href="{{ route('products.index') }}" class="filter-chip-x"><i class="bi bi-x"></i></a>
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

            {{-- ── Product grid ── --}}
            <div class="row g-3">
                @forelse($products as $product)
                    @include('_partials.product-card', ['product' => $product])
                @empty
                <div class="col-12">
                    <div class="empty-state-box">
                        <i class="bi bi-search"></i>
                        <h5>No items match your search</h5>
                        <p>Try different keywords or browse another category.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm mt-1">Clear all filters</a>
                    </div>
                </div>
                @endforelse
            </div>

            {{-- ── Pagination ── --}}
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
/* ── Browse hero ─────────────────────────────────────────── */
.browse-hero {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 1.5rem 0;
}
.browse-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -.025em;
    margin: 0;
    display: flex;
    align-items: center;
}
.browse-sub { font-size: .84rem; color: var(--muted); margin: .2rem 0 0; }

/* ── Filter sidebar ─────────────────────────────────────── */
.filter-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.1rem;
    box-shadow: var(--shadow-sm);
}
.filter-group { margin-bottom: 1rem; }
.filter-label { font-size: .72rem; font-weight: 700; color: var(--text2); letter-spacing: .04em; text-transform: uppercase; display: block; margin-bottom: .45rem; }
.filter-search-wrap { position: relative; }
.filter-search-icon { position: absolute; top:50%; left:.7rem; transform:translateY(-50%); color:var(--muted2); font-size:.85rem; pointer-events:none; }
.ps-search { padding-left: 2.1rem !important; }
.filter-input {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: .6rem;
    padding: .45rem .75rem;
    font-size: .84rem;
    color: var(--text);
    background: var(--bg);
    transition: border-color var(--transition), box-shadow var(--transition);
    outline: none;
}
.filter-input:focus { border-color:var(--p); box-shadow:0 0 0 3px var(--p-ring); background:var(--surface); }
.cond-radio { display:flex; align-items:center; gap:.45rem; font-size:.82rem; color:var(--text2); cursor:pointer; padding:.15rem 0; }
.cond-radio input { accent-color:var(--p); width:14px; height:14px; cursor:pointer; }
.cond-radio span { font-weight:500; }
.sidebar-divider { height:1px; background:var(--border); margin:.85rem 0; }

/* ── Category tree (sidebar) ─────────────────────────────── */
.cat-tree-header {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--muted);
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: .6rem;
}
.cat-tree-reset {
    font-size: .72rem;
    font-weight: 600;
    color: var(--p);
    text-decoration: none;
}
.cat-tree {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 1px;
}
.cat-tree-link {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .42rem .5rem;
    border-radius: .5rem;
    text-decoration: none;
    color: var(--text2);
    font-size: .83rem;
    font-weight: 500;
    transition: background var(--transition), color var(--transition);
}
.cat-tree-link:hover { background: var(--bg); color: var(--text); }
.cat-tree-link.active { background: var(--p-light); color: var(--p); font-weight: 600; }
.cat-tree-icon {
    width: 24px; height: 24px;
    border-radius: .35rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem;
    flex-shrink: 0;
}
.cat-tree-arrow { margin-left: auto; font-size: .7rem; color: var(--muted2); }
.cat-tree-link.active .cat-tree-arrow { color: var(--p); }

/* Subcategory list */
.sub-tree {
    list-style: none;
    padding: 0 0 0 1.5rem;
    margin: 2px 0 4px;
}
.sub-tree-link {
    display: block;
    padding: .3rem .5rem;
    border-radius: .4rem;
    font-size: .8rem;
    font-weight: 500;
    color: var(--muted);
    text-decoration: none;
    transition: all var(--transition);
}
.sub-tree-link:hover { color: var(--p); background: var(--p-light); }
.sub-tree-link.active { color: var(--p); font-weight: 700; background: var(--p-light); }

/* ── Category tile grid (main area, no filter) ───────────── */
.cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: .65rem;
}
.cat-tile {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .4rem;
    padding: .75rem .4rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    transition: all var(--transition);
    text-align: center;
}
.cat-tile:hover { border-color: var(--p); box-shadow: 0 4px 12px rgba(79,70,229,.1); transform: translateY(-2px); }
.cat-tile-icon {
    width: 40px; height: 40px;
    border-radius: .6rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
}
.cat-tile-label {
    font-size: .72rem;
    font-weight: 600;
    color: var(--text2);
    line-height: 1.2;
}

/* ── Subcategory pills (shown in main category) ─────────── */
.sub-pills {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
}
.sub-pill {
    display: inline-block;
    padding: .35rem .85rem;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: 99px;
    font-size: .8rem;
    font-weight: 600;
    color: var(--text2);
    text-decoration: none;
    transition: all var(--transition);
}
.sub-pill:hover { border-color: var(--p); color: var(--p); background: var(--p-light); }

/* ── Results bar ─────────────────────────────────────────── */
.results-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.results-count { font-size:.84rem; color:var(--muted); }
.results-count strong { color:var(--text); }

/* ── Filter chips ────────────────────────────────────────── */
.filter-chip { display:inline-flex; align-items:center; gap:.35rem; background:var(--p-light); color:var(--p); border:1px solid #c7d2fe; border-radius:99px; padding:.25rem .7rem; font-size:.75rem; font-weight:600; }
.filter-chip-x { color:var(--p); text-decoration:none; display:flex; align-items:center; opacity:.6; transition:opacity .15s; }
.filter-chip-x:hover { opacity:1; }

/* ── Pagination ──────────────────────────────────────────── */
.pagination-wrap { display:flex; flex-direction:column; align-items:center; gap:.75rem; margin-top:2.5rem; padding-top:2rem; border-top:1px solid var(--border); }
.pagination-info { font-size:.8rem; color:var(--muted); }

/* ── Empty state ─────────────────────────────────────────── */
.empty-state-box { text-align:center; padding:4rem 2rem; background:var(--surface); border:1.5px dashed var(--border2); border-radius:var(--radius-lg); color:var(--muted); }
.empty-state-box i { font-size:2.5rem; opacity:.25; display:block; margin-bottom:1rem; color:var(--p); }
.empty-state-box h5 { font-weight:700; color:var(--text2); margin-bottom:.4rem; }
.empty-state-box p { font-size:.875rem; margin:0; }

/* ── Dropdown active ─────────────────────────────────────── */
.dropdown-item.active { background:var(--p-light) !important; color:var(--p) !important; }
</style>
@endpush
