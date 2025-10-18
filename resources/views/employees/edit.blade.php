<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $employee->registration_step == 'personal_saved' || $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? 'active' : '' }}"
                                   id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Personal Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? 'active' : '' }}"
                                   id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false">Earning and Deduction</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row my-2">
                    @include('elements.spinner')
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $employee->registration_step == 'personal_saved' || $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? 'active' : '' }}"
                                           id="home-tab" data-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true">
                                            <i class="fas fa-user"></i> Personal Details
                                            <span class="badge badge-success ml-1" id="step1-badge"
                                                  style="{{ $employee->registration_step == 'personal_saved' || $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? 'display: inline;' : 'display: none;' }}">✓</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? 'active' : '' }}"
                                           id="profile-tab" data-toggle="tab" href="#profile"
                                            role="tab" aria-controls="profile" aria-selected="false">
                                            <i class="fas fa-money-bill-wave"></i> Earning and Deduction
                                            <span class="badge badge-success ml-1" id="step2-badge"
                                                  style="{{ $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? 'display: inline;' : 'display: none;' }}">✓</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Step 1: Personal Details Form -->
                                <form method="POST" class="needs-validation {{ $employee->registration_step == 'personal_saved' || $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? '' : 'd-none' }}"
                                      action="{{ route('employee.update', $employee) }}" novalidate id="personalForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="step" value="personal">

                                    <div class="tab-content mb-3" id="myTabContent">
                                        <!-- Personal Details Tab -->
                                        <div class="tab-pane fade {{ $employee->registration_step == 'personal_saved' || $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? 'show active' : '' }}"
                                             id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="accordion w-100" id="accordion1">

                                                @include('employees.partials.personal')

                                                @include('employees.partials.departments')

                                                @include('employees.partials.payments')

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-12">
                                            <button class="btn btn-primary mr-2" type="submit" id="savePersonalBtn">
                                                <i class="fas fa-save"></i> Save Step 1
                                            </button>
                                            <a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>

                                <!-- Step 2: Salary Details Form -->
                                <form method="POST" class="needs-validation {{ $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? '' : 'd-none' }}"
                                      action="{{ route('employee.update', $employee) }}" novalidate id="salaryForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="step" value="salary">

                                    <div class="tab-content mb-3" id="myTabContent2">
                                        <div class="tab-pane fade {{ $employee->registration_step == 'salary_saved' || $employee->registration_step == 'completed' ? 'show active' : '' }}"
                                             id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                            <div class="accordion w-100" id="accordion2">
                                                @include('employees.partials.salary')
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-12">
                                            <button class="btn btn-primary mr-2" type="submit" id="saveSalaryBtn">
                                                <i class="fas fa-save"></i> Save Step 2
                                            </button>
                                            @if($employee->registration_step == 'salary_saved')
                                                <button class="btn btn-success mr-2" type="button" id="completeRegistrationBtn">
                                                    <i class="fas fa-check"></i> Complete Registration
                                                </button>
                                            @endif
                                            <a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const personalForm = document.getElementById('personalForm');
            const salaryForm = document.getElementById('salaryForm');
            const personalTab = document.getElementById('home-tab');
            const salaryTab = document.getElementById('profile-tab');
            const personalTabPane = document.getElementById('home');
            const salaryTabPane = document.getElementById('profile');
            const completeRegistrationBtn = document.getElementById('completeRegistrationBtn');

            // Handle personal form submission
            personalForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                const saveBtn = document.getElementById('savePersonalBtn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;

                // Submit form
                fetch(personalForm.action, {
                    method: 'POST',
                    body: new FormData(personalForm),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Check if response contains success message
                    if (html.includes('Step 1 updated successfully')) {
                        // Show success message
                        showAlert('success', 'Step 1 updated successfully! You can now proceed to Step 2.');

                        // Show checkmark for step 1
                        document.getElementById('step1-badge').style.display = 'inline';

                        // Switch to salary form
                        personalForm.classList.add('d-none');
                        salaryForm.classList.remove('d-none');

                        // Update tab states
                        personalTab.classList.remove('active');
                        salaryTab.classList.add('active');
                        personalTabPane.classList.remove('show', 'active');
                        salaryTabPane.classList.add('show', 'active');
                    } else {
                        // Show error message
                        showAlert('danger', 'Error updating personal details. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Error updating personal details. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
            });

            // Handle salary form submission
            salaryForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                const saveBtn = document.getElementById('saveSalaryBtn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;

                // Submit form
                fetch(salaryForm.action, {
                    method: 'POST',
                    body: new FormData(salaryForm),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.text())
                .then(html => {
                    if (html.includes('Step 2 updated successfully')) {
                        showAlert('success', 'Step 2 updated successfully! You can now complete the registration.');
                        document.getElementById('step2-badge').style.display = 'inline';
                        if (completeRegistrationBtn) {
                            completeRegistrationBtn.style.display = 'inline-block';
                        }
                    } else {
                        showAlert('danger', 'Error updating salary details. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Error updating salary details. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
            });

            // Handle complete registration
            if (completeRegistrationBtn) {
                completeRegistrationBtn.addEventListener('click', function() {
                    // Show loading state
                    const originalText = completeRegistrationBtn.innerHTML;
                    completeRegistrationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Completing...';
                    completeRegistrationBtn.disabled = true;

                    // Submit completion
                    fetch('{{ route("employee.store") }}', {
                        method: 'POST',
                        body: new FormData(Object.assign(document.createElement('form'), {
                            innerHTML: `
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="step" value="complete">
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            `
                        })),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        if (html.includes('Employee registration completed successfully')) {
                            showAlert('success', 'Employee registration completed successfully!');
                            setTimeout(() => {
                                window.location.href = '{{ route("employee.index") }}';
                            }, 2000);
                        } else {
                            showAlert('danger', 'Error completing registration. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', 'Error completing registration. Please try again.');
                    })
                    .finally(() => {
                        // Reset button state
                        completeRegistrationBtn.innerHTML = originalText;
                        completeRegistrationBtn.disabled = false;
                    });
                });
            }

            // Function to show alerts
            function showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                `;

                // Insert at the top of the card body
                const cardBody = document.querySelector('.card-body');
                cardBody.insertBefore(alertDiv, cardBody.firstChild);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        });
    </script>

</x-app-layout>
