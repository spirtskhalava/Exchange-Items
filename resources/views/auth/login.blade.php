@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0">{{ __('Login') }}</h4>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                            <label for="email">{{ __('Email Address') }}</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                            <label for="password">{{ __('Password') }}</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg btn-gradient">
                                {{ __('Login') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center py-3">
                    <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">{{ __('Sign Up') }}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: #f8f9fa;
    }
    .btn-gradient {
        background: linear-gradient(45deg, #007bff, #1e90ff);
        border: none;
    }
    .btn-gradient:hover {
        background: linear-gradient(45deg, #1e90ff, #007bff);
    }
    .card {
        border: none;
        border-radius: 1rem;
    }
    .card-header {
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }
    .card-footer {
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }
    .form-floating>.form-control:focus~label {
        color: #007bff;
    }
</style>
@endsection