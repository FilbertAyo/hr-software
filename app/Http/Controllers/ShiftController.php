<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Employee;
use App\Models\PunchRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Display shifts management page
     */
    public function index()
    {
        $companyId = session('selected_company_id');
        
        $shifts = Shift::where('company_id', $companyId)
            ->orderBy('start_time')
            ->get();

        return view('attendance.shift', compact('shifts'));
    }

    /**
     * Store a new shift
     */
    public function store(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration_minutes' => 'nullable|integer|min:0|max:480', // Max 8 hours break
            'description' => 'nullable|string|max:500',
        ]);

        $companyId = session('selected_company_id');

        try {
            Shift::create([
                'shift_name' => $request->shift_name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'break_duration_minutes' => $request->break_duration_minutes ?? 60,
                'description' => $request->description,
                'company_id' => $companyId,
                'is_active' => true,
            ]);

            return redirect()->back()->with('success', 'Shift created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating shift: ' . $e->getMessage());
        }
    }

    /**
     * Update shift
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration_minutes' => 'nullable|integer|min:0|max:480',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $shift = Shift::findOrFail($id);
            $shift->update($request->only([
                'shift_name', 'start_time', 'end_time', 
                'break_duration_minutes', 'description'
            ]));

            return redirect()->back()->with('success', 'Shift updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating shift: ' . $e->getMessage());
        }
    }

    /**
     * Toggle shift status
     */
    public function toggleStatus($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->update(['is_active' => !$shift->is_active]);

            $status = $shift->is_active ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "Shift {$status} successfully.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating shift status: ' . $e->getMessage());
        }
    }

    /**
     * Delete shift
     */
    public function destroy($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            
            // Check if shift is assigned to any employees
            $employeeCount = Employee::where('shift_id', $id)->count();
            if ($employeeCount > 0) {
                return redirect()->back()->with('error', 
                    "Cannot delete shift. It is assigned to {$employeeCount} employee(s). Please reassign them first.");
            }

            $shift->delete();
            return redirect()->back()->with('success', 'Shift deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting shift: ' . $e->getMessage());
        }
    }

    /**
     * Punch in/out for employee
     */
    public function punch(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'action' => 'required|in:punch_in,punch_out,break_start,break_end',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        
        if (!$employee->shift_id) {
            return response()->json(['error' => 'Employee is not assigned to any shift.'], 400);
        }

        $today = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        try {
            DB::beginTransaction();

            // Get or create punch record for today
            $punchRecord = PunchRecord::firstOrCreate(
                [
                    'employee_id' => $employee->id,
                    'punch_date' => $today,
                ],
                [
                    'shift_id' => $employee->shift_id,
                ]
            );

            switch ($request->action) {
                case 'punch_in':
                    if ($punchRecord->punch_in_time) {
                        return response()->json(['error' => 'Already punched in today.'], 400);
                    }
                    $punchRecord->punch_in_time = $currentTime;
                    break;

                case 'punch_out':
                    if (!$punchRecord->punch_in_time) {
                        return response()->json(['error' => 'Must punch in first.'], 400);
                    }
                    if ($punchRecord->punch_out_time) {
                        return response()->json(['error' => 'Already punched out today.'], 400);
                    }
                    $punchRecord->punch_out_time = $currentTime;
                    break;

                case 'break_start':
                    if ($punchRecord->break_start_time) {
                        return response()->json(['error' => 'Break already started.'], 400);
                    }
                    $punchRecord->break_start_time = $currentTime;
                    break;

                case 'break_end':
                    if (!$punchRecord->break_start_time) {
                        return response()->json(['error' => 'Must start break first.'], 400);
                    }
                    if ($punchRecord->break_end_time) {
                        return response()->json(['error' => 'Break already ended.'], 400);
                    }
                    $punchRecord->break_end_time = $currentTime;
                    break;
            }

            $punchRecord->save();
            $punchRecord->updateCalculatedFields();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $request->action)) . ' recorded successfully.',
                'punch_record' => $punchRecord->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error recording punch: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get employee punch records for a date range
     */
    public function getPunchRecords(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $records = PunchRecord::where('employee_id', $request->employee_id)
            ->whereBetween('punch_date', [$request->start_date, $request->end_date])
            ->with('shift')
            ->orderBy('punch_date', 'desc')
            ->get();

        return response()->json($records);
    }

    /**
     * Bulk punch operations
     */
    public function bulkPunch(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'action' => 'required|in:punch_in,punch_out',
            'punch_time' => 'required|date_format:H:i',
        ]);

        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($request->employee_ids as $employeeId) {
            try {
                $employee = Employee::findOrFail($employeeId);
                
                if (!$employee->shift_id) {
                    $results[] = [
                        'employee_id' => $employeeId,
                        'employee_name' => $employee->employee_name,
                        'status' => 'error',
                        'message' => 'No shift assigned'
                    ];
                    $errorCount++;
                    continue;
                }

                $today = now()->toDateString();
                $punchRecord = PunchRecord::firstOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'punch_date' => $today,
                    ],
                    [
                        'shift_id' => $employee->shift_id,
                    ]
                );

                if ($request->action === 'punch_in') {
                    if ($punchRecord->punch_in_time) {
                        $results[] = [
                            'employee_id' => $employeeId,
                            'employee_name' => $employee->employee_name,
                            'status' => 'error',
                            'message' => 'Already punched in'
                        ];
                        $errorCount++;
                        continue;
                    }
                    $punchRecord->punch_in_time = $request->punch_time;
                } else {
                    if (!$punchRecord->punch_in_time) {
                        $results[] = [
                            'employee_id' => $employeeId,
                            'employee_name' => $employee->employee_name,
                            'status' => 'error',
                            'message' => 'Must punch in first'
                        ];
                        $errorCount++;
                        continue;
                    }
                    if ($punchRecord->punch_out_time) {
                        $results[] = [
                            'employee_id' => $employeeId,
                            'employee_name' => $employee->employee_name,
                            'status' => 'error',
                            'message' => 'Already punched out'
                        ];
                        $errorCount++;
                        continue;
                    }
                    $punchRecord->punch_out_time = $request->punch_time;
                }

                $punchRecord->save();
                $punchRecord->updateCalculatedFields();

                $results[] = [
                    'employee_id' => $employeeId,
                    'employee_name' => $employee->employee_name,
                    'status' => 'success',
                    'message' => ucfirst(str_replace('_', ' ', $request->action)) . ' recorded'
                ];
                $successCount++;

            } catch (\Exception $e) {
                $results[] = [
                    'employee_id' => $employeeId,
                    'employee_name' => $employee->employee_name ?? 'Unknown',
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                $errorCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Bulk punch completed. Success: {$successCount}, Errors: {$errorCount}",
            'results' => $results,
            'summary' => [
                'total' => count($request->employee_ids),
                'success' => $successCount,
                'errors' => $errorCount
            ]
        ]);
    }
}
