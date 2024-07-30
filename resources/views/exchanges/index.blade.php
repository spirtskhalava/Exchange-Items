@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Exchange Requests</h1>
    @foreach($exchanges as $exchange)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Exchange Request</h5>
                <p>Requested Product: {{ $exchange->requestedProduct->name }}</p>
                <p>Offered Product: {{ $exchange->offeredProduct->name }}</p>
                <p>Status: {{ $exchange->status }}</p>
                @if($exchange->status == 'pending')
                    <form action="{{ route('exchanges.updateStatus', $exchange->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="status">Update Status:</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="accepted">Accept</option>
                                <option value="declined">Decline</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success mt-2">Update</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection