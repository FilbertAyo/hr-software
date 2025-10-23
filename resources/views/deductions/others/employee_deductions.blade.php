<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <h2 class="h3 mb-0 page-title">Employee Other Deductions</h2>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#newEmployeeDeductionModal">
                            New Employee Deduction<span class="fe fe-plus fe-16 ml-2"></span>
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <table class="table table-bordered table-hover datatables" id="employeeDeductionsTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>Deduction Type</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Reason</th>
                                            <th>Document</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($employee_deductions->count() > 0)
                                            @foreach ($employee_deductions as $index => $deduction)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><strong>{{ $deduction->employee->employee_name }}</strong></td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $deduction->deductionType->deduction_type }}</span>
                                                    </td>
                                                    <td><strong>{{ number_format($deduction->amount, 2) }}</strong></td>
                                                    <td>{{ $deduction->deduction_date->format('d M Y') }}</td>
                                                    <td>{{ Str::limit($deduction->reason, 40) ?? 'N/A' }}</td>
                                                    <td class="text-center">
                                                        @if($deduction->document_path)
                                                            <a href="{{ asset('storage/' . $deduction->document_path) }}"
                                                               target="_blank" class="btn btn-sm btn-info">
                                                                <span class="fe fe-file-text fe-12"></span> View
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($deduction->status == 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif($deduction->status == 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif($deduction->status == 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @else
                                                            <span class="badge badge-primary">{{ ucfirst($deduction->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            @if($deduction->status == 'pending')
                                                                <form action="{{ route('other-deductions.deduction.approve', $deduction->id) }}"
                                                                    method="POST" style="display: inline;">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-success"
                                                                            title="Approve">
                                                                        <span class="fe fe-check fe-16"></span>
                                                                    </button>
                                                                </form>

                                                                <form action="{{ route('other-deductions.deduction.reject', $deduction->id) }}"
                                                                    method="POST" style="display: inline;">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                                            title="Reject">
                                                                        <span class="fe fe-x fe-16"></span>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-deduction-btn"
                                                                data-id="{{ $deduction->id }}"
                                                                data-employee-id="{{ $deduction->employee_id }}"
                                                                data-type-id="{{ $deduction->other_deduction_type_id }}"
                                                                data-amount="{{ $deduction->amount }}"
                                                                data-date="{{ $deduction->deduction_date->format('Y-m-d') }}"
                                                                data-reason="{{ $deduction->reason }}"
                                                                data-notes="{{ $deduction->notes }}"
                                                                data-status="{{ $deduction->status }}"
                                                                title="Edit">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('other-deductions.deduction.destroy', $deduction->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this deduction?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                                    <span class="fe fe-trash-2 fe-16"></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center">No employee deductions found</td>
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
    </div>

    <!-- New Employee Deduction Modal -->
    <div class="modal fade" id="newEmployeeDeductionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Employee Deduction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('other-deductions.deduction.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="employee_id" name="employee_id" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->employee_name }} - {{ $employee->employeeID ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="other_deduction_type_id">Deduction Type <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="other_deduction_type_id"
                                            name="other_deduction_type_id" required>
                                        <option value="">Select Deduction Type</option>
                                        @foreach($deduction_types->where('status', true) as $type)
                                            <option value="{{ $type->id }}"
                                                    data-requires-doc="{{ $type->requires_document ? '1' : '0' }}">
                                                {{ $type->deduction_type }}
                                                @if($type->requires_document)
                                                    <small>(Document Required)</small>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                           step="0.01" min="0" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="deduction_date">Deduction Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="deduction_date"
                                           name="deduction_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="2"
                                      placeholder="Reason for this deduction"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="notes">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"
                                      placeholder="Any additional notes or comments"></textarea>
                        </div>

                        <div class="form-group" id="document_upload_section">
                            <label for="document">
                                Upload Document
                                <span class="text-danger" id="document_required_indicator">*</span>
                            </label>
                            <input type="file" class="form-control-file" id="document" name="document"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <small class="form-text text-muted">
                                <i class="fe fe-info"></i> Allowed formats: PDF, JPG, PNG, DOC, DOCX (Max: 5MB)
                            </small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save Deduction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Employee Deduction Modal -->
    <div class="modal fade" id="editEmployeeDeductionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Employee Deduction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editEmployeeDeductionForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_employee_id">Employee <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="edit_employee_id" name="employee_id" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->employee_name }} - {{ $employee->employeeID ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_other_deduction_type_id">Deduction Type <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="edit_other_deduction_type_id"
                                            name="other_deduction_type_id" required>
                                        <option value="">Select Deduction Type</option>
                                        @foreach($deduction_types->where('status', true) as $type)
                                            <option value="{{ $type->id }}"
                                                    data-requires-doc="{{ $type->requires_document ? '1' : '0' }}">
                                                {{ $type->deduction_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="edit_amount" name="amount"
                                           step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_deduction_date">Deduction Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="edit_deduction_date"
                                           name="deduction_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_reason">Reason</label>
                            <textarea class="form-control" id="edit_reason" name="reason" rows="2"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit_notes">Additional Notes</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="2"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit_status">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="processed">Processed</option>
                            </select>
                        </div>

                        <div class="form-group" id="edit_document_upload_section">
                            <label for="edit_document">Upload New Document (Optional)</label>
                            <input type="file" class="form-control-file" id="edit_document" name="document"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <small class="form-text text-muted">
                                Leave empty to keep existing document. Max: 5MB
                            </small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Update Deduction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reloadPage() {
            location.reload();
        }

        // Show/hide document upload based on deduction type selection
        $(document).ready(function() {
            // Initialize Select2 first
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select an option'
            });

            // Function to toggle document section
            function toggleDocumentSection(selectElement, documentSection, documentField, requiredIndicator) {
                const selectedOption = selectElement.find('option:selected');
                const requiresDoc = selectedOption.data('requires-doc');

                console.log('Selected type:', selectedOption.text(), 'Requires doc:', requiresDoc);

                if (requiresDoc == '1') {
                    documentSection.slideDown();
                    if (documentField) documentField.attr('required', true);
                    if (requiredIndicator) requiredIndicator.show();
                } else {
                    documentSection.slideUp();
                    if (documentField) documentField.attr('required', false);
                    if (requiredIndicator) requiredIndicator.hide();
                }
            }

            // For new deduction form - Listen to Select2 change event
            $('#other_deduction_type_id').on('select2:select', function(e) {
                toggleDocumentSection(
                    $(this),
                    $('#document_upload_section'),
                    $('#document'),
                    $('#document_required_indicator')
                );
            });

            // Also listen to regular change event as fallback
            $('#other_deduction_type_id').on('change', function() {
                toggleDocumentSection(
                    $(this),
                    $('#document_upload_section'),
                    $('#document'),
                    $('#document_required_indicator')
                );
            });

            // For edit deduction form
            $('#edit_other_deduction_type_id').on('select2:select', function(e) {
                toggleDocumentSection(
                    $(this),
                    $('#edit_document_upload_section'),
                    null,
                    null
                );
            });

            // Also listen to regular change event as fallback
            $('#edit_other_deduction_type_id').on('change', function() {
                toggleDocumentSection(
                    $(this),
                    $('#edit_document_upload_section'),
                    null,
                    null
                );
            });

            // Initialize DataTable
            $('#employeeDeductionsTable').DataTable({
                "order": [[4, "desc"]]
            });
        });

        // Edit Employee Deduction
        document.querySelectorAll('.edit-deduction-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const employeeId = this.getAttribute('data-employee-id');
                const typeId = this.getAttribute('data-type-id');
                const amount = this.getAttribute('data-amount');
                const date = this.getAttribute('data-date');
                const reason = this.getAttribute('data-reason');
                const notes = this.getAttribute('data-notes');
                const status = this.getAttribute('data-status');

                document.getElementById('editEmployeeDeductionForm').setAttribute('action',
                    `/other-deductions/deduction/${id}`);
                document.getElementById('edit_employee_id').value = employeeId;
                document.getElementById('edit_other_deduction_type_id').value = typeId;
                document.getElementById('edit_amount').value = amount;
                document.getElementById('edit_deduction_date').value = date;
                document.getElementById('edit_reason').value = reason || '';
                document.getElementById('edit_notes').value = notes || '';
                document.getElementById('edit_status').value = status;

                // Trigger change event to show/hide document section
                $('#edit_other_deduction_type_id').trigger('change');

                $('#editEmployeeDeductionModal').modal('show');
            });
        });
    </script>
</x-app-layout>

