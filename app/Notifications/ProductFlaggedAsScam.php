<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductFlaggedAsScam extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Product $product, public User $flaggedBy) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Product Flagged as Fake')
            ->line("Product \"{$this->product->name}\" was flagged as fake.")
            ->line("Flagged by user: {$this->flaggedBy->name}")
            ->action('Review Product', route('admin.products.show', $this->product->id));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'product_id'    => $this->product->id,
            'product_name'  => $this->product->name,
            'flagged_by'    => $this->flaggedBy->name,
            'flagged_by_id' => $this->flaggedBy->id,
        ];
    }
}