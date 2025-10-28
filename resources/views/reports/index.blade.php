<x-app-layout>


    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Reports</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-sm" onclick="reloadPage()">
                <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
            </button>

        </div>
    </div>

    <div class="row my-2">
        @include('elements.spinner')

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.employees') }}">Employee Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.payroll') }}">Payroll Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.loans') }}">Loan Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.leaves') }}">Leave Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.attendance') }}">Attendance Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.departments') }}">Department Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.advances') }}">Advance Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.tax') }}">Tax Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.pension') }}">Pension Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.salary-analysis') }}">Salary Analysis</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.payslip') }}">Payslip Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.bank-salary') }}">Bank Salary Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <a href="{{ route('reports.earning-group') }}">Earning Group Report</a>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
