<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsentLateController extends Controller
{
    /**
     * Show the create form
     */
    public function create()
    {
        $companyId = session('selected_company_id');
        
        // Get current payroll period
        $currentPayrollPeriod = PayrollPeriod::where('company_id', $companyId)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        // Get all active employees
        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->orderBy('employee_name')
            ->get();

        return view('attendance.create', compact('currentPayrollPeriod', 'employees'));
    }

    /**
     * Display the Absent & Late management page
     */
    public function index()
    {
        $companyId = session('selected_company_id');
        
        // Get current payroll period
        $currentPeriod = PayrollPeriod::where('company_id', $companyId)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$currentPeriod) {
            return view('attendance.absentlate', [
                'currentPeriod' => null,
                'employees' => collect(),
                'attendanceRecords' => collect()
            ]);
        }

        // Get all active employees
        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->orderBy('employee_name')
            ->get();

        // Get attendance records for current period
        $attendanceRecords = Attendance::with('employee')
            ->where('payroll_period_id', $currentPeriod->id)
            ->whereIn('attendance_type', ['absent', 'late'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('employee_id');

        return view('attendance.absentlate', compact(
            'currentPeriod', 
            'employees', 
            'attendanceRecords'
        ));
    }

    /**
     * Store absent/late record
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_period_id' => 'required|exists:payroll_periods,id',
            'absent_days' => 'nullable|integer|min:0|max:31',
            'late_hours' => 'nullable|numeric|min:0|max:24',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'employee_id.required' => 'Please select an employee.',
            'payroll_period_id.required' => 'Payroll period is required.',
            'reason.required' => 'Please provide a reason for absence or lateness.',
        ]);    
    
        try {
            DB::beginTransaction();

            $absentDays = $request->absent_days ?? 0;
            $lateHours = $request->late_hours ?? 0;

            // Validate at least one value is provided
            if ($absentDays == 0 && $lateHours == 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please enter either absent days or late hours.');
            }

            // Check if record already exists for this employee in this period
            $existingRecord = Attendance::where('employee_id', $request->employee_id)
                ->where('payroll_period_id', $request->payroll_period_id)
                ->whereIn('attendance_type', ['absent', 'late'])
                ->exists();

            if ($existingRecord) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Attendance record already exists for this employee in this payroll period.');
            }

            // Create absent record if absent days > 0
            if ($absentDays > 0) {
                Attendance::create([
                    'employee_id' => $request->employee_id,
                    'payroll_period_id' => $request->payroll_period_id,
                    'attendance_type' => 'absent',
                    // Removed attendance_date - not using it
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'is_absent' => true,
                    'absent_days' => $absentDays,
                ]);
            }

            // Create late record if late hours > 0
            if ($lateHours > 0) {
                Attendance::create([
                    'employee_id' => $request->employee_id,
                    'payroll_period_id' => $request->payroll_period_id,
                    'attendance_type' => 'late',
                    // Removed attendance_date - not using it
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'is_late' => true,
                    'late_time' => now()->format('H:i:s'),
                    'expected_time' => '08:00:00',
                    'late_minutes' => $lateHours * 60,
                    'late_hours' => $lateHours,
                ]);
            }

            DB::commit();

            $message = [];
            if ($absentDays > 0) $message[] = "{$absentDays} absent day(s)";
            if ($lateHours > 0) $message[] = "{$lateHours} late hour(s)";
            
            return redirect()->route('absent-late.index')
                ->with('success', 'Attendance recorded successfully: ' . implode(', ', $message));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error recording attendance: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error recording attendance: ' . $e->getMessage());
        }
    }

    /**
     * Update absent/late record
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'absent_days' => 'nullable|integer|min:0|max:31',
            'late_hours' => 'nullable|numeric|min:0|max:24',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $record = Attendance::findOrFail($id);

            if ($record->attendance_type === 'absent') {
                $record->update([
                    'absent_days' => $request->absent_days ?? 0,
                    'reason' => $request->reason ?? $record->reason,
                ]);
            } elseif ($record->attendance_type === 'late') {
                $lateHours = $request->late_hours ?? 0;
                $record->update([
                    'late_hours' => $lateHours,
                    'late_minutes' => $lateHours * 60,
                    'reason' => $request->reason ?? $record->reason,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Attendance record updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating attendance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating attendance: ' . $e->getMessage());
        }
    }

    /**
     * Delete absent/late record
     */
    public function destroy($id)
    {
        try {
            $record = Attendance::findOrFail($id);
            $record->delete();

            return redirect()->back()->with('success', 'Attendance record deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Error deleting attendance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting attendance: ' . $e->getMessage());
        }
    }

    /**
     * Get employee attendance summary for current period
     */
    public function getEmployeeSummary($employeeId)
    {
        $companyId = session('selected_company_id');
        
        $currentPeriod = PayrollPeriod::where('company_id', $companyId)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$currentPeriod) {
            return response()->json(['error' => 'No current payroll period found.'], 404);
        }

        $employee = Employee::findOrFail($employeeId);

        // Count total absent days
        $absentDays = Attendance::where('employee_id', $employeeId)
            ->where('payroll_period_id', $currentPeriod->id)
            ->where('attendance_type', 'absent')
            ->where('status', 'approved')
            ->sum('absent_days');

        // Sum total late hours
        $lateHours = Attendance::where('employee_id', $employeeId)
            ->where('payroll_period_id', $currentPeriod->id)
            ->where('attendance_type', 'late')
            ->where('status', 'approved')
            ->sum('late_hours');

        // Calculate deductions
        $workingDays = $employee->working_days_per_month ?? 26;
        $workingHours = $employee->working_hours_per_day ?? 8;
        
        $dailySalary = $employee->basic_salary / $workingDays;
        $hourlySalary = $dailySalary / $workingHours;
        
        $absentDeduction = $dailySalary * $absentDays;
        $lateDeduction = $hourlySalary * $lateHours;
        $totalDeduction = $absentDeduction + $lateDeduction;

        return response()->json([
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->employee_name,
                'employee_id' => $employee->employeeID,
                'basic_salary' => $employee->basic_salary,
                'working_days_per_month' => $workingDays,
                'working_hours_per_day' => $workingHours,
            ],
            'attendance' => [
                'absent_days' => $absentDays,
                'late_hours' => round($lateHours, 2),
            ],
            'deductions' => [
                'daily_salary' => round($dailySalary, 2),
                'hourly_salary' => round($hourlySalary, 2),
                'absent_deduction' => round($absentDeduction, 2),
                'late_deduction' => round($lateDeduction, 2),
                'total_deduction' => round($totalDeduction, 2),
            ]
        ]);
    }
}