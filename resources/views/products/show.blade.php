@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm">
                @if($product->image_paths)
                    @php
                        $imagePaths = json_decode($product->image_paths, true);
                        $firstImagePath = $imagePaths[0]['path'] ?? 'default-image.jpg';
                    @endphp
                    <img src="{{ asset('storage/' . $firstImagePath) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                @else
                    <img src="{{ asset('storage/default-image.jpg') }}" class="card-img-top" alt="Default Image" style="height: 400px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h1 class="card-title mb-3">{{ $product->name }}</h1>
                    <p class="card-text mb-4">{{ $product->description }}</p>
                    <p class="card-text text-muted">
                        <small>Posted on {{ $product->created_at->format('M d, Y') }}</small>
                        <br>
                        <small>Views: {{ $product->views }}</small>
                    </p>
                    @if($product->user_id !== Auth::id())
                        <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-warning btn-lg">Offer Exchange</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-light mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Product Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Name:</strong> {{ $product->name }}</li>
                        <li class="list-group-item"><strong>Description:</strong> {{ $product->description }}</li>
                        <li class="list-group-item"><strong>Posted By:</strong> {{ $product->user->username }}</li>
                        <li class="list-group-item"><strong>Views:</strong> {{ $product->views }}</li>
                        <li class="list-group-item"><strong>Posted On:</strong> {{ $product->created_at->format('M d, Y') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection