<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobtitle extends Model
{
    protected $fillable = [
        'job_title',
        'description',
        'occupation_id',
        'pay_grade_id',
        'department_id',
    ];

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function paygrade()
    {
        return $this->belongsTo(Paygrade::class, 'pay_grade_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
