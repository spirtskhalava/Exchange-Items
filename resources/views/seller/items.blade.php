@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Items by {{ $seller->name }}</h2>
    <div class="row">
        @foreach($items as $item)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @php
                        $imagePaths = json_decode($item->image_paths, true);
                        $firstImagePath = $imagePaths[0] ?? asset('storage/default-image.jpg');
                    @endphp
                    <img src="{{ $firstImagePath }}" class="card-img-top" alt="{{ $item->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ $item->description }}</p>
                        <a href="{{ route('exchanges.create', $item->id) }}" class="btn btn-primary">Exchange Item</a>
                        <a href="{{ route('products.show', $item->id) }}" class="btn btn-secondary">View Item</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

<style>
.row {
    display: flex;
    flex-wrap: wrap;
}

.card {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card-body {
    flex: 1;
}
</style>