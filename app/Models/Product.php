<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'image_paths', 
        'views'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requestedExchanges()
    {
        return $this->hasMany(Exchange::class, 'requested_product_id');
    }

    public function offeredExchanges()
    {
        return $this->hasMany(Exchange::class, 'offered_product_id');
    }
}
