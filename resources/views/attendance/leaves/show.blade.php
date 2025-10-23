<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Leave Details</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-16 fe-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-8">
                        <div class="card shadow-none border">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Leave Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Employee:</label>
                                            <p class="form-control-plaintext">
                                                {{ $leave->employee->employee_name ?? 'N/A' }} ({{ $leave->employee->employeeID ?? 'N/A' }})
                                                <span class="badge badge-{{
                                                    $leave->employee->employee_status == 'active' ? 'success' :
                                                    ($leave->employee->employee_status == 'onhold' ? 'warning' : 'danger')
                                                }} ml-2">
                                                    {{ ucfirst($leave->employee->employee_status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Leave Type:</label>
                                            <p class="form-control-plaintext">{{ $leave->leaveType->leave_type_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Leave Action:</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge badge-{{
                                                    $leave->leave_action == 'proceed' ? 'primary' :
                                                    ($leave->leave_action == 'sold' ? 'success' :
                                                    ($leave->leave_action == 'emergency' ? 'danger' : 'info'))
                                                }}">
                                                    {{ ucfirst($leave->leave_action) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Status:</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge badge-{{
                                                    $leave->status == 'Approved' ? 'success' :
                                                    ($leave->status == 'Rejected' ? 'danger' : 'warning')
                                                }}">
                                                    {{ $leave->status }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">From Date:</label>
                                            <p class="form-control-plaintext">{{ date('M d, Y', strtotime($leave->from_date)) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">To Date:</label>
                                            <p class="form-control-plaintext">{{ date('M d, Y', strtotime($leave->to_date)) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Number of Days:</label>
                                            <p class="form-control-plaintext">{{ $leave->no_of_days }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($leave->remarks)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Remarks:</label>
                                            <p class="form-control-plaintext">{{ $leave->remarks }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif


                            </div>
                        </div>
                    </div>

                    <!-- Action Panel -->
                    <div class="col-md-4">
                        <div class="card shadow-none border">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                @if($leave->status == 'Pending')
                                    <div class="d-grid gap-2">
                                        <form action="{{ route('leaves.approve', $leave->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-block"
                                                    onclick="return confirm('Are you sure you want to approve this leave?');">
                                                <i class="fe fe-check fe-16 mr-2"></i>Approve Leave
                                            </button>
                                        </form>

                                        <form action="{{ route('leaves.reject', $leave->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-block"
                                                    onclick="return confirm('Are you sure you want to reject this leave?');">
                                                <i class="fe fe-x fe-16 mr-2"></i>Reject Leave
                                            </button>
                                        </form>
                                    </div>

                                    @if($leave->leave_action === 'proceed')
                                        <div class="alert alert-warning mt-2">
                                            <i class="fe fe-info fe-16 mr-2"></i>
                                            <strong>Note:</strong> Approving this leave will change the employee status to "On Hold".
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-info">
                                        <i class="fe fe-info fe-16 mr-2"></i>
                                        This leave has already been {{ strtolower($leave->status) }}.
                                        @if($leave->status == 'Approved' && $leave->leave_action === 'proceed')
                                            <br><small>The employee status has been updated to "On Hold".</small>
                                        @endif
                                    </div>
                                @endif

                                <hr>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('leaves.edit', $leave->id) }}" class="btn btn-primary btn-block">
                                        <i class="fe fe-edit fe-16 mr-2"></i>Edit Leave
                                    </a>

                                    <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block"
                                                onclick="return confirm('Are you sure you want to delete this leave record?');">
                                            <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete Leave
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
