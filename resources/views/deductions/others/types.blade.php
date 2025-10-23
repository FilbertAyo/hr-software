<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <h2 class="h3 mb-0 page-title">Other Deduction Types</h2>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#newDeductionTypeModal">
                            New Deduction Type<span class="fe fe-plus fe-16 ml-2"></span>
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
                    @include('elements.spinner')
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <table class="table table-bordered table-hover datatables" id="deductionTypesTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Deduction Type</th>
                                            <th>Requires Document</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($deduction_types->count() > 0)
                                            @foreach ($deduction_types as $index => $type)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><strong>{{ $type->deduction_type }}</strong></td>
                                                    <td>
                                                        @if($type->requires_document)
                                                            <span class="badge badge-success">
                                                                <i class="fe fe-check"></i> Yes
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary">
                                                                <i class="fe fe-x"></i> No
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $type->description ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($type->status)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-type-btn"
                                                                data-id="{{ $type->id }}"
                                                                data-name="{{ $type->deduction_type }}"
                                                                data-requires-doc="{{ $type->requires_document }}"
                                                                data-description="{{ $type->description }}"
                                                                data-status="{{ $type->status }}"
                                                                title="Edit">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('other-deductions.type.destroy', $type->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this deduction type?');">
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
                                                <td colspan="6" class="text-center">No deduction types found</td>
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

    <!-- New Deduction Type Modal -->
    <div class="modal fade" id="newDeductionTypeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Deduction Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('other-deductions.type.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="deduction_type">Deduction Type Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="deduction_type" name="deduction_type"
                                   placeholder="e.g., Internal, Discipline, Legal, etc." required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="Brief description of this deduction type"></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="requires_document" name="requires_document" value="1">
                            <label class="form-check-label" for="requires_document">
                                <strong>Requires Document Upload</strong>
                                <br><small class="text-muted">Check this if employees must upload supporting documents for this deduction type</small>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save Deduction Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Deduction Type Modal -->
    <div class="modal fade" id="editDeductionTypeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Deduction Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editDeductionTypeForm">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="edit_deduction_type">Deduction Type Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_deduction_type" name="deduction_type" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_description">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="edit_requires_document"
                                   name="requires_document" value="1">
                            <label class="form-check-label" for="edit_requires_document">
                                <strong>Requires Document Upload</strong>
                                <br><small class="text-muted">Check this if employees must upload supporting documents for this deduction type</small>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="edit_status" name="status" value="1">
                            <label class="form-check-label" for="edit_status">
                                Active
                            </label>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Update Deduction Type</button>
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

        // Edit Deduction Type
        document.querySelectorAll('.edit-type-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const requiresDoc = this.getAttribute('data-requires-doc');
                const description = this.getAttribute('data-description');
                const status = this.getAttribute('data-status');

                document.getElementById('editDeductionTypeForm').setAttribute('action',
                    `/other-deductions/type/${id}`);
                document.getElementById('edit_deduction_type').value = name;
                document.getElementById('edit_description').value = description || '';
                document.getElementById('edit_requires_document').checked = requiresDoc == '1';
                document.getElementById('edit_status').checked = status == '1';

                $('#editDeductionTypeModal').modal('show');
            });
        });

        // Initialize DataTable
        $(document).ready(function() {
            $('#deductionTypesTable').DataTable({
                "order": [[1, "asc"]]
            });
        });
    </script>
</x-app-layout>

