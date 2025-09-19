<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'allocated_days',
        'used_days',
        'remaining_days'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
