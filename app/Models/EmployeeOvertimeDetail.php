<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOvertimeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'overtime_given',
        'overtime_rate_weekday',
        'overtime_rate_saturday',
        'overtime_rate_weekend_holiday',
        'overtime_do_not_start_immediately',
        'weekday_overtime_starts_after',
        'saturday_overtime_starts_after',
        'sunday_holiday_overtime_starts_after'
    ];

    protected $casts = [
        'overtime_given' => 'boolean',
        'overtime_rate_weekday' => 'decimal:2',
        'overtime_rate_saturday' => 'decimal:2',
        'overtime_rate_weekend_holiday' => 'decimal:2',
        'overtime_do_not_start_immediately' => 'boolean',
        'weekday_overtime_starts_after' => 'time',
        'saturday_overtime_starts_after' => 'time',
        'sunday_holiday_overtime_starts_after' => 'time'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
