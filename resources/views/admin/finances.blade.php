@extends('admin.layout')
@section('title', 'Finances & PayPal')

@section('content')

{{-- Summary stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['bi-lock-fill',       '#f87171', 'Locked in Escrow',    $totals['locked'],    '$'.number_format($totals['amount_locked'],2)],
        ['bi-shield-exclamation','#fbbf24','Under Dispute',       $totals['disputed'],  null],
        ['bi-check2-all',      '#34d399', 'Released',            $totals['released'],  '$'.number_format($totals['amount_released'],2)],
        ['bi-clock-history',   '#60a5fa', 'Awaiting Payment',    $totals['pending'],   null],
        ['bi-cash-coin',       '#a78bfa', 'Fees Collected ($10×)',null,                '$'.number_format($totals['fees_collected'],2)],
    ] as [$icon, $color, $label, $count, $amount])
    <div class="col-6 col-md-{{ $loop->last ? 4 : 3 }}">
        <div class="adm-stat-card">
            <i class="bi {{ $icon }} mb-2" style="font-size:1.3rem;color:{{ $color }};"></i>
            @if($amount)
            <div class="adm-stat-val" style="color:{{ $color }};">{{ $amount }}</div>
            @endif
            @if($count !== null)
            <div style="font-size:1.1rem;font-weight:700;color:#94a3b8;">{{ $count }} records</div>
            @endif
            <div class="adm-stat-label">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- PayPal mode indicator --}}
<div class="adm-card mb-4 d-flex align-items-center gap-3" style="padding:.9rem 1.25rem;">
    <i class="bi bi-paypal" style="font-size:1.5rem;color:#009cde;"></i>
    <div>
        <div style="font-weight:700;font-size:.875rem;">PayPal Integration</div>
        <div style="font-size:.78rem;color:#64748b;">
            Mode: <strong style="color:{{ config('paypal.mode')==='sandbox' ? '#fbbf24' : '#34d399' }}">
                {{ strtoupper(config('paypal.mode')) }}
            </strong>
            &nbsp;·&nbsp;
            Client ID: <code style="font-size:.75rem;color:#94a3b8;">{{ Str::limit(config('paypal.'.config('paypal.mode').'.client_id'), 30) }}</code>
        </div>
    </div>
    @if(config('paypal.mode') === 'sandbox')
    <span class="adm-badge adm-badge-yellow ms-auto">Sandbox — not real money</span>
    @else
    <span class="adm-badge adm-badge-green ms-auto">Live</span>
    @endif
</div>

{{-- Insurance records table --}}
<div class="adm-card p-0">
    <div class="px-4 py-3 border-bottom" style="border-color:#1e2130 !important;">
        <div style="font-weight:700;font-size:.9rem;">Escrow Records</div>
    </div>
    <table class="adm-table">
        <thead><tr>
            <th style="padding-left:1.5rem;">#</th>
            <th>Requester</th>
            <th>Responder</th>
            <th>Items</th>
            <th>Locked (Req)</th>
            <th>Locked (Resp)</th>
            <th>Status</th>
            <th style="padding-right:1.5rem;">PayPal Orders</th>
        </tr></thead>
        <tbody>
        @forelse($insurances as $ins)
        @php
            $ex = $ins->exchange;
            $statusColors = [
                'negotiating'    => 'adm-badge-blue',
                'pending_payment'=> 'adm-badge-yellow',
                'locked'         => 'adm-badge-green',
                'released'       => 'adm-badge-gray',
                'disputed'       => 'adm-badge-red',
            ];
        @endphp
        <tr>
            <td style="padding-left:1.5rem;color:#475569;font-size:.78rem;">#{{ $ins->id }}</td>
            <td>
                <div style="font-weight:600;color:#e2e8f0;font-size:.82rem;">{{ $ex->requester->name ?? '—' }}</div>
                <div style="font-size:.72rem;color:#475569;">{{ $ex->offeredProduct->name ?? 'Cash' }}</div>
            </td>
            <td>
                <div style="font-weight:600;color:#e2e8f0;font-size:.82rem;">{{ $ex->responder->name ?? '—' }}</div>
                <div style="font-size:.72rem;color:#475569;">{{ $ex->requestedProduct->name ?? '—' }}</div>
            </td>
            <td style="font-size:.78rem;color:#94a3b8;">
                @if($ins->req_item_value) ${{ number_format($ins->req_item_value,2) }} @else — @endif
                /
                @if($ins->resp_item_value) ${{ number_format($ins->resp_item_value,2) }} @else — @endif
            </td>
            <td style="font-size:.82rem;color:#34d399;font-weight:600;">${{ number_format($ins->requesterLockedAmount(),2) }}</td>
            <td style="font-size:.82rem;color:#34d399;font-weight:600;">${{ number_format($ins->responderLockedAmount(),2) }}</td>
            <td>
                <span class="adm-badge {{ $statusColors[$ins->escrow_status] ?? 'adm-badge-gray' }}">
                    {{ ucfirst(str_replace('_',' ',$ins->escrow_status)) }}
                </span>
                @if($ins->dispute)
                    <span class="adm-badge adm-badge-red ms-1">Dispute</span>
                @endif
            </td>
            <td style="padding-right:1.5rem;font-size:.72rem;">
                @if($ins->req_paypal_order_id)
                <div style="color:#60a5fa;" title="{{ $ins->req_paypal_order_id }}">
                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    Req: {{ Str::limit($ins->req_paypal_order_id, 12) }}
                </div>
                @endif
                @if($ins->resp_paypal_order_id)
                <div style="color:#60a5fa;" title="{{ $ins->resp_paypal_order_id }}">
                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    Resp: {{ Str::limit($ins->resp_paypal_order_id, 12) }}
                </div>
                @endif
                @if(!$ins->req_paypal_order_id && !$ins->resp_paypal_order_id)
                <span style="color:#475569;">No payments yet</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-4" style="color:#475569;">No escrow records yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($insurances->hasPages())
<div class="mt-3 d-flex justify-content-center">{{ $insurances->links() }}</div>
@endif
@endsection
