<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mainstation extends Model
{
    protected $fillable = [
        'station_name',
        'description'
    ];

    public function substations()
    {
        return $this->hasMany(Substation::class, 'mainstation_id'); // Specify the foreign key explicitly if needed
    }
}
