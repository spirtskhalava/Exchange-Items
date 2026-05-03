@extends('layouts.app')

@section('content')
<div class="container py-4">

    <a href="{{ route('products.index') }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-1 mb-4">
        <i class="bi bi-arrow-left"></i> Back to Browse
    </a>

    <div class="row g-4">

        {{-- Seller Sidebar --}}
        <div class="col-lg-3">
            <div class="card sticky-top" style="top:76px;">
                <div class="card-body p-4 text-center">
                    {{-- Avatar --}}
                    @if($seller->avatar_url)
                        <img src="{{ $seller->avatar_url }}" class="rounded-circle object-fit-cover mx-auto d-block mb-3"
                             style="width:72px;height:72px;border:3px solid var(--border);">
                    @else
                    @php $sc = ['#4f46e5','#0ea5e9','#10b981','#f59e0b','#ef4444'][ crc32($seller->name) % 5 ]; @endphp
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white mx-auto mb-3"
                         style="width:72px;height:72px;font-size:1.8rem;background:{{ $sc }};">
                        {{ strtoupper(substr($seller->name, 0, 1)) }}
                    </div>
                    @endif
                    <h5 class="fw-bold mb-1">
                        {{ $seller->name }}
                        @include('_partials.verified-badge', ['user' => $seller])
                    </h5>
                    <div class="text-muted small mb-2">Member since {{ $seller->created_at->format('M Y') }}</div>

                    {{-- Rating --}}
                    @php
                        $avg   = $seller->reviewsReceived->avg('rating') ?? 0;
                        $count = $seller->reviewsReceived->count();
                    @endphp
                    <div class="mb-3">
                        <span class="fw-bold text-warning" style="font-size:1.1rem;">{{ number_format($avg, 1) }}</span>
                        <i class="bi bi-star-fill text-warning" style="font-size:.8rem;"></i>
                        <span class="text-muted small ms-1">({{ $count }} {{ Str::plural('review', $count) }})</span>
                    </div>

                    <div class="p-2 rounded-2 mb-3" style="background:var(--bg);">
                        <div class="fw-bold" style="font-size:1.1rem;">{{ $items->count() }}</div>
                        <div class="text-muted small">Active Listings</div>
                    </div>

                    @auth
                        @if(Auth::id() !== $seller->id)
                            <a href="{{ route('messages.openChatWithSeller', $seller->id) }}"
                               class="btn btn-primary w-100 btn-sm mb-2" style="border-radius:.55rem;">
                                <i class="bi bi-chat-dots me-1"></i> Message
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Write a Review --}}
            <div class="card mt-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Leave a Review</h6>
                    @auth
                        @if(auth()->id() === $seller->id)
                            <p class="text-muted small mb-0">You cannot review yourself.</p>
                        @elseif($seller->reviewsReceived->where('reviewer_id', auth()->id())->count() > 0)
                            <div class="d-flex align-items-center gap-2 text-success small">
                                <i class="bi bi-check-circle-fill"></i> You've already reviewed this user.
                            </div>
                        @else
                            <form action="{{ route('user.reviews.store', $seller->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <select name="rating" class="form-select form-select-sm" required>
                                        <option value="5">★★★★★ Excellent</option>
                                        <option value="4">★★★★  Good</option>
                                        <option value="3">★★★   Average</option>
                                        <option value="2">★★    Poor</option>
                                        <option value="1">★     Terrible</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Comment</label>
                                    <textarea name="comment" rows="3" class="form-control form-control-sm"
                                              placeholder="How was your experience?"></textarea>
                                </div>
                                <button type="submit" class="btn btn-dark w-100 btn-sm" style="border-radius:.55rem;">Submit Review</button>
                            </form>
                        @endif
                    @else
                        <p class="text-muted small mb-2">Sign in to leave a review</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 btn-sm" style="border-radius:.55rem;">Login</a>
                    @endauth
                </div>
            </div>

            {{-- Recent Reviews --}}
            <div class="card mt-3">
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <span class="fw-bold small">Recent Feedback</span>
                    </div>
                    <div style="max-height:360px;overflow-y:auto;">
                        @forelse($seller->reviewsReceived as $review)
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold" style="font-size:.82rem;">{{ $review->reviewer->name ?? 'User' }}</span>
                                <span class="text-warning" style="font-size:.8rem;">{{ str_repeat('★', $review->rating) }}<span class="text-muted">{{ str_repeat('★', 5 - $review->rating) }}</span></span>
                            </div>
                            @if($review->comment)
                                <p class="text-muted mb-1" style="font-size:.8rem;line-height:1.4;">{{ $review->comment }}</p>
                            @endif
                            <div class="text-muted" style="font-size:.72rem;">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                        @empty
                        <div class="p-4 text-center text-muted small">
                            <i class="bi bi-chat-square d-block mb-1" style="font-size:1.5rem;opacity:.3;"></i>
                            No reviews yet
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Items Grid --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0" style="font-size:1.3rem;">
                    Items by {{ $seller->name }}
                    <span class="badge ms-2" style="background:rgba(67,97,238,.1);color:var(--primary);font-size:.75rem;vertical-align:middle;">{{ $items->count() }}</span>
                </h2>
            </div>

            @if($items->count() > 0)
                <div class="row g-4">
                    @foreach($items as $item)
                    @php
                        $product = $item;
                    @endphp
                    @include('_partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-box-seam text-muted" style="font-size:3rem;opacity:.25;"></i>
                        <h5 class="mt-3 fw-normal text-muted">No items listed yet</h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
