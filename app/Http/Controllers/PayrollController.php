<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all payroll periods for dropdown filtered by company
        $companyId = session('selected_company_id');
        $payrollPeriods = PayrollPeriod::where('company_id', $companyId)
            ->orderBy('start_date', 'desc')->get();

        // Get selected payroll period or default to latest
        $selectedPeriodId = $request->get('payroll_period_id');

        if ($selectedPeriodId) {
            $payrollPeriod = PayrollPeriod::find($selectedPeriodId);
        } else {
            $payrollPeriod = $payrollPeriods->first(); // Get latest period
        }

        // If no payroll period exists, show empty state
        if (!$payrollPeriod) {
            return view("payroll.index", [
                'employees' => collect(),
                'payrollPeriod' => null,
                'payrollPeriods' => collect(),
                'payrollStats' => [
                    'total_employees' => 0,
                    'processed_employees' => 0,
                    'pending_employees' => 0,
                    'total_gross' => 0,
                    'total_deductions' => 0,
                    'total_net' => 0
                ]
            ]);
        }

        // Get employees with their salary details and existing payroll if any, filtered by company
        $employees = Employee::where('company_id', $companyId)
            ->with(['salaryDetails', 'payrolls' => function($query) use ($payrollPeriod) {
                $query->where('payroll_period_id', $payrollPeriod->id);
            }])->get();

        // Get payroll statistics for this period
        $payrollStats = [
            'total_employees' => $employees->count(),
            'processed_employees' => $employees->filter(function($employee) {
                return $employee->payrolls->where('status', 'processed')->count() > 0;
            })->count(),
            'pending_employees' => $employees->filter(function($employee) {
                return $employee->payrolls->where('status', 'pending')->count() > 0 || $employee->payrolls->count() == 0;
            })->count(),
            'total_gross' => $payrollPeriod->total_gross_amount,
            'total_deductions' => $payrollPeriod->total_deductions,
            'total_net' => $payrollPeriod->total_net_amount
        ];

        return view("payroll.index", compact("employees", "payrollPeriod", "payrollPeriods", "payrollStats"));
    }

    /**
     * Process payroll for selected employees
     */
    public function processSelected(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'payroll_period_id' => 'required|exists:payroll_periods,id'
        ]);

        try {
            DB::beginTransaction();

            $payrollPeriod = PayrollPeriod::findOrFail($request->payroll_period_id);
            $processedCount = 0;

            foreach ($request->employee_ids as $employeeId) {
                $employee = Employee::with(['SalaryDetails', 'advances'])->findOrFail($employeeId);

                // Check if payroll already exists for this period and delete it for reprocessing
                $existingPayroll = Payroll::where('employee_id', $employeeId)
                    ->where('payroll_period_id', $payrollPeriod->id)
                    ->first();

                if ($existingPayroll) {
                    $existingPayroll->delete(); // Delete existing payroll for reprocessing
                }

                // Get salary information from salary details or default
                $salaryDetails = $employee->salaryDetails()->first(); // Use first() to get single record
                $basicSalary = $salaryDetails->basic_salary ?? 0;
                $housingAllowance = $salaryDetails->housing_allowance ?? 0;
                $transportAllowance = $salaryDetails->transport_allowance ?? 0;
                $medicalAllowance = $salaryDetails->medical_allowance ?? 0;

                $totalAllowances = $housingAllowance + $transportAllowance + $medicalAllowance;

                // Calculate tax (example: 10% of basic salary)
                $taxDeduction = $basicSalary * 0.10;

                // Calculate insurance (example: 5% of basic salary)
                $insuranceDeduction = $basicSalary * 0.05;

                // Get advance amount for this employee in this period
                $advanceAmount = $employee->advances()
                    ->where('payroll_period_id', $payrollPeriod->id)
                    ->where('status', 'approved')
                    ->sum('advance_amount') ?? 0;

                // Create payroll record
                $payroll = Payroll::create([
                    'employee_id' => $employeeId,
                    'payroll_period_id' => $payrollPeriod->id,
                    'basic_salary' => $basicSalary,
                    'allowances' => $totalAllowances,
                    'overtime_amount' => 0,
                    'bonus' => 0,
                    'tax_deduction' => $taxDeduction,
                    'insurance_deduction' => $insuranceDeduction,
                    'loan_deduction' => 0,
                    'other_deductions' => $advanceAmount,
                    'status' => 'processed',
                    'processed_at' => now()
                ]);

                $processedCount++;
            }

            // Update payroll period statistics
            $this->updatePayrollPeriodStats($payrollPeriod);

            DB::commit();

            return redirect()->back()->with('success', "Payroll processed successfully for {$processedCount} employees.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error processing payroll: ' . $e->getMessage());
        }
    }

    /**
     * Process all employees payroll
     */
    public function processAll(Request $request)
    {
        $request->validate([
            'payroll_period_id' => 'required|exists:payroll_periods,id'
        ]);

        $employees = Employee::with('SalaryDetails')->get();
        $employeeIds = $employees->pluck('id')->toArray();

        $request->merge(['employee_ids' => $employeeIds]);

        return $this->processSelected($request);
    }

    /**
     * Update payroll period statistics
     */
    private function updatePayrollPeriodStats(PayrollPeriod $payrollPeriod)
    {
        $payrolls = $payrollPeriod->payrolls()->where('status', 'processed')->get();

        $payrollPeriod->update([
            'total_employees' => $payrolls->count(),
            'total_gross_amount' => $payrolls->sum('gross_salary'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_net_amount' => $payrolls->sum('net_salary'),
            'processed_at' => now(),
            'processed_by' => null,
            'status' => $payrolls->count() > 0 ? 'completed' : 'draft'
        ]);
    }

    /**
     * Show payroll details for a specific employee and period
     */
    public function show($id)
    {
        $payroll = Payroll::with(['employee', 'payrollPeriod', 'deductions', 'allowanceDetails'])
            ->findOrFail($id);

        return view('payroll.show', compact('payroll'));
    }

    /**
     * Delete/Cancel payroll record
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'payroll_ids' => 'required|array',
            'payroll_ids.*' => 'exists:payrolls,id'
        ]);

        try {
            DB::beginTransaction();

            $deletedCount = 0;
            foreach ($request->payroll_ids as $payrollId) {
                $payroll = Payroll::findOrFail($payrollId);
                $payrollPeriod = $payroll->payrollPeriod;

                $payroll->delete();
                $deletedCount++;

                // Update period stats
                $this->updatePayrollPeriodStats($payrollPeriod);
            }

            DB::commit();

            return redirect()->back()->with('success', "Successfully cancelled payroll for {$deletedCount} employees.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error cancelling payroll: ' . $e->getMessage());
        }
    }

    /**
     * Get payroll periods for dropdown
     */
    public function getPeriods()
    {
        $periods = PayrollPeriod::orderBy('start_date', 'desc')->get();
        return response()->json($periods);
    }
}
