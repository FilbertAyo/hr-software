<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Section -->
                <div class="row align-items-center mb-4 border-bottom">
                    <div class="col">
                        <h2 class="mb-1">Employee Registration</h2>
                        <p class="text-muted mb-0">Register a new employee in the system</p>

                    </div>
                    <div class="col-auto">
                        <a href="{{ route('employee.index') }}" class="btn btn-outline-secondary">
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
                                            <p class="text-muted">Enter the employee's personal and department information</p>
                                        </div>

                                        @include('employees.partials.personal_and_department')

                                        <div class="step-actions mt-4">
                                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                                Next <i class="fe fe-arrow-right ml-1"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Step 2: Payment & Salary -->
                                    <div class="form-step" id="step2">
                                        <div class="step-header mb-4">
                                            <h4 class="text-primary mb-2">
                                                <i class="fe fe-dollar-sign mr-2"></i>Payment & Salary Details
                                            </h4>
                                            <p class="text-muted">Configure payment method and salary information</p>
                                        </div>

                                        @include('employees.partials.payments')
                                        @include('employees.partials.salary')
                                        @include('employees.partials.nhif_deductions')

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
            </div>
        </div>
    </div>

    <style>
        .progress-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2rem 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background-color: #007bff;
            color: white;
        }

        .step.completed .step-circle {
            background-color: #28a745;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6c757d;
            text-align: center;
        }

        .step.active .step-label {
            color: #007bff;
        }

        .step.completed .step-label {
            color: #28a745;
        }

        .step-line {
            width: 100px;
            height: 2px;
            background-color: #e9ecef;
            margin: 0 1rem;
            margin-top: -20px;
        }

        .step.completed + .step-line {
            background-color: #28a745;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .step-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
        }

        .step-actions {
            display: flex;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-valid {
            border-color: #28a745;
        }

        .invalid-feedback {
            display: block;
        }

        .valid-feedback {
            display: block;
        }

        .card {
            border-radius: 0.5rem;
        }

        .alert {
            border-radius: 0.5rem;
        }
    </style>

    <script>
        let currentStep = 1;
        const totalSteps = 2;

        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    // Hide current step
                    document.getElementById(`step${currentStep}`).classList.remove('active');
                    document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');

                    // Show next step
                    currentStep++;
                    document.getElementById(`step${currentStep}`).classList.add('active');
                    document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');

                    updateProgressIndicator();
                }
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                // Hide current step
                document.getElementById(`step${currentStep}`).classList.remove('active');
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');

                // Show previous step
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.add('active');
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');

                updateProgressIndicator();
            }
        }

        function validateCurrentStep() {
            const currentStepElement = document.getElementById(`step${currentStep}`);
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });

            // Special validation for step 1
            if (currentStep === 1) {
                const email = document.getElementById('email');
                const mobile = document.getElementById('mobile_no');

                if (email.value && !isValidEmail(email.value)) {
                    email.classList.add('is-invalid');
                    isValid = false;
                }

                if (mobile.value && !isValidMobile(mobile.value)) {
                    mobile.classList.add('is-invalid');
                    isValid = false;
                }
            }

            return isValid;
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidMobile(mobile) {
            const mobileRegex = /^[0-9+\-\s()]+$/;
            return mobileRegex.test(mobile) && mobile.length >= 10;
        }

        function updateProgressIndicator() {
            for (let i = 1; i <= totalSteps; i++) {
                const stepElement = document.querySelector(`.step[data-step="${i}"]`);
                if (i < currentStep) {
                    stepElement.classList.add('completed');
                    stepElement.classList.remove('active');
                } else if (i === currentStep) {
                    stepElement.classList.add('active');
                    stepElement.classList.remove('completed');
                } else {
                    stepElement.classList.remove('active', 'completed');
                }
            }
        }

        // Form submission
        document.getElementById('employeeForm').addEventListener('submit', function(e) {
            if (!validateCurrentStep()) {
                e.preventDefault();
                return false;
            }
        });

        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('employeeForm');
            const inputs = form.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid') && this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });
        });
    </script>
</x-app-layout>
