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

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_action' => 'required|in:proceed_on_leave,sold_leave',
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
        return view('leaves.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        $employees = User::where('status', 'Active')->get();
        $leaveTypes = LeaveType::where('status', 'Active')->get();

        return view('leaves.edit', compact('leave', 'employees', 'leaveTypes'));
    }

    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_action' => 'required|in:proceed_on_leave,sold_leave',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'no_of_days' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Rejected'
        ]);

        $leave->update($request->all());

        return redirect()->route('attendance.leaves.index')->with('success', 'Leave updated successfully!');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully!');
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
