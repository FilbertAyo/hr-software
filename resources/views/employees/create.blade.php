<x-app-layout>

    @include('employees.partials.helpers')

    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Employee Registration</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <a href="{{ route('employee.index') }}" class="btn btn-secondary btn-sm"
                onclick="clearEmployeeSession(event)">
                <i class="fe fe-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Progress Indicator -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="progress-indicator">
                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Personal & Department</div>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Payment & Salary</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fe fe-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fe fe-alert-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fe fe-alert-circle mr-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Registration Form -->
                    <form method="POST" class="needs-validation" action="{{ route('employee.store') }}"
                        id="employeeForm" enctype="multipart/form-data" novalidate>
                        @csrf

                        <!-- Step 1: Personal & Department Details -->
                        <div class="form-step active" id="step1">
                            <div class="step-header mb-4">
                                <h4 class="text-primary mb-2">
                                    <i class="fe fe-user mr-2"></i>Personal & Department Details
                                </h4>
                                <p class="text-muted">Enter the employee's personal and department
                                    information</p>
                            </div>

                            <div class="card shadow-none border">
                                @include('employees.partials.personal', ['employee' => null])
                                @include('employees.partials.departments', ['employee' => null])
                            </div>


                        </div>



                        <!-- Step 2: Payment & Salary (Final Step) -->
                        <div class="form-step" id="step2">
                            <div class="step-header mb-4">
                                <h4 class="text-primary mb-2">
                                    <i class="fe fe-dollar-sign mr-2"></i>Payment & Salary Details
                                </h4>
                                <p class="text-muted">Configure payment method and salary information</p>
                            </div>

                            <div class="card shadow-none border">
                                @include('employees.partials.payments', ['employee' => null])
                                @include('employees.partials.salary', ['employee' => null])
                            </div>

                            <div class="step-actions mt-4">
                                <button type="button" class="btn btn-outline-secondary mr-2" onclick="prevStep()">
                                    <i class="fe fe-arrow-left mr-1"></i> Previous
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fe fe-save mr-1"></i> Complete Registration
                                </button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
