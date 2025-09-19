<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mainstation extends Model
{
    protected $fillable = [
        'main_station',
        'created_by'
    ];

    public function substations()
    {
        return $this->hasMany(Substation::class, 'main_station_id'); // Specify the foreign key explicitly if needed
    }
}
