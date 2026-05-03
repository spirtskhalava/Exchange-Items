<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedSearch extends Model
{
    protected $fillable = ['user_id', 'query', 'category', 'condition', 'last_notified_at'];

    protected $casts = ['last_notified_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Check if a product matches this saved search */
    public function matches(Product $product): bool
    {
        if ($this->query && !str_contains(
            strtolower($product->name . ' ' . $product->description),
            strtolower($this->query)
        )) {
            return false;
        }

        if ($this->category && $product->category !== $this->category) {
            return false;
        }

        if ($this->condition && $product->condition !== $this->condition) {
            return false;
        }

        return true;
    }

    /** Human-readable label */
    public function getLabelAttribute(): string
    {
        $parts = array_filter([
            $this->query    ? '"' . $this->query . '"' : null,
            $this->category ? ucfirst(str_replace('-', ' ', $this->category)) : null,
            $this->condition,
        ]);
        return implode(' · ', $parts) ?: 'Any listing';
    }
}
