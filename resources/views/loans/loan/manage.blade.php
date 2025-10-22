<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0"><i class="fe fe-settings"></i> Loan Management & Approval</h4>
                    <a href="{{ route('loan.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fe fe-arrow-left fe-16"></i> Back to Loans List
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Tabs for different loan statuses -->
                <ul class="nav nav-tabs" id="loanManagementTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                            Pending Approval
                            @if($pendingLoans->count() > 0)
                                <span class="badge badge-warning">{{ $pendingLoans->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="active-tab" data-toggle="tab" href="#active" role="tab">
                            Active Loans
                            <span class="badge badge-success">{{ $activeLoans->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab">
                            Rejected Loans
                            <span class="badge badge-danger">{{ $rejectedLoans->count() }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="loanManagementTabsContent">
                    <!-- Pending Loans Tab -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="card shadow-sm mt-3">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0">Loans Awaiting Approval</h5>
                            </div>
                            <div class="card-body">
                                @if($pendingLoans->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Employee</th>
                                                    <th>Loan Type</th>
                                                    <th>Amount</th>
                                                    <th>Request Date</th>
                                                    <th>Installments Set?</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pendingLoans as $index => $loan)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $loan->employee->employee_name }}</td>
                                                        <td>{{ $loan->loanType->loan_type_name }}</td>
                                                        <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                                        <td>{{ $loan->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            @if($loan->installments->count() > 0)
                                                                <span class="badge badge-success">Yes ({{ $loan->installment_count }})</span>
                                                            @else
                                                                <span class="badge badge-secondary">No</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                                <i class="fe fe-eye"></i>
                                                            </a>
                                                            <form action="{{ route('loan.approve', $loan->id) }}" method="POST" style="display:inline" title="Approve Loan">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success"
                                                                        onclick="return confirm('Approve this loan?')">
                                                                    <i class="fe fe-check"></i>
                                                                </button>
                                                            </form>
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                    data-toggle="modal" data-target="#rejectModal{{ $loan->id }}" title="Reject Loan">
                                                                <i class="fe fe-x"></i>
                                                            </button>

                                                            <!-- Reject Modal -->
                                                            <div class="modal fade" id="rejectModal{{ $loan->id }}" tabindex="-1" role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-danger text-white">
                                                                            <h5 class="modal-title">Reject Loan</h5>
                                                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                                                <span>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="{{ route('loan.reject', $loan->id) }}" method="POST">
                                                                            @csrf
                                                                            <div class="modal-body">
                                                                                <p><strong>Employee:</strong> {{ $loan->employee->employee_name }}</p>
                                                                                <p><strong>Amount:</strong> {{ number_format($loan->loan_amount, 2) }}</p>
                                                                                <div class="form-group">
                                                                                    <label for="rejection_reason{{ $loan->id }}">Reason for Rejection <span class="text-danger">*</span></label>
                                                                                    <textarea id="rejection_reason{{ $loan->id }}" name="rejection_reason"
                                                                                              class="form-control" rows="4" required
                                                                                              placeholder="Enter reason for rejecting this loan..."></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                <button type="submit" class="btn btn-danger">Reject Loan</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fe fe-info"></i> No pending loans awaiting approval.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Active Loans Tab -->
                    <div class="tab-pane fade" id="active" role="tabpanel">
                        <div class="card shadow-sm mt-3">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Active Loans</h5>
                            </div>
                            <div class="card-body">
                                @if($activeLoans->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Employee</th>
                                                    <th>Loan Type</th>
                                                    <th>Original Amount</th>
                                                    <th>Remaining</th>
                                                    <th>Current Installment</th>
                                                    <th>Payroll Period</th>
                                                    <th>Installments</th>
                                                    <th>Progress</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($activeLoans as $index => $loan)
                                                    @php
                                                        $paidCount = $loan->installments->where('status', 'paid')->count();
                                                        $totalCount = $loan->installment_count;
                                                        $progressPercent = $totalCount > 0 ? ($paidCount / $totalCount) * 100 : 0;

                                                        // Get current month's installment
                                                        $currentMonth = now()->format('Y-m');
                                                        $currentInstallment = $loan->installments->first(function($inst) use ($currentMonth) {
                                                            return \Carbon\Carbon::parse($inst->due_date)->format('Y-m') == $currentMonth;
                                                        });

                                                        // Get next pending installment if current month not found
                                                        if (!$currentInstallment) {
                                                            $currentInstallment = $loan->installments
                                                                ->where('status', 'pending')
                                                                ->sortBy('due_date')
                                                                ->first();
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $loan->employee->employee_name }}</td>
                                                        <td>{{ $loan->loanType->loan_type_name }}</td>
                                                        <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                                        <td class="text-danger font-weight-bold">{{ number_format($loan->remaining_amount, 2) }}</td>
                                                        <td>
                                                            @if($currentInstallment)
                                                                <strong>{{ number_format($currentInstallment->amount, 2) }}</strong>
                                                                <br>
                                                                <small class="text-muted">
                                                                    {{ \Carbon\Carbon::parse($currentInstallment->due_date)->format('M Y') }}
                                                                    @if($currentInstallment->status == 'paid')
                                                                        <span class="badge badge-success badge-sm">Paid</span>
                                                                    @else
                                                                        <span class="badge badge-warning badge-sm">Pending</span>
                                                                    @endif
                                                                </small>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                // Find the payroll period that matches the current installment's due date
                                                                $payrollPeriod = null;
                                                                if ($currentInstallment) {
                                                                    $installmentMonth = \Carbon\Carbon::parse($currentInstallment->due_date)->format('Y-m');
                                                                    $payrollPeriod = \App\Models\PayrollPeriod::where('company_id', session('selected_company_id'))
                                                                        ->whereRaw("TO_CHAR(start_date, 'YYYY-MM') = ?", [$installmentMonth])
                                                                        ->first();
                                                                }
                                                            @endphp
                                                            @if($payrollPeriod)
                                                                <strong>{{ \Carbon\Carbon::parse($payrollPeriod->start_date)->format('M Y') }}</strong>
                                                                <br>
                                                                <small class="text-muted">
                                                                    {{ \Carbon\Carbon::parse($payrollPeriod->start_date)->format('M d') }} -
                                                                    {{ \Carbon\Carbon::parse($payrollPeriod->end_date)->format('M d, Y') }}
                                                                </small>
                                                            @elseif($loan->payrollPeriod)
                                                                <span class="text-info">
                                                                    {{ \Carbon\Carbon::parse($loan->payrollPeriod->start_date)->format('M Y') }}
                                                                    <br><small>(Loan Start)</small>
                                                                </span>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $paidCount }}/{{ $totalCount }}
                                                            @if($loan->is_restructured)
                                                                <br><span class="badge badge-info">Restructured {{ $loan->restructure_count }}x</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                     style="width: {{ $progressPercent }}%;"
                                                                     aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                                                                    {{ round($progressPercent) }}%
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                                <i class="fe fe-eye"></i>
                                                            </a>
                                                            <a href="{{ route('loan.restructure', $loan->id) }}" class="btn btn-sm btn-warning" title="Restructure">
                                                                <i class="fe fe-edit"></i>
                                                            </a>
                                                            @if($loan->is_restructured)
                                                                <a href="{{ route('loan.history', $loan->id) }}" class="btn btn-sm btn-secondary" title="View History">
                                                                    <i class="fe fe-clock"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fe fe-info"></i> No active loans at the moment.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Loans Tab -->
                    <div class="tab-pane fade" id="rejected" role="tabpanel">
                        <div class="card shadow-sm mt-3">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">Rejected Loans</h5>
                            </div>
                            <div class="card-body">
                                @if($rejectedLoans->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Employee</th>
                                                    <th>Loan Type</th>
                                                    <th>Amount</th>
                                                    <th>Request Date</th>
                                                    <th>Rejected Date</th>
                                                    <th>Rejected By</th>
                                                    <th>Reason</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rejectedLoans as $index => $loan)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $loan->employee->employee_name }}</td>
                                                        <td>{{ $loan->loanType->loan_type_name }}</td>
                                                        <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                                        <td>{{ $loan->created_at->format('M d, Y') }}</td>
                                                        <td>{{ $loan->rejected_at ? $loan->rejected_at->format('M d, Y') : 'N/A' }}</td>
                                                        <td>{{ $loan->rejectedBy ? $loan->rejectedBy->name : 'System' }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-link"
                                                                    data-toggle="modal" data-target="#reasonModal{{ $loan->id }}">
                                                                View Reason
                                                            </button>

                                                            <!-- Reason Modal -->
                                                            <div class="modal fade" id="reasonModal{{ $loan->id }}" tabindex="-1" role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Rejection Reason</h5>
                                                                            <button type="button" class="close" data-dismiss="modal">
                                                                                <span>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p><strong>Employee:</strong> {{ $loan->employee->employee_name }}</p>
                                                                            <p><strong>Amount:</strong> {{ number_format($loan->loan_amount, 2) }}</p>
                                                                            <hr>
                                                                            <p><strong>Rejection Reason:</strong></p>
                                                                            <p>{{ $loan->rejection_reason ?? 'No reason provided.' }}</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                                <i class="fe fe-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fe fe-info"></i> No rejected loans.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function reloadPage() {
            location.reload();
        }
    </script>
</x-app-layout>


