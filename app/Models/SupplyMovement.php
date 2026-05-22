<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplyMovement extends Model
{
    protected $fillable = [
        'farm_supply_id',
        'type',
        'quantity',
        'unit_cost',
        'notes',
        'linked_wallet_transaction_id',
        'linked_batch_id',
        'created_by',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function farmSupply(): BelongsTo
    {
        return $this->belongsTo(FarmSupply::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFormattedQuantityAttribute(): string
    {
        return number_format((float) $this->quantity, 2) . ' ' . ($this->farmSupply?->unit ?? '');
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'stock_in'   => 'text-brand-green bg-brand-green/10 border-brand-green/20',
            'stock_out'  => 'text-red-400 bg-red-400/10 border-red-400/20',
            'adjustment' => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            default      => 'text-content-muted bg-white/5 border-white/10',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'stock_in'   => 'Stock In',
            'stock_out'  => 'Stock Out',
            'adjustment' => 'Adjustment',
            default      => 'Unknown',
        };
    }
}
