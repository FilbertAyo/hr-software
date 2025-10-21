
    <div class="card-header">
        <strong>Employee Salary Details</strong>
    </div>
    <div>

        <div class="card-body">
            <div class="form-row">

                <!-- Basic Salary -->
                <div class="col-md-3 mb-3">
                    <label for="basic_salary">Basic Salary</label>
                    <input type="number" class="form-control" id="basic_salary" name="basic_salary"
                        value="{{ old('basic_salary', $employee->basic_salary ?? '') }}" min="0" step="0.01">
                </div>
                <!-- Earning Groups -->
                <div class="col-md-9 mb-3">
                    <label for="earngroups">Earning Groups</label>
                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa;">
                        @if(isset($earngroups) && $earngroups->count() > 0)
                            @foreach($earngroups as $earngroup)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="earngroup_ids[]"
                                        value="{{ $earngroup->id }}"
                                        id="earngroup_{{ $earngroup->id }}"
                                        {{ (isset($employee) && $employee->earngroups->contains($earngroup->id)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="earngroup_{{ $earngroup->id }}">
                                        {{ $earngroup->earngroup_name }}
                                        @if($earngroup->description)
                                            <small class="text-muted">({{ $earngroup->description }})</small>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">No earning groups available</p>
                        @endif
                    </div>
                    <small class="form-text text-muted">Select one or more earning groups for this employee. Each earning group contains multiple allowances.</small>
                </div>

                <!-- Advance Salary Checkbox -->
                <div class="col-md-3 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="advance_option" name="advance_option" value="1"
                            {{ old('advance_option', $employee->advance_option ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="advance_option">
                            <strong>Enable Advance Salary</strong>
                        </label>
                    </div>
                </div>

                <!-- Advance Percentage -->
                <div class="col-md-3 mb-3 d-none" id="advance_percentage_field">
                    <label for="advance_percentage">Advance Percentage (%)</label>
                    <input type="number" class="form-control" id="advance_percentage" name="advance_percentage"
                        value="{{ old('advance_percentage', $employee->advance_percentage ?? 50) }}" min="0" max="100" step="0.01">
                </div>

                <!-- Advance Salary -->
                {{-- Do not fill this input automatically by percentage; leave it empty for later use --}}
                <div class="col-md-3 mb-3 d-none" id="advance_salary_field">
                    <label for="advance_salary">Advance Salary</label>
                    <input type="number" class="form-control" id="advance_salary" name="advance_salary"
                        value="" readonly>
                </div>

                   <!-- PAYE Exemption -->

                <div class="col-md-3 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="paye_exempt" name="paye_exempt" value="1"
                            {{ old('paye_exempt', $employee->paye_exempt ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="paye_exempt">
                            <strong>PAYE Exempt</strong>
                        </label>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <div class="card-header">
        <strong>Pension Details</strong>
    </div>

    <div class="card-body">
        <!-- Pension Details Checkbox -->
        <div class="form-row mb-3">
            <div class="col-md-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="pension_details" name="pension_details" value="1"
                        {{ old('pension_details', $employee->pension_details ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="pension_details">
                        <strong>Enable Pension Details</strong>
                    </label>
                </div>
            </div>
        </div>

        <!-- Pension Fields (hidden by default) -->
        <div id="pensionFields" class="form-row" style="display: none;">
            <div class="col-md-3 mb-3">
                <label for="pension">Pension</label>
                <select class="form-control" id="pension" name="pension_id">
                    <option value="">--Select Pension--</option>
                    @foreach ($pensions as $pension)
                        <option value="{{ $pension->id }}" data-employee="{{ $pension->employee_percent }}"
                            data-employer="{{ $pension->employer_percent }}"
                            {{ old('pension_id', $employee->pension_id ?? '') == $pension->id ? 'selected' : '' }}>
                            {{ $pension->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Employee Pension Amount -->
            <div class="col-md-3 mb-3">
                <label for="employee_pension_amount">Employee Amount</label>
                <input type="number" class="form-control" id="employee_pension_amount" name="employee_pension_amount"
                    value="{{ old('employee_pension_amount', $employee->employee_pension_amount ?? '') }}" min="0" step="0.01">
            </div>

            <!-- Employer Pension Amount -->
            <div class="col-md-3 mb-3">
                <label for="employer_pension_amount">Employer Amount</label>
                <input type="number" class="form-control" id="employer_pension_amount" name="employer_pension_amount"
                    value="{{ old('employer_pension_amount', $employee->employer_pension_amount ?? '') }}" min="0" step="0.01">
            </div>

            <!-- Pension No -->
            <div class="col-md-3 mb-3">
                <label for="employee_pension_no">Pension No</label>
                <input type="text" class="form-control" id="employee_pension_no" name="employee_pension_no"
                    value="{{ old('employee_pension_no', $employee->employee_pension_no ?? '') }}">
            </div>
        </div>
    </div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const advanceCheckbox = document.getElementById('advance_option');
        const advancePercentField = document.getElementById('advance_percentage_field');
        const advanceSalaryField = document.getElementById('advance_salary_field');

        function toggleAdvanceFields() {
            if (advanceCheckbox.checked) {
                advancePercentField.classList.remove('d-none');
                advanceSalaryField.classList.remove('d-none');
            } else {
                advancePercentField.classList.add('d-none');
                advanceSalaryField.classList.add('d-none');
                // Clear advance fields when hidden
                document.getElementById('advance_percentage').value = '';
                document.getElementById('advance_salary').value = '';
            }
        }

        advanceCheckbox.addEventListener('change', toggleAdvanceFields);

        // Check on page load
        toggleAdvanceFields();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pensionDetailsCheckbox = document.getElementById('pension_details');
        const pensionFields = document.getElementById('pensionFields');
        const pensionSelect = document.getElementById('pension');

        // Function to toggle pension fields visibility
        function togglePensionFields() {
            if (pensionDetailsCheckbox.checked) {
                pensionFields.style.display = 'flex';
            } else {
                pensionFields.style.display = 'none';
                // Clear pension fields when hidden
                pensionSelect.value = '';
                document.getElementById('employee_pension_amount').value = '';
                document.getElementById('employer_pension_amount').value = '';
                document.getElementById('employee_pension_no').value = '';
            }
        }

        // Function to calculate pension amounts
        function calculatePensionAmounts() {
            const selected = pensionSelect.options[pensionSelect.selectedIndex];
            const employeePercent = parseFloat(selected.getAttribute('data-employee')) || 0;
            const employerPercent = parseFloat(selected.getAttribute('data-employer')) || 0;

            // Get basic salary for calculation
            const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;

            // Calculate amounts
            const employeeAmount = (basicSalary * employeePercent) / 100;
            const employerAmount = (basicSalary * employerPercent) / 100;

            document.getElementById('employee_pension_amount').value = employeeAmount.toFixed(2);
            document.getElementById('employer_pension_amount').value = employerAmount.toFixed(2);
        }

        // Toggle pension fields on checkbox change
        pensionDetailsCheckbox.addEventListener('change', togglePensionFields);

        // Update on pension selection change
        pensionSelect.addEventListener('change', calculatePensionAmounts);

        // Also calculate when basic salary changes
        const basicSalaryInput = document.getElementById('basic_salary');
        if (basicSalaryInput) {
            basicSalaryInput.addEventListener('input', calculatePensionAmounts);
        }

        // Initialize on page load
        togglePensionFields();
        if (pensionSelect.value) {
            calculatePensionAmounts();
        }
    });
</script>
