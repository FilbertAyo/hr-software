<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEarngroup extends Model
{
    protected $fillable = [
        'employee_id',
        'earngroup_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function earngroup()
    {
        return $this->belongsTo(Earngroup::class);
    }
}
