<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmTourBooking extends Model
{
    protected $fillable = [
        'reference', 'name', 'email', 'phone', 'preferred_date',
        'group_size', 'package', 'amount', 'persons', 'notes',
        'payment_status', 'paystack_ref',
        'booking_status', 'admin_note', 'alternative_date', 'confirmed_date',
    ];

    protected $casts = [
        'preferred_date'   => 'date',
        'alternative_date' => 'date',
        'confirmed_date'   => 'date',
    ];

    public function getFormattedAmountAttribute(): string
    {
        return '₦' . number_format($this->amount, 2);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->booking_status) {
            'approved'  => ['label' => 'Approved',  'class' => 'bg-green-500/15 text-green-400 border-green-500/20'],
            'rejected'  => ['label' => 'Rejected',  'class' => 'bg-red-500/15 text-red-400 border-red-500/20'],
            'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-surface-elevated text-content-muted border-surface-border'],
            default     => ['label' => 'Pending',   'class' => 'bg-yellow-500/15 text-yellow-400 border-yellow-500/20'],
        };
    }

    public static function packages(): array
    {
        return [
            'individual' => ['label' => 'Individual',         'price' => 5000, 'per' => 'per person'],
            'group'      => ['label' => 'Group (10+ people)', 'price' => 3500, 'per' => 'per person'],
            'corporate'  => ['label' => 'Corporate / School', 'price' => 0,    'per' => 'contact us'],
        ];
    }
}
