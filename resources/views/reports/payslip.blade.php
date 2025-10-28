<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Payslip Report</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                <i class="fe fe-arrow-left mr-1"></i> Back to Reports
            </a>
            @if($payslips->count() > 0)
            <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                <i class="fe fe-printer mr-1"></i> Print
            </button>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3 no-print">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filter Payslips</h5>
                    <form method="GET" action="{{ route('reports.payslip') }}" id="payslipFilterForm">
                        <div class="row">
                            <!-- Payroll Period -->
                            <div class="col-md-3 mb-3">
                                <label class="font-weight-bold">Payroll Period <span class="text-danger">*</span></label>
                                <select name="period_id" class="form-control" required>
                                    <option value="">Select Period</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->period_name }} ({{ date('M Y', strtotime($period->start_date)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tax Rate -->
                            <div class="col-md-3 mb-3">
                                <label class="font-weight-bold">Tax Rate</label>
                                <select name="tax_rate_id" class="form-control">
                                    <option value="all" {{ request('tax_rate_id') == 'all' ? 'selected' : '' }}>All Tax Rates</option>
                                    @foreach($taxRates as $taxRate)
                                        <option value="{{ $taxRate->id }}" {{ request('tax_rate_id') == $taxRate->id ? 'selected' : '' }}>
                                            {{ $taxRate->tax_name }} ({{ $taxRate->rate }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Employee Filter Type -->
                            <div class="col-md-3 mb-3">
                                <label class="font-weight-bold">Employee Selection</label>
                                <select name="employee_filter" id="employeeFilterType" class="form-control">
                                    <option value="all" {{ request('employee_filter') == 'all' ? 'selected' : '' }}>All Employees</option>
                                    <option value="selected" {{ request('employee_filter') == 'selected' ? 'selected' : '' }}>Selected Employees</option>
                                    <option value="branch" {{ request('employee_filter') == 'branch' ? 'selected' : '' }}>By Branch (Main Station)</option>
                                    <option value="department" {{ request('employee_filter') == 'department' ? 'selected' : '' }}>By Department</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fe fe-search mr-1"></i> Get Report
                                </button>
                            </div>
                        </div>

                        <!-- Conditional Filters -->
                        <div class="row">
                            <!-- Selected Employees -->
                            <div class="col-md-12 mb-3" id="selectedEmployeesDiv" style="display: {{ request('employee_filter') == 'selected' ? 'block' : 'none' }};">
                                <label class="font-weight-bold">Select Employees</label>
                                <select name="selected_employees[]" class="form-control" multiple size="8">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" 
                                            {{ in_array($employee->id, (array)request('selected_employees', [])) ? 'selected' : '' }}>
                                            {{ $employee->employee_name }} ({{ $employee->employeeID }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple employees</small>
                            </div>

                            <!-- Branch Filter -->
                            <div class="col-md-6 mb-3" id="branchFilterDiv" style="display: {{ request('employee_filter') == 'branch' ? 'block' : 'none' }};">
                                <label class="font-weight-bold">Select Branch (Main Station)</label>
                                <select name="mainstation_id" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach($mainstations as $mainstation)
                                        <option value="{{ $mainstation->id }}" {{ request('mainstation_id') == $mainstation->id ? 'selected' : '' }}>
                                            {{ $mainstation->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Department Filter -->
                            <div class="col-md-6 mb-3" id="departmentFilterDiv" style="display: {{ request('employee_filter') == 'department' ? 'block' : 'none' }};">
                                <label class="font-weight-bold">Select Department</label>
                                <select name="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($payslips->count() > 0)
        <!-- Summary Cards -->
        <div class="row mb-3 no-print">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Employees</h6>
                        <h3 class="text-info">{{ $payslips->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Gross Salary</h6>
                        <h3 class="text-success">{{ number_format($payslips->sum('gross_salary'), 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Deductions</h6>
                        <h3 class="text-danger">{{ number_format($payslips->sum('total_deductions'), 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Net Salary</h6>
                        <h3 class="text-primary">{{ number_format($payslips->sum('net_salary'), 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payslips -->
        <div class="row">
            @foreach($payslips as $payslip)
                <div class="col-md-12 mb-4 payslip-page">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="row mb-3 border-bottom pb-3">
                                <div class="col-md-12 text-center">
                                    <h3 class="mb-1">{{ $payslip->employee->company->company_name ?? 'Company Name' }}</h3>
                                    <h5 class="text-muted mb-0">PAYSLIP</h5>
                                    <p class="text-muted mb-0">
                                        Period: {{ $payslip->payrollPeriod->period_name ?? 'N/A' }} 
                                        ({{ date('d M Y', strtotime($payslip->payrollPeriod->start_date ?? '')) }} - 
                                        {{ date('d M Y', strtotime($payslip->payrollPeriod->end_date ?? '')) }})
                                    </p>
                                </div>
                            </div>

                            <!-- Employee Information -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="font-weight-bold" width="150">Employee Name:</td>
                                            <td>{{ $payslip->employee->employee_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Employee ID:</td>
                                            <td>{{ $payslip->employee->employeeID ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Department:</td>
                                            <td>{{ $payslip->employee->department->department->department_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Job Title:</td>
                                            <td>{{ $payslip->employee->department->jobtitle->job_title ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="font-weight-bold" width="150">Branch:</td>
                                            <td>{{ $payslip->employee->department->mainstation->station_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Tax Rate:</td>
                                            <td>{{ $payslip->employee->taxRate->tax_name ?? 'N/A' }} ({{ $payslip->employee->taxRate->rate ?? 0 }}%)</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">TIN Number:</td>
                                            <td>{{ $payslip->employee->tin_no ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Payment Date:</td>
                                            <td>{{ $payslip->paid_at ? date('d M Y', strtotime($payslip->paid_at)) : 'Not Paid' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Earnings and Deductions -->
                            <div class="row">
                                <!-- Earnings -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold bg-light p-2">EARNINGS</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Description</th>
                                                <th class="text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Basic Salary</td>
                                                <td class="text-right">{{ number_format($payslip->basic_salary, 2) }}</td>
                                            </tr>
                                            @if($payslip->taxable_allowances > 0)
                                            <tr>
                                                <td>Taxable Allowances</td>
                                                <td class="text-right">{{ number_format($payslip->taxable_allowances, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->non_taxable_allowances > 0)
                                            <tr>
                                                <td>Non-Taxable Allowances</td>
                                                <td class="text-right">{{ number_format($payslip->non_taxable_allowances, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->overtime_amount > 0)
                                            <tr>
                                                <td>Overtime</td>
                                                <td class="text-right">{{ number_format($payslip->overtime_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->bonus > 0)
                                            <tr>
                                                <td>Bonus</td>
                                                <td class="text-right">{{ number_format($payslip->bonus, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr class="font-weight-bold bg-light">
                                                <td>GROSS SALARY</td>
                                                <td class="text-right">{{ number_format($payslip->gross_salary, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Deductions -->
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold bg-light p-2">DEDUCTIONS</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Description</th>
                                                <th class="text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($payslip->tax_deduction > 0)
                                            <tr>
                                                <td>PAYE Tax</td>
                                                <td class="text-right">{{ number_format($payslip->tax_deduction, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->employee_pension_amount > 0)
                                            <tr>
                                                <td>Employee Pension</td>
                                                <td class="text-right">{{ number_format($payslip->employee_pension_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->wcf_amount > 0)
                                            <tr>
                                                <td>WCF</td>
                                                <td class="text-right">{{ number_format($payslip->wcf_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->sdl_amount > 0)
                                            <tr>
                                                <td>SDL</td>
                                                <td class="text-right">{{ number_format($payslip->sdl_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->loan_deduction > 0)
                                            <tr>
                                                <td>Loan Deduction</td>
                                                <td class="text-right">{{ number_format($payslip->loan_deduction, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->advance_salary > 0)
                                            <tr>
                                                <td>Advance Salary</td>
                                                <td class="text-right">{{ number_format($payslip->advance_salary, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->absent_late_deduction > 0)
                                            <tr>
                                                <td>Absent/Late Deduction</td>
                                                <td class="text-right">{{ number_format($payslip->absent_late_deduction, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->normal_deduction > 0)
                                            <tr>
                                                <td>Normal Deduction</td>
                                                <td class="text-right">{{ number_format($payslip->normal_deduction, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($payslip->other_deductions > 0)
                                            <tr>
                                                <td>Other Deductions</td>
                                                <td class="text-right">{{ number_format($payslip->other_deductions, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr class="font-weight-bold bg-light">
                                                <td>TOTAL DEDUCTIONS</td>
                                                <td class="text-right">{{ number_format($payslip->total_deductions, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Net Salary -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="alert alert-success text-center">
                                        <h4 class="mb-0">
                                            <strong>NET SALARY: TZS {{ number_format($payslip->net_salary, 2) }}</strong>
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="row mt-3 border-top pt-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><small><strong>Taxable Income:</strong> {{ number_format($payslip->taxable_income, 2) }}</small></p>
                                    @if($payslip->employer_pension_amount > 0)
                                    <p class="mb-1"><small><strong>Employer Pension:</strong> {{ number_format($payslip->employer_pension_amount, 2) }}</small></p>
                                    @endif
                                </div>
                                <div class="col-md-6 text-right">
                                    <p class="mb-1"><small><strong>Status:</strong> 
                                        <span class="badge badge-{{ $payslip->status == 'paid' ? 'success' : ($payslip->status == 'processed' ? 'info' : 'warning') }}">
                                            {{ ucfirst($payslip->status) }}
                                        </span>
                                    </small></p>
                                    <p class="mb-1"><small><strong>Processed Date:</strong> {{ $payslip->processed_at ? date('d M Y', strtotime($payslip->processed_at)) : 'N/A' }}</small></p>
                                </div>
                            </div>

                            @if($payslip->notes)
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p class="mb-0"><small><strong>Notes:</strong> {{ $payslip->notes }}</small></p>
                                </div>
                            </div>
                            @endif

                            <!-- Footer -->
                            <div class="row mt-4 border-top pt-3">
                                <div class="col-md-12 text-center">
                                    <p class="text-muted mb-0"><small>This is a computer-generated payslip and does not require a signature.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif(request()->filled('period_id'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    <i class="fe fe-info mr-2"></i> No payslips found matching the selected criteria.
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning text-center">
                    <i class="fe fe-alert-triangle mr-2"></i> Please select a payroll period and click "Get Report" to view payslips.
                </div>
            </div>
        </div>
    @endif

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .payslip-page {
                page-break-after: always;
            }
            .payslip-page:last-child {
                page-break-after: auto;
            }
            .card {
                border: 1px solid #000 !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeFilterType = document.getElementById('employeeFilterType');
            const selectedEmployeesDiv = document.getElementById('selectedEmployeesDiv');
            const branchFilterDiv = document.getElementById('branchFilterDiv');
            const departmentFilterDiv = document.getElementById('departmentFilterDiv');

            employeeFilterType.addEventListener('change', function() {
                // Hide all conditional filters
                selectedEmployeesDiv.style.display = 'none';
                branchFilterDiv.style.display = 'none';
                departmentFilterDiv.style.display = 'none';

                // Show the selected filter
                switch(this.value) {
                    case 'selected':
                        selectedEmployeesDiv.style.display = 'block';
                        break;
                    case 'branch':
                        branchFilterDiv.style.display = 'block';
                        break;
                    case 'department':
                        departmentFilterDiv.style.display = 'block';
                        break;
                }
            });
        });
    </script>
</x-app-layout>
