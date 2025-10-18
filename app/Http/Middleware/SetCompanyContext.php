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
                    $currentPayrollPeriod = $company->payrollPeriods()
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    if ($currentPayrollPeriod) {
                        session(['current_payroll_period' => $currentPayrollPeriod]);
                    } else {
                        // If no current period, get the latest one
                        $latestPayrollPeriod = $company->payrollPeriods()
                            ->orderBy('start_date', 'desc')
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
