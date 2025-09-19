<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'qualification',
        'qualification_details'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
