<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeNextOfKin extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'kin_name',
        'kin_phone',
        'kin_email',
        'kin_address',
        'relationship'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
