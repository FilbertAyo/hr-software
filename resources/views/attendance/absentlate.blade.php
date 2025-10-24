<x-app-layout>
    <!-- Page Header -->
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="absent-late-tab" data-toggle="tab" href="#absent-late" role="tab"
                        aria-controls="absent-late" aria-selected="true">Absent & Late Management</a>
                </li>
            </ul>
        </div>

        <div class="col-auto">
           
            <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-primary">
                <i class="fe fe-plus"></i> Add Record
            </a>
        </div>
    </div>


    @if ($currentPeriod)
        <!-- Current Period Info -->
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong>Current Payroll Period:</strong> {{ $currentPeriod->period_name }}
                    ({{ $currentPeriod->start_date->format('M d, Y') }} -
                    {{ $currentPeriod->end_date->format('M d, Y') }})
                </div>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        @if ($attendanceRecords->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Employee</th>
                                            <th>Type</th>
                                            <th>Days/Hours</th>
                                            <th>Reason</th>
                                            <th>Notes</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendanceRecords as $employeeId => $records)
                                            @foreach ($records as $record)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $record->employee->employeeID }}</strong><br>
                                                        <small>{{ $record->employee->employee_name }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($record->attendance_type === 'absent')
                                                            <span class="badge badge-danger">Absent</span>
                                                        @else
                                                            <span class="badge badge-warning">Late</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($record->attendance_type === 'absent')
                                                            <span class="text-danger">
                                                                <strong>{{ $record->absent_days ?? 0 }}</strong> day(s)
                                                            </span>
                                                        @else
                                                            <span class="text-warning">
                                                                <strong>{{ $record->late_hours ?? 0 }}</strong> hour(s)
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $record->reason }}</td>
                                                    <td>{{ $record->notes ?? '-' }}</td>
                                                    <td>{{ $record->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary edit-record"
                                                                data-id="{{ $record->id }}"
                                                                data-type="{{ $record->attendance_type }}"
                                                                data-days="{{ $record->absent_days ?? 0 }}"
                                                                data-hours="{{ $record->late_hours ?? 0 }}"
                                                                data-reason="{{ $record->reason }}">
                                                                <i class="fe fe-edit"></i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-record"
                                                                data-id="{{ $record->id }}">
                                                                <i class="fe fe-trash-2"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fe fe-user-check fe-48 text-muted mb-3"></i>
                                <h4 class="text-muted">No Attendance Records</h4>
                                <p class="text-muted">No attendance records found for the current payroll period.</p>
                                <a href="{{ route('attendance.create') }}" class="btn btn-primary mt-3">
                                    <i class="fe fe-plus"></i> Add First Record
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Record Modal -->
        <div class="modal fade" id="editRecordModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Attendance Record</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form id="editRecordForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group" id="edit_absent_group">
                                <label for="edit_absent_days" class="form-label">Absent Days</label>
                                <input type="number" name="absent_days" id="edit_absent_days" class="form-control"
                                    min="0" max="31" step="1">
                            </div>
                            <div class="form-group" id="edit_late_group">
                                <label for="edit_late_hours" class="form-label">Late Hours</label>
                                <input type="number" name="late_hours" id="edit_late_hours" class="form-control"
                                    min="0" max="24" step="0.5">
                            </div>
                            <div class="form-group">
                                <label for="edit_reason" class="form-label">Reason</label>
                                <textarea name="reason" id="edit_reason" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Employee Summary Modal -->
        <div class="modal fade" id="employeeSummaryModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Employee Attendance Summary</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="employeeSummaryContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No Current Period -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fe fe-calendar fe-48 text-muted mb-3"></i>
                        <h4 class="text-muted">No Current Payroll Period</h4>
                        <p class="text-muted">Please create a payroll period to manage attendance records.</p>
                        <a href="{{ route('payroll-periods.create') }}" class="btn btn-primary mt-3">
                            <i class="fe fe-plus"></i> Create Payroll Period
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                $('#dataTable-1').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [
                        [5, 'desc']
                    ], // Sort by date descending
                    columnDefs: [{
                            orderable: false,
                            targets: -1
                        } // Disable sorting on Actions column
                    ]
                });

                // Edit record
                $('.edit-record').click(function() {
                    const recordId = $(this).data('id');
                    const type = $(this).data('type');
                    const days = $(this).data('days');
                    const hours = $(this).data('hours');
                    const reason = $(this).data('reason');

                    $('#editRecordForm').attr('action', `/absent-late/${recordId}`);

                    // Show/hide fields based on type
                    if (type === 'absent') {
                        $('#edit_absent_group').show();
                        $('#edit_late_group').hide();
                        $('#edit_absent_days').val(days);
                        $('#edit_late_hours').val(0);
                    } else {
                        $('#edit_absent_group').hide();
                        $('#edit_late_group').show();
                        $('#edit_absent_days').val(0);
                        $('#edit_late_hours').val(hours);
                    }

                    $('#edit_reason').val(reason);
                    $('#editRecordModal').modal('show');
                });

                // Delete record
                $('.delete-record').click(function() {
                    const recordId = $(this).data('id');

                    if (confirm('Are you sure you want to delete this attendance record?')) {
                        $.ajax({
                            url: `/absent-late/${recordId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('Error deleting record: ' + xhr.responseJSON?.message || xhr
                                    .responseText);
                            }
                        });
                    }
                });
            });

            // Load employee summary
            function loadEmployeeSummary(employeeId) {
                if (!employeeId) {
                    alert('Please select an employee first.');
                    return;
                }

                $.ajax({
                    url: `/absent-late/employee/${employeeId}/summary`,
                    type: 'GET',
                    success: function(data) {
                        displayEmployeeSummary(data);
                    },
                    error: function(xhr) {
                        alert('Error loading employee summary: ' + (xhr.responseJSON?.error || xhr.responseText));
                    }
                });
            }

            // Display employee summary
            function displayEmployeeSummary(data) {
                const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6><strong>Employee Information</strong></h6>
                    <p><strong>Name:</strong> ${data.employee.name}</p>
                    <p><strong>ID:</strong> ${data.employee.employee_id}</p>
                    <p><strong>Basic Salary:</strong> TZS ${data.employee.basic_salary.toLocaleString()}</p>
                    <p><strong>Working Days/Month:</strong> ${data.employee.working_days_per_month}</p>
                    <p><strong>Working Hours/Day:</strong> ${data.employee.working_hours_per_day}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>Current Period Attendance</strong></h6>
                    <p><strong>Absent Days:</strong> <span class="text-danger">${data.attendance.absent_days}</span></p>
                    <p><strong>Late Hours:</strong> <span class="text-warning">${data.attendance.late_hours}</span></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h6><strong>Deduction Calculations</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <td>Daily Salary:</td>
                                <td><strong>TZS ${data.deductions.daily_salary.toLocaleString()}</strong></td>
                            </tr>
                            <tr>
                                <td>Hourly Salary:</td>
                                <td><strong>TZS ${data.deductions.hourly_salary.toLocaleString()}</strong></td>
                            </tr>
                            <tr>
                                <td>Absent Deduction:</td>
                                <td><strong class="text-danger">TZS ${data.deductions.absent_deduction.toLocaleString()}</strong></td>
                            </tr>
                            <tr>
                                <td>Late Deduction:</td>
                                <td><strong class="text-warning">TZS ${data.deductions.late_deduction.toLocaleString()}</strong></td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Total Deduction:</strong></td>
                                <td><strong class="text-danger">TZS ${data.deductions.total_deduction.toLocaleString()}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        `;

                $('#employeeSummaryContent').html(content);
                $('#employeeSummaryModal').modal('show');
            }
        </script>
    @endpush
</x-app-layout>
