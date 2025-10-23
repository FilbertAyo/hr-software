<x-app-layout>

                <div class="card shadow-none border">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Role Details: {{ ucfirst($role->name) }}</h4>
                            <div>
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-sm">
                                    <i class="fe fe-edit fe-16 mr-2"></i>Edit Role
                                </a>
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm ml-2">
                                    <i class="fe fe-arrow-left fe-16 mr-2"></i>Back to Roles
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Role Information</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Role Name:</strong></td>
                                        <td>{{ ucfirst($role->name) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $role->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $role->updated_at->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Users Assigned:</strong></td>
                                        <td>{{ $role->users->count() }} user(s)</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Assigned Users</h5>
                                @if($role->users->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($role->users->take(5) as $user)
                                            <div class="list-group-item px-0 py-2 border-0">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('images/photo.jpeg') }}"
                                                         alt="{{ $user->name }}"
                                                         class="avatar avatar-sm rounded-circle mr-2">
                                                    <div>
                                                        <div class="small font-weight-bold">{{ $user->name }}</div>
                                                        <div class="small text-muted">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($role->users->count() > 5)
                                            <div class="small text-muted px-0 py-1">
                                                +{{ $role->users->count() - 5 }} more users
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted">No users assigned to this role yet.</p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h5>Permissions ({{ $role->permissions->count() }})</h5>
                                @if($role->permissions->count() > 0)
                                    <div class="row">
                                        @foreach($role->permissions as $permission)
                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fe fe-check-circle text-success fe-16 mr-2"></i>
                                                    <span>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fe fe-alert-triangle fe-16 mr-2"></i>
                                        This role has no permissions assigned. Users with this role will have limited access.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
