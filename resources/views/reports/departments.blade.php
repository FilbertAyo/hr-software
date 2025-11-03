<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Department Report</a>
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

    <div class="row my-2">

        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="card-title mb-3">Department Analysis</h5>
                    <table class="table table-bordered datatables">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Department Name</th>
                                <th>Total Employees</th>
                                <th>Total Salary</th>
                                <th>Average Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departmentDetails as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail['department']->department_name }}</td>
                                    <td>{{ $detail['total_employees'] }}</td>
                                    <td>{{ number_format($detail['total_salary'], 2) }}</td>
                                    <td>{{ $detail['total_employees'] > 0 ? number_format($detail['total_salary'] / $detail['total_employees'], 2) : '0.00' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="2">TOTAL</td>
                                <td>{{ collect($departmentDetails)->sum('total_employees') }}</td>
                                <td>{{ number_format(collect($departmentDetails)->sum('total_salary'), 2) }}</td>
                                <td>-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
