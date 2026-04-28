@extends('layouts.app')

@section('content')

{{-- Hero Section --}}
<section style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); padding: 5rem 0 4rem; position: relative; overflow: hidden;">
    <div style="position:absolute;width:500px;height:500px;border-radius:50%;background:rgba(67,97,238,.15);top:-150px;right:-100px;"></div>
    <div style="position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(76,201,240,.08);bottom:-80px;left:-60px;"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <span class="badge mb-3" style="background:rgba(76,201,240,.15);color:#4cc9f0;border:1px solid rgba(76,201,240,.3);padding:.5rem 1rem;border-radius:50rem;font-size:.8rem;font-weight:600;letter-spacing:.5px;">
                    <i class="bi bi-arrow-left-right me-1"></i> ITEM EXCHANGE PLATFORM
                </span>
                <h1 class="display-5 fw-bold mb-3 lh-sm">Trade Smarter,<br><span style="color:#4cc9f0;">Live Better.</span></h1>
                <p style="color:rgba(255,255,255,.65);font-size:1.05rem;max-width:460px;line-height:1.7;" class="mb-4">
                    Exchange items you no longer need for things you actually want. Safe, simple, and community-driven.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4" style="border-radius:.75rem;">
                        Browse Items <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-lg px-4" style="background:rgba(255,255,255,.08);border:1.5px solid rgba(255,255,255,.2);color:#fff;border-radius:.75rem;">
                        Join Free
                    </a>
                    @endguest
                    @auth
                    <a href="{{ route('products.create') }}" class="btn btn-lg px-4" style="background:rgba(255,255,255,.08);border:1.5px solid rgba(255,255,255,.2);color:#fff;border-radius:.75rem;">
                        <i class="bi bi-plus-lg me-1"></i> List an Item
                    </a>
                    @endauth
                </div>
                <div class="d-flex gap-4 mt-4">
                    <div>
                        <div class="fw-bold text-white" style="font-size:1.4rem;">500+</div>
                        <div style="color:rgba(255,255,255,.45);font-size:.8rem;">Active Listings</div>
                    </div>
                    <div style="border-left:1px solid rgba(255,255,255,.1);padding-left:1.5rem;">
                        <div class="fw-bold text-white" style="font-size:1.4rem;">1,200+</div>
                        <div style="color:rgba(255,255,255,.45);font-size:.8rem;">Trades Completed</div>
                    </div>
                    <div style="border-left:1px solid rgba(255,255,255,.1);padding-left:1.5rem;">
                        <div class="fw-bold text-white" style="font-size:1.4rem;">800+</div>
                        <div style="color:rgba(255,255,255,.45);font-size:.8rem;">Members</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-flex justify-content-end mt-4 mt-lg-0">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.85rem;max-width:380px;width:100%;">
                    <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.2rem;backdrop-filter:blur(10px);">
                        <i class="bi bi-shield-check-fill" style="color:#4cc9f0;font-size:1.5rem;"></i>
                        <div style="color:#fff;font-weight:600;font-size:.85rem;margin-top:.5rem;">Safe Trades</div>
                        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Escrow protection</div>
                    </div>
                    <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.2rem;backdrop-filter:blur(10px);margin-top:1.5rem;">
                        <i class="bi bi-arrow-left-right" style="color:#7b2fff;font-size:1.5rem;"></i>
                        <div style="color:#fff;font-weight:600;font-size:.85rem;margin-top:.5rem;">Easy Exchange</div>
                        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Item-for-item</div>
                    </div>
                    <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.2rem;backdrop-filter:blur(10px);">
                        <i class="bi bi-chat-dots-fill" style="color:#06d6a0;font-size:1.5rem;"></i>
                        <div style="color:#fff;font-weight:600;font-size:.85rem;margin-top:.5rem;">Direct Chat</div>
                        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Message sellers</div>
                    </div>
                    <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.2rem;backdrop-filter:blur(10px);margin-top:1.5rem;">
                        <i class="bi bi-star-fill" style="color:#ffd166;font-size:1.5rem;"></i>
                        <div style="color:#fff;font-weight:600;font-size:.85rem;margin-top:.5rem;">Verified Reviews</div>
                        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Trusted community</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Category Quick Links --}}
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="font-size:1.3rem;">Browse by Category</h2>
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:.55rem;font-size:.8rem;">View All</a>
    </div>
    <div class="row g-3">
        @php
            $cats = [
                ['slug'=>'electronics','icon'=>'bi-cpu','label'=>'Electronics','color'=>'#4361ee','bg'=>'rgba(67,97,238,.08)'],
                ['slug'=>'fashion','icon'=>'bi-bag','label'=>'Fashion','color'=>'#e63946','bg'=>'rgba(230,57,70,.08)'],
                ['slug'=>'home-garden','icon'=>'bi-house-heart','label'=>'Home & Garden','color'=>'#06d6a0','bg'=>'rgba(6,214,160,.08)'],
                ['slug'=>'sports','icon'=>'bi-trophy','label'=>'Sports','color'=>'#ff9f1c','bg'=>'rgba(255,159,28,.08)'],
                ['slug'=>'books','icon'=>'bi-book','label'=>'Books','color'=>'#7b2fff','bg'=>'rgba(123,47,255,.08)'],
                ['slug'=>'toys','icon'=>'bi-controller','label'=>'Toys & Games','color'=>'#ef476f','bg'=>'rgba(239,71,111,.08)'],
            ];
        @endphp
        @foreach($cats as $cat)
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('products.index', ['category'=>$cat['slug']]) }}" class="text-decoration-none">
                <div class="card text-center py-3 px-2" style="border-radius:.9rem;transition:all .2s;cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.08)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div class="mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:48px;height:48px;border-radius:.75rem;background:{{ $cat['bg'] }};">
                        <i class="bi {{ $cat['icon'] }}" style="color:{{ $cat['color'] }};font-size:1.3rem;"></i>
                    </div>
                    <div style="font-size:.82rem;font-weight:600;color:#374151;">{{ $cat['label'] }}</div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>

