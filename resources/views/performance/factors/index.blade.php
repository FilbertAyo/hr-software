<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Factors</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                                data-target="#createFactorModal">
                            New Factor<span class="fe fe-plus fe-16 ml-2"></span>
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
                                    <a href="{{ route('factors.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-grid"></i> Factors
                                    </a>
                                    <a href="{{ route('sub-factors.index') }}" class="btn btn-outline-primary btn-sm">
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
                                            <th>Factor Name</th>
                                            <th>General Factor</th>
                                            <th>Weight (%)</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($factors->count() > 0)
                                            @foreach ($factors as $index => $factor)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><strong>{{ $factor->factor_name }}</strong></td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ $factor->generalFactor->general_factor_name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">{{ $factor->weight }}%</span>
                                                    </td>
                                                    <td>{{ $factor->description ? Str::limit($factor->description, 50) : 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $factor->status == 'Active' ? 'success' : 'secondary' }}">
                                                            {{ $factor->status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-primary edit-factor-btn"
                                                               data-id="{{ $factor->id }}"
                                                               data-name="{{ $factor->factor_name }}"
                                                               data-general-factor-id="{{ $factor->general_factor_id }}"
                                                               data-description="{{ $factor->description }}"
                                                               data-weight="{{ $factor->weight }}"
                                                               data-status="{{ $factor->status }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('factors.destroy', $factor->id) }}" method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to delete this factor?');">
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
                                                <td colspan="7" class="text-center">No factors found</td>
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
    </div> <!-- .container-fluid -->

    <!-- Create Factor Modal -->
    <div class="modal fade" id="createFactorModal" tabindex="-1" role="dialog" aria-labelledby="createFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFactorModalLabel">New Factor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('factors.store') }}">
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
                                <label for="factor_name">Factor Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="factor_name"
                                       name="factor_name" required>
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
                            <button type="submit" class="btn mb-2 btn-primary">Save Factor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Factor Modal -->
    <div class="modal fade" id="editFactorModal" tabindex="-1" role="dialog" aria-labelledby="editFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFactorModalLabel">Edit Factor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editFactorForm">
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
                                <label for="edit_factor_name">Factor Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_factor_name"
                                       name="factor_name" required>
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
                            <button type="submit" class="btn mb-2 btn-primary">Update Factor</button>
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

        document.querySelectorAll('.edit-factor-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const generalFactorId = this.getAttribute('data-general-factor-id');
                const description = this.getAttribute('data-description');
                const weight = this.getAttribute('data-weight');
                const status = this.getAttribute('data-status');

                // Set the form's action attribute
                document.getElementById('editFactorForm').setAttribute('action', `/factors/${id}`);

                // Populate the form fields
                document.getElementById('edit_factor_name').value = name;
                document.getElementById('edit_general_factor_id').value = generalFactorId;
                document.getElementById('edit_description').value = description || '';
                document.getElementById('edit_weight').value = weight;
                document.getElementById('edit_status').value = status;

                // Show the modal
                $('#editFactorModal').modal('show');
            });
        });
    </script>

</x-app-layout>
