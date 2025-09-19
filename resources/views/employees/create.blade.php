<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <h2 class="mb-0">Employee Registration</h2>
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
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true">Personal
                                            Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                                            role="tab" aria-controls="profile" aria-selected="false">Earning and
                                            Deduction</a>
                                    </li>
                                </ul>

                                <form method="POST" class="needs-validation" action="{{ route('employee.store') }}"
                                    novalidate>
                                    @csrf
                                    <div class="tab-content mb-3" id="myTabContent">
                                        <!-- Personal Details Tab -->
                                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                                            aria-labelledby="home-tab">
                                            <div class="accordion w-100" id="accordion1">

                                                @include('employees.partials.personal')

                                                @include('employees.partials.departments')

                                                @include('employees.partials.payments')

                                            </div>
                                        </div>

                                        <!-- Earning and Deduction Tab -->
                                        <div class="tab-pane fade" id="profile" role="tabpanel"
                                            aria-labelledby="profile-tab">
                                            <div class="accordion w-100" id="accordion5">

                                                <div class="card shadow-none border">
                                                    <div class="card-header">

                                                        <strong>Employee Salary Details</strong>
                                                    </div>
                                                    <div>
                                                        <div class="card-body">
                                                            <div class="form-row">
                                                                <!-- Basic Salary -->
                                                                <div class="col-md-3 mb-3">
                                                                    <label for="basic_salary">Basic Salary</label>
                                                                    <input type="number" class="form-control"
                                                                        id="basic_salary" name="basic_salary"
                                                                        value="{{ old('basic_salary') }}" min="0"
                                                                        step="0.01">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>

                                                                <!-- Use Tax Table -->
                                                                {{-- <div class="col-md-3 mb-3">
                                                                    <label for="tax">Use Tax Table *</label>
                                                                    <select class="form-control" id="tax"
                                                                        name="tax" required>
                                                                        <option value="">Select...</option>
                                                                        <option value="yes"
                                                                            {{ old('tax') == 'yes' ? 'selected' : '' }}>
                                                                            Yes</option>
                                                                        <option value="no"
                                                                            {{ old('tax') == 'no' ? 'selected' : '' }}>
                                                                            No</option>
                                                                    </select>

                                                                </div>


                                                                <div class="col-md-3 mb-3">
                                                                    <label for="pension">Pension Fund</label>
                                                                    <input type="text" class="form-control"
                                                                        id="pension" name="pension"
                                                                        value="{{ old('pension') }}">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="pensionNo">Pension No</label>
                                                                    <input type="text" class="form-control"
                                                                        id="pensionNo" name="pensionNo"
                                                                        value="{{ old('pensionNo') }}">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="earningGroup">Earning Group</label>
                                                                    <input type="text" class="form-control"
                                                                        id="earningGroup" name="earningGroup"
                                                                        value="{{ old('earningGroup') }}">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="payGrade">Pay Grade</label>
                                                                    <input type="text" class="form-control"
                                                                        id="payGrade" name="payGrade"
                                                                        value="{{ old('payGrade') }}">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="directDeduction">Select Direct
                                                                        Deductions (e.g., HESLB)</label>
                                                                    <input type="text" class="form-control"
                                                                        id="directDeduction" name="directDeduction"
                                                                        value="{{ old('directDeduction') }}">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="currencyID">Currency ID *</label>
                                                                    <input type="text" class="form-control"
                                                                        id="currencyID" name="currencyID"
                                                                        value="{{ old('currencyID', 'TZS') }}"
                                                                        required>

                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="loan">Allow to Apply Employee Loans
                                                                        *</label>
                                                                    <select class="form-control" id="loan"
                                                                        name="loan" required>
                                                                        <option value="">Select...</option>
                                                                        <option value="yes"
                                                                            {{ old('loan') == 'yes' ? 'selected' : '' }}>
                                                                            Yes</option>
                                                                        <option value="no"
                                                                            {{ old('loan') == 'no' ? 'selected' : '' }}>
                                                                            No</option>
                                                                    </select>

                                                                </div>

                                                                <div class="col-md-3 mb-3">
                                                                    <label for="payPeriod">Pay Period *</label>
                                                                    <select class="form-control" id="payPeriod"
                                                                        name="payPeriod" required>
                                                                        <option value="">Select Pay Period
                                                                        </option>
                                                                        <option value="Monthly"
                                                                            {{ old('payPeriod') == 'Monthly' ? 'selected' : '' }}>
                                                                            Monthly</option>
                                                                        <option value="Bi-Weekly"
                                                                            {{ old('payPeriod') == 'Bi-Weekly' ? 'selected' : '' }}>
                                                                            Bi-Weekly</option>
                                                                        <option value="Weekly"
                                                                            {{ old('payPeriod') == 'Weekly' ? 'selected' : '' }}>
                                                                            Weekly</option>
                                                                    </select>

                                                                </div> --}}

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="col-12">
                                            <button class="btn btn-primary mr-2" type="submit">Save and
                                                Close</button>
                                            <a href="{{ route('employee.index') }}"
                                                class="btn btn-secondary">Cancel</a>
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

    <!-- Custom JavaScript for Form Validation -->
    <script>
        // Bootstrap form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Auto-generate Employee ID based on name
        document.addEventListener('DOMContentLoaded', function() {
            const firstNameInput = document.getElementById('firstName');
            const lastNameInput = document.getElementById('lastName');
            const employeeIDInput = document.getElementById('employeeID');

            function generateEmployeeID() {
                const firstName = firstNameInput.value.trim();
                const lastName = lastNameInput.value.trim();

                if (firstName && lastName && !employeeIDInput.value) {
                    const id = (firstName.substring(0, 3) + lastName.substring(0, 3) +
                        Math.floor(Math.random() * 1000).toString().padStart(3, '0')).toUpperCase();
                    employeeIDInput.value = id;
                }
            }

            firstNameInput.addEventListener('blur', generateEmployeeID);
            lastNameInput.addEventListener('blur', generateEmployeeID);
        });
    </script>

    <style>
        .accordion .card-header a {
            color: #495057;
            text-decoration: none;
        }

        .accordion .card-header a:hover {
            color: #007bff;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .invalid-feedback {
            display: block;
        }

        .was-validated .form-control:valid {
            border-color: #28a745;
        }

        .was-validated .form-control:invalid {
            border-color: #dc3545;
        }
    </style>
</x-app-layout>
