<x-app-layout>
    <div class="container">

        <!-- Loan Details -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Loan Details</h5>
                <div class="row">
                    <div class="col-4 mb-3">
                        <label class="form-label">Employee</label>
                        <input type="text" class="form-control"
                            value="{{ $loan->employee->firstName }} {{ $loan->employee->lastName }}" disabled>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Loan Type</label>
                        <input type="text" class="form-control" value="{{ $loan->loanType->loantype }}" disabled>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Loan Amount</label>
                        <input type="text" class="form-control" value="{{ number_format($loan->loan_amount, 2) }}"
                            disabled>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Remaining Amount</label>
                        <input type="text" class="form-control"
                            value="{{ number_format($loan->remaining_amount, 2) }}" disabled>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" class="form-control" value="{{ $loan->reason ?? 'N/A' }}" disabled>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-body">
                <h5>Installments</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loan->installments as $inst)
                            <tr>
                                <td>{{ $inst->installment_number }}</td>
                                <td>{{ number_format($inst->amount, 2) }}</td>
                                <td>{{ $inst->due_date }}</td>
                                <td>{{ ucfirst($inst->status) }}</td>
                                <td>{{ $inst->remarks }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No installments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Installments Form -->
        <div class="card mt-4">
            <div class="card-body">
                <h5>Add Installments</h5>
                <form action="{{ route('loan.installments.store', $loan->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Number of Installments</label>
                        <input type="number" id="installments_number" name="installments_number" class="form-control"
                            min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Installment Amount</label>
                        <input type="text" id="installment_amount" class="form-control" readonly>
                        <small class="text-muted">Calculated as Loan Amount ÷ Number of Installments</small>
                    </div>

                    <div class="form-group">
                        <label>First Due Date</label>
                        <input type="date" name="due_date" class="form-control" required>
                        <small class="text-muted">This will be the start date for installments</small>
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Installments</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS to auto calculate installment amount -->
    <script>
        const loanAmount = {{ $loan->loan_amount }};
        const installmentsInput = document.getElementById('installments_number');
        const installmentAmountField = document.getElementById('installment_amount');

        installmentsInput.addEventListener('input', function() {
            let num = parseInt(this.value);
            if (num > 0) {
                installmentAmountField.value = (loanAmount / num).toFixed(2);
            } else {
                installmentAmountField.value = '';
            }
        });
    </script>
</x-app-layout>
