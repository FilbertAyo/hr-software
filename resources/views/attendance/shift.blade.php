<x-app-layout>
    <!-- Page Header -->
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="shifts-tab" data-toggle="tab" href="#shifts" role="tab"
                        aria-controls="shifts" aria-selected="true">Shift Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="punch-tab" data-toggle="tab" href="#punch" role="tab"
                        aria-controls="punch" aria-selected="false">Punch In/Out</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bulk-tab" data-toggle="tab" href="#bulk" role="tab"
                        aria-controls="bulk" aria-selected="false">Bulk Operations</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <!-- Shifts Management Tab -->
        <div class="tab-pane fade show active" id="shifts" role="tabpanel" aria-labelledby="shifts-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Shifts</h3>
                            <div class="card-options">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#addShiftModal">
                                    <i class="fe fe-plus"></i> Add Shift
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Shift Name</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Duration</th>
                                            <th>Break</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shifts as $index => $shift)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>

                                                <td>
                                                    <strong>{{ $shift->shift_name }}</strong>
                                                    @if ($shift->description)
                                                        <br><small class="text-muted">{{ $shift->description }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $shift->start_time }}</td>
                                                <td>{{ $shift->end_time }}</td>
                                                <td>{{ $shift->duration }} hours</td>
                                                <td>{{ $shift->break_duration_minutes }} min</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $shift->is_active ? 'success' : 'danger' }}">
                                                        {{ $shift->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-primary edit-shift"
                                                            data-id="{{ $shift->id }}"
                                                            data-name="{{ $shift->shift_name }}"
                                                            data-start="{{ $shift->start_time }}"
                                                            data-end="{{ $shift->end_time }}"
                                                            data-break="{{ $shift->break_duration_minutes }}"
                                                            data-description="{{ $shift->description }}">
                                                            <i class="fe fe-edit"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-{{ $shift->is_active ? 'warning' : 'success' }} toggle-shift"
                                                            data-id="{{ $shift->id }}">
                                                            <i
                                                                class="fe fe-{{ $shift->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger delete-shift"
                                                            data-id="{{ $shift->id }}"
                                                            data-name="{{ $shift->shift_name }}">
                                                            <i class="fe fe-trash-2"></i>
                                                        </button>
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
            </div>
        </div>

        <!-- Punch In/Out Tab -->
        <div class="tab-pane fade" id="punch" role="tabpanel" aria-labelledby="punch-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Punch In/Out</h3>
                        </div>
                        <div class="card-body">
                            <form id="punchForm">
                                @csrf
                                <div class="form-group">
                                    <label for="punch_employee_id" class="form-label">Employee</label>
                                    <select name="employee_id" id="punch_employee_id" class="form-control" required>
                                        <option value="">Select Employee</option>
                                        @foreach (\App\Models\Employee::where('company_id', session('selected_company_id'))->where('employee_status', 'active')->get() as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->employeeID }} - {{ $employee->employee_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Action</label>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-success punch-action"
                                            data-action="punch_in">
                                            <i class="fe fe-log-in"></i> Punch In
                                        </button>
                                        <button type="button" class="btn btn-warning punch-action"
                                            data-action="break_start">
                                            <i class="fe fe-pause"></i> Break Start
                                        </button>
                                        <button type="button" class="btn btn-info punch-action"
                                            data-action="break_end">
                                            <i class="fe fe-play"></i> Break End
                                        </button>
                                        <button type="button" class="btn btn-danger punch-action"
                                            data-action="punch_out">
                                            <i class="fe fe-log-out"></i> Punch Out
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Today's Status</h3>
                        </div>
                        <div class="card-body" id="todayStatus">
                            <p class="text-muted">Select an employee to view today's punch status.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Operations Tab -->
        <div class="tab-pane fade" id="bulk" role="tabpanel" aria-labelledby="bulk-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bulk Punch Operations</h3>
                        </div>
                        <div class="card-body">
                            <form id="bulkPunchForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Action</label>
                                            <select name="action" id="bulk_action" class="form-control" required>
                                                <option value="">Select Action</option>
                                                <option value="punch_in">Punch In</option>
                                                <option value="punch_out">Punch Out</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Time</label>
                                            <input type="time" name="punch_time" id="bulk_punch_time"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Employees</label>
                                            <select name="employee_ids[]" id="bulk_employee_ids" class="form-control"
                                                multiple required>
                                                @foreach (\App\Models\Employee::where('company_id', session('selected_company_id'))->where('employee_status', 'active')->get() as $employee)
                                                    <option value="{{ $employee->id }}">
                                                        {{ $employee->employeeID }} - {{ $employee->employee_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-upload"></i> Execute Bulk Operation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Shift Modal -->
    <div class="modal fade" id="addShiftModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Shift</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="shiftForm" method="POST" action="{{ route('shift.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="shift_name" class="form-label">Shift Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="shift_name" id="shift_name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time" class="form-label">Start Time <span
                                            class="text-danger">*</span></label>
                                    <input type="time" name="start_time" id="start_time" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time" class="form-label">End Time <span
                                            class="text-danger">*</span></label>
                                    <input type="time" name="end_time" id="end_time" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="break_duration_minutes" class="form-label">Break Duration (minutes)</label>
                            <input type="number" name="break_duration_minutes" id="break_duration_minutes"
                                class="form-control" value="60" min="0" max="480">
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Shift</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Shift Modal -->
    <div class="modal fade" id="editShiftModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Shift</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="editShiftForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_shift_name" class="form-label">Shift Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="shift_name" id="edit_shift_name" class="form-control"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_start_time" class="form-label">Start Time <span
                                            class="text-danger">*</span></label>
                                    <input type="time" name="start_time" id="edit_start_time"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_end_time" class="form-label">End Time <span
                                            class="text-danger">*</span></label>
                                    <input type="time" name="end_time" id="edit_end_time" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_break_duration_minutes" class="form-label">Break Duration
                                (minutes)</label>
                            <input type="number" name="break_duration_minutes" id="edit_break_duration_minutes"
                                class="form-control" min="0" max="480">
                        </div>
                        <div class="form-group">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Shift</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                $('#shiftsTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [
                        [1, 'asc']
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: -1
                    }]
                });

                // Set current time for bulk operations
                $('#bulk_punch_time').val(new Date().toTimeString().slice(0, 5));

                // Edit shift
                $('.edit-shift').click(function() {
                    const shiftId = $(this).data('id');
                    const name = $(this).data('name');
                    const start = $(this).data('start');
                    const end = $(this).data('end');
                    const breakDuration = $(this).data('break');
                    const description = $(this).data('description');

                    $('#editShiftForm').attr('action', `/shift/${shiftId}`);
                    $('#edit_shift_name').val(name);
                    $('#edit_start_time').val(start);
                    $('#edit_end_time').val(end);
                    $('#edit_break_duration_minutes').val(breakDuration);
                    $('#edit_description').val(description);
                    $('#editShiftModal').modal('show');
                });

                // Toggle shift status
                $('.toggle-shift').click(function() {
                    const shiftId = $(this).data('id');

                    if (confirm('Are you sure you want to toggle this shift status?')) {
                        $.ajax({
                            url: `/shift/${shiftId}/toggle`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('Error: ' + xhr.responseText);
                            }
                        });
                    }
                });

                // Delete shift
                $('.delete-shift').click(function() {
                    const shiftId = $(this).data('id');
                    const shiftName = $(this).data('name');

                    if (confirm(
                            `Are you sure you want to delete "${shiftName}"? This action cannot be undone.`)) {
                        $.ajax({
                            url: `/shift/${shiftId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('Error: ' + xhr.responseText);
                            }
                        });
                    }
                });

                // Punch actions
                $('.punch-action').click(function() {
                    const employeeId = $('#punch_employee_id').val();
                    const action = $(this).data('action');

                    if (!employeeId) {
                        alert('Please select an employee first.');
                        return;
                    }

                    $.ajax({
                        url: '/shift/punch',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            employee_id: employeeId,
                            action: action
                        },
                        success: function(response) {
                            alert(response.message);
                            loadTodayStatus(employeeId);
                        },
                        error: function(xhr) {
                            const error = JSON.parse(xhr.responseText);
                            alert('Error: ' + error.error);
                        }
                    });
                });

                // Load today's status when employee is selected
                $('#punch_employee_id').change(function() {
                    const employeeId = $(this).val();
                    if (employeeId) {
                        loadTodayStatus(employeeId);
                    } else {
                        $('#todayStatus').html(
                            '<p class="text-muted">Select an employee to view today\'s punch status.</p>');
                    }
                });

                // Bulk punch form
                $('#bulkPunchForm').submit(function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const employeeIds = $('#bulk_employee_ids').val();

                    if (!employeeIds || employeeIds.length === 0) {
                        alert('Please select at least one employee.');
                        return;
                    }

                    formData.append('employee_ids', JSON.stringify(employeeIds));

                    $.ajax({
                        url: '/shift/bulk-punch',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            alert(response.message);
                            console.log('Results:', response.results);
                        },
                        error: function(xhr) {
                            const error = JSON.parse(xhr.responseText);
                            alert('Error: ' + error.message);
                        }
                    });
                });
            });

            // Load today's status for selected employee
            function loadTodayStatus(employeeId) {
                $.ajax({
                    url: `/shift/punch-records`,
                    type: 'GET',
                    data: {
                        employee_id: employeeId,
                        start_date: new Date().toISOString().split('T')[0],
                        end_date: new Date().toISOString().split('T')[0]
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            const record = response[0];
                            let statusHtml = `
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Punch In:</strong> ${record.punch_in_time || 'Not punched in'}<br>
                                <strong>Punch Out:</strong> ${record.punch_out_time || 'Not punched out'}<br>
                            </div>
                            <div class="col-md-6">
                                <strong>Break Start:</strong> ${record.break_start_time || 'Not started'}<br>
                                <strong>Break End:</strong> ${record.break_end_time || 'Not ended'}<br>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Working Hours:</strong> ${record.total_working_hours} hours<br>
                                <strong>Overtime:</strong> ${record.overtime_hours} hours<br>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong> <span class="badge badge-${getStatusBadgeClass(record.status)}">${record.status}</span><br>
                            </div>
                        </div>
                    `;
                            $('#todayStatus').html(statusHtml);
                        } else {
                            $('#todayStatus').html('<p class="text-muted">No punch record found for today.</p>');
                        }
                    },
                    error: function(xhr) {
                        $('#todayStatus').html('<p class="text-danger">Error loading status.</p>');
                    }
                });
            }

            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'present':
                        return 'success';
                    case 'late':
                        return 'warning';
                    case 'absent':
                        return 'danger';
                    case 'half_day':
                        return 'info';
                    default:
                        return 'secondary';
                }
            }
        </script>
    @endpush
</x-app-layout>
