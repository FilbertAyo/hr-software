<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Leave Types</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal">
                            New Leave Type<span class="fe fe-plus fe-16 ml-2"></span>
                        </button>
                    </div>
                </div>

                <div class="row my-2">
                    @include('elements.spinner')
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover datatables" id="dataTable-1">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="15%">Leave Type</th>
                                                <th width="12%">Other Name</th>
                                                <th width="8%">Days</th>
                                                <th width="10%">Monthly Inc.</th>
                                                <th width="10%">Extra Days</th>
                                                <th width="8%">Inc. Value</th>
                                                <th width="8%">Extra Value</th>
                                                <th width="8%">Web Portal</th>
                                                <th width="8%">Status</th>
                                                <th width="8%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($leavetypes as $index => $leavetype)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $leavetype->leave_type_name }}</strong>
                                                    </td>
                                                    <td>{{ $leavetype->other_name ?: '-' }}</td>
                                                    <td>
                                                      {{ $leavetype->no_of_days }}
                                                    </td>
                                                    <td>
                                                        @if($leavetype->no_monthly_increment)
                                                            <span class="badge badge-success">Yes</span>
                                                        @else
                                                            <span class="badge badge-secondary">No</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($leavetype->extra_no_of_days)
                                                            <span class="badge badge-success">Yes</span>
                                                        @else
                                                            <span class="badge badge-secondary">No</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $leavetype->no_of_monthly_increment ?: '0' }}
                                                    </td>
                                                    <td>
                                                       {{ $leavetype->extra_days ?: '0' }}
                                                    </td>
                                                    <td>
                                                        @if($leavetype->show_in_web_portal)
                                                            <span class="badge badge-success">Yes</span>
                                                        @else
                                                            <span class="badge badge-secondary">No</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($leavetype->status == 'Active')
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary edit-leavetype-btn"
                                                                data-leavetype-id="{{ $leavetype->id }}"
                                                                data-leavetype-name="{{ $leavetype->leave_type_name }}"
                                                                data-leavetype-other="{{ $leavetype->other_name }}"
                                                                data-leavetype-days="{{ $leavetype->no_of_days }}"
                                                                data-leavetype-nomincr="{{ $leavetype->no_monthly_increment }}"
                                                                data-leavetype-extra="{{ $leavetype->extra_no_of_days }}"
                                                                data-leavetype-nomonth="{{ $leavetype->no_of_monthly_increment }}"
                                                                data-leavetype-extradays="{{ $leavetype->extra_days }}"
                                                                data-leavetype-web="{{ $leavetype->show_in_web_portal }}"
                                                                data-leavetype-status="{{ $leavetype->status }}"
                                                                title="Edit">
                                                                <i class="fe fe-edit fe-16"></i>
                                                            </button>

                                                            <form action="{{ route('leavetype.destroy', $leavetype->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Are you sure you want to delete this leave type?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                    <i class="fe fe-trash-2 fe-16"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fe fe-inbox fe-24 mb-3"></i>
                                                            <p>No leave types found</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="varyModalLabel">
                            <i class="fe fe-plus-circle mr-2"></i>Create New Leave Type
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('leavetype.store') }}" id="createForm">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="leave_type_name">Leave Type Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="leave_type_name"
                                        id="leave_type_name" placeholder="Enter leave type name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="other_name">Other Name</label>
                                    <input type="text" class="form-control" name="other_name"
                                        id="other_name" placeholder="Enter other name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_of_days">Number of Days</label>
                                    <input type="number" class="form-control" name="no_of_days"
                                        id="no_of_days" placeholder="0" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="no_monthly_increment"
                                            id="no_monthly_increment" value="1">
                                        <label class="custom-control-label" for="no_monthly_increment">
                                            Enable Monthly Increment
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="extra_no_of_days"
                                            id="extra_no_of_days" value="1">
                                        <label class="custom-control-label" for="extra_no_of_days">
                                            Enable Extra Days
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3" id="monthly_increment_field" style="display: none;">
                                    <label for="no_of_monthly_increment">Monthly Increment Value</label>
                                    <input type="number" class="form-control" name="no_of_monthly_increment"
                                        id="no_of_monthly_increment_input" placeholder="0" min="0" step="0.01">
                                </div>
                                <div class="col-md-6 mb-3" id="extra_days_field" style="display: none;">
                                    <label for="extra_days">Extra Days Value</label>
                                    <input type="number" class="form-control" name="extra_days"
                                        id="extra_days_input" placeholder="0" min="0">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="show_in_web_portal"
                                            id="show_in_web_portal" value="1">
                                        <label class="custom-control-label" for="show_in_web_portal">
                                            Show in Web Portal
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fe fe-x mr-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-save mr-2"></i>Save Leave Type
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editleavetypeModal" tabindex="-1" role="dialog"
            aria-labelledby="editleavetypeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="editleavetypeModalLabel">
                            <i class="fe fe-edit mr-2"></i>Edit Leave Type
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="" id="editleavetypeForm">
                            @csrf
                            @method('PUT')

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="editleavetypeName">Leave Type Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editleavetypeName"
                                        name="leave_type_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editleavetypeOtherName">Other Name</label>
                                    <input type="text" class="form-control" id="editleavetypeOtherName"
                                        name="other_name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="editleavetypeDays">Number of Days</label>
                                    <input type="number" class="form-control" id="editleavetypeDays"
                                        name="no_of_days" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editleavetypeStatus">Status</label>
                                    <select class="form-control" id="editleavetypeStatus" name="status">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="no_monthly_increment"
                                            id="editNoMonthlyIncrement" value="1">
                                        <label class="custom-control-label" for="editNoMonthlyIncrement">
                                            Enable Monthly Increment
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="extra_no_of_days"
                                            id="editExtraNoDays" value="1">
                                        <label class="custom-control-label" for="editExtraNoDays">
                                            Enable Extra Days
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3" id="edit_monthly_increment_field" style="display: none;">
                                    <label for="editleavetypeIncrement">Monthly Increment Value</label>
                                    <input type="number" class="form-control" id="editleavetypeIncrement"
                                        name="no_of_monthly_increment" min="0" step="0.01">
                                </div>
                                <div class="col-md-6 mb-3" id="edit_extra_days_field" style="display: none;">
                                    <label for="editleavetypeExtra">Extra Days Value</label>
                                    <input type="number" class="form-control" id="editleavetypeExtra"
                                        name="extra_days" min="0">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="show_in_web_portal"
                                            id="editShowPortal" value="1">
                                        <label class="custom-control-label" for="editShowPortal">
                                            Show in Web Portal
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fe fe-x mr-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fe fe-save mr-2"></i>Update Leave Type
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .modal-header.bg-primary,
        .modal-header.bg-warning {
            border-bottom: none;
        }

        .custom-control-label {
            font-weight: 500;
        }

        .table-responsive {
            border-radius: 0.5rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle checkbox changes for create form
            const monthlyIncrementCheckbox = document.getElementById('no_monthly_increment');
            const extraDaysCheckbox = document.getElementById('extra_no_of_days');
            const monthlyIncrementField = document.getElementById('monthly_increment_field');
            const extraDaysField = document.getElementById('extra_days_field');

            monthlyIncrementCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    monthlyIncrementField.style.display = 'block';
                    document.getElementById('no_of_monthly_increment_input').required = true;
                } else {
                    monthlyIncrementField.style.display = 'none';
                    document.getElementById('no_of_monthly_increment_input').required = false;
                    document.getElementById('no_of_monthly_increment_input').value = '';
                }
            });

            extraDaysCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    extraDaysField.style.display = 'block';
                    document.getElementById('extra_days_input').required = true;
                } else {
                    extraDaysField.style.display = 'none';
                    document.getElementById('extra_days_input').required = false;
                    document.getElementById('extra_days_input').value = '';
                }
            });

            // Handle checkbox changes for edit form
            const editMonthlyIncrementCheckbox = document.getElementById('editNoMonthlyIncrement');
            const editExtraDaysCheckbox = document.getElementById('editExtraNoDays');
            const editMonthlyIncrementField = document.getElementById('edit_monthly_increment_field');
            const editExtraDaysField = document.getElementById('edit_extra_days_field');

            editMonthlyIncrementCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    editMonthlyIncrementField.style.display = 'block';
                } else {
                    editMonthlyIncrementField.style.display = 'none';
                    document.getElementById('editleavetypeIncrement').value = '';
                }
            });

            editExtraDaysCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    editExtraDaysField.style.display = 'block';
                } else {
                    editExtraDaysField.style.display = 'none';
                    document.getElementById('editleavetypeExtra').value = '';
                }
            });

            // Handle edit button clicks
            document.querySelectorAll('.edit-leavetype-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const leavetypeId = this.getAttribute('data-leavetype-id');
                    const leavetypeName = this.getAttribute('data-leavetype-name');
                    const leavetypeOther = this.getAttribute('data-leavetype-other');
                    const leavetypeDays = this.getAttribute('data-leavetype-days');
                    const leavetypeNoMIncr = this.getAttribute('data-leavetype-nomincr');
                    const leavetypeExtra = this.getAttribute('data-leavetype-extra');
                    const leavetypeNoMonth = this.getAttribute('data-leavetype-nomonth');
                    const leavetypeExtraDays = this.getAttribute('data-leavetype-extradays');
                    const leavetypeWeb = this.getAttribute('data-leavetype-web');
                    const leavetypeStatus = this.getAttribute('data-leavetype-status');

                    // Set form action
                    document.getElementById('editleavetypeForm').setAttribute('action', `/leavetype/${leavetypeId}`);

                    // Populate form fields
                    document.getElementById('editleavetypeName').value = leavetypeName || '';
                    document.getElementById('editleavetypeOtherName').value = leavetypeOther || '';
                    document.getElementById('editleavetypeDays').value = leavetypeDays || '';
                    document.getElementById('editleavetypeIncrement').value = leavetypeNoMonth || '';
                    document.getElementById('editleavetypeExtra').value = leavetypeExtraDays || '';
                    document.getElementById('editleavetypeStatus').value = leavetypeStatus || 'Active';

                    // Handle checkboxes
                    const monthlyIncrCheck = document.getElementById('editNoMonthlyIncrement');
                    const extraDaysCheck = document.getElementById('editExtraNoDays');
                    const webPortalCheck = document.getElementById('editShowPortal');

                    monthlyIncrCheck.checked = leavetypeNoMIncr == '1';
                    extraDaysCheck.checked = leavetypeExtra == '1';
                    webPortalCheck.checked = leavetypeWeb == '1';

                    // Show/hide fields based on checkbox state
                    if (monthlyIncrCheck.checked) {
                        editMonthlyIncrementField.style.display = 'block';
                    } else {
                        editMonthlyIncrementField.style.display = 'none';
                    }

                    if (extraDaysCheck.checked) {
                        editExtraDaysField.style.display = 'block';
                    } else {
                        editExtraDaysField.style.display = 'none';
                    }

                    // Show modal
                    $('#editleavetypeModal').modal('show');
                });
            });

            // Reset forms when modals are closed
            $('#varyModal').on('hidden.bs.modal', function() {
                document.getElementById('createForm').reset();
                monthlyIncrementField.style.display = 'none';
                extraDaysField.style.display = 'none';
            });

            $('#editleavetypeModal').on('hidden.bs.modal', function() {
                editMonthlyIncrementField.style.display = 'none';
                editExtraDaysField.style.display = 'none';
            });
        });

        function reloadPage() {
            window.location.reload();
        }
    </script>
</x-app-layout>
