<nav class="navbar navbar-light bg-white border mb-4">
    <button type="button" class="navbar-toggler text-muted mt-2 px-3 mr-3 collapseSidebar">
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


        <li class="nav-item dropdown">

               <a class="nav-link text-muted d-flex align-items-center" href="{{ route('profile.edit') }}">
                <span class="avatar avatar-sm m-1"
                    style="width: 32px; height: 32px; overflow: hidden; display: inline-block;">
                    <img src="{{ asset('images/photo.jpeg') }}" alt="..."
                        class="avatar-img rounded-circle"
                        style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;">
                </span>
                <span class="fw-bold">{{ Auth::user()->name }}</span>
            </a>

        </li>
    </ul>
</nav>


