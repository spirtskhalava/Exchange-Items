<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="current-user" content="{{ Auth::id() }}">

    <title>Bartaro</title>

    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles: Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --bg: #f5f6fa;
            --border: #e9ecef;
            --text: #1a1a2e;
            --muted: #6b7280;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: var(--bg); color: var(--text); }

        /* Navbar */
        .navbar { background: rgba(255,255,255,.97) !important; backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: .7rem 0; }
        .navbar-brand { font-weight: 800; color: var(--primary) !important; letter-spacing: -.5px; font-size: 1.3rem; gap: .4rem; }
        .nav-link { font-weight: 500; color: var(--muted) !important; font-size: .88rem; padding: .45rem .8rem !important; border-radius: .55rem; transition: all .15s; display: flex; align-items: center; gap: .4rem; }
        .nav-link:hover { color: var(--primary) !important; background: rgba(67,97,238,.07); }
        .nav-icon { font-size: 1rem; line-height: 1; }

        /* Dropdown */
        .dropdown-menu { border: 1px solid var(--border) !important; box-shadow: 0 12px 32px rgba(0,0,0,.08) !important; border-radius: .85rem !important; padding: .4rem !important; margin-top: .4rem !important; }
        .dropdown-item { border-radius: .5rem; padding: .55rem .9rem; font-size: .875rem; font-weight: 500; color: #374151; display: flex; align-items: center; gap: .5rem; transition: background .15s; }
        .dropdown-item:hover { background: #f1f5f9 !important; color: var(--primary); }

        /* Cards */
        .card { border: 1px solid var(--border) !important; border-radius: .9rem !important; box-shadow: 0 1px 6px rgba(0,0,0,.04) !important; transition: box-shadow .2s, transform .2s; }

        /* Buttons */
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; border-radius: .65rem; font-weight: 600; transition: all .18s; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--primary-dark), var(--primary)); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(67,97,238,.3); }
        .btn-outline-primary { border-radius: .65rem; font-weight: 600; border-width: 1.5px; }
        .btn-dark { border-radius: .65rem; font-weight: 600; background: #1a1a2e; border-color: #1a1a2e; }
        .btn-dark:hover { background: #111; border-color: #111; }
        .btn-success { border-radius: .65rem; font-weight: 600; }
        .btn-danger { border-radius: .65rem; font-weight: 600; }

        /* Forms */
        .form-control, .form-select { border: 1.5px solid var(--border); border-radius: .65rem; background: #f9fafb; font-size: .9rem; transition: border-color .18s, box-shadow .18s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(67,97,238,.1); }

        /* Pagination */
        .pagination .page-link { border-radius: .55rem !important; margin: 0 2px; border-color: var(--border); color: var(--muted); font-weight: 500; font-size: .875rem; }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

        /* Auth nav button */
        .btn-auth { padding: .45rem 1.2rem; border-radius: 50rem; font-weight: 600; font-size: .875rem; }

        /* Alerts */
        .alert { border-radius: .75rem; border: none; }

        /* Badges */
        .badge { font-weight: 500; }

        /* Footer */
        .footer_section { background: #fff; border-top: 1px solid var(--border); }

        #notifDropdown::after, .notif-toggle::after { display: none !important; }

        /* Product cards */
        .product-card-item:hover { box-shadow: 0 8px 24px rgba(67,97,238,.12) !important; transform: translateY(-3px); }
        .product-card-item:hover .product-card-img { transform: scale(1.04); }

        /* Section titles */
        .section-title { font-size: 1.25rem; font-weight: 700; color: var(--text); }

        /* Nav active link */
        .nav-link.active-page { color: var(--primary) !important; background: rgba(67,97,238,.07); }
    </style>
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        @include('layouts._verification_popup')
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-box-seam-fill"></i>
                    Bartaro
                </a>
                
                <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="bi bi-house-door nav-icon"></i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="bi bi-grid nav-icon"></i>
                                <span>Products</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('offers.index') }}">
                                    <i class="bi bi-arrow-left-right nav-icon text-primary opacity-75"></i>
                                    <span>My Offers</span>
                                    @if($pendingOffersCount > 0)
                                        <span class="badge rounded-pill bg-danger ms-1" style="font-size:0.7rem;">
                                            {{ $pendingOffersCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('listings.index') }}">
                                    <i class="bi bi-tag nav-icon text-primary opacity-75"></i>
                                    <span>My Listings</span>
                                </a>
                            </li>
                            
                            <li class="nav-item d-none d-lg-block mx-2 border-end opacity-50" style="height: 24px;"></li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('wishlist.index') }}">
                                    <i class="bi bi-heart nav-icon text-danger opacity-75"></i>
                                    <span>Wishlist</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('messages.index') }}">
                                    <i class="bi bi-chat-dots nav-icon text-success opacity-75"></i>
                                    <span>Messages</span>
                                    <span id="unread-badge"
                                          class="badge rounded-pill bg-danger ms-1 {{ $unreadCount > 0 ? '' : 'd-none' }}"
                                          style="font-size:0.7rem;">
                                        {{ $unreadCount }}
                                    </span>
                                </a>
                            </li>

                            <!-- Notification Bell -->
                            <li class="nav-item" style="position:relative;">
                                <a class="nav-link" href="#" id="bellBtn">
                                    <span style="position:relative;display:inline-block;line-height:1;">
                                        <i class="bi bi-bell fs-5 text-warning opacity-75"></i>
                                        @if($unreadNotifications > 0)
                                            <span style="position:absolute;top:-4px;right:-8px;background:#dc3545;color:#fff;border-radius:50rem;font-size:0.55rem;min-width:16px;padding:2px 4px;text-align:center;line-height:1.4;">{{ $unreadNotifications }}</span>
                                        @endif
                                    </span>
                                </a>
                                <div id="notifPanel" style="display:none;position:absolute;right:0;top:110%;width:320px;max-height:420px;overflow-y:auto;background:#fff;border:1px solid rgba(0,0,0,.1);border-radius:.75rem;box-shadow:0 10px 30px rgba(0,0,0,.1);z-index:9999;">
                                    <div style="padding:.6rem 1rem;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;">
                                        <span style="font-weight:600;font-size:.875rem;">Notifications</span>
                                        @if($unreadNotifications > 0)
                                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                                @csrf
                                                <button style="background:none;border:none;color:#6c757d;font-size:.78rem;cursor:pointer;padding:0;">Mark all read</button>
                                            </form>
                                        @endif
                                    </div>
                                    @forelse(Auth::user()->unreadNotifications()->latest()->take(10)->get() as $notif)
                                        <a href="{{ $notif->data['url'] ?? route('offers.index') }}"
                                           onclick="fetch('{{ route('notifications.read', $notif->id) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});"
                                           style="display:block;padding:.6rem 1rem;border-bottom:1px solid #f5f5f5;text-decoration:none;color:inherit;">
                                            <div style="display:flex;gap:.6rem;align-items:flex-start;">
                                                @if(($notif->data['status'] ?? '') === 'accepted')
                                                    <i class="bi bi-check-circle-fill text-success" style="margin-top:2px;flex-shrink:0;"></i>
                                                @elseif(($notif->data['status'] ?? '') === 'declined')
                                                    <i class="bi bi-x-circle-fill text-danger" style="margin-top:2px;flex-shrink:0;"></i>
                                                @else
                                                    <i class="bi bi-arrow-left-right text-primary" style="margin-top:2px;flex-shrink:0;"></i>
                                                @endif
                                                <div>
                                                    <div style="font-size:.85rem;">{{ $notif->data['message'] ?? '' }}</div>
                                                    <div style="font-size:.75rem;color:#6c757d;">{{ $notif->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div style="padding:2rem 1rem;text-align:center;color:#aaa;font-size:.85rem;">
                                            <i class="bi bi-bell-slash d-block mb-1" style="font-size:1.5rem;opacity:.3;"></i>
                                            No new notifications
                                        </div>
                                    @endforelse
                                </div>
                            </li>
                        @endauth

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item ms-lg-2">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item ms-lg-2">
                                    <a class="btn btn-primary btn-auth shadow-sm text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown ms-lg-3">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold border" style="width: 32px; height: 32px;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <div class="dropdown-header small text-uppercase fw-bold text-muted">Account</div>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="bi bi-person"></i> My Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <!-- 
            STICKY FOOTER
            mt-auto pushes it to the bottom of the flex container 
        -->
        <footer class="footer_section mt-auto py-4">
            <div class="container text-center">
                <p class="mb-0 text-muted small">
                    &copy; <span id="displayYear"></span> Bartaro &mdash; All Rights Reserved
                </p>
            </div>
        </footer>
    </div>

    <script>
        // 1. Tooltips Initialization
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // 2. Set Footer Year
            var currentDate = new Date();
            var yearElement = document.getElementById("displayYear");
            if(yearElement) {
                yearElement.innerHTML = currentDate.getFullYear();
            }
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var panel = document.getElementById('notifPanel');
            var btn   = document.getElementById('bellBtn');
            if (!btn || !panel) return;

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
            });

            document.addEventListener('click', function (e) {
                if (!panel.contains(e.target) && !btn.contains(e.target)) {
                    panel.style.display = 'none';
                }
            });
        });
    </script>

    {{-- Global wishlist toggle --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
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
                icon.classList.toggle('text-secondary', filled);
                fetch('/wishlist/' + id, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({})
                }).then(r => { if (r.status === 401) window.location.href = '/login'; return r.json(); })
                  .then(d => {
                      icon.classList.toggle('fas', d.status === 'added');
                      icon.classList.toggle('far', d.status !== 'added');
                      icon.classList.toggle('text-danger', d.status === 'added');
                      icon.classList.toggle('text-secondary', d.status !== 'added');
                  }).catch(() => {
                      icon.classList.toggle('fas', filled);
                      icon.classList.toggle('far', !filled);
                      icon.classList.toggle('text-danger', filled);
                      icon.classList.toggle('text-secondary', !filled);
                  });
            });
        });
    });
    </script>

    @stack('scripts')
</body>
</html>