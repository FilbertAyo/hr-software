<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_period_id',
        'attendance_type',
        // 'attendance_date',  // REMOVED - Not using this field
        'reason',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'late_time',
        'expected_time',
        'late_minutes',
        'late_hours',       // ADDED THIS
        'absent_days',      // ADDED THIS
        'is_absent',
        'is_late',
        'check_in_time',
        'check_out_time',
        'break_start',
        'break_end',
        'hours_worked',
        'overtime_hours',
    ];

    protected $casts = [
        // 'attendance_date' => 'date',  // REMOVED
        'late_time' => 'datetime',
        'expected_time' => 'datetime',
        'approved_at' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'is_absent' => 'boolean',
        'is_late' => 'boolean',
        'late_minutes' => 'integer',
        'late_hours' => 'decimal:2',    // ADDED THIS
        'absent_days' => 'integer',     // ADDED THIS
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    // Scope for different attendance types
    public function scopeAbsent($query)
    {
        return $query->where('attendance_type', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('attendance_type', 'late');
    }

    public function scopePresent($query)
    {
        return $query->where('attendance_type', 'present');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper methods
    public function calculateLateMinutes()
    {
        if ($this->is_late && $this->late_time && $this->expected_time) {
            $lateTime = \Carbon\Carbon::parse($this->late_time);
            $expectedTime = \Carbon\Carbon::parse($this->expected_time);
            return $lateTime->diffInMinutes($expectedTime);
        }
        return 0;
    }
}