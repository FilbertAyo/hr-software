<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'loan_type_id',
        'loan_amount',
        'remaining_amount',
        'interest_rate',
        'installment_count',
        'monthly_payment',
        'start_date',
        'end_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_payment' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
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
}
