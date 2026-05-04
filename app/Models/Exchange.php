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
        'money_offer',
        'cash_paypal_order_id',
        'cash_payment_captured',
        'cash_payment_method',
        'cancel_reason',
        'cancel_requested_at',
        'cancel_approved',
    ];

    protected $casts = [
        'cash_payment_captured' => 'boolean',
        'cancel_approved'       => 'boolean',
        'cancel_requested_at'   => 'datetime',
    ];

    /** Cancellation has been requested but not yet decided */
    public function hasPendingCancelRequest(): bool
    {
        return $this->cancel_reason !== null && $this->cancel_approved === null;
    }

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

    public function insurance()
    {
        return $this->hasOne(ExchangeInsurance::class);
    }
}