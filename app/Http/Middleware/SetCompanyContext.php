<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\PayrollPeriod;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetCompanyContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $companyId = session('selected_company_id');

            if ($companyId) {
                $company = Company::find($companyId);
                if ($company) {
                    // Set company in session for easy access
                    session(['selected_company' => $company]);

                    // Get current payroll period for the company
                    // Priority 1: Get the latest open period (not closed)
                    $openPeriod = $company->payrollPeriods()
                        ->where('status', '!=', 'closed')
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($openPeriod) {
                        session(['current_payroll_period' => $openPeriod]);
                    } else {
                        // Priority 2: If all periods are closed, get the most recent period
                        $latestPayrollPeriod = $company->payrollPeriods()
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($latestPayrollPeriod) {
                            session(['current_payroll_period' => $latestPayrollPeriod]);
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
