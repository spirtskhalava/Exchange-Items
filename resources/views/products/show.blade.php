@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.82rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Products</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold">{{ Str::limit($product->name, 35) }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- LEFT: Images + Tabs --}}
        <div class="col-lg-7">

            {{-- Main image --}}
            <div class="card overflow-hidden mb-3" style="border-radius:.9rem;">
                <div class="position-relative" style="background:#f8f9fa;">
                    @php
                        $imagePaths = !empty($product->image_paths) ? json_decode($product->image_paths, true) : [];
                        $mainImageUrl = 'https://placehold.co/700x500/f5f6fa/adb5bd?text=No+Image';
                        if (!empty($imagePaths[0])) {
                            $mainImageUrl = str_starts_with($imagePaths[0], 'http') ? $imagePaths[0] : asset('storage/' . $imagePaths[0]);
                        }
                    @endphp
                    <img src="{{ $mainImageUrl }}" id="main-product-image"
                         class="w-100" alt="{{ $product->name }}"
                         style="height:440px;object-fit:contain;cursor:zoom-in;transition:transform .3s;"
                         data-bs-toggle="modal" data-bs-target="#imageModal"
                         onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform=''">

                    {{-- Wishlist button --}}
                    <div class="position-absolute top-0 end-0 p-3">
                        @auth
                            @php $isWishlisted = Auth::user()->wishlist->contains('product_id', $product->id); @endphp
                            <button class="btn btn-sm bg-white shadow rounded-circle toggle-wishlist d-flex align-items-center justify-content-center"
                                    style="width:38px;height:38px;border:none;"
                                    data-id="{{ $product->id }}" type="button">
                                <i class="fa-heart {{ $isWishlisted ? 'fas text-danger' : 'far text-secondary' }} wishlist-icon"></i>
                            </button>
                        @endauth
                    </div>
                </div>

                {{-- Thumbnails --}}
                @if(count($imagePaths) > 1)
                <div class="p-3 d-flex gap-2 flex-wrap bg-white border-top">
                    @foreach($imagePaths as $idx => $path)
                    @php
                        $thumbUrl = str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
                    @endphp
                    <div class="thumb-wrap rounded-2 overflow-hidden" style="width:68px;height:68px;cursor:pointer;border:2px solid {{ $idx===0 ? 'var(--primary)' : 'var(--border)' }};transition:border-color .15s;"
                         onclick="setMainImage(this, '{{ $thumbUrl }}')">
                        <img src="{{ $thumbUrl }}" class="w-100 h-100" style="object-fit:cover;" alt="Thumb">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Tabs: Description + Reviews --}}
            <div class="card">
                <div class="card-body p-4">
                    <ul class="nav nav-tabs mb-4" id="productTabs">
                        <li class="nav-item">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">Details</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                                Reviews <span class="badge rounded-pill ms-1" style="background:rgba(67,97,238,.1);color:var(--primary);font-size:.7rem;">{{ $product->review_count }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Details --}}
                        <div class="tab-pane fade show active" id="details">
                            <h6 class="fw-bold mb-3">About this item</h6>
                            <div class="text-muted" style="white-space:pre-line;line-height:1.7;font-size:.9rem;">{{ $product->description }}</div>
                        </div>

                        {{-- Reviews --}}
                        <div class="tab-pane fade" id="reviews">
                            {{-- Rating summary --}}
                            <div class="d-flex align-items-center gap-4 mb-4 p-3 rounded-2" style="background:var(--bg);">
                                <div class="text-center">
                                    <div class="fw-bold" style="font-size:2.5rem;line-height:1;color:var(--text);">{{ number_format($product->average_rating, 1) }}</div>
                                    <div class="text-muted small mt-1">out of 5</div>
                                </div>
                                <div>
                                    <div class="text-warning mb-1">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}" style="font-size:.9rem;"></i>
                                        @endfor
                                    </div>
                                    <div class="text-muted small">{{ $product->review_count }} {{ Str::plural('review', $product->review_count) }}</div>
                                </div>
                            </div>

                            {{-- Review form --}}
                            @auth
                                @if(Auth::id() !== $product->user_id)
                                    @php $userReview = $product->reviews->where('user_id', Auth::id())->first(); @endphp
                                    @if(!$userReview)
                                    <div class="card mb-4" style="background:var(--bg);border:none;">
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold mb-3">Write a Review</h6>
                                            <form action="{{ route('products.review.store', $product->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label">Rating</label>
                                                    <div class="rating-input">
                                                        @for($i=5;$i>=1;$i--)
                                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $i==5 ? 'required' : '' }}>
                                                        <label for="star{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm px-4" style="border-radius:.55rem;">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                    @else
                                        <div class="alert alert-success small py-2 mb-4"><i class="bi bi-check-circle-fill me-1"></i>You've already reviewed this item.</div>
                                    @endif
                                @endif
                            @else
                                <div class="alert alert-secondary small py-2 mb-4">
                                    <a href="{{ route('login') }}" class="alert-link">Login</a> to write a review.
                                </div>
                            @endauth

                            {{-- Reviews list --}}
                            @forelse($product->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                                             style="width:36px;height:36px;font-size:.85rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.875rem;">{{ $review->user->name }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ $review->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="text-warning" style="font-size:.85rem;">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-muted mb-0" style="font-size:.875rem;padding-left:44px;">{{ $review->comment }}</p>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-chat-square d-block mb-2" style="font-size:1.8rem;opacity:.3;"></i>
                                <div class="small">No reviews yet — be the first!</div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Info + Actions --}}
        <div class="col-lg-5">
            <div class="sticky-top" style="top:76px;">

                {{-- Product info --}}
                <div class="card mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h1 class="fw-bold mb-0" style="font-size:1.35rem;line-height:1.3;flex:1;">{{ $product->name }}</h1>
                            @php
                                $cmap = ['new'=>['label'=>'New','cls'=>'success'],'used'=>['label'=>'Used','cls'=>'warning'],'refurbished'=>['label'=>'Refurbished','cls'=>'info']];
                                $cond = $cmap[strtolower($product->condition ?? '')] ?? ['label'=>ucfirst($product->condition ?? ''), 'cls'=>'secondary'];
                            @endphp
                            <span class="badge bg-{{ $cond['cls'] }} bg-opacity-10 text-{{ $cond['cls'] }} ms-2 flex-shrink-0" style="align-self:flex-start;">{{ $cond['label'] }}</span>
                        </div>

                        <div class="d-flex align-items-center gap-1 text-success small mb-4">
                            <i class="bi bi-check-circle-fill"></i> Available for exchange
                        </div>

                        {{-- Meta --}}
                        <div class="d-flex flex-column gap-2 mb-4 text-muted small">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-tags" style="width:16px;"></i>
                                <span>{{ ucfirst(str_replace('-', ' ', $product->category ?? '')) }}</span>
                            </div>
                            @if($product->location)
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt" style="width:16px;"></i>
                                <span>{{ $product->location }}</span>
                            </div>
                            @endif
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-eye" style="width:16px;"></i>
                                <span>{{ number_format($product->views) }} views</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-calendar3" style="width:16px;"></i>
                                <span>Posted {{ $product->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-grid gap-2">
                            @if(!Auth::check() || Auth::id() !== $product->user_id)
                                <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-primary py-2">
                                    <i class="bi bi-arrow-left-right me-2"></i>Make an Offer
                                </a>
                                <a href="{{ route('messages.openChatWithSeller', $product->user->id) }}" class="btn btn-outline-secondary py-2">
                                    <i class="bi bi-chat-dots me-2"></i>Contact Seller
                                </a>
                            @else
                                <a href="{{ route('listings.edit', $product) }}" class="btn btn-outline-primary py-2">
                                    <i class="bi bi-pencil me-2"></i>Edit Listing
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Seller card --}}
                <div class="card mb-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Seller</h6>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                                 style="width:48px;height:48px;font-size:1.1rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));">
                                {{ strtoupper(substr($product->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('seller.items', $product->user->id) }}" class="fw-semibold text-dark text-decoration-none" style="font-size:.9rem;">
                                    {{ $product->user->name }}
                                </a>
                                @php
                                    $sAvg   = $product->user->reviewsReceived->avg('rating') ?? 0;
                                    $sCount = $product->user->reviewsReceived->count();
                                @endphp
                                <div class="small">
                                    <span class="text-warning fw-semibold">{{ number_format($sAvg, 1) }} <i class="bi bi-star-fill" style="font-size:.7rem;"></i></span>
                                    <span class="text-muted ms-1">({{ $sCount }} {{ Str::plural('review', $sCount) }})</span>
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">Member since {{ $product->user->created_at->format('M Y') }}</div>
                            </div>
                        </div>
                        <a href="{{ route('seller.items', $product->user->id) }}" class="btn btn-light w-100 btn-sm" style="border-radius:.55rem;font-size:.82rem;">
                            <i class="bi bi-box-seam me-1"></i>View all listings
                        </a>
                    </div>
                </div>

                {{-- Safety tips --}}
                <div class="card" style="background:rgba(67,97,238,.03);">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-2" style="font-size:.83rem;"><i class="bi bi-shield-check text-primary me-1"></i>Safety Tips</h6>
                        <ul class="list-unstyled mb-0" style="font-size:.78rem;color:var(--muted);">
                            <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Meet in public places</li>
                            <li class="mb-1"><i class="bi bi-check text-success me-1"></i>Inspect items before exchange</li>
                            <li><i class="bi bi-check text-success me-1"></i>Use our escrow for added protection</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Lightbox modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modal-image" src="" class="img-fluid rounded-3" style="max-height:85vh;object-fit:contain;" alt="Product">
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link { border:none;color:var(--muted);font-weight:500;padding:.5rem 1rem;border-radius:.5rem .5rem 0 0; }
    .nav-tabs .nav-link.active { color:var(--primary);background:none;border-bottom:2.5px solid var(--primary); }
    .nav-tabs { border-bottom:2px solid var(--border); }
    .thumb-wrap:hover { border-color:var(--primary) !important; }

    /* Star rating input */
    .rating-input { display:flex;flex-direction:row-reverse;justify-content:flex-end;gap:4px; }
    .rating-input input { display:none; }
    .rating-input label { color:#ddd;font-size:1.4rem;cursor:pointer;transition:color .15s; }
    .rating-input input:checked ~ label,
    .rating-input label:hover,
    .rating-input label:hover ~ label { color:#ffc107; }
</style>
@endpush

@push('scripts')
<script>
function setMainImage(thumb, src) {
    document.getElementById('main-product-image').src = src;
    document.querySelectorAll('.thumb-wrap').forEach(t => t.style.borderColor = 'var(--border)');
    thumb.style.borderColor = 'var(--primary)';
}
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('imageModal');
    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', function() {
            document.getElementById('modal-image').src = document.getElementById('main-product-image').src;
        });
    }
    if(window.location.hash === '#reviews') {
        const tab = document.querySelector('#reviews-tab');
        if (tab) new bootstrap.Tab(tab).show();
    }
});
</script>
@endpush
