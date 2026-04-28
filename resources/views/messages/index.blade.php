@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card overflow-hidden" style="height:80vh;min-height:520px;">
        <div class="row g-0 h-100">

            {{-- Contacts sidebar --}}
            <div class="col-md-4 col-lg-3 border-end h-100 d-flex flex-column bg-white">
                <div class="p-3 border-bottom">
                    <h6 class="fw-bold mb-0">Messages</h6>
                </div>
                <div class="flex-grow-1 overflow-auto" style="scrollbar-width:thin;">
                    <div class="list-group list-group-flush" id="user-list">
                        @forelse($users as $user)
                        <a href="#"
                           class="list-group-item list-group-item-action user-list-item d-flex align-items-center gap-3 py-3"
                           data-user-id="{{ $user->id }}"
                           data-user-name="{{ $user->name }}"
                           style="border:none;border-bottom:1px solid var(--border);transition:background .12s;">
                            <div class="position-relative flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                     style="width:40px;height:40px;font-size:.9rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="position-absolute border border-white rounded-circle bg-success"
                                      style="width:10px;height:10px;bottom:0;right:0;"></span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size:.875rem;">{{ $user->name }}</div>
                                <div class="text-muted text-truncate" style="font-size:.75rem;">Click to chat</div>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size:2rem;opacity:.3;"></i>
                            <p class="text-muted small mt-2">No contacts yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Chat area --}}
            <div class="col-md-8 col-lg-9 h-100" style="background:var(--bg);">
                <div id="chat-windows-container" class="h-100 d-flex flex-column">
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <div class="d-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm"
                             style="width:64px;height:64px;background:#fff;">
                            <i class="bi bi-chat-square-text text-primary" style="font-size:1.8rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Your Messages</h5>
                        <p class="text-muted small mb-0">Select a contact to start chatting</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    .user-list-item.active-user {
        background: rgba(67,97,238,.06);
        border-left: 3px solid var(--primary) !important;
    }
    .user-list-item:hover { background: rgba(67,97,238,.03); }
</style>
@endpush
@endsection
