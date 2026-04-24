@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div class="container py-5" style="max-width: 640px;">
    <div class="mb-4">
        <a href="{{ route('offers.index') }}" class="text-muted small text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Back to Offers
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4 p-md-5">

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                    <i class="bi bi-shield-exclamation text-danger fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">Open a Dispute</h4>
                    <p class="text-muted small mb-0">Describe what went wrong and provide evidence</p>
                </div>
            </div>

            {{-- Exchange summary --}}
            <div class="bg-light rounded-2 p-3 mb-4">
                <div class="row g-2 text-center">
                    <div class="col-5">
                        <small class="text-muted d-block">You Give</small>
                        <strong class="small">
                            @if(Auth::id() === $exchange->requester_id)
                                {{ $exchange->offeredProduct->name ?? 'Cash' }}
                            @else
                                {{ $exchange->requestedProduct->name }}
                            @endif
                        </strong>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-left-right text-muted"></i>
                    </div>
                    <div class="col-5">
                        <small class="text-muted d-block">You Receive</small>
                        <strong class="small">
                            @if(Auth::id() === $exchange->requester_id)
                                {{ $exchange->requestedProduct->name }}
                            @else
                                {{ $exchange->offeredProduct->name ?? 'Cash' }}
                            @endif
                        </strong>
                    </div>
                </div>
                <hr class="my-2 opacity-25">
                <div class="text-center small text-muted">
                    Total escrow locked:
                    <strong class="text-dark">${{ number_format($insurance->requesterLockedAmount() + $insurance->responderLockedAmount(), 2) }}</strong>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 small mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('disputes.store', $exchange) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Describe the Problem <span class="text-danger">*</span></label>
                    <textarea name="description" rows="5"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="What went wrong? Wrong item, damaged, missing parts, etc."
                              required minlength="20" maxlength="2000">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Evidence (Photos / Documents)</label>
                    <input type="file" name="evidence[]"
                           class="form-control @error('evidence.*') is-invalid @enderror"
                           multiple accept="image/*,.pdf">
                    <div class="form-text text-muted">Up to 5 files. Images or PDFs, max 5MB each.</div>
                    @error('evidence.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-warning py-2 px-3 small mb-4">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Escrow remains frozen until admin resolves the case. Filing a false dispute may result in account penalties.
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger py-2">
                        <i class="bi bi-send me-1"></i> Submit Dispute
                    </button>
                    <a href="{{ route('offers.index') }}" class="btn btn-light py-2 text-muted">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
