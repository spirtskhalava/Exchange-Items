@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width:640px;">

    <div class="mb-4">
        <h4 class="fw-bold mb-0">My Profile</h4>
        <p class="text-muted small mt-1">Manage your personal information</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 small mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger py-2 px-3 small mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">

            {{-- Avatar --}}
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center fw-bold text-primary"
                     style="width:56px;height:56px;font-size:1.4rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-semibold">{{ $user->name }}</div>
                    <div class="text-muted small">{{ $user->email }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Username</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="form-control @error('name') is-invalid @enderror"
                           required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="form-control @error('email') is-invalid @enderror"
                           required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="form-control @error('phone') is-invalid @enderror"
                           placeholder="Optional">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="my-4 opacity-25">

                <p class="small fw-semibold text-muted mb-3">Change Password <span class="fw-normal">(leave blank to keep current)</span></p>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">New Password</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min 6 characters" autocomplete="new-password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control"
                           placeholder="Repeat new password">
                </div>

                <button type="submit" class="btn btn-dark w-100 py-2">Save Changes</button>
            </form>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mt-3">
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                <div class="fw-bold fs-5">{{ $user->products()->count() }}</div>
                <div class="text-muted small">Listings</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                <div class="fw-bold fs-5">{{ \App\Models\Exchange::where('requester_id', $user->id)->where('status','accepted')->count() }}</div>
                <div class="text-muted small">Trades Done</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                <div class="fw-bold fs-5">{{ number_format($user->reviewsReceived()->avg('rating') ?? 0, 1) }} ★</div>
                <div class="text-muted small">Rating</div>
            </div>
        </div>
    </div>
</div>
@endsection
