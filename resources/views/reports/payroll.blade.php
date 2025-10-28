<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Payroll Report</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                <i class="fe fe-arrow-left mr-1"></i> Back to Reports
            </a>
            <button type="button" class="btn btn-sm btn-dark" onclick="window.print()">
                <i class="fe fe-printer text-muted"></i> Print
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Gross Salary</h6>
                    <h4>{{ number_format($summary['total_gross'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Deductions</h6>
                    <h4>{{ number_format($summary['total_deductions'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Net Salary</h6>
                    <h4>{{ number_format($summary['total_net'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.payroll') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Payroll Period <span class="text-danger">*</span></label>
                                <select name="period_id" class="form-control" required>
                                    <option value="">Select Period</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}"
                                            {{ request('period_id') == $period->id ? 'selected' : (isset($currentPeriod) && $currentPeriod && $period->id == $currentPeriod->id && !request('period_id') ? 'selected' : '') }}>
                                            {{ $period->period_name }} ({{ date('M Y', strtotime($period->start_date)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Filter By</label>
                                <select name="filter_type" id="filterType" class="form-control">
                                    <option value="all" {{ request('filter_type') == 'all' ? 'selected' : '' }}>All Employees</option>
                                    <option value="branch" {{ request('filter_type') == 'branch' ? 'selected' : '' }}>Branch Wise</option>
                                    <option value="department" {{ request('filter_type') == 'department' ? 'selected' : '' }}>Department Wise</option>
                                </select>
                            </div>
                            <div class="col-md-2" id="branchFilter" style="display: {{ request('filter_type') == 'branch' ? 'block' : 'none' }};">
                                <label>Select Branch</label>
                                <select name="mainstation_id" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach($mainstations as $mainstation)
                                        <option value="{{ $mainstation->id }}" {{ request('mainstation_id') == $mainstation->id ? 'selected' : '' }}>
                                            {{ $mainstation->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2" id="departmentFilter" style="display: {{ request('filter_type') == 'department' ? 'block' : 'none' }};">
                                <label>Select Department</label>
                                <select name="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tax Rate</label>
                                <select name="tax_rate_id" class="form-control">
                                    <option value="">All Tax Rates</option>
                                    @foreach($taxRates as $taxRate)
                                        <option value="{{ $taxRate->id }}" {{ request('tax_rate_id') == $taxRate->id ? 'selected' : '' }}>
                                            {{ $taxRate->tax_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fe fe-file-text mr-1"></i> Get Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('filterType').addEventListener('change', function() {
            var branchFilter = document.getElementById('branchFilter');
            var departmentFilter = document.getElementById('departmentFilter');

            if (this.value === 'branch') {
                branchFilter.style.display = 'block';
                departmentFilter.style.display = 'none';
            } else if (this.value === 'department') {
                branchFilter.style.display = 'none';
                departmentFilter.style.display = 'block';
            } else {
                branchFilter.style.display = 'none';
                departmentFilter.style.display = 'none';
            }
        });
    </script>

    <div class="row my-2">
        @include('elements.spinner')
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="card-title mb-3">Payroll Records: {{ $payrolls->count() }}</h5>
                    @if($payrolls->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered datatables">
                                <thead class="thead-light">
                                    <tr>
                                        <th>SN</th>
                                        <th>Employee Name</th>
                                        <th>Tax name</th>
                                        <th>Basic Salary</th>
                                        <th>Taxable Allowances</th>
                                        <th>Non-Taxable Allowances</th>
                                        <th>Gross Salary</th>
                                        <th>Pension</th>
                                        <th>Taxable Income</th>
                                        <th>PAYE</th>
                                        <th>Advance</th>
                                        <th>Loan Deduction</th>
                                        <th>Attendance Deduction</th>
                                        <th>Other Deductions</th>
                                        <th>Total Deductions</th>
                                        <th>Net Salary</th>
                                        <th>WCF</th>
                                        <th>SDL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payrolls as $index => $payroll)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $payroll->employee->employee_name ?? 'N/A' }}
                                            </td>
                                            <td>{{ $payroll->employee->taxRate->tax_name ?? 'N/A' }}</td>
                                            <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                                            <td>{{ number_format($payroll->taxable_allowances, 2) }}</td>
                                            <td>{{ number_format($payroll->non_taxable_allowances, 2) }}</td>
                                            <td>{{ number_format($payroll->gross_salary, 2) }}</td>
                                            <td>{{ number_format($payroll->employee_pension_amount, 2) }}</td>
                                            <td>{{ number_format($payroll->taxable_income, 2) }}</td>
                                            <td>{{ number_format($payroll->tax_deduction, 2) }}</td>
                                            <td>{{ number_format($payroll->advance_salary, 2) }}</td>
                                            <td>{{ number_format($payroll->loan_deduction, 2) }}</td>
                                            <td>{{ number_format($payroll->attendance_deduction, 2) }}</td>
                                            <td>{{ number_format($payroll->other_deductions, 2) }}</td>
                                            <td>{{ number_format($payroll->total_deductions, 2) }}</td>
                                            <td><strong>{{ number_format($payroll->net_salary, 2) }}</strong></td>
                                            <td>{{ number_format($payroll->wcf_amount, 2) }}</td>
                                            <td>{{ number_format($payroll->sdl_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fe fe-info mr-2"></i> Please select a payroll period and apply filters, then click "Get Report" to view the data.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
