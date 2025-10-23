<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $fillable = [
        'leave_type_name',
        'other_name',
        'no_of_days',
        'no_monthly_increment',
        'extra_no_of_days',
        'no_of_monthly_increment',
        'extra_days',
        'show_in_web_portal',
        'status',
        'description',
        'carry_forward',
        'max_carry_forward_days',
        'requires_approval',
        'requires_documentation',
        'gender_restriction',
        'min_service_days'
    ];

    protected $casts = [
        'no_monthly_increment' => 'boolean',
        'extra_no_of_days' => 'boolean',
        'show_in_web_portal' => 'boolean',
        'carry_forward' => 'boolean',
        'requires_approval' => 'boolean',
        'requires_documentation' => 'boolean',
        'no_of_monthly_increment' => 'decimal:2',
    ];

    /**
     * Get the leaves for the leave type.
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Get the employee leave balances for this leave type.
     */
    public function employeeLeaveBalances(): HasMany
    {
        return $this->hasMany(EmployeeLeaveBalance::class);
    }

    /**
     * Scope for active leave types.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for leave types visible in web portal.
     */
    public function scopeVisibleInPortal($query)
    {
        return $query->where('show_in_web_portal', true);
    }
}
