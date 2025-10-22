<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'payroll_period_id',
        'employee_id',
        'loan_type_id',
        'loan_amount',
        'remaining_amount',
        'interest_rate',
        'installment_count',
        'original_installment_count',
        'monthly_payment',
        'start_date',
        'end_date',
        'notes',
        'status',
        'is_restructured',
        'restructure_count',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_payment' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_restructured' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * A loan belongs to an employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * A loan has many installments.
     */
    public function installments()
    {
        return $this->hasMany(LoanInstallment::class);
    }

    /**
     * A loan belongs to a loan type.
     */
    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }

    /**
     * A loan belongs to a company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * A loan belongs to a payroll period.
     */
    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    /**
     * A loan has many restructures.
     */
    public function restructures()
    {
        return $this->hasMany(LoanRestructure::class);
    }

    /**
     * A loan was approved by a user.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * A loan was rejected by a user.
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
