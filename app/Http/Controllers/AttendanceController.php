<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance management dashboard
     */
    public function index(Request $request)
    {
        $companyId = session('selected_company_id');

        // Get payroll periods for dropdown
        $payrollPeriods = PayrollPeriod::where('company_id', $companyId)
            ->orderBy('start_date', 'desc')
            ->get();

        // Get selected payroll period or default to current period
        $selectedPeriodId = $request->get('payroll_period_id');
        $payrollPeriod = null;

        if ($selectedPeriodId) {
            $payrollPeriod = PayrollPeriod::find($selectedPeriodId);
        } else {
            $payrollPeriod = $payrollPeriods->first();
        }

        // Get employees with attendance data for the selected period
        $employees = collect();
        $attendanceStats = [
            'total_employees' => 0,
            'employees_with_absent' => 0,
            'employees_with_late' => 0,
            'total_absent_days' => 0,
            'total_late_hours' => 0,
            'total_deduction_amount' => 0
        ];

        if ($payrollPeriod) {
            $employees = Employee::where('company_id', $companyId)
                ->where('employee_status', 'active')
                ->with([
                    'absentRecords' => function ($query) use ($payrollPeriod) {
                        $query->where('status', 'approved');
                    },
                    'lateRecords' => function ($query) use ($payrollPeriod) {
                        $query->where('status', 'approved');
                    }
                ])
                ->get();

            // Calculate statistics
            $attendanceStats['total_employees'] = $employees->count();
            $attendanceStats['employees_with_absent'] = $employees->filter(function ($emp) {
                return $emp->absentRecords->count() > 0;
            })->count();
            $attendanceStats['employees_with_late'] = $employees->filter(function ($emp) {
                return $emp->lateRecords->count() > 0;
            })->count();
            $attendanceStats['total_absent_days'] = $employees->sum(function ($emp) {
                return $emp->absentRecords->count();
            });
            $attendanceStats['total_late_hours'] = $employees->sum(function ($emp) {
                return $emp->lateRecords->sum('late_minutes') / 60; // Convert minutes to hours
            });
            $attendanceStats['total_deduction_amount'] = $this->calculateTotalAttendanceDeductions($employees, $payrollPeriod);
        }

        return view('attendance.index', compact(
            'employees',
            'payrollPeriod',
            'payrollPeriods',
            'attendanceStats'
        ));
    }

    /**
     * Show form to add attendance records
     */
    public function create(Request $request)
    {
        $companyId = session('selected_company_id');

        // Get current payroll period from session (set by middleware)
        $currentPayrollPeriod = session('current_payroll_period');

        if (!$currentPayrollPeriod) {
            return redirect()->route('absent-late.index')
                ->with('error', 'No current payroll period found. Please create a payroll period first.');
        }

        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->orderBy('employee_name')
            ->get();

        return view('attendance.create', compact('employees', 'currentPayrollPeriod'));
    }

    /**
     * Store attendance records
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'absent_days' => 'nullable|integer|min:0|max:31',
            'late_hours' => 'nullable|numeric|min:0|max:24',
            'payroll_period_id' => 'required|exists:payroll_periods,id',
        ]);

        // Ensure at least one attendance type is provided
        $absentDays = $request->absent_days ?? 0;
        $lateHours = $request->late_hours ?? 0;

        if ($absentDays == 0 && $lateHours == 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please enter either absent days or late hours.');
        }

        try {
            DB::beginTransaction();

            $payrollPeriod = PayrollPeriod::findOrFail($request->payroll_period_id);
            $employee = Employee::findOrFail($request->employee_id);

            // Check if record already exists for the same date
            $existingRecord = Attendance::where('employee_id', $request->employee_id)
                ->whereIn('attendance_type', ['absent', 'late'])
                ->where('status', 'approved')
                ->first();

            if ($existingRecord) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Attendance record already exists for this employee on the selected date.');
            }

            // Create absent record if absent days > 0
            if ($absentDays > 0) {
                Attendance::create([
                    'employee_id' => $request->employee_id,
                    'attendance_type' => 'absent',
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'is_absent' => true,
                ]);
            }

            // Create late record if late hours > 0
            if ($lateHours > 0) {
                Attendance::create([
                    'employee_id' => $request->employee_id,
                    'attendance_type' => 'late',
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'is_late' => true,
                    'late_time' => now()->format('H:i:s'),
                    'expected_time' => '08:00:00',
                    'late_minutes' => $lateHours * 60, // Convert hours to minutes
                ]);
            }

            DB::commit();

            $message = [];
            if ($absentDays > 0) $message[] = "{$absentDays} absent day(s)";
            if ($lateHours > 0) $message[] = "{$lateHours} late hour(s)";

            return redirect()->route('absent-late.index')
                ->with('success', 'Attendance record added successfully: ' . implode(', ', $message));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error adding attendance record: ' . $e->getMessage());
        }
    }



    public function bulkCreate(Request $request)
    {
        $companyId = session('selected_company_id');

        // Get current payroll period from session (set by middleware)
        $currentPayrollPeriod = session('current_payroll_period');

        if (!$currentPayrollPeriod) {
            return redirect()->route('absent-late.index')
                ->with('error', 'No current payroll period found. Please create a payroll period first.');
        }

        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->orderBy('employee_name')
            ->get();

        return view('attendance.bulk-create', compact('employees', 'currentPayrollPeriod'));
    }

    /**
     * Store bulk attendance records
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'attendance_records' => 'required|array|min:1',
            'attendance_records.*.employee_id' => 'required|exists:employees,id',
            'attendance_records.*.attendance_type' => 'required|in:absent,late',
            'attendance_records.*.reason' => 'required|string|max:255',
            'attendance_records.*.notes' => 'nullable|string|max:500',
            'attendance_records.*.absent_days' => 'required_if:attendance_records.*.attendance_type,absent|integer|min:1|max:31',
            'attendance_records.*.late_hours' => 'required_if:attendance_records.*.attendance_type,late|numeric|min:0.5|max:24',
            'attendance_records.*.expected_time' => 'required_if:attendance_records.*.attendance_type,late|date_format:H:i',
            'attendance_records.*.late_time' => 'required_if:attendance_records.*.attendance_type,late|date_format:H:i',
            'payroll_period_id' => 'required|exists:payroll_periods,id',
        ]);

        try {
            DB::beginTransaction();

            $payrollPeriod = PayrollPeriod::findOrFail($request->payroll_period_id);
            $processedCount = 0;

            foreach ($request->attendance_records as $record) {
                $activityData = [
                    'employee_id' => $record['employee_id'],
                    'attendance_type' => $record['attendance_type'],
                    'reason' => $record['reason'],
                    'notes' => $record['notes'] ?? null,
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ];

                if ($record['attendance_type'] === 'absent') {
                    $activityData = array_merge($activityData, [
                        'is_absent' => true,
                    ]);
                } else {
                    $activityData = array_merge($activityData, [
                        'is_late' => true,
                        'late_time' => $record['late_time'],
                        'expected_time' => $record['expected_time'],
                        'late_minutes' => $record['late_hours'] * 60, // Convert hours to minutes
                    ]);
                }

                Attendance::create($activityData);
                $processedCount++;
            }

            DB::commit();

            return redirect()->route('absent-late.index')
                ->with('success', "Successfully added {$processedCount} attendance records.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error adding attendance records: ' . $e->getMessage());
        }
    }

    /**
     * Show attendance details for an employee
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        return view('attendance.show', compact('employee'));
    }

    /**
     * Delete attendance record
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'exists:employee_activities,id'
        ]);

        try {
            DB::beginTransaction();

            $deletedCount = 0;
            foreach ($request->activity_ids as $activityId) {
                $activity = Attendance::findOrFail($activityId);

                // Only allow deletion of pending or approved records
                if (in_array($activity->status, ['pending', 'approved'])) {
                    $activity->delete();
                    $deletedCount++;
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Successfully deleted {$deletedCount} attendance records.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting attendance records: ' . $e->getMessage());
        }
    }

    /**
     * Get attendance deductions for payroll integration
     */
    public function getAttendanceDeductions($employeeId, $payrollPeriodId)
    {
        $payrollPeriod = PayrollPeriod::findOrFail($payrollPeriodId);
        $employee = Employee::findOrFail($employeeId);

        return $this->calculateAttendanceDeductions($employee, $payrollPeriod);
    }

    /**
     * Calculate attendance deductions for an employee in a payroll period
     */
    private function calculateAttendanceDeductions($employee, $payrollPeriod)
    {
        $totalDeduction = 0;
        $dailySalary = $employee->basic_salary / $this->getWorkingDaysInPeriod($payrollPeriod);

        // Absent deductions
        $absentRecords = $employee->absentRecords()
            ->where('status', 'approved')
            ->get();

        foreach ($absentRecords as $record) {
            $totalDeduction += $dailySalary; // Each absent record counts as 1 day
        }

        // Late deductions (assuming 1 hour late = 0.125 of daily salary)
        $lateRecords = $employee->lateRecords()
            ->where('status', 'approved')
            ->get();

        foreach ($lateRecords as $record) {
            $lateHours = ($record->late_minutes ?? 0) / 60; // Convert minutes to hours
            $lateDeduction = ($dailySalary / 8) * $lateHours; // 8 hours per day
            $totalDeduction += $lateDeduction;
        }

        return round($totalDeduction, 2);
    }

    /**
     * Calculate total attendance deductions for all employees
     */
    private function calculateTotalAttendanceDeductions($employees, $payrollPeriod)
    {
        $totalDeduction = 0;

        foreach ($employees as $employee) {
            $totalDeduction += $this->calculateAttendanceDeductions($employee, $payrollPeriod);
        }

        return $totalDeduction;
    }

    /**
     * Get working days in a payroll period (excluding weekends)
     */
    private function getWorkingDaysInPeriod($payrollPeriod)
    {
        $start = Carbon::parse($payrollPeriod->start_date);
        $end = Carbon::parse($payrollPeriod->end_date);

        $workingDays = 0;
        while ($start->lte($end)) {
            if ($start->isWeekday()) {
                $workingDays++;
            }
            $start->addDay();
        }

        return $workingDays > 0 ? $workingDays : 1; // Prevent division by zero
    }

    /**
     * Export attendance data
     */
    public function export(Request $request)
    {
        $companyId = session('selected_company_id');
        $payrollPeriodId = $request->get('payroll_period_id');

        if (!$payrollPeriodId) {
            return redirect()->back()->with('error', 'Please select a payroll period to export.');
        }

        $payrollPeriod = PayrollPeriod::findOrFail($payrollPeriodId);

        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->with([
                'absentRecords' => function ($query) use ($payrollPeriod) {
                    $query->where('status', 'approved');
                },
                'lateRecords' => function ($query) use ($payrollPeriod) {
                    $query->where('status', 'approved');
                }
            ])
            ->get();

        // Prepare CSV data
        $csvData = [];
        $csvData[] = ['Employee ID', 'Employee Name', 'Absent Days', 'Late Hours', 'Total Deduction'];

        foreach ($employees as $employee) {
            $absentDays = $employee->absentRecords->count();
            $lateHours = $employee->lateRecords->sum('late_minutes') / 60; // Convert minutes to hours
            $deduction = $this->calculateAttendanceDeductions($employee, $payrollPeriod);

            $csvData[] = [
                $employee->employeeID,
                $employee->employee_name,
                $absentDays,
                $lateHours,
                $deduction
            ];
        }

        // Generate CSV file
        $filename = 'attendance_report_' . $payrollPeriod->period_name . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
