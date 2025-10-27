<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_period_id',
        'basic_salary',
        'allowances',
        'taxable_allowances',
        'non_taxable_allowances',
        'overtime_amount',
        'bonus',
        'advance_salary',
        'employee_pension_amount',
        'employer_pension_amount',
        'wcf_amount',
        'sdl_amount',
        'taxable_income',
        'gross_salary',
        'tax_deduction',
        'insurance_deduction',
        'loan_deduction',
        'attendance_deduction',
        'absent_late_deduction',
        'normal_deduction',
        'other_deductions',
        'total_deductions',
        'net_salary',
        'status',
        'processed_at',
        'paid_at',
        'notes'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'taxable_allowances' => 'decimal:2',
        'non_taxable_allowances' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'advance_salary' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'employee_pension_amount' => 'decimal:2',
        'employer_pension_amount' => 'decimal:2',
        'wcf_amount' => 'decimal:2',
        'sdl_amount' => 'decimal:2',
        'taxable_income' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'attendance_deduction' => 'decimal:2',
        'absent_late_deduction' => 'decimal:2',
        'normal_deduction'=>'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class);
    }

    public function allowanceDetails()
    {
        return $this->hasMany(PayrollAllowance::class);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Removed automatic calculations - now handled manually in controller
    // This ensures that payroll values are only set when explicitly processed by admin
}
