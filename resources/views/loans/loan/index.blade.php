<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">loans</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">New loan<span
                                class="fe fe-plus fe-16 ml-2"></span></button>
                    </div>
                </div>

                <div class="row my-2">
                    @if(session('success'))
                        <div class="col-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="col-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    @include('elements.spinner')
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Employee</th>
                                            <th>Loan Type</th>
                                            <th>Loan Amount</th>
                                            <th>Remaining</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($loans as $index => $loan)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $loan->employee->employee_name }}</td>
                                                <td>{{ $loan->loanType->loan_type_name }}</td>
                                                <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                                <td>{{ number_format($loan->remaining_amount, 2) }}</td>
                                                <td>
                                                    @if($loan->status == 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($loan->status == 'active')
                                                        <span class="badge badge-success">Active</span>
                                                    @elseif($loan->status == 'completed')
                                                        <span class="badge badge-info">Completed</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ ucfirst($loan->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    @if($loan->status == 'pending')
                                                        <form action="{{ route('loan.destroy', $loan->id) }}" method="POST" style="display:inline" title="Delete Loan">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this loan?')">
                                                                <i class="fe fe-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- simple table -->


                </div> <!-- .row -->
            </div> <!-- .container-fluid -->


            <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="varyModalLabel">New loan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('loan.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control" required>
                                        <option value="">Select employee</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}" data-has-loan="{{ $emp->loans()->where('status', '!=', 'completed')->exists() ? '1' : '0' }}">
                                                {{ $emp->employee_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="loan_type_id">Loan Type <span class="text-danger">*</span></label>
                                    <select name="loan_type_id" id="loan_type_id" class="form-control" required>
                                        <option value="">Select loan type</option>
                                        @foreach ($loanTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->loan_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="loan_amount">Loan Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" name="loan_amount" id="loan_amount" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="remaining_loan">Remaining from Previous Loan</label>
                                    <input type="number" step="0.01" name="remaining_loan" id="remaining_loan" class="form-control" value="0.00" disabled>
                                    <small class="form-text text-muted">This is auto-populated if employee has a previous loan</small>
                                </div>

                                <div class="form-group">
                                    <label for="notes">Reason/Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Enter reason for loan..."></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn mb-2 btn-primary">Save Loan</button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>


            <!-- Edit loan Modal -->
            <div class="modal fade" id="editloanModal" tabindex="-1" role="dialog"
                aria-labelledby="editloanModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editloanModalLabel">Edit loan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="" id="editloanForm">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <input type="text" class="form-control" id="editloanName" name="loan"
                                            required>
                                        <div class="valid-feedback"> Looks good! </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn mb-2 btn-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn mb-2 btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Check for previous loans when employee is selected
                document.getElementById('employee_id').addEventListener('change', function() {
                    const employeeId = this.value;
                    const remainingLoanInput = document.getElementById('remaining_loan');

                    if (employeeId) {
                        // Fetch employee's remaining loan amount via AJAX
                        fetch(`/loan/employee/${employeeId}/remaining`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.has_loan) {
                                    remainingLoanInput.value = parseFloat(data.remaining_amount).toFixed(2);
                                } else {
                                    remainingLoanInput.value = '0.00';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching loan data:', error);
                                remainingLoanInput.value = '0.00';
                            });
                    } else {
                        remainingLoanInput.value = '0.00';
                    }
                });

                document.querySelectorAll('.edit-loan-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const loanId = this.getAttribute('data-loan-id');
                        const loanName = this.getAttribute('data-loan-name');

                        // Set the form's action attribute to the route for updating the loan
                        document.getElementById('editloanForm').setAttribute('action', `/loan/${loanId}`);

                        // Populate the loan name in the modal
                        document.getElementById('editloanName').value = loanName;

                        // Show the modal
                        $('#editloanModal').modal('show');
                    });
                });
            </script>



</x-app-layout>
