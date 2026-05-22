<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LivestockBatch extends Model
{
    protected $fillable = [
        'batch_name',
        'animal_type',
        'quantity_purchased',
        'quantity_current',
        'purchase_cost',
        'purchase_date',
        'expected_sale_date',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date'      => 'date',
            'expected_sale_date' => 'date',
            'purchase_cost'      => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function batchExpenses(): HasMany
    {
        return $this->hasMany(BatchExpense::class);
    }

    // ─── Computed Financials ──────────────────────────────────────────────────

    public function getTotalExpensesAttribute(): float
    {
        return (float) $this->batchExpenses->sum('amount');
    }

    public function getTotalCostAttribute(): float
    {
        return (float) $this->purchase_cost + $this->total_expenses;
    }

    public function getCostPerAnimalAttribute(): float
    {
        $current = (int) $this->quantity_current;
        if ($current <= 0) {
            return 0.0;
        }
        return $this->total_cost / $current;
    }

    public function getFeedCostAttribute(): float
    {
        return (float) $this->batchExpenses->where('type', 'feed')->sum('amount');
    }

    public function getTreatmentCostAttribute(): float
    {
        return (float) $this->batchExpenses->whereIn('type', ['treatment', 'vaccination'])->sum('amount');
    }

    public function getLaborCostAttribute(): float
    {
        return (float) $this->batchExpenses->where('type', 'labor')->sum('amount');
    }

    public function getTransportCostAttribute(): float
    {
        return (float) $this->batchExpenses->where('type', 'transport')->sum('amount');
    }

    public function getOtherCostAttribute(): float
    {
        return (float) $this->batchExpenses
            ->whereNotIn('type', ['feed', 'treatment', 'vaccination', 'labor', 'transport'])
            ->sum('amount');
    }

    // ─── Formatted Accessors ─────────────────────────────────────────────────

    public function getFormattedPurchaseCostAttribute(): string
    {
        return '₦' . number_format((float) $this->purchase_cost, 2);
    }

    public function getFormattedTotalCostAttribute(): string
    {
        return '₦' . number_format($this->total_cost, 2);
    }

    public function getFormattedCostPerAnimalAttribute(): string
    {
        return '₦' . number_format($this->cost_per_animal, 2);
    }

    public function getFormattedTotalExpensesAttribute(): string
    {
        return '₦' . number_format($this->total_expenses, 2);
    }

    // ─── Display Helpers ─────────────────────────────────────────────────────

    public function getAnimalTypeIconAttribute(): string
    {
        return match ($this->animal_type) {
            'pig'     => '🐷',
            'chicken' => '🐔',
            'cattle'  => '🐄',
            'goat'    => '🐐',
            'sheep'   => '🐑',
            'fish'    => '🐟',
            'turkey'  => '🦃',
            'other'   => '🐾',
            default   => '🐾',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'         => 'text-brand-green bg-brand-green/10 border-brand-green/20',
            'partially_sold' => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/20',
            'sold'           => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            'closed'         => 'text-red-400 bg-red-400/10 border-red-400/20',
            default          => 'text-content-muted bg-white/5 border-white/10',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'         => 'Active',
            'partially_sold' => 'Partially Sold',
            'sold'           => 'Sold',
            'closed'         => 'Closed',
            default          => ucfirst($this->status),
        };
    }

    public function getAnimalTypeLabelAttribute(): string
    {
        return ucfirst($this->animal_type);
    }

    public function getMortalityCountAttribute(): int
    {
        return (int) $this->quantity_purchased - (int) $this->quantity_current;
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
