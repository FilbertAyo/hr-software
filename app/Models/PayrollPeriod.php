<?php

// Model 1: PayrollPeriod.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
        'status',
        'total_gross_amount',
        'total_deductions',
        'total_net_amount',
        'total_employees',
        'processed_at',
        'processed_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'processed_at' => 'datetime',
        'total_gross_amount' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net_amount' => 'decimal:2'
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['draft', 'processing']);
    }
}
