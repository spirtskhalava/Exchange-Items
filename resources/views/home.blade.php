@extends('layouts.app')

@section('meta_title', 'Bartaro — Trade What You Have for What You Want')
@section('meta_description', 'Bartaro is a free peer-to-peer item exchange platform. List your unused items, browse what others offer, and trade safely with PayPal protection and optional trade insurance.')
@section('meta_canonical', route('home'))

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Bartaro",
  "url": "{{ config('app.url') }}",
  "description": "Free peer-to-peer item exchange platform — trade what you have for what you want.",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ route('products.index') }}?query={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
@endpush

@section('content')

{{-- ══ HERO ════════════════════════════════════════════════════ --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-hero">
            <div class="col-lg-6 py-5">
                <div class="hero-eyebrow">
                    <i class="bi bi-shield-check-fill"></i> Trusted Item Exchange
                </div>
                <h1 class="hero-title">
                    Trade what you have<br>
                    <span class="hero-title-accent">for what you want.</span>
                </h1>
                <p class="hero-sub">
                    Bartaro makes it simple to exchange items with people in your community — safely, fairly, and for free.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        Browse items <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-lg hero-btn-ghost">
                        Join for free
                    </a>
                    @endguest
                    @auth
                    <a href="{{ route('products.create') }}" class="btn btn-lg hero-btn-ghost">
                        <i class="bi bi-plus-lg me-1"></i> List an item
                    </a>
                    @endauth
                </div>

                {{-- Stats --}}
                <div class="hero-stats">
                    <div class="hero-stat">
                        <strong>2,400+</strong>
                        <span>Items listed</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <strong>1,800+</strong>
                        <span>Trades done</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <strong>900+</strong>
                        <span>Members</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-feature-grid">
                    @foreach([
                        ['icon'=>'bi-shield-check-fill', 'color'=>'#4f46e5', 'bg'=>'#eef2ff', 'title'=>'Escrow Protected', 'sub'=>'Your items stay safe'],
                        ['icon'=>'bi-arrow-left-right',  'color'=>'#059669', 'bg'=>'#ecfdf5', 'title'=>'Item-for-Item',    'sub'=>'Fair exchange every time'],
                        ['icon'=>'bi-chat-dots-fill',    'color'=>'#d97706', 'bg'=>'#fffbeb', 'title'=>'Direct Chat',      'sub'=>'Talk to sellers directly'],
                        ['icon'=>'bi-star-fill',         'color'=>'#dc2626', 'bg'=>'#fef2f2', 'title'=>'Verified Reviews', 'sub'=>'Trusted community ratings'],
                    ] as $f)
                    <div class="hero-feature-card">
                        <div class="hero-feature-icon" style="background:{{ $f['bg'] }};color:{{ $f['color'] }};">
                            <i class="bi {{ $f['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="hero-feature-title">{{ $f['title'] }}</div>
                            <div class="hero-feature-sub">{{ $f['sub'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ CATEGORIES ══════════════════════════════════════════════ --}}
<section class="py-5" style="background:var(--surface);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-label mb-1">Shop by</div>
                <h2 class="section-title">Categories</h2>
            </div>
        </div>

        <div class="row g-3 mt-1">
            @foreach([
                ['slug'=>'electronics', 'icon'=>'bi-cpu-fill',        'label'=>'Electronics',   'color'=>'#4f46e5','bg'=>'#eef2ff'],
                ['slug'=>'fashion',     'icon'=>'bi-bag-heart-fill',   'label'=>'Fashion',       'color'=>'#db2777','bg'=>'#fdf2f8'],
                ['slug'=>'home-garden', 'icon'=>'bi-house-heart-fill', 'label'=>'Home & Garden', 'color'=>'#059669','bg'=>'#ecfdf5'],
                ['slug'=>'sports',      'icon'=>'bi-bicycle',          'label'=>'Sports',        'color'=>'#d97706','bg'=>'#fffbeb'],
                ['slug'=>'gaming',      'icon'=>'bi-controller-fill',  'label'=>'Gaming',        'color'=>'#7c3aed','bg'=>'#f5f3ff'],
                ['slug'=>'mobiles',     'icon'=>'bi-phone-fill',       'label'=>'Phones',        'color'=>'#0891b2','bg'=>'#ecfeff'],
                ['slug'=>'books',       'icon'=>'bi-book-fill',        'label'=>'Books',         'color'=>'#b45309','bg'=>'#fef3c7'],
                ['slug'=>'tools',       'icon'=>'bi-wrench-adjustable','label'=>'Tools',         'color'=>'#475569','bg'=>'#f1f5f9'],
            ] as $cat)
            <div class="col-6 col-md-3 col-lg-{{ count([1]) > 0 ? '3' : '3' }}">
                <a href="{{ route('products.index', ['category' => $cat['slug']]) }}" class="cat-card text-decoration-none">
                    <div class="cat-icon" style="background:{{ $cat['bg'] }};color:{{ $cat['color'] }};">
                        <i class="bi {{ $cat['icon'] }}"></i>
                    </div>
                    <span class="cat-label">{{ $cat['label'] }}</span>
                    <i class="bi bi-arrow-right cat-arrow"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ POPULAR PRODUCTS ════════════════════════════════════════ --}}
<section class="py-5">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-label mb-1">Trending now</div>
                <h2 class="section-title">Popular Items</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-light">
                View all <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        @if($products->isEmpty())
            <div class="empty-state mt-4">
                <i class="bi bi-box-seam"></i>
                <h5>No listings yet</h5>
                <p>Be the first to list an item for trade.</p>
                @auth
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm mt-1">Add a listing</a>
                @endauth
            </div>
        @else
            <div class="row g-4 mt-1">
                @foreach($products as $product)
                    @include('_partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ══ RECENT DEALS ════════════════════════════════════════════ --}}
@if(!$deals->isEmpty())
<section class="py-5" style="background:var(--surface);border-top:1px solid var(--border);">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-label mb-1">Just listed</div>
                <h2 class="section-title">New Arrivals</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-light">
                View all <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4 mt-1">
            @foreach($deals as $deal)
                @php $product = $deal; @endphp
                @include('_partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ HOW IT WORKS ════════════════════════════════════════════ --}}
<section class="py-5 py-lg-6">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label mb-2">Simple process</div>
            <h2 class="section-title" style="font-size:1.75rem;">How Bartaro works</h2>
            <p class="text-muted mt-2" style="max-width:480px;margin:0 auto;font-size:.9rem;">Three easy steps to start trading with people in your community.</p>
        </div>
        <div class="row g-4 g-lg-5 justify-content-center">
            @foreach([
                ['num'=>'1','icon'=>'bi-camera','color'=>'#4f46e5','bg'=>'#eef2ff','title'=>'List your item','desc'=>'Take a few photos, write a short description, and publish. Done in under 2 minutes.'],
                ['num'=>'2','icon'=>'bi-arrow-left-right','color'=>'#059669','bg'=>'#ecfdf5','title'=>'Make or receive an offer','desc'=>'Browse listings and send an offer with your item, cash, or both. Others can offer you too.'],
                ['num'=>'3','icon'=>'bi-box-seam','color'=>'#d97706','bg'=>'#fffbeb','title'=>'Complete the trade','desc'=>'Agree on terms, use our optional escrow protection, and ship or meet up to swap.'],
            ] as $step)
            <div class="col-md-4 text-center">
                <div class="hiw-icon-wrap" style="background:{{ $step['bg'] }};">
                    <i class="bi {{ $step['icon'] }}" style="color:{{ $step['color'] }};font-size:1.5rem;"></i>
                </div>
                <div class="hiw-num" style="color:{{ $step['color'] }};">Step {{ $step['num'] }}</div>
                <h5 class="fw-700 mb-2" style="font-size:1rem;">{{ $step['title'] }}</h5>
                <p class="text-muted mb-0" style="font-size:.855rem;line-height:1.65;">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
        @guest
        <div class="text-center mt-5">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
                Start trading for free <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        @endguest
    </div>
</section>

@endsection

@push('styles')
<style>
/* ── Hero ── */
.hero-section {
    background: linear-gradient(135deg, #0f0c29 0%, #1a1a4e 45%, #0f3460 100%);
    position: relative;
    overflow: hidden;
}
.hero-section::before {
    content: '';
    position: absolute;
    width: 600px; height: 600px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(79,70,229,.25) 0%, transparent 70%);
    top: -200px; right: -100px;
    pointer-events: none;
}
.hero-section::after {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(5,150,105,.15) 0%, transparent 70%);
    bottom: -100px; left: -80px;
    pointer-events: none;
}
.min-vh-hero { min-height: calc(100vh - 62px); }
@media (max-width: 991px) { .min-vh-hero { min-height: auto; padding: 3rem 0; } }

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: rgba(79,70,229,.25);
    border: 1px solid rgba(79,70,229,.4);
    color: #a5b4fc;
    font-size: .78rem;
    font-weight: 600;
    letter-spacing: .04em;
    padding: .35rem .9rem;
    border-radius: 99px;
    margin-bottom: 1.5rem;
}
.hero-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    letter-spacing: -.03em;
    margin-bottom: 1.25rem;
}
.hero-title-accent {
    background: linear-gradient(90deg, #818cf8, #38bdf8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-sub {
    font-size: 1rem;
    color: rgba(255,255,255,.62);
    max-width: 440px;
    line-height: 1.7;
    margin-bottom: 2rem;
}
.hero-btn-ghost {
    background: rgba(255,255,255,.08);
    border: 1.5px solid rgba(255,255,255,.18);
    color: rgba(255,255,255,.85);
    backdrop-filter: blur(8px);
}
.hero-btn-ghost:hover {
    background: rgba(255,255,255,.15);
    color: #fff;
    border-color: rgba(255,255,255,.35);
    transform: translateY(-1px);
}

.hero-stats {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,.1);
}
.hero-stat strong {
    display: block;
    font-size: 1.25rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -.03em;
}
.hero-stat span { font-size: .75rem; color: rgba(255,255,255,.45); }
.hero-stat-divider { width: 1px; height: 32px; background: rgba(255,255,255,.12); }

.hero-feature-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    padding: 2rem 0 2rem 2rem;
}
.hero-feature-card {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 1rem;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: .85rem;
    backdrop-filter: blur(10px);
    transition: all .2s;
}
.hero-feature-card:hover { background: rgba(255,255,255,.1); transform: translateY(-2px); }
.hero-feature-card:nth-child(even) { margin-top: 1.5rem; }
.hero-feature-icon {
    width: 42px; height: 42px;
    border-radius: .65rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.hero-feature-title { font-size: .84rem; font-weight: 700; color: #fff; line-height: 1.2; }
.hero-feature-sub   { font-size: .74rem; color: rgba(255,255,255,.45); margin-top: 2px; }

/* ── Section headers ── */
.section-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: .5rem;
}
.section-label {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--p);
}
.section-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -.02em;
    margin: 0;
}

/* ── Categories ── */
.cat-card {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .85rem 1rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    transition: all var(--transition);
}
.cat-card:hover {
    border-color: #c7d2fe;
    background: var(--p-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}
.cat-card:hover .cat-arrow { opacity: 1; transform: translateX(2px); }
.cat-icon {
    width: 40px; height: 40px;
    border-radius: .65rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.cat-label { font-size: .84rem; font-weight: 600; color: var(--text2); flex: 1; }
.cat-arrow { font-size: .78rem; color: var(--muted2); opacity: 0; transition: all var(--transition); margin-left: auto; }

/* ── How it works ── */
.hiw-icon-wrap {
    width: 64px; height: 64px;
    border-radius: 1.1rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem;
}
.hiw-num {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: .4rem;
}

/* ── Empty state ── */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    border: 1.5px dashed var(--border2);
    border-radius: var(--radius-lg);
    color: var(--muted);
}
.empty-state i { font-size: 2.5rem; opacity: .3; display: block; margin-bottom: 1rem; }
.empty-state h5 { font-weight: 700; color: var(--text2); }
.empty-state p { font-size: .875rem; }

.py-lg-6 { padding-top: 5rem !important; padding-bottom: 5rem !important; }
</style>
@endpush
