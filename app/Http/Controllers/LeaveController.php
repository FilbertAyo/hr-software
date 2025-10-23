<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User; // Assuming you have User model for employees
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with(['employee', 'leaveType'])->get();
        return view('attendance.leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::all(); // Adjust based on your employee model
        $leaveTypes = LeaveType::where('status', 'Active')->get();

        return view('attendance.leaves.create', compact('employees', 'leaveTypes'));
    }

    // API method to get leave types filtered by employee gender
    public function getLeaveTypesByGender($employeeId)
    {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $leaveTypes = LeaveType::where('status', 'Active')
            ->where(function($query) use ($employee) {
                $query->where('gender_restriction', 'All')
                      ->orWhere('gender_restriction', $employee->gender);
            })
            ->get();

        return response()->json($leaveTypes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_action' => 'required|in:proceed,sold,emergency,compensatory',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'no_of_days' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Rejected'
        ]);

        Leave::create($request->all());

        return redirect()->route('leaves.index')->with('success', 'Leave assigned successfully!');
    }

    public function show(Leave $leave)
    {
        return view('attendance.leaves.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        $employees = User::where('status', 'Active')->get();
        $leaveTypes = LeaveType::where('status', 'Active')->get();

        return view('attendance.leaves.edit', compact('leave', 'employees', 'leaveTypes'));
    }

    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_action' => 'required|in:proceed,sold,emergency,compensatory',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'no_of_days' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Rejected'
        ]);

        $leave->update($request->all());

        return redirect()->route('leaves.index')->with('success', 'Leave updated successfully!');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully!');
    }

    public function approve(Leave $leave)
    {
        $leave->update(['status' => 'Approved']);

        // Update employee status based on leave action
        if ($leave->leave_action === 'proceed') {
            $leave->employee->update(['employee_status' => 'onhold']);
        }

        return redirect()->route('leaves.show', $leave->id)->with('success', 'Leave approved successfully!');
    }

    public function reject(Leave $leave)
    {
        $leave->update(['status' => 'Rejected']);
        return redirect()->route('leaves.show', $leave->id)->with('success', 'Leave rejected successfully!');
    }

    /**
     * Check and update employee status for completed leaves
     */
    public function checkCompletedLeaves()
    {
        $today = now()->toDateString();

        // Find approved leaves that have ended
        $completedLeaves = Leave::where('status', 'Approved')
            ->where('leave_action', 'proceed')
            ->where('to_date', '<', $today)
            ->with('employee')
            ->get();

        foreach ($completedLeaves as $leave) {
            // Check if employee has any other active leaves
            $hasActiveLeave = Leave::where('employee_id', $leave->employee_id)
                ->where('status', 'Approved')
                ->where('leave_action', 'proceed')
                ->where('from_date', '<=', $today)
                ->where('to_date', '>=', $today)
                ->exists();

            // If no active leave, reactivate employee
            if (!$hasActiveLeave) {
                $leave->employee->update(['employee_status' => 'active']);
            }
        }

        return response()->json(['message' => 'Employee statuses updated successfully']);
    }

    // API method to get leave type details
    public function getLeaveTypeDetails($id)
    {
        $leaveType = LeaveType::find($id);

        if (!$leaveType) {
            return response()->json(['error' => 'Leave type not found'], 404);
        }

        return response()->json([
            'no_of_days' => $leaveType->no_of_days,
            'extra_days' => $leaveType->extra_days,
            'extra_no_of_days' => $leaveType->extra_no_of_days
        ]);
    }
}
