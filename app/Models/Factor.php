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
        'weight',
        'status'
    ];

    protected $casts = [
        'weight' => 'decimal:2'
    ];

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
