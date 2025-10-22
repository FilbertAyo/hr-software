<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0"><i class="fe fe-edit"></i> Restructure Loan</h4>
                    <div>
                        <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-arrow-left fe-16"></i> Back to Loan Details
                        </a>
                        <a href="{{ route('loan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-list fe-16"></i> All Loans
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

                <!-- Current Loan Information -->
                <div class="card mb-4 shadow-sm border-warning">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="fe fe-info"></i> Current Loan Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Employee</label>
                                <p class="form-control-static">{{ $loan->employee->employee_name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Loan Type</label>
                                <p class="form-control-static">{{ $loan->loanType->loan_type_name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Original Loan Amount</label>
                                <p class="form-control-static text-primary font-weight-bold">{{ number_format($loan->loan_amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold">Remaining Amount</label>
                                <p class="form-control-static text-danger font-weight-bold">{{ number_format($loan->remaining_amount, 2) }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold">Current Installments</label>
                                <p class="form-control-static">{{ $loan->installment_count }} months</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold">Monthly Payment</label>
                                <p class="form-control-static">{{ number_format($loan->monthly_payment, 2) }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold">Restructured Before</label>
                                <p class="form-control-static">
                                    @if($loan->is_restructured)
                                        <span class="badge badge-info">Yes ({{ $loan->restructure_count }}x)</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Installment Schedule Summary -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fe fe-calendar"></i> Current Installment Schedule</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Paid Installments:</strong> {{ $loan->installments->where('status', 'paid')->count() }}</p>
                                <p><strong>Pending Installments:</strong> {{ $loan->installments->where('status', 'pending')->count() }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Start Date:</strong> {{ $loan->start_date ? \Carbon\Carbon::parse($loan->start_date)->format('M d, Y') : 'N/A' }}</p>
                                <p><strong>End Date:</strong> {{ $loan->end_date ? \Carbon\Carbon::parse($loan->end_date)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restructure Form -->
                <div class="card shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fe fe-repeat"></i> New Restructure Terms</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('loan.restructure.process', $loan->id) }}" method="POST" id="restructureForm">
                            @csrf

                            <div class="alert alert-warning">
                                <i class="fe fe-alert-triangle fe-16"></i>
                                <strong>Important:</strong> Restructuring will delete all pending (unpaid) installments and create new ones based on the remaining amount. Paid installments will remain unchanged.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="new_installment_count" class="form-label">New Number of Installments <span class="text-danger">*</span></label>
                                    <input type="number" id="new_installment_count" name="new_installment_count"
                                           class="form-control" min="1" max="60" value="{{ $loan->installment_count }}" required>
                                    <small class="text-muted">Enter new number of monthly installments (1-60)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="new_monthly_payment" class="form-label">New Monthly Payment</label>
                                    <input type="text" id="new_monthly_payment" class="form-control" disabled>
                                    <small class="text-muted">Auto-calculated: Remaining Amount ÷ New Installments</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Current Payroll Period</label>
                                    <input type="text" class="form-control"
                                           value="{{ $currentPayrollPeriod ? \Carbon\Carbon::parse($currentPayrollPeriod->start_date)->format('F Y') : 'Not Set' }}" disabled>
                                    <small class="text-muted">Reference period for payment start</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="new_start_month" class="form-label">New Starting Payment Month <span class="text-danger">*</span></label>
                                    <select id="new_start_month" name="new_start_month" class="form-control" required>
                                        <option value="">Select month</option>
                                        @if($currentPayrollPeriod)
                                            @php
                                                $currentDate = \Carbon\Carbon::parse($currentPayrollPeriod->start_date);
                                                // Show current and next 2 months
                                                for ($i = 0; $i <= 2; $i++) {
                                                    $nextMonth = $currentDate->copy()->addMonths($i);
                                                    echo '<option value="' . $nextMonth->format('Y-m-01') . '">' . $nextMonth->format('F Y') . '</option>';
                                                }
                                            @endphp
                                        @else
                                            @php
                                                // Fallback if no payroll period
                                                $today = \Carbon\Carbon::now();
                                                for ($i = 0; $i <= 2; $i++) {
                                                    $nextMonth = $today->copy()->addMonths($i);
                                                    echo '<option value="' . $nextMonth->format('Y-m-01') . '">' . $nextMonth->format('F Y') . '</option>';
                                                }
                                            @endphp
                                        @endif
                                    </select>
                                    <small class="text-muted">Select when first payment should start (current and next 2 months)</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="reason" class="form-label">Reason for Restructuring <span class="text-danger">*</span></label>
                                    <textarea id="reason" name="reason" class="form-control" rows="4"
                                              placeholder="Explain why this loan needs to be restructured..." required></textarea>
                                    <small class="text-muted">This will be recorded in the loan history</small>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fe fe-info fe-16"></i>
                                <strong>Note:</strong> After restructuring, the loan will maintain "active" status with updated payment schedule. All changes will be logged in the loan history.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-secondary">
                                    <i class="fe fe-x fe-16"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to restructure this loan? This action will modify the payment schedule.')">
                                    <i class="fe fe-check fe-16"></i> Confirm Restructure
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JS to auto calculate new monthly payment -->
    <script>
        const remainingAmount = {{ $loan->remaining_amount }};
        const newInstallmentCountInput = document.getElementById('new_installment_count');
        const newMonthlyPaymentField = document.getElementById('new_monthly_payment');

        // Calculate new monthly payment when count changes
        if (newInstallmentCountInput) {
            newInstallmentCountInput.addEventListener('input', function() {
                let count = parseInt(this.value);
                if (count > 0) {
                    const amount = (remainingAmount / count).toFixed(2);
                    newMonthlyPaymentField.value = parseFloat(amount).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                } else {
                    newMonthlyPaymentField.value = '';
                }
            });

            // Trigger calculation on page load
            newInstallmentCountInput.dispatchEvent(new Event('input'));
        }
    </script>
</x-app-layout>


