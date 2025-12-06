<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Import View
use Illuminate\Support\Facades\Auth; // Import Auth
use App\Models\Message;              // Import Message Model

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Add this logic
        View::composer('*', function ($view) {
            $unreadCount = 0;
            
            if (Auth::check()) {
                $unreadCount = Message::where('receiver_id', Auth::id())
                                      ->where('is_read', false) // or 0
                                      ->count();
            }

            $view->with('unreadCount', $unreadCount);
        });
    }
}