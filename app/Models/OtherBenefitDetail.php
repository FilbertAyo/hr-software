<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherBenefitDetail extends Model
{
    protected $fillable = [
        'other_benefit_id',
        'amount',
        'benefit_date',
        'taxable',
        'status',
    ];

    protected $casts = [
        'benefit_date' => 'date',
        'amount' => 'decimal:2',
        'taxable' => 'boolean',
    ];

    public function otherBenefit()
    {
        return $this->belongsTo(OtherBenefit::class);
    }

    /**
     * Get all employees assigned to this other benefit detail
     */
    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'employee_other_benefit_details',
            'other_benefit_detail_id',
            'employee_id'
        )->withPivot('status')->withTimestamps();
    }

    /**
     * Get active employees only
     */
    public function activeEmployees()
    {
        return $this->employees()->wherePivot('status', 'active');
    }
}

