<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-none border">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Edit User: {{ $user->name }}</h4>
                            <a href="{{ route('users.list') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fe fe-arrow-left fe-16 mr-2"></i>Back to Users
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Leave blank to keep current password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave blank to keep current password</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="role_id">Role</label>
                                    <select class="form-control @error('role_id') is-invalid @enderror"
                                        id="role_id" name="role_id" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role_id', in_array($role->id, $userRoles) ? $role->id : '') == $role->id ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="companies">Companies</label>
                                    <select class="form-control" id="companies" name="companies[]" multiple>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ in_array($company->id, old('companies', $userCompanies)) ? 'selected' : '' }}>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple companies</small>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-save fe-16 mr-2"></i>Update User
                                </button>
                                <a href="{{ route('users.list') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- User Information Card -->
                <div class="card shadow-none border mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Current User Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Current Role:</strong>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-secondary">No Role Assigned</span>
                                    @endif
                                </p>
                                <p><strong>Status:</strong>
                                    <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Companies:</strong></p>
                                @if($user->companies->count() > 0)
                                    @foreach($user->companies as $company)
                                        <span class="badge badge-light mr-1 mb-1">{{ $company->company_name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No companies assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
