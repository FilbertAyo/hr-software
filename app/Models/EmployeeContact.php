<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'contact_type',
        'name',
        'phone',
        'email',
        'address',
        'relationship',
        'qualification_details',
        'institution',
        'start_date',
        'end_date',
        'grade',
        'guarantor_occupation',
        'guarantor_employer',
        'kin_priority',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scope for different contact types
    public function scopeGuarantors($query)
    {
        return $query->where('contact_type', 'guarantor');
    }

    public function scopeNextOfKin($query)
    {
        return $query->where('contact_type', 'next_of_kin');
    }

    public function scopeQualifications($query)
    {
        return $query->where('contact_type', 'qualification');
    }
}
