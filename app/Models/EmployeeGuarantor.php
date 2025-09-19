<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeGuarantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'guarantor_name',
        'guarantor_phone',
        'guarantor_email',
        'guarantor_address',
        'relationship'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
