<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Salary Analysis Report</a>
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
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Employees</h6>
                    <h4>{{ $analysis['total_employees'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Total Basic Salary</h6>
                    <h4>{{ number_format($analysis['total_basic_salary'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Average Salary</h6>
                    <h4>{{ number_format($analysis['average_salary'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Highest Salary</h6>
                    <h4>{{ number_format($analysis['highest_salary'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Lowest Salary</h6>
                    <h4>{{ number_format($analysis['lowest_salary'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h6 class="text-muted">Median Salary</h6>
                    <h4>{{ number_format($analysis['median_salary'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary by Department -->
    <div class="row my-2">

        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="card-title mb-3">Salary Distribution by Department</h5>
                    <table class="table table-bordered datatables">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Department</th>
                                <th>Employee Count</th>
                                <th>Total Salary</th>
                                <th>Average Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryByDept as $index => $dept)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $dept['department'] }}</td>
                                    <td>{{ $dept['employee_count'] }}</td>
                                    <td>{{ number_format($dept['total_salary'], 2) }}</td>
                                    <td>{{ number_format($dept['average_salary'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="2">TOTAL</td>
                                <td>{{ $salaryByDept->sum('employee_count') }}</td>
                                <td>{{ number_format($salaryByDept->sum('total_salary'), 2) }}</td>
                                <td>-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Range Distribution -->
    <div class="row my-2">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="card-title mb-3">Salary Range Distribution</h5>
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Salary Range</th>
                                <th>Employee Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ranges = [
                                    ['min' => 0, 'max' => 500000, 'label' => 'Below 500,000'],
                                    ['min' => 500000, 'max' => 1000000, 'label' => '500,000 - 1,000,000'],
                                    ['min' => 1000000, 'max' => 2000000, 'label' => '1,000,000 - 2,000,000'],
                                    ['min' => 2000000, 'max' => 5000000, 'label' => '2,000,000 - 5,000,000'],
                                    ['min' => 5000000, 'max' => PHP_INT_MAX, 'label' => 'Above 5,000,000']
                                ];
                                $totalEmployees = $employees->count();
                            @endphp
                            @foreach($ranges as $range)
                                @php
                                    $count = $employees->whereBetween('basic_salary', [$range['min'], $range['max']])->count();
                                    $percentage = $totalEmployees > 0 ? ($count / $totalEmployees) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $range['label'] }}</td>
                                    <td>{{ $count }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                        </div>
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
