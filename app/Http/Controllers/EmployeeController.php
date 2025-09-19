<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\EmployeeBankDetail;
use App\Models\EmployeeSalaryDetail;
use App\Models\EmployeePensionDetail;
use App\Models\EmployeeNhifDetail;
use App\Models\EmployeeOvertimeDetail;
use App\Models\EmployeeTimingDetail;
use App\Models\EmployeePaymentDetail;
use App\Models\EmployeePortalDetail;
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
        $employees = Employee::with(['department', 'primaryBankDetail', 'salaryDetails'])
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        // Get data for dropdowns - adjust according to your existing models
        $substations = DB::table('substations')->select('sub_station')->distinct()->get();
        $banks = Bank::all();
        $departments = DB::table('departments')->select('department_name')->distinct()->get();
        $nationalities = Nationality::all();
        $religions = Religion::all();
        $tax_rates = TaxRate::all();
        $mainstations = Mainstation::all();
        $substations = Substation::all();
        $departments = Department::all();
        $jobtitles = Jobtitle::all();
        $stafflevels = StaffLevel::all();

        return view('employees.create', compact('substations', 'banks', 'departments', 'nationalities', 'religions', 'tax_rates', 'mainstations', 'substations', 'departments', 'jobtitles', 'stafflevels'));
    }

    public function store(Request $request)
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


            // 'shiftType' => 'nullable|string|max:255',
            // 'emergencyContact' => 'nullable|string|max:255',

            // 'villageBorn' => 'required|string|max:255',
            // 'wardBorn' => 'required|string|max:255',
            // 'birthNo' => 'required|string|max:255',


            // 'tribe' => 'required|string|max:255',

            // 'baptizedYear' => 'required|integer|min:1900|max:' . date('Y'),
            // 'baptizedWard' => 'required|string|max:255',
            // 'churchMosque' => 'required|string|max:255',
            // 'baptizedRegion' => 'required|string|max:255',
            // 'baptizedDistrict' => 'required|string|max:255',
            // 'baptizedVillage' => 'required|string|max:255',
            // 'baptizedDivision' => 'required|string|max:255',



            'basic_salary' => 'nullable|numeric|min:0',
            // 'tax' => 'required|in:yes,no',
            // 'pension' => 'nullable|string|max:255',
            // 'pensionNo' => 'nullable|string|max:255',
            // 'earningGroup' => 'nullable|string|max:255',
            // 'payGrade' => 'nullable|string|max:255',
            // 'directDeduction' => 'nullable|string|max:255',
            // 'currencyID' => 'required|string|max:10',
            // 'loan' => 'required|in:yes,no',
            // 'payPeriod' => 'required|string|max:255',

        ]);

        try {
            DB::beginTransaction();

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

            // Create bank details

            if (in_array($validatedData['payment_method'], ['bank', 'both'])) {
                EmployeeBankDetail::create([
                    'employee_id' => $employee->id,
                    'bank_id'     => $validatedData['bank_id'],
                    'account_no'  => $validatedData['account_no'],
                ]);
            }

            // Create salary details

            EmployeeSalaryDetail::create([
                'employee_id' => $employee->id,
                'basic_salary' => $validatedData['basic_salary'],
                // 'total_payments' => $validatedData['basicSalary'],
                // 'net_salary' => $validatedData['basicSalary'],
            ]);


            // // Create pension details if provided
            // if ($validatedData['pension'] || $validatedData['pensionNo']) {
            //     EmployeePensionDetail::create([
            //         'employee_id' => $employee->id,
            //         'pension_details' => true,
            //         'pension' => $validatedData['pension'],
            //         'employee_pension_no' => $validatedData['pensionNo'],
            //     ]);
            // }

            // Create NHIF details (default)
            // EmployeeNhifDetail::create([
            //     'employee_id' => $employee->id,
            //     'nhif' => false,
            // ]);

            // Create overtime details (default)
            // EmployeeOvertimeDetail::create([
            //     'employee_id' => $employee->id,
            //     'overtime_given' => false,
            // ]);

            // // Create timing details (default)
            // EmployeeTimingDetail::create([
            //     'employee_id' => $employee->id,
            //     'use_office_timing' => true,
            //     'use_biometrics' => false,
            // ]);

            // // Create payment details
            // EmployeePaymentDetail::create([
            //     'employee_id' => $employee->id,
            //     'payments' => $validatedData['loan'] === 'yes',
            // ]);

            // Create portal details with default credentials
            // EmployeePortalDetail::create([
            //     'employee_id' => $employee->id,
            //     'username' => strtolower(str_replace(' ', '.', $employee->employee_name)),
            //     'password' => Hash::make('password123'),
            //     'login_permission' => true,
            //     'payslips_permission' => true,
            //     'leave_requests_permission' => true,
            //     'loan_requests_permission' => $validatedData['loan'] === 'yes',
            // ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                ->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'bankDetails',
            'salaryDetails',
            'pensionDetails',
            'nhifDetails',
            'overtimeDetails',
            'timingDetails',
            'qualifications',
            'guarantors',
            'nextOfKin',
            'paymentDetails',
            'deductionDetails',
            'portalDetails'
        ]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load([
            'department',
            'primaryBankDetail',
            'salaryDetails',
            'pensionDetails'
        ]);

        $substations = DB::table('substations')->select('sub_station')->distinct()->get();
        $banks = DB::table('banks')->select('bank_name')->distinct()->get();
        $departments = DB::table('departments')->select('department_name')->distinct()->get();

        return view('employees.edit', compact('employee', 'substations', 'banks', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            // Add your validation rules here - similar to store method
        ]);

        try {
            DB::beginTransaction();

            // Update employee and related records
            $employee->update($validatedData);

            // Update related tables as needed

            DB::commit();

            return redirect()->route('employee.show', $employee)
                ->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                ->with('error', 'Error updating employee: ' . $e->getMessage());
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
        $substations = DB::table('substations')->select('sub_station')->distinct()->get();
        return response()->json($substations);
    }

    public function getDepartments()
    {
        $departments = DB::table('departments')->select('department_name')->distinct()->get();
        return response()->json($departments);
    }
}
