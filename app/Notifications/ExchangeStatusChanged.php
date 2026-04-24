<?php
namespace App\Notifications;

use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExchangeStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Exchange $exchange) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $status      = $this->exchange->status;
        $productName = $this->exchange->requestedProduct->name ?? 'item';
        $otherUser   = $status === 'accepted'
            ? $this->exchange->responder->name
            : $this->exchange->responder->name;

        return [
            'exchange_id'  => $this->exchange->id,
            'status'       => $status,
            'message'      => $status === 'accepted'
                ? "Your offer for \"{$productName}\" was accepted!"
                : "Your offer for \"{$productName}\" was declined.",
            'url'          => route('offers.index'),
        ];
    }
}
