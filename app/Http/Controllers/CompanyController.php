<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PayrollPeriod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = company::all();

        return view("company.setup.index", compact("companies"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("company.setup.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'start_month' => 'nullable|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'start_year' => 'nullable|integer|min:1900|max:2099',
        ]);

        $company = Company::create($request->all());

        $message = 'Company added successfully';

        // Create the first payroll period if start month and year are provided
        if ($company->start_month && $company->start_year) {
            $this->createFirstPayrollPeriod($company);
            $message .= ' and first payroll period (' . $company->start_month . ' ' . $company->start_year . ') created automatically';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Create the first payroll period for a new company
     */
    private function createFirstPayrollPeriod($company)
    {
        $monthNumber = \Carbon\Carbon::parse($company->start_month . ' 1')->month;
        $periodName = \Carbon\Carbon::createFromDate($company->start_year, $monthNumber, 1)->format('F Y');
        $startDate = \Carbon\Carbon::createFromDate($company->start_year, $monthNumber, 1)->startOfMonth();
        $endDate = \Carbon\Carbon::createFromDate($company->start_year, $monthNumber, 1)->endOfMonth();

        PayrollPeriod::create([
            'period_name' => $periodName,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'draft',
            'company_id' => $company->id,
        ]);
    }

    public function show(string $id)
    {
        $company = Company::find($id);
        return view('company.setup.show', compact('company'));
    }
    public function edit(string $id)
    {
        $company = Company::find($id);
        return view('company.setup.edit', compact('company'));
    }

    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'company' => 'required|string|max:255',
        ]);

        // Find the company by ID
        $company = company::findOrFail($id);

        // Update the company's name
        $company->company = $request->input('company');

        // Save the updated company
        $company->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'company updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = company::find($id);

        if ($company) {
            $company->delete();
            return redirect()->back()->with('success', 'company deleted successfully');
        } else {
            return redirect()->back()->with('error', 'company not found');
        }
    }

    /**
     * Switch to a different company
     */
    public function switch(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $companyId = $request->input('company_id');
        $user = Auth::user();

        // Check if user has access to the selected company
        if (!$user->companies()->where('company_id', $companyId)->exists()) {
            return redirect()->back()->with('error', 'You do not have access to the selected company.');
        }

        // Update session with new company
        session(['selected_company_id' => $companyId]);

        $company = Company::find($companyId);
        session(['selected_company' => $company]);

        // Get current payroll period for the new company
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

        return redirect()->back()->with('success', 'Company switched successfully.');
    }

}
