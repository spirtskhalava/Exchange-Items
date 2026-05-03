@extends('admin.layout')
@section('title', 'Users')

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="adm-card mb-3 d-flex align-items-center gap-2" style="border-left:3px solid #34d399;padding:.75rem 1rem;">
    <i class="bi bi-check-circle-fill" style="color:#34d399;"></i>
    <span style="color:#e2e8f0;font-size:.85rem;">{!! session('success') !!}</span>
</div>
@endif
@if(session('error'))
<div class="adm-card mb-3 d-flex align-items-center gap-2" style="border-left:3px solid #ef4444;padding:.75rem 1rem;">
    <i class="bi bi-x-circle-fill" style="color:#ef4444;"></i>
    <span style="color:#e2e8f0;font-size:.85rem;">{{ session('error') }}</span>
</div>
@endif

{{-- Toolbar --}}
<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <form method="GET" class="d-flex gap-2 flex-grow-1">
        <input name="q" value="{{ request('q') }}" class="adm-search flex-grow-1" placeholder="Search by name or email…">
        <select name="role" class="adm-search" style="width:auto;" onchange="this.form.submit()">
            <option value="">All roles</option>
            <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admins only</option>
        </select>
        <button type="submit" class="adm-btn adm-btn-primary"><i class="bi bi-search"></i></button>
    </form>
    <span style="color:#475569;font-size:.8rem;">{{ $users->total() }} users</span>
</div>

{{-- Table --}}
<div class="adm-card p-0">
    <table class="adm-table">
        <thead><tr>
            <th style="padding-left:1.5rem;">User</th>
            <th>Joined</th>
            <th>Products</th>
            <th>Role</th>
            <th>Status</th>
            <th style="padding-right:1.5rem;text-align:right;">Actions</th>
        </tr></thead>
        <tbody>
        @forelse($users as $user)
        <tr>
            <td style="padding-left:1.5rem;">
                <div class="d-flex align-items-center gap-2">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;flex-shrink:0;">
                    @else
                    @php $c = ['#4f46e5','#0ea5e9','#10b981','#f59e0b','#ef4444'][ crc32($user->name) % 5 ]; @endphp
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                         style="width:32px;height:32px;font-size:.8rem;background:{{ $c }};">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    @endif
                    <div>
                        <div style="font-weight:600;color:#e2e8f0;">{{ $user->name }}</div>
                        <div style="font-size:.73rem;color:#475569;">{{ $user->email }}</div>
                    </div>
                </div>
            </td>
            <td style="color:#64748b;font-size:.78rem;">{{ $user->created_at->format('d M Y') }}</td>
            <td style="color:#94a3b8;">{{ $user->products_count ?? 0 }}</td>
            <td>
                @if($user->hasRole('admin'))
                    <span class="adm-badge adm-badge-blue"><i class="bi bi-shield-fill"></i> Admin</span>
                @else
                    <span class="adm-badge adm-badge-gray">User</span>
                @endif
            </td>
            <td>
                @if($user->status === 'banned')
                    <span class="adm-badge adm-badge-red"><i class="bi bi-slash-circle"></i> Banned</span>
                @else
                    <span class="adm-badge adm-badge-green"><i class="bi bi-check-circle"></i> Active</span>
                @endif
            </td>
            <td style="padding-right:1.5rem;">
                <div class="d-flex gap-1 justify-content-end flex-wrap">
                    {{-- View --}}
                    <a href="{{ route('seller.items', $user->id) }}" target="_blank"
                       class="adm-btn adm-btn-ghost" title="View profile">
                        <i class="bi bi-eye"></i>
                    </a>

                    {{-- Edit --}}
                    <button class="adm-btn adm-btn-ghost" title="Edit user"
                            onclick="openEditUser({{ $user->id }}, {{ json_encode($user->name) }}, {{ json_encode($user->email) }}, {{ json_encode($user->phone ?? '') }}, '{{ $user->status }}')">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    @if($user->id !== Auth::id())
                    {{-- Ban / Unban --}}
                    <form method="POST" action="{{ route('admin.users.toggleStatus', $user) }}" class="d-inline">
                        @csrf
                        <button class="adm-btn {{ $user->status==='banned' ? 'adm-btn-primary' : 'adm-btn-danger' }}"
                                title="{{ $user->status==='banned' ? 'Unban' : 'Ban' }}">
                            <i class="bi {{ $user->status==='banned' ? 'bi-unlock' : 'bi-slash-circle' }}"></i>
                        </button>
                    </form>
                    {{-- Admin toggle --}}
                    <form method="POST" action="{{ route('admin.users.toggleAdmin', $user) }}" class="d-inline">
                        @csrf
                        <button class="adm-btn adm-btn-ghost" title="{{ $user->hasRole('admin') ? 'Remove admin' : 'Make admin' }}">
                            <i class="bi bi-shield{{ $user->hasRole('admin') ? '-minus' : '-plus' }}"></i>
                        </button>
                    </form>
                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="d-inline"
                          onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This is irreversible.')">
                        @csrf @method('DELETE')
                        <button class="adm-btn adm-btn-danger" title="Delete user">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4" style="color:#475569;">No users found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages())
