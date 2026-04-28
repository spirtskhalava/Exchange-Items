<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — Bartaro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; }

        .auth-wrapper {
            width: 100%; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: #fff;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 960px;
            display: flex;
            min-height: 560px;
        }

        /* Left panel */
        .auth-brand {
            width: 42%;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .auth-brand::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
            top: -80px; right: -80px;
        }
        .auth-brand::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            bottom: -60px; left: -60px;
        }
        .brand-logo {
            font-size: 1.6rem; font-weight: 800;
            color: #fff; letter-spacing: -0.5px;
            display: flex; align-items: center; gap: .6rem;
        }
        .brand-logo i { color: #4cc9f0; }
        .brand-tagline { color: rgba(255,255,255,0.9); margin-top: 2.5rem; }
        .brand-tagline h2 { font-size: 1.8rem; font-weight: 700; line-height: 1.3; }
        .brand-tagline p { color: rgba(255,255,255,0.55); font-size: .9rem; margin-top: .75rem; }
        .brand-features { list-style: none; padding: 0; margin-top: 2rem; }
        .brand-features li {
            color: rgba(255,255,255,0.7);
            font-size: .85rem; margin-bottom: .6rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .brand-features li i { color: #4cc9f0; font-size: .9rem; }
        .brand-footer { color: rgba(255,255,255,0.3); font-size: .75rem; }

        /* Right panel */
        .auth-form { flex: 1; padding: 3rem; display: flex; flex-direction: column; justify-content: center; }
        .auth-form h1 { font-size: 1.6rem; font-weight: 700; color: #1a1a2e; margin-bottom: .4rem; }
        .auth-form .subtitle { color: #6b7280; font-size: .9rem; margin-bottom: 2rem; }

        .form-label { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: .75rem;
            padding: .7rem 1rem;
            font-size: .9rem;
            transition: all .2s;
            background: #f9fafb;
        }
        .form-control:focus {
            border-color: #4361ee;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(67,97,238,.12);
        }
        .input-group .form-control { border-radius: .75rem !important; }
        .btn-auth {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border: none; border-radius: .75rem;
            padding: .75rem; font-weight: 600;
            font-size: .95rem; color: #fff;
            transition: all .2s; letter-spacing: .3px;
        }
        .btn-auth:hover {
            background: linear-gradient(135deg, #3a0ca3, #4361ee);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(67,97,238,.35);
            color: #fff;
        }
        .divider { position: relative; text-align: center; margin: 1.5rem 0; }
        .divider::before {
            content: ''; position: absolute;
            top: 50%; left: 0; right: 0; height: 1px;
            background: #e5e7eb;
        }
        .divider span {
            background: #fff; padding: 0 .75rem;
            color: #9ca3af; font-size: .8rem;
            position: relative;
        }
        .auth-footer { margin-top: 1.5rem; text-align: center; font-size: .875rem; color: #6b7280; }
        .auth-footer a { color: #4361ee; font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }

        @media (max-width: 767px) {
            .auth-brand { display: none; }
            .auth-card { max-width: 440px; }
            .auth-form { padding: 2rem; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">

        {{-- Left brand panel --}}
        <div class="auth-brand">
            <div class="brand-logo">
                <i class="bi bi-box-seam-fill"></i> Bartaro
            </div>
            <div class="brand-tagline">
                <h2>Trade smarter,<br>live better.</h2>
                <p>The modern platform for item exchanges. Safe, simple, and smart.</p>
                <ul class="brand-features">
                    <li><i class="bi bi-shield-check-fill"></i> Escrow-protected trades</li>
                    <li><i class="bi bi-arrow-left-right"></i> Item-for-item exchanges</li>
                    <li><i class="bi bi-star-fill"></i> Verified community reviews</li>
                </ul>
            </div>
            <div class="brand-footer">© {{ date('Y') }} Bartaro. All rights reserved.</div>
        </div>

        {{-- Right form panel --}}
        <div class="auth-form">
            <h1>Welcome back</h1>
            <p class="subtitle">Sign in to your account to continue</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="you@example.com" required autofocus>
                </div>

                <div class="mb-1">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="••••••••" required>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-end mb-3">
                        <a href="{{ route('password.request') }}" class="small" style="color:#4361ee;text-decoration:none;">Forgot password?</a>
                    </div>
                @else
                    <div class="mb-3"></div>
                @endif

                <button type="submit" class="btn btn-auth w-100">
                    Sign In <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
