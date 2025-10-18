<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Company;
use App\Models\PayrollPeriod;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share company and payroll period data with all views
        View::composer('*', function ($view) {
            $selectedCompany = session('selected_company');
            $currentPayrollPeriod = session('current_payroll_period');

            $view->with([
                'selectedCompany' => $selectedCompany,
                'currentPayrollPeriod' => $currentPayrollPeriod
            ]);
        });
    }
}
