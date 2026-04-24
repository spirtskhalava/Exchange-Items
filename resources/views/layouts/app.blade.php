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
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar Container */
        .navbar {
            background-color: #ffffff !important;
            backdrop-filter: blur(10px);
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }

        .navbar-brand {
            font-weight: 800;
            color: #4361ee !important;
            letter-spacing: -0.5px;
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Navigation Links */
        .nav-link {
            font-weight: 500;
            color: #64748b !important;
            font-size: 0.95rem;
            padding: 0.6rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: #4361ee !important;
            background-color: rgba(67, 97, 238, 0.05);
        }

        .nav-link.active {
            color: #4361ee !important;
            font-weight: 600;
        }

        .nav-icon {
            font-size: 1.1rem;
            line-height: 1;
            display: flex;
        }

        /* Dropdown Styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 1rem;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #4361ee;
        }
        
        .btn-auth {
            padding: 0.5rem 1.5rem;
            border-radius: 50rem;
            font-weight: 600;
        }

        #notifDropdown::after { display: none !important; }

        /* Footer Specific Styling */
        .footer_section {
            background-color: #ffffff;
            border-top: 1px solid #e9ecef;
        }
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
                <p class="mb-2 text-muted small">
                    &copy; <span id="displayYear"></span> All Rights Reserved 
                </p>
                <!-- Include Vite Assets here as requested -->
                @vite('resources/js/app.js')
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

    @stack('scripts')
</body>
</html>