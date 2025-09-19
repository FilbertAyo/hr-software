<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        "holiday_name",
        "holiday_date",
        "status",
    ];
}
