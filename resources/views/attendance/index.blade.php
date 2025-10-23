<x-app-layout>
    <!-- Page Header -->
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="page-title">Attendance Management</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attendance Management</li>
                        </ol>
                    </div>
                    <div class="col-md-6 text-end">
                        @if($payrollPeriod)
                        <div class="btn-group" role="group">
                            <a href="{{ route('attendance.create', ['payroll_period_id' => $payrollPeriod->id]) }}" class="btn btn-primary">
                                <i class="fe fe-plus"></i> Add Attendance
                            </a>
                            <a href="{{ route('attendance.bulk-create', ['payroll_period_id' => $payrollPeriod->id]) }}" class="btn btn-success">
                                <i class="fe fe-upload"></i> Bulk Add
                            </a>
                            <a href="{{ route('attendance.export', ['payroll_period_id' => $payrollPeriod->id]) }}" class="btn btn-info">
                                <i class="fe fe-download"></i> Export CSV
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Selection -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Select Payroll Period</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('attendance.index') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="payroll_period_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">Select a payroll period</option>
                                    @foreach($payrollPeriods as $period)
                                    <option value="{{ $period->id }}" {{ ($payrollPeriod && $payrollPeriod->id == $period->id) ? 'selected' : '' }}>
                                        {{ $period->period_name }} ({{ $period->start_date->format('M d') }} - {{ $period->end_date->format('M d, Y') }})
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

    @if($payrollPeriod)
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="h5 mb-0">{{ $attendanceStats['total_employees'] }}</div>
                            <p class="text-muted mb-0">Total Employees</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fe fe-users text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="h5 mb-0 text-danger">{{ $attendanceStats['employees_with_absent'] }}</div>
                            <p class="text-muted mb-0">Employees Absent</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fe fe-user-x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="h5 mb-0 text-warning">{{ $attendanceStats['employees_with_late'] }}</div>
                            <p class="text-muted mb-0">Employees Late</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fe fe-clock text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="h5 mb-0 text-info">{{ number_format($attendanceStats['total_deduction_amount'], 2) }}</div>
                            <p class="text-muted mb-0">Total Deductions</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fe fe-dollar-sign text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Info -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Period: {{ $payrollPeriod->period_name }}</h3>
                    <div class="card-options">
                        <span class="badge badge-info">
                            {{ $payrollPeriod->start_date->format('M d, Y') }} - {{ $payrollPeriod->end_date->format('M d, Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Total Absent Days:</strong> {{ $attendanceStats['total_absent_days'] }}
                        </div>
                        <div class="col-md-3">
                            <strong>Total Late Hours:</strong> {{ $attendanceStats['total_late_hours'] }}
                        </div>
                        <div class="col-md-3">
                            <strong>Working Days:</strong> {{ \Carbon\Carbon::parse($payrollPeriod->start_date)->diffInDaysFiltered(function($date) { return $date->isWeekday(); }, \Carbon\Carbon::parse($payrollPeriod->end_date)) + 1 }}
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $payrollPeriod->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($payrollPeriod->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee Attendance Records</h3>
                </div>
                <div class="card-body">
                    @if($employees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Department</th>
                                    <th>Absent Days</th>
                                    <th>Late Hours</th>
                                    <th>Deduction Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                @php
                                    $absentDays = $employee->absentRecords->sum('used_days');
                                    $lateHours = $employee->lateRecords->sum('amount');
                                    $deduction = 0;
                                    if($absentDays > 0 || $lateHours > 0) {
                                        $dailySalary = $employee->basic_salary / (\Carbon\Carbon::parse($payrollPeriod->start_date)->diffInDaysFiltered(function($date) { return $date->isWeekday(); }, \Carbon\Carbon::parse($payrollPeriod->end_date)) + 1);
                                        $deduction = ($dailySalary * $absentDays) + (($dailySalary / 8) * $lateHours);
                                    }
                                @endphp
                                @if($absentDays > 0 || $lateHours > 0)
                                <tr>
                                    <td>{{ $employee->employeeID }}</td>
                                    <td>{{ $employee->employee_name }}</td>
                                    <td>{{ $employee->department->department->department_name ?? 'N/A' }}</td>
                                    <td>
                                        @if($absentDays > 0)
                                        <span class="badge badge-danger">{{ $absentDays }} days</span>
                                        @else
                                        <span class="badge badge-success">0 days</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lateHours > 0)
                                        <span class="badge badge-warning">{{ $lateHours }} hours</span>
                                        @else
                                        <span class="badge badge-success">0 hours</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($deduction > 0)
                                        <span class="text-danger">TZS {{ number_format($deduction, 2) }}</span>
                                        @else
                                        <span class="text-success">TZS 0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('attendance.show', $employee->id) }}" class="btn btn-sm btn-info">
                                                <i class="fe fe-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fe fe-users fe-48 text-muted"></i>
                        <h4 class="mt-3 text-muted">No employees found</h4>
                        <p class="text-muted">No active employees found for the selected period.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Period Selected -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fe fe-calendar fe-48 text-muted"></i>
                    <h4 class="mt-3 text-muted">Select a Payroll Period</h4>
                    <p class="text-muted">Please select a payroll period to view attendance records.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
    });
    </script>
    @endpush
</x-app-layout>
