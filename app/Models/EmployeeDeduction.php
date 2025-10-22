<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    protected $fillable = [
        'employee_id',
        'direct_deduction_id',
        'member_number',
        'status',
    ];

    /**
     * Get the employee that owns the deduction.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the direct deduction details.
     */
    public function directDeduction()
    {
        return $this->belongsTo(DirectDeduction::class);
    }
}
