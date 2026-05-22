<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTimeline extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'status', 'note', 'created_at'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getLabelAttribute(): string
    {
        return match ($this->status) {
            'PENDING'          => 'Order Placed',
            'CONFIRMED'        => 'Order Confirmed',
            'PROCESSING'       => 'Being Processed',
            'PACKED'           => 'Packed & Ready',
            'DISPATCHED'       => 'Dispatched',
            'OUT_FOR_DELIVERY' => 'Out for Delivery',
            'DELIVERED'        => 'Delivered',
            'CANCELLED'        => 'Cancelled',
            'REFUNDED'         => 'Refunded',
            'PAID'             => 'Payment Confirmed',
            default            => ucfirst(strtolower($this->status)),
        };
    }
}
