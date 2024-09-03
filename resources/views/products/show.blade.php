@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4 shadow-sm border-0">
                @if($product->image_paths)
                    @php
                        $imagePaths = json_decode($product->image_paths, true);
                        $firstImagePath = $imagePaths[0] ?? asset('storage/default-image.jpg');
                    @endphp
                    <img src="{{ $firstImagePath }}" class="card-img-top img-fluid clickable-image" alt="{{ $product->name }}" style="max-height: 500px; object-fit: contain; border-radius: 5px;" data-toggle="modal" data-target="#imageModal">
                @else
                    <img src="{{ asset('storage/default-image.jpg') }}" class="card-img-top img-fluid" alt="Default Image" style="max-height: 500px; object-fit: contain; border-radius: 5px;">
                @endif

                <div class="mt-3 d-flex justify-content-center">
                    @foreach($imagePaths as $image)
                        <img src="{{ $image }}" alt="{{ $product->name }}" class="img-thumbnail mx-2 clickable-image" style="width: 100px; height: 100px; object-fit: cover;" data-toggle="modal" data-target="#imageModal">
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card border-light shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-primary">{{ $product->name }}</h2>
                    <p class="text-muted">{{ $product->condition }}</p>
                    <p class="card-text">{{ $product->description }}</p>

                    <ul class="list-unstyled mt-3 mb-4">
                        <li><strong>Category: </strong>{{ $product->category }}</li>
                        <li><strong>Condition: </strong>{{ $product->condition }}</li>
                        <li><strong>Posted On: </strong>{{ $product->created_at->format('M d, Y') }}</li>
                        <li><strong>Views: </strong>{{ $product->views }}</li>
                    </ul>

                    @if($product->user_id !== Auth::id())
                        <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-warning btn-lg w-100 my-2">Make an Offer</a>
                    @endif

                    @if(Auth::check())
                        <form action="{{ route('wishlist.store', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100 my-2">Add to Wishlist</button>
                        </form>
                    @endif
                   <a href="{{ route('messages.openChatWithSeller', $product->user->id) }}" class="btn btn-outline-secondary btn-lg w-100 my-2">Contact Seller</a>
                </div>
            </div>

            <div class="card border-light mt-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Seller Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Seller: </strong><a href="{{ route('seller.items', $product->user->id) }}">{{ $product->user->name }}</a></li>
                        <li class="list-group-item"><strong>Location: </strong>{{ $product->location ?? 'Not provided' }}</li> <!-- Assuming you have a location field -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modal-image" src="" class="img-fluid w-100" alt="Product Image">
            </div>
        </div>
    </div>
</div>

<style>
.clickable-image{
    cursor:pointer;
}
</style>    

@endsection