<x-app-layout>
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Employee Report</a>
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
                    <form method="GET" action="{{ route('reports.employees') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="onhold" {{ request('status') == 'onhold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Department</label>
                                <select name="department_id" class="form-control">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">All Genders</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-3">
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
                    <h5 class="card-title mb-3">Total Employees: {{ $employees->count() }}</h5>
                    <table class="table table-bordered datatables">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Department</th>
                                <th>Job Title</th>
                                <th>Basic Salary</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $index => $employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee->employeeID ?? 'N/A' }}</td>
                                    <td>{{ $employee->employee_name }}</td>
                                    <td>{{ ucfirst($employee->gender ?? 'N/A') }}</td>
                                    <td>{{ $employee->department->department->department_name ?? 'N/A' }}</td>
                                    <td> {{ $employee->department?->jobtitle?->job_title ?? 'N/A' }}</td>
                                    <td>{{ number_format($employee->basic_salary, 2) }}</td>
                                    <td>
                                      {{ ucfirst($employee->employee_status) }}
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
