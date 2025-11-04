<x-app-layout>
    <div class="container-fluid">
        <!-- Payroll Period Banner -->
        @if($currentPayrollPeriod)
        <div class="alert alert-primary mb-4">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fe fe-calendar fe-24"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="alert-heading mb-1">Current Payroll Period: {{ $currentPayrollPeriod->period_name }}</h5>
                    <p class="mb-0">
                        {{ $currentPayrollPeriod->start_date->format('M d, Y') }} to {{ $currentPayrollPeriod->end_date->format('M d, Y') }}
                        @if($payrollSummary)
                            | {{ $payrollSummary->employee_count }} Employees | 
                            Total Payroll: {{ number_format($payrollSummary->total_net, 2) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Dashboard Stats -->
        <div class="row">
            <!-- Employees Summary -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Employees</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEmployees }}</div>
                                <div class="mt-2">
                                    <span class="badge bg-success text-white">{{ $activeEmployees }} Active</span>
                                    @if($onLeave > 0)
                                        <span class="badge bg-warning text-dark">{{ $onLeave }} On Leave</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fe fe-users fe-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payroll Summary -->
            @if(isset($payrollSummary) && $currentPayrollPeriod)
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Current Payroll</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($payrollSummary->total_net, 2) }}
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        {{ $payrollSummary->employee_count }} employees
                                    </small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fe fe-dollar-sign fe-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Stations Summary -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Stations</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            {{ $mainstation + $substation }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" 
                                                style="width: {{ $mainstation > 0 ? ($mainstation / ($mainstation + $substation) * 100) : 0 }}%" 
                                                aria-valuenow="{{ $mainstation }}" aria-valuemin="0" 
                                                aria-valuemax="{{ $mainstation + $substation }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-info text-white">{{ $mainstation }} Main</span>
                                    <span class="badge bg-secondary text-white">{{ $substation }} Sub</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Users -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    System Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user }}</div>
                                <div class="mt-2">
                                    <span class="badge bg-warning text-dark">{{ $user }} Active</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fe fe-user-check fe-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Rows -->
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <a href="#" class="btn btn-outline-primary btn-block">
                                    <i class="fe fe-plus-circle"></i> New Employee
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="#" class="btn btn-outline-success btn-block">
                                    <i class="fe fe-dollar-sign"></i> Process Payroll
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-info btn-block">
                                    <i class="fe fe-calendar"></i> View Calendar
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-secondary btn-block">
                                    <i class="fe fe-file-text"></i> Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#">View All</a>
                                <a class="dropdown-item" href="#">Mark as Read</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fe fe-user-plus text-success"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>New employee</strong> - John Doe was added to the system.</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fe fe-dollar-sign text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>Payroll processed</strong> - Payroll for {{ $currentPayrollPeriod ? $currentPayrollPeriod->period_name : 'current period' }} was processed.</p>
                                    <small class="text-muted">1 day ago</small>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fe fe-calendar text-warning"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>Leave request</strong> - Jane Smith requested leave from 15-20 Nov 2023.</p>
                                    <small class="text-muted">2 days ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
