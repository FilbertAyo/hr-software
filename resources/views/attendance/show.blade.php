<x-app-layout>
    <!-- Page Header -->
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="absent-late-tab" data-toggle="tab" href="#absent-late" role="tab"
                        aria-controls="absent-late" aria-selected="true">Employee Attendance Details</a>
                </li>
            </ul>
        </div>

        <div class="col-auto">
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                <i class="fe fe-arrow-left"></i> Back to Attendance
            </a>
        </div>
    </div>


    <!-- Employee Info -->
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Employee ID:</strong> {{ $employee->employeeID }}
                        </div>
                        <div class="col-md-3">
                            <strong>Name:</strong> {{ $employee->employee_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Department:</strong>
                            {{ $employee->department->department->department_name ?? 'N/A' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Basic Salary:</strong> TZS {{ number_format($employee->basic_salary, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="h5 mb-0 text-danger">{{ $employee->absentRecords->sum('absent_days') }}</div>
                            <p class="text-muted mb-0">Total Absent Days</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fe fe-user-x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="h5 mb-0 text-warning">{{ $employee->lateRecords->sum('late_hours') }}</div>
                            <p class="text-muted mb-0">Total Late Hours</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fe fe-clock text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="h5 mb-0 text-info">
                                {{ $employee->absentRecords->count() + $employee->lateRecords->count() }}</div>
                            <p class="text-muted mb-0">Total Records</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fe fe-list text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="h5 mb-0 text-success">
                                {{ $employee->absentRecords->where('status', 'approved')->count() + $employee->lateRecords->where('status', 'approved')->count() }}
                            </div>
                            <p class="text-muted mb-0">Approved Records</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fe fe-check-circle text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Absent Records -->
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Absent Records</h5>
                </div>
                <div class="card-body">
                    @if ($employee->absentRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Days</th>
                                        <th>Reason</th>
                                        <th>Notes</th>
                                        <th>Status</th>
                                        <th>Approved By</th>
                                        <th>Approved At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee->absentRecords as $record)
                                        <tr>
                                            <td>
                                                <span class="badge badge-danger">{{ $record->absent_days ?? 0 }}
                                                    {{ ($record->absent_days ?? 0) == 1 ? 'day' : 'days' }}</span>
                                            </td>
                                            <td>{{ $record->reason }}</td>
                                            <td>{{ $record->notes ?? 'N/A' }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $record->status == 'approved' ? 'success' : ($record->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($record->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $record->approver->name ?? 'N/A' }}</td>
                                            <td>{{ $record->approved_at ? $record->approved_at->format('M d, Y H:i') : 'N/A' }}
                                            </td>
                                            <td>
                                                @if ($record->status == 'pending' || $record->status == 'approved')
                                                    <button type="button" class="btn btn-sm btn-danger delete-record"
                                                        data-id="{{ $record->id }}">
                                                        <i class="fe fe-trash-2"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fe fe-user-check fe-48 text-muted"></i>
                            <h4 class="mt-3 text-muted">No Absent Records</h4>
                            <p class="text-muted">This employee has no absent records.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Late Records -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Late Records</h5>
                </div>
                <div class="card-body">
                    @if ($employee->lateRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Expected Time</th>
                                        <th>Actual Time</th>
                                        <th>Late Hours</th>
                                        <th>Reason</th>
                                        <th>Notes</th>
                                        <th>Status</th>
                                        <th>Approved By</th>
                                        <th>Approved At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee->lateRecords as $record)
                                        <tr>
                                            <td>{{ $record->expected_time ? \Carbon\Carbon::parse($record->expected_time)->format('H:i') : 'N/A' }}
                                            </td>
                                            <td>{{ $record->late_time ? \Carbon\Carbon::parse($record->late_time)->format('H:i') : 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="badge badge-warning">{{ $record->late_hours ?? 0 }}
                                                    {{ ($record->late_hours ?? 0) == 1 ? 'hour' : 'hours' }}</span>
                                            </td>
                                            <td>{{ $record->reason }}</td>
                                            <td>{{ $record->notes ?? 'N/A' }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $record->status == 'approved' ? 'success' : ($record->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($record->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $record->approver->name ?? 'N/A' }}</td>
                                            <td>{{ $record->approved_at ? $record->approved_at->format('M d, Y H:i') : 'N/A' }}
                                            </td>
                                            <td>
                                                @if ($record->status == 'pending' || $record->status == 'approved')
                                                    <button type="button" class="btn btn-sm btn-danger delete-record"
                                                        data-id="{{ $record->id }}">
                                                        <i class="fe fe-trash-2"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fe fe-clock fe-48 text-muted"></i>
                            <h4 class="mt-3 text-muted">No Late Records</h4>
                            <p class="text-muted">This employee has no late records.</p>
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
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this attendance record? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                $(document).ready(function() {
                    // Delete record
                    $('.delete-record').click(function() {
                        const recordId = $(this).data('id');
                        $('#deleteForm').attr('action', '{{ route('attendance.destroy') }}');
                        $('#deleteForm').append('<input type="hidden" name="activity_ids[]" value="' + recordId +
                            '">');
                        $('#deleteModal').modal('show');
                    });

                    // Clear form when modal is closed
                    $('#deleteModal').on('hidden.bs.modal', function() {
                        $('#deleteForm').find('input[name="activity_ids[]"]').remove();
                    });
                });
            </script>
        @endpush
</x-app-layout>
