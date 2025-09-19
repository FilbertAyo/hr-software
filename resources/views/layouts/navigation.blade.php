
        <nav class="topnav navbar navbar-light bg-white border">
            <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
                <i class="fe fe-menu navbar-toggler-icon"></i>
              </button>

              {{-- <span>{{ Auth::user()->company->company_name }}</span> --}}

            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
                        <i class="fe fe-sun fe-16"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted my-2" href="./#" data-toggle="modal"
                        data-target=".modal-shortcut">
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
                    <!-- Laravel dropdown integration -->
                    <a class="nav-link text-muted" href="{{ route('profile.edit') }}" >
                        <span class="avatar avatar-sm ">
                            <img src="{{ asset('images/photo.jpeg') }}" alt="..." class="avatar-img rounded-circle">
                        </span>
                        <div>{{ Auth::user()->name }}</div>
                    </a>

                </li>
            </ul>
        </nav>


        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/apps.js') }}"></script>


