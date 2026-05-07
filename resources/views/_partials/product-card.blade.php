@php
    $imgUrl = 'https://placehold.co/480x340/f1f5f9/94a3b8?text=No+Photo';
    $paths  = !empty($product->image_paths) ? json_decode($product->image_paths, true) : null;
    if (is_array($paths) && !empty($paths[0])) {
        $imgUrl = str_starts_with($paths[0], 'http')
            ? $paths[0]
            : asset('storage/' . $paths[0]);
    }

    $condCfg = [
        'new'         => ['label' => 'New',         'bg' => '#dcfce7', 'color' => '#15803d'],
        'used'        => ['label' => 'Used',         'bg' => '#fef9c3', 'color' => '#92400e'],
        'refurbished' => ['label' => 'Refurbished',  'bg' => '#dbeafe', 'color' => '#1d4ed8'],
    ];
    $cond = $condCfg[strtolower($product->condition ?? '')] ?? ['label' => ucfirst($product->condition ?? ''), 'bg' => '#f3f4f6', 'color' => '#4b5563'];
@endphp

<div class="col-xl-3 col-lg-4 col-sm-6">
    <div class="card pcard h-100 p-0 overflow-hidden">

        {{-- Image area --}}
        <div class="position-relative overflow-hidden" style="height:200px;background:#f8fafc;">
            <img src="{{ $imgUrl }}" alt="{{ $product->name }}"
                 class="pcard-img w-100 h-100" style="object-fit:cover;display:block;">

            {{-- Condition badge --}}
            <span class="position-absolute" style="top:.6rem;left:.6rem;background:{{ $cond['bg'] }};color:{{ $cond['color'] }};font-size:.68rem;font-weight:700;padding:.25rem .55rem;border-radius:.4rem;letter-spacing:.03em;">
                {{ $cond['label'] }}
            </span>

            {{-- Wishlist --}}
            @auth
                @php $wishlisted = Auth::user()->wishlist->contains('product_id', $product->id); @endphp
                <button class="toggle-wishlist position-absolute d-flex align-items-center justify-content-center"
                        data-id="{{ $product->id }}"
                        style="bottom:.6rem;right:.6rem;width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.9);backdrop-filter:blur(8px);border:none;cursor:pointer;transition:all .18s;box-shadow:0 2px 8px rgba(0,0,0,.12);"
                        onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform=''">
                    <i class="fa-heart {{ $wishlisted ? 'fas text-danger' : 'far text-muted' }} wishlist-icon" style="font-size:.8rem;"></i>
                </button>
            @endauth
        </div>

        {{-- Body --}}
        <div class="card-body p-3 d-flex flex-column">

            {{-- Category --}}
            <div class="mb-1">
                <span style="font-size:.7rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">
                    {{ ucfirst(str_replace('-', ' ', $product->category ?? '')) }}
                </span>
            </div>

            {{-- Name --}}
            <h6 class="fw-700 mb-1 text-truncate" title="{{ $product->name }}" style="font-size:.9rem;font-weight:700;color:var(--text);line-height:1.3;">
                {{ $product->name }}
            </h6>

            {{-- Description --}}
            <p class="mb-0" style="font-size:.78rem;color:var(--muted);line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;flex:1;">
                {{ $product->description }}
            </p>

            {{-- Footer --}}
            <div class="d-flex align-items-center justify-content-between mt-3 pt-3" style="border-top:1px solid var(--border);">
                <span style="font-size:.74rem;color:var(--muted2);display:flex;align-items:center;gap:.3rem;">
                    <i class="bi bi-eye"></i> {{ number_format($product->views ?? 0) }}
                </span>
                <div class="d-flex gap-1">
                    <a href="{{ route('products.show', $product->id) }}"
                       class="btn btn-sm btn-light" style="padding:.3rem .7rem;font-size:.77rem;">
                        View
                    </a>
                    @auth
                        @if($product->user_id !== Auth::id())
                            <a href="{{ route('exchanges.create', $product->id) }}"
                               class="btn btn-sm btn-primary" style="padding:.3rem .7rem;font-size:.77rem;">
                                Offer
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
