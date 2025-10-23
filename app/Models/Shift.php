<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'break_duration_minutes',
        'is_active',
        'description',
        'company_id'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function punchRecords()
    {
        return $this->hasMany(PunchRecord::class);
    }

    /**
     * Get shift duration in hours
     */
    public function getDurationAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        // Handle overnight shifts
        if ($end->lessThan($start)) {
            $end->addDay();
        }
        
        return $start->diffInHours($end);
    }

    /**
     * Get working hours (duration minus break)
     */
    public function getWorkingHoursAttribute()
    {
        return $this->duration - ($this->break_duration_minutes / 60);
    }

    /**
     * Check if current time is within shift hours
     */
    public function isWithinShiftHours($time = null)
    {
        $time = $time ? \Carbon\Carbon::parse($time) : now();
        $currentTime = $time->format('H:i:s');
        
        if ($this->end_time > $this->start_time) {
            // Same day shift
            return $currentTime >= $this->start_time && $currentTime <= $this->end_time;
        } else {
            // Overnight shift
            return $currentTime >= $this->start_time || $currentTime <= $this->end_time;
        }
    }
}