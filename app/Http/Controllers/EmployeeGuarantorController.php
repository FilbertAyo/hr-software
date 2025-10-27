<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeGuarantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeGuarantorController extends Controller
{
    /**
     * Store a newly created guarantor
     */
    public function store(Request $request, $employeeId)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'occupation' => 'nullable|string|max:255',
            'id_number' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $employee = Employee::findOrFail($employeeId);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('guarantor-attachments', 'public');
            $validatedData['attachment'] = $attachmentPath;
        }

        // Create guarantor
        $guarantor = new EmployeeGuarantor($validatedData);
        $guarantor->employee_id = $employee->id;
        $guarantor->save();

        return redirect()->back()->with('success', 'Guarantor added successfully.');
    }

    /**
     * Update the specified guarantor
     */
    public function update(Request $request, $employeeId, $guarantorId)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'occupation' => 'nullable|string|max:255',
            'id_number' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $guarantor = EmployeeGuarantor::where('employee_id', $employeeId)
            ->where('id', $guarantorId)
            ->firstOrFail();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($guarantor->attachment) {
                Storage::disk('public')->delete($guarantor->attachment);
            }
            $attachmentPath = $request->file('attachment')->store('guarantor-attachments', 'public');
            $validatedData['attachment'] = $attachmentPath;
        }

        $guarantor->fill($validatedData);
        $guarantor->save();

        return redirect()->back()->with('success', 'Guarantor updated successfully.');
    }

    /**
     * Remove the specified guarantor
     */
    public function destroy($employeeId, $guarantorId)
    {
        $guarantor = EmployeeGuarantor::where('employee_id', $employeeId)
            ->where('id', $guarantorId)
            ->firstOrFail();

        // Delete attachment if exists
        if ($guarantor->attachment) {
            Storage::disk('public')->delete($guarantor->attachment);
        }

        $guarantor->delete();

        return redirect()->back()->with('success', 'Guarantor deleted successfully.');
    }
}
