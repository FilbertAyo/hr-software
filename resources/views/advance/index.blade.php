<x-app-layout>


    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">advances</a>
                </li>

            </ul>
        </div>
        <div class="col-auto">

            <button type="button" class="btn btn-sm" onclick="reloadPage()">
                <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
            </button>
            <button type="button" class="btn mb-2 btn-success btn-sm" onclick="approveAllAdvances()">
                Approve All<span class="fe fe-check fe-16 ml-2"></span>
            </button>
            <x-modal-button>
                {{ __('Add advance') }}
            </x-modal-button>
        </div>
    </div>

    <div class="row my-2">
        <!-- Status Summary Cards -->
        <div class="col-12 mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow-none border">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $advances->where('status', 'pending')->count() }}</h5>
                            <p class="card-text">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-none border">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $advances->where('status', 'approved')->count() }}</h5>
                            <p class="card-text">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-none border">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $advances->where('status', 'rejected')->count() }}</h5>
                            <p class="card-text">Rejected</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-none border">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $advances->count() }}</h5>
                            <p class="card-text">Total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="col-12 mb-3">
                <div class="alert alert-danger">
                    <h6>Validation Errors:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">

                    <table class="table table-bordered datatables" id="dataTable-1">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Employee</th>
                                <th>Advance Amount</th>
                                <th>Payroll Period</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($advances as $index => $advance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $advance->employee->employee_name ?? '' }}</td>
                                    <td>{{ number_format($advance->advance_amount, 2) }}</td>
                                    <td>{{ $advance->payrollPeriod ? $advance->payrollPeriod->period_name : 'N/A' }}
                                    </td>
                                    <td>{{ $advance->reason ?? '-' }}</td>
                                    <td>
                                        @if ($advance->status === 'pending')
                                            <span class="badge badge-warning">{{ $advance->status }}</span>
                                        @elseif($advance->status === 'approved')
                                            <span class="badge badge-success">{{ $advance->status }}</span>
                                        @elseif($advance->status === 'rejected')
                                            <span class="badge badge-danger">{{ $advance->status }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $advance->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                            @if ($advance->status === 'pending')
                                                <form action="{{ route('advance.approve', $advance->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        title="Approve Advance"
                                                        onclick="return confirm('Are you sure you want to approve this advance?');">
                                                        <span class="fe fe-check fe-16"></span>
                                                    </button>
                                                </form>

                                                <form action="{{ route('advance.reject', $advance->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                        title="Reject Advance"
                                                        onclick="return confirm('Are you sure you want to reject this advance?');">
                                                        <span class="fe fe-x fe-16"></span>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($advance->status !== 'approved')
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-primary edit-advance-btn"
                                                    data-advance-id="{{ $advance->id }}"
                                                    data-employee-id="{{ $advance->employee_id }}"
                                                    data-advance-amount="{{ $advance->advance_amount }}"
                                                    data-reason="{{ $advance->reason }}">
                                                    <span class="fe fe-edit fe-16"></span>
                                                </a>

                                                <form action="{{ route('advance.destroy', $advance->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this advance?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <span class="fe fe-trash-2 fe-16"></span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No advance found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
    </div>


    <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('advance.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Advance</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Employee</label>
                            <select name="employee_id" id="employeeSelect" class="form-control" required>
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}" data-limit="{{ $emp->getAdvanceLimit() }}">
                                        {{ $emp->employee_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Advance Amount</label>
                            <input type="number" step="0.01" name="advance_amount" id="advanceAmount"
                                class="form-control" required>
                            <small id="limitInfo" class="text-muted">Please select an employee to see the advance
                                limit</small>
                        </div>

                        <div class="form-group">
                            <label>Advance Period</label>
                            <input type="text"
                                value="{{ $currentPayrollPeriod ? $currentPayrollPeriod->period_name : 'No active period' }}"
                                class="form-control" readonly>
                            <input type="hidden" name="payroll_period_id"
                                value="{{ $currentPayrollPeriod ? $currentPayrollPeriod->id : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Reason</label>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-secondary-button data-dismiss="modal">
                            {{ __('Close') }}
                        </x-secondary-button>
                        <x-primary-button>
                            {{ __('Save') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="editadvanceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="" id="editadvanceForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Advance</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Employee</label>
                            <select name="employee_id" id="editEmployeeId" class="form-control" required>
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}" data-limit="{{ $emp->getAdvanceLimit() }}">
                                        {{ $emp->employee_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Advance Amount</label>
                            <input type="number" step="0.01" id="editAdvanceAmount" name="advance_amount"
                                class="form-control" required>
                            <small id="editLimitInfo" class="text-muted">Please select an employee to see the advance
                                limit</small>
                        </div>

                        <div class="form-group">
                            <label>Advance Period</label>
                            <input type="text"
                                value="{{ $currentPayrollPeriod ? $currentPayrollPeriod->period_name : 'No active period' }}"
                                class="form-control" readonly>
                            <input type="hidden" name="payroll_period_id"
                                value="{{ $currentPayrollPeriod ? $currentPayrollPeriod->id : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Reason</label>
                            <textarea name="reason" id="editReason" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-secondary-button data-dismiss="modal">
                            {{ __('Close') }}
                        </x-secondary-button>
                        <x-primary-button>
                            {{ __('Update') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Handle employee selection for new advance
        document.getElementById('employeeSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const limit = selectedOption.dataset.limit;
            const limitInfo = document.getElementById('limitInfo');

            if (limit && limit > 0) {
                limitInfo.textContent =
                    `Maximum advance amount: ${parseFloat(limit).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                limitInfo.className = 'text-success';
                document.getElementById('advanceAmount').max = limit;
            } else {
                limitInfo.textContent = 'No advance limit available for this employee';
                limitInfo.className = 'text-danger';
                document.getElementById('advanceAmount').removeAttribute('max');
            }
        });

        // Handle employee selection for edit advance
        document.getElementById('editEmployeeId').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const limit = selectedOption.dataset.limit;
            const limitInfo = document.getElementById('editLimitInfo');

            if (limit && limit > 0) {
                limitInfo.textContent =
                    `Maximum advance amount: ${parseFloat(limit).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                limitInfo.className = 'text-success';
                document.getElementById('editAdvanceAmount').max = limit;
            } else {
                limitInfo.textContent = 'No advance limit available for this employee';
                limitInfo.className = 'text-danger';
                document.getElementById('editAdvanceAmount').removeAttribute('max');
            }
        });

        // Handle edit advance button clicks
        document.querySelectorAll('.edit-advance-btn').forEach(button => {
            button.addEventListener('click', function() {
                const advanceId = this.dataset.advanceId;

                document.getElementById('editadvanceForm').setAttribute('action', `/advance/${advanceId}`);
                document.getElementById('editEmployeeId').value = this.dataset.employeeId;
                document.getElementById('editAdvanceAmount').value = this.dataset.advanceAmount;
                document.getElementById('editReason').value = this.dataset.reason;

                // Trigger the change event to update the limit info
                document.getElementById('editEmployeeId').dispatchEvent(new Event('change'));

                $('#editadvanceModal').modal('show');
            });
        });

        // Validate advance amount on input
        document.getElementById('advanceAmount').addEventListener('input', function() {
            const amount = parseFloat(this.value);
            const maxAmount = parseFloat(this.max);

            if (amount > maxAmount && maxAmount > 0) {
                this.setCustomValidity(
                    `Amount cannot exceed ${maxAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`
                    );
            } else {
                this.setCustomValidity('');
            }
        });

        document.getElementById('editAdvanceAmount').addEventListener('input', function() {
            const amount = parseFloat(this.value);
            const maxAmount = parseFloat(this.max);

            if (amount > maxAmount && maxAmount > 0) {
                this.setCustomValidity(
                    `Amount cannot exceed ${maxAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`
                    );
            } else {
                this.setCustomValidity('');
            }
        });

        // Function to approve all pending advances
        function approveAllAdvances() {
            if (confirm('Are you sure you want to approve all pending advances?')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('advance.approve-all') }}';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>




</x-app-layout>
