<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'evaluation_name',
        'department_id',
        'general_factor_id',
        'rating_scale_id',
        'evaluation_period_start',
        'evaluation_period_end',
        'description',
        'status'
    ];

    protected $casts = [
        'evaluation_period_start' => 'date',
        'evaluation_period_end' => 'date'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function generalFactor(): BelongsTo
    {
        return $this->belongsTo(GeneralFactor::class);
    }

    public function ratingScale(): BelongsTo
    {
        return $this->belongsTo(RatingScale::class);
    }

    public function employeeEvaluations(): HasMany
    {
        return $this->hasMany(EmployeeEvaluation::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Active' => 'badge-success',
            'Completed' => 'badge-info',
            'Inactive' => 'badge-secondary',
            default => 'badge-warning'
        };
    }
}
