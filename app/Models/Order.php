<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'delivery_address', 'delivery_city', 'delivery_state', 'delivery_notes',
        'delivery_type', 'delivery_fee', 'subtotal', 'discount', 'total', 'coupon_code',
        'status', 'payment_status', 'payment_method', 'payment_reference', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'     => 'decimal:2',
            'discount'     => 'decimal:2',
            'total'        => 'decimal:2',
            'delivery_fee' => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function timeline()
    {
        return $this->hasMany(OrderTimeline::class)->orderBy('created_at', 'asc');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->count() + 1;
        return 'TAK-' . $year . '-' . str_pad($last, 5, '0', STR_PAD_LEFT);
    }

    public function addTimeline(string $status, ?string $note = null): void
    {
        $this->timeline()->create(['status' => $status, 'note' => $note]);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'PENDING'          => 'Pending',
            'CONFIRMED'        => 'Confirmed',
            'PROCESSING'       => 'Processing',
            'PACKED'           => 'Packed',
            'DISPATCHED'       => 'Dispatched',
            'OUT_FOR_DELIVERY' => 'Out for Delivery',
            'DELIVERED'        => 'Delivered',
            'CANCELLED'        => 'Cancelled',
            'REFUNDED'         => 'Refunded',
            default            => ucfirst(strtolower($this->status)),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'PENDING'          => 'yellow',
            'CONFIRMED'        => 'blue',
            'PROCESSING'       => 'indigo',
            'PACKED'           => 'purple',
            'DISPATCHED'       => 'orange',
            'OUT_FOR_DELIVERY' => 'orange',
            'DELIVERED'        => 'green',
            'CANCELLED'        => 'red',
            'REFUNDED'         => 'gray',
            default            => 'gray',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return '₦' . number_format($this->total, 2);
    }

    public function getIsCancellableAttribute(): bool
    {
        return in_array($this->status, ['PENDING', 'CONFIRMED']);
    }
}
