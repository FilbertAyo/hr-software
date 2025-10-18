<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Employee Management</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <a href="{{ route('employee.create') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Add New Employee
                        </a>
                    </div>
                </div>


                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <!-- Search and Filter Section -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchInput"
                                                placeholder="Search employees...">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                                    <i class="fe fe-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="departmentFilter">
                                            <option value="">All Departments</option>
                                            <!-- Populate dynamically -->
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="statusFilter">
                                            <option value="">All Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-secondary btn-block" id="resetFilters">Reset</button>
                                    </div>
                                </div>

                                <!-- Employee Table -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="employeeTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Photo</th>
                                                <th>Employee Name</th>
                                                <th>Department</th>
                                                <th>Position</th>
                                                <th>Join Date</th>
                                                <th>Basic Salary</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($employees as $employee)
                                                <tr>
                                                    <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}
                                                    </td>
                                                    <td>
                                                        @if ($employee->photo_path)
                                                            <img src="{{ asset('storage/' . $employee->photo_path) }}"
                                                                alt="Photo" class="rounded-circle" width="40"
                                                                height="40">
                                                        @else
                                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white"
                                                                style="width: 40px; height: 40px; font-size: 14px;">
                                                                {{ strtoupper(substr($employee->employee_name, 0, 2)) }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <strong>{{ $employee->employee_name }}</strong>
                                                            @if ($employee->mobile_no)
                                                                <br><small
                                                                    class="text-muted">{{ $employee->mobile_no }}</small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $employee->department?->department?->department_name ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $employee->department?->jobtitle?->title ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $employee->department?->joining_date?->format('M d, Y') ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if ($employee->basic_salary)
                                                            <span class="text-success font-weight-bold">
                                                                TZS {{ number_format($employee->basic_salary, 2) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">Not Set</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $employee->employee_status === 'active' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($employee->employee_status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                type="button"
                                                                id="dropdownMenuButton{{ $employee->id }}"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton{{ $employee->id }}">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('employee.show', $employee) }}">
                                                                    <i class="fe fe-eye mr-2"></i>View Details
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('employee.edit', $employee) }}">
                                                                    <i class="fe fe-edit mr-2"></i>Edit
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <button class="dropdown-item text-danger"
                                                                    onclick="confirmDelete({{ $employee->id }}, '{{ $employee->employee_name }}')">
                                                                    <i class="fe fe-trash mr-2"></i>Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fe fe-users mb-3" style="font-size: 48px;"></i>
                                                            <h5>No employees found</h5>
                                                            <p>Get started by adding your first employee.</p>
                                                            <a href="{{ route('employee.create') }}"
                                                                class="btn btn-primary">
                                                                Add Employee
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                @if ($employees->hasPages())
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-muted">
                                                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }}
                                                of {{ $employees->total() }} employees
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            {{ $employees->links() }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete employee <strong id="employeeName"></strong>?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Employee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#employeeTable tbody tr');

            tableRows.forEach(row => {
                const employeeName = row.cells[2].textContent.toLowerCase();
                const department = row.cells[3].textContent.toLowerCase();

                if (employeeName.includes(searchTerm) || department.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Department filter
        document.getElementById('departmentFilter').addEventListener('change', function() {
            const selectedDepartment = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#employeeTable tbody tr');

            tableRows.forEach(row => {
                const department = row.cells[3].textContent.toLowerCase();

                if (!selectedDepartment || department.includes(selectedDepartment)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            const selectedStatus = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#employeeTable tbody tr');

            tableRows.forEach(row => {
                const status = row.cells[7].textContent.toLowerCase();

                if (!selectedStatus || status.includes(selectedStatus)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Reset filters
        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('departmentFilter').value = '';
            document.getElementById('statusFilter').value = '';

            const tableRows = document.querySelectorAll('#employeeTable tbody tr');
            tableRows.forEach(row => {
                row.style.display = '';
            });
        });

        // Delete confirmation
        function confirmDelete(employeeId, employeeName) {
            document.getElementById('employeeName').textContent = employeeName;
            document.getElementById('deleteForm').action = `/employees/${employeeId}`;
            $('#deleteModal').modal('show');
        }
    </script>

    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .table td {
            vertical-align: middle;
        }

        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .table-responsive {
            border-radius: 0.375rem;
        }

        .alert {
            border: none;
            border-radius: 0.375rem;
        }
    </style>
</x-app-layout>
