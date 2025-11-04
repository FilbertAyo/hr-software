<x-app-layout>

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

                        <a href="{{ route('employee.create') }}" class="btn btn-primary btn-sm" onclick="clearEmployeeSession(event)">
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

                                <!-- Employee Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered datatables" id="dataTable-1">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Employee Name</th>
                                                <th>Department</th>
                                                <th>Position</th>
                                                <th>Join Date</th>
                                                <th>Basic Salary TZS</th>
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
                                                        <div>
                                                            <strong>{{ $employee->employee_name }}</strong>
                                                            @if ($employee->employeeID)
                                                                <br><small
                                                                    class="text-muted">{{ $employee->employeeID }}</small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $employee->department?->department?->department_name ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $employee->department?->jobtitle?->job_title ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $employee->department?->joining_date?->format('M d, Y') ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if ($employee->basic_salary)
                                                                {{ number_format($employee->basic_salary, 2) }}

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
                                                                <i class="fe fe-action"></i>
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
                                                    <td colspan="8" class="text-center py-4">
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

        // Clear employee session before creating new employee
        function clearEmployeeSession(event) {
            event.preventDefault();
            const link = event.currentTarget.href;

            fetch('{{ route('employee.session.clear') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Navigate to create page after clearing session
                window.location.href = link;
            })
            .catch(error => {
                console.error('Error clearing session:', error);
                // Navigate anyway even if there's an error
                window.location.href = link;
            });
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
