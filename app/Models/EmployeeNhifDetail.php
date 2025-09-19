<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeNhifDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'nhif',
        'nhif_fixed_amount',
        'nhif_amount'
    ];

    protected $casts = [
        'nhif' => 'boolean',
        'nhif_fixed_amount' => 'boolean',
        'nhif_amount' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
