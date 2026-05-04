<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeInsurance extends Model
{
    protected $table = 'exchange_insurance';

    protected $fillable = [
        'exchange_id',
        'req_opted', 'resp_opted',
        'req_paypal_order_id', 'req_payment_captured',
        'resp_paypal_order_id', 'resp_payment_captured',
        'req_item_value', 'req_item_proposed_by', 'req_item_agreed',
        'resp_item_value', 'resp_item_proposed_by', 'resp_item_agreed',
        'escrow_status',
        'req_received', 'resp_received',
    ];

    protected $casts = [
        'req_opted'              => 'boolean',
        'resp_opted'             => 'boolean',
        'req_payment_captured'   => 'boolean',
        'resp_payment_captured'  => 'boolean',
        'req_item_agreed'        => 'boolean',
        'resp_item_agreed'       => 'boolean',
        'req_received'           => 'boolean',
        'resp_received'          => 'boolean',
    ];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function dispute()
    {
        return $this->hasOne(InsuranceDispute::class, 'exchange_id', 'exchange_id');
    }

    public function bothOpted(): bool
    {
        return $this->req_opted && $this->resp_opted;
    }

    public function valuationsAgreed(): bool
    {
        return $this->req_item_agreed && $this->resp_item_agreed;
    }

    // Platform fee: 3% of item value, minimum $1.00
    public const FEE_RATE    = 0.03;
    public const FEE_MINIMUM = 1.00;

    public static function platformFee(float $itemValue): float
    {
        return max(round($itemValue * self::FEE_RATE, 2), self::FEE_MINIMUM);
    }

    // Total locked amount per side = item value + 3% fee
    public function requesterLockedAmount(): float
    {
        $value = $this->req_item_value ?? 0;
        return $value + ($this->req_opted ? self::platformFee($value) : 0);
    }

    public function responderLockedAmount(): float
    {
        $value = $this->resp_item_value ?? 0;
        return $value + ($this->resp_opted ? self::platformFee($value) : 0);
    }
}
