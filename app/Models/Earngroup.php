<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Earngroup extends Model
{
    protected $fillable = [
        'earngroup_name',
        'description',
    ];

    public function groupBenefits()
    {
        return $this->hasMany(GroupBenefit::class);
    }
}
