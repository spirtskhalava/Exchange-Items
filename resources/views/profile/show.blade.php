@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:760px;">

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card text-center p-3">
                <div class="fw-bold" style="font-size:1.6rem;color:var(--primary);">{{ $user->products()->count() }}</div>
                <div class="text-muted small mt-1">Listings</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center p-3">
                <div class="fw-bold" style="font-size:1.6rem;color:#06d6a0;">{{ \App\Models\Exchange::where('requester_id', $user->id)->where('status','accepted')->count() }}</div>
                <div class="text-muted small mt-1">Trades Done</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center p-3">
                <div class="fw-bold" style="font-size:1.6rem;color:#ff9f1c;">
                    {{ number_format($user->reviewsReceived()->avg('rating') ?? 0, 1) }}
                    <i class="bi bi-star-fill" style="font-size:.9rem;"></i>
                </div>
                <div class="text-muted small mt-1">Rating</div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 py-2 px-3 small">
            <i class="bi bi-check-circle-fill text-success"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2 px-3 small mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Profile Card --}}
    <div class="card">
        <div class="card-body p-4">

            {{-- Avatar Row --}}
            <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white flex-shrink-0"
                     style="width:60px;height:60px;font-size:1.5rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.05rem;">{{ $user->name }}</div>
                    <div class="text-muted small">{{ $user->email }}</div>
                    <div class="text-muted" style="font-size:.75rem;">Member since {{ $user->created_at->format('M Y') }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Phone <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="form-control @error('phone') is-invalid @enderror" placeholder="+1 555 000 0000">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="border-top pt-4 mb-3">
                    <p class="small fw-semibold mb-3" style="color:var(--muted);">
                        Change Password
                        <span class="fw-normal">&mdash; leave blank to keep current</span>
                    </p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min 6 characters" autocomplete="new-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                    <i class="bi bi-check2 me-1"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
