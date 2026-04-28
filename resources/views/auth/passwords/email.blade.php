<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password — Bartaro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; }
        .auth-wrapper { width:100%;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem; }
        .auth-card { background:#fff;border-radius:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,.1);width:100%;max-width:440px;padding:2.5rem; }
        .brand-logo { font-size:1.3rem;font-weight:800;color:#1a1a2e;letter-spacing:-.5px;display:flex;align-items:center;gap:.5rem;text-decoration:none;justify-content:center;margin-bottom:2rem; }
        .brand-logo i { color:#4361ee; }
        .form-label { font-size:.82rem;font-weight:600;color:#374151;margin-bottom:.4rem; }
        .form-control { border:1.5px solid #e5e7eb;border-radius:.75rem;padding:.7rem 1rem;font-size:.9rem;transition:all .2s;background:#f9fafb; }
        .form-control:focus { border-color:#4361ee;background:#fff;box-shadow:0 0 0 3px rgba(67,97,238,.12); }
        .btn-auth { background:linear-gradient(135deg,#4361ee,#3a0ca3);border:none;border-radius:.75rem;padding:.75rem;font-weight:600;font-size:.95rem;color:#fff;transition:all .2s;letter-spacing:.3px;width:100%; }
        .btn-auth:hover { background:linear-gradient(135deg,#3a0ca3,#4361ee);transform:translateY(-1px);box-shadow:0 6px 20px rgba(67,97,238,.35);color:#fff; }
        .auth-footer { margin-top:1.5rem;text-align:center;font-size:.875rem;color:#6b7280; }
        .auth-footer a { color:#4361ee;font-weight:600;text-decoration:none; }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <a href="{{ url('/') }}" class="brand-logo">
            <i class="bi bi-box-seam-fill"></i> Bartaro
        </a>

        <div class="text-center mb-4">
            <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                 style="width:52px;height:52px;background:rgba(67,97,238,.08);">
                <i class="bi bi-key text-primary" style="font-size:1.3rem;"></i>
            </div>
            <h1 style="font-size:1.35rem;font-weight:700;color:#1a1a2e;margin-bottom:.35rem;">Forgot password?</h1>
            <p style="color:#6b7280;font-size:.875rem;">No worries. Enter your email and we'll send you a reset link.</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4 py-2 px-3 small" style="border-radius:.75rem;border:none;">
                <i class="bi bi-check-circle-fill text-success"></i> {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4 py-2 px-3 small" style="border-radius:.75rem;border:none;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="you@example.com" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-auth">
                Send Reset Link <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="auth-footer">
            <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
        </div>
    </div>
</div>
</body>
</html>
