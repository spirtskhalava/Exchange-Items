@extends('layouts.app')
@section('content')
@push('styles')
<style>
.ss-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: box-shadow var(--transition);
}
.ss-card:hover { box-shadow: var(--shadow); }
.ss-icon {
    width: 40px; height: 40px; border-radius: .6rem;
    background: var(--p-light); color: var(--p);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
</style>
@endpush

<div class="container py-5" style="max-width:660px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--text);">
                <i class="bi bi-bookmark-heart me-2" style="color:var(--p);"></i>Saved Searches
            </h4>
            <p class="text-muted small mb-0 mt-1">We notify you the moment a matching listing appears.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="bi bi-plus me-1"></i>New search
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 small rounded-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 small rounded-3">{{ session('error') }}</div>
    @endif

    @forelse($searches as $search)
    <div class="ss-card mb-3">
        <div class="ss-icon"><i class="bi bi-search"></i></div>
        <div class="flex-grow-1">
            <div class="fw-semibold" style="color:var(--text);">{{ $search->label }}</div>
            <div class="small text-muted mt-1">
                Saved {{ $search->created_at->diffForHumans() }}
                @if($search->last_notified_at)
                    · Last match {{ $search->last_notified_at->diffForHumans() }}
                @else
                    · No matches yet
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            {{-- Browse this search --}}
            <a href="{{ route('products.index', array_filter(['search' => $search->query, 'category' => $search->category, 'condition' => $search->condition])) }}"
               class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="bi bi-eye me-1"></i>Browse
            </a>
            {{-- Delete --}}
            <form method="POST" action="{{ route('saved-searches.destroy', $search) }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                        onclick="return confirm('Remove this saved search?')">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-bookmark" style="font-size:2.5rem;color:var(--muted2);display:block;margin-bottom:1rem;"></i>
        <p class="text-muted">No saved searches yet.</p>
        <a href="{{ route('products.index') }}" class="btn btn-dark rounded-pill px-4">
            <i class="bi bi-search me-2"></i>Browse & Save a Search
        </a>
    </div>
    @endforelse
</div>
@endsection
