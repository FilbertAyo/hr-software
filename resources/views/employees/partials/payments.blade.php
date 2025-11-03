
    <div class="card-header">
        <strong>Payment Method</strong>
    </div>
    <div class="card-body">

        <div class="form-row mb-3">

            <div class="col-md-12">
                <div class="form-check form-check-inline ">
                    <input class="form-check-input" type="radio" name="payment_method" id="paymentCash" value="cash"
                        {{ old('payment_method', $employee->payment_method ?? 'cash') == 'cash' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="paymentCash">Cash</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_method" id="paymentBank" value="bank"
                        {{ old('payment_method', $employee->payment_method ?? '') == 'bank' ? 'checked' : '' }}>
                    <label class="form-check-label" for="paymentBank">Bank</label>
                </div>

                {{-- <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_method" id="paymentBoth" value="both"
                        {{ old('payment_method', $employee->payment_method ?? '') == 'both' ? 'checked' : '' }}>
                    <label class="form-check-label" for="paymentBoth">Both (Bank + Cash)</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_method" id="paymentOther" value="other"
                        {{ old('payment_method', $employee->payment_method ?? '') == 'other' ? 'checked' : '' }}>
                    <label class="form-check-label" for="paymentOther">Other</label>
                </div> --}}
            </div>
        </div>

        <div id="bankDetails" class="form-row" style="display: none;">
            <div class="col-md-6 mb-3">
                <label for="bank_id">Bank Name *</label>
                <select class="form-control" id="bank_id" name="bank_id" required>
                    <option value="">--Select Bank--</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}"
                            {{ old('bank_id', $employee->bank_id ?? '') == $bank->id ? 'selected' : '' }}>
                            {{ $bank->bank_name }}
                        </option>
                    @endforeach
                </select>
                @error('bank_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="account_no">Account Number *</label>
                <input type="text" class="form-control" id="account_no" name="account_no"
                       value="{{ old('account_no', $employee->account_no ?? '') }}" required>
                @error('account_no')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function toggleBankDetails() {
            let selected = document.querySelector('input[name="payment_method"]:checked')?.value;
            let bankSection = document.getElementById("bankDetails");
            let bankIdField = document.getElementById("bank_id");
            let accountNoField = document.getElementById("account_no");

            if (selected === "bank" || selected === "both") {
                bankSection.style.display = "flex";
                bankIdField.required = true;
                accountNoField.required = true;
            } else {
                bankSection.style.display = "none";
                bankIdField.required = false;
                accountNoField.required = false;
                // Clear bank fields when hidden
                bankIdField.value = '';
                accountNoField.value = '';
            }
        }

        // Run once on page load
        toggleBankDetails();

        // Add change listener
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener("change", toggleBankDetails);
        });

        // Add form validation
        const form = document.getElementById('employeeForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value;
                const bankIdField = document.getElementById('bank_id');
                const accountNoField = document.getElementById('account_no');

                if (selectedPayment === 'bank' || selectedPayment === 'both') {
                    if (!bankIdField.value) {
                        e.preventDefault();
                        alert('Please select a bank when payment method is Bank or Both.');
                        bankIdField.focus();
                        return false;
                    }
                    if (!accountNoField.value.trim()) {
                        e.preventDefault();
                        alert('Please enter an account number when payment method is Bank or Both.');
                        accountNoField.focus();
                        return false;
                    }
                }
            });
        }
    });
</script>
