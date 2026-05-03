@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">Trade Offers</h1>
        <p class="text-muted small mt-1 mb-0">Review incoming offers and track your sent offers</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 py-2 px-3" role="alert">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="row g-4">

        {{-- ============================
             INCOMING OFFERS
        ============================ --}}
        <div class="col-lg-6">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="fw-bold" style="font-size:.95rem;">Incoming</span>
                <span class="badge rounded-pill" style="background:rgba(79,70,229,.1);color:var(--p);font-size:.75rem;">{{ $receivedOffers->total() }}</span>
            </div>

            @forelse($receivedOffers as $offer)
            <div class="card mb-3 offer-card">
                <div class="card-body p-4">

                    {{-- Offer header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                                 style="width:34px;height:34px;font-size:.85rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));flex-shrink:0;">
                                {{ strtoupper(substr($offer->requester->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem;line-height:1.2;">{{ $offer->requester->name ?? 'Unknown' }}</div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $offer->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @php
                            $sc = match($offer->status) {
                                'accepted' => 'success',
                                'declined' => 'danger',
                                default    => 'warning',
                            };
                        @endphp
                        <span class="badge" style="background:rgba(var(--bs-{{ $sc }}-rgb),.1);color:var(--bs-{{ $sc }});border-radius:50rem;padding:.35rem .8rem;font-size:.72rem;">
                            {{ ucfirst($offer->status) }}
                        </span>
                    </div>

                    {{-- Trade visual --}}
                    <div class="d-flex align-items-stretch gap-2 mb-3">
                        <div class="flex-1 p-2 rounded-2 text-center" style="background:var(--bg);flex:1;">
                            <div class="text-muted" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.2rem;">You Give</div>
                            <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="font-size:.83rem;">{{ $offer->requestedProduct->name }}</a>
                        </div>
                        <div class="d-flex align-items-center px-1 text-muted" style="opacity:.35;font-size:1rem;">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div class="flex-1 p-2 rounded-2 text-center" style="background:var(--bg);flex:1;">
                            <div class="text-muted" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.2rem;">You Receive</div>
                            @if(isset($offer->offeredProduct->id))
                                <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="font-size:.83rem;">{{ $offer->offeredProduct->name }}</a>
                            @else
                                <span class="fw-semibold text-dark" style="font-size:.83rem;">Cash Only</span>
                            @endif
                        </div>
                    </div>

                    {{-- Cash top-up --}}
                    @if($offer->money_offer)
                    <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:rgba(6,214,160,.07);border:1px solid rgba(6,214,160,.2);">
                        <i class="bi bi-cash-coin text-success" style="font-size:.9rem;"></i>
                        <span style="font-size:.82rem;color:#374151;">Includes <strong>${{ number_format($offer->money_offer, 2) }}</strong> cash top-up</span>
                    </div>
                    @endif

                    {{-- Actions --}}
                    @if($offer->status === 'pending')
                    <div class="row g-2">
                        <div class="col-6">
                            <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="declined">
                                <button type="submit" class="btn btn-light w-100 btn-sm text-muted py-2" style="border-radius:.55rem;">Decline</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form method="POST" action="{{ route('exchanges.updateStatus', $offer) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="btn btn-dark w-100 btn-sm py-2" style="border-radius:.55rem;">Accept</button>
                            </form>
                        </div>
                    </div>
                    @endif

                    @if($offer->status === 'accepted')
                        <div class="mt-3 pt-3 border-top">
                            @include('insurance._panel', ['offer' => $offer, 'myRole' => 'responder'])
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                    <p class="text-muted small mt-2 mb-0">No incoming offers yet</p>
                </div>
            </div>
            @endforelse

            @if($receivedOffers->hasPages())
            <div class="mt-2 d-flex justify-content-center">{{ $receivedOffers->withQueryString()->links() }}</div>
            @endif
        </div>

        {{-- ============================
             SENT OFFERS
        ============================ --}}
        <div class="col-lg-6">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="fw-bold" style="font-size:.95rem;">Sent</span>
                <span class="badge rounded-pill" style="background:rgba(79,70,229,.1);color:var(--p);font-size:.75rem;">{{ $sentOffers->total() }}</span>
            </div>

            @forelse($sentOffers as $offer)
            <div class="card mb-3 offer-card">
                <div class="card-body p-4">

                    {{-- Offer header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:34px;height:34px;background:var(--bg);flex-shrink:0;">
                                <i class="bi bi-arrow-up-right text-muted" style="font-size:.8rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem;line-height:1.2;">To: {{ $offer->responder->name ?? 'Unknown' }}</div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $offer->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @php
                            $sc = match($offer->status) {
                                'accepted' => 'success',
                                'declined' => 'danger',
                                default    => 'warning',
                            };
                        @endphp
                        <span class="badge" style="background:rgba(var(--bs-{{ $sc }}-rgb),.1);color:var(--bs-{{ $sc }});border-radius:50rem;padding:.35rem .8rem;font-size:.72rem;">
                            {{ ucfirst($offer->status) }}
                        </span>
                    </div>

                    {{-- Trade visual --}}
                    <div class="d-flex align-items-stretch gap-2 mb-3">
                        <div class="flex-1 p-2 rounded-2 text-center" style="background:var(--bg);flex:1;">
                            <div class="text-muted" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.2rem;">You Offer</div>
                            @if(isset($offer->offeredProduct->id))
                                <a href="{{ route('products.show', $offer->offeredProduct->id) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="font-size:.83rem;">{{ $offer->offeredProduct->name }}</a>
                            @else
                                <span class="fw-semibold text-dark" style="font-size:.83rem;">Cash Only</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center px-1 text-muted" style="opacity:.35;font-size:1rem;">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        <div class="flex-1 p-2 rounded-2 text-center" style="background:var(--bg);flex:1;">
                            <div class="text-muted" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.2rem;">For</div>
                            <a href="{{ route('products.show', $offer->requestedProduct->id) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="font-size:.83rem;">{{ $offer->requestedProduct->name }}</a>
                        </div>
                    </div>

                    {{-- Cash --}}
                    @if($offer->money_offer)
                    <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:rgba(6,214,160,.07);border:1px solid rgba(6,214,160,.2);">
                        <i class="bi bi-cash-coin text-success" style="font-size:.9rem;"></i>
                        <span style="font-size:.82rem;color:#374151;">You added <strong>${{ number_format($offer->money_offer, 2) }}</strong> cash</span>
                    </div>
                    @endif

                    @if($offer->status === 'pending' && $offer->requester_id === Auth::id())
                    <form method="POST" action="{{ route('exchanges.cancel', $offer) }}"
                          onsubmit="return confirm('Withdraw this offer?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100 py-2" style="border-radius:.55rem;border-style:dashed;">
                            <i class="bi bi-x me-1"></i>Withdraw Offer
                        </button>
                    </form>
                    @endif

                    @if($offer->status === 'accepted')
                        <div class="mt-3 pt-3 border-top">
                            @include('insurance._panel', ['offer' => $offer, 'myRole' => 'requester'])
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-send text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                    <p class="text-muted small mt-2 mb-0">No sent offers yet</p>
                </div>
            </div>
            @endforelse

            @if($sentOffers->hasPages())
            <div class="mt-2 d-flex justify-content-center">{{ $sentOffers->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .offer-card { transition: box-shadow .2s, transform .2s; }
    .offer-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.06) !important; }
</style>
@endpush
