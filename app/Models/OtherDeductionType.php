<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherDeductionType extends Model
{
    protected $fillable = [
        'deduction_type',
        'requires_document',
        'description',
        'status'
    ];

    protected $casts = [
        'requires_document' => 'boolean',
        'status' => 'boolean'
    ];

    /**
     * Get all employee deductions for this type
     */
    public function employeeDeductions()
    {
        return $this->hasMany(EmployeeOtherDeduction::class);
    }
}
