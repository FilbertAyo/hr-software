<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'installment_number',
        'amount',
        'due_date',
        'paid_date',
        'status',
    ];

    /**
     * An installment belongs to a loan.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
