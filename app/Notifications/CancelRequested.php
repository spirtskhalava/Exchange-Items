<?php

namespace App\Notifications;

use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CancelRequested extends Notification
{
    use Queueable;

    public function __construct(public Exchange $exchange) {}

    public function via($notifiable): array { return ['database']; }

    public function toArray($notifiable): array
    {
        $requester = $this->exchange->requester->name ?? 'Someone';
        $item      = $this->exchange->requestedProduct->name ?? 'your item';

        return [
            'message' => "{$requester} wants to cancel their offer on \"{$item}\". Reason: {$this->exchange->cancel_reason}",
            'url'     => route('offers.index'),
            'type'    => 'cancel_requested',
        ];
    }
}
