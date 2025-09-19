<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payments',
        'dynamic_payments_paid_in_rates'
    ];

    protected $casts = [
        'payments' => 'boolean',
        'dynamic_payments_paid_in_rates' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
