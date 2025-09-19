<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'allowance_type',
        'allowance_name',
        'amount',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
