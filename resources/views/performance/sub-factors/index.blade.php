<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Sub Factors</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                                data-target="#createSubFactorModal">
                            New Sub Factor<span class="fe fe-plus fe-16 ml-2"></span>
                        </button>
                    </div>
                </div>

                <!-- Performance Management Navigation -->
                {{-- <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body py-2">
                                <div class="btn-group" role="group" aria-label="Performance Management">
                                    <a href="{{ route('general-factors.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-layers"></i> General Factors
                                    </a>
                                    <a href="{{ route('factors.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-grid"></i> Factors
                                    </a>
                                    <a href="{{ route('sub-factors.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-list"></i> Sub Factors
                                    </a>
                                    <a href="{{ route('rating-scales.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-star"></i> Rating Scales
                                    </a>
                                    <a href="{{ route('evaluations.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-clipboard"></i> Evaluations
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

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
                                            <th>Sub Factor Name</th>
                                            <th>Factor</th>
                                            <th>General Factor</th>
                                            <th>Weight (%)</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($subFactors->count() > 0)
                                            @foreach ($subFactors as $index => $subFactor)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><strong>{{ $subFactor->sub_factor_name }}</strong></td>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            {{ $subFactor->factor->factor_name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ $subFactor->generalFactor->general_factor_name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">{{ $subFactor->weight }}%</span>
                                                    </td>
                                                    <td>{{ $subFactor->description ? Str::limit($subFactor->description, 40) : 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $subFactor->status == 'Active' ? 'success' : 'secondary' }}">
                                                            {{ $subFactor->status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-primary edit-sub-factor-btn"
                                                               data-id="{{ $subFactor->id }}"
                                                               data-name="{{ $subFactor->sub_factor_name }}"
                                                               data-general-factor-id="{{ $subFactor->general_factor_id }}"
                                                               data-factor-id="{{ $subFactor->factor_id }}"
                                                               data-description="{{ $subFactor->description }}"
                                                               data-weight="{{ $subFactor->weight }}"
                                                               data-status="{{ $subFactor->status }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('sub-factors.destroy', $subFactor->id) }}" method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to delete this sub factor?');">
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
                                                <td colspan="8" class="text-center">No sub factors found</td>
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


    <!-- Create Sub Factor Modal -->
    <div class="modal fade" id="createSubFactorModal" tabindex="-1" role="dialog" aria-labelledby="createSubFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubFactorModalLabel">New Sub Factor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('sub-factors.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="general_factor_id">General Factor <span class="text-danger">*</span></label>
                                <select class="form-control" id="general_factor_id" name="general_factor_id" required>
                                    <option value="">Choose General Factor...</option>
                                    @foreach($generalFactors ?? [] as $generalFactor)
                                        <option value="{{ $generalFactor->id }}">{{ $generalFactor->general_factor_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="factor_id">Factor <span class="text-danger">*</span></label>
                                <select class="form-control" id="factor_id" name="factor_id" required>
                                    <option value="">First select General Factor...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="sub_factor_name">Sub Factor Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sub_factor_name"
                                       name="sub_factor_name" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="weight">Weight (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control"
                                       id="weight" name="weight" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="Enter description..."></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save Sub Factor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Sub Factor Modal -->
    <div class="modal fade" id="editSubFactorModal" tabindex="-1" role="dialog" aria-labelledby="editSubFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubFactorModalLabel">Edit Sub Factor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editSubFactorForm">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_general_factor_id">General Factor <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_general_factor_id" name="general_factor_id" required>
                                    <option value="">Choose General Factor...</option>
                                    @foreach($generalFactors ?? [] as $generalFactor)
                                        <option value="{{ $generalFactor->id }}">{{ $generalFactor->general_factor_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_factor_id">Factor <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_factor_id" name="factor_id" required>
                                    <option value="">First select General Factor...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_sub_factor_name">Sub Factor Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_sub_factor_name"
                                       name="sub_factor_name" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_weight">Weight (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control"
                                       id="edit_weight" name="weight" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_description">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"
                                          placeholder="Enter description..."></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Update Sub Factor</button>
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

        // Load factors when general factor is selected (Create Modal)
        document.getElementById('general_factor_id').addEventListener('change', function() {
            const generalFactorId = this.value;
            const factorSelect = document.getElementById('factor_id');

            // Clear existing options
            factorSelect.innerHTML = '<option value="">Loading...</option>';

            if (generalFactorId) {
                fetch(`/api/general-factors/${generalFactorId}/factors`)
                    .then(response => response.json())
                    .then(data => {
                        factorSelect.innerHTML = '<option value="">Choose Factor...</option>';
                        data.forEach(factor => {
                            factorSelect.innerHTML += `<option value="${factor.id}">${factor.factor_name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error loading factors:', error);
                        factorSelect.innerHTML = '<option value="">Error loading factors</option>';
                    });
            } else {
                factorSelect.innerHTML = '<option value="">First select General Factor...</option>';
            }
        });

        // Load factors when general factor is selected (Edit Modal)
        document.getElementById('edit_general_factor_id').addEventListener('change', function() {
            const generalFactorId = this.value;
            const factorSelect = document.getElementById('edit_factor_id');

            // Clear existing options
            factorSelect.innerHTML = '<option value="">Loading...</option>';

            if (generalFactorId) {
                fetch(`/api/general-factors/${generalFactorId}/factors`)
                    .then(response => response.json())
                    .then(data => {
                        factorSelect.innerHTML = '<option value="">Choose Factor...</option>';
                        data.forEach(factor => {
                            factorSelect.innerHTML += `<option value="${factor.id}">${factor.factor_name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error loading factors:', error);
                        factorSelect.innerHTML = '<option value="">Error loading factors</option>';
                    });
            } else {
                factorSelect.innerHTML = '<option value="">First select General Factor...</option>';
            }
        });

        // Edit Sub Factor functionality
        document.querySelectorAll('.edit-sub-factor-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const generalFactorId = this.getAttribute('data-general-factor-id');
                const factorId = this.getAttribute('data-factor-id');
                const description = this.getAttribute('data-description');
                const weight = this.getAttribute('data-weight');
                const status = this.getAttribute('data-status');

                // Set the form's action attribute
                document.getElementById('editSubFactorForm').setAttribute('action', `/sub-factors/${id}`);

                // Populate the form fields
                document.getElementById('edit_sub_factor_name').value = name;
                document.getElementById('edit_general_factor_id').value = generalFactorId;
                document.getElementById('edit_description').value = description || '';
                document.getElementById('edit_weight').value = weight;
                document.getElementById('edit_status').value = status;

                // Load factors for the selected general factor, then select the correct factor
                if (generalFactorId) {
                    const factorSelect = document.getElementById('edit_factor_id');
                    factorSelect.innerHTML = '<option value="">Loading...</option>';

                    fetch(`/api/general-factors/${generalFactorId}/factors`)
                        .then(response => response.json())
                        .then(data => {
                            factorSelect.innerHTML = '<option value="">Choose Factor...</option>';
                            data.forEach(factor => {
                                const selected = factor.id == factorId ? 'selected' : '';
                                factorSelect.innerHTML += `<option value="${factor.id}" ${selected}>${factor.factor_name}</option>`;
                            });
                        })
                        .catch(error => {
                            console.error('Error loading factors:', error);
                            factorSelect.innerHTML = '<option value="">Error loading factors</option>';
                        });
                }

                // Show the modal
                $('#editSubFactorModal').modal('show');
            });
        });
    </script>

</x-app-layout>
