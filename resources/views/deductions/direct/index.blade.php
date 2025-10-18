<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Direct Deduction</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">New Direct Deduction<span
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
                                            <th>Name</th>
                                            <th>Employer %</th>
                                            <th>Employee %</th>
                                            <th>Type</th>
                                            <th>Percentage Of</th>
                                            <th>Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach ($deductions as $index => $deduction)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $deduction->name }}</td>
                                                    <td>{{ $deduction->employer_percent }}</td>
                                                    <td>{{ $deduction->employee_percent }}</td>
                                                    <td>{{ ucfirst($deduction->deduction_type) }}</td>
                                                    <td>{{ ucfirst($deduction->percentage_of) }} Salary</td>

                                                    <td>{{ ucfirst($deduction->status) }}</td>
                                                    <td class="text-right">
                                                        <div
                                                            style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-deduction-btn"
                                                                data-deduction-id="{{ $deduction->id }}"
                                                                data-deduction-name="{{ $deduction->name }}"
                                                                data-employer-percent="{{ $deduction->employer_percent }}"
                                                                data-employee-percent="{{ $deduction->employee_percent }}"
                                                                data-deduction-type="{{ $deduction->deduction_type }}"
                                                                data-percentage-of="{{ $deduction->percentage_of }}"
                                                                data-status="{{ $deduction->status }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>


                                                            <form
                                                                action="{{ route('direct-deduction.destroy', $deduction->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <span class="fe fe-trash-2 fe-16"></span>
                                                                </button>
                                                            </form>
                                                        </div>
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
                            <h5 class="modal-title" id="varyModalLabel">New Direct Deduction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('direct-deduction.store') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Deduction Type</label>
                                        <select class="form-control" name="deduction_type">
                                            <option selected disabled>--Select deduction type--</option>
                                            <option value="normal">Normal</option>
                                            <option value="pension">Pension</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Employer %</label>
                                        <input type="text" class="form-control" name="employer_percent">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Employee %</label>
                                        <input type="text" class="form-control" name="employee_percent">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Percentage of</label>
                                        <select class="form-control" name="percentage_of">
                                            <option selected disabled>--choose percentage of--</option>
                                            <option value="basic">Basic Salary</option>
                                            <option value="gross">Gross Salary</option>
                                        </select>

                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="active" selected>Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="must_include" id="must_include" class="form-check-input" value="1"
                                            {{ old('must_include') ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger" for="must_include">Must Include</label>
                                    </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn mb-2 btn-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn mb-2 btn-primary">Save and Close</button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>


            <!-- Edit deduction Modal -->
            <div class="modal fade" id="editdeductionModal" tabindex="-1" role="dialog"
                aria-labelledby="editdeductionModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editdeductionModalLabel">Edit deduction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="" id="editdeductionForm">
                                @csrf
                                @method('PUT')
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Name</label>
                                        <input type="text" class="form-control" id="editName" name="name"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Deduction Type</label>
                                        <select class="form-control" id="editDeductionType" name="deduction_type">
                                            <option value="normal">Normal</option>
                                            <option value="pension">Pension</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Employer %</label>
                                        <input type="text" class="form-control" id="editEmployerPercent"
                                            name="employer_percent">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Employee %</label>
                                        <input type="text" class="form-control" id="editEmployeePercent"
                                            name="employee_percent">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Percentage of</label>
                                        <select class="form-control" id="editPercentageOf" name="percentage_of">
                                            <option value="basic">Basic Salary</option>
                                            <option value="gross">Gross Salary</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Status</label>
                                        <select class="form-control" id="editStatus" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="must_include" id="must_include" class="form-check-input" value="1"
                                                {{ old('must_include') ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger" for="must_include">Must Include</label>
                                        </div>
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
                document.querySelectorAll('.edit-deduction-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const deductionId = this.getAttribute('data-deduction-id');
                        const deductionName = this.getAttribute('data-deduction-name');
                        const employerPercent = this.getAttribute('data-employer-percent');
                        const employeePercent = this.getAttribute('data-employee-percent');
                        const status = this.getAttribute('data-status');
                        const deductionType = this.getAttribute('data-deduction-type');
                        const percentageOf = this.getAttribute('data-percentage-of');

                        document.getElementById('editdeductionForm').setAttribute('action',
                            `/direct-deduction/${deductionId}`);
                        document.getElementById('editName').value = deductionName;
                        document.getElementById('editEmployerPercent').value = employerPercent;
                        document.getElementById('editEmployeePercent').value = employeePercent;
                        document.getElementById('editStatus').value = status;
                        document.getElementById('editDeductionType').value = deductionType;
                        document.getElementById('editPercentageOf').value = percentageOf;

                        $('#editdeductionModal').modal('show');
                    });
                });
            </script>



</x-app-layout>
