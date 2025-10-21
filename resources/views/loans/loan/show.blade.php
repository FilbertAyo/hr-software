<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Loan Details</h4>
                    <a href="{{ route('loan.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fe fe-arrow-left fe-16"></i> Back to Loans
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
                                <input type="text" class="form-control" value="{{ ucfirst($loan->status) }}" disabled>
                            </div>
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
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Setup Loan Installments</h5>
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
                                                $currentDate = \Carbon\Carbon::parse($currentPayrollPeriod->period_start);
                                                // Show next 3 months
                                                for ($i = 1; $i <= 3; $i++) {
                                                    $nextMonth = $currentDate->copy()->addMonths($i);
                                                    echo '<option value="' . $nextMonth->format('Y-m-01') . '">' . $nextMonth->format('F Y') . '</option>';
                                                }
                                            @endphp
                                        @else
                                            @php
                                                // Fallback if no payroll period
                                                $today = \Carbon\Carbon::now();
                                                for ($i = 1; $i <= 3; $i++) {
                                                    $nextMonth = $today->copy()->addMonths($i);
                                                    echo '<option value="' . $nextMonth->format('Y-m-01') . '">' . $nextMonth->format('F Y') . '</option>';
                                                }
                                            @endphp
                                        @endif
                                    </select>
                                    <small class="text-muted">Select when first payment should start (next 3 months only)</small>
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
