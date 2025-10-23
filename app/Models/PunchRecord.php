<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PunchRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'punch_date',
        'punch_in_time',
        'punch_out_time',
        'break_start_time',
        'break_end_time',
        'total_working_hours',
        'overtime_hours',
        'status',
        'notes'
    ];

    protected $casts = [
        'punch_date' => 'date',
        'punch_in_time' => 'datetime:H:i:s',
        'punch_out_time' => 'datetime:H:i:s',
        'break_start_time' => 'datetime:H:i:s',
        'break_end_time' => 'datetime:H:i:s',
        'total_working_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Calculate total working hours
     */
    public function calculateWorkingHours()
    {
        if (!$this->punch_in_time || !$this->punch_out_time) {
            return 0;
        }

        $punchIn = Carbon::parse($this->punch_date . ' ' . $this->punch_in_time);
        $punchOut = Carbon::parse($this->punch_date . ' ' . $this->punch_out_time);
        
        // Handle overnight shifts
        if ($punchOut->lessThan($punchIn)) {
            $punchOut->addDay();
        }

        $totalMinutes = $punchIn->diffInMinutes($punchOut);
        
        // Subtract break time if recorded
        if ($this->break_start_time && $this->break_end_time) {
            $breakStart = Carbon::parse($this->punch_date . ' ' . $this->break_start_time);
            $breakEnd = Carbon::parse($this->punch_date . ' ' . $this->break_end_time);
            
            if ($breakEnd->lessThan($breakStart)) {
                $breakEnd->addDay();
            }
            
            $breakMinutes = $breakStart->diffInMinutes($breakEnd);
            $totalMinutes -= $breakMinutes;
        }

        return round($totalMinutes / 60, 2);
    }

    /**
     * Calculate overtime hours
     */
    public function calculateOvertimeHours()
    {
        $workingHours = $this->calculateWorkingHours();
        $expectedHours = $this->shift->working_hours;
        
        return max(0, $workingHours - $expectedHours);
    }

    /**
     * Check if employee is late
     */
    public function isLate()
    {
        if (!$this->punch_in_time) {
            return false;
        }

        $punchInTime = Carbon::parse($this->punch_date . ' ' . $this->punch_in_time);
        $expectedStartTime = Carbon::parse($this->punch_date . ' ' . $this->shift->start_time);
        
        return $punchInTime->greaterThan($expectedStartTime);
    }

    /**
     * Get late minutes
     */
    public function getLateMinutes()
    {
        if (!$this->isLate()) {
            return 0;
        }

        $punchInTime = Carbon::parse($this->punch_date . ' ' . $this->punch_in_time);
        $expectedStartTime = Carbon::parse($this->punch_date . ' ' . $this->shift->start_time);
        
        return $punchInTime->diffInMinutes($expectedStartTime);
    }

    /**
     * Update calculated fields
     */
    public function updateCalculatedFields()
    {
        $this->total_working_hours = round($this->calculateWorkingHours(), 2);
        $this->overtime_hours = round($this->calculateOvertimeHours(), 2);
        
        // Update status based on attendance
        if (!$this->punch_in_time) {
            $this->status = 'absent';
        } elseif ($this->isLate()) {
            $this->status = 'late';
        } elseif ($this->total_working_hours < ($this->shift->working_hours / 2)) {
            $this->status = 'half_day';
        } else {
            $this->status = 'present';
        }
        
        $this->save();
    }
}