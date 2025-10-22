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




    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function employeeEarngroups()
    {
        return $this->hasMany(EmployeeEarngroup::class);
    }

    public function earngroups()
    {
        return $this->belongsToMany(Earngroup::class, 'employee_earngroups')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get all other benefit details assigned to this employee
     */
    public function otherBenefitDetails()
    {
        return $this->belongsToMany(
            OtherBenefitDetail::class,
            'employee_other_benefit_details',
            'employee_id',
            'other_benefit_detail_id'
        )->withPivot('status')->withTimestamps();
    }

    /**
     * Get active other benefit details only
     */
    public function activeOtherBenefitDetails()
    {
        return $this->otherBenefitDetails()->wherePivot('status', 'active');
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

    // Get employee's taxable allowances from earngroups
    public function getTaxableAllowancesFromEarngroups()
    {
        $taxableAllowances = 0;

        // Get all active earngroups assigned to this employee
        $activeEarngroups = $this->earngroups()
            ->wherePivot('status', 'active')
            ->get();

        foreach ($activeEarngroups as $earngroup) {
            // Get all active group benefits (allowances) for this earngroup
            $groupBenefits = $earngroup->groupBenefits()
                ->where('status', 'active')
                ->with('allowance.allowanceDetails')
                ->get();

            foreach ($groupBenefits as $groupBenefit) {
                if ($groupBenefit->allowance && $groupBenefit->allowance->allowanceDetails->count() > 0) {
                    foreach ($groupBenefit->allowance->allowanceDetails as $detail) {
                        if ($detail->status == 'active' && $detail->taxable) {
                            if ($detail->calculation_type == 'amount') {
                                $taxableAllowances += $detail->amount ?? 0;
                            } elseif ($detail->calculation_type == 'percentage') {
                                // Calculate percentage of basic salary
                                $taxableAllowances += ($this->basic_salary * ($detail->percentage ?? 0)) / 100;
                            }
                        }
                    }
                }
            }
        }

        return $taxableAllowances;
    }

    // Get employee's non-taxable allowances from earngroups
    public function getNonTaxableAllowancesFromEarngroups()
    {
        $nonTaxableAllowances = 0;

        // Get all active earngroups assigned to this employee
        $activeEarngroups = $this->earngroups()
            ->wherePivot('status', 'active')
            ->get();

        foreach ($activeEarngroups as $earngroup) {
            // Get all active group benefits (allowances) for this earngroup
            $groupBenefits = $earngroup->groupBenefits()
                ->where('status', 'active')
                ->with('allowance.allowanceDetails')
                ->get();

            foreach ($groupBenefits as $groupBenefit) {
                if ($groupBenefit->allowance && $groupBenefit->allowance->allowanceDetails->count() > 0) {
                    foreach ($groupBenefit->allowance->allowanceDetails as $detail) {
                        if ($detail->status == 'active' && !$detail->taxable) {
                            if ($detail->calculation_type == 'amount') {
                                $nonTaxableAllowances += $detail->amount ?? 0;
                            } elseif ($detail->calculation_type == 'percentage') {
                                // Calculate percentage of basic salary
                                $nonTaxableAllowances += ($this->basic_salary * ($detail->percentage ?? 0)) / 100;
                            }
                        }
                    }
                }
            }
        }

        return $nonTaxableAllowances;
    }

    // Get employee's total allowances from earngroups (for backward compatibility)
    public function getTotalAllowancesFromEarngroups()
    {
        return $this->getTaxableAllowancesFromEarngroups() + $this->getNonTaxableAllowancesFromEarngroups();
    }

    /**
     * Get employee's taxable other benefits
     * @param string|null $startDate Optional start date filter
     * @param string|null $endDate Optional end date filter
     * @return float
     */
    public function getTaxableOtherBenefits($startDate = null, $endDate = null)
    {
        $query = $this->activeOtherBenefitDetails()->where('taxable', true);

        if ($startDate) {
            $query->whereDate('benefit_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('benefit_date', '<=', $endDate);
        }

        return $query->sum('amount') ?? 0;
    }

    /**
     * Get employee's non-taxable other benefits
     * @param string|null $startDate Optional start date filter
     * @param string|null $endDate Optional end date filter
     * @return float
     */
    public function getNonTaxableOtherBenefits($startDate = null, $endDate = null)
    {
        $query = $this->activeOtherBenefitDetails()->where('taxable', false);

        if ($startDate) {
            $query->whereDate('benefit_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('benefit_date', '<=', $endDate);
        }

        return $query->sum('amount') ?? 0;
    }

    /**
     * Get employee's total other benefits
     * @param string|null $startDate Optional start date filter
     * @param string|null $endDate Optional end date filter
     * @return float
     */
    public function getTotalOtherBenefits($startDate = null, $endDate = null)
    {
        return $this->getTaxableOtherBenefits($startDate, $endDate) +
               $this->getNonTaxableOtherBenefits($startDate, $endDate);
    }

    // Get employee's total salary including TAXABLE allowances only (for gross salary calculation)
    public function getTotalSalary()
    {
        // Use taxable earngroups allowances if available, otherwise fall back to individual allowances
        $taxableAllowancesFromEarngroups = $this->getTaxableAllowancesFromEarngroups();

        if ($taxableAllowancesFromEarngroups > 0) {
            return $this->basic_salary + $taxableAllowancesFromEarngroups;
        }

        // Fallback to individual allowances for backwards compatibility
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
