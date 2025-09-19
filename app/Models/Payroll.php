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
        'overtime_amount',
        'bonus',
        'gross_salary',
        'tax_deduction',
        'insurance_deduction',
        'loan_deduction',
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
        'overtime_amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payroll) {
            // Auto calculate gross and net salary
            $payroll->gross_salary = $payroll->basic_salary + $payroll->allowances + $payroll->overtime_amount + $payroll->bonus;
            $payroll->total_deductions = $payroll->tax_deduction + $payroll->insurance_deduction + $payroll->loan_deduction + $payroll->other_deductions;
            $payroll->net_salary = $payroll->gross_salary - $payroll->total_deductions;
        });

        static::updating(function ($payroll) {
            // Auto calculate gross and net salary
            $payroll->gross_salary = $payroll->basic_salary + $payroll->allowances + $payroll->overtime_amount + $payroll->bonus;
            $payroll->total_deductions = $payroll->tax_deduction + $payroll->insurance_deduction + $payroll->loan_deduction + $payroll->other_deductions;
            $payroll->net_salary = $payroll->gross_salary - $payroll->total_deductions;
        });
    }
}
