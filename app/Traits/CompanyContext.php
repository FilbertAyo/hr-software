<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\PayrollPeriod;
use Illuminate\Support\Facades\Session;

trait CompanyContext
{
    /**
     * Get the currently selected company
     */
    public function getCurrentCompany(): ?Company
    {
        $companyId = Session::get('selected_company_id');

        if (!$companyId) {
            return null;
        }

        return Company::find($companyId);
    }

    /**
     * Get the current payroll period for the selected company
     * First tries to find an active period (current date within range)
     * If none found, returns the latest available period
     */
    public function getCurrentPayrollPeriod(): ?PayrollPeriod
    {
        $company = $this->getCurrentCompany();

        if (!$company) {
            return null;
        }

        // First try to find an active period (current date within range)
        $activePeriod = $company->payrollPeriods()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        // If no active period found, get the latest available period
        if (!$activePeriod) {
            $activePeriod = $this->getLatestPayrollPeriod();
        }

        return $activePeriod;
    }

    /**
     * Get the latest payroll period for the selected company
     */
    public function getLatestPayrollPeriod(): ?PayrollPeriod
    {
        $company = $this->getCurrentCompany();

        if (!$company) {
            return null;
        }

        return $company->payrollPeriods()
            ->orderBy('start_date', 'desc')
            ->first();
    }
}
