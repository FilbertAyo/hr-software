<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Main Employee Model
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'biometric_id',
        'card_id',
        'date_of_birth',
        'mobile_no',
        'telephone_no',
        'email',
        'tin_no',
        'gender',
        'marital_status',
        'nationality',
        'religion',
        'nida_card_no',
        'employee_type',
        'employee_status',
        'residential_status',
        'type_of_tax',
        'address',
        'photo_path',
        'payment_method',
        'wcf_no',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Relationships
    public function department()
    {
        return $this->hasOne(EmployeeDepartment::class);
    }
    public function jobtitle()
    {
        return $this->hasOne(Jobtitle::class);
    }

    // public function bankDetails()
    // {
    //     return $this->hasMany(EmployeeBankDetail::class);
    // }

    public function primaryBankDetail()
    {
        return $this->hasOne(EmployeeBankDetail::class)->where('is_primary', true);
    }

    // public function salaryDetails()
    // {
    //     return $this->hasOne(EmployeeSalaryDetail::class);
    // }

    public function pensionDetails()
    {
        return $this->hasOne(EmployeePensionDetail::class);
    }

    public function nhifDetails()
    {
        return $this->hasOne(EmployeeNhifDetail::class);
    }

    public function overtimeDetails()
    {
        return $this->hasOne(EmployeeOvertimeDetail::class);
    }

    public function timingDetails()
    {
        return $this->hasOne(EmployeeTimingDetail::class);
    }

    public function leaveDetails()
    {
        return $this->hasMany(EmployeeLeaveDetail::class);
    }

    public function qualifications()
    {
        return $this->hasMany(EmployeeQualification::class);
    }

    public function guarantors()
    {
        return $this->hasMany(EmployeeGuarantor::class);
    }

    public function nextOfKin()
    {
        return $this->hasMany(EmployeeNextOfKin::class);
    }

    public function paymentDetails()
    {
        return $this->hasOne(EmployeePaymentDetail::class);
    }

    public function deductionDetails()
    {
        return $this->hasMany(EmployeeDeductionDetail::class);
    }

    // public function portalDetails()
    // {
    //     return $this->hasOne(EmployeePortalDetail::class);
    // }

    public function absentDetails()
    {
        return $this->hasMany(EmployeeAbsentDetail::class);
    }

    public function lateDetails()
    {
        return $this->hasMany(EmployeeLateDetail::class);
    }
  public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function salaryDetails()
    {
        return $this->hasOne(EmployeeBankDetail::class, 'employee_id'); // Make sure foreign key is correct
    }

    public function bankDetails()
    {
        return $this->hasOne(EmployeeBankDetail::class, 'employee_id');
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
        $salaryDetails = $this->salaryDetails()->first(); // Use first() method

        if (!$salaryDetails) {
            return 0;
        }

        return $salaryDetails->basic_salary +
               ($salaryDetails->housing_allowance ?? 0) +
               ($salaryDetails->transport_allowance ?? 0) +
               ($salaryDetails->medical_allowance ?? 0);
    }
}
