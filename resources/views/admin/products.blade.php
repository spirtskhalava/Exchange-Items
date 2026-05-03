@extends('admin.layout')
@section('title', 'Content Moderation')

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
    <form method="GET" class="d-flex gap-2 flex-grow-1 flex-wrap">
        <input name="q" value="{{ request('q') }}" class="adm-search flex-grow-1" placeholder="Search listings…">
        <select name="status" class="adm-search" style="width:auto;" onchange="this.form.submit()">
            <option value="">All status</option>
            <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
            <option value="hidden" {{ request('status')==='hidden'?'selected':'' }}>Hidden</option>
        </select>
        <select name="category" class="adm-search" style="width:auto;" onchange="this.form.submit()">
            <option value="">All categories</option>
            @foreach(['electronics','gaming','fashion','furniture','books','sports','mobiles','home-garden','clothing','tools','art','beauty','pets','music','vehicles','baby','office','toys'] as $cat)
                <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>
                    {{ ucfirst(str_replace('-',' ',$cat)) }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="adm-btn adm-btn-primary"><i class="bi bi-search"></i></button>
    </form>
    <span style="color:#475569;font-size:.8rem;">{{ $products->total() }} listings</span>
</div>

{{-- Table --}}
<div class="adm-card p-0">
    <table class="adm-table">
        <thead><tr>
            <th style="padding-left:1.5rem;">Item</th>
            <th>Seller</th>
            <th>Category</th>
            <th>Condition</th>
            <th>Views</th>
            <th>Status</th>
            <th style="padding-right:1.5rem;text-align:right;">Actions</th>
        </tr></thead>
        <tbody>
        @forelse($products as $product)
        @php
            $paths = !empty($product->image_paths) ? json_decode($product->image_paths, true) : null;
            $thumb = (is_array($paths) && !empty($paths[0]) && !str_starts_with($paths[0],'http'))
                ? asset('storage/'.$paths[0]) : null;
        @endphp
        <tr>
            <td style="padding-left:1.5rem;">
                <div class="d-flex align-items-center gap-2">
                    @if($thumb)
                        <img src="{{ $thumb }}" class="rounded" style="width:36px;height:36px;object-fit:cover;flex-shrink:0;">
                    @else
                        <div class="rounded d-flex align-items-center justify-content-center"
                             style="width:36px;height:36px;background:#1e2130;flex-shrink:0;">
                            <i class="bi bi-image" style="color:#475569;font-size:.8rem;"></i>
                        </div>
                    @endif
                    <div>
                        <div style="font-weight:600;color:#e2e8f0;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                             title="{{ $product->name }}">{{ $product->name }}</div>
                        <div style="font-size:.73rem;color:#475569;">{{ $product->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </td>
            <td style="color:#94a3b8;font-size:.82rem;">{{ $product->user->name ?? '—' }}</td>
            <td><span class="adm-badge adm-badge-blue">{{ ucfirst(str_replace('-',' ',$product->category)) }}</span></td>
            <td style="color:#94a3b8;font-size:.82rem;">{{ $product->condition }}</td>
            <td style="color:#64748b;font-size:.82rem;"><i class="bi bi-eye me-1"></i>{{ number_format($product->views) }}</td>
            <td>
                @if($product->hide)
                    <span class="adm-badge adm-badge-red">Hidden</span>
                @else
                    <span class="adm-badge adm-badge-green">Active</span>
                @endif
            </td>
            <td style="padding-right:1.5rem;">
                <div class="d-flex gap-1 justify-content-end">
                    {{-- View --}}
                    <a href="{{ route('products.show', $product->id) }}" target="_blank"
                       class="adm-btn adm-btn-ghost" title="View listing"><i class="bi bi-eye"></i></a>

                    {{-- Edit --}}
                    <button class="adm-btn adm-btn-ghost" title="Edit listing"
                            onclick="openEditProduct(
                                {{ $product->id }},
                                {{ json_encode($product->name) }},
                                {{ json_encode($product->description ?? '') }},
                                {{ json_encode($product->category) }},
                                {{ json_encode($product->condition) }},
                                {{ $product->hide ? 1 : 0 }}
                            )">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    {{-- Hide / Show --}}
                    <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="d-inline">
                        @csrf
                        <button class="adm-btn {{ $product->hide ? 'adm-btn-primary' : 'adm-btn-ghost' }}"
                                title="{{ $product->hide ? 'Make visible' : 'Hide' }}">
                            <i class="bi bi-{{ $product->hide ? 'eye' : 'eye-slash' }}"></i>
                        </button>
                    </form>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="d-inline"
                          onsubmit="return confirm('Delete this listing permanently?')">
                        @csrf @method('DELETE')
                        <button class="adm-btn adm-btn-danger" title="Delete"><i class="bi bi-trash3"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center py-4" style="color:#475569;">No products found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
<div class="mt-3 d-flex justify-content-center">{{ $products->links() }}</div>
@endif


{{-- ─── Edit Product Modal ───────────────────────────────────────── --}}
<div id="editProductModal" style="
    display:none;position:fixed;inset:0;z-index:9999;
    background:rgba(0,0,0,.65);backdrop-filter:blur(4px);
    align-items:center;justify-content:center;">
    <div style="background:#14161e;border:1px solid #1e2130;border-radius:.9rem;
                width:100%;max-width:540px;padding:2rem;position:relative;
                margin:1rem;max-height:90vh;overflow-y:auto;">

        <button onclick="closeEditProduct()" style="position:absolute;top:1rem;right:1rem;
            background:none;border:none;color:#475569;font-size:1.2rem;cursor:pointer;line-height:1;">
            <i class="bi bi-x-lg"></i>
        </button>

        <h5 style="color:#e2e8f0;font-weight:700;margin-bottom:1.5rem;">
            <i class="bi bi-pencil-square me-2" style="color:#818cf8;"></i>Edit Listing
        </h5>

        <form id="editProductForm" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                    Title <span style="color:#ef4444;">*</span>
                </label>
                <input id="ep_name" type="text" name="name" required maxlength="200"
                       class="adm-search w-100" placeholder="Listing title">
            </div>

            <div class="mb-3">
                <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                    Description
                </label>
                <textarea id="ep_description" name="description" rows="4" maxlength="5000"
                          class="adm-search w-100" style="resize:vertical;"
                          placeholder="Item description…"></textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                        Category <span style="color:#ef4444;">*</span>
                    </label>
                    <select id="ep_category" name="category" class="adm-search w-100">
                        @foreach(['electronics','gaming','fashion','furniture','books','sports','mobiles','home-garden','clothing','tools','art','beauty','pets','music','vehicles','baby','office','toys'] as $cat)
                            <option value="{{ $cat }}">{{ ucfirst(str_replace('-',' ',$cat)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                        Condition <span style="color:#ef4444;">*</span>
                    </label>
                    <select id="ep_condition" name="condition" class="adm-search w-100">
                        @foreach(['New','Like New','Good','Fair','Poor'] as $cond)
                            <option value="{{ $cond }}">{{ $cond }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label style="font-size:.75rem;color:#64748b;font-weight:600;display:block;margin-bottom:.35rem;">
                    Visibility
                </label>
                <select id="ep_hide" name="hide" class="adm-search w-100">
                    <option value="0">Active — publicly visible</option>
                    <option value="1">Hidden — removed from listings</option>
                </select>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <button type="button" onclick="closeEditProduct()" class="adm-btn adm-btn-ghost">Cancel</button>
                <button type="submit" class="adm-btn adm-btn-primary">
                    <i class="bi bi-check2 me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditProduct(id, name, description, category, condition, hide) {
    const base = "{{ rtrim(route('admin.products.update', ['product' => '__ID__']), '') }}";
    document.getElementById('editProductForm').action = base.replace('__ID__', id);
    document.getElementById('ep_name').value        = name;
    document.getElementById('ep_description').value = description || '';
    document.getElementById('ep_category').value    = category;
    document.getElementById('ep_condition').value   = condition;
    document.getElementById('ep_hide').value        = hide;
    const modal = document.getElementById('editProductModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeEditProduct() {
    document.getElementById('editProductModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('editProductModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditProduct();
});
</script>

@endsection
