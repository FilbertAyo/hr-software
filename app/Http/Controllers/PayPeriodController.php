<?php

namespace App\Http\Controllers;

use App\Models\PayPeriod;
use App\Models\PayrollPeriod;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayPeriodController extends Controller
{

    public function index(Request $request)
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('company.index')->with('error', 'Please select a company first.');
        }

        $company = Company::findOrFail($companyId);
        $payperiods = PayrollPeriod::where('company_id', $companyId)
            ->orderBy('start_date', 'desc')
            ->get();

        // Get the next available month/year for creating new payroll periods
        $nextMonthYear = $this->getNextAvailableMonthYear($companyId, $company);

        return view('payroll.payperiod', compact('payperiods', 'nextMonthYear', 'company'));
    }

    public function store(Request $request)
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->back()->with('error', 'Please select a company first.');
        }

        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer|min:2000',
        ]);

        $month = $validated['month'];
        $year  = $validated['year'];

        // Check if payroll period already exists for this month/year
        $existingPeriod = PayrollPeriod::where('company_id', $companyId)
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->first();

        if ($existingPeriod) {
            return redirect()->back()->with('error', 'Payroll period for ' . Carbon::createFromDate($year, $month, 1)->format('F Y') . ' already exists.');
        }

        $periodName = Carbon::createFromDate($year, $month, 1)->format('F Y');
        $startDate  = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate    = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Close all previous periods before creating new one
        PayrollPeriod::where('company_id', $companyId)
            ->where('status', '!=', 'closed')
            ->update(['status' => 'closed']);

        $newPeriod = PayrollPeriod::create([
            'period_name' => $periodName,
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'status'     => 'draft',
            'company_id' => $companyId,
        ]);

        // Update session to set the newly created period as current
        session(['current_payroll_period' => $newPeriod]);

        return redirect()->back()->with('success', 'Pay period created successfully. Previous periods have been closed.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'payperiod' => 'required|string|max:255',
        ]);

        $payperiod = PayrollPeriod::findOrFail($id);

        // Only allow editing if period is in draft status
        if ($payperiod->status !== 'draft') {
            return redirect()->back()->with('error', 'Cannot edit closed or processed payroll periods.');
        }

        $payperiod->period_name = $request->input('payperiod');
        $payperiod->save();

        return redirect()->back()->with('success', 'Payroll period updated successfully!');
    }


    /**
     * Get the next available month/year for creating new payroll periods
     */
    private function getNextAvailableMonthYear($companyId, $company)
    {
        // Get the latest payroll period for this company
        $latestPeriod = PayrollPeriod::where('company_id', $companyId)
            ->orderBy('start_date', 'desc')
            ->first();

        if ($latestPeriod) {
            // If there's an existing period, get the next month
            $nextDate = Carbon::parse($latestPeriod->start_date)->addMonth();
        } else {
            // If no periods exist, start from company's start month/year
            $startMonth = $company->start_month;
            $startYear = $company->start_year;

            if (!$startMonth || !$startYear) {
                // Fallback to current month/year if company doesn't have start date
                $nextDate = now()->startOfMonth();
            } else {
                $monthNumber = Carbon::parse($startMonth . ' 1')->month;
                $nextDate = Carbon::createFromDate($startYear, $monthNumber, 1);
            }
        }

        return [
            'month' => $nextDate->month,
            'year' => $nextDate->year,
            'month_name' => $nextDate->format('F'),
            'is_first_period' => $latestPeriod === null
        ];
    }
}
