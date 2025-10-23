<x-app-layout>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0"><i class="fe fe-edit-3"></i> Custom Installment Setup</h4>
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
                            <div class="col-md-3">
                                <p><strong>Loan Amount:</strong><br>
                                    <span class="text-primary font-weight-bold" id="loanAmount">{{ number_format($loan->remaining_amount, 2) }}</span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Current Payroll:</strong><br>
                                    {{ $currentPayrollPeriod ? \Carbon\Carbon::parse($currentPayrollPeriod->start_date)->format('F Y') : 'Not Set' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balance Indicator -->
                <div class="card mb-4 shadow-sm" id="balanceCard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="mb-0">Total Installments:</h6>
                                <h4 class="mb-0" id="totalInstallments">0.00</h4>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-0">Remaining to Allocate:</h6>
                                <h4 class="mb-0" id="remainingAmount">{{ number_format($loan->remaining_amount, 2) }}</h4>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-0">Status:</h6>
                                <h4 class="mb-0">
                                    <span id="balanceStatus" class="badge badge-warning">Not Balanced</span>
                                </h4>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 30px;">
                            <div id="balanceProgress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <span id="balancePercentage">0%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Installments Form -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fe fe-list"></i> Custom Installment Schedule</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('loan.installments.custom.store', $loan->id) }}" method="POST" id="customInstallmentsForm">
                            @csrf

                            <div class="alert alert-info">
                                <i class="fe fe-info"></i>
                                <strong>Instructions:</strong>
                                <ul class="mb-0">
                                    <li>Set custom amounts and dates for each installment</li>
                                    <li>Total of all installments must equal the loan amount</li>
                                    <li>Installments with 0 amount will be ignored</li>
                                    <li>Click "Add Installment" to add more rows</li>
                                    <li>Minimum 1 installment required</li>
                                </ul>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="installmentsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="10%">#</th>
                                            <th width="35%">Amount <span class="text-danger">*</span></th>
                                            <th width="45%">Due Date (Month & Year) <span class="text-danger">*</span></th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="installmentsBody">
                                        <!-- Initial 3 rows -->
                                        @for($i = 0; $i < 5; $i++)
                                        <tr class="installment-row">
                                            <td class="align-middle text-center installment-number">{{ $i + 1 }}</td>
                                            <td>
                                                <input type="number"
                                                       step="0.01"
                                                       min="0"
                                                       name="installments[{{ $i }}][amount]"
                                                       class="form-control installment-amount"
                                                       value="{{ old('installments.'.$i.'.amount', 0) }}"
                                                       placeholder="Enter amount"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="month"
                                                       name="installments[{{ $i }}][due_date]"
                                                       class="form-control installment-date"
                                                       value="{{ old('installments.'.$i.'.due_date', \Carbon\Carbon::now()->addMonths($i)->format('Y-m')) }}"
                                                       required>
                                            </td>
                                            <td class="text-center">
                                                @if($i >= 3)
                                                <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove">
                                                    <i class="fe fe-trash"></i>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button type="button" class="btn btn-secondary" id="addInstallmentBtn">
                                    <i class="fe fe-plus"></i> Add Installment
                                </button>

                                <div>
                                    <button type="button" class="btn btn-info" id="autoFillBtn" title="Auto-fill remaining amount">
                                        <i class="fe fe-zap"></i> Auto-Fill Remaining
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fe fe-save"></i> Save Custom Installments
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const loanAmount = {{ $loan->remaining_amount }};
        let installmentCounter = {{ 5 }};

        // Calculate balance
        function calculateBalance() {
            let total = 0;
            document.querySelectorAll('.installment-amount').forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });

            const remaining = loanAmount - total;
            const percentage = (total / loanAmount) * 100;

            // Update display
            document.getElementById('totalInstallments').textContent = total.toFixed(2);
            document.getElementById('remainingAmount').textContent = remaining.toFixed(2);
            document.getElementById('balancePercentage').textContent = Math.round(percentage) + '%';

            // Update progress bar
            const progressBar = document.getElementById('balanceProgress');
            progressBar.style.width = Math.min(percentage, 100) + '%';
            progressBar.setAttribute('aria-valuenow', Math.min(percentage, 100));

            // Update status and colors
            const statusBadge = document.getElementById('balanceStatus');
            const balanceCard = document.getElementById('balanceCard');
            const submitBtn = document.getElementById('submitBtn');

            if (Math.abs(remaining) < 0.01) { // Balanced
                statusBadge.textContent = 'Balanced ✓';
                statusBadge.className = 'badge badge-success badge-lg';
                progressBar.className = 'progress-bar bg-success';
                balanceCard.className = 'card mb-4 shadow-sm border-success';
                submitBtn.disabled = false;
            } else if (remaining > 0) { // Under-allocated
                statusBadge.textContent = 'Under-allocated';
                statusBadge.className = 'badge badge-warning badge-lg';
                progressBar.className = 'progress-bar bg-warning';
                balanceCard.className = 'card mb-4 shadow-sm border-warning';
                submitBtn.disabled = true;
            } else { // Over-allocated
                statusBadge.textContent = 'Over-allocated!';
                statusBadge.className = 'badge badge-danger badge-lg';
                progressBar.className = 'progress-bar bg-danger';
                balanceCard.className = 'card mb-4 shadow-sm border-danger';
                submitBtn.disabled = true;
            }
        }

        // Add installment row
        document.getElementById('addInstallmentBtn').addEventListener('click', function() {
            const tbody = document.getElementById('installmentsBody');
            const newRow = document.createElement('tr');
            newRow.className = 'installment-row';

            const nextMonth = new Date();
            nextMonth.setMonth(nextMonth.getMonth() + installmentCounter);
            const monthValue = nextMonth.toISOString().slice(0, 7);

            newRow.innerHTML = `
                <td class="align-middle text-center installment-number">${installmentCounter + 1}</td>
                <td>
                    <input type="number"
                           step="0.01"
                           min="0"
                           name="installments[${installmentCounter}][amount]"
                           class="form-control installment-amount"
                           value="0"
                           placeholder="Enter amount"
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
                    <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove">
                        <i class="fe fe-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(newRow);
            installmentCounter++;

            // Update row numbers
            updateRowNumbers();

            // Add event listener to new amount input
            newRow.querySelector('.installment-amount').addEventListener('input', calculateBalance);

            // Add event listener to remove button
            newRow.querySelector('.remove-row').addEventListener('click', function() {
                newRow.remove();
                updateRowNumbers();
                calculateBalance();
            });

            calculateBalance();
        });

        // Remove row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('.installment-row').remove();
                updateRowNumbers();
                calculateBalance();
            }
        });

        // Update row numbers
        function updateRowNumbers() {
            document.querySelectorAll('.installment-number').forEach((el, index) => {
                el.textContent = index + 1;
            });
        }

        // Auto-fill remaining amount
        document.getElementById('autoFillBtn').addEventListener('click', function() {
            let total = 0;
            const inputs = document.querySelectorAll('.installment-amount');

            // Calculate current total
            inputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            const remaining = loanAmount - total;

            if (remaining > 0) {
                // Find first empty (0) installment and fill it
                for (let input of inputs) {
                    if (parseFloat(input.value) === 0) {
                        input.value = remaining.toFixed(2);
                        calculateBalance();
                        return;
                    }
                }

                // If no empty found, add to last installment
                if (inputs.length > 0) {
                    const lastInput = inputs[inputs.length - 1];
                    lastInput.value = (parseFloat(lastInput.value) + remaining).toFixed(2);
                    calculateBalance();
                }
            } else if (remaining < 0) {
                alert('Total is already over the loan amount. Please reduce some installments first.');
            } else {
                alert('Balance is already complete!');
            }
        });

        // Add event listeners to all amount inputs
        document.querySelectorAll('.installment-amount').forEach(input => {
            input.addEventListener('input', calculateBalance);
        });

        // Initial calculation
        calculateBalance();

        // Form validation
        document.getElementById('customInstallmentsForm').addEventListener('submit', function(e) {
            let total = 0;
            document.querySelectorAll('.installment-amount').forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            const remaining = loanAmount - total;

            if (Math.abs(remaining) > 0.01) {
                e.preventDefault();
                alert(`Total installments must equal the loan amount.\nCurrent total: ${total.toFixed(2)}\nRequired: ${loanAmount.toFixed(2)}\nDifference: ${remaining.toFixed(2)}`);
                return false;
            }
        });
    </script>
</x-app-layout>

