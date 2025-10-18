<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePensionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'pension_id',
        'pension_details',
        'employee_pension_no',
        'employee_pension_amount',
        'employer_pension_amount'
    ];

    protected $casts = [
        'pension_details' => 'boolean',
        'employee_pension_amount' => 'decimal:2',
        'employer_pension_amount' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function pension()
    {
        return $this->belongsTo(DirectDeduction::class, 'pension_id');
    }
}
