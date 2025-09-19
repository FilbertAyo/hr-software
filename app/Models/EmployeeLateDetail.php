<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLateDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'late',
        'late_date',
        'late_time',
        'late_reason'
    ];

    protected $casts = [
        'late' => 'boolean',
        'late_date' => 'date',
        'late_time' => 'time'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
