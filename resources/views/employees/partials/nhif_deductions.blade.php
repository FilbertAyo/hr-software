<div class="card shadow-none border">
    <div class="card-header">
        <strong>NHIF Details</strong>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="nhif_enabled" name="nhif_enabled" value="1"
                        {{ old('nhif_enabled', $employee->nhifDetails->enabled ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label" for="nhif_enabled">
                        <strong>NHIF</strong>
                    </label>
                </div>
            </div>
        </div>

        <div id="nhif_details" class="form-row" style="display: none;">
            <div class="col-md-3 mb-3">
                <label for="nhif_start_date">NHIF Start Date</label>
                <input type="date" class="form-control" id="nhif_start_date" name="nhif_start_date"
                    value="{{ old('nhif_start_date', $employee->nhifDetails->start_date ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label for="nhif_employee_no">NHIF Employee No</label>
                <input type="text" class="form-control" id="nhif_employee_no" name="nhif_employee_no"
                    value="{{ old('nhif_employee_no', $employee->nhifDetails->employee_no ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label for="nhif_employer_per">NHIF Employer %</label>
                <input type="number" class="form-control" id="nhif_employer_per" name="nhif_employer_per"
                    value="{{ old('nhif_employer_per', $employee->nhifDetails->employer_percent ?? '3.00') }}"
                    min="0" max="100" step="0.01">
            </div>

            <div class="col-md-3 mb-3">
                <label for="nhif_employee_per">NHIF Employee %</label>
                <input type="number" class="form-control" id="nhif_employee_per" name="nhif_employee_per"
                    value="{{ old('nhif_employee_per', $employee->nhifDetails->employee_percent ?? '3.00') }}"
                    min="0" max="100" step="0.01">
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="nhif_fixed_amount" name="nhif_fixed_amount" value="1"
                        {{ old('nhif_fixed_amount', $employee->nhifDetails->fixed_amount ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label" for="nhif_fixed_amount">
                        NHIF Fixed Amount
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-none border">
    <div class="card-header">
        <strong>Absent Details</strong>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="absent_enabled" name="absent_enabled" value="1"
                        {{ old('absent_enabled', $employee->absentDetails->enabled ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label" for="absent_enabled">
                        <strong>Absent</strong>
                    </label>
                </div>
            </div>
        </div>

        <div id="absent_details" class="form-row" style="display: none;">
            <div class="col-md-12 mb-3">
                <label for="leave_days">Leave Days</label>
                <input type="number" class="form-control" id="leave_days" name="leave_days"
                    value="{{ old('leave_days', $employee->absentDetails->leave_days ?? '') }}" min="0">
                <small class="form-text text-muted">Absent Deduction</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-none border">
    <div class="card-header">
        <strong>Late Details</strong>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="late_enabled" name="late_enabled" value="1"
                        {{ old('late_enabled', $employee->lateDetails->enabled ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label" for="late_enabled">
                        <strong>Late</strong>
                    </label>
                </div>
            </div>
        </div>

        <div id="late_details" class="form-row" style="display: none;">
            <div class="col-md-12 mb-3">
                <label for="deduct_from">Deduct From</label>
                <select class="form-control" id="deduct_from" name="deduct_from">
                    <option value="basic" {{ old('deduct_from', $employee->lateDetails->deduct_from ?? '') == 'basic' ? 'selected' : '' }}>Basic Salary</option>
                    <option value="net" {{ old('deduct_from', $employee->lateDetails->deduct_from ?? '') == 'net' ? 'selected' : '' }}>Net Salary</option>
                </select>
                <small class="form-text text-muted">Deducted From</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-none border">
    <div class="card-header">
        <strong>Payment Details</strong>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="payments_enabled" name="payments_enabled" value="1"
                        {{ old('payments_enabled', $employee->paymentDetails->enabled ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label" for="payments_enabled">
                        <strong>Payments</strong>
                    </label>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="dynamic_payments" name="dynamic_payments" value="1"
                        {{ old('dynamic_payments', $employee->paymentDetails->dynamic_payments ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label" for="dynamic_payments">
                        Dynamic Payments Paid in Rates
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-none border">
    <div class="card-header">
        <strong>Deduction Details</strong>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label for="heslb_index_no">HESLB Index No</label>
                <input type="text" class="form-control" id="heslb_index_no" name="heslb_index_no"
                    value="{{ old('heslb_index_no', $employee->deductionDetails->heslb_index_no ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="heslb_name_used">HESLB Name Used</label>
                <input type="text" class="form-control" id="heslb_name_used" name="heslb_name_used"
                    value="{{ old('heslb_name_used', $employee->deductionDetails->heslb_name_used ?? '') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="deduction_type">Deduction</label>
                <select class="form-control" id="deduction_type" name="deduction_type">
                    <option value="">--Select Deduction--</option>
                    <option value="loan" {{ old('deduction_type', $employee->deductionDetails->deduction_type ?? '') == 'loan' ? 'selected' : '' }}>Loan</option>
                    <option value="advance" {{ old('deduction_type', $employee->deductionDetails->deduction_type ?? '') == 'advance' ? 'selected' : '' }}>Advance</option>
                    <option value="other" {{ old('deduction_type', $employee->deductionDetails->deduction_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="deduction_amount">Amount</label>
                <input type="number" class="form-control" id="deduction_amount" name="deduction_amount"
                    value="{{ old('deduction_amount', $employee->deductionDetails->amount ?? '') }}" min="0" step="0.01">
            </div>

            <div class="col-md-4 mb-3">
                <label for="deduction_percentage">Deduction %</label>
                <input type="number" class="form-control" id="deduction_percentage" name="deduction_percentage"
                    value="{{ old('deduction_percentage', $employee->deductionDetails->percentage ?? '') }}" min="0" max="100" step="0.01">
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="paid_by_employer" name="paid_by_employer" value="1"
                        {{ old('paid_by_employer', $employee->deductionDetails->paid_by_employer ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label" for="paid_by_employer">
                        Paid By Employer
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // NHIF toggle
    const nhifCheckbox = document.getElementById('nhif_enabled');
    const nhifDetails = document.getElementById('nhif_details');

    function toggleNHIF() {
        if (nhifCheckbox.checked) {
            nhifDetails.style.display = 'flex';
        } else {
            nhifDetails.style.display = 'none';
        }
    }

    nhifCheckbox.addEventListener('change', toggleNHIF);
    toggleNHIF(); // Check on page load

    // Absent toggle
    const absentCheckbox = document.getElementById('absent_enabled');
    const absentDetails = document.getElementById('absent_details');

    function toggleAbsent() {
        if (absentCheckbox.checked) {
            absentDetails.style.display = 'flex';
        } else {
            absentDetails.style.display = 'none';
        }
    }

    absentCheckbox.addEventListener('change', toggleAbsent);
    toggleAbsent(); // Check on page load

    // Late toggle
    const lateCheckbox = document.getElementById('late_enabled');
    const lateDetails = document.getElementById('late_details');

    function toggleLate() {
        if (lateCheckbox.checked) {
            lateDetails.style.display = 'flex';
        } else {
            lateDetails.style.display = 'none';
        }
    }

    lateCheckbox.addEventListener('change', toggleLate);
    toggleLate(); // Check on page load
});
</script>
