<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
    protected $fillable = [
        'name',
        'employer_percent',
        'employee_percent',
        'status',
    ];
}
