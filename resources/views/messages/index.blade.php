@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="receiver_id">Select User</label>
                <select id="receiver_id" class="form-control">
                    <option value="">--Select User--</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div id="chat-windows-container">
                <!-- Chat windows will be dynamically created here -->
            </div>
        </div>
    </div>
</div>
@endsection