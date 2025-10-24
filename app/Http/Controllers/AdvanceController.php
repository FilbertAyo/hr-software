<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Traits\CompanyContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdvanceController extends Controller
{
    use CompanyContext;
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = session('selected_company_id');

        // Use the global payroll period from session (set by middleware and ViewServiceProvider)
        $currentPayrollPeriod = session('current_payroll_period');

        if (!$currentPayrollPeriod) {
            return redirect()->back()->with('error', 'No payroll period found. Please select a company first.');
        }

        $advances = Advance::with(['employee', 'payrollPeriod'])
            ->whereHas('employee', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->where('payroll_period_id', $currentPayrollPeriod->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $employees = Employee::where('company_id', $companyId)->get();

        return view("advance.index", compact("advances", "employees", "currentPayrollPeriod"));
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'advance_amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        // Use the global payroll period from session (set by middleware and ViewServiceProvider)
        $currentPayrollPeriod = session('current_payroll_period');

        // If no payroll period in session, this should not happen with proper middleware setup
        if (!$currentPayrollPeriod) {
            return redirect()->back()->with('error', 'No payroll period found. Please select a company first.');
        }

        // Check if employee has advance option enabled
        if (!$employee->hasAdvanceOption()) {
            return redirect()->back()->with('error', 'Employee does not have advance option enabled.');
        }

        // Get advance limit
        $advanceLimit = $employee->getAdvanceLimit();

        if ($request->advance_amount > $advanceLimit) {
            return redirect()->back()->with('error', "Advance amount cannot exceed the limit of " . number_format($advanceLimit, 2));
        }

        // Check if employee already has an advance for this payroll period
        $existingAdvance = Advance::where('employee_id', $request->employee_id)
            ->where('payroll_period_id', $currentPayrollPeriod->id)
            ->first();

        if ($existingAdvance) {
            return redirect()->back()->with('error', 'Employee already has an advance for this payroll period.');
        }

        DB::beginTransaction();
        try {
            // Create advance
            $advance = Advance::create([
                'employee_id' => $request->employee_id,
                'payroll_period_id' => $currentPayrollPeriod->id,
                'advance_amount' => $request->advance_amount,
                'advance_date' => now()->format('Y-m-d'),
                'reason' => $request->reason,
                'status' => 'pending'
            ]);

            // Persist suggested advance amount directly on employee record
            $employee->advance_salary = $request->advance_amount;
            $employee->save();

            DB::commit();
            return redirect()->back()->with('success', 'Advance created successfully. The advance amount will appear as a suggestion in future payroll periods.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Advance creation failed: ' . $e->getMessage(), [
                'employee_id' => $request->employee_id,
                'advance_amount' => $request->advance_amount,
                'payroll_period_id' => $currentPayrollPeriod->id ?? 'null',
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to create advance: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'advance_amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
        ]);

        $advance = Advance::findOrFail($id);
        $employee = Employee::findOrFail($request->employee_id);

        // Check if employee has advance option enabled
        if (!$employee->hasAdvanceOption()) {
            return redirect()->back()->with('error', 'Employee does not have advance option enabled.');
        }

        // Get advance limit
        $advanceLimit = $employee->getAdvanceLimit();

        if ($request->advance_amount > $advanceLimit) {
            return redirect()->back()->with('error', "Advance amount cannot exceed the limit of " . number_format($advanceLimit, 2));
        }

        // Check if employee already has an advance for this payroll period (excluding current advance)
        $currentPayrollPeriod = session('current_payroll_period');
        if ($currentPayrollPeriod) {
            $existingAdvance = Advance::where('employee_id', $request->employee_id)
                ->where('payroll_period_id', $currentPayrollPeriod->id)
                ->where('id', '!=', $id)
                ->first();

            if ($existingAdvance) {
                return redirect()->back()->with('error', 'Employee already has an advance for this payroll period.');
            }
        }

        DB::beginTransaction();
        try {
            // Update advance
            $advance->update([
                'employee_id' => $request->employee_id,
                'advance_amount' => $request->advance_amount,
                'reason' => $request->reason,
            ]);

            // Persist suggested advance amount directly on employee record
            $employee->advance_salary = $request->advance_amount;
            $employee->save();

            DB::commit();
            return redirect()->back()->with('success', 'Advance updated successfully. The advance amount will appear as a suggestion in future payroll periods.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Advance update failed: ' . $e->getMessage(), [
                'advance_id' => $id,
                'employee_id' => $request->employee_id,
                'advance_amount' => $request->advance_amount,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to update advance: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $advance = Advance::find($id);

        if ($advance) {
            DB::beginTransaction();
            try {
                // Clear employee's advance_salary suggestion
                $employee = $advance->employee;
                $employee->advance_salary = 0.00; // decimal
                $employee->save();

                $advance->delete();
                DB::commit();
                return redirect()->back()->with('success', 'Advance deleted successfully. The advance suggestion has been cleared.');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Failed to delete advance: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Advance not found');
        }
    }

    /**
     * Approve a single advance
     */
    public function approve(string $id)
    {
        $advance = Advance::findOrFail($id);

        if ($advance->isApproved()) {
            return redirect()->back()->with('warning', 'This advance is already approved.');
        }

        if ($advance->isRejected()) {
            return redirect()->back()->with('error', 'Cannot approve a rejected advance.');
        }

        DB::beginTransaction();
        try {
            $advance->update(['status' => 'approved']);
            DB::commit();
            return redirect()->back()->with('success', 'Advance approved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Advance approval failed: ' . $e->getMessage(), [
                'advance_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to approve advance: ' . $e->getMessage());
        }
    }

    /**
     * Approve all pending advances
     */
    public function approveAll()
    {
        $companyId = session('selected_company_id');
        $currentPayrollPeriod = session('current_payroll_period');

        if (!$currentPayrollPeriod) {
            return redirect()->back()->with('error', 'No payroll period found. Please select a company first.');
        }

        $pendingAdvances = Advance::whereHas('employee', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->where('payroll_period_id', $currentPayrollPeriod->id)
            ->pending()
            ->get();

        if ($pendingAdvances->isEmpty()) {
            return redirect()->back()->with('warning', 'No pending advances to approve.');
        }

        DB::beginTransaction();
        try {
            $approvedCount = 0;
            foreach ($pendingAdvances as $advance) {
                $advance->update(['status' => 'approved']);
                $approvedCount++;
            }

            DB::commit();
            return redirect()->back()->with('success', "Successfully approved {$approvedCount} advance(s).");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk advance approval failed: ' . $e->getMessage(), [
                'company_id' => $companyId,
                'payroll_period_id' => $currentPayrollPeriod->id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to approve advances: ' . $e->getMessage());
        }
    }

    /**
     * Reject a single advance
     */
    public function reject(string $id)
    {
        $advance = Advance::findOrFail($id);

        if ($advance->isApproved()) {
            return redirect()->back()->with('error', 'Cannot reject an approved advance.');
        }

        if ($advance->isRejected()) {
            return redirect()->back()->with('warning', 'This advance is already rejected.');
        }

        DB::beginTransaction();
        try {
            $advance->update(['status' => 'rejected']);
            DB::commit();
            return redirect()->back()->with('success', 'Advance rejected successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Advance rejection failed: ' . $e->getMessage(), [
                'advance_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to reject advance: ' . $e->getMessage());
        }
    }

    /**
     * Get employee advance limit for AJAX request
     */
    public function getEmployeeAdvanceLimit(Request $request)
    {
        $employeeId = $request->employee_id;
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $advanceLimit = $employee->getAdvanceLimit();
        $hasAdvanceOption = $employee->hasAdvanceOption();

        return response()->json([
            'advance_limit' => $advanceLimit,
            'has_advance_option' => $hasAdvanceOption,
            'advance_limit_formatted' => number_format($advanceLimit, 2)
        ]);
    }
}
