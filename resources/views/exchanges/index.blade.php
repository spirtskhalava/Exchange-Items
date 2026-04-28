@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">Exchange Requests</h1>
        <p class="text-muted small mt-1 mb-0">Review all incoming exchange requests</p>
    </div>

    @if($exchanges->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="bi bi-arrow-left-right text-muted" style="font-size:3rem;opacity:.25;"></i>
                <h5 class="mt-3 fw-normal text-muted">No exchange requests yet</h5>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($exchanges as $exchange)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold" style="font-size:.875rem;">Exchange Request</span>
                            @php
                                $sc = match($exchange->status) { 'accepted'=>'success','declined'=>'danger',default=>'warning' };
                            @endphp
                            <span class="badge bg-{{ $sc }} bg-opacity-10 text-{{ $sc }}" style="border-radius:50rem;padding:.3rem .75rem;font-size:.72rem;">{{ ucfirst($exchange->status) }}</span>
                        </div>

                        <div class="mb-3 text-muted small">
                            <div class="mb-1"><span class="text-dark fw-semibold">Wanted:</span> {{ $exchange->requestedProduct->name }}</div>
                            <div><span class="text-dark fw-semibold">Offered:</span> {{ $exchange->offeredProduct->name ?? 'Cash only' }}</div>
                        </div>

                        @if($exchange->status === 'pending')
                        <form action="{{ route('exchanges.updateStatus', $exchange->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="submit" name="status" value="declined" class="btn btn-light w-100 btn-sm text-muted" style="border-radius:.55rem;">Decline</button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" name="status" value="accepted" class="btn btn-dark w-100 btn-sm" style="border-radius:.55rem;">Accept</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
