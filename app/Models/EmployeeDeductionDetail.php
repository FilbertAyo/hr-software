<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeductionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'heslb_index_no',
        'heslb_name_used',
        'deduction',
        'amount',
        'deduction_percentage',
        'paid_by_employer'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deduction_percentage' => 'decimal:2',
        'paid_by_employer' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
