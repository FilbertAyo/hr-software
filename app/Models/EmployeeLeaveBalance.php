<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLeaveBalance extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'leave_year',
        'allocated_days',
        'used_days',
        'remaining_days',
        'carry_forward_days',
        'monthly_increment',
        'extra_days_allocated'
    ];

    protected $casts = [
        'monthly_increment' => 'decimal:2',
        'leave_year' => 'integer',
    ];

    /**
     * Get the employee that owns the leave balance.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the leave type that owns the leave balance.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Calculate remaining days automatically.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->remaining_days = $model->allocated_days + $model->carry_forward_days + $model->extra_days_allocated - $model->used_days;
        });
    }
}
