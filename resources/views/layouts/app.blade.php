<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KGFDWT9L');</script>
    <!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="current-user" content="{{ Auth::id() }}">

    {{-- ── SEO: Title & Description ───────────────────────── --}}
    @php
        $metaTitle       = trim($__env->yieldContent('meta_title'))       ?: 'Bartaro — Trade Smarter';
        $metaDescription = trim($__env->yieldContent('meta_description')) ?: 'Bartaro is the modern platform for safe, free item exchanges. Trade what you have for what you want — no cash needed.';
        $metaImage       = trim($__env->yieldContent('meta_image'))       ?: asset('favicon.svg');
        $metaCanonical   = trim($__env->yieldContent('meta_canonical'))   ?: url()->current();
        $metaType        = trim($__env->yieldContent('meta_type'))        ?: 'website';
    @endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ $metaCanonical }}">

    {{-- ── Open Graph ──────────────────────────────────────── --}}
    <meta property="og:type"        content="{{ $metaType }}">
    <meta property="og:title"       content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url"         content="{{ $metaCanonical }}">
    <meta property="og:image"       content="{{ $metaImage }}">
    <meta property="og:site_name"   content="Bartaro">

    {{-- ── Twitter Card ───────────────────────────────────── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image"       content="{{ $metaImage }}">

    {{-- ── Structured data (JSON-LD) injected per-page ─────── --}}
    @stack('structured_data')

    {{-- ── PWA / Icons ────────────────────────────────────── --}}
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Bartaro">

    {{-- Preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    {{-- Google Fonts: async (non-blocking) --}}
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    </noscript>

    {{-- Bootstrap CSS — self-hosted, served by Nginx with 1y cache --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- Bootstrap Icons — self-hosted, font-display:swap, async --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}"
          media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}"></noscript>

    {{-- JS: self-hosted, defer --}}
    <script defer src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
    /* ─── Design Tokens ────────────────────────────────────── */
    :root {
        --p:        #4f46e5;
        --p-dark:   #3730a3;
        --p-light:  #eef2ff;
        --p-ring:   rgba(79,70,229,.18);
        --success:  #059669;
        --danger:   #dc2626;
        --warning:  #d97706;
        --bg:       #f8f9fc;
        --surface:  #ffffff;
        --border:   #e5e7eb;
        --border2:  #d1d5db;
        --text:     #111827;
        --text2:    #374151;
        --muted:    #6b7280;
        --muted2:   #9ca3af;
        --radius:   .75rem;
        --radius-lg:1.1rem;
        --shadow-sm:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
        --shadow:   0 4px 12px rgba(0,0,0,.07),0 1px 4px rgba(0,0,0,.04);
        --shadow-lg:0 12px 28px rgba(0,0,0,.09),0 4px 12px rgba(0,0,0,.05);
        --transition:.18s cubic-bezier(.4,0,.2,1);
    }

    /* ─── Base ─────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: var(--bg);
        color: var(--text);
        font-size: .9375rem;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }

    /* ─── Scrollbar ─────────────────────────────────────────── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--muted2); }

    /* ─── Navbar ─────────────────────────────────────────────── */
    .navbar {
        background: rgba(255,255,255,.92) !important;
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border-bottom: 1px solid var(--border) !important;
        padding: .6rem 0;
        box-shadow: none !important;
    }
    .navbar-brand {
        font-weight: 800;
        font-size: 1.25rem;
        letter-spacing: -.6px;
        color: var(--p) !important;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .navbar-brand i { font-size: 1.2rem; }

    .nav-link {
        font-size: .84rem;
        font-weight: 500;
        color: var(--muted) !important;
        padding: .4rem .75rem !important;
        border-radius: .55rem;
        transition: all var(--transition);
        display: flex;
        align-items: center;
        gap: .35rem;
        white-space: nowrap;
    }
    .nav-link:hover, .nav-link.active { color: var(--p) !important; background: var(--p-light); }
    .nav-link .bi { font-size: .95rem; }

    /* User avatar in nav */
    .nav-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--p), var(--p-dark));
        color: #fff;
        font-weight: 700;
        font-size: .78rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ─── Dropdown ───────────────────────────────────────────── */
    .dropdown-menu {
        border: 1px solid var(--border) !important;
        box-shadow: var(--shadow-lg) !important;
        border-radius: var(--radius-lg) !important;
        padding: .35rem !important;
        margin-top: .5rem !important;
        min-width: 190px !important;
    }
    .dropdown-item {
        border-radius: .55rem;
        padding: .5rem .85rem;
        font-size: .845rem;
        font-weight: 500;
        color: var(--text2);
        display: flex;
        align-items: center;
        gap: .5rem;
        transition: background var(--transition);
    }
    .dropdown-item:hover { background: var(--p-light) !important; color: var(--p); }
    .dropdown-item.text-danger:hover { background: #fef2f2 !important; color: var(--danger); }
    .dropdown-header { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: .4rem .85rem .2rem; color: var(--muted2); }
    .dropdown-divider { margin: .3rem 0; border-color: var(--border); }

    /* ─── Cards ──────────────────────────────────────────────── */
    .card {
        background: var(--surface);
        border: 1px solid var(--border) !important;
        border-radius: var(--radius-lg) !important;
        box-shadow: var(--shadow-sm) !important;
        transition: box-shadow var(--transition), transform var(--transition);
    }

    /* ─── Buttons ────────────────────────────────────────────── */
    .btn { font-weight: 600; font-size: .855rem; border-radius: var(--radius); transition: all var(--transition); }
    .btn-sm { font-size: .8rem; border-radius: .55rem; }
    .btn-lg { font-size: .95rem; border-radius: .85rem; padding: .7rem 1.5rem; }

    .btn-primary {
        background: linear-gradient(135deg, var(--p) 0%, var(--p-dark) 100%);
        border: none;
        color: #fff;
        box-shadow: 0 2px 8px rgba(79,70,229,.3);
    }
    .btn-primary:hover, .btn-primary:focus {
        background: linear-gradient(135deg, var(--p-dark) 0%, #2e27a0 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79,70,229,.35);
        color: #fff;
    }
    .btn-primary:active { transform: translateY(0); }

    .btn-outline-primary {
        border: 1.5px solid var(--p);
        color: var(--p);
        background: transparent;
    }
    .btn-outline-primary:hover {
        background: var(--p-light);
        border-color: var(--p);
        color: var(--p);
        transform: translateY(-1px);
    }

    .btn-dark {
        background: var(--text);
        border-color: var(--text);
        color: #fff;
    }
    .btn-dark:hover { background: #000; border-color: #000; transform: translateY(-1px); }

    .btn-light {
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--text2);
    }
    .btn-light:hover { background: #eef2ff; border-color: #c7d2fe; color: var(--p); }

    .btn-ghost {
        background: transparent;
        border: none;
        color: var(--muted);
        padding: .4rem .75rem;
    }
    .btn-ghost:hover { background: var(--bg); color: var(--text2); }

    /* ─── Forms ──────────────────────────────────────────────── */
    .form-label { font-size: .8rem; font-weight: 600; color: var(--text2); margin-bottom: .35rem; letter-spacing: .01em; }
    .form-control, .form-select {
        border: 1.5px solid var(--border);
        border-radius: var(--radius);
        background: var(--surface);
        font-size: .875rem;
        color: var(--text);
        padding: .55rem .85rem;
        transition: border-color var(--transition), box-shadow var(--transition);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--p);
        box-shadow: 0 0 0 3px var(--p-ring);
        background: var(--surface);
        outline: none;
    }
    .form-control.is-invalid { border-color: var(--danger); }
    .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.15); }
    .invalid-feedback { font-size: .775rem; color: var(--danger); }

    /* ─── Badges ─────────────────────────────────────────────── */
    .badge { font-weight: 600; font-size: .72rem; letter-spacing: .02em; border-radius: .4rem; padding: .28em .6em; }
    .badge-pill { border-radius: 99px; }

    /* ─── Alerts ─────────────────────────────────────────────── */
    .alert { border-radius: var(--radius); border: none; font-size: .875rem; }
    .alert-success { background: #ecfdf5; color: #065f46; }
    .alert-danger  { background: #fef2f2; color: #991b1b; }

    /* ─── Pagination ─────────────────────────────────────────── */
    .pagination { gap: 3px; }
    .pagination .page-link {
        border-radius: .55rem !important;
        border: 1px solid var(--border);
        color: var(--muted);
        font-weight: 500;
        font-size: .83rem;
        padding: .42rem .75rem;
        transition: all var(--transition);
        background: var(--surface);
        line-height: 1.4;
    }
    .pagination .page-link:hover { background: var(--p-light); border-color: #c7d2fe; color: var(--p); }
    .pagination .page-item.active .page-link {
        background: var(--p);
        border-color: var(--p);
        color: #fff;
        box-shadow: 0 2px 8px rgba(79,70,229,.3);
    }
    .pagination .page-item.disabled .page-link { background: var(--bg); color: var(--muted2); }

    /* ─── Tabs ───────────────────────────────────────────────── */
    .nav-tabs { border-bottom: 2px solid var(--border); gap: .25rem; }
    .nav-tabs .nav-link { border: none; color: var(--muted); font-weight: 500; font-size: .875rem; padding: .5rem 1rem; border-radius: .55rem .55rem 0 0; }
    .nav-tabs .nav-link:hover { color: var(--p); background: var(--p-light); }
    .nav-tabs .nav-link.active { color: var(--p); background: transparent; border-bottom: 2px solid var(--p); margin-bottom: -2px; }

    /* ─── Notification Bell Panel ────────────────────────────── */
    #notifPanel {
        display: none;
        position: absolute;
        right: 0; top: calc(100% + .5rem);
        width: 320px; max-height: 430px;
        overflow-y: auto;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        z-index: 9999;
    }

    /* ─── Product card ───────────────────────────────────────── */
    .pcard { transition: box-shadow var(--transition), transform var(--transition); cursor: default; }
    .pcard:hover { box-shadow: var(--shadow-lg) !important; transform: translateY(-4px); }
    .pcard:hover .pcard-img { transform: scale(1.06); }
    .pcard-img { transition: transform .35s cubic-bezier(.4,0,.2,1); }

    /* ─── Misc utilities ─────────────────────────────────────── */
    .section-label { font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); }
    .text-p { color: var(--p) !important; }
    .bg-p-light { background: var(--p-light) !important; }
    .divider { height: 24px; width: 1px; background: var(--border); margin: 0 .5rem; }
    .border-dashed { border-style: dashed !important; }
    img { max-width: 100%; }

    /* ─── Footer ─────────────────────────────────────────────── */
    footer { background: var(--surface); border-top: 1px solid var(--border); }

    /* ─── Verified Trader Badge ──────────────────────────────── */
    .verified-badge-sm {
        display: inline-flex; align-items: center;
        color: #4f46e5; font-size: .75rem; cursor: default;
    }
    .verified-badge-sm i { font-size: .85rem; }
    .verified-badge-md {
        display: inline-flex; align-items: center; gap: .3rem;
        background: linear-gradient(135deg,#4f46e5,#7c3aed);
        color: #fff; font-size: .72rem; font-weight: 700;
        padding: .3rem .7rem; border-radius: 99px;
        letter-spacing: .02em;
    }
    .verified-badge-md i { font-size: .85rem; }
    </style>

    @stack('styles')
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KGFDWT9L"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="app" class="d-flex flex-column min-vh-100">

    @include('layouts._verification_popup')

    {{-- ══ NAVBAR ══════════════════════════════════════════════ --}}
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-box-seam-fill"></i> Bartaro
            </a>

            <button class="navbar-toggler border-0 shadow-none p-1" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navMain"
                    aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                {{-- Left --}}
                <ul class="navbar-nav me-auto gap-1 mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-grid-3x3-gap"></i> Browse
                        </a>
                    </li>
                </ul>

                {{-- Right --}}
                <ul class="navbar-nav align-items-lg-center gap-1 mt-2 mt-lg-0">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('offers.*') ? 'active' : '' }}" href="{{ route('offers.index') }}">
                                <i class="bi bi-arrow-left-right"></i>
                                Offers
                                @if($pendingOffersCount > 0)
                                    <span class="badge rounded-pill ms-1" style="background:var(--danger);color:#fff;font-size:.65rem;">{{ $pendingOffersCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('listings.*') ? 'active' : '' }}" href="{{ route('listings.index') }}">
                                <i class="bi bi-tag"></i> Listings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('wishlist.*') ? 'active' : '' }}" href="{{ route('wishlist.index') }}">
                                <i class="bi bi-heart"></i> Wishlist
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}" href="{{ route('messages.index') }}">
                                <i class="bi bi-chat-dots"></i>
                                Messages
                                @if($unreadCount > 0)
                                    <span class="badge rounded-pill ms-1" id="unread-badge" style="background:var(--success);color:#fff;font-size:.65rem;">{{ $unreadCount }}</span>
                                @else
                                    <span class="badge rounded-pill ms-1 d-none" id="unread-badge" style="background:var(--success);color:#fff;font-size:.65rem;"></span>
                                @endif
                            </a>
                        </li>

                        {{-- Bell --}}
                        <li class="nav-item" style="position:relative;">
                            <a class="nav-link" href="#" id="bellBtn" aria-label="Notifications">
                                <span style="position:relative;display:inline-flex;">
                                    <i class="bi bi-bell" style="font-size:1.05rem;"></i>
                                    @if($unreadNotifications > 0)
                                        <span style="position:absolute;top:-5px;right:-7px;background:var(--danger);color:#fff;border-radius:99px;font-size:.58rem;min-width:15px;height:15px;display:flex;align-items:center;justify-content:center;padding:0 3px;font-weight:700;border:1.5px solid #fff;">{{ $unreadNotifications }}</span>
                                    @endif
                                </span>
                            </a>

                            {{-- Notification panel --}}
                            <div id="notifPanel">
                                <div style="padding:.65rem 1rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
                                    <span style="font-weight:700;font-size:.825rem;color:var(--text);">Notifications</span>
                                    @if($unreadNotifications > 0)
                                        <form method="POST" action="{{ route('notifications.readAll') }}">
                                            @csrf
                                            <button style="background:none;border:none;color:var(--p);font-size:.75rem;font-weight:600;cursor:pointer;padding:0;">Mark all read</button>
                                        </form>
                                    @endif
                                </div>
                                @forelse(Auth::user()->unreadNotifications()->latest()->take(10)->get() as $notif)
                                    <a href="{{ $notif->data['url'] ?? route('offers.index') }}"
                                       onclick="fetch('{{ route('notifications.read', $notif->id) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})"
                                       style="display:block;padding:.65rem 1rem;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .12s;"
                                       onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                                        <div style="display:flex;gap:.6rem;align-items:flex-start;">
                                            @if(($notif->data['status'] ?? '') === 'accepted')
                                                <span style="width:28px;height:28px;border-radius:.45rem;background:#ecfdf5;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;"><i class="bi bi-check-lg" style="color:var(--success);font-size:.8rem;"></i></span>
                                            @elseif(($notif->data['status'] ?? '') === 'declined')
                                                <span style="width:28px;height:28px;border-radius:.45rem;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;"><i class="bi bi-x-lg" style="color:var(--danger);font-size:.75rem;"></i></span>
                                            @else
                                                <span style="width:28px;height:28px;border-radius:.45rem;background:var(--p-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;"><i class="bi bi-arrow-left-right" style="color:var(--p);font-size:.75rem;"></i></span>
                                            @endif
                                            <div style="min-width:0;">
                                                <div style="font-size:.82rem;color:var(--text2);line-height:1.4;">{{ $notif->data['message'] ?? '' }}</div>
                                                <div style="font-size:.72rem;color:var(--muted2);margin-top:2px;">{{ $notif->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div style="padding:2.5rem 1rem;text-align:center;">
                                        <i class="bi bi-bell-slash" style="font-size:1.8rem;color:var(--muted2);opacity:.5;display:block;margin-bottom:.5rem;"></i>
                                        <span style="font-size:.82rem;color:var(--muted);">All caught up!</span>
                                    </div>
                                @endforelse
                            </div>
                        </li>

                        {{-- Divider --}}
                        <li class="nav-item d-none d-lg-flex align-items-center">
                            <div class="divider"></div>
                        </li>

                        {{-- User dropdown --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 pe-1" href="#"
                               id="userDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" class="nav-avatar" style="object-fit:cover;padding:0;">
                                @else
                                <div class="nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                @endif
                                <span class="d-none d-lg-inline fw-semibold" style="font-size:.84rem;max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDrop">
                                <li><span class="dropdown-header">Account</span></li>
                                @if(Auth::user()->hasRole('admin'))
                                <li>
                                    <a class="dropdown-item fw-semibold" href="{{ route('admin.dashboard') }}" style="color:#4f46e5;">
                                        <i class="bi bi-shield-fill"></i> Admin Panel
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person-circle"></i> My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('trades.index') }}"><i class="bi bi-clock-history"></i> Trade History</a></li>
                                <li><a class="dropdown-item" href="{{ route('saved-searches.index') }}"><i class="bi bi-bookmark-heart"></i> Saved Searches</a></li>
                                <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Log out
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Log in</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary px-4" style="border-radius:99px;font-size:.84rem;" href="{{ route('register') }}">Sign up free</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- ══ MAIN ═════════════════════════════════════════════════ --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- ══ FOOTER ═══════════════════════════════════════════════ --}}
    <footer class="py-5 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center gap-2 fw-800" style="color:var(--p);font-weight:800;font-size:1.1rem;letter-spacing:-.4px;">
                        <i class="bi bi-box-seam-fill"></i> Bartaro
                    </a>
                    <p class="text-muted small mt-2 mb-0">The modern platform for item exchanges.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="d-flex justify-content-md-center gap-4">
                        <a href="{{ route('products.index') }}" class="text-muted text-decoration-none small fw-500">Browse</a>
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none small fw-500">Home</a>
                        <a href="{{ route('trade.protection') }}" class="text-muted text-decoration-none small fw-500">Trade Protection</a>
                        <a href="{{ route('terms') }}" class="text-muted text-decoration-none small fw-500">Terms</a>
                        @auth
                        <a href="{{ route('products.create') }}" class="text-muted text-decoration-none small fw-500">Sell</a>
                        @endauth
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="text-muted small mb-0">&copy; <span id="displayYear"></span> Bartaro. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Footer year
    var el = document.getElementById('displayYear');
    if (el) el.textContent = new Date().getFullYear();

    // Tooltips
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      .forEach(function(el){ new bootstrap.Tooltip(el); });

    // Notification bell
    var panel = document.getElementById('notifPanel');
    var bell  = document.getElementById('bellBtn');
    if (bell && panel) {
        bell.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        });
        document.addEventListener('click', function(e) {
            if (panel && !panel.contains(e.target) && !bell.contains(e.target)) {
                panel.style.display = 'none';
            }
        });
    }

    // Global wishlist toggle
    document.querySelectorAll('.toggle-wishlist').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var id   = this.getAttribute('data-id');
            var icon = this.querySelector('.wishlist-icon');
            if (!icon) return;
            var filled = icon.classList.contains('fas');
            icon.classList.toggle('fas', !filled);
            icon.classList.toggle('far', filled);
            icon.classList.toggle('text-danger', !filled);
            icon.classList.toggle('text-muted', filled);
            fetch('/wishlist/' + id, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: '{}'
            })
            .then(r => { if (r.status === 401) { window.location.href = '/login'; return null; } return r.json(); })
            .then(d => {
                if (!d) return;
                icon.classList.toggle('fas', d.status === 'added');
                icon.classList.toggle('far', d.status !== 'added');
                icon.classList.toggle('text-danger', d.status === 'added');
                icon.classList.toggle('text-muted', d.status !== 'added');
            });
        });
    });
});
</script>

@auth
<script src="{{ asset('js/chat.js') }}"></script>
@endauth
<script>
/* script.js — image modal helper */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.clickable-image').forEach(function (image) {
        image.addEventListener('click', function () {
            var el = document.getElementById('modal-image');
            if (el) el.src = this.src;
        });
    });
});
</script>

@stack('scripts')

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}
</script>
</body>
</html>
