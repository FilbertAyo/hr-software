<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'total_payments',
        'net_salary',
        'housing',
        'advance_salary',
        'advance_option',
        'advance_percentage',
        'advance_salary',
        'advance_rate',
        'paye_exempt',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'total_payments' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'housing' => 'boolean',
        'advance_option' => 'boolean',
        'advance_salary' => 'boolean',
        'advance_rate' => 'decimal:2',
        'paye_exempt' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
