<x-app-layout>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0"><i class="fe fe-edit"></i> Edit Loan Installments</h4>
                    <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-sm btn-secondary">
                        <i class="fe fe-arrow-left fe-16"></i> Back to Loan Details
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

                <!-- Loan Summary -->
                <div class="card mb-4 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fe fe-info"></i> Loan Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Employee:</strong><br>{{ $loan->employee->employee_name }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Loan Type:</strong><br>{{ $loan->loanType->loan_type_name }}</p>
                            </div>
                            <div class="col-md-2">
                                <p><strong>Total Amount:</strong><br>
                                    <span class="text-primary font-weight-bold" id="loanAmount">{{ number_format($loan->loan_amount, 2) }}</span>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p><strong>Amount Paid:</strong><br>
                                    <span class="text-success font-weight-bold" id="paidAmount">{{ number_format($paidInstallments->sum('amount'), 2) }}</span>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p><strong>Remaining:</strong><br>
                                    <span class="text-danger font-weight-bold">{{ number_format($loan->remaining_amount, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balance Indicator for Pending Installments -->
                <div class="card mb-4 shadow-sm" id="balanceCard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="mb-0">Total Pending Installments:</h6>
                                <h4 class="mb-0" id="totalPendingInstallments">{{ number_format($pendingInstallments->sum('amount'), 2) }}</h4>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-0">Should Equal (Remaining):</h6>
                                <h4 class="mb-0" id="expectedPendingAmount">{{ number_format($loan->remaining_amount, 2) }}</h4>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-0">Status:</h6>
                                <h4 class="mb-0">
                                    <span id="balanceStatus" class="badge badge-warning">Checking...</span>
                                </h4>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 30px;">
                            <div id="balanceProgress" class="progress-bar" role="progressbar" style="width: 0%;">
                                <span id="balancePercentage">0%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('loan.installments.update', $loan->id) }}" method="POST" id="editInstallmentsForm">
                    @csrf

                    <!-- Paid Installments (Read-Only) -->
                    @if($paidInstallments->count() > 0)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fe fe-check-circle"></i> Paid Installments (Locked - Cannot Modify)</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="10%">#</th>
                                            <th width="30%">Amount</th>
                                            <th width="30%">Due Date</th>
                                            <th width="30%">Paid Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paidInstallments as $installment)
                                        <tr class="bg-light">
                                            <td class="align-middle text-center">{{ $installment->installment_number }}</td>
                                            <td>
                                                <input type="text"
                                                       class="form-control"
                                                       value="{{ number_format($installment->amount, 2) }}"
                                                       disabled
                                                       style="background-color: #e9ecef;">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control"
                                                       value="{{ \Carbon\Carbon::parse($installment->due_date)->format('F Y') }}"
                                                       disabled
                                                       style="background-color: #e9ecef;">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control"
                                                       value="{{ $installment->paid_date ? \Carbon\Carbon::parse($installment->paid_date)->format('M d, Y') : '-' }}"
                                                       disabled
                                                       style="background-color: #e9ecef;">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="alert alert-success mb-0 mt-2">
                                <i class="fe fe-lock"></i>
                                <strong>Note:</strong> These {{ $paidInstallments->count() }} installment(s) have been paid and cannot be modified.
                                Total paid: <strong>{{ number_format($paidInstallments->sum('amount'), 2) }}</strong>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Pending Installments (Editable) -->
                    @if($pendingInstallments->count() > 0)
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0"><i class="fe fe-edit-2"></i> Pending Installments (Editable)</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fe fe-info"></i>
                                <strong>Instructions:</strong>
                                <ul class="mb-0">
                                    <li>You can only modify pending (unpaid) installments</li>
                                    <li>Total of pending installments must equal the remaining loan amount</li>
                                    <li>Paid installments are locked and shown above</li>
                                    <li>Changes will be tracked in loan history</li>
                                </ul>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="pendingInstallmentsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="8%">#</th>
                                            <th width="37%">Amount <span class="text-danger">*</span></th>
                                            <th width="45%">Due Date (Month & Year) <span class="text-danger">*</span></th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pendingInstallmentsBody">
                                        @foreach($pendingInstallments as $installment)
                                        <tr class="pending-row" data-installment-id="{{ $installment->id }}">
                                            <td class="align-middle text-center installment-number">{{ $installment->installment_number }}</td>
                                            <td>
                                                <input type="hidden" name="installments[{{ $loop->index }}][id]" value="{{ $installment->id }}" class="installment-id">
                                                <input type="number"
                                                       step="0.01"
                                                       min="0"
                                                       name="installments[{{ $loop->index }}][amount]"
                                                       class="form-control pending-amount"
                                                       value="{{ $installment->amount }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="month"
                                                       name="installments[{{ $loop->index }}][due_date]"
                                                       class="form-control installment-date"
                                                       value="{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m') }}"
                                                       required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger remove-installment" title="Delete Installment">
                                                    <i class="fe fe-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button type="button" class="btn btn-secondary" id="addInstallmentBtn">
                                    <i class="fe fe-plus"></i> Add Installment
                                </button>
                                <div>
                                    <button type="button" class="btn btn-info" id="autoFillBtn">
                                        <i class="fe fe-zap"></i> Auto-Fill Remaining
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fe fe-save"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fe fe-alert-triangle"></i>
                        <strong>All installments have been paid!</strong> There are no pending installments to modify.
                    </div>
                    @endif
                </form>

            </div>
        </div>
    </div>

    <script>
        const expectedPendingTotal = {{ $loan->remaining_amount }};
        let installmentCounter = {{ $pendingInstallments->count() }};

        // Calculate balance
        function calculateBalance() {
            let total = 0;
            document.querySelectorAll('.pending-amount').forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });

            const difference = total - expectedPendingTotal;
            const percentage = (total / expectedPendingTotal) * 100;

            // Update display
            document.getElementById('totalPendingInstallments').textContent = total.toFixed(2);
            document.getElementById('balancePercentage').textContent = Math.round(percentage) + '%';

            // Update progress bar
            const progressBar = document.getElementById('balanceProgress');
            progressBar.style.width = Math.min(percentage, 100) + '%';
            progressBar.setAttribute('aria-valuenow', Math.min(percentage, 100));

            // Update status and colors
            const statusBadge = document.getElementById('balanceStatus');
            const balanceCard = document.getElementById('balanceCard');
            const submitBtn = document.getElementById('submitBtn');

            if (Math.abs(difference) < 0.01) { // Balanced
                statusBadge.textContent = 'Balanced ✓';
                statusBadge.className = 'badge badge-success badge-lg';
                progressBar.className = 'progress-bar bg-success';
                balanceCard.className = 'card mb-4 shadow-sm border-success';
                submitBtn.disabled = false;
            } else if (total < expectedPendingTotal) { // Under
                statusBadge.textContent = 'Under by ' + Math.abs(difference).toFixed(2);
                statusBadge.className = 'badge badge-warning badge-lg';
                progressBar.className = 'progress-bar bg-warning';
                balanceCard.className = 'card mb-4 shadow-sm border-warning';
                submitBtn.disabled = true;
            } else { // Over
                statusBadge.textContent = 'Over by ' + Math.abs(difference).toFixed(2);
                statusBadge.className = 'badge badge-danger badge-lg';
                progressBar.className = 'progress-bar bg-danger';
                balanceCard.className = 'card mb-4 shadow-sm border-danger';
                submitBtn.disabled = true;
            }
        }

        // Update row numbers
        function updateRowNumbers() {
            document.querySelectorAll('.installment-number').forEach((el, index) => {
                el.textContent = index + 1;
            });
        }

        // Update input names after add/remove
        function updateInputNames() {
            const rows = document.querySelectorAll('.pending-row');
            rows.forEach((row, index) => {
                // Update hidden ID input name
                const idInput = row.querySelector('.installment-id');
                if (idInput) {
                    idInput.name = `installments[${index}][id]`;
                }

                // Update amount input name
                const amountInput = row.querySelector('.pending-amount');
                if (amountInput) {
                    amountInput.name = `installments[${index}][amount]`;
                }

                // Update date input name
                const dateInput = row.querySelector('.installment-date');
                if (dateInput) {
                    dateInput.name = `installments[${index}][due_date]`;
                }
            });
        }

        // Add new installment row
        document.getElementById('addInstallmentBtn').addEventListener('click', function() {
            const tbody = document.getElementById('pendingInstallmentsBody');
            const newRow = document.createElement('tr');
            newRow.className = 'pending-row';
            newRow.setAttribute('data-installment-id', 'new');

            // Get next month from last row's date
            const lastDateInput = tbody.querySelector('.pending-row:last-child .installment-date');
            let nextMonth;
            if (lastDateInput) {
                const lastDate = new Date(lastDateInput.value + '-01');
                nextMonth = new Date(lastDate.getFullYear(), lastDate.getMonth() + 1, 1);
            } else {
                nextMonth = new Date();
                nextMonth.setMonth(nextMonth.getMonth() + 1);
            }
            const monthValue = nextMonth.toISOString().slice(0, 7);

            newRow.innerHTML = `
                <td class="align-middle text-center installment-number">${installmentCounter + 1}</td>
                <td>
                    <input type="hidden" name="installments[${installmentCounter}][id]" value="new" class="installment-id">
                    <input type="number"
                           step="0.01"
                           min="0"
                           name="installments[${installmentCounter}][amount]"
                           class="form-control pending-amount"
                           value="0"
                           required>
                </td>
                <td>
                    <input type="month"
                           name="installments[${installmentCounter}][due_date]"
                           class="form-control installment-date"
                           value="${monthValue}"
                           required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-installment" title="Delete Installment">
                        <i class="fe fe-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(newRow);
            installmentCounter++;

            // Add event listener to new amount input
            newRow.querySelector('.pending-amount').addEventListener('input', calculateBalance);

            // Add event listener to remove button
            newRow.querySelector('.remove-installment').addEventListener('click', removeInstallmentRow);

            updateRowNumbers();
            updateInputNames();
            calculateBalance();
        });

        // Remove installment row
        function removeInstallmentRow(e) {
            const row = e.target.closest('.pending-row');
            const rowCount = document.querySelectorAll('.pending-row').length;

            // Require at least 1 row
            if (rowCount <= 1) {
                alert('You must have at least one installment. Cannot delete all installments.');
                return;
            }

            if (confirm('Are you sure you want to delete this installment?')) {
                row.remove();
                updateRowNumbers();
                updateInputNames();
                calculateBalance();
            }
        }

        // Auto-fill remaining amount
        document.getElementById('autoFillBtn').addEventListener('click', function() {
            let total = 0;
            const inputs = document.querySelectorAll('.pending-amount');

            // Calculate current total
            inputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            const remaining = expectedPendingTotal - total;

            if (remaining > 0) {
                // Find first zero or lowest amount and fill it
                let targetInput = null;
                let lowestValue = Infinity;

                inputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    if (value === 0) {
                        targetInput = input;
                        return;
                    }
                    if (value < lowestValue) {
                        lowestValue = value;
                        targetInput = input;
                    }
                });

                if (targetInput) {
                    const currentValue = parseFloat(targetInput.value) || 0;
                    targetInput.value = (currentValue + remaining).toFixed(2);
                    calculateBalance();
                }
            } else if (remaining < 0) {
                alert('Total is already over the required amount. Please reduce some installments first.');
            } else {
                alert('Balance is already complete!');
            }
        });

        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-installment').forEach(btn => {
            btn.addEventListener('click', removeInstallmentRow);
        });

        // Add event listeners to all pending amount inputs
        document.querySelectorAll('.pending-amount').forEach(input => {
            input.addEventListener('input', calculateBalance);
        });

        // Initial calculation
        calculateBalance();

        // Form validation
        document.getElementById('editInstallmentsForm').addEventListener('submit', function(e) {
            let total = 0;
            document.querySelectorAll('.pending-amount').forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            const difference = total - expectedPendingTotal;

            if (Math.abs(difference) > 0.01) {
                e.preventDefault();
                alert(`Total pending installments must equal the remaining loan amount.\nCurrent total: ${total.toFixed(2)}\nRequired: ${expectedPendingTotal.toFixed(2)}\nDifference: ${difference.toFixed(2)}`);
                return false;
            }
        });
    </script>
</x-app-layout>

