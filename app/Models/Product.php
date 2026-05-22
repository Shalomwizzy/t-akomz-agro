<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'short_description',
        'price', 'compare_price', 'wholesale_price', 'unit', 'sku',
        'stock', 'low_stock_threshold', 'is_active', 'is_featured', 'is_organic',
        'weight', 'tags', 'nutrition_facts', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'is_organic'  => 'boolean',
            'tags'        => 'array',
            'nutrition_facts' => 'array',
            'price'       => 'decimal:2',
            'compare_price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOrganic($query)
    {
        return $query->where('is_organic', true);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();
        return $primary
            ? asset('storage/' . $primary->url)
            : asset('images/product-placeholder.jpg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₦' . number_format($this->price, 2);
    }

    public function getFormattedComparePriceAttribute(): ?string
    {
        return $this->compare_price
            ? '₦' . number_format($this->compare_price, 2)
            : null;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return null;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) return 'out_of_stock';
        if ($this->stock <= $this->low_stock_threshold) return 'low_stock';
        return 'in_stock';
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
