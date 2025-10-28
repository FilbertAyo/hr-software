<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Loan;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Advance;
use App\Models\PayrollPeriod;
use App\Models\EmployeeDepartment;
use App\Models\Company;
use App\Models\TaxRate;
use App\Models\Mainstation;
use App\Models\DirectDeduction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected function getCompanyId()
    {
        return session('selected_company_id');
    }

    protected function getCurrentPeriod()
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = session('current_payroll_period');
        
        if (!$currentPeriod) {
            $currentPeriod = PayrollPeriod::where('company_id', $companyId)
                ->orderBy('start_date', 'desc')
                ->first();
        }
        
        return $currentPeriod;
    }

    public function index(){
        return view('reports.index');
    }

    // Employee Reports
    public function employeeReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $query = Employee::where('company_id', $companyId)
            ->with(['department.department', 'department.jobtitle', 'department.mainstation']);
        
        // Filters
        if ($request->filled('status')) {
            $query->where('employee_status', $request->status);
        }
        
        if ($request->filled('department_id')) {
            $query->whereHas('department', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $employees = $query->get();
        $departments = Department::all();
        
        return view('reports.employees', compact('employees', 'departments'));
    }

    // Payroll Reports
    public function payrollReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = $this->getCurrentPeriod();
        
        // Get filter data for dropdowns
        $periods = PayrollPeriod::where('company_id', $companyId)->orderBy('start_date', 'desc')->get();
        $taxRates = TaxRate::all();
        $departments = Department::all();
        $mainstations = Mainstation::all();
        
        // Initialize empty collections
        $payrolls = collect();
        $summary = [
            'total_gross' => 0,
            'total_deductions' => 0,
            'total_net' => 0,
            'total_tax' => 0,
            'total_pension' => 0,
            'employee_count' => 0
        ];
        
        // Only fetch data if Get Report button is clicked (has period_id)
        if ($request->filled('period_id')) {
            $query = Payroll::with(['employee.department.department', 'employee.department.mainstation', 
                                    'employee.taxRate', 'payrollPeriod'])
                ->whereHas('employee', function($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })
                ->where('payroll_period_id', $request->period_id);
            
            // Filter by employee type
            if ($request->filled('filter_type') && $request->filter_type !== 'all') {
                if ($request->filter_type === 'branch' && $request->filled('mainstation_id')) {
                    $query->whereHas('employee.department', function($q) use ($request) {
                        $q->where('mainstation_id', $request->mainstation_id)
                          ->where('is_current', true);
                    });
                } elseif ($request->filter_type === 'department' && $request->filled('department_id')) {
                    $query->whereHas('employee.department', function($q) use ($request) {
                        $q->where('department_id', $request->department_id)
                          ->where('is_current', true);
                    });
                }
            }
            
            // Filter by tax rate
            if ($request->filled('tax_rate_id')) {
                $query->whereHas('employee', function($q) use ($request) {
                    $q->where('tax_rate_id', $request->tax_rate_id);
                });
            }

            $payrolls = $query->orderBy('created_at', 'desc')->get();
            
            // Summary calculations
            $summary = [
                'total_gross' => $payrolls->sum('gross_salary'),
                'total_deductions' => $payrolls->sum('total_deductions'),
                'total_net' => $payrolls->sum('net_salary'),
                'total_tax' => $payrolls->sum('tax_deduction'),
                'total_pension' => $payrolls->sum('employee_pension_amount'),
                'employee_count' => $payrolls->count()
            ];
        }
        
        return view('reports.payroll', compact('payrolls', 'periods', 'summary', 'taxRates', 'departments', 'mainstations', 'currentPeriod'));
    }

    // Loan Reports
    public function loanReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = $this->getCurrentPeriod();
        
        // Default to current period if not specified
        $periodId = $request->filled('period_id') ? $request->period_id : ($currentPeriod ? $currentPeriod->id : null);
        
        $query = Loan::where('company_id', $companyId)
            ->with(['employee', 'loanType', 'payrollPeriod']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($periodId) {
            $query->where('payroll_period_id', $periodId);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();
        $periods = PayrollPeriod::where('company_id', $companyId)->orderBy('start_date', 'desc')->get();
        
        // Summary
        $summary = [
            'total_loans' => $loans->sum('loan_amount'),
            'total_remaining' => $loans->sum('remaining_amount'),
            'total_paid' => $loans->sum('loan_amount') - $loans->sum('remaining_amount'),
            'active_loans' => $loans->where('status', 'active')->count(),
            'completed_loans' => $loans->where('status', 'completed')->count()
        ];
        
        return view('reports.loans', compact('loans', 'summary', 'periods'));
    }

    // Leave Reports
    public function leaveReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $query = Leave::with(['employee', 'leaveType'])
            ->whereHas('employee', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        
        if ($request->filled('from_date')) {
            $query->where('from_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->where('to_date', '<=', $request->to_date);
        }

        $leaves = $query->orderBy('from_date', 'desc')->get();
        
        // Summary
        $summary = [
            'total_leaves' => $leaves->count(),
            'total_days' => $leaves->sum('no_of_days'),
            'pending' => $leaves->where('status', 'Pending')->count(),
            'approved' => $leaves->where('status', 'Approved')->count(),
            'rejected' => $leaves->where('status', 'Rejected')->count()
        ];
        
        return view('reports.leaves', compact('leaves', 'summary'));
    }

    // Attendance Reports
    public function attendanceReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = $this->getCurrentPeriod();
        
        // Default to current period if not specified
        $periodId = $request->filled('period_id') ? $request->period_id : ($currentPeriod ? $currentPeriod->id : null);
        
        $query = Attendance::with(['employee'])
            ->whereHas('employee', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->filled('attendance_type')) {
            $query->where('attendance_type', $request->attendance_type);
        }
        
        if ($periodId) {
            $query->where('payroll_period_id', $periodId);
        }

        $attendances = $query->orderBy('created_at', 'desc')->get();
        $periods = PayrollPeriod::where('company_id', $companyId)->orderBy('start_date', 'desc')->get();
        
        // Summary
        $summary = [
            'total_records' => $attendances->count(),
            'absent_count' => $attendances->where('attendance_type', 'absent')->count(),
            'late_count' => $attendances->where('attendance_type', 'late')->count(),
            'present_count' => $attendances->where('attendance_type', 'present')->count(),
            'total_late_hours' => $attendances->sum('late_hours'),
            'total_absent_days' => $attendances->sum('absent_days')
        ];
        
        return view('reports.attendance', compact('attendances', 'summary', 'periods'));
    }

    // Department Reports
    public function departmentReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $departments = Department::withCount('employeeDepartments')->get();
        
        $departmentDetails = [];
        foreach ($departments as $dept) {
            $employeeDepts = EmployeeDepartment::where('department_id', $dept->id)
                ->where('is_current', true)
                ->with('employee')
                ->get();
            
            $activeEmployees = $employeeDepts->filter(function($ed) use ($companyId) {
                return $ed->employee && $ed->employee->employee_status === 'active' && $ed->employee->company_id == $companyId;
            });
            
            $departmentDetails[] = [
                'department' => $dept,
                'total_employees' => $activeEmployees->count(),
                'total_salary' => $activeEmployees->sum(function($ed) {
                    return $ed->employee ? $ed->employee->basic_salary : 0;
                })
            ];
        }
        
        return view('reports.departments', compact('departmentDetails'));
    }

    // Advance Reports
    public function advanceReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = $this->getCurrentPeriod();
        
        // Default to current period if not specified
        $periodId = $request->filled('period_id') ? $request->period_id : ($currentPeriod ? $currentPeriod->id : null);
        
        $query = Advance::with(['employee', 'payrollPeriod'])
            ->whereHas('employee', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($periodId) {
            $query->where('payroll_period_id', $periodId);
        }

        $advances = $query->orderBy('created_at', 'desc')->get();
        $periods = PayrollPeriod::where('company_id', $companyId)->orderBy('start_date', 'desc')->get();
        
        // Summary
        $summary = [
            'total_advances' => $advances->sum('advance_amount'),
            'total_remaining' => $advances->sum('remaining_amount'),
            'total_recovered' => $advances->sum('advance_amount') - $advances->sum('remaining_amount'),
            'pending_count' => $advances->where('status', 'pending')->count(),
            'approved_count' => $advances->where('status', 'approved')->count()
        ];
        
        return view('reports.advances', compact('advances', 'summary', 'periods'));
    }

    // Tax Reports
    public function taxReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = $this->getCurrentPeriod();
        
        // Default to current period if not specified
        $periodId = $request->filled('period_id') ? $request->period_id : ($currentPeriod ? $currentPeriod->id : null);
        
        $query = Payroll::with(['employee', 'payrollPeriod'])
            ->whereHas('employee', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        
        if ($periodId) {
            $query->where('payroll_period_id', $periodId);
        }

        $payrolls = $query->get();
        
        // Group by period
        $taxByPeriod = $payrolls->groupBy('payroll_period_id')->map(function($group) {
            return [
                'period' => $group->first()->payrollPeriod,
                'total_tax' => $group->sum('tax_deduction'),
                'total_taxable_income' => $group->sum('taxable_income'),
                'employee_count' => $group->count()
            ];
        });
        
        $periods = PayrollPeriod::where('company_id', $companyId)->orderBy('start_date', 'desc')->get();
        
        return view('reports.tax', compact('taxByPeriod', 'periods'));
    }

    // Pension Reports
    public function pensionReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = $this->getCurrentPeriod();
        
        // Get all pension types from DirectDeduction table
        $pensions = DirectDeduction::where('deduction_type', 'pension')
            ->where('status', 'active')
            ->get();
        
        // Get all payroll periods for the dropdown
        $periods = PayrollPeriod::where('company_id', $companyId)->orderBy('start_date', 'desc')->get();
        
        // Initialize empty collections
        $payrolls = collect();
        $summary = [
            'total_employee_pension' => 0,
            'total_employer_pension' => 0,
            'total_pension' => 0,
            'employee_count' => 0
        ];
        
        // Only fetch data if Get Report button is clicked (has both period_id and pension_id)
        if ($request->filled('period_id') && $request->filled('pension_id')) {
            $periodId = $request->period_id;
            $pensionId = $request->pension_id;
            
            // Get employees with the selected pension
            $employeesWithPension = Employee::where('company_id', $companyId)
                ->where('pension_id', $pensionId)
                ->where('pension_details', true)
                ->pluck('id');
            
            // Get payrolls for these employees in the selected period
            $payrolls = Payroll::with(['employee', 'payrollPeriod'])
                ->where('payroll_period_id', $periodId)
                ->whereIn('employee_id', $employeesWithPension)
                ->get();
            
            $summary = [
                'total_employee_pension' => $payrolls->sum('employee_pension_amount'),
                'total_employer_pension' => $payrolls->sum('employer_pension_amount'),
                'total_pension' => $payrolls->sum('employee_pension_amount') + $payrolls->sum('employer_pension_amount'),
                'employee_count' => $payrolls->count()
            ];
        }
        
        return view('reports.pension', compact('payrolls', 'summary', 'periods', 'pensions', 'currentPeriod'));
    }

    // Salary Analysis Report
    public function salaryAnalysisReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->with(['department.department', 'department.jobtitle'])
            ->get();
        
        $analysis = [
            'total_employees' => $employees->count(),
            'total_basic_salary' => $employees->sum('basic_salary'),
            'average_salary' => $employees->avg('basic_salary'),
            'highest_salary' => $employees->max('basic_salary'),
            'lowest_salary' => $employees->min('basic_salary'),
            'median_salary' => $employees->median('basic_salary')
        ];
        
        // Salary by department
        $salaryByDept = EmployeeDepartment::where('is_current', true)
            ->with(['employee', 'department'])
            ->get()
            ->groupBy('department_id')
            ->map(function($group) use ($companyId) {
                $activeEmployees = $group->filter(function($ed) use ($companyId) {
                    return $ed->employee && $ed->employee->employee_status === 'active' && $ed->employee->company_id == $companyId;
                });
                
                return [
                    'department' => $group->first()->department->department_name ?? 'N/A',
                    'employee_count' => $activeEmployees->count(),
                    'total_salary' => $activeEmployees->sum(function($ed) {
                        return $ed->employee ? $ed->employee->basic_salary : 0;
                    }),
                    'average_salary' => $activeEmployees->avg(function($ed) {
                        return $ed->employee ? $ed->employee->basic_salary : 0;
                    })
                ];
            });
        
        return view('reports.salary-analysis', compact('analysis', 'salaryByDept', 'employees'));
    }

    // Payslip Report
    public function payslipReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = $this->getCurrentPeriod();
        
        // Get filter data for dropdowns
        $periods = PayrollPeriod::where('company_id', $companyId)->orderBy('start_date', 'desc')->get();
        $taxRates = TaxRate::all();
        $departments = Department::all();
        $mainstations = Mainstation::all();
        $employees = Employee::where('company_id', $companyId)
            ->where('employee_status', 'active')
            ->orderBy('employee_name')
            ->get();
        
        // Initialize payslips as empty collection
        $payslips = collect();
        
        // Only fetch payslips if form is submitted (has period_id)
        if ($request->filled('period_id')) {
            $query = Payroll::with(['employee.department.department', 'employee.department.mainstation', 
                                    'employee.taxRate', 'payrollPeriod', 'allowanceDetails', 'deductions'])
                ->whereHas('employee', function($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            
            // Filter by payroll period (required)
            $query->where('payroll_period_id', $request->period_id);
            
            // Filter by tax rate
            if ($request->filled('tax_rate_id') && $request->tax_rate_id != 'all') {
                $query->whereHas('employee', function($q) use ($request) {
                    $q->where('tax_rate_id', $request->tax_rate_id);
                });
            }
            
            // Filter by employee selection type
            if ($request->filled('employee_filter')) {
                switch ($request->employee_filter) {
                    case 'selected':
                        if ($request->filled('selected_employees')) {
                            $selectedEmployees = is_array($request->selected_employees) 
                                ? $request->selected_employees 
                                : [$request->selected_employees];
                            $query->whereIn('employee_id', $selectedEmployees);
                        }
                        break;
                    
                    case 'branch':
                        if ($request->filled('mainstation_id')) {
                            $query->whereHas('employee.department', function($q) use ($request) {
                                $q->where('mainstation_id', $request->mainstation_id);
                            });
                        }
                        break;
                    
                    case 'department':
                        if ($request->filled('department_id')) {
                            $query->whereHas('employee.department', function($q) use ($request) {
                                $q->where('department_id', $request->department_id);
                            });
                        }
                        break;
                    
                    case 'all':
                    default:
                        // No additional filter, get all employees
                        break;
                }
            }
            
            $payslips = $query->orderBy('created_at', 'desc')->get();
        }
        
        return view('reports.payslip', compact('payslips', 'periods', 'taxRates', 'departments', 
                                               'mainstations', 'employees', 'currentPeriod'));
    }

    // Bank Salary Report
    public function bankSalaryReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        $currentPeriod = $this->getCurrentPeriod();
        
        // Get filter data for dropdowns
        $periods = PayrollPeriod::where('company_id', $companyId)->orderBy('start_date', 'desc')->get();
        $departments = Department::all();
        $mainstations = Mainstation::all();
        
        // Initialize empty collections
        $employees = collect();
        $summary = [
            'total_net_salary' => 0,
            'employee_count' => 0
        ];
        
        // Only fetch data if Get Report button is clicked (has period_id)
        if ($request->filled('period_id')) {
            $query = Employee::where('company_id', $companyId)
                ->where('employee_status', 'active')
                ->with(['bank', 'department.department', 'department.mainstation']);
            
            // Filter by employee type
            if ($request->filled('filter_type') && $request->filter_type !== 'all') {
                if ($request->filter_type === 'branch' && $request->filled('mainstation_id')) {
                    $query->whereHas('department', function($q) use ($request) {
                        $q->where('mainstation_id', $request->mainstation_id)
                          ->where('is_current', true);
                    });
                } elseif ($request->filter_type === 'department' && $request->filled('department_id')) {
                    $query->whereHas('department', function($q) use ($request) {
                        $q->where('department_id', $request->department_id)
                          ->where('is_current', true);
                    });
                }
            }
            
            $employees = $query->orderBy('employee_name')->get();
            
            // Get payroll data for the selected period
            $periodId = $request->period_id;
            $payrolls = Payroll::where('payroll_period_id', $periodId)
                ->whereIn('employee_id', $employees->pluck('id'))
                ->get()
                ->keyBy('employee_id');
            
            // Attach net salary to each employee
            $employees = $employees->map(function($employee) use ($payrolls) {
                $employee->net_salary = $payrolls->has($employee->id) 
                    ? $payrolls->get($employee->id)->net_salary 
                    : 0;
                return $employee;
            });
            
            // Summary calculations
            $summary = [
                'total_net_salary' => $employees->sum('net_salary'),
                'employee_count' => $employees->count()
            ];
        }
        
        return view('reports.bank-salary', compact('employees', 'periods', 'summary', 'departments', 'mainstations', 'currentPeriod'));
    }

    // Earning Group Report
    public function earningGroupReport(Request $request)
    {
        $companyId = $this->getCompanyId();
        
        // Get filter data for dropdowns
        $departments = Department::all();
        $mainstations = Mainstation::all();
        $earngroups = \App\Models\Earngroup::all();
        
        // Initialize empty collections
        $employeeEarngroups = collect();
        $summary = [
            'total_amount' => 0,
            'employee_count' => 0
        ];
        
        // Only fetch data if Get Report button is clicked
        if ($request->has('get_report')) {
            $query = \App\Models\EmployeeEarngroup::with([
                'employee.department.department', 
                'employee.department.mainstation',
                'earngroup.groupBenefits.allowance.allowanceDetails'
            ])
            ->whereHas('employee', function($q) use ($companyId) {
                $q->where('company_id', $companyId)
                  ->where('employee_status', 'active');
            })
            ->where('status', 'active');
            
            // Filter by earning group
            if ($request->filled('earngroup_id') && $request->earngroup_id !== 'all') {
                $query->where('earngroup_id', $request->earngroup_id);
            }
            
            // Filter by employee type
            if ($request->filled('filter_type') && $request->filter_type !== 'all') {
                if ($request->filter_type === 'branch' && $request->filled('mainstation_id')) {
                    $query->whereHas('employee.department', function($q) use ($request) {
                        $q->where('mainstation_id', $request->mainstation_id)
                          ->where('is_current', true);
                    });
                } elseif ($request->filter_type === 'department' && $request->filled('department_id')) {
                    $query->whereHas('employee.department', function($q) use ($request) {
                        $q->where('department_id', $request->department_id)
                          ->where('is_current', true);
                    });
                }
            }
            
            $employeeEarngroups = $query->orderBy('employee_id')->get();
            
            // Calculate total allowances for each employee-earngroup combination
            $employeeEarngroups = $employeeEarngroups->map(function($empEarngroup) {
                $totalAmount = 0;
                
                if ($empEarngroup->earngroup && $empEarngroup->earngroup->groupBenefits) {
                    foreach ($empEarngroup->earngroup->groupBenefits as $groupBenefit) {
                        if ($groupBenefit->status == 'active' && $groupBenefit->allowance) {
                            foreach ($groupBenefit->allowance->allowanceDetails as $detail) {
                                if ($detail->status == 'active') {
                                    if ($detail->calculation_type == 'amount') {
                                        $totalAmount += $detail->amount ?? 0;
                                    } elseif ($detail->calculation_type == 'percentage' && $empEarngroup->employee) {
                                        $totalAmount += ($empEarngroup->employee->basic_salary * ($detail->percentage ?? 0)) / 100;
                                    }
                                }
                            }
                        }
                    }
                }
                
                $empEarngroup->total_amount = $totalAmount;
                return $empEarngroup;
            });
            
            // Summary calculations
            $summary = [
                'total_amount' => $employeeEarngroups->sum('total_amount'),
                'employee_count' => $employeeEarngroups->unique('employee_id')->count()
            ];
        }
        
        return view('reports.earning-group', compact('employeeEarngroups', 'summary', 'departments', 'mainstations', 'earngroups'));
    }
}
