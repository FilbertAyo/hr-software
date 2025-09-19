<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobtitle extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'pay_grade',
        'department'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
