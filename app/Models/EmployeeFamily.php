<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFamily extends Model
{
    use HasFactory;

    protected $table = 'employee_families';

    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'relationship_id',
        'mobile',
        'home_mobile',
        'email',
        'date_of_birth',
        'age',
        'postal_address',
        'district',
        'ward',
        'division',
        'region',
        'tribe',
        'religion',
        'attachment',
        'is_dependant',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_dependant' => 'boolean',
        'age' => 'integer',
    ];

    /**
     * Get the employee that owns the family member
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the relationship type
     */
    public function relationship()
    {
        return $this->belongsTo(Relation::class, 'relationship_id');
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        $names = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name
        ]);
        
        return implode(' ', $names);
    }
}
