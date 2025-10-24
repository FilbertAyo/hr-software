<x-app-layout>
    <!-- Page Header -->
        <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="absent-late-tab" data-toggle="tab" href="#absent-late" role="tab"
                        aria-controls="absent-late" aria-selected="true">Bulk Add Attendance Records</a>
                </li>
            </ul>
        </div>

        <div class="col-auto">
            <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-secondary">
                <i class="fe fe-arrow-left"></i> Back to Attendance
            </a>
        </div>
    </div>


    @if($currentPayrollPeriod)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
             
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Current Payroll Period:</strong> {{ $currentPayrollPeriod->period_name }}
                        ({{ $currentPayrollPeriod->start_date->format('M d, Y') }} - {{ $currentPayrollPeriod->end_date->format('M d, Y') }})
                    </div>

                    <form method="POST" action="{{ route('attendance.bulk-store') }}" id="bulkAttendanceForm">
                        @csrf
                        <input type="hidden" name="payroll_period_id" value="{{ $currentPayrollPeriod->id }}">

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" id="addRecordBtn">
                                    <i class="fe fe-plus"></i> Add Record
                                </button>
                                <button type="button" class="btn btn-danger" id="removeAllBtn">
                                    <i class="fe fe-trash-2"></i> Remove All
                                </button>
                                <button type="button" class="btn btn-info" id="copyLastBtn">
                                    <i class="fe fe-copy"></i> Copy Last Record
                                </button>
                            </div>
                        </div>

                        <div id="attendanceRecords">
                            <!-- Records will be added dynamically -->
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fe fe-save"></i> Save All Records
                            </button>
                            <a href="{{ route('absent-late.index') }}" class="btn btn-secondary">
                                <i class="fe fe-arrow-left"></i> Back to Absent & Late Management
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for attendance record -->
    <template id="attendanceRecordTemplate">
        <div class="attendance-record border rounded p-3 mb-3" data-index="">
            <div class="row">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Employee <span class="text-danger">*</span></label>
                                <select name="attendance_records[INDEX][employee_id]" class="form-control employee-select" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->employeeID }} - {{ $employee->employee_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="attendance_records[INDEX][attendance_type]" class="form-control attendance-type" required>
                                    <option value="">Select Type</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <input type="text" name="attendance_records[INDEX][reason]" class="form-control" placeholder="Enter reason" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Notes</label>
                                <input type="text" name="attendance_records[INDEX][notes]" class="form-control" placeholder="Optional notes">
                            </div>
                        </div>
                    </div>

                    <!-- Absent Fields -->
                    <div class="row absent-fields" style="display: none;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Absent Days <span class="text-danger">*</span></label>
                                <input type="number" name="attendance_records[INDEX][absent_days]" class="form-control" min="1" max="31">
                            </div>
                        </div>
                    </div>

                    <!-- Late Fields -->
                    <div class="row late-fields" style="display: none;">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Expected Time <span class="text-danger">*</span></label>
                                <input type="time" name="attendance_records[INDEX][expected_time]" class="form-control expected-time" value="08:00">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Actual Time <span class="text-danger">*</span></label>
                                <input type="time" name="attendance_records[INDEX][late_time]" class="form-control late-time">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Late Hours <span class="text-danger">*</span></label>
                                <input type="number" name="attendance_records[INDEX][late_hours]" class="form-control late-hours" step="0.5" min="0.5" max="24" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block remove-record">
                            <i class="fe fe-trash-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @else
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fe fe-alert-circle fe-48 text-danger"></i>
                    <h4 class="mt-3 text-danger">No Payroll Period Selected</h4>
                    <p class="text-muted">Please select a payroll period to add attendance records.</p>
                    <a href="{{ route('absent-late.index') }}" class="btn btn-primary">
                        <i class="fe fe-arrow-left"></i> Back to Absent & Late Management
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
    $(document).ready(function() {
    let recordIndex = 0;

    // Add new record
    $('#addRecordBtn').click(function() {
        addAttendanceRecord();
    });

    // Remove all records
    $('#removeAllBtn').click(function() {
        if (confirm('Are you sure you want to remove all records?')) {
            $('#attendanceRecords').empty();
            recordIndex = 0;
            updateSubmitButton();
        }
    });

    // Copy last record
    $('#copyLastBtn').click(function() {
        const lastRecord = $('.attendance-record').last();
        if (lastRecord.length > 0) {
            addAttendanceRecord();
            const newRecord = $('.attendance-record').last();

            // Copy values from last record
            newRecord.find('.employee-select').val(lastRecord.find('.employee-select').val());
            newRecord.find('.attendance-type').val(lastRecord.find('.attendance-type').val());
            newRecord.find('input[name*="[reason]"]').val(lastRecord.find('input[name*="[reason]"]').val());
            newRecord.find('input[name*="[notes]"]').val(lastRecord.find('input[name*="[notes]"]').val());

            // Trigger change events
            newRecord.find('.attendance-type').trigger('change');
        } else {
            addAttendanceRecord();
        }
    });

    function addAttendanceRecord() {
        const template = document.getElementById('attendanceRecordTemplate');
        const html = template.innerHTML.replace(/INDEX/g, recordIndex);

        $('#attendanceRecords').append(html);
        recordIndex++;

        updateSubmitButton();
        attachEventHandlers($('.attendance-record').last());
    }

    function attachEventHandlers(record) {
        // Attendance type change
        record.find('.attendance-type').change(function() {
            const type = $(this).val();
            const absentFields = record.find('.absent-fields');
            const lateFields = record.find('.late-fields');

            if (type === 'absent') {
                absentFields.show();
                lateFields.hide();
                record.find('input[name*="[absent_days]"]').prop('required', true);
                record.find('input[name*="[expected_time]"], input[name*="[late_time]"], input[name*="[late_hours]"]').prop('required', false);
            } else if (type === 'late') {
                lateFields.show();
                absentFields.hide();
                record.find('input[name*="[expected_time]"], input[name*="[late_time]"], input[name*="[late_hours]"]').prop('required', true);
                record.find('input[name*="[absent_days]"]').prop('required', false);
            } else {
                absentFields.hide();
                lateFields.hide();
                record.find('input[name*="[absent_days]"], input[name*="[expected_time]"], input[name*="[late_time]"], input[name*="[late_hours]"]').prop('required', false);
            }
        });

        // Remove record
        record.find('.remove-record').click(function() {
            $(this).closest('.attendance-record').remove();
            updateSubmitButton();
        });

        // Calculate late hours
        record.find('.expected-time, .late-time').change(function() {
            const expectedTime = record.find('.expected-time').val();
            const lateTime = record.find('.late-time').val();

            if (expectedTime && lateTime) {
                const expected = new Date('2000-01-01 ' + expectedTime);
                const late = new Date('2000-01-01 ' + lateTime);

                if (late > expected) {
                    const diffMs = late - expected;
                    const diffHours = diffMs / (1000 * 60 * 60);
                    record.find('.late-hours').val(Math.round(diffHours * 2) / 2);
                } else {
                    record.find('.late-hours').val('');
                }
            }
        });

        // Set default date
        const today = new Date().toISOString().split('T')[0];
        record.find('input[type="date"]').val(today);
    }

    function updateSubmitButton() {
        const recordCount = $('.attendance-record').length;
        if (recordCount > 0) {
            $('#submitBtn').prop('disabled', false);
        } else {
            $('#submitBtn').prop('disabled', true);
        }
    }

    // Form submission validation
    $('#bulkAttendanceForm').submit(function(e) {
        const records = $('.attendance-record');
        if (records.length === 0) {
            e.preventDefault();
            alert('Please add at least one attendance record.');
            return false;
        }

        // Validate each record
        let isValid = true;
        records.each(function() {
            const record = $(this);
            const employeeId = record.find('.employee-select').val();
            const type = record.find('.attendance-type').val();
            const reason = record.find('input[name*="[reason]"]').val();

            if (!employeeId || !type || !reason) {
                isValid = false;
                record.addClass('border-danger');
            } else {
                record.removeClass('border-danger');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields for all records.');
            return false;
        }
    });

    // Add initial record
    addAttendanceRecord();
    });
    </script>
    @endpush
</x-app-layout>
