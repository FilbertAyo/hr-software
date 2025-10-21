<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <h2 class="mb-0">Process Payroll
                            @if ($payrollPeriod)
                                - {{ $payrollPeriod->period_name }}
                            @endif
                        </h2>
                    </div>
                    <div class="col-auto">
                        @if ($payrollPeriod)
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" id="processSelectedBtn" disabled>
                                    <i class="fe fe-check"></i> Process Selected
                                </button>
                                <button type="button" class="btn btn-success" id="processAllBtn">
                                    <i class="fe fe-check-circle"></i> Process All
                                </button>
                            </div>
                        @else
                            <a href="{{ route('payroll.payperiod') }}" class="btn btn-primary">
                                <i class="fe fe-plus"></i> Create Pay Period
                            </a>
                        @endif
                    </div>
                </div>

                @if ($payrollPeriod)
                    <!-- Payroll Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Employees
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $payrollStats['total_employees'] }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fe fe-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Processed
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $payrollStats['processed_employees'] }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fe fe-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-warning">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $payrollStats['pending_employees'] }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fe fe-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total Net Amount
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                TZS {{ number_format($payrollStats['total_net'], 2) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fe fe-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <!-- Payroll Period Selection -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <form method="GET" action="{{ route('payroll.index') }}">
                                            @if ($payrollPeriod)
                                                <input type="hidden" name="payroll_period_id"
                                                    value="{{ $payrollPeriod->id }}">
                                            @endif
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="payroll_period_id" class="form-label">Select Pay
                                                        Period</label>
                                                    <select name="payroll_period_id" id="payroll_period_id"
                                                        class="form-control" {{ $payrollPeriod ? 'disabled' : '' }}>
                                                        <option value="">-- Select Pay Period --</option>
                                                        @foreach ($payrollPeriods as $period)
                                                            <option value="{{ $period->id }}"
                                                                {{ $payrollPeriod && $payrollPeriod->id == $period->id ? 'selected' : '' }}>
                                                                {{ $period->period_name }}
                                                                @if ($period->status == 'completed')
                                                                    (Completed)
                                                                @elseif($period->status == 'processing')
                                                                    (Processing)
                                                                @else
                                                                    (Draft)
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-outline-primary">Load
                                                        Period</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                @if (!$payrollPeriod)
                                    <!-- Empty State -->
                                    <div class="text-center py-5">
                                        <i class="fe fe-calendar mb-3" style="font-size: 48px; color: #ccc;"></i>
                                        <h4>No Pay Period Selected</h4>
                                        <p class="text-muted">Please select a pay period from the dropdown above or
                                            create a new one.</p>
                                        <a href="{{ route('payroll.payperiod') }}" class="btn btn-primary">
                                            <i class="fe fe-plus"></i> Create New Pay Period
                                        </a>
                                    </div>
                                @elseif($employees->isEmpty())
                                    <!-- No Employees State -->
                                    <div class="text-center py-5">
                                        <i class="fe fe-users mb-3" style="font-size: 48px; color: #ccc;"></i>
                                        <h4>No Employees Found</h4>
                                        <p class="text-muted">Add employees to start processing payroll for
                                            {{ $payrollPeriod->period_name }}.</p>
                                    </div>
                                @else
                                    <!-- Employee Table -->
                                    <form id="payrollForm">
                                        @csrf
                                        <input type="hidden" name="payroll_period_id"
                                            value="{{ $payrollPeriod->id }}">

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="employeeTable">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="50">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="selectAll">
                                                                <label class="custom-control-label"
                                                                    for="selectAll"></label>
                                                            </div>
                                                        </th>
                                                        <th>Employee Name</th>
                                                        <th>Basic Salary</th>
                                                        <th>Taxable Allowances</th>
                                                        <th>Non-Taxable Allowances</th>
                                                        <th>Gross Salary</th>
                                                        <th>Pension</th>
                                                        <th>Taxable Income</th>
                                                        <th>PAYE</th>
                                                        <th>Advance</th>
                                                        <th>Net Salary</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($employees as $employee)
                                                        @php
                                                            $payroll = $employee->payrolls->first();

                                                            // If payroll exists (processed), use payroll data
                                                            if ($payroll) {
                                                                $basicSalary = $payroll->basic_salary;
                                                                $taxableAllowances = $payroll->taxable_allowances;
                                                                $nonTaxableAllowances = $payroll->non_taxable_allowances;
                                                                $grossSalary = $payroll->gross_salary;
                                                                $pensionAmount = $payroll->pension_amount;
                                                                $taxableIncome = $payroll->taxable_income;
                                                                $totalDeductions = $payroll->total_deductions;
                                                                $advanceAmount = $payroll->advance_salary; // Now using dedicated advance_salary column
                                                                $payeTax = $payroll->tax_deduction; // PAYE tax
                                                                $netSalary = $payroll->net_salary;
                                                            } else {
                                                                // If not processed, show employee data for preview
                                                                $basicSalary = $employee->basic_salary ?? 0;

                                                                // Get taxable and non-taxable allowances from earngroups
                                                                $taxableAllowances = $employee->getTaxableAllowancesFromEarngroups();
                                                                $nonTaxableAllowances = $employee->getNonTaxableAllowancesFromEarngroups();

                                                                // Get other benefits for this payroll period
                                                                $taxableOtherBenefits = $employee->getTaxableOtherBenefits(
                                                                    $payrollPeriod->start_date,
                                                                    $payrollPeriod->end_date
                                                                );
                                                                $nonTaxableOtherBenefits = $employee->getNonTaxableOtherBenefits(
                                                                    $payrollPeriod->start_date,
                                                                    $payrollPeriod->end_date
                                                                );

                                                                // Combine allowances and other benefits
                                                                $taxableAllowances += $taxableOtherBenefits;
                                                                $nonTaxableAllowances += $nonTaxableOtherBenefits;

                                                                $grossSalary = $basicSalary + $taxableAllowances;

                                                                // Get pension amount directly from employee table
                                                                $pensionAmount = 0;
                                                                if ($employee->pension_details && $employee->employee_pension_amount) {
                                                                    $pensionAmount = $employee->employee_pension_amount;
                                                                }

                                                                // Calculate taxable income (gross salary minus pension)
                                                                $taxableIncome = $grossSalary - $pensionAmount;

                                                                $payeTax = 0; // No PAYE until processed

                                                                // Get advance amount for preview
                                                                $advanceAmount = 0;
                                                                if ($payrollPeriod) {
                                                                    $advance = $employee
                                                                        ->advances()
                                                                        ->where('payroll_period_id', $payrollPeriod->id)
                                                                        ->where('status', 'approved')
                                                                        ->sum('advance_amount');
                                                                    $advanceAmount = $advance ?? 0;
                                                                }

                                                                // Calculate total deductions (preview: pension + advance only)
                                                                $totalDeductions = $pensionAmount + $advanceAmount;

                                                                // Calculate net salary (gross - total deductions + non-taxable allowances)
                                                                $netSalary = $grossSalary - $totalDeductions + $nonTaxableAllowances;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        class="custom-control-input employee-checkbox"
                                                                        id="employee_{{ $employee->id }}"
                                                                        name="employee_ids[]"
                                                                        value="{{ $employee->id }}">
                                                                    <label class="custom-control-label"
                                                                        for="employee_{{ $employee->id }}"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <strong>{{ $employee->employee_name }}</strong>
                                                                    @if ($employee->employee_id)
                                                                        <br><small class="text-muted">ID:
                                                                            {{ $employee->employee_id }}</small>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td>{{ number_format($basicSalary, 2) }}</td>
                                                            <td>{{ number_format($taxableAllowances, 2) }}</td>
                                                            <td>{{ number_format($nonTaxableAllowances, 2) }}</td>
                                                            <td>{{ number_format($grossSalary, 2) }}</td>
                                                            <td>{{ number_format($pensionAmount, 2) }}</td>
                                                            <td>{{ number_format($taxableIncome, 2) }}</td>
                                                            <td>{{ number_format($payeTax, 2) }}</td>
                                                            <td>{{ number_format($advanceAmount, 2) }}</td>
                                                            <td>{{ number_format($netSalary, 2) }}</td>
                                                            <td>
                                                                @if ($payroll)
                                                                    @if ($payroll->status == 'processed')
                                                                        <span
                                                                            class="badge badge-success">Processed</span>
                                                                    @elseif($payroll->status == 'pending')
                                                                        <span
                                                                            class="badge badge-warning">Pending</span>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-secondary">{{ ucfirst($payroll->status) }}</span>
                                                                    @endif
                                                                @else
                                                                    <span class="badge badge-light">Not
                                                                        Processed</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Payroll Modal -->
    <div class="modal fade" id="cancelPayrollModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Payroll</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this payroll? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelBtn">Cancel Payroll</button>
                </div>
            </div>
        </div>
    </div>

    @if ($payrollPeriod)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAllCheckbox = document.getElementById('selectAll');
                const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
                const processSelectedBtn = document.getElementById('processSelectedBtn');
                const processAllBtn = document.getElementById('processAllBtn');
                let payrollToCancel = null;

                // Handle Select All checkbox
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        employeeCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateProcessButtonState();
                    });
                }

                // Handle individual checkboxes
                employeeCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateProcessButtonState();
                        updateSelectAllState();
                    });
                });

                function updateProcessButtonState() {
                    const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
                    if (processSelectedBtn) {
                        processSelectedBtn.disabled = checkedBoxes.length === 0;
                    }
                }

                function updateSelectAllState() {
                    if (!selectAllCheckbox) return;

                    const allCheckboxes = document.querySelectorAll('.employee-checkbox');
                    const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');

                    if (allCheckboxes.length === 0) {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = false;
                    } else if (checkedBoxes.length === allCheckboxes.length) {
                        selectAllCheckbox.checked = true;
                        selectAllCheckbox.indeterminate = false;
                    } else if (checkedBoxes.length > 0) {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = true;
                    } else {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = false;
                    }
                }

                // Process Selected button click
                if (processSelectedBtn) {
                    processSelectedBtn.addEventListener('click', function() {
                        const form = document.getElementById('payrollForm');
                        form.action = '{{ route('payroll.processSelected') }}';
                        form.method = 'POST';
                        form.submit();
                    });
                }

                // Process All button click
                if (processAllBtn) {
                    processAllBtn.addEventListener('click', function() {
                        if (confirm('Are you sure you want to process payroll for all employees?')) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route('payroll.processAll') }}';

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);

                            const periodId = document.createElement('input');
                            periodId.type = 'hidden';
                            periodId.name = 'payroll_period_id';
                            periodId.value = '{{ $payrollPeriod->id }}';
                            form.appendChild(periodId);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }

                // Initialize button states
                updateProcessButtonState();
                updateSelectAllState();

                // Cancel payroll modal handler
                if (document.getElementById('confirmCancelBtn')) {
                    document.getElementById('confirmCancelBtn').addEventListener('click', function() {
                        if (payrollToCancel) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route('payroll.cancel') }}';

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);

                            const payrollIdInput = document.createElement('input');
                            payrollIdInput.type = 'hidden';
                            payrollIdInput.name = 'payroll_ids[]';
                            payrollIdInput.value = payrollToCancel;
                            form.appendChild(payrollIdInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }

                // Global functions for inline onclick handlers
                window.processIndividual = function(employeeId) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('payroll.processSelected') }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const periodId = document.createElement('input');
                    periodId.type = 'hidden';
                    periodId.name = 'payroll_period_id';
                    periodId.value = '{{ $payrollPeriod->id }}';
                    form.appendChild(periodId);

                    const employeeIdInput = document.createElement('input');
                    employeeIdInput.type = 'hidden';
                    employeeIdInput.name = 'employee_ids[]';
                    employeeIdInput.value = employeeId;
                    form.appendChild(employeeIdInput);

                    document.body.appendChild(form);
                    form.submit();
                };

                window.cancelPayroll = function(payrollId) {
                    payrollToCancel = payrollId;
                    $('#cancelPayrollModal').modal('show');
                };
            });
        </script>
    @endif
</x-app-layout>
