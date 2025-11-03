<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Attendance Report</a>
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
                    <h6 class="text-muted">Total Records</h6>
                    <h3 class="text-primary">{{ $summary['total_records'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Absent Days</h6>
                    <h3 class="text-danger">{{ $summary['absent_count'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Late Arrivals</h6>
                    <h3 class="text-warning">{{ $summary['late_count'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Present</h6>
                    <h3 class="text-success">{{ $summary['present_count'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.attendance') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Payroll Period</label>
                                <select name="period_id" class="form-control">
                                    <option value="">Current Period</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->period_name }} ({{ date('M Y', strtotime($period->start_date)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Attendance Type</label>
                                <select name="attendance_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="present" {{ request('attendance_type') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ request('attendance_type') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ request('attendance_type') == 'late' ? 'selected' : '' }}>Late</option>
                                </select>
                            </div>
                            <div class="col-md-4">
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
                    <h5 class="card-title mb-3">Attendance Records: {{ $attendances->count() }}</h5>
                    <table class="table table-bordered datatables">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Late Hours</th>
                                <th>Absent Days</th>
                                <th>Hours Worked</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $index => $attendance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $attendance->employee->employee_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->attendance_type == 'present' ? 'success' : ($attendance->attendance_type == 'late' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($attendance->attendance_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->late_hours }}</td>
                                    <td>{{ $attendance->absent_days }}</td>
                                    <td>{{ $attendance->hours_worked }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->status == 'approved' ? 'success' : 'warning' }}">
                                            {{ ucfirst($attendance->status) }}
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
