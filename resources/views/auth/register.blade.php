<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account — Bartaro</title>
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
            width: 100%; max-width: 960px;
            display: flex; min-height: 620px;
        }
        .auth-brand {
            width: 42%;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            padding: 3rem;
            display: flex; flex-direction: column; justify-content: space-between;
            position: relative; overflow: hidden;
        }
        .auth-brand::before {
            content: ''; position: absolute;
            width: 300px; height: 300px; border-radius: 50%;
            background: rgba(255,255,255,0.03);
            top: -80px; right: -80px;
        }
        .auth-brand::after {
            content: ''; position: absolute;
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
            bottom: -60px; left: -60px;
        }
        .brand-logo { font-size: 1.6rem; font-weight: 800; color: #fff; letter-spacing: -0.5px; display: flex; align-items: center; gap: .6rem; }
        .brand-logo i { color: #4cc9f0; }
        .brand-tagline { color: rgba(255,255,255,0.9); margin-top: 2.5rem; }
        .brand-tagline h2 { font-size: 1.8rem; font-weight: 700; line-height: 1.3; }
        .brand-tagline p { color: rgba(255,255,255,0.55); font-size: .9rem; margin-top: .75rem; }
        .steps { margin-top: 2rem; }
        .step { display: flex; align-items: flex-start; gap: .75rem; margin-bottom: 1.2rem; }
        .step-num { width: 28px; height: 28px; border-radius: 50%; background: rgba(76,201,240,.15); border: 1px solid rgba(76,201,240,.3); color: #4cc9f0; font-size: .78rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .step-text { color: rgba(255,255,255,0.7); font-size: .85rem; padding-top: 4px; }
        .brand-footer { color: rgba(255,255,255,0.3); font-size: .75rem; }

        .auth-form { flex: 1; padding: 3rem; display: flex; flex-direction: column; justify-content: center; overflow-y: auto; }
        .auth-form h1 { font-size: 1.6rem; font-weight: 700; color: #1a1a2e; margin-bottom: .4rem; }
        .auth-form .subtitle { color: #6b7280; font-size: .9rem; margin-bottom: 1.5rem; }

        .form-label { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
        .form-control {
            border: 1.5px solid #e5e7eb; border-radius: .75rem;
            padding: .65rem 1rem; font-size: .9rem;
            transition: all .2s; background: #f9fafb;
        }
        .form-control:focus {
            border-color: #4361ee; background: #fff;
            box-shadow: 0 0 0 3px rgba(67,97,238,.12);
        }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { font-size: .78rem; }
        .btn-auth {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border: none; border-radius: .75rem; padding: .75rem;
            font-weight: 600; font-size: .95rem; color: #fff;
            transition: all .2s; letter-spacing: .3px;
        }
        .btn-auth:hover {
            background: linear-gradient(135deg, #3a0ca3, #4361ee);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(67,97,238,.35); color: #fff;
        }
        .auth-footer { margin-top: 1.5rem; text-align: center; font-size: .875rem; color: #6b7280; }
        .auth-footer a { color: #4361ee; font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }

        @media (max-width: 767px) {
            .auth-brand { display: none; }
            .auth-card { max-width: 480px; }
            .auth-form { padding: 2rem; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-brand">
            <div class="brand-logo">
                <i class="bi bi-box-seam-fill"></i> Bartaro
            </div>
            <div class="brand-tagline">
                <h2>Join thousands of traders today.</h2>
                <p>Create your free account and start exchanging in minutes.</p>
                <div class="steps">
                    <div class="step">
                        <div class="step-num">1</div>
                        <div class="step-text">Create your free account</div>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <div class="step-text">List items you want to trade</div>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <div class="step-text">Make offers & complete trades safely</div>
                    </div>
                </div>
            </div>
            <div class="brand-footer">© {{ date('Y') }} Bartaro. All rights reserved.</div>
        </div>

        <div class="auth-form">
            <h1>Create account</h1>
            <p class="subtitle">Join Bartaro — it's free and takes 30 seconds</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">Username</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="johndoe" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="you@example.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="form-control @error('phone') is-invalid @enderror"
                           placeholder="+1 555 000 0000">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min 6 chars" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label">Confirm</label>
                        <input type="password" name="password_confirmation"
                               class="form-control" placeholder="Repeat" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-auth w-100">
                    Create Account <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
