@extends('layouts.app')

@section('content')
<style>
    .card {
    border-radius: 0.75rem; /* Rounded corners */
}

.card-body {
    background-color: #f8f9fa; /* Light background for card body */
}

.btn-secondary, .btn-danger {
    border-radius: 0.25rem; /* Rounded corners for buttons */
}
</style>    
<div class="container">
    <h1 class="mt-5 mb-4 text-center text-primary">My Listings</h1>

    <a href="{{ route('listings.create') }}" class="btn btn-primary mb-4">Add New Product</a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @forelse($products as $product)
        <div class="card mb-4 shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title text-dark">{{ $product->name }}</h5>
                <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('listings.edit', $product) }}" class="btn btn-secondary">Edit</a>
                    <form action="{{ route('listings.destroy', $product) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center" role="alert">
            You have no listings yet.
        </div>
    @endforelse
</div>
@endsection