<?php

namespace App\Notifications;

use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CancelDecided extends Notification
{
    use Queueable;

    public function __construct(public Exchange $exchange, public bool $approved) {}

    public function via($notifiable): array { return ['database']; }

    public function toArray($notifiable): array
    {
        $item   = $this->exchange->requestedProduct->name ?? 'item';
        $result = $this->approved ? 'approved' : 'rejected';

        return [
            'message' => "Your cancellation request for \"{$item}\" was {$result} by the other party.",
            'url'     => route('offers.index'),
            'type'    => 'cancel_decided',
        ];
    }
}
