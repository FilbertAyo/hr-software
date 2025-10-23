<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'leave_action',
        'from_date',
        'to_date',
        'no_of_days',
        'remarks',
        'status'
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    /**
     * Get the employee that owns the leave.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the leave type that owns the leave.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Get the formatted leave action.
     */
    public function getFormattedLeaveActionAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->leave_action));
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Approved' => 'badge-success',
            'Rejected' => 'badge-danger',
            default => 'badge-warning'
        };
    }
}
