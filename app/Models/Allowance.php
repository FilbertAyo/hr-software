<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $fillable = [
        'allowance_name',
        'description',
    ];

    public function allowanceDetails()
    {
        return $this->hasMany(AllowanceDetail::class);
    }

    public function groupBenefits()
    {
        return $this->hasMany(GroupBenefit::class);
    }
}
