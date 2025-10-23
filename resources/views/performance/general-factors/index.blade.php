<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">General Factors</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                                data-target="#createGeneralFactorModal">
                            New General Factor<span class="fe fe-plus fe-16 ml-2"></span>
                        </button>
                    </div>
                </div>

                <div class="row my-2">
                    @include('elements.spinner')
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <!-- table -->
                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>General Factor Name</th>
                                            <th>Description</th>
                                            <th>Factors Count</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($generalFactors->count() > 0)
                                            @foreach ($generalFactors as $index => $generalFactor)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $generalFactor->general_factor_name }}</td>
                                                    <td>{{ $generalFactor->description ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $generalFactor->factors_count }} factors</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $generalFactor->status == 'Active' ? 'success' : 'secondary' }}">
                                                            {{ $generalFactor->status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-primary edit-general-factor-btn"
                                                               data-id="{{ $generalFactor->id }}"
                                                               data-name="{{ $generalFactor->general_factor_name }}"
                                                               data-description="{{ $generalFactor->description }}"
                                                               data-status="{{ $generalFactor->status }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('general-factors.destroy', $generalFactor->id) }}" method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to delete this general factor?');">
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
                                                <td colspan="6" class="text-center">No general factors found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- simple table -->
                </div> <!-- .row -->
            </div> <!-- .col -->
        </div> <!-- .row -->


    <!-- Create General Factor Modal -->
    <div class="modal fade" id="createGeneralFactorModal" tabindex="-1" role="dialog" aria-labelledby="createGeneralFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createGeneralFactorModalLabel">New General Factor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('general-factors.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="general_factor_name">General Factor Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="general_factor_name"
                                       name="general_factor_name" required>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="Enter description..."></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save General Factor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit General Factor Modal -->
    <div class="modal fade" id="editGeneralFactorModal" tabindex="-1" role="dialog" aria-labelledby="editGeneralFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGeneralFactorModalLabel">Edit General Factor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editGeneralFactorForm">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_general_factor_name">General Factor Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_general_factor_name"
                                       name="general_factor_name" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_description">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"
                                          placeholder="Enter description..."></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Update General Factor</button>
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

        document.querySelectorAll('.edit-general-factor-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');
                const status = this.getAttribute('data-status');

                // Set the form's action attribute
                document.getElementById('editGeneralFactorForm').setAttribute('action', `/general-factors/${id}`);

                // Populate the form fields
                document.getElementById('edit_general_factor_name').value = name;
                document.getElementById('edit_description').value = description || '';
                document.getElementById('edit_status').value = status;

                // Show the modal
                $('#editGeneralFactorModal').modal('show');
            });
        });
    </script>

</x-app-layout>
