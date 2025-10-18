<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Substation extends Model
{
    protected $fillable = [
        'substation_name',
        'mainstation_id'
    ];

    public function mainstation()
    {
        return $this->belongsTo(Mainstation::class, 'mainstation_id'); // Specify foreign key explicitly if needed
    }
}
