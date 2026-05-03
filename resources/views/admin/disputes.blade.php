@extends('admin.layout')
@section('title', 'Disputes')

@section('content')

@forelse($disputes as $dispute)
@php
    $exchange = $dispute->exchange;
    $ins      = $exchange->insurance;
    $colors   = ['pending'=>'adm-badge-yellow','resolved_filer'=>'adm-badge-green','resolved_other'=>'adm-badge-blue','dismissed'=>'adm-badge-gray'];
    $badge    = $colors[$dispute->status] ?? 'adm-badge-gray';
@endphp
<div class="adm-card mb-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <span class="adm-badge {{ $badge }}">{{ ucfirst(str_replace('_',' ',$dispute->status)) }}</span>
            <span style="color:#475569;font-size:.78rem;">Dispute #{{ $dispute->id }} &middot; {{ $dispute->created_at->format('d M Y, H:i') }}</span>
        </div>
        @if($dispute->status === 'pending')
        <span class="adm-badge adm-badge-red"><i class="bi bi-clock me-1"></i>Needs Review</span>
        @endif
    </div>

    {{-- Parties --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div style="background:#1a1d27;border-radius:.6rem;padding:1rem;">
                <div style="font-size:.65rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">Requester</div>
                <div style="font-weight:700;color:#e2e8f0;margin-bottom:.2rem;">{{ $exchange->requester->name ?? '—' }}</div>
                <div style="font-size:.78rem;color:#64748b;">Item: <em>{{ $exchange->offeredProduct->name ?? 'Cash' }}</em></div>
                <div style="font-size:.78rem;color:#34d399;margin-top:.3rem;">
                    Locked: <strong>${{ number_format($ins?->requesterLockedAmount() ?? 0, 2) }}</strong>
                    <span style="color:#475569;">(item ${{ number_format($ins?->req_item_value??0,2) }} + $5 fee)</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background:#1a1d27;border-radius:.6rem;padding:1rem;">
                <div style="font-size:.65rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">Responder</div>
                <div style="font-weight:700;color:#e2e8f0;margin-bottom:.2rem;">{{ $exchange->responder->name ?? '—' }}</div>
                <div style="font-size:.78rem;color:#64748b;">Item: <em>{{ $exchange->requestedProduct->name ?? '—' }}</em></div>
                <div style="font-size:.78rem;color:#34d399;margin-top:.3rem;">
                    Locked: <strong>${{ number_format($ins?->responderLockedAmount() ?? 0, 2) }}</strong>
                    <span style="color:#475569;">(item ${{ number_format($ins?->resp_item_value??0,2) }} + $5 fee)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filed by + description --}}
    <div class="mb-4">
        <div style="font-size:.65rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">
            Filed by: <span style="color:#818cf8;">{{ $dispute->filer->name ?? '—' }}</span>
        </div>
        <div style="background:#1a1d27;border-radius:.6rem;padding:1rem;font-size:.84rem;color:#94a3b8;line-height:1.7;">
            {{ $dispute->description }}
        </div>
    </div>

    {{-- Evidence --}}
    @if($dispute->evidence_paths && count($dispute->evidence_paths) > 0)
    <div class="mb-4">
        <div style="font-size:.65rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">Evidence</div>
        <div class="d-flex flex-wrap gap-2">
            @foreach($dispute->evidence_paths as $path)
            @php $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); @endphp
            @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                <a href="{{ Storage::url($path) }}" target="_blank">
                    <img src="{{ Storage::url($path) }}" class="rounded" style="height:72px;width:72px;object-fit:cover;border:1px solid #1e2130;">
                </a>
            @else
                <a href="{{ Storage::url($path) }}" target="_blank" class="adm-btn adm-btn-ghost">
                    <i class="bi bi-file-earmark me-1"></i>Document
                </a>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Resolve form --}}
    @if($dispute->status === 'pending')
    <div style="border-top:1px solid #1e2130;padding-top:1.25rem;">
        <div style="font-size:.65rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;">Admin Decision</div>
        <form method="POST" action="{{ route('admin.disputes.resolve', $dispute) }}">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">Decision</label>
                    <select name="resolution" class="adm-search w-100" required>
                        <option value="">— Choose outcome —</option>
                        <option value="resolved_filer">Rule FOR {{ $dispute->filer->name ?? 'filer' }}</option>
                        <option value="resolved_other">Rule AGAINST filer (other party wins)</option>
                        <option value="dismissed">Dismiss — release funds to both</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">Admin Notes <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="admin_notes" class="adm-search w-100"
                           placeholder="Reason for your decision (min 10 chars)" required minlength="10">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="adm-btn adm-btn-primary w-100" style="padding:.55rem;">
                        <i class="bi bi-check2 me-1"></i> Resolve
                    </button>
                </div>
            </div>
        </form>
    </div>
    @else
    <div style="border-top:1px solid #1e2130;padding-top:1rem;font-size:.84rem;">
        <span style="color:#64748b;">Resolved by {{ $dispute->resolver?->name ?? 'Admin' }}:</span>
        <span style="color:#94a3b8;margin-left:.5rem;">{{ $dispute->admin_notes }}</span>
    </div>
    @endif
</div>
@empty
<div class="adm-card text-center py-5">
    <i class="bi bi-shield-check" style="font-size:2.5rem;color:#1e2130;display:block;margin-bottom:1rem;"></i>
    <div style="color:#475569;">No disputes to review. All clear.</div>
</div>
@endforelse

@if($disputes->hasPages())
<div class="mt-3 d-flex justify-content-center">{{ $disputes->links() }}</div>
@endif
@endsection
