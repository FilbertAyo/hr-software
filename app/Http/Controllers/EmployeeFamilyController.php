<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeFamily;
use App\Models\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeFamilyController extends Controller
{
    /**
     * Store a newly created family member
     */
    public function store(Request $request, $employeeId)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|integer|exists:relations,id',
            'mobile' => 'required|string|max:20',
            'home_mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer|min:0',
            'postal_address' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'tribe' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'is_dependant' => 'nullable|boolean',
        ]);

        $employee = Employee::findOrFail($employeeId);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('family-attachments', 'public');
            $validatedData['attachment'] = $attachmentPath;
        }

        // Create family member
        $family = new EmployeeFamily($validatedData);
        $family->employee_id = $employee->id;
        $family->relationship_id = $validatedData['relationship'];
        $family->is_dependant = $request->has('is_dependant') ? true : false;
        $family->save();

        return redirect()->back()->with('success', 'Family member added successfully.');
    }

    /**
     * Update the specified family member
     */
    public function update(Request $request, $employeeId, $familyId)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|integer|exists:relations,id',
            'mobile' => 'required|string|max:20',
            'home_mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer|min:0',
            'postal_address' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'tribe' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'is_dependant' => 'nullable|boolean',
        ]);

        $family = EmployeeFamily::where('employee_id', $employeeId)
            ->where('id', $familyId)
            ->firstOrFail();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($family->attachment) {
                Storage::disk('public')->delete($family->attachment);
            }
            $attachmentPath = $request->file('attachment')->store('family-attachments', 'public');
            $validatedData['attachment'] = $attachmentPath;
        }

        $family->fill($validatedData);
        $family->relationship_id = $validatedData['relationship'];
        $family->is_dependant = $request->has('is_dependant') ? true : false;
        $family->save();

        return redirect()->back()->with('success', 'Family member updated successfully.');
    }

    /**
     * Remove the specified family member
     */
    public function destroy($employeeId, $familyId)
    {
        $family = EmployeeFamily::where('employee_id', $employeeId)
            ->where('id', $familyId)
            ->firstOrFail();

        // Delete attachment if exists
        if ($family->attachment) {
            Storage::disk('public')->delete($family->attachment);
        }

        $family->delete();

        return redirect()->back()->with('success', 'Family member deleted successfully.');
    }
}
