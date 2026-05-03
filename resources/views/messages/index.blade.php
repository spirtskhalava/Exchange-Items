@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="msg-shell overflow-hidden">
        <div class="row g-0 h-100">

            {{-- ── Sidebar ──────────────────────────────────────────── --}}
            <div class="col-md-4 col-lg-3 msg-sidebar d-flex flex-column">

                {{-- Header + search --}}
                <div class="p-3 border-bottom">
                    <div class="fw-bold mb-2" style="font-size:.95rem;">Messages</div>
                    <div class="msg-search-wrap">
                        <i class="bi bi-search msg-search-icon"></i>
                        <input type="text" id="userSearch" class="msg-search-input" placeholder="Search users…" autocomplete="off">
                    </div>
                </div>

                {{-- Search results (hidden by default) --}}
                <div id="searchResults" class="border-bottom d-none" style="max-height:220px;overflow-y:auto;"></div>

                {{-- Conversations --}}
                <div class="flex-grow-1 overflow-auto" style="scrollbar-width:thin;" id="contactList">
                    @forelse($contacts as $contact)
                    @php
                        $colors = ['#4f46e5','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
                        $bg = $colors[crc32($contact->name) % count($colors)];
                    @endphp
                    <a href="#"
                       class="msg-contact-item d-flex align-items-center gap-3"
                       data-user-id="{{ $contact->id }}"
                       data-user-name="{{ $contact->name }}">
                        <div class="position-relative flex-shrink-0">
                            @if($contact->avatar_url)
                                <img src="{{ $contact->avatar_url }}" class="rounded-circle object-fit-cover"
                                     style="width:42px;height:42px;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                     style="width:42px;height:42px;font-size:1rem;background:{{ $bg }};">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold text-truncate" style="font-size:.875rem;">{{ $contact->name }}</div>
                            <div class="text-muted text-truncate" style="font-size:.75rem;">Tap to continue chat</div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-5 px-3">
                        <i class="bi bi-chat-dots" style="font-size:2rem;opacity:.2;color:var(--p);display:block;"></i>
                        <p class="text-muted small mt-2 mb-0">No conversations yet.<br>Search for a user to start chatting.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ── Chat pane ────────────────────────────────────────── --}}
            <div class="col-md-8 col-lg-9 msg-pane">
                <div id="chat-windows-container" class="h-100 d-flex flex-column">
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <div class="d-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm"
                             style="width:64px;height:64px;background:#fff;">
                            <i class="bi bi-chat-square-text" style="font-size:1.8rem;color:var(--p);"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Your Messages</h5>
                        <p class="text-muted small mb-0">Select a contact or search for a user to start chatting.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.msg-shell {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    height: 80vh;
    min-height: 520px;
    box-shadow: var(--shadow);
}
.msg-sidebar {
    border-right: 1px solid var(--border);
    background: var(--surface);
}
.msg-pane { background: var(--bg); }

/* Search */
.msg-search-wrap { position: relative; }
.msg-search-icon {
    position: absolute; top: 50%; left: .7rem;
    transform: translateY(-50%);
    color: var(--muted2); font-size: .8rem; pointer-events: none;
}
.msg-search-input {
    width: 100%;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: .6rem;
    padding: .45rem .75rem .45rem 2rem;
    font-size: .84rem;
    color: var(--text);
    outline: none;
    transition: border-color var(--transition);
}
.msg-search-input:focus { border-color: var(--p); background: #fff; }

/* Contact items */
.msg-contact-item {
    display: flex;
    padding: .75rem 1rem;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid var(--border);
    transition: background var(--transition);
    cursor: pointer;
}
.msg-contact-item:hover { background: var(--p-light); }
.msg-contact-item.active { background: var(--p-light); border-left: 3px solid var(--p); }

/* Search result rows */
.search-result-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .65rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid var(--border);
    transition: background var(--transition);
    text-decoration: none;
    color: inherit;
}
.search-result-item:hover { background: var(--p-light); }
.search-result-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    var searchInput   = document.getElementById('userSearch');
    var searchResults = document.getElementById('searchResults');
    var searchTimer   = null;
    var searchUrl     = '{{ route("messages.searchUsers") }}';

    function avatarHtml(u, size) {
        size = size || 34;
        var colors = ['#4f46e5','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
        var bg     = colors[Math.abs(hashCode(u.name)) % colors.length];
        if (u.avatar_url) {
            return '<img src="'+u.avatar_url+'" class="rounded-circle object-fit-cover" style="width:'+size+'px;height:'+size+'px;">';
        }
        return '<div class="search-result-avatar" style="background:'+bg+';width:'+size+'px;height:'+size+'px;">'+u.initials+'</div>';
    }

    function hashCode(str) {
        var h = 0;
        for (var i = 0; i < str.length; i++) { h = (Math.imul(31, h) + str.charCodeAt(i)) | 0; }
        return h;
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var q = this.value.trim();
        if (!q) { searchResults.classList.add('d-none'); searchResults.innerHTML = ''; return; }
        searchTimer = setTimeout(function () {
            fetch(searchUrl + '?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r){ return r.json(); })
            .then(function (users) {
                if (!users.length) {
                    searchResults.innerHTML = '<div class="px-3 py-3 text-muted small">No users found.</div>';
                } else {
                    searchResults.innerHTML = users.map(function (u) {
                        return '<div class="search-result-item" data-user-id="'+u.id+'" data-user-name="'+u.name+'">'
                            + avatarHtml(u)
                            + '<span class="fw-semibold" style="font-size:.875rem;">'+u.name+'</span>'
                            + '</div>';
                    }).join('');
                    searchResults.querySelectorAll('.search-result-item').forEach(function (el) {
                        el.addEventListener('click', function () {
                            openChat(this.dataset.userId, this.dataset.userName);
                            searchInput.value = '';
                            searchResults.classList.add('d-none');
                            searchResults.innerHTML = '';
                        });
                    });
                }
                searchResults.classList.remove('d-none');
            });
        }, 250);
    });

    document.addEventListener('click', function (e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.classList.add('d-none');
        }
    });

    // Wire up existing contacts
    document.querySelectorAll('.msg-contact-item').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('.msg-contact-item').forEach(function(x){ x.classList.remove('active'); });
            this.classList.add('active');
            openChat(this.dataset.userId, this.dataset.userName);
        });
    });

    function openChat(userId, userName) {
        if (typeof createChatWindow === 'function') {
            createChatWindow(userId, userName);
        }
    }

    // Auto-open from URL params (e.g. from seller page)
    var params  = new URLSearchParams(window.location.search);
    var chatId  = params.get('chat_id');
    var sellerName = params.get('seller_name');
    if (chatId && sellerName) {
        var sellerId = chatId.split('_')[0];
        openChat(sellerId, sellerName);
        var item = document.querySelector('[data-user-id="'+sellerId+'"]');
        if (item) item.classList.add('active');
    }
})();
</script>
@endpush
@endsection
