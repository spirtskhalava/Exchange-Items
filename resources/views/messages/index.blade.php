@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="height: 80vh; min-height: 500px;">
        <div class="row g-0 h-100">
            
            <!-- LEFT SIDEBAR: User List -->
            <div class="col-md-4 col-lg-3 border-end h-100 d-flex flex-column bg-white">
                
                <!-- Sidebar Header -->
                <div class="p-3 border-bottom">
                    <h5 class="fw-bold mb-3">Messages</h5>
                    <!-- <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" class="form-control rounded-pill ps-5 bg-light border-0" placeholder="Search contacts...">
                    </div> -->
                </div>

                <!-- User Scrollable List -->
                <div class="flex-grow-1 overflow-auto custom-scrollbar">
                    <div class="list-group list-group-flush" id="user-list">
                        @foreach($users as $user)
                            <a href="#" 
                               class="list-group-item list-group-item-action user-list-item d-flex align-items-center gap-3 py-3 border-bottom-0" 
                               data-user-id="{{ $user->id }}"
                               data-user-name="{{ $user->name }}">
                                
                                <!-- Avatar -->
                                <div class="position-relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle" 
                                         width="45" height="45">
                                    <!-- Online Indicator (Static for now, can be dynamic later) -->
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                                </div>

                                <!-- User Details -->
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 text-truncate fw-bold text-dark">{{ $user->name }}</h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">Now</small>
                                    </div>
                                    <p class="mb-0 text-muted small text-truncate">Click to start chatting</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDEBAR: Chat Window -->
            <div class="col-md-8 col-lg-9 h-100 bg-light">
                <!-- This container is targeted by your JavaScript -->
                <div id="chat-windows-container" class="h-100 d-flex flex-column">
                    
                    <!-- Default Empty State (Visible before clicking a user) -->
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <div class="bg-white p-4 rounded-circle shadow-sm mb-4">
                            <i class="bi bi-chat-square-text text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Your Messages</h4>
                        <p class="text-muted mb-0">Select a contact from the list on the left to start a conversation.</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar for the sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #bbb;
    }

    /* Active State for User List */
    .user-list-item.active-user {
        background-color: #f0f2f5;
        border-left: 4px solid #4361ee;
    }
    
    .user-list-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection