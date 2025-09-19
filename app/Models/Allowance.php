<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $fillable = [
        'name',
    ];

    public function allowanceDetails()
{
    return $this->hasMany(AllowanceDetail::class);
}
}
