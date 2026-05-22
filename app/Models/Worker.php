<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Worker extends Model
{
    protected $fillable = [
        'full_name', 'phone', 'email', 'address', 'role', 'employment_type',
        'salary_type', 'salary_amount', 'start_date', 'end_date', 'status',
        'bank_name', 'bank_account', 'id_number', 'emergency_contact',
        'photo', 'notes', 'created_by',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'salary_amount' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function salaryPayments(): HasMany
    {
        return $this->hasMany(SalaryPayment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFormattedSalaryAttribute(): string
    {
        return '₦' . number_format((float) $this->salary_amount, 2);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'text-brand-green bg-brand-green/10 border-brand-green/20',
            'inactive'  => 'text-content-muted bg-white/5 border-white/10',
            'suspended' => 'text-red-400 bg-red-400/10 border-red-400/20',
            default     => 'text-content-muted bg-white/5 border-white/10',
        };
    }

    public function getEmploymentLabelAttribute(): string
    {
        return ucfirst($this->employment_type);
    }

    public function getSalaryLabelAttribute(): string
    {
        return match ($this->salary_type) {
            'fixed'      => $this->formatted_salary . '/period',
            'daily_rate' => $this->formatted_salary . '/day',
            'hourly'     => $this->formatted_salary . '/hour',
            default      => $this->formatted_salary,
        };
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function daysPresent(\Carbon\Carbon $from, \Carbon\Carbon $to): int
    {
        return $this->attendances()
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->where('status', 'present')
            ->count();
    }

    public function halfDays(\Carbon\Carbon $from, \Carbon\Carbon $to): int
    {
        return $this->attendances()
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->where('status', 'half_day')
            ->count();
    }

    public function totalAttendanceRecords(\Carbon\Carbon $from, \Carbon\Carbon $to): int
    {
        return $this->attendances()
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->count();
    }

    /**
     * Calculate base salary for a period based on attendance.
     *
     * Fixed-salary workers: if no attendance records exist for the period at all
     * (attendance tracking hasn't been done), pay the full salary rather than ₦0.
     * If records exist but the worker was absent every day, pro-rate to 0.
     *
     * Daily-rate workers always require attendance — no records = no pay.
     */
    public function calculateBaseSalary(\Carbon\Carbon $from, \Carbon\Carbon $to): float
    {
        $workingDays = $from->diffInWeekdays($to) + 1;

        if ($this->salary_type === 'daily_rate') {
            $present  = $this->daysPresent($from, $to);
            $halfDays = $this->halfDays($from, $to);
            return ($present + ($halfDays * 0.5)) * (float) $this->salary_amount;
        }

        // Fixed salary: check if any attendance records exist for the period
        $totalRecords = $this->totalAttendanceRecords($from, $to);

        if ($totalRecords === 0) {
            // No attendance tracked at all — assume full attendance, pay full salary
            return (float) $this->salary_amount;
        }

        // Records exist — pro-rate by actual attendance
        $present   = $this->daysPresent($from, $to);
        $halfDays  = $this->halfDays($from, $to);
        $effective = $present + ($halfDays * 0.5);

        return $workingDays > 0
            ? round(($effective / $workingDays) * (float) $this->salary_amount, 2)
            : (float) $this->salary_amount;
    }
}
