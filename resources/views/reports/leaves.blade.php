<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Leave Report</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                <i class="fe fe-arrow-left mr-1"></i> Back to Reports
            </a>
            <button type="button" class="btn btn-sm" onclick="window.print()">
                <i class="fe fe-printer text-muted"></i>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Leaves</h6>
                    <h3 class="text-primary">{{ $summary['total_leaves'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Days</h6>
                    <h3 class="text-info">{{ $summary['total_days'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Approved</h6>
                    <h3 class="text-success">{{ $summary['approved'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Pending</h6>
                    <h3 class="text-warning">{{ $summary['pending'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.leaves') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label>To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fe fe-filter mr-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-2">

        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="card-title mb-3">Leave Records: {{ $leaves->count() }}</h5>
                    <table class="table table-bordered datatables">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $index => $leave)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $leave->employee->employee_name ?? 'N/A' }}</td>
                                    <td>{{ $leave->leaveType->leave_type_name ?? 'N/A' }}</td>
                                    <td>{{ date('d M Y', strtotime($leave->from_date)) }}</td>
                                    <td>{{ date('d M Y', strtotime($leave->to_date)) }}</td>
                                    <td>{{ $leave->no_of_days }}</td>
                                    <td>
                                        <span class="badge badge-{{ $leave->status == 'Approved' ? 'success' : ($leave->status == 'Pending' ? 'warning' : 'danger') }}">
                                            {{ $leave->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
