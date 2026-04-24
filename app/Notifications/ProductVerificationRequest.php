<?php

namespace App\Notifications;

// FIX: Import from the correct namespace
use App\Models\Product;  // <-- was wrongly referencing App\Notifications\Product
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductVerificationRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Product $product) {}  // now correctly typed

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'product_id'   => $this->product->id,
            'product_name' => $this->product->name,
            'message'      => 'Is this product real? Please verify.',
            'verify_url'   => route('products.verify', $this->product->id),
            'product_url'  => route('products.show', $this->product->id),
        ];
    }
}