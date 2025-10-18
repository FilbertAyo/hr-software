<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'activity_type',
        'activity_date',
        'reason',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'leave_type',
        'allocated_days',
        'used_days',
        'remaining_days',
        'leave_start_date',
        'leave_end_date',
        'heslb_index_no',
        'heslb_name_used',
        'deduction_name',
        'amount',
        'deduction_percentage',
        'paid_by_employer',
        'absent',
        'late',
        'late_time',
        'expected_time',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'leave_start_date' => 'date',
        'leave_end_date' => 'date',
        'late_time' => 'datetime',
        'expected_time' => 'datetime',
        'approved_at' => 'datetime',
        'amount' => 'decimal:2',
        'deduction_percentage' => 'decimal:2',
        'paid_by_employer' => 'boolean',
        'absent' => 'boolean',
        'late' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope for different activity types
    public function scopeLeaves($query)
    {
        return $query->where('activity_type', 'leave');
    }

    public function scopeDeductions($query)
    {
        return $query->where('activity_type', 'deduction');
    }

    public function scopeAbsent($query)
    {
        return $query->where('activity_type', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('activity_type', 'late');
    }

    public function scopeDepartment($query)
    {
        return $query->where('activity_type', 'department');
    }
}
