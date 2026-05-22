<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchExpense extends Model
{
    protected $fillable = [
        'livestock_batch_id',
        'type',
        'amount',
        'description',
        'vendor_name',
        'receipt_file',
        'expense_date',
        'linked_wallet_transaction_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount'       => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function livestockBatch(): BelongsTo
    {
        return $this->belongsTo(LivestockBatch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFormattedAmountAttribute(): string
    {
        return '₦' . number_format((float) $this->amount, 2);
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'feed'        => 'text-brand-green bg-brand-green/10 border-brand-green/20',
            'treatment'   => 'text-red-400 bg-red-400/10 border-red-400/20',
            'vaccination' => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            'labor'       => 'text-purple-400 bg-purple-400/10 border-purple-400/20',
            'transport'   => 'text-cyan-400 bg-cyan-400/10 border-cyan-400/20',
            'equipment'   => 'text-orange-400 bg-orange-400/10 border-orange-400/20',
            'other'       => 'text-content-muted bg-white/5 border-white/10',
            default       => 'text-content-muted bg-white/5 border-white/10',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return ucfirst($this->type);
    }
}
