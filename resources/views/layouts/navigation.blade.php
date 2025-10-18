<nav class="topnav navbar navbar-light bg-white border">
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
    </button>

    <div class="d-none d-lg-flex">
        <div class="input-group">
            <div class="input-group-prepend">
                <button type="button" class="btn btn-search pe-1" disabled>
                    <i class="bi bi-building-fill"></i>
                </button>
            </div>
            <input type="text" class="form-control" disabled
                placeholder="{{ optional($selectedCompany)->company_name ?? 'No Company Selected' }}" />
        </div>
    </div>

    <!-- Company Payroll Info -->
    @if($selectedCompany && $currentPayrollPeriod)
        <div class="d-none d-lg-flex ml-3">
            <div class="badge badge-info">
                <i class="fe fe-calendar fe-12 mr-1"></i>
                {{ $currentPayrollPeriod->period_name }}
            </div>
        </div>
    @endif


    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
                <i class="fe fe-sun fe-16"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-shortcut">
                <span class="fe fe-grid fe-16"></span>
            </a>
        </li>
        <li class="nav-item nav-notif">
            <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-notif">
                <span class="fe fe-bell fe-16"></span>
                <span class="dot dot-md bg-danger"></span>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link text-muted" href="{{ route('profile.edit') }}">
                <span class="avatar avatar-sm ">
                    <img src="{{ asset('images/photo.jpeg') }}" alt="..." class="avatar-img rounded-circle">
                </span>
                <div>{{ Auth::user()->name }}</div>
            </a>

        </li>
    </ul>
</nav>


