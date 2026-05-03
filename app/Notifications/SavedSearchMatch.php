<?php
namespace App\Notifications;

use App\Models\Product;
use App\Models\SavedSearch;
use Illuminate\Notifications\Notification;

class SavedSearchMatch extends Notification
{
    public function __construct(
        public Product $product,
        public SavedSearch $search
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'       => 'saved_search_match',
            'message'    => "New match for your saved search \"{$this->search->label}\": {$this->product->name}",
            'url'        => route('products.show', $this->product->id),
            'product_id' => $this->product->id,
            'search_id'  => $this->search->id,
        ];
    }
}
