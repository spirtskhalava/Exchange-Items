<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'responder_id',
        'requested_product_id',
        'offered_product_id',
        'status',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id');
    }

    public function requestedProduct()
    {
        return $this->belongsTo(Product::class, 'requested_product_id');
    }

    public function offeredProduct()
    {
        return $this->belongsTo(Product::class, 'offered_product_id');
    }
}