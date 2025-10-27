<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'evaluation_name',
        'start_date',
        'end_date',
        'description',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function employeeEvaluations(): HasMany
    {
        return $this->hasMany(EmployeeEvaluation::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match(strtolower($this->status)) {
            'active' => 'badge-success',
            'completed' => 'badge-info',
            'cancelled' => 'badge-secondary',
            'draft' => 'badge-warning',
            default => 'badge-secondary'
        };
    }
}

