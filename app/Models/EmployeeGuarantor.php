<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeGuarantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'full_name',
        'relationship',
        'mobile',
        'email',
        'occupation',
        'id_number',
        'address',
        'attachment',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
