<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralFactor extends Model
{
    protected $fillable = [
        'factor_name',
        'description',
    ];

    public function factors(): HasMany
    {
        return $this->hasMany(Factor::class);
    }
}
