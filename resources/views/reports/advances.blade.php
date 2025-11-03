<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Advance Report</a>
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
            <div class="card shadow-none border">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.advances') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Payroll Period</label>
                                <select name="payroll_period_id" class="form-control">
                                    <option value="all" {{ request('payroll_period_id') == 'all' ? 'selected' : '' }}>All Periods</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ (request('payroll_period_id') == $period->id || $currentPeriod?->id == $period->id) ? 'selected' : '' }}>
                                            {{ $period->period_name }} ({{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Branch</label>
                                <select name="branch_id" class="form-control">
                                    <option value="">All Branches</option>
                                    @foreach($branches ?? [] as $branch)
                                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Department</label>
                                <select name="department_id" class="form-control">
                                    <option value="">All Departments</option>
                                    @foreach($departments ?? [] as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fe fe-filter mr-1"></i> Get Report
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
                    <h5 class="card-title mb-3">Advance Records: {{ $advances->count() }}</h5>
                    <table class="table table-bordered datatables">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Employee</th>

                                <th>Advance Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advances as $index => $advance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $advance->employee->employee_name ?? 'N/A' }}</td>

                                    <td class="text-right">{{ number_format($advance->advance_amount, 2) }}</td>
                                    <td>{{ $advance->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="2" class="text-right">Total:</td>
                                <td class="text-right">{{ number_format($advances->sum('advance_amount'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
