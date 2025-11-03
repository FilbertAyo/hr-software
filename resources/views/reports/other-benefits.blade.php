<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Other Benefits Report</a>
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
        <div class="col-md-3">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Employees</h6>
                    <h4>{{ number_format($summary['employee_count']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Amount</h6>
                    <h4>{{ number_format($summary['total_amount'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Taxable Amount</h6>
                    <h4>{{ number_format($summary['taxable_amount'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Non-Taxable Amount</h6>
                    <h4>{{ number_format($summary['non_taxable_amount'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.other-benefits') }}" id="filterForm">
                        <input type="hidden" name="get_report" value="1">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Payroll Period</label>
                                <select name="payroll_period_id" class="form-control">
                                    <option value="all" {{ request('payroll_period_id') == 'all' ? 'selected' : '' }}>All Periods</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ (request('payroll_period_id') == $period->id || $currentPeriod?->id == $period->id) ? 'selected' : '' }}>
                                            {{ $period->period_name }} ({{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Benefit Type</label>
                                <select name="benefit_id" class="form-control">
                                    <option value="all" {{ request('benefit_id') == 'all' ? 'selected' : '' }}>All Benefits</option>
                                    @foreach($otherBenefits as $benefit)
                                        <option value="{{ $benefit->id }}" {{ request('benefit_id') == $benefit->id ? 'selected' : '' }}>
                                            {{ $benefit->other_benefit_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Taxable Status</label>
                                <select name="taxable" class="form-control">
                                    <option value="all" {{ request('taxable') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="yes" {{ request('taxable') == 'yes' ? 'selected' : '' }}>Taxable</option>
                                    <option value="no" {{ request('taxable') == 'no' ? 'selected' : '' }}>Non-Taxable</option>
                                </select>
                            </div>
                            <div class="col-md-2">
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
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                   </i> Filter
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

        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    @if(isset($employeeBenefits) && $employeeBenefits->count() > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Other Benefits Report: {{ $employeeBenefits->count() }} records found</h5>
                            <div>
                                <span class="badge badge-success">Total: {{ number_format($summary['total_amount'], 2) }}</span>
                                <span class="badge badge-info">Taxable: {{ number_format($summary['taxable_amount'], 2) }}</span>
                                <span class="badge badge-secondary">Non-Taxable: {{ number_format($summary['non_taxable_amount'], 2) }}</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered datatables">
                                <thead class="thead-light">
                                    <tr>
                                        <th>SN</th>
                                        <th>Employee ID</th>
                                        <th>Employee Name</th>
                                        <th>Department</th>
                                        <th>Branch</th>
                                        <th>Benefit Type</th>
                                        <th>Benefit Date</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employeeBenefits as $index => $benefit)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $benefit->employee_id_number ?? 'N/A' }}</td>
                                            <td>{{ $benefit->employee_name ?? 'N/A' }}</td>
                                            <td>{{ $benefit->department ?? 'N/A' }}</td>
                                            <td>{{ $benefit->mainstation ?? 'N/A' }}</td>
                                            <td>{{ $benefit->benefit_name ?? 'N/A' }}</td>
                                            <td>{{ $benefit->benefit_date ? \Carbon\Carbon::parse($benefit->benefit_date)->format('d M Y') : 'N/A' }}</td>
                                            <td class="text-right">{{ number_format($benefit->amount, 2) }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="thead-light">
                                    <tr>
                                        <th colspan="7" class="text-right">Total:</th>
                                        <th class="text-right">{{ number_format($summary['total_amount'], 2) }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="7" class="text-right">Taxable Amount:</th>
                                        <th class="text-right">{{ number_format($summary['taxable_amount'], 2) }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="7" class="text-right">Non-Taxable Amount:</th>
                                        <th class="text-right">{{ number_format($summary['non_taxable_amount'], 2) }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fe fe-info mr-2"></i> Please select filters and click "Filter" to view the report.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
