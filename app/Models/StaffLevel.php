<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffLevel extends Model
{
    protected $fillable = [
        'level_name',
        'level_order',
        'description',
    ];
}
