<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Loan Details</h4>
                    <div>
                        @if(in_array($loan->status, ['active', 'pending']) && $loan->installments->count() > 0)
                            <a href="{{ route('loan.installments.edit', $loan->id) }}" class="btn btn-sm btn-success">
                                <i class="fe fe-edit-2 fe-16"></i> Edit Installments
                            </a>
                        @endif
                        @if($loan->status == 'active')
                            <a href="{{ route('loan.restructure', $loan->id) }}" class="btn btn-sm btn-warning">
                                <i class="fe fe-edit fe-16"></i> Restructure Loan
                            </a>
                            @if($loan->is_restructured)
                                <a href="{{ route('loan.history', $loan->id) }}" class="btn btn-sm btn-info">
                                    <i class="fe fe-clock fe-16"></i> View History ({{ $loan->restructure_count }})
                                </a>
                            @endif
                        @endif
                        <a href="{{ route('loan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-arrow-left fe-16"></i> Back to Loans
                        </a>
                    </div>
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

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Loan Details -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Loan Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Employee</label>
                                <input type="text" class="form-control" value="{{ $loan->employee->employee_name }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Loan Type</label>
                                <input type="text" class="form-control" value="{{ $loan->loanType->loan_type_name }}" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Loan Amount</label>
                                <input type="text" class="form-control" value="{{ number_format($loan->loan_amount, 2) }}" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Remaining Amount</label>
                                <input type="text" class="form-control" value="{{ number_format($loan->remaining_amount, 2) }}" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Status</label>
                                <div>
                                    @if($loan->status == 'pending')
                                        <span class="badge badge-warning badge-lg">Pending</span>
                                    @elseif($loan->status == 'active')
                                        <span class="badge badge-success badge-lg">Active</span>
                                        @if($loan->is_restructured)
                                            <br><span class="badge badge-info mt-1">Restructured {{ $loan->restructure_count }}x</span>
                                        @endif
                                    @elseif($loan->status == 'completed')
                                        <span class="badge badge-info badge-lg">Completed</span>
                                    @elseif($loan->status == 'rejected')
                                        <span class="badge badge-danger badge-lg">Rejected</span>
                                    @else
                                        <span class="badge badge-secondary badge-lg">{{ ucfirst($loan->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($loan->payrollPeriod)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Payroll Period</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($loan->payrollPeriod->start_date)->format('F Y') }}" disabled>
                                </div>
                            @endif
                            @if($loan->approved_at)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Approved</label>
                                    <input type="text" class="form-control" value="{{ $loan->approved_at->format('M d, Y') }} by {{ $loan->approvedBy ? $loan->approvedBy->name : 'System' }}" disabled>
                                </div>
                            @endif
                            @if($loan->rejected_at)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Rejected</label>
                                    <input type="text" class="form-control" value="{{ $loan->rejected_at->format('M d, Y') }} by {{ $loan->rejectedBy ? $loan->rejectedBy->name : 'System' }}" disabled>
                                </div>
                            @endif
                            @if($loan->rejection_reason)
                                <div class="col-12 mb-3">
                                    <label class="form-label font-weight-bold">Rejection Reason</label>
                                    <textarea class="form-control" rows="2" disabled>{{ $loan->rejection_reason }}</textarea>
                                </div>
                            @endif
                            <div class="col-12 mb-3">
                                <label class="form-label font-weight-bold">Reason/Notes</label>
                                <textarea class="form-control" rows="2" disabled>{{ $loan->notes ?? 'N/A' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Installments Table -->
                @if($loan->installments->count() > 0)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Installment Schedule</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Installment #</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Paid Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loan->installments as $inst)
                                        <tr>
                                            <td>{{ $inst->installment_number }}</td>
                                            <td>{{ number_format($inst->amount, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($inst->due_date)->format('M Y') }}</td>
                                            <td>{{ $inst->paid_date ? \Carbon\Carbon::parse($inst->paid_date)->format('M d, Y') : '-' }}</td>
                                            <td>
                                                @if($inst->status == 'paid')
                                                    <span class="badge badge-success">Paid</span>
                                                @elseif($inst->status == 'overdue')
                                                    <span class="badge badge-danger">Overdue</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Setup Installments Form -->
                @if($loan->installments->count() == 0 && $loan->status == 'pending')
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Choose Installment Setup Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <i class="fe fe-layers fe-48 text-info mb-3"></i>
                                        <h5>Automatic Installments</h5>
                                        <p class="text-muted">Equal monthly payments automatically calculated</p>
                                        <button type="button" class="btn btn-info" onclick="document.getElementById('autoInstallmentCard').scrollIntoView({behavior: 'smooth'})">
                                            <i class="fe fe-settings"></i> Setup Auto Installments
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <i class="fe fe-edit-3 fe-48 text-success mb-3"></i>
                                        <h5>Custom Installments</h5>
                                        <p class="text-muted">Manually set different amounts and dates</p>
                                        <a href="{{ route('loan.installments.custom', $loan->id) }}" class="btn btn-success">
                                            <i class="fe fe-edit"></i> Create Custom Schedule
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm" id="autoInstallmentCard">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Setup Automatic Installments</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('loan.installments.store', $loan->id) }}" method="POST" id="installmentForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="installment_count" class="form-label">Number of Installments <span class="text-danger">*</span></label>
                                    <input type="number" id="installment_count" name="installment_count" class="form-control" min="1" max="60" required>
                                    <small class="text-muted">Enter number of monthly installments (max 60)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="installment_amount" class="form-label">Installment Amount</label>
                                    <input type="text" id="installment_amount" class="form-control" disabled>
                                    <small class="text-muted">Auto-calculated: Remaining Amount ÷ Installments</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Current Payroll Period</label>
                                    <input type="text" class="form-control" value="{{ $currentPayrollPeriod ? \Carbon\Carbon::parse($currentPayrollPeriod->period_start)->format('F Y') : 'Not Set' }}" disabled>
                                    <small class="text-muted">Reference period for payment start</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="start_month" class="form-label">Starting Payment Month <span class="text-danger">*</span></label>
                                    <select id="start_month" name="start_month" class="form-control" required>
                                        <option value="">Select month</option>
                                        @if($currentPayrollPeriod)
                                            @php
                                                $currentDate = \Carbon\Carbon::parse($currentPayrollPeriod->start_date);
                                                // Show current and next 2 months
                                                for ($i = 0; $i <= 2; $i++) {
                                                    $nextMonth = $currentDate->copy()->addMonths($i);
                                                    echo '<option value="' . $nextMonth->format('Y-m-01') . '">' . $nextMonth->format('F Y') . ($i == 0 ? ' (Current)' : '') . '</option>';
                                                }
                                            @endphp
                                        @else
                                            @php
                                                // Fallback if no payroll period
                                                $today = \Carbon\Carbon::now();
                                                for ($i = 0; $i <= 2; $i++) {
                                                    $nextMonth = $today->copy()->addMonths($i);
                                                    echo '<option value="' . $nextMonth->format('Y-m-01') . '">' . $nextMonth->format('F Y') . ($i == 0 ? ' (Current)' : '') . '</option>';
                                                }
                                            @endphp
                                        @endif
                                    </select>
                                    <small class="text-muted">Select when first payment should start (current payroll date and 2 months ahead)</small>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fe fe-info fe-16"></i>
                                <strong>Note:</strong> Once installments are created, the loan status will change to "active" and deductions will be processed monthly.
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-save fe-16"></i> Setup Installments
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- JS to auto calculate installment amount -->
    <script>
        const remainingAmount = {{ $loan->remaining_amount }};
        const installmentCountInput = document.getElementById('installment_count');
        const installmentAmountField = document.getElementById('installment_amount');

        // Calculate installment amount when count changes
        if (installmentCountInput) {
            installmentCountInput.addEventListener('input', function() {
                let count = parseInt(this.value);
                if (count > 0) {
                    const amount = (remainingAmount / count).toFixed(2);
                    installmentAmountField.value = parseFloat(amount).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                } else {
                    installmentAmountField.value = '';
                }
            });
        }
    </script>
</x-app-layout>
