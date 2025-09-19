<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePensionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'pension_details',
        'pension',
        'employee_pension_no',
        'employer_percentage',
        'employee_percentage',
        'previous_pension_amount',
        'paye_exempt'
    ];

    protected $casts = [
        'pension_details' => 'boolean',
        'employer_percentage' => 'decimal:2',
        'employee_percentage' => 'decimal:2',
        'previous_pension_amount' => 'boolean',
        'paye_exempt' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
