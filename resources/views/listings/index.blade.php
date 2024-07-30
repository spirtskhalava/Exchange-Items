@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-5 mb-4">My Listings</h1>

    <a href="{{ route('listings.create') }}" class="btn btn-primary mb-3">Add New Product</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @foreach($products as $product)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">{{ $product->description }}</p>
                <a href="{{ route('listings.edit', $product) }}" class="btn btn-secondary">Edit</a>
                <form action="{{ route('listings.destroy', $product) }}" method="POST" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
