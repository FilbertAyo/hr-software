<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeEvaluation extends Model
{
    protected $fillable = [
        'evaluation_id',
        'employee_id',
        'evaluator_id',
        'total_score',
        'final_rating',
        'overall_comments',
        'status',
        'evaluation_date'
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
        'final_rating' => 'decimal:2',
        'evaluation_date' => 'date'
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function employeeEvaluationDetails(): HasMany
    {
        return $this->hasMany(EmployeeEvaluationDetail::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Completed' => 'badge-success',
            'Approved' => 'badge-primary',
            'In Progress' => 'badge-warning',
            default => 'badge-secondary'
        };
    }
}
