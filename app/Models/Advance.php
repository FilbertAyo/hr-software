<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_period_id',
        'advance_amount',
        'request_date',
        'remarks',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'request_date' => 'date',
        'advance_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    /**
     * Scope to check if employee already has an advance in given month
     */
    public function scopeForMonth($query, $employeeId, $month)
    {
        return $query->where('employee_id', $employeeId)
                     ->whereMonth('request_date', $month);
    }

    /**
     * Check if advance is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if advance is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if advance is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope for pending advances
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved advances
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected advances
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
