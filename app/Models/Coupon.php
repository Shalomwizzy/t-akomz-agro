<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_value', 'max_uses',
        'used_count', 'valid_from', 'valid_until', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'valid_from'      => 'datetime',
            'valid_until'     => 'datetime',
            'value'           => 'decimal:2',
            'min_order_value' => 'decimal:2',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('valid_from', '<=', now())
                     ->where('valid_until', '>=', now());
    }

    public function isValid(float $orderTotal): bool
    {
        if (!$this->is_active) return false;
        if (now()->lt($this->valid_from) || now()->gt($this->valid_until)) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($this->min_order_value && $orderTotal < $this->min_order_value) return false;
        return true;
    }

    public function calculateDiscount(float $orderTotal, float $deliveryFee): float
    {
        return match ($this->type) {
            'PERCENTAGE'    => round($orderTotal * ($this->value / 100), 2),
            'FIXED'         => min($this->value, $orderTotal),
            'FREE_DELIVERY' => $deliveryFee,
            default         => 0,
        };
    }
}
