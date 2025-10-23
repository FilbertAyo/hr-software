<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOtherDeduction extends Model
{
    protected $fillable = [
        'employee_id',
        'other_deduction_type_id',
        'amount',
        'deduction_date',
        'reason',
        'document_path',
        'status',
        'notes',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deduction_date' => 'date',
        'approved_at' => 'datetime'
    ];

    /**
     * Get the employee for this deduction
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the deduction type
     */
    public function deductionType()
    {
        return $this->belongsTo(OtherDeductionType::class, 'other_deduction_type_id');
    }

    /**
     * Get the user who approved this deduction
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
