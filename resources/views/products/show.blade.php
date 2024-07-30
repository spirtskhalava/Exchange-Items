@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    @if($product->user_id !== Auth::id())
        <a href="{{ route('exchanges.create', $product->id) }}" class="btn btn-warning">Offer Exchange</a>
    @endif
</div>
@endsection