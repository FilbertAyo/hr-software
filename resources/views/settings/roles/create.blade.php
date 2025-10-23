<x-app-layout>


                <div class="card shadow-none border">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Create New Role</h4>
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fe fe-arrow-left fe-16 mr-2"></i>Back to Roles
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('roles.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Permissions</label>
                                <div class="card border">
                                    <div class="card-header py-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select-all">
                                            <label class="custom-control-label" for="select-all">
                                                <strong>Select All Permissions</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($permissions->count() > 0)
                                            <div class="row">
                                                @foreach($permissions as $permission)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="custom-control custom-checkbox mb-2">
                                                            <input type="checkbox" class="custom-control-input permission-checkbox"
                                                                id="permission-{{ $permission->id }}"
                                                                name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="permission-{{ $permission->id }}">
                                                                {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">No permissions available. Please run the permissions seeder first.</p>
                                        @endif
                                    </div>
                                </div>
                                @error('permissions')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-save fe-16 mr-2"></i>Create Role
                                </button>
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

            // Handle select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectAllState();
            });

            // Handle individual checkbox changes
            permissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectAllState);
            });

            function updateSelectAllState() {
                const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                const totalCount = permissionCheckboxes.length;

                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
                selectAllCheckbox.checked = checkedCount === totalCount;
            }

            // Initialize state
            updateSelectAllState();
        });
    </script>
</x-app-layout>
