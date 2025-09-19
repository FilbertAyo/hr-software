<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Substation extends Model
{
    protected $fillable = [
        'sub_station',
        'main_station_id'
    ];

    public function mainstation()
    {
        return $this->belongsTo(Mainstation::class, 'main_station_id'); // Specify foreign key explicitly if needed
    }
}
