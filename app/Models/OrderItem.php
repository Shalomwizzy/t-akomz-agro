<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'product_unit',
        'quantity', 'unit_price', 'total_price',
    ];

    protected function casts(): array
    {
        return [
            'unit_price'  => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function getLineTotalAttribute(): float
    {
        return (float) $this->total_price;
    }

    public function getUnitAttribute(): string
    {
        return $this->product_unit;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
