<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTimingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'use_office_timing',
        'use_biometrics'
    ];

    protected $casts = [
        'use_office_timing' => 'boolean',
        'use_biometrics' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
