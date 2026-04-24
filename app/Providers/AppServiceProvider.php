<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Exchange;

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
            $unreadCount          = 0;
            $pendingOffersCount   = 0;
            $unreadNotifications  = 0;

            if (Auth::check()) {
                $unreadCount = Message::where('receiver_id', Auth::id())
                                      ->where('is_read', false)
                                      ->count();

                $pendingOffersCount = Exchange::where('responder_id', Auth::id())
                                              ->where('status', 'pending')
                                              ->count();

                $unreadNotifications = Auth::user()->unreadNotifications()->count();
            }

            $view->with([
                'unreadCount'         => $unreadCount,
                'pendingOffersCount'  => $pendingOffersCount,
                'unreadNotifications' => $unreadNotifications,
            ]);
        });
    }
}