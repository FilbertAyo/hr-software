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
     * Priority 1: Get period with 'draft' status (actively being worked on)
     * Priority 2: Get period where current date falls within date range
     * Priority 3: Get the latest available period
     */
    public function getCurrentPayrollPeriod(): ?PayrollPeriod
    {
        $company = $this->getCurrentCompany();

        if (!$company) {
            return null;
        }

        // Priority 1: Get period with 'draft' status
        $draftPeriod = $company->payrollPeriods()
            ->where('status', 'draft')
            ->orderBy('start_date', 'desc')
            ->first();

        if ($draftPeriod) {
            return $draftPeriod;
        }

        // Priority 2: Get period where current date falls within date range
        $activePeriod = $company->payrollPeriods()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($activePeriod) {
            return $activePeriod;
        }

        // Priority 3: Get the latest available period
        return $this->getLatestPayrollPeriod();
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
