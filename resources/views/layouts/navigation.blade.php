<nav class="topnav navbar navbar-light bg-white border mb-3">
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
    </button>

    <div class="d-none d-lg-flex align-items-center">
        <div class="d-flex align-items-center bg-light px-3 py-2 rounded border" style="min-width: 200px; max-width: 400px;">
            <i class="bi bi-building-fill text-primary mr-2"></i>
            <span class="text-dark font-weight-medium text-truncate"
                  title="{{ optional($selectedCompany)->company_name ?? 'No Company Selected' }}"
                  style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ optional($selectedCompany)->company_name ?? 'No Company Selected' }}
            </span>
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


