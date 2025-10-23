<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeOtherDeduction;
use App\Models\OtherDeductionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OtherDeductionController extends Controller
{
    /**
     * Display deduction types page
     */
    public function types()
    {
        $deduction_types = OtherDeductionType::orderBy('deduction_type')->get();

        return view('deductions.others.types', compact('deduction_types'));
    }

    /**
     * Display employee deductions page
     */
    public function employeeDeductions()
    {
        $deduction_types = OtherDeductionType::orderBy('deduction_type')->get();
        $employee_deductions = EmployeeOtherDeduction::with(['employee', 'deductionType', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();
        $employees = Employee::where('employee_status', 'active')
            ->orderBy('employee_name')
            ->get();

        return view('deductions.others.employee_deductions', compact('deduction_types', 'employee_deductions', 'employees'));
    }

    /**
     * Store a new deduction type
     */
    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'deduction_type' => 'required|string|max:255|unique:other_deduction_types,deduction_type',
            'requires_document' => 'boolean',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $validated['requires_document'] = $request->has('requires_document');
        $validated['status'] = $request->has('status') ? true : true;

        OtherDeductionType::create($validated);

        return redirect()->route('other-deductions.types')
            ->with('success', 'Deduction type created successfully.');
    }

    /**
     * Update a deduction type
     */
    public function updateType(Request $request, $id)
    {
        $deduction_type = OtherDeductionType::findOrFail($id);

        $validated = $request->validate([
            'deduction_type' => 'required|string|max:255|unique:other_deduction_types,deduction_type,' . $id,
            'requires_document' => 'boolean',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $validated['requires_document'] = $request->has('requires_document');
        $validated['status'] = $request->has('status') ? true : true;

        $deduction_type->update($validated);

        return redirect()->route('other-deductions.types')
            ->with('success', 'Deduction type updated successfully.');
    }

    /**
     * Delete a deduction type
     */
    public function destroyType($id)
    {
        $deduction_type = OtherDeductionType::findOrFail($id);

        // Check if there are any employee deductions using this type
        if ($deduction_type->employeeDeductions()->count() > 0) {
            return redirect()->route('other-deductions.types')
                ->with('error', 'Cannot delete deduction type. It is being used by employee deductions.');
        }

        $deduction_type->delete();

        return redirect()->route('other-deductions.types')
            ->with('success', 'Deduction type deleted successfully.');
    }

    /**
     * Store a new employee deduction
     */
    public function storeDeduction(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'other_deduction_type_id' => 'required|exists:other_deduction_types,id',
            'amount' => 'required|numeric|min:0',
            'deduction_date' => 'required|date',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        // Handle file upload
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('deduction_documents', 'public');
            $validated['document_path'] = $path;
        }

        EmployeeOtherDeduction::create($validated);

        return redirect()->route('other-deductions.employee-deductions')
            ->with('success', 'Employee deduction created successfully.');
    }

    /**
     * Update an employee deduction
     */
    public function updateDeduction(Request $request, $id)
    {
        $deduction = EmployeeOtherDeduction::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'other_deduction_type_id' => 'required|exists:other_deduction_types,id',
            'amount' => 'required|numeric|min:0',
            'deduction_date' => 'required|date',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,processed',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        // Handle file upload
        if ($request->hasFile('document')) {
            // Delete old file if exists
            if ($deduction->document_path) {
                Storage::disk('public')->delete($deduction->document_path);
            }
            $path = $request->file('document')->store('deduction_documents', 'public');
            $validated['document_path'] = $path;
        }

        $deduction->update($validated);

        return redirect()->route('other-deductions.employee-deductions')
            ->with('success', 'Employee deduction updated successfully.');
    }

    /**
     * Approve a deduction
     */
    public function approveDeduction($id)
    {
        $deduction = EmployeeOtherDeduction::findOrFail($id);

        $deduction->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->route('other-deductions.employee-deductions')
            ->with('success', 'Deduction approved successfully.');
    }

    /**
     * Reject a deduction
     */
    public function rejectDeduction($id)
    {
        $deduction = EmployeeOtherDeduction::findOrFail($id);

        $deduction->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->route('other-deductions.employee-deductions')
            ->with('success', 'Deduction rejected successfully.');
    }

    /**
     * Delete an employee deduction
     */
    public function destroyDeduction($id)
    {
        $deduction = EmployeeOtherDeduction::findOrFail($id);

        // Delete associated file if exists
        if ($deduction->document_path) {
            Storage::disk('public')->delete($deduction->document_path);
        }

        $deduction->delete();

        return redirect()->route('other-deductions.employee-deductions')
            ->with('success', 'Employee deduction deleted successfully.');
    }
}
