<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'is_primary',
        'bank_id',
        'account_no',
        'bank_name',
        'branch',
        'branch_code',
        'bank_account_no',
        'amount',
        'type'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'amount' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
