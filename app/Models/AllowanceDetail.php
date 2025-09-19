<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowanceDetail extends Model
{
    protected $fillable = [
        'allowance_id',
        'amount',
        'taxable',
        'status',
    ];

    public function allowance()
{
    return $this->belongsTo(Allowance::class);
}
}
