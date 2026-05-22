<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmSupply extends Model
{
    protected $fillable = [
        'name',
        'category',
        'unit',
        'low_stock_threshold',
        'notes',
        'created_by',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(SupplyMovement::class);
    }

    // ─── Computed Stock ───────────────────────────────────────────────────────

    /**
     * Compute current stock from movements (stock_in + adjustment - stock_out).
     */
    public function getStockQuantityAttribute(): float
    {
        $stockIn     = $this->movements->where('type', 'stock_in')->sum('quantity');
        $stockOut    = $this->movements->where('type', 'stock_out')->sum('quantity');
        $adjustments = $this->movements->where('type', 'adjustment')->sum('quantity');

        return (float) ($stockIn + $adjustments - $stockOut);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getStatusAttribute(): string
    {
        $qty = $this->stock_quantity;

        if ($qty <= 0) {
            return 'out_of_stock';
        }

        if ($qty <= (float) $this->low_stock_threshold) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'out_of_stock' => 'text-red-400 bg-red-400/10 border-red-400/20',
            'low_stock'    => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/20',
            'in_stock'     => 'text-brand-green bg-brand-green/10 border-brand-green/20',
            default        => 'text-content-muted bg-white/5 border-white/10',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'out_of_stock' => 'Out of Stock',
            'low_stock'    => 'Low Stock',
            'in_stock'     => 'In Stock',
            default        => 'Unknown',
        };
    }

    public function getFormattedStockAttribute(): string
    {
        return number_format($this->stock_quantity, 2) . ' ' . $this->unit;
    }

    public function getCategoryLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->category));
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'feed'        => '🌾',
            'medication'  => '💊',
            'vaccination' => '💉',
            'equipment'   => '🔧',
            'seeds'       => '🌱',
            'chemicals'   => '🧪',
            'fuel'        => '⛽',
            'other'       => '📦',
            default       => '📦',
        };
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Filter low-stock items in PHP (computed field cannot be used in SQL directly).
     * Use ->get() then ->filter() on the collection, or use this scope with caution.
     */
    public function scopeLowStock($query)
    {
        // We return all and let PHP-side filtering handle it
        return $query;
    }
}
