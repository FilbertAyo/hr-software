<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'loan_type_id',
        'loan_amount',
        'remaining_amount',
        'reason',
        'status',
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
}
