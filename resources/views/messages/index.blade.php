@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-4">
            <div class="form-group">
                <label for="user-list">Select User</label>
                <ul id="user-list" class="list-group">
                    @foreach($users as $user)
                        <li class="list-group-item user-list-item" data-user-id="{{ $user->id }}">
                            {{ $user->name }}
                        </li>
                    @endforeach
                </ul>
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