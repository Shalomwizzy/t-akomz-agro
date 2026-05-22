<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    protected $fillable = ['name', 'role', 'bio', 'image', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=B8F397&color=050505&size=400';
        }
        // images copied from public/our-team/ are referenced directly by asset()
        if (str_starts_with($this->image, 'our-team/')) {
            return asset($this->image);
        }
        return Storage::disk('public')->url($this->image);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
