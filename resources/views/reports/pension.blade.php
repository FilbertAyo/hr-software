<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Pension Report</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                <i class="fe fe-arrow-left mr-1"></i> Back to Reports
            </a>
            <button type="button" class="btn btn-sm btn-dark" onclick="window.print()">
                <i class="fe fe-printer text-muted"></i> Print
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Employee Pension</h6>
                    <h4>{{ number_format($summary['total_employee_pension'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Employer Pension</h6>
                    <h4>{{ number_format($summary['total_employer_pension'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Pension</h6>
                    <h4>{{ number_format($summary['total_pension'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.pension') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Payroll Period <span class="text-danger">*</span></label>
                                <select name="period_id" class="form-control" required>
                                    <option value="">Select Period</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}"
                                            {{ request('period_id') == $period->id ? 'selected' : (isset($currentPeriod) && $currentPeriod && $period->id == $currentPeriod->id && !request('period_id') ? 'selected' : '') }}>
                                            {{ $period->period_name }} ({{ date('M Y', strtotime($period->start_date)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Pension Type <span class="text-danger">*</span></label>
                                <select name="pension_id" class="form-control" required>
                                    <option value="">Select Pension</option>
                                    @foreach($pensions as $pension)
                                        <option value="{{ $pension->id }}" {{ request('pension_id') == $pension->id ? 'selected' : '' }}>
                                            {{ $pension->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fe fe-file-text mr-1"></i> Get Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-2">

        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="card-title mb-3">Pension Contributions: {{ $payrolls->count() }} Records</h5>
                    @if($payrolls->count() > 0)
                        <table class="table table-sm table-bordered datatables">
                            <thead class="thead-light">
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Gross Salary</th>
                                    <th>Employer Contribution</th>
                                    <th>Employee Pension</th>
                                    <th>Total Pension</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $index => $payroll)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $payroll->employee->employee_name ?? 'N/A' }}</td>
                                        <td>{{ number_format($payroll->gross_salary, 2) }}</td>
                                        <td>{{ number_format($payroll->employer_pension_amount, 2) }}</td>
                                        <td>{{ number_format($payroll->employee_pension_amount, 2) }}</td>
                                        <td>{{ number_format($payroll->employee_pension_amount + $payroll->employer_pension_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            <i class="fe fe-info mr-2"></i> Please select a payroll period and pension type, then click "Get Report" to view the data.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