<div class="mt-3 d-flex justify-content-center">{{ $users->links() }}</div>
@endif


{{-- ─── Edit User Modal ──────────────────────────────────────────── --}}
<div id="editUserModal" style="
    display:none;position:fixed;inset:0;z-index:9999;
    background:rgba(0,0,0,.65);backdrop-filter:blur(4px);
    align-items:center;justify-content:center;">
    <div style="background:#14161e;border:1px solid #1e2130;border-radius:.9rem;
                width:100%;max-width:480px;padding:2rem;position:relative;margin:1rem;">

        {{-- Close --}}
        <button onclick="closeEditUser()" style="position:absolute;top:1rem;right:1rem;
            background:none;border:none;color:#475569;font-size:1.2rem;cursor:pointer;line-height:1;">
            <i class="bi bi-x-lg"></i>
        </button>

        <h5 style="color:#e2e8f0;font-weight:700;margin-bottom:1.5rem;">
            <i class="bi bi-pencil-square me-2" style="color:#818cf8;"></i>Edit User
        </h5>

        <form id="editUserForm" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                    Full Name <span style="color:#ef4444;">*</span>
                </label>
                <input id="eu_name" type="text" name="name" required maxlength="100"
                       class="adm-search w-100" placeholder="Full name">
            </div>

            <div class="mb-3">
                <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                    Email <span style="color:#ef4444;">*</span>
                </label>
                <input id="eu_email" type="email" name="email" required maxlength="150"
                       class="adm-search w-100" placeholder="email@example.com">
            </div>

            <div class="mb-3">
                <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                    Phone
                </label>
                <input id="eu_phone" type="text" name="phone" maxlength="30"
                       class="adm-search w-100" placeholder="+1 555 000 0000">
            </div>

            <div class="mb-4">
                <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                    Account Status
                </label>
                <select id="eu_status" name="status" class="adm-search w-100">
                    <option value="active">Active</option>
                    <option value="banned">Banned</option>
                </select>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <button type="button" onclick="closeEditUser()" class="adm-btn adm-btn-ghost">Cancel</button>
                <button type="submit" class="adm-btn adm-btn-primary">
                    <i class="bi bi-check2 me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditUser(id, name, email, phone, status) {
    const base = "{{ rtrim(route('admin.users.update', ['user' => '__ID__']), '') }}";
    document.getElementById('editUserForm').action = base.replace('__ID__', id);
    document.getElementById('eu_name').value   = name;
    document.getElementById('eu_email').value  = email;
    document.getElementById('eu_phone').value  = phone || '';
    document.getElementById('eu_status').value = status;
    const modal = document.getElementById('editUserModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeEditUser() {
    document.getElementById('editUserModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('editUserModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditUser();
});
</script>

@endsection
