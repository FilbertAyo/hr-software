<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'advance_amount',
        'request_date',
        'advance_taken',
        'remarks',
    ];

    protected $casts = [
        'advance_taken' => 'boolean',
        'request_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Scope to check if employee already has an advance in given month
     */
    public function scopeForMonth($query, $employeeId, $month)
    {
        return $query->where('employee_id', $employeeId)
                     ->whereMonth('request_date', $month);
    }
}
