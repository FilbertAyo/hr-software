<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeEvaluationDetail extends Model
{
    protected $fillable = [
        'employee_evaluation_id',
        'factor_id',
        'sub_factor_id',
        'rating_scale_item_id',
        'score',
        'weighted_score',
        'comments'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'weighted_score' => 'decimal:2'
    ];

    public function employeeEvaluation(): BelongsTo
    {
        return $this->belongsTo(EmployeeEvaluation::class);
    }

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class);
    }

    public function subFactor(): BelongsTo
    {
        return $this->belongsTo(SubFactor::class);
    }

    public function ratingScaleItem(): BelongsTo
    {
        return $this->belongsTo(RatingScaleItem::class);
    }
}
