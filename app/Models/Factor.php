<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factor extends Model
{
    protected $fillable = [
        'general_factor_id',
        'factor_name',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($factor) {
            // Delete all sub-factors when factor is deleted
            $factor->subFactors()->delete();
        });
    }

    public function generalFactor(): BelongsTo
    {
        return $this->belongsTo(GeneralFactor::class);
    }

    public function subFactors(): HasMany
    {
        return $this->hasMany(SubFactor::class);
    }

    public function employeeEvaluationDetails(): HasMany
    {
        return $this->hasMany(EmployeeEvaluationDetail::class);
    }
}
