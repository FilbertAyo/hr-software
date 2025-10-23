<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Edit Leave</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-16 fe-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <form method="POST" action="{{ route('leaves.update', $leave->id) }}" id="leaveEditForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-row">
                                        <!-- Employee Selection -->
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_id">Select Employee <span class="text-danger">*</span></label>
                                            <select class="form-control" id="employee_id" name="employee_id" required>
                                                <option value="">Choose Employee...</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                            {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->employee_name }} - {{ $employee->employeeID }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Leave Type Selection -->
                                        <div class="col-md-6 mb-3">
                                            <label for="leave_type_id">Leave Type <span class="text-danger">*</span></label>
                                            <select class="form-control" id="leave_type_id" name="leave_type_id" required>
                                                <option value="">Choose Leave Type...</option>
                                                @foreach($leaveTypes as $leaveType)
                                                    <option value="{{ $leaveType->id }}"
                                                            data-days="{{ $leaveType->no_of_days }}"
                                                            data-extra-days="{{ $leaveType->extra_days }}"
                                                            {{ old('leave_type_id', $leave->leave_type_id) == $leaveType->id ? 'selected' : '' }}>
                                                        {{ $leaveType->leave_type_name }} ({{ $leaveType->no_of_days }} days)
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('leave_type_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <!-- Leave Action -->
                                        <div class="col-md-6 mb-3">
                                            <label for="leave_action">Leave Action <span class="text-danger">*</span></label>
                                            <select class="form-control" id="leave_action" name="leave_action" required>
                                                <option value="">Choose Action...</option>
                                                <option value="proceed" {{ old('leave_action', $leave->leave_action) == 'proceed' ? 'selected' : '' }}>Proceed on Leave</option>
                                                <option value="sold" {{ old('leave_action', $leave->leave_action) == 'sold' ? 'selected' : '' }}>Sold Leave</option>
                                                <option value="emergency" {{ old('leave_action', $leave->leave_action) == 'emergency' ? 'selected' : '' }}>Emergency Leave</option>
                                                <option value="compensatory" {{ old('leave_action', $leave->leave_action) == 'compensatory' ? 'selected' : '' }}>Compensatory Leave</option>
                                            </select>
                                            @error('leave_action')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-6 mb-3">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="Pending" {{ old('status', $leave->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Approved" {{ old('status', $leave->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="Rejected" {{ old('status', $leave->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <!-- From Date -->
                                        <div class="col-md-4 mb-3">
                                            <label for="from_date">From Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="from_date" name="from_date"
                                                   value="{{ old('from_date', $leave->from_date ? $leave->from_date->format('Y-m-d') : '') }}" required>
                                            @error('from_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- To Date -->
                                        <div class="col-md-4 mb-3">
                                            <label for="to_date">To Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="to_date" name="to_date"
                                                   value="{{ old('to_date', $leave->to_date ? $leave->to_date->format('Y-m-d') : '') }}" required>
                                            @error('to_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Number of Days -->
                                        <div class="col-md-4 mb-3">
                                            <label for="no_of_days">Number of Days <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="no_of_days" name="no_of_days"
                                                   value="{{ old('no_of_days', $leave->no_of_days) }}" min="1" required readonly>
                                            @error('no_of_days')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Remarks -->
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                                      placeholder="Enter any additional remarks...">{{ old('remarks', $leave->remarks) }}</textarea>
                                            @error('remarks')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">
                                                <span class="fe fe-save fe-16 mr-2"></span>Update Leave
                                            </button>
                                            <a href="{{ route('leaves.show', $leave->id) }}" class="btn btn-info ml-2">
                                                <span class="fe fe-eye fe-16 mr-2"></span>View Details
                                            </a>
                                            <a href="{{ route('leaves.index') }}" class="btn btn-secondary ml-2">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leaveTypeSelect = document.getElementById('leave_type_id');
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');
            const noDaysInput = document.getElementById('no_of_days');

            // Auto-populate to_date when leave type and from_date are selected
            function updateToDate() {
                const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
                const fromDate = fromDateInput.value;

                if (selectedOption && fromDate && selectedOption.value) {
                    const days = parseInt(selectedOption.getAttribute('data-days')) || 0;
                    if (days > 0) {
                        const startDate = new Date(fromDate);
                        const endDate = new Date(startDate);
                        endDate.setDate(startDate.getDate() + days - 1); // Subtract 1 because start date is included

                        const formattedEndDate = endDate.toISOString().split('T')[0];
                        toDateInput.value = formattedEndDate;

                        calculateDays();
                    }
                }
            }

            // Calculate number of days between from_date and to_date
            function calculateDays() {
                const fromDate = fromDateInput.value;
                const toDate = toDateInput.value;

                if (fromDate && toDate) {
                    const start = new Date(fromDate);
                    const end = new Date(toDate);

                    if (end >= start) {
                        const timeDiff = end.getTime() - start.getTime();
                        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Add 1 to include both start and end dates
                        noDaysInput.value = daysDiff;
                    } else {
                        noDaysInput.value = '';
                    }
                }
            }

            // Event listeners
            leaveTypeSelect.addEventListener('change', updateToDate);
            fromDateInput.addEventListener('change', function() {
                updateToDate();
                calculateDays();
            });
            toDateInput.addEventListener('change', calculateDays);

            // Make no_of_days editable but recalculate on date changes
            noDaysInput.addEventListener('focus', function() {
                this.removeAttribute('readonly');
            });

            noDaysInput.addEventListener('blur', function() {
                this.setAttribute('readonly', true);
            });

            // Calculate days on page load if dates are already set
            calculateDays();
        });
    </script>

</x-app-layout>
