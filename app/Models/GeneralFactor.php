<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralFactor extends Model
{
    protected $fillable = [
        'general_factor_name',
        'description',
        'status'
    ];

    public function factors(): HasMany
    {
        return $this->hasMany(Factor::class);
    }

    public function subFactors(): HasMany
    {
        return $this->hasMany(SubFactor::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
