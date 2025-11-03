<x-app-layout>


    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">All Users</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-sm" onclick="reloadPage()">
                <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
            </button>
            <x-modal-button>
                {{ __('Add User') }}
            </x-modal-button>
        </div>
    </div>



    <div class="row">
        @foreach ($user as $index => $user)
            <div class="col-md-4">
                <div class="card shadow-none border mb-4">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg mt-4">
                            <a href="">
                                <img src="{{ asset('images/photo.jpeg') }}" alt="..."
                                    class="avatar-img rounded-circle">
                            </a>
                        </div>
                        <div class="card-text my-2">
                            <strong class="card-title my-0">{{ $user->name }}</strong>
                            <p class="small text-muted mb-0">{{ $user->phone }} | {{ $user->email }}</p>

                            <!-- Display Role -->
                            <p class="small mb-1">
                                @if ($user->roles->count() > 0)
                                    @foreach ($user->roles as $role)
                                        <span class="badge badge-info text-white">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-secondary">No Role</span>
                                @endif
                            </p>

                            <!-- Display Companies -->
                            @if ($user->companies->count() > 0)
                                <p class="small text-muted mb-0">
                                    <i class="fe fe-briefcase fe-12 mr-1"></i>
                                    @foreach ($user->companies->take(2) as $company)
                                        {{ $company->company_name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                    @if ($user->companies->count() > 2)
                                        <span class="text-primary">+{{ $user->companies->count() - 2 }} more</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto">
                                <small>
                                    <span
                                        class="dot dot-lg {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }} mr-1"></span>
                                    {{ ucfirst($user->status) }}
                                </small>
                            </div>


                            <div class="col-auto">
                                <div class="file-action">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->phone }}', {{ $user->roles->first()->id ?? 'null' }}, [{{ $user->companies->pluck('id')->implode(',') }}], '{{ $user->status }}')">
                                        <i class="fe fe-edit fe-15"></i>
                                    </button>
                                    @if ($user->status === 'active')
                                        <button type="button" class="btn btn-sm btn-outline-danger ml-1"
                                            onclick="deactivateUser({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fe fe-user-x fe-15"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-success ml-1"
                                            onclick="activateUser({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fe fe-user-check fe-15"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </div>
    </div>


    <!-- Create User Modal -->
    <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="varyModalLabel">User Registration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('user.register') }}" validate>
                        @csrf

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role_id">Role</label>
                                <select class="form-control @error('role_id') is-invalid @enderror" id="role_id"
                                    name="role_id" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_status">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="edit_status"
                                    name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Companies (Optional)</label>
                                <div class="form-group">
                                    @foreach ($companies as $company)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="companies[]"
                                                value="{{ $company->id }}" id="company_{{ $company->id }}"
                                                {{ in_array($company->id, old('companies', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="company_{{ $company->id }}">
                                                {{ $company->company_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Select one or more companies</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editUserForm" validate>
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="edit_name" name="name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="edit_email" name="email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_phone">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="edit_phone" name="phone">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_status">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="edit_status"
                                    name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="edit_password" name="password"
                                    placeholder="Leave blank to keep current password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave blank to keep current password</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_role_id">Role</label>
                                <select class="form-control @error('role_id') is-invalid @enderror" id="edit_role_id"
                                    name="role_id" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Companies</label>
                                <div class="form-group">
                                    @foreach ($companies as $company)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="companies[]"
                                                value="{{ $company->id }}" id="edit_company_{{ $company->id }}">
                                            <label class="form-check-label" for="edit_company_{{ $company->id }}">
                                                {{ $company->company_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Select one or more companies</small>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" form="editUserForm" class="btn btn-primary">
                        <i class="fe fe-save fe-16 mr-2"></i>Update User
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function reloadPage() {
            window.location.reload();
        }

        function editUser(userId, name, email, phone, roleId, companyIds, status) {
            // Set form action
            document.getElementById('editUserForm').action = '/users/' + userId;

            // Fill form fields
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone || '';
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_status').value = status || 'active';

            // Set role
            if (roleId) {
                document.getElementById('edit_role_id').value = roleId;
            }

            // Set companies
            // First, uncheck all company checkboxes
            document.querySelectorAll('input[name="companies[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Then check the ones that were selected
            if (companyIds && companyIds.length > 0) {
                companyIds.forEach(companyId => {
                    const checkbox = document.getElementById('edit_company_' + companyId);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }

            // Show modal
            $('#editUserModal').modal('show');
        }

        function deactivateUser(userId, userName) {
            if (confirm('Are you sure you want to deactivate ' + userName + '?')) {
                // Create a form to submit the deactivation
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/users/' + userId + '/deactivate';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function activateUser(userId, userName) {
            if (confirm('Are you sure you want to activate ' + userName + '?')) {
                // Create a form to submit the activation
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/users/' + userId + '/activate';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
