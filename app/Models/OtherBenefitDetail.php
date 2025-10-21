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
        'apply_to_all',
        'employee_ids',
    ];

    protected $casts = [
        'benefit_date' => 'date',
        'amount' => 'decimal:2',
        'taxable' => 'boolean',
        'apply_to_all' => 'boolean',
        'employee_ids' => 'array',
    ];

    public function otherBenefit()
    {
        return $this->belongsTo(OtherBenefit::class);
    }
}

