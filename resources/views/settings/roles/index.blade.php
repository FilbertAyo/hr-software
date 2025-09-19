<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="roles-tab" data-toggle="tab" href="#roles" role="tab"
                                    aria-controls="roles" aria-selected="true">All Roles</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <a href="{{ route('roles.create') }}" class="btn mb-2 btn-primary btn-sm">
                            New Role<span class="fe fe-plus fe-16 ml-2"></span>
                        </a>
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

                <div class="row">
                    @foreach ($roles as $role)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-none border mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title mb-0">{{ ucfirst($role->name) }}</h5>
                                        @if (Auth::user()->level == 0)
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle p-0 text-muted" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{ route('roles.show', $role) }}">
                                                        <i class="fe fe-eye fe-15 mr-2"></i>View
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('roles.edit', $role) }}">
                                                        <i class="fe fe-edit fe-15 mr-2"></i>Edit
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fe fe-trash fe-15 mr-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Permissions ({{ $role->permissions->count() }})</small>
                                        <div class="mt-2">
                                            @if($role->permissions->count() > 0)
                                                @foreach($role->permissions->take(3) as $permission)
                                                    <span class="badge badge-light mr-1 mb-1">{{ $permission->name }}</span>
                                                @endforeach
                                                @if($role->permissions->count() > 3)
                                                    <span class="badge badge-secondary">+{{ $role->permissions->count() - 3 }} more</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No permissions assigned</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-muted">
                                        <small>
                                            <i class="fe fe-users fe-12 mr-1"></i>
                                            {{ $role->users->count() }} user(s) assigned
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function reloadPage() {
            window.location.reload();
        }
    </script>
</x-app-layout>
