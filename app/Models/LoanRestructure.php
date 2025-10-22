<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRestructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'restructured_by',
        'old_installment_count',
        'new_installment_count',
        'old_monthly_payment',
        'new_monthly_payment',
        'old_start_date',
        'new_start_date',
        'old_end_date',
        'new_end_date',
        'remaining_amount_at_restructure',
        'reason',
        'old_installments_snapshot',
    ];

    protected $casts = [
        'old_monthly_payment' => 'decimal:2',
        'new_monthly_payment' => 'decimal:2',
        'old_start_date' => 'date',
        'new_start_date' => 'date',
        'old_end_date' => 'date',
        'new_end_date' => 'date',
        'remaining_amount_at_restructure' => 'decimal:2',
        'old_installments_snapshot' => 'array',
    ];

    /**
     * A restructure belongs to a loan.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * A restructure was done by a user.
     */
    public function restructuredBy()
    {
        return $this->belongsTo(User::class, 'restructured_by');
    }
}
