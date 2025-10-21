<aside class="sidebar-left border-right bg-white shadow-none border" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
        <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>
    <nav class="vertnav navbar navbar-light">

        <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('dashboard') }}">

                <img src="{{ asset('images/logoNoBg.png') }}" class="navbar-brand-img" alt=""
                    style="height: 30px">
            </a>
        </div>

        <ul class="navbar-nav flex-fill w-100">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fe fe-home fe-16"></i>
                    <span class="ml-3 item-text">Dashboard</span><span
                        class="badge badge-pill badge-primary">Analytics</span>
                </a>
            </li>
        </ul>

        <p class="text-muted nav-heading mt-4 mb-1">
            <span>Payroll</span>
        </p>

        <ul class="navbar-nav flex-fill w-100">
            <li class="nav-item dropdown">
                <a href="#settings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-settings fe-16"></i>
                    <span class="ml-3 item-text">Settings</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 w-100" id="settings">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('skill.index') }}">
                            <span class="ml-1 item-text">Skills</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('stafflevel.index') }}">
                            <span class="ml-1 item-text">Staff level</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('religion.index') }}">
                            <span class="ml-1 item-text">Religion</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('language.index') }}">
                            <span class="ml-1 item-text">Languages</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('termination.index') }}">
                            <span class="ml-1 item-text">Termination Types</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('holiday.index') }}">
                            <span class="ml-1 item-text">Holiday</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('nationality.index') }}">
                            <span class="ml-1 item-text">Nationality</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('education.index') }}">
                            <span class="ml-1 item-text">Education Level</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('occupation.index') }}">
                            <span class="ml-1 item-text">Job Category</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('job_title.index') }}">
                            <span class="ml-1 item-text">Job title/Qualifications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('bank.index') }}">
                            <span class="ml-1 item-text">Bank</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('payment.index') }}">
                            <span class="ml-1 item-text">payments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('relation.index') }}">
                            <span class="ml-1 item-text">Relationships</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('supervisor.index') }}">
                            <span class="ml-1 item-text">Supervisor type</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('reporting.index') }}">
                            <span class="ml-1 item-text">Reporting Method</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('pay_grade.index') }}">
                            <span class="ml-1 item-text">Pay grade</span>
                        </a>
                    </li>


                </ul>
            </li>
        </ul>


        <ul class="navbar-nav flex-fill w-100">
            <li class="nav-item dropdown">
                <a href="#users" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-users fe-16"></i>
                    <span class="ml-3 item-text">Users</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 w-100" id="users">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('roles.index') }}">
                            <span class="ml-1 item-text">Roles</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('users.index') }}">
                            <span class="ml-1 item-text">All Users</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>


        <ul class="navbar-nav flex-fill w-100">
            <li class="nav-item dropdown">
                <a href="#dashboard" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-archive fe-16"></i>
                    <span class="ml-3 item-text">Company</span><span class="sr-only">(current)</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="dashboard">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('company.index') }}"><span
                                class="ml-1 item-text">Company Setup</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('mainstation.index') }}"><span
                                class="ml-1 item-text">Main stations</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('substation.index') }}"><span
                                class="ml-1 item-text">Sub stations</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('department.index') }}">
                            <span class="ml-1 item-text">Departments</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#ui-elements" data-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle nav-link">
                    <i class="fe fe-box fe-16"></i>
                    <span class="ml-3 item-text">Employee Portal</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="ui-elements">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('employee.index') }}"><span
                                class="ml-1 item-text">Employee List</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <p class="text-muted nav-heading mt-4 mb-1">
            <span>Management</span>
        </p>
        <ul class="navbar-nav flex-fill w-100">

            <li class="nav-item dropdown">
                <a href="#allowances" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-plus-square fe-16"></i>
                    <span class="ml-3 item-text">Allowances/Benefits</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="allowances">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('earngroup.index') }}">
                            <span class="ml-1 item-text">Earning Group</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('allowance.index') }}"><span
                                class="ml-1 item-text">Benefit Names</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('direct.index') }}"><span
                                class="ml-1 item-text">Direct Benefits</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('group-benefits.index') }}"><span
                                class="ml-1 item-text">Group
                                Benefits</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('other-benefits.index') }}"><span
                                class="ml-1 item-text">Other Benefits</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('other-benefit-details.index') }}"><span
                                class="ml-1 item-text">Other Benefit Details</span></a>
                    </li>

                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#tax-deductions" data-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle nav-link">
                    <i class="fe fe-minus-square fe-16"></i>
                    <span class="ml-3 item-text">Tax & Deductions</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="tax-deductions">

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('taxrate.index') }}">
                            <span class="ml-1 item-text">Tax Rates</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('taxtable.index') }}">
                            <span class="ml-1 item-text">Tax Tables/PAYE</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('direct-deduction.index') }}">
                            <span class="ml-1 item-text">Direct Deduction</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link pl-3" href="./form_validation.html"><span class="ml-1 item-text">Other
                                Deductions</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./form_validation.html"><span class="ml-1 item-text">Credit
                                Agency</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./form_validation.html"><span class="ml-1 item-text">Emp
                                Deductions</span></a>
                    </li> --}}


                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#contact" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-pocket fe-16"></i>
                    <span class="ml-3 item-text">Loan Manager</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="contact">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('loantype.index') }}">
                            <span class="ml-1 item-text">Loan Types</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('loan.index') }}">
                            <span class="ml-1 item-text">Loan Requests</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#advance" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-dollar-sign fe-16"></i>
                    <span class="ml-3 item-text">Advance Salary</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="advance">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('advance.index') }}">
                            <span class="ml-1 item-text">Requests</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#attendance" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-user-check fe-16"></i>
                    <span class="ml-3 item-text">Attendance</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="attendance">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('leavetype.index') }}">
                            <span class="ml-1 item-text">Leave Type</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('leaves.index') }}">
                            <span class="ml-1 item-text">Leave Requests</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#evaluations" data-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle nav-link">
                    <i class="fe fe-check-square fe-16"></i>
                    <span class="ml-3 item-text">Performance Evaluation</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="evaluations">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('general-factors.index') }}">
                            <span class="ml-1 item-text">General Factor</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('factors.index') }}">
                            <span class="ml-1 item-text">Factors</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('sub-factors.index') }}">
                            <span class="ml-1 item-text">Sub Factor</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('rating-scales.index') }}">
                            <span class="ml-1 item-text">Rating Scale</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('evaluations.index') }}">
                            <span class="ml-1 item-text">Evaluation</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#forms" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-credit-card fe-16"></i>
                    <span class="ml-3 item-text">Payroll</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="forms">


                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('cal.netpay') }}"><span
                                class="ml-1 item-text">Calculator</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('payperiod.index') }}"><span
                                class="ml-1 item-text">Payroll
                                Period</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('payroll.index') }}"><span
                                class="ml-1 item-text text-success">Process
                                Payroll</span></a>
                    </li>
                </ul>
            </li>



        </ul>
        <p class="text-muted nav-heading mt-4 mb-1">
            <span>Reports</span>
        </p>
        <ul class="navbar-nav flex-fill w-100">
            <li class="nav-item dropdown">
                <a href="#pages" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-file fe-16"></i>
                    <span class="ml-3 item-text">Reports</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 w-100" id="pages">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./page-orders.html">
                            <span class="ml-1 item-text">Detailed Report</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./page-timeline.html">
                            <span class="ml-1 item-text">Summarized Report</span>
                        </a>
                    </li>

                </ul>
            </li>
        </ul>



        <div class="btn-box w-100 mt-5 mb-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a href="{{ route('logout') }}" class="btn mb-2 btn-danger btn-lg btn-block"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fe fe-log-out fe-12 mx-2"></i><span class="small">Log out</span>
                </a>

            </form>
        </div>


    </nav>
</aside>
