<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupBenefit extends Model
{
    protected $fillable = [
        'earngroup_id',
        'allowance_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function earngroup()
    {
        return $this->belongsTo(Earngroup::class);
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
