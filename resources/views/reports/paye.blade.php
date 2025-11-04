<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="paye-tab" data-toggle="tab" href="#paye" role="tab"
                        aria-controls="paye" aria-selected="true">PAYE Report</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                <i class="fe fe-arrow-left mr-1"></i> Back to Reports
            </a>
            <button type="button" class="btn btn-sm" onclick="window.print()">
                <i class="fe fe-printer text-muted"></i>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.paye') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Payroll Period</label>
                                <select name="period_id" class="form-control">
                                    <option value="">Select Period</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ $selectedPeriod == $period->id ? 'selected' : '' }}>
                                            {{ $period->period_name }} ({{ date('M Y', strtotime($period->start_date)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fe fe-filter mr-1"></i> Generate Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($selectedPeriod)
    <div class="row my-2">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        PAYE Report - {{ $currentPeriod ? $currentPeriod->period_name : 'Selected Period' }}
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered datatables">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th class="text-right">Taxable Income</th>
                                    <th class="text-right">PAYE Deducted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalTax = 0; $totalTaxable = 0; @endphp
                                @forelse($payrolls as $index => $payroll)
                                    @php
                                        $totalTax += $payroll->tax_deduction;
                                        $totalTaxable += $payroll->taxable_income;
                                        $employee = $payroll->employee;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $employee->employeeID ?? 'N/A' }}</td>
                                        <td>{{ $employee->employee_name ?? 'N/A' }}</td>
                                        <td class="text-right">{{ number_format($payroll->taxable_income, 2) }}</td>
                                        <td class="text-right">{{ number_format($payroll->tax_deduction, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No PAYE data available for the selected period</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($payrolls->isNotEmpty())
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td colspan="3" class="text-right">TOTAL</td>
                                    <td class="text-right">{{ number_format($totalTaxable, 2) }}</td>
                                    <td class="text-right">{{ number_format($totalTax, 2) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">Total Employees</h6>
                                        <h4 class="mb-0">{{ $payrolls->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">Total PAYE</h6>
                                        <h4 class="mb-0">{{ number_format($totalTax, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">Average PAYE per Employee</h6>
                                        <h4 class="mb-0">{{ $payrolls->count() > 0 ? number_format($totalTax / $payrolls->count(), 2) : '0.00' }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="fe fe-info mr-2"></i> Please select a payroll period to generate the PAYE report.
    </div>
    @endif
</x-app-layout>
