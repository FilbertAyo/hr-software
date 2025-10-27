<x-app-layout>
   
    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Add Attendance Record</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-sm" onclick="reloadPage()">
                <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
            </button>
        </div>
    </div>

    <!-- Display Validation Errors -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Please fix the following issues:
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Attendance Information</h5>
                </div>
                <div class="card-body">
                    @if($currentPayrollPeriod)
                    <div class="alert alert-info">
                        <strong>Current Payroll Period:</strong> {{ $currentPayrollPeriod->period_name }} 
                        ({{ $currentPayrollPeriod->start_date->format('M d, Y') }} - {{ $currentPayrollPeriod->end_date->format('M d, Y') }})
                    </div>

                    <form method="POST" action="{{ route('absent-late.store') }}" id="attendanceForm">
                        @csrf
                        
                        <input type="hidden" name="payroll_period_id" value="{{ $currentPayrollPeriod->id }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->employeeID }} - {{ $employee->employee_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="absent_days" class="form-label">Absent Days</label>
                                    <input type="number" name="absent_days" id="absent_days" 
                                           class="form-control @error('absent_days') is-invalid @enderror" 
                                           value="{{ old('absent_days', 0) }}" min="0" max="31" step="1" placeholder="0">
                                    @error('absent_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter number of days employee was absent</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="late_hours" class="form-label">Late Hours</label>
                                    <input type="number" name="late_hours" id="late_hours" 
                                           class="form-control @error('late_hours') is-invalid @enderror" 
                                           value="{{ old('late_hours', 0) }}" step="0.5" min="0" max="24" placeholder="0">
                                    @error('late_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter number of hours employee was late</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" rows="3" 
                                              class="form-control @error('reason') is-invalid @enderror" 
                                              placeholder="Enter reason for absence or lateness (required)" required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes" class="form-label">Additional Notes</label>
                                    <textarea name="notes" id="notes" rows="2" 
                                              class="form-control @error('notes') is-invalid @enderror" 
                                              placeholder="Additional notes (optional)">{{ old('notes') }}</textarea>
                                    @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fe fe-save"></i> Add Attendance Record
                            </button>
                            <a href="{{ route('absent-late.index') }}" class="btn btn-secondary">
                                <i class="fe fe-arrow-left"></i> Back to Absent & Late Management
                            </a>
                        </div>
                    </form>

                    @else
                    <div class="alert alert-warning">
                        <strong>No Current Payroll Period:</strong> Please create a payroll period to manage attendance records.
                    </div>
                    <a href="{{ route('payroll-periods.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus"></i> Create Payroll Period
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    @if($currentPayrollPeriod)
                    <div class="list-group">
                        <a href="{{ route('attendance.bulk-create') }}" class="list-group-item list-group-item-action">
                            <i class="fe fe-upload"></i> Bulk Add Attendance
                        </a>
                        <a href="{{ route('absent-late.index') }}" class="list-group-item list-group-item-action">
                            <i class="fe fe-list"></i> View All Records
                        </a>
                        <a href="{{ route('attendance.export') }}" class="list-group-item list-group-item-action">
                            <i class="fe fe-download"></i> Export to CSV
                        </a>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <strong>No Current Payroll Period:</strong> Please create a payroll period to access quick actions.
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Information</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><strong>Absent Records:</strong></h6>
                        <p class="mb-2">• Deducted from basic salary</p>
                        <p class="mb-0">• Daily salary = Basic salary ÷ Working days in period</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><strong>Late Records:</strong></h6>
                        <p class="mb-2">• Deducted proportionally</p>
                        <p class="mb-0">• Hourly deduction = Daily salary ÷ Working hours per day</p>
                        <p class="mb-0 mt-1"><small>(Uses shift working hours if employee has a shift assigned)</small></p>
                    </div>

                    <div class="alert alert-secondary">
                        <h6><strong>Requirements:</strong></h6>
                        <ul class="mb-0 pl-3">
                            <li>Select an employee</li>
                            <li>Enter absent days OR late hours (or both)</li>
                            <li>Provide a reason</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function reloadPage() {
        location.reload();
    }

    $(document).ready(function() {
        // Form validation before submit
        $('#attendanceForm').on('submit', function(e) {
            const absentDays = parseFloat($('#absent_days').val()) || 0;
            const lateHours = parseFloat($('#late_hours').val()) || 0;
            const reason = $('#reason').val().trim();
            const employeeId = $('#employee_id').val();

            // Check if employee is selected
            if (!employeeId) {
                e.preventDefault();
                alert('Please select an employee.');
                $('#employee_id').focus();
                return false;
            }

            // Check if at least one value is provided
            if (absentDays === 0 && lateHours === 0) {
                e.preventDefault();
                alert('Please enter either Absent Days or Late Hours (or both).');
                $('#absent_days').focus();
                return false;
            }

            // Check if reason is provided
            if (!reason) {
                e.preventDefault();
                alert('Please provide a reason for the absence or lateness.');
                $('#reason').focus();
                return false;
            }

            // Disable submit button to prevent double submission
            $('#submitBtn').prop('disabled', true).html('<i class="fe fe-loader"></i> Saving...');
            
            return true;
        });

        // Auto-calculate deductions preview (optional)
        $('#employee_id, #absent_days, #late_hours').on('change', function() {
            const employeeId = $('#employee_id').val();
            const absentDays = parseFloat($('#absent_days').val()) || 0;
            const lateHours = parseFloat($('#late_hours').val()) || 0;

            if (employeeId && (absentDays > 0 || lateHours > 0)) {
                // You could add an AJAX call here to preview deductions
                console.log('Employee:', employeeId, 'Absent:', absentDays, 'Late:', lateHours);
            }
        });
    });
    </script>
    @endpush

</x-app-layout>