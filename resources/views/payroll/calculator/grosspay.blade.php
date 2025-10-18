<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Header --}}
                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Gross Pay Calculator</a>
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
                                    <a href="{{ route('cal.netpay') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-calculator"></i> Netpay Calculator
                                    </a>
                                    <a href="{{ route('cal.grosspay') }}" class="btn btn-primary btn-sm">
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
                                <div class="alert alert-info">
                                    <h6><i class="fe fe-info"></i> Gross Pay Calculator</h6>
                                    <p class="mb-0">Enter your desired net pay, and we'll calculate what gross salary is needed to achieve it.</p>
                                </div>

                                {{-- Inputs --}}
                                <form id="calcForm">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="target_net_pay">Target Net Pay (TZS)</label>
                                            <input type="number" class="form-control" id="target_net_pay"
                                                placeholder="Enter desired net pay" required>
                                            <small class="text-muted">The net pay you want to achieve</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="allowances_gross">Allowances (TZS)</label>
                                            <input type="number" class="form-control" id="allowances_gross" value="0"
                                                placeholder="Enter total allowances">
                                            <small class="text-muted">Housing, transport, etc.</small>
                                        </div>
                                    </div>

                                    {{-- Mandatory Deductions (Auto-included) --}}
                                    @if($mandatoryDeductions->count() > 0)
                                        <div class="form-group">
                                            <label class="text-success">
                                                <i class="fe fe-check-circle"></i> Mandatory Deductions (Auto-included)
                                            </label>
                                            <div class="row">
                                                @foreach($mandatoryDeductions as $deduction)
                                                    <div class="col-md-6">
                                                        <div class="alert alert-light py-2 px-3 mb-2">
                                                            <strong>{{ $deduction->name }}</strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                Employee: {{ $deduction->employee_percent }}%
                                                                (of {{ ucfirst($deduction->percentage_of) }})
                                                                @if($deduction->employer_percent > 0)
                                                                    | Employer: {{ $deduction->employer_percent }}%
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

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
                                                                id="deduction_gross_{{ $deduction->id }}"
                                                                value="{{ $deduction->id }}"
                                                                data-name="{{ $deduction->name }}"
                                                                data-employee="{{ $deduction->employee_percent }}"
                                                                data-employer="{{ $deduction->employer_percent }}"
                                                                data-type="{{ $deduction->deduction_type }}"
                                                                data-percentage-of="{{ $deduction->percentage_of }}">
                                                            <label class="custom-control-label" for="deduction_gross_{{ $deduction->id }}">
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
                                    <button type="button" class="btn btn-success btn-lg" onclick="calculateGrosspay()">
                                        <i class="fe fe-trending-up"></i> Calculate Required Gross Pay
                                    </button>
                                </form>

                                <hr>

                                {{-- Results Section --}}
                                <div id="results_gross" style="display: none;">
                                    <h5 class="text-success">
                                        <i class="fe fe-check-circle"></i> Required Gross Pay Calculation
                                    </h5>

                                    {{-- Summary Cards --}}
                                    <div class="row mb-4">
                                        <div class="col-md-2">
                                            <div class="card border border-primary">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-primary mb-1">Required Gross</h6>
                                                    <h5 class="mb-0 text-primary" id="gross-required-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">Pension Deducted</h6>
                                                    <h5 class="mb-0" id="pension-required-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">Taxable Income</h6>
                                                    <h5 class="mb-0" id="taxable-required-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">PAYE Tax</h6>
                                                    <h5 class="mb-0" id="paye-required-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-muted mb-1">Other Deductions</h6>
                                                    <h5 class="mb-0" id="other-required-display">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card border border-success">
                                                <div class="card-body text-center py-3">
                                                    <h6 class="text-success mb-1">Target Achieved</h6>
                                                    <h4 class="mb-0 text-success" id="target-achieved-display">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Detailed Breakdown --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">
                                                <i class="fe fe-user"></i> Employee Salary Breakdown
                                            </h6>
                                            <table class="table table-bordered table-sm" id="employeeBreakdownTable">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Component</th>
                                                        <th class="text-right">Amount (TZS)</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-info">
                                                <i class="fe fe-briefcase"></i> Cost to Company
                                            </h6>
                                            <table class="table table-bordered table-sm" id="employerBreakdownTable">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Component</th>
                                                        <th class="text-right">Amount (TZS)</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="table-info font-weight-bold">
                                                        <td><strong>Total Cost to Company</strong></td>
                                                        <td class="text-right" id="total-cost-required">0</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div> {{-- card-body --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        function calculateGrosspay() {
            const targetNetPay = parseFloat(document.getElementById('target_net_pay').value) || 0;
            const allowances = parseFloat(document.getElementById('allowances_gross').value) || 0;

            if (targetNetPay <= 0) {
                alert('Please enter a valid target net pay');
                return;
            }

            // Get selected optional deductions
            const selectedDeductions = [];
            document.querySelectorAll('.deduction-check:checked').forEach(checkbox => {
                selectedDeductions.push(checkbox.value);
            });

            // Show loading state
            const calculateBtn = document.querySelector('button[onclick="calculateGrosspay()"]');
            const originalText = calculateBtn.innerHTML;
            calculateBtn.innerHTML = '<i class="fe fe-loader"></i> Calculating...';
            calculateBtn.disabled = true;

            // Make AJAX request
            fetch('{{ route("cal.calculate.grosspay") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    target_net_pay: targetNetPay,
                    allowances: allowances,
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
                displayGrossResults(data, targetNetPay, allowances);

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

        function displayGrossResults(data, targetNetPay, allowances) {
            // Show results section
            document.getElementById('results_gross').style.display = 'block';

            // Update summary cards
            document.getElementById('gross-required-display').textContent = formatMoney(data.gross_salary);
            document.getElementById('pension-required-display').textContent = formatMoney(data.total_pension_deductions);
            document.getElementById('taxable-required-display').textContent = formatMoney(data.taxable_income);
            document.getElementById('paye-required-display').textContent = formatMoney(data.paye_amount);
            document.getElementById('other-required-display').textContent = formatMoney(data.total_other_deductions);
            document.getElementById('target-achieved-display').textContent = formatMoney(data.take_home);

            // Update employee breakdown table
            const employeeBreakdownBody = document.querySelector('#employeeBreakdownTable tbody');
            employeeBreakdownBody.innerHTML = `<tr class="table-success">
                <td><strong>Required Gross Salary</strong></td>
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
                    employeeBreakdownBody.innerHTML += row;
                });
            }

            // Add taxable income row
            employeeBreakdownBody.innerHTML += `<tr class="table-light font-weight-bold">
                <td><strong>Taxable Income</strong></td>
                <td class="text-right"><strong>${formatMoney(data.taxable_income)}</strong></td>
            </tr>`;

            // Add PAYE tax
            if (data.paye_amount > 0) {
                employeeBreakdownBody.innerHTML += `<tr class="table-warning">
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
                        employeeBreakdownBody.innerHTML += row;
                    }
                });
            }

            // Add final take home row
            employeeBreakdownBody.innerHTML += `<tr class="table-success font-weight-bold border-success border-3">
                <td><strong><i class="fe fe-dollar-sign"></i> ACHIEVED TAKE HOME</strong></td>
                <td class="text-right"><strong>${formatMoney(data.take_home)}</strong></td>
            </tr>`;

            // Update employer breakdown table
            const employerBreakdownBody = document.querySelector('#employerBreakdownTable tbody');
            employerBreakdownBody.innerHTML = `<tr class="table-primary">
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
                        employerBreakdownBody.innerHTML += row;
                    }
                });
            }

            document.getElementById('total-cost-required').textContent = formatMoney(data.total_cost_to_company);

            // Scroll to results
            document.getElementById('results_gross').scrollIntoView({ behavior: 'smooth' });
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
