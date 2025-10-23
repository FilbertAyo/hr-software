<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Department;
use App\Models\DirectDeduction;
use App\Models\Employee;
use App\Models\EmployeeContact;
use App\Models\EmployeeActivity;
use App\Models\EmployeeBankDetail;
use App\Models\EmployeeDepartment;
use App\Models\EmployeePensionDetail;
use App\Models\EmployeeSalaryDetail;
use App\Models\Jobtitle;
use App\Models\Mainstation;
use App\Models\Nationality;
use App\Models\Religion;
use App\Models\StaffLevel;
use App\Models\Substation;
use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        // Get normal deductions that have employee_percent (selectable by employees)
        $deductions = DirectDeduction::where('deduction_type', 'normal')
            ->where('status', 'active')
            ->whereNotNull('employee_percent')
            ->get();

        return view('employees.create', compact('substations', 'pensions', 'deductions', 'banks', 'departments', 'nationalities', 'religions', 'tax_rates', 'mainstations', 'jobtitles', 'level_names', 'earngroups'));
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

                // NHIF Details
                'nhif' => 'nullable|boolean',
                'nhif_fixed_amount' => 'nullable|boolean',
                'nhif_amount' => 'nullable|numeric|min:0',

                // Overtime Details
                'overtime_given' => 'nullable|boolean',
                'overtime_rate_weekday' => 'nullable|numeric|min:0',
                'overtime_rate_saturday' => 'nullable|numeric|min:0',
                'overtime_rate_weekend_holiday' => 'nullable|numeric|min:0',

                // Timing Details
                'use_office_timing' => 'nullable|boolean',
                'use_biometrics' => 'nullable|boolean',

                // Payment Details
                'payments' => 'nullable|boolean',
                'dynamic_payments_paid_in_rates' => 'nullable|boolean',

                // Earning Groups
                'earngroup_ids' => 'nullable|array',
                'earngroup_ids.*' => 'integer|exists:earngroups,id',

                // Employee Deductions
                'deduction_ids' => 'nullable|array',
                'deduction_ids.*' => 'integer|exists:direct_deductions,id',
                'deduction_member_numbers' => 'nullable|array',
                'deduction_member_numbers.*' => 'nullable|string|max:255',
            ], [
                'bank_id.required_if' => 'Please select a bank when payment method is Bank or Both.',
                'account_no.required_if' => 'Please enter an account number when payment method is Bank or Both.',
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
                'biometricID' => $validatedData['biometricID'],
                'employeeID' => $validatedData['employeeID'],
                'mobile_no' => $validatedData['mobile_no'],
                'email' => $validatedData['email'],
                'tin_no' => $validatedData['tin_no'],
                'gender' => strtolower($validatedData['gender']),
                'marital_status' => strtolower($validatedData['marital_status']),
                'nationality_id' => $validatedData['nationality_id'],
                'religion_id' => $validatedData['religion_id'],
                'residential_status' => $validatedData['residential_status'],
                'nida_no' => $validatedData['nida_no'],
                'employee_type' => $validatedData['employee_type'],
                'employee_status' => $validatedData['employee_status'],
                'tax_rate_id' => $validatedData['tax_rate_id'],
                'address' => $validatedData['address'],
                'photo_path' => $validatedData['photo_path'] ?? null,
                'wcf_no' => $validatedData['wcf_no'],
                'payment_method' => $validatedData['payment_method'],
                'company_id' => $companyId,
                'registration_step' => 'completed',

                // Salary Details
                'basic_salary' => $validatedData['basic_salary'] ?? 0,
                'advance_option' => $validatedData['advance_option'] ?? false,
                'advance_percentage' => $validatedData['advance_percentage'] ?? 0,
                'advance_salary' => $validatedData['advance_salary'] ?? 0,
                'paye_exempt' => $validatedData['paye_exempt'] ?? false,

                // Bank Details
                'is_primary_bank' => true,
                'bank_id' => $validatedData['bank_id'],
                'account_no' => $validatedData['account_no'],

                // Pension Details
                'pension_details' => $validatedData['pension_details'] ?? false,
                'pension_id' => $validatedData['pension_id'],
                'employee_pension_no' => $validatedData['employee_pension_no'],

                // NHIF Details
                'nhif' => $validatedData['nhif'] ?? false,
                'nhif_fixed_amount' => $validatedData['nhif_fixed_amount'] ?? false,
                'nhif_amount' => $validatedData['nhif_amount'] ?? 0,

                // Overtime Details
                'overtime_given' => $validatedData['overtime_given'] ?? false,
                'overtime_rate_weekday' => $validatedData['overtime_rate_weekday'] ?? 1.50,
                'overtime_rate_saturday' => $validatedData['overtime_rate_saturday'] ?? 1.50,
                'overtime_rate_weekend_holiday' => $validatedData['overtime_rate_weekend_holiday'] ?? 2.00,

                // Timing Details
                'use_office_timing' => $validatedData['use_office_timing'] ?? true,
                'use_biometrics' => $validatedData['use_biometrics'] ?? false,

                // Payment Details
                'payments' => $validatedData['payments'] ?? false,
                'dynamic_payments_paid_in_rates' => $validatedData['dynamic_payments_paid_in_rates'] ?? false,
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

            return redirect()->route('employee.show', $employee)
                ->with('success', 'Employee registered successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error registering employee: ' . $e->getMessage());
        }
    }

    private function storePersonalDetails(Request $request)
    {
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
            'nationality_id' => 'nullable|string',
            'religion_id' => 'nullable|string|max:255',
            'residential_status' => 'required|string',
            'nida_no' => 'nullable|string',
            'employee_type' => 'nullable|string',
            'employee_status' => 'required|string',
            'tax_rate_id' => 'nullable|integer|exists:tax_rates,id',
            'address' => 'nullable|string',
            'photo_path' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'wcf_no' => 'nullable|string|max:255',

            'joining_date'       => 'nullable|date',
            'mainstation_id'     => 'nullable|integer|exists:mainstations,id',
            'substation_id'      => 'nullable|integer|exists:substations,id',
            'department_id'      => 'nullable|integer|exists:departments,id',
            'main_division_branch' => 'nullable|string|max:255',
            'jobtitle_id'        => 'nullable|integer|exists:jobtitles,id',
            'staff_level_id'     => 'nullable|integer|exists:staff_levels,id',
            'hod'                => 'nullable|boolean',

            'payment_method' => 'required|in:cash,bank,both,other',
            'bank_id'        => 'required_if:payment_method,bank,required_if:payment_method,both|nullable|integer|exists:banks,id',
            'account_no'     => 'required_if:payment_method,bank,required_if:payment_method,both|nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Get the current company ID from session
            $companyId = session('selected_company_id');

            if (!$companyId) {
                return redirect()->back()->with('error', 'Please select a company before creating an employee.');
            }

            // Create main employee record
            $employee = Employee::create([
                'employee_name' => $validatedData['employee_name'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'biometricID' => $validatedData['biometricID'],
                'employeeID' => $validatedData['employeeID'],
                'mobile_no' => $validatedData['mobile_no'],
                'email' => $validatedData['email'],
                'tin_no' => $validatedData['tin_no'],
                'gender' => strtolower($validatedData['gender']),
                'marital_status' => strtolower($validatedData['marital_status']),
                'nationality_id' => $validatedData['nationality_id'],
                'religion_id' => $validatedData['religion_id'],
                'residential_status' => $validatedData['residential_status'],
                'nida_no' => $validatedData['nida_no'],
                'employee_type' => $validatedData['employee_type'],
                'employee_status' => $validatedData['employee_status'],
                'tax_rate_id' => $validatedData['tax_rate_id'],
                'address' => $validatedData['address'],
                'photo_path' => $validatedData['photo_path'],
                'wcf_no' => $validatedData['wcf_no'],
                'payment_method' => $validatedData['payment_method'],
                'company_id' => $companyId,
                'registration_step' => 'personal_saved'
            ]);

            // Create department details
            EmployeeDepartment::create([
                'employee_id' => $employee->id,
                'joining_date' => $validatedData['joining_date'],
                'mainstation_id' => $validatedData['mainstation_id'],
                'substation_id' => $validatedData['substation_id'],
                'department_id' => $validatedData['department_id'],
                'jobtitle_id' => $validatedData['jobtitle_id'],
                'staff_level_id' => $validatedData['staff_level_id'],
                'hod' => $validatedData['hod'],
            ]);

            DB::commit();

            return redirect()->route('employee.edit', $employee)
                ->with('success', 'Step 1 saved successfully! You can now proceed to Step 2.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error saving personal details: ' . $e->getMessage());
        }
    }

    private function completeRegistration(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $employee = Employee::findOrFail($employeeId);

        try {
            DB::beginTransaction();

            // Update employee registration step to completed
            $employee->update(['registration_step' => 'completed']);

            DB::commit();

            return redirect()->route('employee.index')
                ->with('success', 'Employee registration completed successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error completing registration: ' . $e->getMessage());
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
            'employeeDeductions.directDeduction'
        ]);

        // Get data for dropdowns - same as create method
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
        // Get normal deductions that have employee_percent (selectable by employees)
        $deductions = DirectDeduction::where('deduction_type', 'normal')
            ->where('status', 'active')
            ->whereNotNull('employee_percent')
            ->get();

        return view('employees.edit', compact('employee', 'substations', 'pensions', 'deductions', 'banks', 'departments', 'nationalities', 'religions', 'tax_rates', 'mainstations', 'jobtitles', 'level_names', 'earngroups'));
    }

    public function update(Request $request, Employee $employee)
    {
        $step = $request->input('step', 'personal');

        if ($step === 'personal') {
            return $this->updatePersonalDetails($request, $employee);
        } elseif ($step === 'salary') {
            return $this->updateSalaryDetails($request, $employee);
        } elseif ($step === 'complete') {
            return $this->completeRegistration($request);
        }

        return back()->with('error', 'Invalid step');
    }

    private function updatePersonalDetails(Request $request, Employee $employee)
    {
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
            'nationality_id' => 'nullable|string',
            'religion_id' => 'nullable|string|max:255',
            'residential_status' => 'required|string',
            'nida_no' => 'nullable|string',
            'employee_type' => 'nullable|string',
            'employee_status' => 'required|string',
            'tax_rate_id' => 'nullable|integer|exists:tax_rates,id',
            'address' => 'nullable|string',
            'photo_path' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'wcf_no' => 'nullable|string|max:255',

            'joining_date'       => 'nullable|date',
            'mainstation_id'     => 'nullable|integer|exists:mainstations,id',
            'substation_id'      => 'nullable|integer|exists:substations,id',
            'department_id'      => 'nullable|integer|exists:departments,id',
            'main_division_branch' => 'nullable|string|max:255',
            'jobtitle_id'        => 'nullable|integer|exists:jobtitles,id',
            'staff_level_id'     => 'nullable|integer|exists:staff_levels,id',
            'hod'                => 'nullable|boolean',

            'payment_method' => 'required|in:cash,bank,both,other',
            'bank_id'        => 'required_if:payment_method,bank,required_if:payment_method,both|nullable|integer|exists:banks,id',
            'account_no'     => 'required_if:payment_method,bank,required_if:payment_method,both|nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update main employee record
            $employee->update([
                'employee_name' => $validatedData['employee_name'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'biometricID' => $validatedData['biometricID'],
                'employeeID' => $validatedData['employeeID'],
                'mobile_no' => $validatedData['mobile_no'],
                'email' => $validatedData['email'],
                'tin_no' => $validatedData['tin_no'],
                'gender' => strtolower($validatedData['gender']),
                'marital_status' => strtolower($validatedData['marital_status']),
                'nationality_id' => $validatedData['nationality_id'],
                'religion_id' => $validatedData['religion_id'],
                'residential_status' => $validatedData['residential_status'],
                'nida_no' => $validatedData['nida_no'],
                'employee_type' => $validatedData['employee_type'],
                'employee_status' => $validatedData['employee_status'],
                'tax_rate_id' => $validatedData['tax_rate_id'],
                'address' => $validatedData['address'],
                'photo_path' => $validatedData['photo_path'],
                'wcf_no' => $validatedData['wcf_no'],
                'payment_method' => $validatedData['payment_method'],
                'registration_step' => 'personal_saved'
            ]);

            // Update department details
            $employee->department()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'joining_date' => $validatedData['joining_date'],
                    'mainstation_id' => $validatedData['mainstation_id'],
                    'substation_id' => $validatedData['substation_id'],
                    'department_id' => $validatedData['department_id'],
                    'jobtitle_id' => $validatedData['jobtitle_id'],
                    'staff_level_id' => $validatedData['staff_level_id'],
                    'hod' => $validatedData['hod'],
                ]
            );



            DB::commit();

            return redirect()->route('employee.edit', $employee)
                ->with('success', 'Step 1 updated successfully! You can now proceed to Step 2.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error updating personal details: ' . $e->getMessage());
        }
    }

    private function updateSalaryDetails(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'basic_salary' => 'nullable|numeric|min:0',
            'advance_option' => 'nullable',
            'advance_percentage' => 'nullable|numeric|min:0|max:100',
            'advance_salary' => 'nullable|numeric|min:0',
            'pension_details' => 'nullable|boolean',
            'pension_id' => 'nullable|integer|exists:direct_deductions,id',
            'employee_pension_no' => 'nullable|string|max:255',
            'paye_exempt' => 'nullable|boolean',
            'earngroup_ids' => 'nullable|array',
            'earngroup_ids.*' => 'integer|exists:earngroups,id',
            'deduction_ids' => 'nullable|array',
            'deduction_ids.*' => 'integer|exists:direct_deductions,id',
            'deduction_member_numbers' => 'nullable|array',
            'deduction_member_numbers.*' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Persist salary fields directly on employees table
            $employee->basic_salary = $validatedData['basic_salary'] ?? 0;
            $employee->advance_option = $validatedData['advance_option'] ?? false;
            $employee->advance_percentage = $validatedData['advance_percentage'] ?? 0;
            $employee->advance_salary = $validatedData['advance_salary'] ?? 0;
            $employee->paye_exempt = $validatedData['paye_exempt'] ?? false;
            $employee->save();

            // Sync earning groups
            if (isset($validatedData['earngroup_ids'])) {
                // Delete existing earngroup assignments
                \App\Models\EmployeeEarngroup::where('employee_id', $employee->id)->delete();

                // Create new assignments
                foreach ($validatedData['earngroup_ids'] as $earngroupId) {
                    \App\Models\EmployeeEarngroup::create([
                        'employee_id' => $employee->id,
                        'earngroup_id' => $earngroupId,
                        'status' => 'active',
                    ]);
                }
            } else {
                // If no earngroups selected, delete all assignments
                \App\Models\EmployeeEarngroup::where('employee_id', $employee->id)->delete();
            }

            // Sync employee deductions (normal deductions only)
            if (isset($validatedData['deduction_ids']) && is_array($validatedData['deduction_ids'])) {
                // Delete existing normal deductions (keep pension separate)
                \App\Models\EmployeeDeduction::where('employee_id', $employee->id)
                    ->whereHas('directDeduction', function($query) {
                        $query->where('deduction_type', 'normal');
                    })->delete();

                // Create new deduction assignments
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
                // If no deductions selected, remove all normal deductions
                \App\Models\EmployeeDeduction::where('employee_id', $employee->id)
                    ->whereHas('directDeduction', function($query) {
                        $query->where('deduction_type', 'normal');
                    })->delete();
            }

            // Update employee registration step
            $employee->update(['registration_step' => 'salary_saved']);

            DB::commit();

            return redirect()->route('employee.edit', $employee)
                ->with('success', 'Step 2 updated successfully! You can now complete the registration.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error updating salary details: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            $employee->delete(); // Cascade delete will handle related records

            return redirect()->route('employee.index')
                ->with('success', 'Employee deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }

    // API Methods for AJAX requests
    public function getBanks()
    {
        $banks = DB::table('banks')->select('bank_name')->distinct()->get();
        return response()->json($banks);
    }

    public function getSubstations()
    {
        $substations = DB::table('substations')->select('substation_name')->distinct()->get();
        return response()->json($substations);
    }

    public function getDepartments()
    {
        $departments = DB::table('departments')->select('department_name')->distinct()->get();
        return response()->json($departments);
    }
}
