<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Department;
use App\Models\DirectDeduction;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Jobtitle;
use App\Models\Mainstation;
use App\Models\Nationality;
use App\Models\Religion;
use App\Models\Shift;
use App\Models\StaffLevel;
use App\Models\Substation;
use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('company.index')->with('error', 'Please select a company to view employees.');
        }

        $employees = Employee::where('company_id', $companyId)
            ->with(['department.department', 'department.jobtitle', 'department.staffLevel', 'nationality', 'religion'])
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            return redirect()->route('company.index')->with('error', 'Please select a company before creating an employee.');
        }

        // Get data for dropdowns
        $banks = Bank::all();
        $nationalities = Nationality::all();
        $religions = Religion::all();
        $tax_rates = TaxRate::all();
        $mainstations = Mainstation::all();
        $substations = Substation::all();
        $departments = Department::all();
        $jobtitles = Jobtitle::all();
        $level_names = StaffLevel::all();
        $pensions = DirectDeduction::where('deduction_type', 'pension')->get();
        $earngroups = \App\Models\Earngroup::all();
        $deductions = DirectDeduction::where('deduction_type', 'normal')
            ->where('status', 'active')
            ->whereNotNull('employee_percent')
            ->get();
        $shifts = Shift::where('is_active', true)->get();
        $relationships = \App\Models\Relation::all();

        // Get form data from session if exists
        $formData = session('employee_form_data', []);
        $currentStep = session('employee_form_step', 1);

        return view('employees.create', compact(
            'substations', 'pensions', 'deductions', 'banks', 'departments',
            'nationalities', 'religions', 'tax_rates', 'mainstations',
            'jobtitles', 'level_names', 'earngroups', 'shifts', 'relationships',
            'formData', 'currentStep'
        ));
    }

    /**
     * Save form data to session (AJAX endpoint)
     */
    public function saveFormSession(Request $request)
    {
        $formData = $request->all();
        $step = $request->input('step', 1);

        // Store form data in session
        session(['employee_form_data' => $formData]);
        session(['employee_form_step' => $step]);

        return response()->json([
            'success' => true,
            'message' => 'Form data saved to session'
        ]);
    }

    /**
     * Navigate between steps without validation
     */
    public function navigateStep(Request $request)
    {
        $step = $request->input('step', 1);
        $formData = $request->except(['step', '_token']);

        // Save current form data to session
        session(['employee_form_data' => array_merge(
            session('employee_form_data', []),
            $formData
        )]);
        session(['employee_form_step' => $step]);

        return response()->json([
            'success' => true,
            'step' => $step,
            'message' => 'Navigated to step ' . $step
        ]);
    }

    /**
     * Clear form session data
     */
    public function clearFormSession()
    {
        session()->forget(['employee_form_data', 'employee_form_step']);

        return response()->json([
            'success' => true,
            'message' => 'Form session cleared'
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate all form data
            $validatedData = $request->validate([
                // Personal Details
                'employee_name' => 'required|string|max:255',
                'employeeID' => 'nullable|string|unique:employees,employeeID',
                'biometricID' => 'nullable|string|unique:employees,biometricID',
                'date_of_birth' => 'required|date',
                'mobile_no' => 'nullable|string|unique:employees,mobile_no',
                'email' => 'nullable|string|email|unique:employees,email',
                'tin_no' => 'nullable|string|unique:employees,tin_no',
                'gender' => 'required|in:male,female',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'nationality_id' => 'nullable|integer|exists:nationalities,id',
                'religion_id' => 'nullable|integer|exists:religions,id',
                'residential_status' => 'required|string',
                'nida_no' => 'nullable|string',
                'employee_type' => 'nullable|string',
                'employee_status' => 'required|string',
                'tax_rate_id' => 'nullable|integer|exists:tax_rates,id',
                'address' => 'nullable|string',
                'photo_path' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'wcf_no' => 'nullable|string|max:255',
                'shift_id' => 'nullable|integer|exists:shifts,id',

                // Employment Details
                'joining_date' => 'required|date',
                'mainstation_id' => 'required|integer|exists:mainstations,id',
                'substation_id' => 'required|integer|exists:substations,id',
                'department_id' => 'required|integer|exists:departments,id',
                'jobtitle_id' => 'required|integer|exists:jobtitles,id',
                'staff_level_id' => 'required|integer|exists:staff_levels,id',
                'hod' => 'nullable|boolean',

                // Payment Details
                'payment_method' => 'required|in:cash,bank,both,other',
                'bank_id' => 'nullable|integer|exists:banks,id',
                'account_no' => 'nullable|string',

                // Salary Details
                'basic_salary' => 'nullable|numeric|min:0',
                'advance_option' => 'nullable|boolean',
                'advance_percentage' => 'nullable|numeric|min:0|max:100',
                'advance_salary' => 'nullable|numeric|min:0',
                'pension_details' => 'nullable|boolean',
                'pension_id' => 'nullable|integer|exists:direct_deductions,id',
                'employee_pension_no' => 'nullable|string|max:255',
                'paye_exempt' => 'nullable|boolean',

                // Earning Groups
                'earngroup_ids' => 'nullable|array',
                'earngroup_ids.*' => 'integer|exists:earngroups,id',

                // Employee Deductions
                'deduction_ids' => 'nullable|array',
                'deduction_ids.*' => 'integer|exists:direct_deductions,id',
                'deduction_member_numbers' => 'nullable|array',
                'deduction_member_numbers.*' => 'nullable|string|max:255',
            ]);

            // Handle photo upload
            if ($request->hasFile('photo_path')) {
                $photoPath = $request->file('photo_path')->store('employee-photos', 'public');
                $validatedData['photo_path'] = $photoPath;
            }

            // Get the current company ID from session
            $companyId = session('selected_company_id');

            if (!$companyId) {
                return redirect()->back()->with('error', 'Please select a company before creating an employee.');
            }

            // Create consolidated employee record with all details
            $employee = Employee::create([
                // Basic Information
                'employee_name' => $validatedData['employee_name'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'biometricID' => $validatedData['biometricID'] ?? null,
                'employeeID' => $validatedData['employeeID'] ?? null,
                'mobile_no' => $validatedData['mobile_no'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'tin_no' => $validatedData['tin_no'] ?? null,
                'gender' => strtolower($validatedData['gender']),
                'marital_status' => strtolower($validatedData['marital_status']),
                'nationality_id' => $validatedData['nationality_id'] ?? null,
                'religion_id' => $validatedData['religion_id'] ?? null,
                'residential_status' => $validatedData['residential_status'],
                'nida_no' => $validatedData['nida_no'] ?? null,
                'employee_type' => $validatedData['employee_type'] ?? null,
                'employee_status' => $validatedData['employee_status'],
                'tax_rate_id' => $validatedData['tax_rate_id'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'photo_path' => $validatedData['photo_path'] ?? null,
                'wcf_no' => $validatedData['wcf_no'] ?? null,
                'payment_method' => $validatedData['payment_method'],
                'company_id' => $companyId,
                'registration_step' => 'completed',
                'shift_id' => $validatedData['shift_id'] ?? null,

                // Salary Details
                'basic_salary' => $validatedData['basic_salary'] ?? 0,
                'advance_option' => $validatedData['advance_option'] ?? false,
                'advance_percentage' => $validatedData['advance_percentage'] ?? 0,
                'advance_salary' => $validatedData['advance_salary'] ?? 0,
                'paye_exempt' => $validatedData['paye_exempt'] ?? false,

                // Bank Details
                'is_primary_bank' => true,
                'bank_id' => $validatedData['bank_id'] ?? null,
                'account_no' => $validatedData['account_no'] ?? null,

                // Pension Details
                'pension_details' => $validatedData['pension_details'] ?? false,
                'pension_id' => $validatedData['pension_id'] ?? null,
                'employee_pension_no' => $validatedData['employee_pension_no'] ?? null,

                // Timing Details
                'use_office_timing' => true,
                'use_biometrics' => false,

                // Payment Details
                'payments' => false,
                'dynamic_payments_paid_in_rates' => false,
            ]);

            // Create department relationship
            EmployeeDepartment::create([
                'employee_id' => $employee->id,
                'joining_date' => $validatedData['joining_date'],
                'mainstation_id' => $validatedData['mainstation_id'],
                'substation_id' => $validatedData['substation_id'],
                'department_id' => $validatedData['department_id'],
                'jobtitle_id' => $validatedData['jobtitle_id'],
                'staff_level_id' => $validatedData['staff_level_id'],
                'hod' => $validatedData['hod'] ?? false,
            ]);

            // Assign earning groups to employee
            if (!empty($validatedData['earngroup_ids'])) {
                foreach ($validatedData['earngroup_ids'] as $earngroupId) {
                    \App\Models\EmployeeEarngroup::create([
                        'employee_id' => $employee->id,
                        'earngroup_id' => $earngroupId,
                        'status' => 'active',
                    ]);
                }
            }

            // Assign deductions to employee
            if (!empty($validatedData['deduction_ids']) && is_array($validatedData['deduction_ids'])) {
                foreach ($validatedData['deduction_ids'] as $index => $deductionId) {
                    if ($deductionId) {
                        \App\Models\EmployeeDeduction::create([
                            'employee_id' => $employee->id,
                            'direct_deduction_id' => $deductionId,
                            'member_number' => $validatedData['deduction_member_numbers'][$index] ?? null,
                            'status' => 'active',
                        ]);
                    }
                }
            }

            DB::commit();

            // Clear form session data after successful submission
            session()->forget(['employee_form_data', 'employee_form_step']);

            return redirect()->route('employee.index')
                ->with('success', 'Employee registered successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();

            // Keep session data on validation error
            return back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            DB::rollback();

            // Keep session data on error
            return back()->withInput()
                ->with('error', 'Error registering employee: ' . $e->getMessage());
        }
    }

    public function show(Employee $employee)
    {
        $companyId = session('selected_company_id');

        // Ensure the employee belongs to the selected company
        if ($employee->company_id != $companyId) {
            return redirect()->route('employee.index')->with('error', 'Employee not found or access denied.');
        }

        $employee->load([
            'department.department',
            'department.jobtitle',
            'department.staffLevel',
            'department.mainstation',
            'department.substation',
            'nationality',
            'religion',
            'taxRate',
            'bank',
            'pension',
            'earngroups'
        ]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $companyId = session('selected_company_id');

        // Ensure the employee belongs to the selected company
        if ($employee->company_id != $companyId) {
            return redirect()->route('employee.index')->with('error', 'Employee not found or access denied.');
        }

        $employee->load([
            'department.department',
            'department.jobtitle',
            'department.staffLevel',
            'department.mainstation',
            'department.substation',
            'nationality',
            'religion',
            'taxRate',
            'bank',
            'pension',
            'earngroups',
            'employeeDeductions.directDeduction',
            'family.relationship',
            'guarantors'
        ]);

        // Get data for dropdowns
        $banks = Bank::all();
        $nationalities = Nationality::all();
        $religions = Religion::all();
        $tax_rates = TaxRate::all();
        $mainstations = Mainstation::all();
        $substations = Substation::all();
        $departments = Department::all();
        $jobtitles = Jobtitle::all();
        $level_names = StaffLevel::all();
        $pensions = DirectDeduction::where('deduction_type', 'pension')->get();
        $earngroups = \App\Models\Earngroup::all();
        $deductions = DirectDeduction::where('deduction_type', 'normal')
            ->where('status', 'active')
            ->whereNotNull('employee_percent')
            ->get();
        $shifts = Shift::where('is_active', true)->get();
        $relationships = \App\Models\Relation::all();

        return view('employees.edit', compact(
            'employee', 'substations', 'pensions', 'deductions', 'banks',
            'departments', 'nationalities', 'religions', 'tax_rates',
            'mainstations', 'jobtitles', 'level_names', 'earngroups', 'shifts', 'relationships'
        ));
    }

    public function update(Request $request, Employee $employee)
    {
        try {
            DB::beginTransaction();

            // Validate all form data
            $validatedData = $request->validate([
                // Personal Details
                'employee_name' => 'required|string|max:255',
                'employeeID' => 'nullable|string|unique:employees,employeeID,' . $employee->id,
                'biometricID' => 'nullable|string|unique:employees,biometricID,' . $employee->id,
                'date_of_birth' => 'required|date',
                'mobile_no' => 'nullable|string|unique:employees,mobile_no,' . $employee->id,
                'email' => 'nullable|string|email|unique:employees,email,' . $employee->id,
                'tin_no' => 'nullable|string|unique:employees,tin_no,' . $employee->id,
                'gender' => 'required|in:male,female',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'nationality_id' => 'nullable|integer|exists:nationalities,id',
                'religion_id' => 'nullable|integer|exists:religions,id',
                'residential_status' => 'required|string',
                'nida_no' => 'nullable|string',
                'employee_type' => 'nullable|string',
                'employee_status' => 'required|string',
                'tax_rate_id' => 'nullable|integer|exists:tax_rates,id',
                'address' => 'nullable|string',
                'photo_path' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'wcf_no' => 'nullable|string|max:255',
                'shift_id' => 'nullable|integer|exists:shifts,id',

                // Employment Details
                'joining_date' => 'required|date',
                'mainstation_id' => 'required|integer|exists:mainstations,id',
                'substation_id' => 'required|integer|exists:substations,id',
                'department_id' => 'required|integer|exists:departments,id',
                'jobtitle_id' => 'required|integer|exists:jobtitles,id',
                'staff_level_id' => 'required|integer|exists:staff_levels,id',
                'hod' => 'nullable|boolean',

                // Payment Details
                'payment_method' => 'required|in:cash,bank,both,other',
                'bank_id' => 'nullable|integer|exists:banks,id',
                'account_no' => 'nullable|string',

                // Salary Details
                'basic_salary' => 'nullable|numeric|min:0',
                'advance_option' => 'nullable|boolean',
                'advance_percentage' => 'nullable|numeric|min:0|max:100',
                'advance_salary' => 'nullable|numeric|min:0',
                'pension_details' => 'nullable|boolean',
                'pension_id' => 'nullable|integer|exists:direct_deductions,id',
                'employee_pension_no' => 'nullable|string|max:255',
                'paye_exempt' => 'nullable|boolean',

                // Earning Groups
                'earngroup_ids' => 'nullable|array',
                'earngroup_ids.*' => 'integer|exists:earngroups,id',

                // Employee Deductions
                'deduction_ids' => 'nullable|array',
                'deduction_ids.*' => 'integer|exists:direct_deductions,id',
                'deduction_member_numbers' => 'nullable|array',
                'deduction_member_numbers.*' => 'nullable|string|max:255',
            ]);

            // Handle photo upload
            if ($request->hasFile('photo_path')) {
                $photoPath = $request->file('photo_path')->store('employee-photos', 'public');
                $validatedData['photo_path'] = $photoPath;
            }

            // Update employee record
            $employee->update([
                'employee_name' => $validatedData['employee_name'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'biometricID' => $validatedData['biometricID'] ?? null,
                'employeeID' => $validatedData['employeeID'] ?? null,
                'mobile_no' => $validatedData['mobile_no'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'tin_no' => $validatedData['tin_no'] ?? null,
                'gender' => strtolower($validatedData['gender']),
                'marital_status' => strtolower($validatedData['marital_status']),
                'nationality_id' => $validatedData['nationality_id'] ?? null,
                'religion_id' => $validatedData['religion_id'] ?? null,
                'residential_status' => $validatedData['residential_status'],
                'nida_no' => $validatedData['nida_no'] ?? null,
                'employee_type' => $validatedData['employee_type'] ?? null,
                'employee_status' => $validatedData['employee_status'],
                'tax_rate_id' => $validatedData['tax_rate_id'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'photo_path' => $validatedData['photo_path'] ?? $employee->photo_path,
                'wcf_no' => $validatedData['wcf_no'] ?? null,
                'payment_method' => $validatedData['payment_method'],
                'shift_id' => $validatedData['shift_id'] ?? null,
                'basic_salary' => $validatedData['basic_salary'] ?? 0,
                'advance_option' => $validatedData['advance_option'] ?? false,
                'advance_percentage' => $validatedData['advance_percentage'] ?? 0,
                'advance_salary' => $validatedData['advance_salary'] ?? 0,
                'paye_exempt' => $validatedData['paye_exempt'] ?? false,
                'bank_id' => $validatedData['bank_id'] ?? null,
                'account_no' => $validatedData['account_no'] ?? null,
                'pension_details' => $validatedData['pension_details'] ?? false,
                'pension_id' => $validatedData['pension_id'] ?? null,
                'employee_pension_no' => $validatedData['employee_pension_no'] ?? null,
            ]);

            // Update department relationship
            $employee->department()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'joining_date' => $validatedData['joining_date'],
                    'mainstation_id' => $validatedData['mainstation_id'],
                    'substation_id' => $validatedData['substation_id'],
                    'department_id' => $validatedData['department_id'],
                    'jobtitle_id' => $validatedData['jobtitle_id'],
                    'staff_level_id' => $validatedData['staff_level_id'],
                    'hod' => $validatedData['hod'] ?? false,
                ]
            );

            // Sync earning groups
            if (isset($validatedData['earngroup_ids'])) {
                \App\Models\EmployeeEarngroup::where('employee_id', $employee->id)->delete();
                foreach ($validatedData['earngroup_ids'] as $earngroupId) {
                    \App\Models\EmployeeEarngroup::create([
                        'employee_id' => $employee->id,
                        'earngroup_id' => $earngroupId,
                        'status' => 'active',
                    ]);
                }
            } else {
                \App\Models\EmployeeEarngroup::where('employee_id', $employee->id)->delete();
            }

            // Sync employee deductions
            if (isset($validatedData['deduction_ids']) && is_array($validatedData['deduction_ids'])) {
                \App\Models\EmployeeDeduction::where('employee_id', $employee->id)
                    ->whereHas('directDeduction', function($query) {
                        $query->where('deduction_type', 'normal');
                    })->delete();

                foreach ($validatedData['deduction_ids'] as $index => $deductionId) {
                    if ($deductionId) {
                        \App\Models\EmployeeDeduction::create([
                            'employee_id' => $employee->id,
                            'direct_deduction_id' => $deductionId,
                            'member_number' => $validatedData['deduction_member_numbers'][$index] ?? null,
                            'status' => 'active',
                        ]);
                    }
                }
            } else {
                \App\Models\EmployeeDeduction::where('employee_id', $employee->id)
                    ->whereHas('directDeduction', function($query) {
                        $query->where('deduction_type', 'normal');
                    })->delete();
            }

            DB::commit();

            return redirect()->route('employee.index')
                ->with('success', 'Employee updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();

            return redirect()->route('employee.index')
                ->with('success', 'Employee deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
}
