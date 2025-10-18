<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporting extends Model
{
    protected $fillable = [
        'reporting_name',
        'description',
    ];
}
