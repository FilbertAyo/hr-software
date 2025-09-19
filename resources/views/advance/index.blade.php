<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

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
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">New advance<span
                                class="fe fe-plus fe-16 ml-2"></span></button>
                    </div>
                </div>

                <div class="row my-2">
                    <!-- Small table -->

                    @include('elements.spinner')
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">

                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Employee</th>
                                            <th>Advance Amount</th>
                                            <th>Request Month</th>
                                            <th>Taken</th>
                                            <th>Remarks</th>
                                            <th>Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($advances as $index => $advance)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $advance->employee->employee_name }}</td>
                                                <td>{{ number_format($advance->advance_amount, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($advance->request_date)->format('F Y') }}</td>
                                                <td>{{ $advance->advance_taken ? 'Yes' : 'No' }}</td>
                                                <td>{{ $advance->remarks ?? '-' }}</td>
                                                <td>
                                                    <span class="badge badge-warning">{{ $advance->status }}</span>
                                                </td>
                                                <td class="text-right">
                                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                        <a href="javascript:void(0);"
                                                           class="btn btn-sm btn-primary edit-advance-btn"
                                                           data-advance-id="{{ $advance->id }}"
                                                           data-employee-id="{{ $advance->employee_id }}"
                                                           data-advance-amount="{{ $advance->advance_amount }}"
                                                           data-request-date="{{ $advance->request_date->format('Y-m') }}"
                                                           data-advance-taken="{{ $advance->advance_taken }}"
                                                           data-remarks="{{ $advance->remarks }}">
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
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="text-center">No advance found</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>


                            </div>
                        </div>
                    </div> <!-- simple table -->


                </div> <!-- .row -->
            </div> <!-- .container-fluid -->


            <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" aria-hidden="true">
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
                                    <select name="employee_id" class="form-control" required>
                                        <option value="">-- Select Employee --</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->employee_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Advance Amount</label>
                                    <input type="number" step="0.01" name="advance_amount" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Request Month</label>
                                    <input type="month" name="request_date" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Advance Taken</label>
                                    <select name="advance_taken" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea name="remarks" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn mb-2 btn-primary">Save and Close</button>
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
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->firstName }} {{ $emp->lastName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Advance Amount</label>
                                    <input type="number" step="0.01" id="editAdvanceAmount" name="advance_amount" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Request Month</label>
                                    <input type="month" id="editRequestDate" name="request_date" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Advance Taken</label>
                                    <select name="advance_taken" id="editAdvanceTaken" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea name="remarks" id="editRemarks" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn mb-2 btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <script>
                document.querySelectorAll('.edit-advance-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const advanceId = this.dataset.advanceId;

                        document.getElementById('editadvanceForm').setAttribute('action', `/advance/${advanceId}`);
                        document.getElementById('editEmployeeId').value = this.dataset.employeeId;
                        document.getElementById('editAdvanceAmount').value = this.dataset.advanceAmount;
                        document.getElementById('editRequestDate').value = this.dataset.requestDate;
                        document.getElementById('editAdvanceTaken').value = this.dataset.advanceTaken;
                        document.getElementById('editRemarks').value = this.dataset.remarks;

                        $('#editadvanceModal').modal('show');
                    });
                });
                </script>




</x-app-layout>
