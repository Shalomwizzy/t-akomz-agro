<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryPayment extends Model
{
    protected $fillable = [
        'payroll_id', 'worker_id', 'amount_paid', 'payment_method',
        'date_paid', 'paid_by', 'wallet_transaction_id', 'reference', 'notes',
    ];

    protected $casts = [
        'date_paid'   => 'date',
        'amount_paid' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function walletTransaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFormattedAmountAttribute(): string
    {
        return '₦' . number_format((float) $this->amount_paid, 2);
    }
}
