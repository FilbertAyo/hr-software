<?php

namespace App\Http\Controllers;

use App\Models\DirectDeduction;
use Illuminate\Http\Request;

class DirectDeductionController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deductions = DirectDeduction::all();

        return view("deductions.direct.index", compact("deductions"));
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
            'name' => 'required|string|max:255',
            'deduction_type' => 'required|string',
            'employer_percent' => 'nullable|numeric',
            'employee_percent' => 'nullable|numeric',
            'percentage_of' => 'required|string',
            'status' => 'required|string',
            'require_member_no' => 'nullable|boolean',
        ]);

        DirectDeduction::create($request->only([
            'name',
            'deduction_type',
            'employer_percent',
            'employee_percent',
            'percentage_of',
            'status',
            'require_member_no'
        ]));

        return redirect()->back()->with('success','deduction added successfully');
    }

    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'deduction_type' => 'required|string',
            'employer_percent' => 'nullable|numeric',
            'employee_percent' => 'nullable|numeric',
            'percentage_of' => 'required|string',
            'require_member_no' => 'nullable|boolean',
            'status' => 'required|string',
        ]);

        // Find the deduction by ID
        $deduction = DirectDeduction::findOrFail($id);

        // Update the deduction fields
        $deduction->update($request->only([
            'name',
            'deduction_type',
            'employer_percent',
            'employee_percent',
            'percentage_of',
            'status',
            'require_member_no'
        ]));

        $deduction->save();

        return redirect()->back()->with('success', 'deduction updated successfully!');
    }

    public function destroy(string $id)
    {
        $deduction = DirectDeduction::find($id);

        if ($deduction) {
            $deduction->delete();
            return redirect()->back()->with('success', 'deduction deleted successfully');
        } else {
            return redirect()->back()->with('error', 'deduction not found');
        }
    }
}
