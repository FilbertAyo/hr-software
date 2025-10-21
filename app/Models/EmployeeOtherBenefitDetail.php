<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOtherBenefitDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'other_benefit_detail_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function otherBenefitDetail()
    {
        return $this->belongsTo(OtherBenefitDetail::class);
    }
}

