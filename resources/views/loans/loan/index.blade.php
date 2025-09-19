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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($loans as $index => $loan)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $loan->employee->firstName }} {{ $loan->employee->lastName }}</td>
                                                <td>{{ $loan->loanType->loantype }}</td>
                                                <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                                <td>{{ number_format($loan->remaining_amount, 2) }}</td>
                                                <td>
                                                    <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-sm btn-info">View</a>
                                                    <a href="{{ route('loan.edit', $loan->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    <form action="{{ route('loan.destroy', $loan->id) }}" method="POST" style="display:inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">No loan found</td></tr>
                                        @endforelse
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
                                    <label for="employee_id">Employee</label>
                                    <select name="employee_id" id="employee_id" class="form-control" required>
                                        <option value="">Select employee</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->firstName }} {{ $emp->lastName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="loan_type_id">Loan Type</label>
                                    <select name="loan_type_id" id="loan_type_id" class="form-control" required>
                                        <option value="">Select loan type</option>
                                        @foreach ($loanTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->loantype }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="loan_amount">Loan Amount</label>
                                    <input type="number" step="0.01" name="loan_amount" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="reason">Reason</label>
                                    <textarea name="reason" class="form-control"></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn mb-2 btn-primary">Save and Close</button>
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
