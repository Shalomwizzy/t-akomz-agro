<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Wallet extends Model
{
    protected $fillable = [
        'name',
        'balance',
        'total_funded',
        'total_spent',
    ];

    protected function casts(): array
    {
        return [
            'balance'      => 'decimal:2',
            'total_funded' => 'decimal:2',
            'total_spent'  => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // ─── Static Helpers ───────────────────────────────────────────────────────

    public static function main(): static
    {
        return static::firstOrCreate(['name' => 'T-Akomz Farm Wallet'], [
            'balance'      => 0,
            'total_funded' => 0,
            'total_spent'  => 0,
        ]);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFormattedBalanceAttribute(): string
    {
        return '₦' . number_format((float) $this->balance, 2);
    }

    public function getFormattedFundedAttribute(): string
    {
        return '₦' . number_format((float) $this->total_funded, 2);
    }

    public function getFormattedSpentAttribute(): string
    {
        return '₦' . number_format((float) $this->total_spent, 2);
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    public function fund(float $amount, string $description, int $userId, ?int $allocatedTo = null, ?string $projectName = null): WalletTransaction
    {
        return DB::transaction(function () use ($amount, $description, $userId, $allocatedTo, $projectName) {
            $transaction = $this->transactions()->create([
                'type'         => 'funding',
                'amount'       => $amount,
                'category'     => 'other',
                'short_title'  => 'Wallet Funding',
                'description'  => $description,
                'status'       => 'completed',
                'created_by'   => $userId,
                'approved_by'  => $userId,
                'allocated_to' => $allocatedTo,
                'project_name' => $projectName,
                'expense_date' => now()->toDateString(),
            ]);

            $this->increment('balance', $amount);
            $this->increment('total_funded', $amount);

            return $transaction;
        });
    }

    public function managerStats(int $userId): array
    {
        $allocated = $this->transactions()
            ->where('type', 'funding')
            ->where('allocated_to', $userId)
            ->where('status', 'completed')
            ->sum('amount');

        $spent = $this->transactions()
            ->where('created_by', $userId)
            ->whereIn('type', ['expense_approved', 'expense_spent'])
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        return [
            'allocated' => (float) $allocated,
            'spent'     => (float) $spent,
            'remaining' => max(0, (float) $allocated - (float) $spent),
        ];
    }

    public function pendingCount(): int
    {
        return $this->transactions()->where('status', 'pending')->count();
    }
}