{{-- Popular Products --}}
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="font-size:1.3rem;">Popular Items</h2>
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:.55rem;font-size:.8rem;">View All</a>
    </div>

    @if($products->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="bi bi-box-seam text-muted" style="font-size:3rem;opacity:.3;"></i>
                <h5 class="mt-3 text-muted fw-normal">No listings yet</h5>
                <p class="text-muted small">Be the first to list an item!</p>
                @auth
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm mt-2">Add Listing</a>
                @endauth
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
                @include('_partials.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
</section>

{{-- Interesting Deals --}}
@if(!$deals->isEmpty())
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="font-size:1.3rem;">Recently Added</h2>
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:.55rem;font-size:.8rem;">View All</a>
    </div>
    <div class="row g-4">
        @foreach($deals as $deal)
            @php $product = $deal; @endphp
            @include('_partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

{{-- How It Works --}}
<section style="background:#fff;border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:4rem 0;" class="my-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How Bartaro Works</h2>
            <p class="text-muted">Three simple steps to start trading</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;background:rgba(67,97,238,.08);border-radius:1rem;">
                    <i class="bi bi-plus-square" style="font-size:1.6rem;color:var(--primary);"></i>
                </div>
                <h5 class="fw-bold">1. List Your Item</h5>
                <p class="text-muted small">Upload photos and describe what you have. It's free and takes under a minute.</p>
            </div>
            <div class="col-md-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;background:rgba(6,214,160,.08);border-radius:1rem;">
                    <i class="bi bi-arrow-left-right" style="font-size:1.6rem;color:#06d6a0;"></i>
                </div>
                <h5 class="fw-bold">2. Make an Offer</h5>
                <p class="text-muted small">Browse listings and make an exchange offer. Offer your item, add cash top-up, or both.</p>
            </div>
            <div class="col-md-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;background:rgba(255,159,28,.08);border-radius:1rem;">
                    <i class="bi bi-box2-heart" style="font-size:1.6rem;color:#ff9f1c;"></i>
                </div>
                <h5 class="fw-bold">3. Complete the Trade</h5>
                <p class="text-muted small">Agree on terms, use our optional escrow protection, and ship your items safely.</p>
            </div>
        </div>
    </div>
</section>

@endsection
