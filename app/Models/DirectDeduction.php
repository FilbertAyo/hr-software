<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectDeduction extends Model
{
    protected $fillable = [
        'name',
        'employer_percent',
        'employee_percent',
        'status',
        'salary_type',
        'deduction_type',
        'percentage_of',
        'require_member_no'
    ];

    /**
     * Get all employees that have this deduction.
     */
    public function employeeDeductions()
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

    /**
     * Get all employees through employee_deductions.
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_deductions')
            ->withPivot('member_number', 'status')
            ->withTimestamps();
    }
}
