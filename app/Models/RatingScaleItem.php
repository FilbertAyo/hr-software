<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RatingScaleItem extends Model
{
    protected $fillable = [
        'rating_scale_id',
        'name',
        'score',
        'description',
        'sort_order'
    ];

    protected $casts = [
        'score' => 'decimal:2'
    ];

    public function ratingScale(): BelongsTo
    {
        return $this->belongsTo(RatingScale::class);
    }

    public function employeeEvaluationDetails(): HasMany
    {
        return $this->hasMany(EmployeeEvaluationDetail::class);
    }
}
