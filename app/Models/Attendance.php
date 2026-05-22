<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Attendance extends Model
{
    protected $fillable = [
        'worker_id', 'date', 'check_in_time', 'check_out_time',
        'status', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePresent(Builder $query): Builder
    {
        return $query->where('status', 'present');
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('date', $date);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'present'  => 'text-brand-green bg-brand-green/10 border-brand-green/20',
            'half_day' => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/20',
            'absent'   => 'text-red-400 bg-red-400/10 border-red-400/20',
            'holiday'  => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            'leave'    => 'text-purple-400 bg-purple-400/10 border-purple-400/20',
            default    => 'text-content-muted bg-white/5 border-white/10',
        };
    }
}
