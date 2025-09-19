<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

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
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target=".modal-full">New User<span class="fe fe-plus fe-16 ml-2"></span></button>
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
                                            @if($user->roles->count() > 0)
                                                @foreach($user->roles as $role)
                                                    <span class="badge badge-info text-white">{{ ucfirst($role->name) }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">No Role</span>
                                            @endif
                                        </p>

                                        <!-- Display Companies -->
                                        @if($user->companies->count() > 0)
                                            <p class="small text-muted mb-0">
                                                <i class="fe fe-briefcase fe-12 mr-1"></i>
                                                @foreach($user->companies->take(2) as $company)
                                                    {{ $company->company_name }}@if(!$loop->last), @endif
                                                @endforeach
                                                @if($user->companies->count() > 2)
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
                                                <span class="dot dot-lg {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }} mr-1"></span>
                                                {{ ucfirst($user->status) }}
                                            </small>
                                        </div>

                                        @if (Auth::user()->level == 0)
                                            <div class="col-auto">
                                                <div class="file-action">
                                                    <button type="button"
                                                        class="btn btn-link dropdown-toggle more-vertical p-0 text-muted mx-auto"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu m-2">
                                                        <a class="dropdown-item" href="{{ route('users.edit', $user) }}">
                                                            <i class="fe fe-edit fe-15 mr-4"></i>Edit
                                                        </a>

                                                        <a class="dropdown-item text-primary" href="#"
                                                            onclick="event.preventDefault(); document.getElementById('toggle-status-{{ $user->id }}').submit();">
                                                            <i class="fe fe-user fe-15 mr-4 text-primary"></i>
                                                            {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                        </a>
                                                        <form id="toggle-status-{{ $user->id }}"
                                                            action="{{ route('user.toggleStatus', $user->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                        </form>

                                                        <form action="{{ route('user.destroy', $user->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fe fe-delete fe-15 mr-4 text-danger"></i>Delete
                                                            </button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- Create User Modal -->
        <div class="modal fade modal-full" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
            data-backdrop="static" aria-hidden="true">
            <button aria-label="" type="button" class="close p-3" data-dismiss="modal" aria-hidden="true"
                style="position: absolute; right: 20px; top: 20px; z-index: 1051;">
                <span aria-hidden="true" style="font-size: 3rem;" class="text-danger">×</span>
            </button>

            <div class="modal-dialog bg-white" role="document" style="width: 100%; max-width: 800px;">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="POST" action="{{ url('/register') }}" validate
                            style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                            @csrf

                            <div class="form-row text-center">
                                <div class="col-md-12 mb-4">
                                    <h3>User Registration</h3>
                                </div>
                            </div>

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
                            </div>

                            <div class="form-row">
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
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="role_id">Role</label>
                                    <select class="form-control @error('role_id') is-invalid @enderror"
                                        id="role_id" name="role_id" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="companies">Companies (Optional)</label>
                                    <select class="form-control" id="companies" name="companies[]" multiple>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ in_array($company->id, old('companies', [])) ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple companies</small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="fe fe-user-plus fe-16 mr-2"></i>Register User
                            </button>
                        </form>
                    </div>
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
