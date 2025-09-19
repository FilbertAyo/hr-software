<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAbsentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'absent',
        'absent_date',
        'absent_reason'
    ];

    protected $casts = [
        'absent' => 'boolean',
        'absent_date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
