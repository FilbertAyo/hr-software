<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paygrade extends Model
{
    protected $fillable = [
        'grade',
        'description',
        'currency',
        'initial_amount',
        'optimal_amount',
        'step_increase'
    ];
}
