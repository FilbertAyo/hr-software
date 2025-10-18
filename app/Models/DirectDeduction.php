<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectDeduction extends Model
{
    protected $fillable = [
        'name',
        'employer_percent',
        'employee_percent',
        'status',
        'salary_type',
        'deduction_type',
        'percentage_of',
        'must_include'
    ];
}
