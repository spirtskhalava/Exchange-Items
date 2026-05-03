<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Dashboard')</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #0f1117; color: #e2e8f0; font-size: .9rem; margin: 0; }

    /* Sidebar */
    .adm-sidebar {
        position: fixed; top: 0; left: 0; bottom: 0; width: 240px;
        background: #14161e;
        border-right: 1px solid #1e2130;
        display: flex; flex-direction: column;
        z-index: 100;
    }
    .adm-brand {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #1e2130;
        font-weight: 800; font-size: 1rem; letter-spacing: -.4px;
        color: #818cf8;
        display: flex; align-items: center; gap: .5rem;
        text-decoration: none;
    }
    .adm-brand span { color: #e2e8f0; }
    .adm-nav { padding: 1rem .75rem; flex: 1; overflow-y: auto; }
    .adm-nav-label {
        font-size: .65rem; font-weight: 700; color: #475569;
        text-transform: uppercase; letter-spacing: .1em;
        padding: .75rem .75rem .3rem;
    }
    .adm-nav-item {
        display: flex; align-items: center; gap: .65rem;
        padding: .55rem .75rem; border-radius: .5rem;
        color: #94a3b8; text-decoration: none; font-size: .84rem; font-weight: 500;
        transition: all .15s; margin-bottom: .1rem;
    }
    .adm-nav-item:hover { background: #1e2130; color: #e2e8f0; }
    .adm-nav-item.active { background: rgba(129,140,248,.12); color: #818cf8; }
    .adm-nav-item i { font-size: 1rem; width: 20px; text-align: center; }
    .adm-nav-badge {
        margin-left: auto; background: #dc2626; color: #fff;
        font-size: .65rem; font-weight: 700; padding: .15rem .45rem;
        border-radius: 99px; min-width: 18px; text-align: center;
    }

    /* Footer */
    .adm-footer { padding: 1rem 1.5rem; border-top: 1px solid #1e2130; }
    .adm-user { display: flex; align-items: center; gap: .65rem; }
    .adm-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg,#4f46e5,#7c3aed);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .8rem; color: #fff; flex-shrink: 0;
    }
    .adm-user-name { font-size: .82rem; font-weight: 600; color: #e2e8f0; }
    .adm-user-role { font-size: .7rem; color: #818cf8; }

    /* Main content */
    .adm-main { margin-left: 240px; min-height: 100vh; }
    .adm-topbar {
        position: sticky; top: 0; z-index: 50;
        background: rgba(15,17,23,.9); backdrop-filter: blur(12px);
        border-bottom: 1px solid #1e2130;
        padding: .75rem 2rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .adm-topbar-title { font-weight: 700; font-size: 1rem; color: #e2e8f0; }
    .adm-content { padding: 2rem; }

    /* Cards */
    .adm-card {
        background: #14161e; border: 1px solid #1e2130;
        border-radius: .75rem; padding: 1.5rem;
    }
    .adm-stat-card {
        background: #14161e; border: 1px solid #1e2130;
        border-radius: .75rem; padding: 1.25rem 1.5rem;
    }
    .adm-stat-val { font-size: 2rem; font-weight: 800; line-height: 1.1; }
    .adm-stat-label { font-size: .75rem; color: #64748b; font-weight: 500; margin-top: .25rem; }

    /* Table */
    .adm-table { width: 100%; border-collapse: collapse; }
    .adm-table th { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #475569; padding: .6rem 1rem; border-bottom: 1px solid #1e2130; }
    .adm-table td { padding: .75rem 1rem; border-bottom: 1px solid #1a1d27; font-size: .84rem; vertical-align: middle; }
    .adm-table tr:last-child td { border-bottom: none; }
    .adm-table tr:hover td { background: rgba(255,255,255,.02); }

    /* Badges */
    .adm-badge { display: inline-flex; align-items: center; gap: .25rem; font-size: .7rem; font-weight: 700; padding: .2rem .6rem; border-radius: 99px; }
    .adm-badge-green  { background: rgba(16,185,129,.12); color: #10b981; }
    .adm-badge-red    { background: rgba(220,38,38,.12);  color: #ef4444; }
    .adm-badge-yellow { background: rgba(245,158,11,.12); color: #f59e0b; }
    .adm-badge-blue   { background: rgba(99,102,241,.12); color: #818cf8; }
    .adm-badge-gray   { background: rgba(100,116,139,.12);color: #64748b; }

    /* Buttons */
    .adm-btn { display: inline-flex; align-items: center; gap: .3rem; padding: .35rem .8rem; border-radius: .45rem; font-size: .78rem; font-weight: 600; border: none; cursor: pointer; transition: all .15s; }
    .adm-btn-primary { background: #4f46e5; color: #fff; }
    .adm-btn-primary:hover { background: #4338ca; }
    .adm-btn-danger { background: rgba(220,38,38,.1); color: #ef4444; border: 1px solid rgba(220,38,38,.2); }
    .adm-btn-danger:hover { background: rgba(220,38,38,.2); }
    .adm-btn-ghost { background: rgba(255,255,255,.05); color: #94a3b8; border: 1px solid #1e2130; }
    .adm-btn-ghost:hover { background: rgba(255,255,255,.08); color: #e2e8f0; }

    /* Search input */
    .adm-search { background: #1a1d27; border: 1px solid #1e2130; border-radius: .5rem; padding: .45rem .9rem; color: #e2e8f0; font-size: .84rem; outline: none; }
    .adm-search:focus { border-color: #4f46e5; }

    /* Pagination */
    .pagination .page-link { background: #14161e; border-color: #1e2130; color: #94a3b8; }
    .pagination .page-item.active .page-link { background: #4f46e5; border-color: #4f46e5; }
    .pagination .page-link:hover { background: #1e2130; color: #e2e8f0; }

    @media (max-width: 768px) {
        .adm-sidebar { display: none; }
        .adm-main { margin-left: 0; }
    }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<div class="adm-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="adm-brand">
        <i class="bi bi-shield-fill"></i> <span>Bartaro</span> Admin
    </a>

    <nav class="adm-nav">
        <div class="adm-nav-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}"
           class="adm-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="adm-nav-label">Manage</div>
        <a href="{{ route('admin.users') }}"
           class="adm-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
            <span class="adm-nav-badge">{{ \App\Models\User::where('status','banned')->count() ?: '' }}</span>
        </a>
        <a href="{{ route('admin.products') }}"
           class="adm-nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Content
        </a>

        <div class="adm-nav-label">Financial</div>
        <a href="{{ route('admin.finances') }}"
           class="adm-nav-item {{ request()->routeIs('admin.finances') ? 'active' : '' }}">
            <i class="bi bi-paypal"></i> Finances & PayPal
        </a>
        <a href="{{ route('admin.disputes.index') }}"
           class="adm-nav-item {{ request()->routeIs('admin.disputes*') ? 'active' : '' }}">
            <i class="bi bi-shield-exclamation"></i> Disputes
            @php $pending = \App\Models\InsuranceDispute::where('status','pending')->count(); @endphp
            @if($pending)
            <span class="adm-nav-badge">{{ $pending }}</span>
            @endif
        </a>

        <div class="adm-nav-label mt-3">App</div>
        <a href="{{ route('home') }}" class="adm-nav-item">
            <i class="bi bi-arrow-left"></i> Back to Site
        </a>
    </nav>

    <div class="adm-footer">
        <div class="adm-user">
            @if(Auth::user()->avatar_url)
                <img src="{{ Auth::user()->avatar_url }}" class="adm-avatar" style="padding:0;object-fit:cover;">
            @else
                <div class="adm-avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
            @endif
            <div>
                <div class="adm-user-name">{{ Auth::user()->name }}</div>
                <div class="adm-user-role">Administrator</div>
            </div>
        </div>
    </div>
</div>

{{-- Main --}}
<div class="adm-main">
    <div class="adm-topbar">
        <div class="adm-topbar-title">@yield('title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-3">
            @if(session('success'))
            <span style="font-size:.78rem;color:#10b981;"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}</span>
            @endif
            @if(session('error'))
            <span style="font-size:.78rem;color:#ef4444;"><i class="bi bi-x-circle me-1"></i>{{ session('error') }}</span>
            @endif
        </div>
    </div>

    <div class="adm-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
