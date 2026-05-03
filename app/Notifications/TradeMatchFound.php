<?php
namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TradeMatchFound extends Notification
{
    use Queueable;

    public function __construct(
        public Product $myProduct,
        public Product $matchProduct
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message'       => "🔥 Perfect match! Someone has \"{$this->matchProduct->name}\" and wants what you have.",
            'my_product'    => $this->myProduct->name,
            'match_product' => $this->matchProduct->name,
            'match_user'    => $this->matchProduct->user->name ?? 'someone',
            'url'           => route('products.show', $this->matchProduct->id),
            'type'          => 'trade_match',
        ];
    }
}
