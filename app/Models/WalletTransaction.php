<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'category',
        'short_title',
        'description',
        'vendor_name',
        'receipt_file',
        'status',
        'rejection_reason',
        'created_by',
        'approved_by',
        'allocated_to',
        'project_name',
        'expense_date',
        'linked_batch_id',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount'       => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function allocatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_to');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFormattedAmountAttribute(): string
    {
        return '₦' . number_format((float) $this->amount, 2);
    }

    public function getCategoryLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->category));
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/20',
            'approved'  => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            'rejected'  => 'text-red-400 bg-red-400/10 border-red-400/20',
            'completed' => 'text-brand-green bg-brand-green/10 border-brand-green/20',
            default     => 'text-content-muted bg-white/5 border-white/10',
        };
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    public function approve(int $adminId): void
    {
        DB::transaction(function () use ($adminId) {
            $this->update([
                'status'      => 'approved',
                'type'        => 'expense_approved',
                'approved_by' => $adminId,
            ]);

            $wallet = $this->wallet;
            $wallet->decrement('balance', (float) $this->amount);
            $wallet->increment('total_spent', (float) $this->amount);
        });
    }

    public function reject(string $reason, int $adminId): void
    {
        $this->update([
            'status'           => 'rejected',
            'rejection_reason' => $reason,
            'approved_by'      => $adminId,
        ]);
    }

    public function confirmSpending(?string $receiptPath = null): void
    {
        $data = [
            'status' => 'completed',
            'type'   => 'expense_spent',
        ];

        if ($receiptPath !== null) {
            $data['receipt_file'] = $receiptPath;
        }

        $this->update($data);
    }
}
