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
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <!-- table -->
                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Factor Name</th>
                                            <th>Sub Factors</th>
                                            <th>General Factor</th>
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
                                                        <span class="badge badge-info">{{ $factor->sub_factors_count }} sub-factors</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            {{ $factor->generalFactor->factor_name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-info view-factor-btn"
                                                               title="View"
                                                               data-id="{{ $factor->id }}"
                                                               data-name="{{ $factor->factor_name }}">
                                                                <span class="fe fe-eye fe-16"></span>
                                                            </a>
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-primary edit-factor-btn"
                                                               data-id="{{ $factor->id }}"
                                                               data-name="{{ $factor->factor_name }}"
                                                               data-general-factor-id="{{ $factor->general_factor_id }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('factors.destroy', $factor->id) }}" method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to delete this factor and all its sub-factors?');">
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
                                                <td colspan="5" class="text-center">No factors found</td>
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


    <!-- Create Sub Factors Modal -->
    <div class="modal fade" id="createSubFactorModal" tabindex="-1" role="dialog" aria-labelledby="createSubFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubFactorModalLabel">Add Sub Factors to Factor</h5>
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
                                        <option value="{{ $generalFactor->id }}">{{ $generalFactor->factor_name }}</option>
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

                        <hr>
                        <div class="form-row mb-2">
                            <div class="col-12 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Sub Factors</h6>
                                <button type="button" class="btn btn-sm btn-success" id="add-sub-factor-row">
                                    <i class="fe fe-plus"></i> Add Sub Factor
                                </button>
                            </div>
                        </div>
                        <div id="sub-factors-container">
                            <div class="sub-factor-row" data-index="0">
                                <div class="form-row">
                                    <div class="col-md-11 mb-3">
                                        <label>Sub Factor Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sub_factors[0][sub_factor_name]" placeholder="e.g., Verbal Communication" />
                                    </div>
                                    <div class="col-md-1 mb-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-sub-factor-row" disabled>
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
                            <button type="submit" class="btn mb-2 btn-primary">Save Sub Factors</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Factor Sub Factors Modal -->
    <div class="modal fade" id="editFactorModal" tabindex="-1" role="dialog" aria-labelledby="editFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFactorModalLabel">Manage Sub Factors</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editFactorForm" onsubmit="console.log('Form submitting...', new FormData(this))">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_factor_id" name="factor_id">

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label>Factor: <strong id="edit_factor_name_display"></strong></label>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <hr>
                        <div class="form-row mb-2">
                            <div class="col-12 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Sub Factors</h6>
                                <button type="button" class="btn btn-sm btn-success" id="edit-add-sub-factor-row">
                                    <i class="fe fe-plus"></i> Add Sub Factor
                                </button>
                            </div>
                        </div>
                        <div id="edit-sub-factors-container">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="text-muted mt-2">Loading sub-factors...</p>
                            </div>
                        </div>

                        <div class="modal-footer">
                             <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
                            <button type="submit" class="btn mb-2 btn-primary">Update Sub Factors</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Factor Sub Factors Modal -->
    <div class="modal fade" id="viewFactorModal" tabindex="-1" role="dialog" aria-labelledby="viewFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFactorModalLabel">Factor Sub Factors</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong id="view_factor_name"></strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Sub Factor Name</th>
                                </tr>
                            </thead>
                            <tbody id="view_sub_factors_tbody">
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="text-muted mt-2 mb-0">Loading sub-factors...</p>
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

        // Dynamic Sub Factors add/remove in Create Modal
        let subFactorIndex = 1;
        const subContainer = document.getElementById('sub-factors-container');
        const addSubBtn = document.getElementById('add-sub-factor-row');

        if (addSubBtn) {
            addSubBtn.addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'sub-factor-row';
                row.setAttribute('data-index', subFactorIndex);
                row.innerHTML = `
                    <div class="form-row">
                        <div class="col-md-11 mb-3">
                            <label>Sub Factor Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sub_factors[${subFactorIndex}][sub_factor_name]" placeholder="e.g., Written Communication" required />
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-sub-factor-row">
                                <i class="fe fe-trash-2"></i>
                            </button>
                        </div>
                    </div>
                `;
                subContainer.appendChild(row);
                subFactorIndex++;
                updateRemoveSubFactorButtons();
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-sub-factor-row')) {
                const row = e.target.closest('.sub-factor-row');
                row && row.remove();
                updateRemoveSubFactorButtons();
            }
        });

        function updateRemoveSubFactorButtons() {
            const rows = document.querySelectorAll('#sub-factors-container .sub-factor-row');
            rows.forEach((r, idx) => {
                const btn = r.querySelector('.remove-sub-factor-row');
                if (btn) btn.disabled = rows.length === 1;
            });
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

        // View Factor Sub Factors
        document.addEventListener('click', function(e) {
            if (e.target.closest('.view-factor-btn')) {
                const btn = e.target.closest('.view-factor-btn');
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                document.getElementById('view_factor_name').textContent = name;

                const tbody = document.getElementById('view_sub_factors_tbody');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="2" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading sub-factors...</p>
                        </td>
                    </tr>
                `;

                fetch(`/api/factors/${id}/sub-factors`)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.length) {
                            tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">No sub-factors found</td></tr>';
                            return;
                        }
                        tbody.innerHTML = data.map((sf, i) => `
                            <tr>
                                <td>${i+1}</td>
                                <td>${sf.sub_factor_name ?? ''}</td>
                            </tr>
                        `).join('');
                    })
                    .catch(() => {
                        tbody.innerHTML = '<tr><td colspan="2" class="text-center"><div class="alert alert-danger mb-0">Failed to load sub-factors.</div></td></tr>';
                    });

                $('#viewFactorModal').modal('show');
            }

            // Edit Factor Sub Factors
            if (e.target.closest('.edit-factor-btn')) {
                const btn = e.target.closest('.edit-factor-btn');
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');

                document.getElementById('edit_factor_id').value = id;
                document.getElementById('edit_factor_name_display').textContent = name;
                document.getElementById('editFactorForm').setAttribute('action', `/factors/${id}`);

                const editContainer = document.getElementById('edit-sub-factors-container');
                editContainer.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="text-muted mt-2">Loading sub-factors...</p>
                    </div>
                `;

                fetch(`/api/factors/${id}/sub-factors`)
                    .then(r => r.json())
                    .then(data => {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach((sf, idx) => {
                                html += subFactorEditRow(idx, sf);
                            });
                            window.editSubFactorIndex = data.length;
                        } else {
                            html = subFactorEditRow(0, null);
                            window.editSubFactorIndex = 1;
                        }
                        editContainer.innerHTML = html;
                        updateEditRemoveButtons();
                    })
                    .catch(() => {
                        editContainer.innerHTML = '<div class="alert alert-danger">Failed to load sub-factors.</div>';
                    });

                $('#editFactorModal').modal('show');
            }
        });

        // Edit modal dynamic rows
        window.editSubFactorIndex = 0;
        const editAddSubBtn = document.getElementById('edit-add-sub-factor-row');
        if (editAddSubBtn) {
            editAddSubBtn.addEventListener('click', function() {
                const c = document.getElementById('edit-sub-factors-container');
                const row = document.createElement('div');
                row.className = 'sub-factor-row';
                row.innerHTML = subFactorEditRow(window.editSubFactorIndex, null);
                c.appendChild(row);
                window.editSubFactorIndex++;
                updateEditRemoveButtons();
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-remove-sub-factor-row')) {
                const row = e.target.closest('.sub-factor-row');
                if (!row) return;
                const idInput = row.querySelector('input[name$="[id]"]');
                if (idInput && idInput.value) {
                    let del = row.querySelector('input[name$="[_delete]"]');
                    if (!del) {
                        del = document.createElement('input');
                        del.type = 'hidden';
                        del.name = idInput.name.replace('[id]', '[_delete]');
                        row.appendChild(del);
                    }
                    del.value = '1';
                    // Remove required attribute from inputs in deleted row
                    const inputs = row.querySelectorAll('input[required]');
                    inputs.forEach(inp => inp.removeAttribute('required'));
                    row.style.opacity = 0.5;
                    row.style.pointerEvents = 'none';
                } else {
                    row.remove();
                }
                updateEditRemoveButtons();
            }
        });

        function subFactorEditRow(idx, sf) {
            const idField = sf && sf.id ? `<input type="hidden" name="sub_factors[${idx}][id]" value="${sf.id}">` : '';
            const nameVal = sf && sf.sub_factor_name ? sf.sub_factor_name : '';
            return `
                <div class="sub-factor-row border rounded p-2 mb-2">
                    ${idField}
                    <div class="form-row">
                        <div class="col-md-11 mb-3">
                            <label>Sub Factor Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sub_factors[${idx}][sub_factor_name]" value="${nameVal}" required>
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm edit-remove-sub-factor-row">
                                <i class="fe fe-trash-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function updateEditRemoveButtons() {
            const rows = document.querySelectorAll('#edit-sub-factors-container .sub-factor-row');
            rows.forEach(r => {
                const btn = r.querySelector('.edit-remove-sub-factor-row');
                if (btn) {
                    const delInput = r.querySelector('input[name$="[_delete]"]');
                    btn.disabled = delInput && delInput.value === '1';
                }
            });
        }
    </script>

</x-app-layout>
