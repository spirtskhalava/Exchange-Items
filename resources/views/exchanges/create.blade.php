@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:580px;">

    {{-- Back link --}}
    <a href="{{ route('products.show', $product->id) }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-1 mb-3">
        <i class="bi bi-arrow-left"></i> Back to listing
    </a>

    {{-- Target item preview --}}
    <div class="card mb-4" style="background:linear-gradient(135deg,#f8f9ff,#eef1ff);">
        <div class="card-body p-3 d-flex align-items-center gap-3">
            @php
                $imageUrl = 'https://placehold.co/80x80/f5f6fa/adb5bd?text=No+Image';
                $paths = !empty($product->image_paths) ? json_decode($product->image_paths, true) : null;
                if (is_array($paths) && !empty($paths[0]) && !str_starts_with($paths[0], 'http')) {
                    $imageUrl = asset('storage/' . $paths[0]);
                }
            @endphp
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                 style="width:70px;height:70px;object-fit:cover;border-radius:.65rem;flex-shrink:0;">
            <div>
                <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px;">You want</div>
                <div class="fw-bold" style="font-size:1rem;">{{ $product->name }}</div>
                <div class="text-muted small">by {{ $product->user->name }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-1">Make an Offer</h5>
            <p class="text-muted small mb-4">Select what you'll offer in exchange. You can offer an item, cash, or both.</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 small mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('exchanges.store', $product->id) }}" method="POST">
                @csrf

                {{-- Offer product --}}
                <div class="mb-4">
                    <label class="form-label">Your Item to Trade <span class="text-muted fw-normal">(optional)</span></label>
                    @if($userProducts->isEmpty())
                        <div class="p-3 rounded-2 text-center small text-muted" style="background:var(--bg);border:1.5px dashed var(--border);">
                            <i class="bi bi-box-seam d-block mb-1" style="font-size:1.2rem;opacity:.5;"></i>
                            You have no listings. <a href="{{ route('products.create') }}" class="text-primary fw-semibold">Add one</a> to trade with.
                        </div>
                    @else
                        <select name="offered_product_id" class="form-select">
                            <option value="">— Cash only / no item —</option>
                            @foreach($userProducts as $up)
                                <option value="{{ $up->id }}" {{ old('offered_product_id') == $up->id ? 'selected' : '' }}>
                                    {{ $up->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                {{-- Cash top-up --}}
                <div class="mb-4">
                    <label class="form-label">Cash Top-up <span class="text-muted fw-normal">(optional)</span></label>
                    <div class="position-relative">
                        <span class="position-absolute fw-semibold text-muted" style="top:50%;transform:translateY(-50%);left:.9rem;">$</span>
                        <input type="number" name="money_offer" value="{{ old('money_offer') }}"
                               class="form-control @error('money_offer') is-invalid @enderror"
                               style="padding-left:1.75rem;"
                               placeholder="0.00" step="0.01" min="0">
                    </div>
                    @error('money_offer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="text-muted small mt-1">Add extra cash to sweeten your offer</div>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-3 border-top pt-3 mt-4">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-light px-4" style="border-radius:.65rem;">Cancel</a>
                    <button type="submit" class="btn btn-primary flex-1" style="border-radius:.65rem;flex:1;">
                        <i class="bi bi-arrow-left-right me-1"></i> Send Offer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
