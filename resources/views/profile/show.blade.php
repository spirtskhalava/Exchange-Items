@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:760px;">

    {{-- Stats Row --}}
    @php
        $tradeCount = $user->completedTradesCount();
        $avgRating  = round($user->reviewsReceived()->avg('rating') ?? 0, 1);
        $isVerified = $user->isVerifiedTrader();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card text-center p-3">
                <div class="fw-bold" style="font-size:1.6rem;color:var(--p);">{{ $user->products()->count() }}</div>
                <div class="text-muted small mt-1">Listings</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center p-3">
                <a href="{{ route('trades.index') }}" class="text-decoration-none">
                    <div class="fw-bold" style="font-size:1.6rem;color:#059669;">{{ $tradeCount }}</div>
                    <div class="text-muted small mt-1">Trades Done</div>
                </a>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center p-3">
                <div class="fw-bold" style="font-size:1.6rem;color:#d97706;">
                    {{ $avgRating }}<i class="bi bi-star-fill" style="font-size:.9rem;"></i>
                </div>
                <div class="text-muted small mt-1">Rating</div>
            </div>
        </div>
    </div>

    {{-- Verified progress (only when not yet verified) --}}
    @if(!$isVerified)
    <div class="card mb-4 p-3" style="border-left:4px solid var(--p);">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-patch-check" style="font-size:1.6rem;color:var(--p);flex-shrink:0;"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold mb-1" style="font-size:.875rem;">Path to Verified Trader</div>
                <div class="d-flex gap-4">
                    <div>
                        <div class="text-muted" style="font-size:.72rem;">Trades</div>
                        <div style="font-size:.82rem;font-weight:600;color:{{ $tradeCount >= 10 ? '#059669' : 'var(--text)' }};">
                            {{ $tradeCount }}/10 @if($tradeCount >= 10)<i class="bi bi-check-circle-fill text-success"></i>@endif
                        </div>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:.72rem;">Rating</div>
                        <div style="font-size:.82rem;font-weight:600;color:{{ $avgRating >= 4.5 ? '#059669' : 'var(--text)' }};">
                            {{ $avgRating }}★ / 4.5★ @if($avgRating >= 4.5)<i class="bi bi-check-circle-fill text-success"></i>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card mb-4 p-3 text-center" style="background:linear-gradient(135deg,#eef2ff,#ede9fe);border:1px solid #c7d2fe;">
        <div class="verified-badge-md mx-auto mb-1" style="width:fit-content;">
            <i class="bi bi-patch-check-fill"></i> Verified Trader
        </div>
        <div class="text-muted" style="font-size:.78rem;">You've completed 10+ trades with a 4.5★+ rating.</div>
    </div>
    @endif

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
            <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">

                {{-- Avatar display + overlay --}}
                <div class="avatar-edit-wrap flex-shrink-0" style="position:relative;width:72px;height:72px;">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" id="avatarPreview"
                             class="rounded-circle object-fit-cover"
                             style="width:72px;height:72px;border:3px solid var(--border);">
                    @else
                        @php
                            $colors = ['#4f46e5','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
                            $bg = $colors[crc32($user->name) % count($colors)];
                        @endphp
                        <div id="avatarPreview" class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                             style="width:72px;height:72px;font-size:1.7rem;background:{{ $bg }};border:3px solid var(--border);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <label for="avatarInput" class="avatar-edit-btn" title="Change photo">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-bold d-flex align-items-center gap-2" style="font-size:1.05rem;">
                        {{ $user->name }}
                        @include('_partials.verified-badge', ['user' => $user])
                    </div>
                    <div class="text-muted small">{{ $user->email }}</div>
                    <div class="text-muted" style="font-size:.75rem;">Member since {{ $user->created_at->format('M Y') }}</div>
                    @if($user->avatar_url)
                        <form method="POST" action="{{ route('profile.avatar.remove') }}" class="d-inline mt-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger" style="font-size:.75rem;">
                                <i class="bi bi-trash3 me-1"></i>Remove photo
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Hidden avatar input --}}
                <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">

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

@push('styles')
<style>
.avatar-edit-wrap { cursor: pointer; }
.avatar-edit-btn {
    position: absolute;
    bottom: 0; right: 0;
    width: 26px; height: 26px;
    background: var(--p);
    border: 2px solid #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: .65rem;
    cursor: pointer;
    transition: background var(--transition);
}
.avatar-edit-btn:hover { background: var(--p-dark); }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('avatarInput').addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        var preview = document.getElementById('avatarPreview');
        // Replace whatever element is there with an img
        var img = document.createElement('img');
        img.src = e.target.result;
        img.id = 'avatarPreview';
        img.className = 'rounded-circle object-fit-cover';
        img.style.cssText = 'width:72px;height:72px;border:3px solid var(--border);';
        preview.replaceWith(img);
    };
    reader.readAsDataURL(file);
    // Auto-submit the form
    this.closest('form').submit();
});
</script>
@endpush
