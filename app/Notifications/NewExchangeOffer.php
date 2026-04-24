<?php
namespace App\Notifications;

use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewExchangeOffer extends Notification
{
    use Queueable;

    public function __construct(public Exchange $exchange) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $fromUser    = $this->exchange->requester->name ?? 'Someone';
        $productName = $this->exchange->requestedProduct->name ?? 'your item';

        return [
            'exchange_id' => $this->exchange->id,
            'status'      => 'pending',
            'message'     => "{$fromUser} sent you an offer for \"{$productName}\".",
            'url'         => route('offers.index'),
        ];
    }
}
