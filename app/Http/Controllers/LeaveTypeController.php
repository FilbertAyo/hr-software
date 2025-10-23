<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leavetypes = leavetype::all();

        return view("attendance.leavetype", compact("leavetypes"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'no_of_days' => 'nullable|integer|min:0',
            'no_monthly_increment' => 'boolean',
            'extra_no_of_days' => 'boolean',
            'no_of_monthly_increment' => 'nullable|numeric|min:0',
            'extra_days' => 'nullable|integer|min:0',
            'show_in_web_portal' => 'boolean',
            'status' => 'required|in:Active,Inactive',
            'description' => 'nullable|string',
            'carry_forward' => 'boolean',
            'max_carry_forward_days' => 'nullable|integer|min:0',
            'requires_approval' => 'boolean',
            'requires_documentation' => 'boolean',
            'gender_restriction' => 'required|in:All,Male,Female',
            'min_service_days' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();

        // Convert checkbox values to boolean
        $data['no_monthly_increment'] = $request->has('no_monthly_increment');
        $data['extra_no_of_days'] = $request->has('extra_no_of_days');
        $data['show_in_web_portal'] = $request->has('show_in_web_portal');
        $data['carry_forward'] = $request->has('carry_forward');
        $data['requires_approval'] = $request->has('requires_approval');
        $data['requires_documentation'] = $request->has('requires_documentation');

        $leavetype = LeaveType::create($data);

        return redirect()->back()->with('success', 'Leave type added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'leave_type_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'no_of_days' => 'nullable|integer|min:0',
            'no_monthly_increment' => 'boolean',
            'extra_no_of_days' => 'boolean',
            'no_of_monthly_increment' => 'nullable|numeric|min:0',
            'extra_days' => 'nullable|integer|min:0',
            'show_in_web_portal' => 'boolean',
            'status' => 'required|in:Active,Inactive',
            'description' => 'nullable|string',
            'carry_forward' => 'boolean',
            'max_carry_forward_days' => 'nullable|integer|min:0',
            'requires_approval' => 'boolean',
            'requires_documentation' => 'boolean',
            'gender_restriction' => 'required|in:All,Male,Female',
            'min_service_days' => 'nullable|integer|min:0',
        ]);

        // Find the leavetype by ID
        $leavetype = LeaveType::findOrFail($id);

        $data = $request->all();

        // Convert checkbox values to boolean
        $data['no_monthly_increment'] = $request->has('no_monthly_increment');
        $data['extra_no_of_days'] = $request->has('extra_no_of_days');
        $data['show_in_web_portal'] = $request->has('show_in_web_portal');
        $data['carry_forward'] = $request->has('carry_forward');
        $data['requires_approval'] = $request->has('requires_approval');
        $data['requires_documentation'] = $request->has('requires_documentation');

        // Update the leavetype
        $leavetype->update($data);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Leave type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leavetype = leavetype::find($id);

        if ($leavetype) {
            $leavetype->delete();
            return redirect()->back()->with('success', 'leavetype deleted successfully');
        } else {
            return redirect()->back()->with('error', 'leavetype not found');
        }
    }
}
