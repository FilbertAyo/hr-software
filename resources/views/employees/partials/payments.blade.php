<div class="card shadow-none border">
    <div class="card-header">
        <strong>Payment Method</strong>
    </div>
    <div class="card-body">


        <div class="form-row mb-3">

            <div class="col-md-12">
                <div class="form-check form-check-inline ">
                    <input class="form-check-input" type="radio" name="payment_method" id="paymentCash" value="cash"
                        {{ old('payment_method') == 'cash' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="paymentCash">Cash</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_method" id="paymentBank" value="bank"
                        {{ old('payment_method') == 'bank' ? 'checked' : '' }}>
                    <label class="form-check-label" for="paymentBank">Bank</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_method" id="paymentBoth" value="both"
                        {{ old('payment_method') == 'both' ? 'checked' : '' }}>
                    <label class="form-check-label" for="paymentBoth">Both (Bank + Cash)</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_method" id="paymentOther" value="other"
                        {{ old('payment_method') == 'other' ? 'checked' : '' }}>
                    <label class="form-check-label" for="paymentOther">Other</label>
                </div>
            </div>
        </div>

        <div id="bankDetails" class="form-row" style="display: none;">
            <div class="col-md-6 mb-3">
                <label for="bankName">Bank Name *</label>
                <select class="form-control" id="bank_id" name="bank_id">
                    <option value="">--Select Bank--</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}"
                            {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                            {{ $bank->bank_name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label for="account_no">Account Number *</label>
                <input type="text" class="form-control" id="account_no" name="account_no">
            </div>
        </div>


    </div>
</div>

<!-- Toggle Bank Details JS -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function toggleBankDetails() {
            let selected = document.querySelector('input[name="payment_method"]:checked')?.value;
            let bankSection = document.getElementById("bankDetails");

            if (selected === "bank" || selected === "both") {
                bankSection.style.display = "flex";
            } else {
                bankSection.style.display = "none";
            }
        }

        // Run once on page load
        toggleBankDetails();

        // Add change listener
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener("change", toggleBankDetails);
        });
    });
</script>
