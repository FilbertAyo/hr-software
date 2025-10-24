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
            // Use globally selected current payroll period from session if available
            $payrollPeriod = session('current_payroll_period');
            if (!$payrollPeriod) {
                // Fallback to detecting by dates, then latest
                $payrollPeriod = PayrollPeriod::where('company_id', $companyId)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->orderBy('start_date', 'desc')
                    ->first() ?? $payrollPeriods->first();
            }
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
                        $query->where('status', 'approved')
                              ->where('payroll_period_id', $payrollPeriod->id);
                    },
                    'lateRecords' => function ($query) use ($payrollPeriod) {
                        $query->where('status', 'approved')
                              ->where('payroll_period_id', $payrollPeriod->id);
                    }
                ])
                ->get();

            // Calculate statistics
            $attendanceStats['total_employees'] = $employees->count();
            $attendanceStats['employees_with_absent'] = $employees->filter(function ($emp) {
                return $emp->absentRecords->sum('absent_days') > 0;
            })->count();
            $attendanceStats['employees_with_late'] = $employees->filter(function ($emp) {
                return $emp->lateRecords->sum('late_hours') > 0;
            })->count();
            $attendanceStats['total_absent_days'] = $employees->sum(function ($emp) {
                return $emp->absentRecords->sum('absent_days');
            });
            $attendanceStats['total_late_hours'] = $employees->sum(function ($emp) {
                return $emp->lateRecords->sum('late_hours');
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
     * Show attendance details for an employee
     */
    public function show($id)
    {
        $companyId = session('selected_company_id');
        $currentPeriod = session('current_payroll_period');

        $employee = Employee::with([
            'absentRecords' => function ($q) use ($currentPeriod) {
                if ($currentPeriod) {
                    $q->where('payroll_period_id', $currentPeriod->id);
                }
            },
            'lateRecords' => function ($q) use ($currentPeriod) {
                if ($currentPeriod) {
                    $q->where('payroll_period_id', $currentPeriod->id);
                }
            }
        ])->findOrFail($id);

        return view('attendance.show', compact('employee'));
    }

    /**
     * Show bulk create page for current payroll period
     */
    public function bulkCreate(Request $request)
    {
        $companyId = session('selected_company_id');

        $currentPayrollPeriod = session('current_payroll_period');

        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->orderBy('employee_name')
            ->get();

        return view('attendance.bulk-create', compact('currentPayrollPeriod', 'employees'));
    }

    /**
     * Handle bulk store of attendance records
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'payroll_period_id' => 'required|exists:payroll_periods,id',
            'attendance_records' => 'required|array|min:1',
            'attendance_records.*.employee_id' => 'required|exists:employees,id',
            'attendance_records.*.attendance_type' => 'required|in:absent,late',
            'attendance_records.*.reason' => 'required|string|max:500',
            'attendance_records.*.notes' => 'nullable|string|max:1000',
            'attendance_records.*.absent_days' => 'nullable|integer|min:1|max:31',
            'attendance_records.*.expected_time' => 'nullable|string',
            'attendance_records.*.late_time' => 'nullable|string',
            'attendance_records.*.late_hours' => 'nullable|numeric|min:0.5|max:24',
        ]);

        try {
            DB::beginTransaction();

            $created = 0;
            foreach ($request->attendance_records as $rec) {
                $type = $rec['attendance_type'];
                if ($type === 'absent') {
                    $days = (int)($rec['absent_days'] ?? 0);
                    if ($days <= 0) continue;
                    Attendance::create([
                        'employee_id' => $rec['employee_id'],
                        'payroll_period_id' => $request->payroll_period_id,
                        'attendance_type' => 'absent',
                        'reason' => $rec['reason'] ?? null,
                        'notes' => $rec['notes'] ?? null,
                        'status' => 'approved',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                        'is_absent' => true,
                        'absent_days' => $days,
                    ]);
                    $created++;
                } elseif ($type === 'late') {
                    $hours = (float)($rec['late_hours'] ?? 0);
                    if ($hours <= 0) continue;
                    Attendance::create([
                        'employee_id' => $rec['employee_id'],
                        'payroll_period_id' => $request->payroll_period_id,
                        'attendance_type' => 'late',
                        'reason' => $rec['reason'] ?? null,
                        'notes' => $rec['notes'] ?? null,
                        'status' => 'approved',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                        'is_late' => true,
                        'expected_time' => $rec['expected_time'] ?? '08:00:00',
                        'late_time' => $rec['late_time'] ?? now()->format('H:i:s'),
                        'late_minutes' => (int)round($hours * 60),
                        'late_hours' => $hours,
                    ]);
                    $created++;
                }
            }

            DB::commit();

            return redirect()->route('absent-late.index')
                ->with('success', "Bulk saved {$created} attendance record(s) successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Error during bulk save: ' . $e->getMessage());
        }
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
            ->where('payroll_period_id', $payrollPeriod->id)
            ->get();

        foreach ($absentRecords as $record) {
            $days = $record->absent_days ?? 0;
            $totalDeduction += ($dailySalary * $days);
        }

        // Late deductions (assuming 1 hour late = 0.125 of daily salary)
        $lateRecords = $employee->lateRecords()
            ->where('status', 'approved')
            ->where('payroll_period_id', $payrollPeriod->id)
            ->get();

        foreach ($lateRecords as $record) {
            $lateHours = $record->late_hours ?? (($record->late_minutes ?? 0) / 60); // Support either field
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
     * Absent & Late Management (merged from AbsentLateController)
     */
    public function absentLateIndex()
    {
        $companyId = session('selected_company_id');

        $currentPeriod = session('current_payroll_period');

        if (!$currentPeriod) {
            return view('attendance.absentlate', [
                'currentPeriod' => null,
                'employees' => collect(),
                'attendanceRecords' => collect()
            ]);
        }

        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->orderBy('employee_name')
            ->get();

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

    public function absentLateStore(Request $request)
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

            if ($absentDays == 0 && $lateHours == 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please enter either absent days or late hours.');
            }

            $existingRecord = Attendance::where('employee_id', $request->employee_id)
                ->where('payroll_period_id', $request->payroll_period_id)
                ->whereIn('attendance_type', ['absent', 'late'])
                ->exists();

            if ($existingRecord) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Attendance record already exists for this employee in this payroll period.');
            }

            if ($absentDays > 0) {
                Attendance::create([
                    'employee_id' => $request->employee_id,
                    'payroll_period_id' => $request->payroll_period_id,
                    'attendance_type' => 'absent',
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'is_absent' => true,
                    'absent_days' => $absentDays,
                ]);
            }

            if ($lateHours > 0) {
                Attendance::create([
                    'employee_id' => $request->employee_id,
                    'payroll_period_id' => $request->payroll_period_id,
                    'attendance_type' => 'late',
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
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error recording attendance: ' . $e->getMessage());
        }
    }

    public function absentLateUpdate(Request $request, $id)
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
            return redirect()->back()->with('error', 'Error updating attendance: ' . $e->getMessage());
        }
    }

    public function absentLateDestroy($id)
    {
        try {
            $record = Attendance::findOrFail($id);
            $record->delete();

            return redirect()->back()->with('success', 'Attendance record deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting attendance: ' . $e->getMessage());
        }
    }

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

        $absentDays = Attendance::where('employee_id', $employeeId)
            ->where('payroll_period_id', $currentPeriod->id)
            ->where('attendance_type', 'absent')
            ->where('status', 'approved')
            ->sum('absent_days');

        $lateHours = Attendance::where('employee_id', $employeeId)
            ->where('payroll_period_id', $currentPeriod->id)
            ->where('attendance_type', 'late')
            ->where('status', 'approved')
            ->sum('late_hours');

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

    /**
     * Export attendance data
     */
    public function export(Request $request)
    {
        $companyId = session('selected_company_id');
        $payrollPeriodId = $request->get('payroll_period_id');
        
        if ($payrollPeriodId) {
            $payrollPeriod = PayrollPeriod::findOrFail($payrollPeriodId);
        } else {
            // Fallbacks: session current period -> active by date -> latest by start_date
            $payrollPeriod = session('current_payroll_period');
            if (!$payrollPeriod) {
                $payrollPeriod = PayrollPeriod::where('company_id', $companyId)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->orderBy('start_date', 'desc')
                    ->first();
            }
            if (!$payrollPeriod) {
                $payrollPeriod = PayrollPeriod::where('company_id', $companyId)
                    ->orderBy('start_date', 'desc')
                    ->first();
            }
            if (!$payrollPeriod) {
                return redirect()->back()->with('error', 'Please select a payroll period to export.');
            }
        }

        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->with([
                'absentRecords' => function ($query) use ($payrollPeriod) {
                    $query->where('status', 'approved')
                          ->where('payroll_period_id', $payrollPeriod->id);
                },
                'lateRecords' => function ($query) use ($payrollPeriod) {
                    $query->where('status', 'approved')
                          ->where('payroll_period_id', $payrollPeriod->id);
                }
            ])
            ->get();

        // Prepare CSV data
        $csvData = [];
        $csvData[] = ['Employee ID', 'Employee Name', 'Absent Days', 'Late Hours', 'Total Deduction'];

        foreach ($employees as $employee) {
            $absentDays = $employee->absentRecords->sum('absent_days');
            $lateHours = $employee->lateRecords->sum('late_hours');
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
