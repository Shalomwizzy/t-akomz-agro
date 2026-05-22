<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Payroll extends Model
{
    protected $fillable = [
        'worker_id', 'period_start', 'period_end',
        'total_days_in_period', 'days_present', 'days_absent', 'half_days',
        'base_salary', 'deductions', 'bonuses', 'total_payable',
        'notes', 'status', 'generated_by', 'approved_by',
    ];

    protected $casts = [
        'period_start'      => 'date',
        'period_end'        => 'date',
        'base_salary'       => 'decimal:2',
        'deductions'        => 'decimal:2',
        'bonuses'           => 'decimal:2',
        'total_payable'     => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(SalaryPayment::class);
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid(Builder $query): Builder
    {
        return $query->whereIn('status', ['draft', 'approved']);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFormattedPayableAttribute(): string
    {
        return '₦' . number_format((float) $this->total_payable, 2);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'    => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/20',
            'approved' => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            'paid'     => 'text-brand-green bg-brand-green/10 border-brand-green/20',
            default    => 'text-content-muted bg-white/5 border-white/10',
        };
    }

    public function getPeriodLabelAttribute(): string
    {
        return $this->period_start->format('M d') . ' – ' . $this->period_end->format('M d, Y');
    }

    public function getAttendanceRateAttribute(): float
    {
        if ($this->total_days_in_period === 0) return 0;
        return round((($this->days_present + $this->half_days * 0.5) / $this->total_days_in_period) * 100, 1);
    }
}
