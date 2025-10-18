<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'joining_date',
        'mainstation_id',
        'substation_id',
        'department_id',
        'main_division_branch',
        'jobtitle_id',
        'staff_level_id',
        'wcf_no',
        'hod',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'hod' => 'boolean',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function mainstation()
    {
        return $this->belongsTo(Mainstation::class);
    }

    public function substation()
    {
        return $this->belongsTo(Substation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobtitle()
    {
        return $this->belongsTo(Jobtitle::class);
    }

    public function staffLevel()
    {
        return $this->belongsTo(StaffLevel::class, 'staff_level_id');
    }
}
