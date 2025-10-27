<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubFactor extends Model
{
    protected $fillable = [
        'factor_id',
        'sub_factor_name',
        'description',
    ];

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class);
    }

    public function employeeEvaluationDetails(): HasMany
    {
        return $this->hasMany(EmployeeEvaluationDetail::class);
    }
}
