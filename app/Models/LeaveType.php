<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $fillable = [
        'leave_type_name',
        'other_name',
        'no_of_days',
        'no_monthly_increment',
        'extra_no_of_days',
        'no_of_monthly_increment',
        'extra_days',
        'show_in_web_portal',
        'status'
    ];

    protected $casts = [
        'no_monthly_increment' => 'boolean',
        'extra_no_of_days' => 'boolean',
        'show_in_web_portal' => 'boolean',
    ];

    /**
     * Get the leaves for the leave type.
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
}
