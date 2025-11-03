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
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($generalFactors->count() > 0)
                                            @foreach ($generalFactors as $index => $generalFactor)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $generalFactor->factor_name }}</td>
                                                    <td>{{ $generalFactor->description ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $generalFactor->factors_count }} factors</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-info view-general-factor-btn"
                                                               title="View"
                                                               data-id="{{ $generalFactor->id }}"
                                                               data-name="{{ $generalFactor->factor_name }}">
                                                                <span class="fe fe-eye fe-16"></span>
                                                            </a>
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-primary edit-general-factor-btn"
                                                               data-id="{{ $generalFactor->id }}"
                                                               data-name="{{ $generalFactor->factor_name }}">
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
                                       name="factor_name" required>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-row mb-2">
                            <div class="col-12 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Factors</h6>
                                <button type="button" class="btn btn-sm btn-success" id="add-factor-row">
                                    <i class="fe fe-plus"></i> Add Factor
                                </button>
                            </div>
                        </div>
                        <div id="factors-container">
                            <div class="factor-row" data-index="0">
                                <div class="form-row">
                                    <div class="col-md-11 mb-3">
                                        <label>Factor Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="factors[0][factor_name]" placeholder="e.g., Communication" />
                                    </div>
                                    <div class="col-md-1 mb-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-factor-row" disabled>
                                            <i class="fe fe-trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                             <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
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
                                       name="factor_name" required>
                            </div>
                        </div>


                        <hr>
                        <div class="form-row mb-2">
                            <div class="col-12 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Factors</h6>
                                <button type="button" class="btn btn-sm btn-success" id="edit-add-factor-row">
                                    <i class="fe fe-plus"></i> Add Factor
                                </button>
                            </div>
                        </div>
                        <div id="edit-factors-container">
                            <!-- Loading spinner -->
                            <div class="text-center py-4" id="edit-factors-loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="text-muted mt-2">Loading factors...</p>
                            </div>
                        </div>

                        <div class="modal-footer">
                             <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
                            <button type="submit" class="btn mb-2 btn-primary">Update General Factor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View General Factor Modal -->
    <div class="modal fade" id="viewGeneralFactorModal" tabindex="-1" role="dialog" aria-labelledby="viewGeneralFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewGeneralFactorModalLabel">General Factor Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong id="view_gf_name"></strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Factor Name</th>
                                </tr>
                            </thead>
                            <tbody id="view_factors_tbody">
                                <!-- Loading spinner -->
                                <tr id="view-factors-loading">
                                    <td colspan="2" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="text-muted mt-2 mb-0">Loading factors...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                     <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
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

                // Set the form's action attribute
                document.getElementById('editGeneralFactorForm').setAttribute('action', `/general-factors/${id}`);

                // Populate the form fields
                document.getElementById('edit_general_factor_name').value = name;

                // Load existing factors for edit
                const editContainer = document.getElementById('edit-factors-container');
                // Show loading spinner
                editContainer.innerHTML = `
                    <div class="text-center py-4" id="edit-factors-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="text-muted mt-2">Loading factors...</p>
                    </div>
                `;

                fetch(`/api/general-factors/${id}/factors`)
                    .then(r => r.json())
                    .then(data => {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach((f, idx) => {
                                html += factorEditRow(idx, f);
                            });
                            window.editFactorIndex = data.length;
                        } else {
                            html = factorEditRow(0, null);
                            window.editFactorIndex = 1;
                        }
                        editContainer.innerHTML = html;
                        updateEditRemoveButtons();
                    })
                    .catch(() => {
                        editContainer.innerHTML = '<div class="alert alert-danger">Failed to load factors. Please try again.</div>';
                    });

                // Show the modal
                $('#editGeneralFactorModal').modal('show');
            });
        });

        // Dynamic Factors add/remove in Create Modal
        let factorIndex = 1;
        const container = document.getElementById('factors-container');
        const addBtn = document.getElementById('add-factor-row');

        if (addBtn) {
            addBtn.addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'factor-row';
                row.setAttribute('data-index', factorIndex);
                row.innerHTML = `
                    <div class="form-row">
                        <div class="col-md-11 mb-3">
                            <label>Factor Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="factors[${factorIndex}][factor_name]" placeholder="e.g., Teamwork" />
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-factor-row">
                                <i class="fe fe-trash-2"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(row);
                factorIndex++;
                updateRemoveButtons();
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-factor-row')) {
                const row = e.target.closest('.factor-row');
                row && row.remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('#factors-container .factor-row');
            rows.forEach((r, idx) => {
                const btn = r.querySelector('.remove-factor-row');
                if (btn) btn.disabled = rows.length === 1;
            });
        }

        // Edit modal dynamic rows
        window.editFactorIndex = 0;
        const editAddBtn = document.getElementById('edit-add-factor-row');
        if (editAddBtn) {
            editAddBtn.addEventListener('click', function() {
                const c = document.getElementById('edit-factors-container');
                const row = document.createElement('div');
                row.className = 'factor-row';
                row.innerHTML = factorEditRow(window.editFactorIndex, null);
                c.appendChild(row);
                window.editFactorIndex++;
                updateEditRemoveButtons();
            });
        }

        document.addEventListener('click', function(e) {
            // remove existing/new factor in edit modal
            if (e.target.closest('.edit-remove-factor-row')) {
                const row = e.target.closest('.factor-row');
                if (!row) return;
                const idInput = row.querySelector('input[name$="[id]"]');
                if (idInput && idInput.value) {
                    // mark for deletion
                    let del = row.querySelector('input[name$="[_delete]"]');
                    if (!del) {
                        del = document.createElement('input');
                        del.type = 'hidden';
                        del.name = idInput.name.replace('[id]', '[_delete]');
                        row.appendChild(del);
                    }
                    del.value = '1';
                    row.style.opacity = 0.5;
                    row.style.pointerEvents = 'none';
                } else {
                    // remove brand new row entirely
                    row.remove();
                }
                updateEditRemoveButtons();
            }

            if (e.target.closest('.view-general-factor-btn')) {
                const btn = e.target.closest('.view-general-factor-btn');
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                document.getElementById('view_gf_name').textContent = name;

                const tbody = document.getElementById('view_factors_tbody');
                // Show loading spinner
                tbody.innerHTML = `
                    <tr id="view-factors-loading">
                        <td colspan="2" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading factors...</p>
                        </td>
                    </tr>
                `;

                fetch(`/api/general-factors/${id}/factors`)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.length) {
                            tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">No factors found</td></tr>';
                            return;
                        }
                        tbody.innerHTML = data.map((f, i) => `
                            <tr>
                                <td>${i+1}</td>
                                <td>${f.factor_name ?? ''}</td>
                            </tr>
                        `).join('');
                    })
                    .catch(() => {
                        tbody.innerHTML = '<tr><td colspan="2" class="text-center"><div class="alert alert-danger mb-0">Failed to load factors. Please try again.</div></td></tr>';
                    });

                $('#viewGeneralFactorModal').modal('show');
            }
        });

        function factorEditRow(idx, f) {
            const idField = f && f.id ? `<input type="hidden" name="factors[${idx}][id]" value="${f.id}">` : '';
            const nameVal = f && f.factor_name ? f.factor_name : '';
            return `
                <div class="factor-row border rounded p-2 mb-2">
                    ${idField}
                    <div class="form-row">
                        <div class="col-md-11 mb-3">
                            <label>Factor Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="factors[${idx}][factor_name]" value="${nameVal}">
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm edit-remove-factor-row">
                                <i class="fe fe-trash-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function updateEditRemoveButtons() {
            const rows = document.querySelectorAll('#edit-factors-container .factor-row');
            const activeRows = Array.from(rows).filter(r => {
                const delInput = r.querySelector('input[name$="[_delete]"]');
                return !delInput || delInput.value !== '1';
            });

            // Don't disable any delete buttons in edit modal
            rows.forEach(r => {
                const btn = r.querySelector('.edit-remove-factor-row');
                if (btn) {
                    // Only disable if it's marked for deletion
                    const delInput = r.querySelector('input[name$="[_delete]"]');
                    btn.disabled = delInput && delInput.value === '1';
                }
            });
        }
    </script>

</x-app-layout>
