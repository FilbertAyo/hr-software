<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Main Employee Model
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        // Basic Information
        'employee_name',
        'biometricID',
        'employeeID',
        'date_of_birth',
        'mobile_no',
        'email',
        'tin_no',
        'gender',
        'marital_status',
        'nationality_id',
        'religion_id',
        'residential_status',
        'nida_no',
        'employee_type',
        'employee_status',
        'tax_rate_id',
        'payment_method',
        'wcf_no',
        'address',
        'photo_path',
        'company_id',
        'registration_step',

        // Salary Details
        'basic_salary',
        'advance_option',
        'advance_percentage',
        'advance_salary',
        'paye_exempt',
        'housing_allowance',
        'transport_allowance',
        'medical_allowance',

        // Bank Details
        'is_primary_bank',
        'bank_id',
        'account_no',

        // Pension Details
        'pension_id',
        'pension_details',
        'employee_pension_amount',
        'employer_pension_amount',
        'employee_pension_no',

        // NHIF Details
        'nhif',
        'nhif_fixed_amount',
        'nhif_amount',

        // Overtime Details
        'overtime_given',
        'overtime_rate_weekday',
        'overtime_rate_saturday',
        'overtime_rate_weekend_holiday',
        'overtime_do_not_start_immediately',
        'weekday_overtime_starts_after',
        'saturday_overtime_starts_after',
        'sunday_holiday_overtime_starts_after',

        // Timing Details
        'use_office_timing',
        'use_biometrics',

        // Payment Details
        'payments',
        'dynamic_payments_paid_in_rates',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'basic_salary' => 'decimal:2',
        'advance_percentage' => 'decimal:2',
        'advance_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'employee_pension_amount' => 'decimal:2',
        'employer_pension_amount' => 'decimal:2',
        'nhif_amount' => 'decimal:2',
        'overtime_rate_weekday' => 'decimal:2',
        'overtime_rate_saturday' => 'decimal:2',
        'overtime_rate_weekend_holiday' => 'decimal:2',
        'weekday_overtime_starts_after' => 'datetime',
        'saturday_overtime_starts_after' => 'datetime',
        'sunday_holiday_overtime_starts_after' => 'datetime',
        'advance_option' => 'boolean',
        'paye_exempt' => 'boolean',
        'is_primary_bank' => 'boolean',
        'pension_details' => 'boolean',
        'nhif' => 'boolean',
        'nhif_fixed_amount' => 'boolean',
        'overtime_given' => 'boolean',
        'overtime_do_not_start_immediately' => 'boolean',
        'use_office_timing' => 'boolean',
        'use_biometrics' => 'boolean',
        'payments' => 'boolean',
        'dynamic_payments_paid_in_rates' => 'boolean',
    ];

    // Relationships
    public function contacts()
    {
        return $this->hasMany(EmployeeContact::class);
    }

    public function activities()
    {
        return $this->hasMany(EmployeeActivity::class);
    }

    // Department relationship
    public function department()
    {
        return $this->hasOne(EmployeeDepartment::class);
    }

    // Reference table relationships
    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function pension()
    {
        return $this->belongsTo(DirectDeduction::class, 'pension_id');
    }

    // Specific contact type relationships
    public function guarantors()
    {
        return $this->contacts()->guarantors();
    }

    public function nextOfKin()
    {
        return $this->contacts()->nextOfKin();
    }

    public function qualifications()
    {
        return $this->contacts()->qualifications();
    }

    // Specific activity type relationships
    public function leaves()
    {
        return $this->activities()->leaves();
    }

    public function deductions()
    {
        return $this->activities()->deductions();
    }

    public function absentRecords()
    {
        return $this->activities()->absent();
    }

    public function lateRecords()
    {
        return $this->activities()->late();
    }

    public function departmentAssignments()
    {
        return $this->activities()->department();
    }
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function bankDetails()
    {
        return $this->hasMany(EmployeeBankDetail::class, 'employee_id');
    }

    public function pensionDetails()
    {
        return $this->hasOne(EmployeePensionDetail::class, 'employee_id');
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    // Get current month payroll
    public function getCurrentPayroll($payrollPeriodId = null)
    {
        $query = $this->payrolls();

        if ($payrollPeriodId) {
            $query->where('payroll_period_id', $payrollPeriodId);
        } else {
            // Get current month payroll
            $query->whereHas('payrollPeriod', function($q) {
                $q->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
            });
        }

        return $query->first();
    }

    // Check if employee has processed payroll for a period
    public function hasProcessedPayroll($payrollPeriodId)
    {
        return $this->payrolls()
            ->where('payroll_period_id', $payrollPeriodId)
            ->where('status', 'processed')
            ->exists();
    }

    // Get employee's total salary including allowances
    public function getTotalSalary()
    {
        return $this->basic_salary +
               ($this->housing_allowance ?? 0) +
               ($this->transport_allowance ?? 0) +
               ($this->medical_allowance ?? 0);
    }

    // Get employee's advance limit based on advance percentage
    public function getAdvanceLimit()
    {
        if (!$this->advance_option || !$this->advance_percentage) {
            return 0;
        }

        $grossSalary = $this->getTotalSalary();
        return ($grossSalary * $this->advance_percentage) / 100;
    }

    // Check if employee has advance option enabled
    public function hasAdvanceOption()
    {
        return $this->advance_option;
    }
}
