@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div class="container py-5">

    <div class="mb-5 border-bottom pb-3 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Insurance Disputes</h1>
            <p class="text-muted small mt-1">Review evidence and resolve escrow cases</p>
        </div>
        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Admin Panel</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 small mb-4">{{ session('success') }}</div>
    @endif

    @forelse($disputes as $dispute)
        @php
            $exchange = $dispute->exchange;
            $ins      = $exchange->insurance;
            $colors   = ['pending' => 'warning', 'resolved_filer' => 'success', 'resolved_other' => 'info', 'dismissed' => 'secondary'];
            $color    = $colors[$dispute->status] ?? 'secondary';
        @endphp

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill me-2">
                            {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                        </span>
                        <small class="text-muted">Dispute #{{ $dispute->id }} — {{ $dispute->created_at->format('M d, Y H:i') }}</small>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="bg-light rounded-2 p-3">
                            <p class="small fw-semibold text-muted mb-1">REQUESTER</p>
                            <p class="fw-bold mb-1">{{ $exchange->requester->username }}</p>
                            <p class="small text-muted mb-1">Item: <em>{{ $exchange->offeredProduct->name ?? 'Cash' }}</em></p>
                            <p class="small text-muted mb-0">
                                Locked: <strong>${{ number_format($ins?->requesterLockedAmount() ?? 0, 2) }}</strong>
                                (value ${{ number_format($ins?->req_item_value ?? 0, 2) }} + $5 fee)
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light rounded-2 p-3">
                            <p class="small fw-semibold text-muted mb-1">RESPONDER</p>
                            <p class="fw-bold mb-1">{{ $exchange->responder->username }}</p>
                            <p class="small text-muted mb-1">Item: <em>{{ $exchange->requestedProduct->name }}</em></p>
                            <p class="small text-muted mb-0">
                                Locked: <strong>${{ number_format($ins?->responderLockedAmount() ?? 0, 2) }}</strong>
                                (value ${{ number_format($ins?->resp_item_value ?? 0, 2) }} + $5 fee)
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <p class="small fw-semibold text-muted mb-1">FILED BY: {{ $dispute->filer->username }}</p>
                    <div class="border rounded-2 p-3">
                        <p class="small text-dark mb-0">{{ $dispute->description }}</p>
                    </div>
                </div>

                @if($dispute->evidence_paths && count($dispute->evidence_paths) > 0)
                    <div class="mb-3">
                        <p class="small fw-semibold text-muted mb-2">EVIDENCE</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($dispute->evidence_paths as $path)
                                @php $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); @endphp
                                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                    <a href="{{ Storage::url($path) }}" target="_blank">
                                        <img src="{{ Storage::url($path) }}" class="rounded-2 border"
                                             style="height:80px;width:80px;object-fit:cover;">
                                    </a>
                                @else
                                    <a href="{{ Storage::url($path) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-file-earmark me-1"></i> Document
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($dispute->status === 'pending')
                    <hr class="opacity-10">
                    <p class="small fw-semibold text-muted mb-2">RESOLVE</p>
                    <form method="POST" action="{{ route('admin.disputes.resolve', $dispute) }}">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Decision</label>
                                <select name="resolution" class="form-select form-select-sm" required>
                                    <option value="">— Choose —</option>
                                    <option value="resolved_filer">
                                        Rule for {{ $dispute->filer->username }} (filer gets both locked amounts)
                                    </option>
                                    <option value="resolved_other">
                                        Rule for other party (other party gets both locked amounts)
                                    </option>
                                    <option value="dismissed">
                                        Dismiss — release all funds back to owners
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Admin Notes <span class="text-danger">*</span></label>
                                <input type="text" name="admin_notes" class="form-control form-control-sm"
                                       placeholder="Reason for decision" required minlength="10">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-dark btn-sm w-100">Resolve</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-{{ $color }} bg-opacity-10 py-2 px-3 small mb-0">
                        <i class="bi bi-check2 me-1"></i>
                        <strong>Resolved by {{ $dispute->resolver?->username ?? 'Admin' }}:</strong>
                        {{ $dispute->admin_notes }}
                    </div>
                @endif

            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="text-muted opacity-25 mb-2"><i class="bi bi-shield-check fs-1"></i></div>
            <p class="text-muted small">No disputes to review.</p>
        </div>
    @endforelse

    {{ $disputes->links() }}
</div>
@endsection
