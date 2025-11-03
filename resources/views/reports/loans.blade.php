<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Loan Report</a>
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

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Loans</h6>
                    <h4>{{ number_format($summary['total_loans'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Paid</h6>
                    <h4>{{ number_format($summary['total_paid'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Remaining</h6>
                    <h4>{{ number_format($summary['total_remaining'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Active Loans</h6>
                    <h4>{{ $summary['active_loans'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.loans') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Payroll Period</label>
                                <select name="period_id" class="form-control">
                                    <option value="">All Periods</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->period_name }} ({{ date('M Y', strtotime($period->start_date)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fe fe-filter mr-1"></i> Filter
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
                    <h5 class="card-title mb-3">Loan Records: {{ $loans->count() }}</h5>
                    <table class="table table-bordered datatables">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Employee</th>
                                <th>Loan Type</th>
                                <th>Loan Amount</th>
                                <th>Remaining</th>
                                <th>Monthly Payment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $index => $loan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $loan->employee->employee_name ?? 'N/A' }}</td>
                                    <td>{{ $loan->loanType->loan_type_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                    <td>{{ number_format($loan->remaining_amount, 2) }}</td>
                                    <td>{{ number_format($loan->monthly_payment, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $loan->status == 'completed' ? 'success' : ($loan->status == 'active' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
