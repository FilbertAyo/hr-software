

    <div class="card-header">
        <strong>Absent & Late Details</strong>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="absent_enabled" name="absent_enabled"
                        value="1"
                        {{ old('absent_enabled', $employee->absentDetails->enabled ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label" for="absent_enabled">
                        <strong>Absent</strong>
                    </label>
                </div>
            </div>
        </div>

        <div id="absent_details" class="form-row" style="display: none;">
            <div class="col-md-6 mb-3">
                <label for="leave_days">Leave Days</label>
                <input type="number" class="form-control" id="leave_days" name="leave_days"
                    value="{{ old('leave_days', $employee->absentDetails->leave_days ?? '') }}" min="0">
                <small class="form-text text-muted">Absent Deduction</small>
            </div>
        </div>


        <div class="col-md-6 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="late_enabled" name="late_enabled" value="1"
                    {{ old('late_enabled', $employee->lateDetails->enabled ?? '') ? 'checked' : '' }}>
                <label class="form-check-label" for="late_enabled">
                    <strong>Late</strong>
                </label>
            </div>
        </div>


        <div id="late_details" class="form-row" style="display: none;">
            <div class="col-md-6 mb-3">
                <label for="deduct_from">Deduct From</label>
                <select class="form-control" id="deduct_from" name="deduct_from">
                    <option value="basic"
                        {{ old('deduct_from', $employee->lateDetails->deduct_from ?? '') == 'basic' ? 'selected' : '' }}>
                        Basic Salary</option>
                    <option value="net"
                        {{ old('deduct_from', $employee->lateDetails->deduct_from ?? '') == 'net' ? 'selected' : '' }}>
                        Net Salary</option>
                </select>
                <small class="form-text text-muted">Deducted From</small>
            </div>
        </div>
    </div>

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
