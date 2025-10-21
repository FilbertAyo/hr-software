<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Employee Other Benefits</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">Assign benefit<span
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
                                            <th>Other Benefit</th>
                                            <th>Amount</th>
                                            <th>Benefit Date</th>
                                            <th>Taxable</th>
                                            <th>Status</th>
                                            <th>Apply To</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($details->count() > 0)
                                            @foreach ($details as $index => $detail)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $detail->otherBenefit->other_benefit_name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($detail->amount, 2) }}</td>
                                                    <td>{{ optional($detail->benefit_date)->format('Y-m-d') }}</td>
                                                    <td>{{ $detail->taxable ? 'Yes' : 'No' }}</td>
                                                    <td>{{ ucfirst($detail->status) }}</td>
                                                    <td>
                                                        @php
                                                            $totalEmployees = \App\Models\Employee::count();
                                                            $assignedEmployees = $detail->employees->count();
                                                            $isAppliedToAll = ($assignedEmployees === $totalEmployees && $totalEmployees > 0);
                                                        @endphp
                                                        {{ $isAppliedToAll ? 'All Employees' : $assignedEmployees . ' Employee(s)' }}
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-primary edit-obd-btn"
                                                               data-detail-id="{{ $detail->id }}"
                                                               data-other-benefit-id="{{ $detail->other_benefit_id }}"
                                                               data-amount="{{ $detail->amount }}"
                                                               data-benefit-date="{{ optional($detail->benefit_date)->format('Y-m-d') }}"
                                                               data-taxable="{{ $detail->taxable }}"
                                                               data-status="{{ $detail->status }}"
                                                               data-apply-to-all="{{ $isAppliedToAll ? 1 : 0 }}"
                                                               data-employee-ids='@json($detail->employees->pluck("id")->toArray())'>
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('other-benefit-details.destroy', $detail->id) }}" method="POST"
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
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">No assignments found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="varyModalLabel">Employee Other Benefits Add</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('other-benefit-details.store') }}">
                            @csrf

                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label>Other Benefit Name</label>
                                    <select name="other_benefit_id" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        @foreach($other_benefits as $ob)
                                            <option value="{{ $ob->id }}">{{ $ob->other_benefit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Benefit Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="amount" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Benefit Date</label>
                                    <input type="date" class="form-control" name="benefit_date" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Taxable</label>
                                    <select name="taxable" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Apply to All</label>
                                    <select name="apply_to_all" id="applyToAll" class="form-control">
                                        <option value="1">Yes Apply</option>
                                        <option value="0">Not to All</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row" id="selectEmployeesRow" style="display:none;">
                                <div class="col-md-12 mb-3">
                                    <label>Select Employees</label>
                                    <div class="border rounded p-2" style="max-height: 260px; overflow-y: auto;">
                                        @foreach($employees as $emp)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" id="emp_create_{{ $emp->id }}">
                                                <label class="form-check-label" for="emp_create_{{ $emp->id }}">{{ $emp->employee_name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn mb-2 btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editObdModal" tabindex="-1" role="dialog"
            aria-labelledby="editObdModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editObdModalLabel">Edit Other Benefit Assignment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="" id="editObdForm">
                            @csrf
                            @method('PUT')

                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label>Other Benefit Name</label>
                                    <select name="other_benefit_id" id="editOtherBenefitId" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        @foreach($other_benefits as $ob)
                                            <option value="{{ $ob->id }}">{{ $ob->other_benefit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Benefit Amount</label>
                                    <input type="number" step="0.01" class="form-control" id="editAmount" name="amount" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Benefit Date</label>
                                    <input type="date" class="form-control" id="editBenefitDate" name="benefit_date" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Taxable</label>
                                    <select name="taxable" id="editTaxable" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Status</label>
                                    <select name="status" id="editStatus" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Apply to All</label>
                                    <select name="apply_to_all" id="editApplyToAll" class="form-control">
                                        <option value="1">Yes Apply</option>
                                        <option value="0">Not to All</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3" id="editSelectEmployeesCol" style="display:none;">
                                    <label>Select Employees</label>
                                    <div class="border rounded p-2" style="max-height: 240px; overflow-y: auto;">
                                        @foreach($employees as $emp)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" id="emp_edit_{{ $emp->id }}">
                                                <label class="form-check-label" for="emp_edit_{{ $emp->id }}">{{ $emp->employee_name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
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
        </div>

        <script>
            const applyToAll = document.getElementById('applyToAll');
            const selectEmployeesRow = document.getElementById('selectEmployeesRow');
            if (applyToAll) {
                applyToAll.addEventListener('change', function() {
                    selectEmployeesRow.style.display = this.value === '0' ? 'block' : 'none';
                });
            }

            document.querySelectorAll('.edit-obd-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const detailId = this.getAttribute('data-detail-id');
                    const otherBenefitId = this.getAttribute('data-other-benefit-id');
                    const amount = this.getAttribute('data-amount');
                    const benefitDate = this.getAttribute('data-benefit-date');
                    const taxable = this.getAttribute('data-taxable');
                    const status = this.getAttribute('data-status');
                    const applyToAllVal = this.getAttribute('data-apply-to-all');
                    const employeeIds = JSON.parse(this.getAttribute('data-employee-ids') || '[]');

                    document.getElementById('editObdForm').setAttribute('action', `{{ url('other-benefit-details') }}/${detailId}`);
                    document.getElementById('editOtherBenefitId').value = otherBenefitId;
                    document.getElementById('editAmount').value = amount;
                    document.getElementById('editBenefitDate').value = benefitDate;
                    document.getElementById('editTaxable').value = taxable;
                    document.getElementById('editStatus').value = status;
                    document.getElementById('editApplyToAll').value = applyToAllVal;

                    // Check appropriate employee checkboxes
                    const editCheckboxes = document.querySelectorAll('#editSelectEmployeesCol input[type="checkbox"][name="employee_ids[]"]');
                    editCheckboxes.forEach(cb => {
                        cb.checked = employeeIds.includes(parseInt(cb.value));
                    });

                    const editSelectEmployeesCol = document.getElementById('editSelectEmployeesCol');
                    editSelectEmployeesCol.style.display = applyToAllVal === '0' ? 'block' : 'none';

                    document.getElementById('editApplyToAll').addEventListener('change', function() {
                        editSelectEmployeesCol.style.display = this.value === '0' ? 'block' : 'none';
                    });

                    $('#editObdModal').modal('show');
                });
            });
        </script>
    </div>
</x-app-layout>
