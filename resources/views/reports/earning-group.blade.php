<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Earning Group Report</a>
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
        <div class="col-md-6">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Employees</h6>
                    <h4>{{ number_format($summary['employee_count']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Amount</h6>
                    <h4>{{ number_format($summary['total_amount'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.earning-group') }}" id="filterForm">
                        <input type="hidden" name="get_report" value="1">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Earning Group</label>
                                <select name="earngroup_id" class="form-control">
                                    <option value="all" {{ request('earngroup_id') == 'all' ? 'selected' : '' }}>All Earning Groups</option>
                                    @foreach($earngroups as $earngroup)
                                        <option value="{{ $earngroup->id }}" {{ request('earngroup_id') == $earngroup->id ? 'selected' : '' }}>
                                            {{ $earngroup->earngroup_name }}
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
                    <h5 class="card-title mb-3">Earning Group Records: {{ $employeeEarngroups->count() }}</h5>
                    @if($employeeEarngroups->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered datatables">
                                <thead class="thead-light">
                                    <tr>
                                        <th>SN</th>
                                        <th>Emp ID</th>
                                        <th>Name</th>
                                        <th>Earning Group</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employeeEarngroups as $index => $empEarngroup)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $empEarngroup->employee->employeeID ?? 'N/A' }}</td>
                                            <td>{{ $empEarngroup->employee->employee_name ?? 'N/A' }}</td>
                                            <td>{{ $empEarngroup->earngroup->earngroup_name ?? 'N/A' }}</td>
                                            <td>{{ number_format($empEarngroup->total_amount ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="thead-light">
                                    <tr>
                                        <th colspan="4" class="text-right">Total:</th>
                                        <th>{{ number_format($summary['total_amount'], 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fe fe-info mr-2"></i> Please select filters and click "Get Report" to view the data.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
