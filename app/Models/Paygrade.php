<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paygrade extends Model
{
    protected $fillable = [
        'paygrade_name',
        'grade',
        'currency',
        'initial_amount',
        'optimal_amount',
        'step_increase',
        'min_salary',
        'max_salary',
        'description'
    ];
}
