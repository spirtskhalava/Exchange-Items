@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:760px;">

    <div class="mb-4">
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">Trade History</h1>
        <p class="text-muted small mt-1 mb-0">Every completed exchange you've been part of</p>
    </div>

    @if($trades->total() === 0)
        <div class="card text-center py-5">
            <div class="card-body">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                     style="width:64px;height:64px;background:var(--p-light);">
                    <i class="bi bi-arrow-left-right" style="font-size:1.6rem;color:var(--p);"></i>
                </div>
                <h5 class="fw-bold mb-1">No trades yet</h5>
                <p class="text-muted small mb-4">Once you complete a trade it will appear here.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="border-radius:.65rem;">Browse Items</a>
            </div>
        </div>
    @else

    {{-- Stats strip --}}
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card text-center p-3">
                <div class="fw-bold" style="font-size:1.6rem;color:var(--p);">{{ $trades->total() }}</div>
                <div class="text-muted small mt-1">Total Trades</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center p-3">
                @php
                    $partners = $trades->getCollection()->map(function($t) {
                        return Auth::id() === $t->requester_id ? $t->responder_id : $t->requester_id;
                    })->unique()->count();
                @endphp
                <div class="fw-bold" style="font-size:1.6rem;color:#059669;">{{ $partners }}</div>
                <div class="text-muted small mt-1">Trade Partners</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center p-3">
                @php
                    $cashTotal = $trades->getCollection()->sum('money_offer');
                @endphp
                <div class="fw-bold" style="font-size:1.6rem;color:#d97706;">${{ number_format($cashTotal, 0) }}</div>
                <div class="text-muted small mt-1">Cash Exchanged</div>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="trade-timeline">
        @foreach($trades as $trade)
        @php
            $isRequester = Auth::id() === $trade->requester_id;
            $partner     = $isRequester ? $trade->responder   : $trade->requester;
            $gave        = $isRequester ? $trade->offeredProduct    : $trade->requestedProduct;
            $got         = $isRequester ? $trade->requestedProduct  : $trade->offeredProduct;

            $pColors = ['#4f46e5','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
            $pColor  = $partner ? $pColors[crc32($partner->name) % count($pColors)] : '#9ca3af';
        @endphp
        <div class="tl-item">
            <div class="tl-dot" style="background:var(--p);"></div>
            <div class="tl-card card">
                <div class="card-body p-4">

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            @if($partner?->avatar_url)
                                <img src="{{ $partner->avatar_url }}" class="rounded-circle object-fit-cover"
                                     style="width:36px;height:36px;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                     style="width:36px;height:36px;font-size:.9rem;background:{{ $pColor }};">
                                    {{ strtoupper(substr($partner?->name ?? '?', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem;">
                                    Trade with
                                    @if($partner)
                                        <a href="{{ route('seller.items', $partner->id) }}" class="text-decoration-none" style="color:var(--p);">{{ $partner->name }}</a>
                                    @else
                                        <span class="text-muted">Deleted User</span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $trade->updated_at->format('d M Y') }}</div>
                            </div>
                        </div>
                        <span class="badge" style="background:rgba(5,150,105,.1);color:#059669;border-radius:50rem;padding:.3rem .75rem;font-size:.72rem;font-weight:600;">
                            <i class="bi bi-check-circle-fill me-1"></i>Completed
                        </span>
                    </div>

                    {{-- Trade visual --}}
                    <div class="d-flex align-items-stretch gap-3">
                        <div class="flex-1 p-3 rounded-2 text-center" style="background:rgba(220,252,231,.5);border:1px solid #bbf7d0;flex:1;">
                            <div style="font-size:.65rem;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem;">You Gave</div>
                            @if($gave)
                                <a href="{{ route('products.show', $gave->id) }}" class="fw-semibold text-decoration-none text-truncate d-block" style="font-size:.83rem;color:var(--text);">{{ $gave->name }}</a>
                                <span style="font-size:.7rem;color:var(--muted);">{{ ucfirst($gave->category ?? '') }}</span>
                            @else
                                <span class="fw-semibold" style="font-size:.83rem;color:var(--muted);">Item removed</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:32px;height:32px;background:var(--p-light);color:var(--p);font-size:.9rem;">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                        </div>

                        <div class="flex-1 p-3 rounded-2 text-center" style="background:rgba(238,242,255,.6);border:1px solid #c7d2fe;flex:1;">
                            <div style="font-size:.65rem;font-weight:700;color:var(--p);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem;">You Got</div>
                            @if($got)
                                <a href="{{ route('products.show', $got->id) }}" class="fw-semibold text-decoration-none text-truncate d-block" style="font-size:.83rem;color:var(--text);">{{ $got->name }}</a>
                                <span style="font-size:.7rem;color:var(--muted);">{{ ucfirst($got->category ?? '') }}</span>
                            @else
                                <span class="fw-semibold" style="font-size:.83rem;color:var(--muted);">Item removed</span>
                            @endif
                        </div>
                    </div>

                    @if($trade->money_offer)
                    <div class="mt-3 d-flex align-items-center gap-2 p-2 rounded-2"
                         style="background:rgba(6,214,160,.07);border:1px solid rgba(6,214,160,.2);">
                        <i class="bi bi-cash-coin text-success" style="font-size:.9rem;"></i>
                        <span style="font-size:.82rem;color:var(--text2);">
                            @if($isRequester)
                                You added <strong>${{ number_format($trade->money_offer, 2) }}</strong> cash top-up
                            @else
                                You received <strong>${{ number_format($trade->money_offer, 2) }}</strong> cash top-up
                            @endif
                        </span>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($trades->hasPages())
    <div class="pagination-wrap mt-4 pt-3 border-top">
        <div class="pagination-info">Showing {{ $trades->firstItem() }}–{{ $trades->lastItem() }} of {{ $trades->total() }}</div>
        {{ $trades->links() }}
    </div>
    @endif

    @endif
</div>
@endsection

@push('styles')
<style>
.trade-timeline { position: relative; padding-left: 1.75rem; }
.trade-timeline::before {
    content: '';
    position: absolute; left: .55rem; top: 0; bottom: 0;
    width: 2px; background: var(--border);
}
.tl-item { position: relative; margin-bottom: 1.5rem; }
.tl-dot {
    position: absolute; left: -1.2rem; top: 1.3rem;
    width: 12px; height: 12px; border-radius: 50%;
    border: 2px solid #fff; box-shadow: 0 0 0 2px var(--p);
}
.tl-card { border-radius: var(--radius-lg) !important; }
</style>
@endpush
