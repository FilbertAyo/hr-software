<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Tax Report</a>
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
                    <form method="GET" action="{{ route('reports.tax') }}">
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                    <h5 class="card-title mb-3">Tax Summary by Period</h5>
                    <table class="table table-bordered datatables">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Period</th>
                                <th>Employee Count</th>
                                <th>Total Taxable Income</th>
                                <th>Total Tax Deducted</th>
                                <th>Average Tax</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taxByPeriod as $index => $tax)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tax['period']->period_name ?? 'N/A' }}</td>
                                    <td>{{ $tax['employee_count'] }}</td>
                                    <td>{{ number_format($tax['total_taxable_income'], 2) }}</td>
                                    <td>{{ number_format($tax['total_tax'], 2) }}</td>
                                    <td>{{ $tax['employee_count'] > 0 ? number_format($tax['total_tax'] / $tax['employee_count'], 2) : '0.00' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="2">TOTAL</td>
                                <td>{{ $taxByPeriod->sum('employee_count') }}</td>
                                <td>{{ number_format($taxByPeriod->sum('total_taxable_income'), 2) }}</td>
                                <td>{{ number_format($taxByPeriod->sum('total_tax'), 2) }}</td>
                                <td>-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
