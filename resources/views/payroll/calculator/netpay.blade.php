<x-app-layout>


                {{-- Header --}}
                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Netpay Calculator</a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body py-2">
                                <div class="btn-group" role="group" aria-label="Payroll Calculators">
                                    <a href="{{ route('cal.netpay') }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-calculator"></i> Netpay Calculator
                                    </a>
                                    <a href="{{ route('cal.grosspay') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-trending-up"></i> Gross Pay Calculator
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Calculator --}}
                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                {{-- Inputs --}}
                                <form id="calcForm">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="basic_salary">Basic Salary (TZS)</label>
                                            <input type="number" class="form-control" id="basic_salary"
                                                placeholder="Enter basic salary" required>
                                            <small class="text-muted">Base salary before allowances</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="allowances">Allowances (TZS)</label>
                                            <input type="number" class="form-control" id="allowances" value="0"
                                                placeholder="Enter total allowances">
                                            <small class="text-muted">Housing, transport, etc.</small>
                                        </div>
                                    </div>

                                    {{-- Pension Selection --}}
                                    <div class="form-group">
                                        <label class="text-primary">
                                            <i class="fe fe-shield"></i> Pension Scheme Selection
                                        </label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="pension_selection"
                                                           id="pension_none" value="" checked>
                                                    <label class="custom-control-label" for="pension_none">
                                                        <strong>No Pension</strong>
                                                        <br>
                                                        <small class="text-muted">No pension deductions will be applied</small>
                                                    </label>
                                                </div>
                                            </div>
                                            @foreach($pensionOptions as $pension)
                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" name="pension_selection"
                                                               id="pension_{{ $pension->id }}" value="{{ $pension->id }}"
                                                               data-name="{{ $pension->name }}"
                                                               data-employee="{{ $pension->employee_percent }}"
                                                               data-employer="{{ $pension->employer_percent }}"
                                                               data-type="{{ $pension->deduction_type }}"
                                                               data-percentage-of="{{ $pension->percentage_of }}">
                                                        <label class="custom-control-label" for="pension_{{ $pension->id }}">
                                                            <strong>{{ $pension->name }}</strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                Employee: {{ $pension->employee_percent }}%
                                                                (of {{ ucfirst($pension->percentage_of) }})
                                                                @if($pension->employer_percent > 0)
                                                                    | Employer: {{ $pension->employer_percent }}%
                                                                @endif
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Optional Deductions --}}
                                    @if($optionalDeductions->count() > 0)
                                        <div class="form-group">
                                            <label>Optional Deductions (Select as needed)</label>
                                            <div class="row">
                                                @foreach($optionalDeductions as $deduction)
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-checkbox mb-2">
                                                            <input class="custom-control-input deduction-check"
                                                                type="checkbox"
                                                                id="deduction_{{ $deduction->id }}"
                                                                value="{{ $deduction->id }}"
                                                                data-name="{{ $deduction->name }}"
                                                                data-employee="{{ $deduction->employee_percent }}"
                                                                data-employer="{{ $deduction->employer_percent }}"
                                                                data-type="{{ $deduction->deduction_type }}"
                                                                data-percentage-of="{{ $deduction->percentage_of }}">
                                                            <label class="custom-control-label" for="deduction_{{ $deduction->id }}">
                                                                <strong>{{ $deduction->name }}</strong>
                                                                <br>
                                                                <small class="text-muted">
                                                                    Employee: {{ $deduction->employee_percent }}%
                                                                    (of {{ ucfirst($deduction->percentage_of) }})
                                                                    @if($deduction->employer_percent > 0)
                                                                        | Employer: {{ $deduction->employer_percent }}%
                                                                    @endif
                                                                </small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Calculate Button --}}
                                    <button type="button" class="btn btn-primary btn-lg" onclick="calculateNetpay()">
                                        <i class="fe fe-calculator"></i> Calculate Net Pay
                                    </button>
                                </form>

                                <hr>

                                {{-- Results Section --}}
                                <div id="results" style="display: none;">
                                    <h5 class="text-primary">
                                        <i class="fe fe-pie-chart"></i> Salary Breakdown
                                    </h5>

                                    {{-- Summary Cards --}}
                                    <div class="row mb-4">
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">Gross Salary</h6>
                                                    <h5 class="mb-0" id="gross-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">Pension Deducted</h6>
                                                    <h5 class="mb-0" id="pension-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">Taxable Income</h6>
                                                    <h5 class="mb-0" id="taxable-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">PAYE Tax</h6>
                                                    <h5 class="mb-0" id="paye-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">Other Deductions</h6>
                                                    <h5 class="mb-0" id="other-deductions-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border border-success">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-success mb-1">Take Home</h6>
                                                    <h4 class="mb-0 text-success" id="takehome-display">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Detailed Tables --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">
                                                <i class="fe fe-user"></i> Employee Salary Breakdown
                                            </h6>
                                            <table class="table table-bordered table-sm" id="employeeTable">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Component</th>
                                                        <th class="text-right">Amount (TZS)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="table-success">
                                                        <td><strong>Gross Salary</strong></td>
                                                        <td class="text-right" id="employee-gross">0</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-info">
                                                <i class="fe fe-briefcase"></i> Cost to Company
                                            </h6>
                                            <table class="table table-bordered table-sm" id="employerTable">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Component</th>
                                                        <th class="text-right">Amount (TZS)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="table-primary">
                                                        <td><strong>Gross Salary</strong></td>
                                                        <td class="text-right" id="employer-gross">0</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="table-info font-weight-bold">
                                                        <td><strong>Total Cost to Company</strong></td>
                                                        <td class="text-right" id="total-cost-company">0</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Explanation --}}
                                    <div class="alert alert-info mt-3">
                                        <h6><i class="fe fe-info"></i> How Net Pay is Calculated:</h6>
                                        <ol class="mb-0">
                                            <li><strong>Gross Salary</strong> = Basic Salary + Allowances</li>
                                            <li><strong>Pension Deduction</strong> = Applied first (if applicable)</li>
                                            <li><strong>Taxable Income</strong> = Gross Salary - Pension Contributions</li>
                                            <li><strong>PAYE Tax</strong> = Calculated on Taxable Income using Tanzania tax brackets</li>
                                            <li><strong>Other Deductions</strong> = Applied as selected</li>
                                            <li><strong>Take Home Pay</strong> = Gross Salary - All Employee Deductions</li>
                                        </ol>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        function calculateNetpay() {
            const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
            const allowances = parseFloat(document.getElementById('allowances').value) || 0;

            if (basicSalary <= 0) {
                alert('Please enter a valid basic salary');
                return;
            }

            // Get selected pension
            const selectedPensionId = document.querySelector('input[name="pension_selection"]:checked').value || null;

            // Get selected optional deductions
            const selectedDeductions = [];
            document.querySelectorAll('.deduction-check:checked').forEach(checkbox => {
                selectedDeductions.push(checkbox.value);
            });

            // Show loading state
            const calculateBtn = document.querySelector('button[onclick="calculateNetpay()"]');
            const originalText = calculateBtn.innerHTML;
            calculateBtn.innerHTML = '<i class="fe fe-loader"></i> Calculating...';
            calculateBtn.disabled = true;

            // Make AJAX request
            fetch('{{ route("cal.calculate.netpay") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    basic_salary: basicSalary,
                    allowances: allowances,
                    selected_pension_id: selectedPensionId,
                    selected_deductions: selectedDeductions
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                displayResults(data);

                // Reset button
                calculateBtn.innerHTML = originalText;
                calculateBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during calculation. Please try again.');

                // Reset button
                calculateBtn.innerHTML = originalText;
                calculateBtn.disabled = false;
            });
        }

        function displayResults(data) {
            // Show results section
            document.getElementById('results').style.display = 'block';

            // Update summary cards
            document.getElementById('gross-display').textContent = formatMoney(data.gross_salary);
            document.getElementById('pension-display').textContent = formatMoney(data.total_pension_deductions);
            document.getElementById('taxable-display').textContent = formatMoney(data.taxable_income);
            document.getElementById('paye-display').textContent = formatMoney(data.paye_amount);
            document.getElementById('other-deductions-display').textContent = formatMoney(data.total_other_deductions);
            document.getElementById('takehome-display').textContent = formatMoney(data.take_home);

            // Update employee salary breakdown table
            const employeeTableBody = document.querySelector('#employeeTable tbody');
            employeeTableBody.innerHTML = `<tr class="table-success">
                <td><strong>Gross Salary</strong></td>
                <td class="text-right">${formatMoney(data.gross_salary)}</td>
            </tr>`;

            // Add pension deductions
            if (data.pension_deductions && data.pension_deductions.length > 0) {
                data.pension_deductions.forEach(deduction => {
                    const row = `<tr class="table-info">
                        <td style="padding-left: 20px;">
                            <i class="fe fe-minus"></i> ${deduction.name}
                            ${deduction.is_mandatory ? '<span class="badge badge-warning badge-sm ml-1">Auto</span>' : ''}
                        </td>
                        <td class="text-right">(${formatMoney(deduction.amount)})</td>
                    </tr>`;
                    employeeTableBody.innerHTML += row;
                });
            }

            // Add taxable income row
            employeeTableBody.innerHTML += `<tr class="table-light font-weight-bold">
                <td><strong>Taxable Income</strong></td>
                <td class="text-right"><strong>${formatMoney(data.taxable_income)}</strong></td>
            </tr>`;

            // Add PAYE tax
            if (data.paye_amount > 0) {
                employeeTableBody.innerHTML += `<tr class="table-warning">
                    <td style="padding-left: 20px;">
                        <i class="fe fe-minus"></i> PAYE (Income Tax)
                        <span class="badge badge-warning badge-sm ml-1">Auto</span>
                    </td>
                    <td class="text-right">(${formatMoney(data.paye_amount)})</td>
                </tr>`;
            }

            // Add other deductions
            if (data.other_deductions && data.other_deductions.length > 0) {
                data.other_deductions.forEach(deduction => {
                    if (deduction.name !== 'PAYE (Income Tax)') {
                        const row = `<tr class="table-warning">
                            <td style="padding-left: 20px;">
                                <i class="fe fe-minus"></i> ${deduction.name}
                                ${deduction.is_mandatory ? '<span class="badge badge-warning badge-sm ml-1">Auto</span>' : ''}
                            </td>
                            <td class="text-right">(${formatMoney(deduction.amount)})</td>
                        </tr>`;
                        employeeTableBody.innerHTML += row;
                    }
                });
            }

            // Add final take home row
            employeeTableBody.innerHTML += `<tr class="table-success font-weight-bold border-success border-2">
                <td><strong><i class="fe fe-dollar-sign"></i> TAKE HOME PAY</strong></td>
                <td class="text-right"><strong>${formatMoney(data.take_home)}</strong></td>
            </tr>`;

            document.getElementById('employee-gross').textContent = formatMoney(data.gross_salary);

            // Update employer cost table
            const employerTableBody = document.querySelector('#employerTable tbody');
            employerTableBody.innerHTML = `<tr class="table-primary">
                <td><strong>Gross Salary</strong></td>
                <td class="text-right">${formatMoney(data.gross_salary)}</td>
            </tr>`;

            // Add employer contributions
            if (data.employer_contributions && data.employer_contributions.length > 0) {
                data.employer_contributions.forEach(contribution => {
                    if (contribution.amount > 0) {
                        const row = `<tr class="table-info">
                            <td style="padding-left: 20px;">
                                <i class="fe fe-plus"></i> ${contribution.name} (Employer Contribution)
                            </td>
                            <td class="text-right">${formatMoney(contribution.amount)}</td>
                        </tr>`;
                        employerTableBody.innerHTML += row;
                    }
                });
            }

            document.getElementById('employer-gross').textContent = formatMoney(data.gross_salary);
            document.getElementById('total-cost-company').textContent = formatMoney(data.total_cost_to_company);

            // Scroll to results
            document.getElementById('results').scrollIntoView({ behavior: 'smooth' });
        }

        function formatMoney(amount) {
            return new Intl.NumberFormat('en-TZ', {
                style: 'currency',
                currency: 'TZS',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</x-app-layout>
