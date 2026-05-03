@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    @foreach([
        ['bi-people-fill',       '#818cf8', 'Total Users',      $stats['users'],          route('admin.users')],
        ['bi-box-seam-fill',     '#34d399', 'Active Listings',  $stats['products'],        route('admin.products')],
        ['bi-arrow-left-right',  '#60a5fa', 'Completed Trades', $stats['trades'],          null],
        ['bi-clock-history',     '#fbbf24', 'Pending Offers',   $stats['pending_offers'],  null],
        ['bi-shield-exclamation','#f87171', 'Open Disputes',    $stats['disputes'],        route('admin.disputes.index')],
        ['bi-lock-fill',         '#a78bfa', 'Escrow Locked',    '$'.number_format($stats['escrow_total'],2), route('admin.finances')],
        ['bi-person-plus-fill',  '#34d399', 'New Users (7d)',   $stats['new_users_week'],  null],
        ['bi-tag-fill',          '#60a5fa', 'New Listings (7d)',$stats['new_listings_week'],null],
    ] as [$icon, $color, $label, $val, $link])
    <div class="col-6 col-md-3">
        @if($link)
        <a href="{{ $link }}" class="text-decoration-none">
        @endif
        <div class="adm-stat-card">
            <i class="bi {{ $icon }} mb-2" style="font-size:1.4rem;color:{{ $color }};"></i>
            <div class="adm-stat-val" style="color:{{ $color }};">{{ $val }}</div>
            <div class="adm-stat-label">{{ $label }}</div>
        </div>
        @if($link)</a>@endif
    </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Recent users --}}
    <div class="col-lg-6">
        <div class="adm-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div style="font-weight:700;font-size:.9rem;">Recent Users</div>
                <a href="{{ route('admin.users') }}" class="adm-btn adm-btn-ghost" style="font-size:.75rem;">View all</a>
            </div>
            <table class="adm-table">
                <thead><tr>
                    <th>User</th><th>Joined</th><th>Status</th>
                </tr></thead>
                <tbody>
                @foreach($recentUsers as $u)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#e2e8f0;">{{ $u->name }}</div>
                        <div style="font-size:.75rem;color:#475569;">{{ $u->email }}</div>
                    </td>
                    <td style="color:#64748b;font-size:.78rem;">{{ $u->created_at->diffForHumans() }}</td>
                    <td>
                        @if($u->status === 'banned')
                            <span class="adm-badge adm-badge-red">Banned</span>
                        @elseif($u->hasRole('admin'))
                            <span class="adm-badge adm-badge-blue">Admin</span>
                        @else
                            <span class="adm-badge adm-badge-green">Active</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent listings --}}
    <div class="col-lg-6">
        <div class="adm-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div style="font-weight:700;font-size:.9rem;">Recent Listings</div>
                <a href="{{ route('admin.products') }}" class="adm-btn adm-btn-ghost" style="font-size:.75rem;">View all</a>
            </div>
            <table class="adm-table">
                <thead><tr>
                    <th>Item</th><th>Seller</th><th>Status</th>
                </tr></thead>
                <tbody>
                @foreach($recentProducts as $p)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#e2e8f0;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->name }}</div>
                        <div style="font-size:.75rem;color:#475569;">{{ ucfirst($p->category) }}</div>
                    </td>
                    <td style="color:#94a3b8;font-size:.82rem;">{{ $p->user->name ?? '—' }}</td>
                    <td>
                        @if($p->hide)
                            <span class="adm-badge adm-badge-red">Hidden</span>
                        @else
                            <span class="adm-badge adm-badge-green">Active</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
