@extends('layouts.app')

@section('content')
<style>
    .card {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Modern shadow effect */
    }

    .card-body {
        background-color: #ffffff;
        padding: 2rem; /* Increased padding for better spacing */
    }

    .btn {
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem; /* Consistent padding for buttons */
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .container {
        max-width: 960px;
    }

    .mt-5, .mb-4 {
        margin-top: 3rem!important;
        margin-bottom: 1.5rem!important;
    }

    .alert-dismissible .btn-close {
        position: absolute;
        right: 1rem;
        top: 1rem;
    }

    .text-primary {
        color: #007bff!important;
    }

    .text-muted {
        color: #6c757d!important;
    }

    .text-dark {
        color: #343a40!important;
    }

    .alert-info {
        background-color: #e9ecef;
        border-color: #cfd2d6;
        color: #6c757d;
    }
</style>
<div class="container">
    <h1 class="mt-5 mb-4 text-center text-primary">My Listings</h1>

    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @forelse($products as $product)
        <div class="card mb-4">
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