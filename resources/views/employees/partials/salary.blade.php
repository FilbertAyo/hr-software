
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
                        value="{{ getFormValue('basic_salary', $employee->basic_salary ?? '') }}" min="0" step="0.01">
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
                            {{ isChecked('advance_option', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="advance_option">
                            <strong>Enable Advance Salary</strong>
                        </label>
                    </div>
                </div>

                <!-- Advance Percentage -->
                <div class="col-md-3 mb-3 d-none" id="advance_percentage_field">
                    <label for="advance_percentage">Advance Percentage (%)</label>
                    <input type="number" class="form-control" id="advance_percentage" name="advance_percentage"
                        value="{{ getFormValue('advance_percentage', $employee->advance_percentage ?? 50) }}" min="0" max="100" step="0.01">
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
                            {{ isChecked('paye_exempt', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="paye_exempt">
                            <strong>PAYE Exempt</strong>
                        </label>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <div class="card-header">
        <strong>Pension and Deduction Details</strong>
    </div>

    <div class="card-body">
        <!-- Pension Details Checkbox -->
        <div class="form-row mb-3">
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="pension_details" name="pension_details" value="1"
                        {{ isChecked('pension_details', '1') ? 'checked' : '' }}>
                    <label class="form-check-label" for="pension_details">
                        <strong>Enable Pension Details</strong>
                    </label>
                </div>
            </div>
        </div>

        <!-- Pension Fields (hidden by default) -->
        <div id="pensionFields" class="form-row" style="display: none;">
            <div class="col-md-8 mb-3">
                <label for="pension">Pension</label>
                <select class="form-control" id="pension" name="pension_id">
                    <option value="">--Select Pension--</option>
                    @foreach ($pensions as $pension)
                        <option value="{{ $pension->id }}"
                            {{ isSelected('pension_id', $pension->id) ? 'selected' : '' }}>
                            {{ $pension->name }} (Employee: {{ $pension->employee_percent }}%, Employer: {{ $pension->employer_percent }}%)
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Pension amounts will be calculated automatically during payroll processing</small>
            </div>

            <!-- Pension No -->
            <div class="col-md-4 mb-3">
                <label for="employee_pension_no">Pension No</label>
                <input type="text" class="form-control" id="employee_pension_no" name="employee_pension_no"
                    value="{{ getFormValue('employee_pension_no', $employee->employee_pension_no ?? '') }}">
            </div>
        </div>

        <!-- Other Deductions Section -->
        <div class="form-row mb-3">
            <div class="col-12">
                <label class="font-weight-bold">Direct Deductions (NHIF, WCF, etc.)</label>
                <small class="form-text text-muted mb-2">Select additional deductions for this employee</small>
            </div>
        </div>

        <!-- Assigned Deductions Table -->
        @if(isset($employee) && $employee->employeeDeductions->where('directDeduction.deduction_type', 'normal')->count() > 0)
        <div class="form-row mb-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Deduction Name</th>
                                <th>Employee %</th>
                                <th>Employer %</th>
                                <th>Member Number</th>
                                <th>Status</th>
                                <th width="80">Action</th>
                            </tr>
                        </thead>
                        <tbody id="assignedDeductionsTable">
                            @foreach($employee->employeeDeductions->where('directDeduction.deduction_type', 'normal') as $empDeduction)
                            <tr data-deduction-id="{{ $empDeduction->id }}">
                                <td>{{ $empDeduction->directDeduction->name }}</td>
                                <td>{{ $empDeduction->directDeduction->employee_percent }}%</td>
                                <td>{{ $empDeduction->directDeduction->employer_percent }}%</td>
                                <td>{{ $empDeduction->member_number ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $empDeduction->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($empDeduction->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-deduction-btn"
                                            data-deduction-row-id="{{ $empDeduction->id }}">
                                        <i class="fe fe-trash-2 fe-12"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Add New Deductions -->
        <div class="form-row mb-3">
            <div class="col-12">
                <button type="button" class="btn btn-sm btn-outline-primary" id="addDeductionBtn">
                    <i class="fe fe-plus fe-12 mr-1"></i> Add Deduction
                </button>
            </div>
        </div>

        <!-- Dynamic Deduction Rows Container -->
        <div id="deductionsContainer"></div>

        <!-- Hidden deduction row template -->
        <template id="deductionRowTemplate">
            <div class="form-row mb-2 deduction-row border-bottom pb-2">
                <div class="col-md-5">
                    <label>Deduction</label>
                    <select class="form-control form-control deduction-select" name="deduction_ids[]" required>
                        <option value="">--Select Deduction--</option>
                        @if(isset($deductions))
                            @foreach($deductions as $deduction)
                                <option value="{{ $deduction->id }}"
                                        data-require-member-no="{{ $deduction->require_member_no ? '1' : '0' }}">
                                    {{ $deduction->name }} (Emp: {{ $deduction->employee_percent }}%, Empr: {{ $deduction->employer_percent }}%)
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-5">
                    <label>Member Number/Index No</label>
                    <input type="text" class="form-control form-control member-number-input"
                           name="deduction_member_numbers[]" placeholder="Enter member number if required">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                        <i class="fe fe-x fe-12"></i> Remove
                    </button>
                </div>
            </div>
        </template>



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
                document.getElementById('employee_pension_no').value = '';
            }
        }

        // Toggle pension fields on checkbox change
        pensionDetailsCheckbox.addEventListener('change', togglePensionFields);

        // Initialize on page load
        togglePensionFields();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addDeductionBtn = document.getElementById('addDeductionBtn');
        const deductionsContainer = document.getElementById('deductionsContainer');
        const deductionRowTemplate = document.getElementById('deductionRowTemplate');

        // Add new deduction row
        addDeductionBtn.addEventListener('click', function() {
            const newRow = deductionRowTemplate.content.cloneNode(true);
            const rowElement = newRow.querySelector('.deduction-row');

            // Add event listener to remove button
            const removeBtn = newRow.querySelector('.remove-row-btn');
            removeBtn.addEventListener('click', function() {
                rowElement.remove();
            });

            // Add event listener to deduction select to handle member number requirement
            const deductionSelect = newRow.querySelector('.deduction-select');
            const memberNumberInput = newRow.querySelector('.member-number-input');

            deductionSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const requireMemberNo = selectedOption.getAttribute('data-require-member-no');

                if (requireMemberNo === '1') {
                    memberNumberInput.setAttribute('required', 'required');
                    memberNumberInput.placeholder = 'Member number required';
                } else {
                    memberNumberInput.removeAttribute('required');
                    memberNumberInput.placeholder = 'Enter member number if required';
                }
            });

            deductionsContainer.appendChild(newRow);
        });

        // Handle removing assigned deductions from table (mark for deletion)
        document.querySelectorAll('.remove-deduction-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                if (confirm('Are you sure you want to remove this deduction?')) {
                    row.remove();
                }
            });
        });
    });
</script>
