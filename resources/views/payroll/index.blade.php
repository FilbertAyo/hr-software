<x-app-layout>
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spin {
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        #progressBar {
            transition: width 0.3s ease;
            font-weight: bold;
        }
    </style>
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

                    <!-- Additional Statistics Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-danger">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Excluded Employees
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $payrollStats['inactive_employees'] ?? 0 }}
                                                <small class="text-muted">On leave or inactive</small>
                                            </div>

                                        </div>
                                        <div class="col-auto">
                                            <i class="fe fe-user-x fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-secondary">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                Processing Summary
                                            </div>
                                            <div class="h6 mb-0 text-gray-800">
                                                {{ $payrollStats['processed_employees'] }} of {{ $payrollStats['total_employees'] }} active employees processed
                                                @if(($payrollStats['inactive_employees'] ?? 0) > 0)
                                                    ({{ $payrollStats['inactive_employees'] }} excluded due to status)
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fe fe-bar-chart-2 fa-2x text-gray-300"></i>
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

                                <!-- Progress Bar for Payroll Processing -->
                                <div id="progressContainer" style="display: none;">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="fe fe-loader spin"></i> Processing Payroll...
                                            </h5>
                                            <div class="progress" style="height: 20px;">
                                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                     role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    <span id="progressText">0%</span>
                                                </div>
                                            </div>
                                            <p class="mt-2 mb-0 text-muted">
                                                <small id="progressMessage">Initializing...</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>


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

                                                    <select name="payroll_period_id" id="payroll_period_id"
                                                        class="form-control" {{ $payrollPeriod ? 'disabled' : '' }}>
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
                                                    <button type="submit" class="btn btn-outline-primary">
                                                        <i class="fe fe-refresh-cw"></i>
                                                    </button>
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
                                    <div class="alert alert-info mb-3">
                                        <i class="fe fe-info fe-16 mr-2"></i>
                                        <strong>Note:</strong> Only employees with "Active" status are eligible for payroll processing.
                                        Employees with "On Hold" (on leave) or "Inactive" status are automatically excluded from payroll processing.

                                        @if(isset($excludedEmployees) && $excludedEmployees->count() > 0)
                                            <button class="btn btn-sm btn-outline-info ml-2" type="button" data-toggle="collapse" data-target="#excludedEmployees" aria-expanded="false">
                                                <i class="fe fe-eye fe-16 mr-1"></i>View Excluded Employees ({{ $excludedEmployees->count() }})
                                            </button>
                                        @endif
                                    </div>

                                    @if(isset($excludedEmployees) && $excludedEmployees->count() > 0)
                                        <div class="collapse mb-3" id="excludedEmployees">
                                            <div class="card border-info">
                                                <div class="card-header alert-info text-dark">
                                                    <h6 class="mb-0">
                                                        <i class="fe fe-user-x fe-16 mr-2"></i>Excluded Employees
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach($excludedEmployees as $employee)
                                                            <div class="col-md-4 mb-2">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge badge-{{
                                                                        $employee->employee_status == 'onhold' ? 'warning' : 'danger'
                                                                    }} mr-2">
                                                                        {{ ucfirst($employee->employee_status) }}
                                                                    </span>
                                                                    <span class="text-sm">{{ $employee->employee_name }}</span>
                                                                    @if($employee->employee_id)
                                                                        <small class="text-muted ml-1">({{ $employee->employee_id }})</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <form id="payrollForm">
                                        @csrf
                                        <input type="hidden" name="payroll_period_id"
                                            value="{{ $payrollPeriod->id }}">

                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered table-hover" id="employeeTable">
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
                                                        <th>Loan Deduction</th>
                                                        <th>Other Deductions</th>
                                                        <th>Total Deductions</th>
                                                        <th>Net Salary</th>
                                                        <th>Payroll Status</th>
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
                                                                $pensionAmount = $payroll->employee_pension_amount;
                                                                $taxableIncome = $payroll->taxable_income;
                                                                $totalDeductions = $payroll->total_deductions;
                                                                $advanceAmount = $payroll->advance_salary; // Now using dedicated advance_salary column
                                                                $loanDeduction = $payroll->loan_deduction;
                                                                $otherDeductionsAmount = $payroll->other_deductions;
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

                                                                // Calculate pension amount from pension_id
                                                                $pensionAmount = 0;
                                                                if ($employee->pension_details && $employee->pension_id && $employee->pension) {
                                                                    $baseAmount = $employee->pension->percentage_of === 'basic' ? $basicSalary : $grossSalary;
                                                                    $pensionAmount = ($baseAmount * floatval($employee->pension->employee_percent)) / 100;
                                                                }

                                                                // Calculate taxable income (gross salary minus employee pension)
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

                                                                // Get loan deduction amount for preview
                                                                $loanDeduction = 0;
                                                                if ($payrollPeriod) {
                                                                    // Get all active loans
                                                                    $activeLoans = $employee->loans()->whereIn('status', ['active', 'approved'])->get();
                                                                    foreach ($activeLoans as $loan) {
                                                                        // Get pending installments due in this period
                                                                        $installments = $loan->installments()
                                                                            ->where('status', 'pending')
                                                                            ->whereBetween('due_date', [$payrollPeriod->start_date, $payrollPeriod->end_date])
                                                                            ->sum('amount');
                                                                        $loanDeduction += $installments;
                                                                    }
                                                                }

                                                                // Get other deductions for preview
                                                                $otherDeductionsAmount = 0;
                                                                if ($payrollPeriod) {
                                                                    $otherDeductionsAmount = $employee->getOtherDeductionsForPeriod(
                                                                        $payrollPeriod->start_date,
                                                                        $payrollPeriod->end_date
                                                                    );
                                                                }

                                                                // Calculate total deductions (preview: pension + advance + loan + other deductions only)
                                                                $totalDeductions = $pensionAmount + $advanceAmount + $loanDeduction + $otherDeductionsAmount;

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
                                                            <td>{{ number_format($loanDeduction, 2) }}</td>
                                                            <td>{{ number_format($otherDeductionsAmount, 2) }}</td>
                                                            <td>{{ number_format($totalDeductions, 2) }}</td>
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
                                                                    @if ($employee->employee_status !== 'active')
                                                                        <span class="badge badge-danger">Not Eligible</span>
                                                                    @else
                                                                        <span class="badge badge-light">Not Processed</span>
                                                                    @endif
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

                // Show progress bar function
                function showProgressBar(totalEmployees) {
                    const progressContainer = document.getElementById('progressContainer');
                    const progressBar = document.getElementById('progressBar');
                    const progressText = document.getElementById('progressText');
                    const progressMessage = document.getElementById('progressMessage');

                    progressContainer.style.display = 'block';
                    progressBar.style.width = '0%';
                    progressText.textContent = '0%';
                    progressMessage.textContent = `Processing payroll for ${totalEmployees} employee(s)...`;

                    // Disable buttons
                    if (processSelectedBtn) processSelectedBtn.disabled = true;
                    if (processAllBtn) processAllBtn.disabled = true;

                    // Simulate progress
                    let progress = 0;
                    const interval = setInterval(function() {
                        progress += Math.random() * 15;
                        if (progress > 90) progress = 90;

                        progressBar.style.width = progress + '%';
                        progressText.textContent = Math.round(progress) + '%';
                        progressBar.setAttribute('aria-valuenow', progress);
                    }, 200);

                    // Store interval so we can clear it if needed
                    window.payrollProgressInterval = interval;
                }

                // Process Selected button click
                if (processSelectedBtn) {
                    processSelectedBtn.addEventListener('click', function() {
                        const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
                        showProgressBar(checkedBoxes.length);

                        const form = document.getElementById('payrollForm');
                        form.action = '{{ route('payroll.processSelected') }}';
                        form.method = 'POST';
                        form.submit();
                    });
                }

                // Process All button click
                if (processAllBtn) {
                    processAllBtn.addEventListener('click', function() {
                        const totalEmployees = document.querySelectorAll('.employee-checkbox').length;
                        showProgressBar(totalEmployees);

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
