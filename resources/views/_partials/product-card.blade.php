@php
    $imageUrl = 'https://placehold.co/400x280/f5f6fa/adb5bd?text=No+Image';
    $paths = !empty($product->image_paths) ? json_decode($product->image_paths, true) : null;
    if (is_array($paths) && count($paths) > 0 && !empty($paths[0])) {
        $p = $paths[0];
        if (!str_starts_with($p, 'http')) {
            $imageUrl = asset('storage/' . $p);
        }
    }

    $conditionMap = [
        'new'         => ['label' => 'New',         'class' => 'bg-success'],
        'used'        => ['label' => 'Used',         'class' => 'bg-warning text-dark'],
        'refurbished' => ['label' => 'Refurbished',  'class' => 'bg-info text-dark'],
    ];
    $cond = $conditionMap[strtolower($product->condition ?? '')] ?? ['label' => ucfirst($product->condition ?? ''), 'class' => 'bg-secondary'];
@endphp
<div class="col-xl-3 col-lg-4 col-md-6">
    <div class="card h-100 product-card-item" style="transition:all .2s;">
        <div class="position-relative overflow-hidden" style="border-radius:.9rem .9rem 0 0;">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                 style="width:100%;height:200px;object-fit:cover;display:block;transition:transform .3s;"
                 class="product-card-img">
            <span class="badge {{ $cond['class'] }} position-absolute" style="top:.6rem;left:.6rem;font-size:.72rem;">{{ $cond['label'] }}</span>
            @auth
                @php $isWishlisted = Auth::user()->wishlist->contains('product_id', $product->id); @endphp
                <button class="btn btn-sm bg-white shadow-sm rounded-circle toggle-wishlist position-absolute"
                        style="bottom:.6rem;right:.6rem;width:34px;height:34px;display:grid;place-items:center;border:none;"
                        data-id="{{ $product->id }}" type="button">
                    <i class="fa-heart {{ $isWishlisted ? 'fas text-danger' : 'far text-secondary' }} wishlist-icon"></i>
                </button>
            @endauth
        </div>
        <div class="card-body d-flex flex-column p-3">
            <div class="mb-1">
                <span class="badge bg-light text-muted border" style="font-size:.7rem;">{{ ucfirst(str_replace('-',' ',$product->category ?? '')) }}</span>
            </div>
            <h6 class="fw-bold text-truncate mb-1" title="{{ $product->name }}">{{ $product->name }}</h6>
            <p class="text-muted small mb-3" style="flex:1;line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $product->description }}</p>
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <small class="text-muted"><i class="bi bi-eye me-1"></i>{{ $product->views ?? 0 }}</small>
                <div class="d-flex gap-1">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:.5rem;font-size:.8rem;padding:.3rem .7rem;">View</a>
                    @auth
                        @if($product->user_id !== Auth::id())
                            <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-sm btn-primary" style="border-radius:.5rem;font-size:.8rem;padding:.3rem .7rem;">Offer</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
