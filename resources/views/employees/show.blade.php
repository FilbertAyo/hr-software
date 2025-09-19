<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <h2 class="mb-0">Employee Details</h2>
                        <p class="text-muted mb-0">{{ $employee->employee_name }}</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('employee.edit', $employee) }}" class="btn btn-primary mr-2">
                            <i class="fe fe-edit"></i> Edit Employee
                        </a>
                        <a href="{{ route('employee.index') }}" class="btn btn-secondary">
                            <i class="fe fe-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Left Column - Employee Info -->
                    <div class="col-md-4">
                        <div class="card shadow-none border">
                            <div class="card-body text-center">
                                @if($employee->photo_path)
                                    <img src="{{ asset('storage/' . $employee->photo_path) }}"
                                         alt="Employee Photo" class="rounded-circle mb-3" width="120" height="120">
                                @else
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3"
                                         style="width: 120px; height: 120px; font-size: 48px;">
                                        {{ strtoupper(substr($employee->employee_name, 0, 2)) }}
                                    </div>
                                @endif

                                <h4 class="mb-1">{{ $employee->employee_name }}</h4>
                                <p class="text-muted mb-2">{{ $employee->department->designation ?? 'No Position' }}</p>
                                <span class="badge badge-{{ $employee->employee_status === 'active' ? 'success' : 'danger' }} mb-3">
                                    {{ ucfirst($employee->employee_status) }}
                                </span>

                                <div class="row text-center border-top pt-3">
                                    <div class="col-6">
                                        <h6 class="text-muted mb-1">Department</h6>
                                        <p class="mb-0">{{ $employee->department ? $employee->department->date_of_joining->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                </div>

                                @if($employee->email || $employee->mobile_no)
                                    <div class="border-top pt-3 mt-3">
                                        @if($employee->email)
                                            <p class="mb-1">
                                                <i class="fe fe-mail mr-2"></i>{{ $employee->email }}
                                            </p>
                                        @endif
                                        @if($employee->mobile_no)
                                            <p class="mb-0">
                                                <i class="fe fe-phone mr-2"></i>{{ $employee->mobile_no }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($employee->salaryDetails)
                            <div class="card shadow-none border mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Salary Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <small class="text-muted">Basic Salary</small>
                                            <h5 class="text-success mb-0">
                                                TZS {{ number_format($employee->salaryDetails->basic_salary, 2) }}
                                            </h5>
                                        </div>
                                        @if($employee->salaryDetails->net_salary)
                                            <div class="col-12">
                                                <small class="text-muted">Net Salary</small>
                                                <h6 class="mb-0">
                                                    TZS {{ number_format($employee->salaryDetails->net_salary, 2) }}
                                                </h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Detailed Information -->
                    <div class="col-md-8">
                        <!-- Personal Information Tab -->
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-3" id="employeeTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal"
                                           role="tab" aria-controls="personal" aria-selected="true">Personal Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="employment-tab" data-toggle="tab" href="#employment"
                                           role="tab" aria-controls="employment" aria-selected="false">Employment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial"
                                           role="tab" aria-controls="financial" aria-selected="false">Financial</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="benefits-tab" data-toggle="tab" href="#benefits"
                                           role="tab" aria-controls="benefits" aria-selected="false">Benefits</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="employeeTabContent">
                                    <!-- Personal Details Tab -->
                                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">Basic Information</h6>
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td class="text-muted" width="40%">Full Name:</td>
                                                        <td>{{ $employee->employee_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Date of Birth:</td>
                                                        <td>{{ $employee->date_of_birth ? $employee->date_of_birth->format('F d, Y') : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Gender:</td>
                                                        <td>{{ ucfirst($employee->gender ?? 'N/A') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Marital Status:</td>
                                                        <td>{{ ucfirst($employee->marital_status ?? 'N/A') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Nationality:</td>
                                                        <td>{{ $employee->nationality ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Religion:</td>
                                                        <td>{{ $employee->religion ?? 'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">Contact Information</h6>
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td class="text-muted" width="40%">Email:</td>
                                                        <td>{{ $employee->email ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Mobile:</td>
                                                        <td>{{ $employee->mobile_no ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Telephone:</td>
                                                        <td>{{ $employee->telephone_no ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Address:</td>
                                                        <td>{{ $employee->address ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">NIDA Card:</td>
                                                        <td>{{ $employee->nida_card_no ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">TIN Number:</td>
                                                        <td>{{ $employee->tin_no ?? 'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Employment Tab -->
                                    <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
                                        @if($employee->department)
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="text-primary mb-3">Employment Details</h6>
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <td class="text-muted" width="25%">Department:</td>
                                                            <td width="25%">{{ $employee->department->department }}</td>
                                                            <td class="text-muted" width="25%">Date of Joining:</td>
                                                            <td>{{ $employee->department->date_of_joining->format('F d, Y') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Designation:</td>
                                                            <td>{{ $employee->department->designation ?? 'N/A' }}</td>
                                                            <td class="text-muted">Staff Level:</td>
                                                            <td>{{ $employee->department->staff_level ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Main Division:</td>
                                                            <td>{{ $employee->department->main_division_branch ?? 'N/A' }}</td>
                                                            <td class="text-muted">Sub Division:</td>
                                                            <td>{{ $employee->department->sub_division ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">WCF No:</td>
                                                            <td>{{ $employee->department->wcf_no ?? 'N/A' }}</td>
                                                            <td class="text-muted">HOD Status:</td>
                                                            <td>
                                                                <span class="badge badge-{{ $employee->department->head_of_department ? 'success' : 'secondary' }}">
                                                                    {{ $employee->department->head_of_department ? 'Yes' : 'No' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    @if($employee->department->job_description)
                                                        <div class="mt-3">
                                                            <h6 class="text-muted">Job Description</h6>
                                                            <p>{{ $employee->department->job_description }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <p class="text-muted">No employment details available</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Financial Tab -->
                                    <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
                                        <div class="row">
                                            <!-- Bank Details -->
                                            @if($employee->bankDetails->count() > 0)
                                                <div class="col-12 mb-4">
                                                    <h6 class="text-primary mb-3">Bank Details</h6>
                                                    @foreach($employee->bankDetails as $bank)
                                                        <div class="card border mb-2">
                                                            <div class="card-body py-2">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-3">
                                                                        <strong>{{ $bank->bank_name }}</strong>
                                                                        @if($bank->is_primary)
                                                                            <span class="badge badge-primary badge-sm ml-1">Primary</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <small class="text-muted">Account:</small><br>
                                                                        {{ $bank->bank_account_no }}
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <small class="text-muted">Branch:</small><br>
                                                                        {{ $bank->branch }}
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <small class="text-muted">Amount:</small><br>
                                                                        TZS {{ number_format($bank->amount, 2) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Salary Details -->
                                            @if($employee->salaryDetails)
                                                <div class="col-12">
                                                    <h6 class="text-primary mb-3">Salary Information</h6>
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <td class="text-muted" width="30%">Basic Salary:</td>
                                                            <td class="text-success font-weight-bold">
                                                                TZS {{ number_format($employee->salaryDetails->basic_salary, 2) }}
                                                            </td>
                                                        </tr>
                                                        @if($employee->salaryDetails->total_payments)
                                                            <tr>
                                                                <td class="text-muted">Total Payments:</td>
                                                                <td>TZS {{ number_format($employee->salaryDetails->total_payments, 2) }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($employee->salaryDetails->net_salary)
                                                            <tr>
                                                                <td class="text-muted">Net Salary:</td>
                                                                <td class="text-success">
                                                                    TZS {{ number_format($employee->salaryDetails->net_salary, 2) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td class="text-muted">Housing Allowance:</td>
                                                            <td>
                                                                <span class="badge badge-{{ $employee->salaryDetails->housing ? 'success' : 'secondary' }}">
                                                                    {{ $employee->salaryDetails->housing ? 'Yes' : 'No' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Benefits Tab -->
                                    <div class="tab-pane fade" id="benefits" role="tabpanel" aria-labelledby="benefits-tab">
                                        <div class="row">
                                            <!-- Pension Details -->
                                            @if($employee->pensionDetails)
                                                <div class="col-md-6 mb-4">
                                                    <h6 class="text-primary mb-3">Pension Details</h6>
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <td class="text-muted" width="50%">Pension Enrolled:</td>
                                                            <td>
                                                                <span class="badge badge-{{ $employee->pensionDetails->pension_details ? 'success' : 'secondary' }}">
                                                                    {{ $employee->pensionDetails->pension_details ? 'Yes' : 'No' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @if($employee->pensionDetails->pension)
                                                            <tr>
                                                                <td class="text-muted">Pension Fund:</td>
                                                                <td>{{ $employee->pensionDetails->pension }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($employee->pensionDetails->employee_pension_no)
                                                            <tr>
                                                                <td class="text-muted">Pension Number:</td>
                                                                <td>{{ $employee->pensionDetails->employee_pension_no }}</td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td class="text-muted">Employer %:</td>
                                                            <td>{{ $employee->pensionDetails->employer_percentage }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Employee %:</td>
                                                            <td>{{ $employee->pensionDetails->employee_percentage }}%</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endif

                                            <!-- NHIF Details -->
                                            @if($employee->nhifDetails)
                                                <div class="col-md-6 mb-4">
                                                    <h6 class="text-primary mb-3">NHIF Details</h6>
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <td class="text-muted" width="50%">NHIF Enrolled:</td>
                                                            <td>
                                                                <span class="badge badge-{{ $employee->nhifDetails->nhif ? 'success' : 'secondary' }}">
                                                                    {{ $employee->nhifDetails->nhif ? 'Yes' : 'No' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Fixed Amount:</td>
                                                            <td>
                                                                <span class="badge badge-{{ $employee->nhifDetails->nhif_fixed_amount ? 'success' : 'secondary' }}">
                                                                    {{ $employee->nhifDetails->nhif_fixed_amount ? 'Yes' : 'No' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @if($employee->nhifDetails->nhif_amount > 0)
                                                            <tr>
                                                                <td class="text-muted">Amount:</td>
                                                                <td>TZS {{ number_format($employee->nhifDetails->nhif_amount, 2) }}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            @endif

                                            <!-- Overtime Details -->
                                            @if($employee->overtimeDetails)
                                                <div class="col-12">
                                                    <h6 class="text-primary mb-3">Overtime Configuration</h6>
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <td class="text-muted" width="25%">Overtime Eligible:</td>
                                                            <td width="25%">
                                                                <span class="badge badge-{{ $employee->overtimeDetails->overtime_given ? 'success' : 'secondary' }}">
                                                                    {{ $employee->overtimeDetails->overtime_given ? 'Yes' : 'No' }}
                                                                </span>
                                                            </td>
                                                            <td class="text-muted" width="25%">Weekday Rate:</td>
                                                            <td>{{ $employee->overtimeDetails->overtime_rate_weekday }}x</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Saturday Rate:</td>
                                                            <td>{{ $employee->overtimeDetails->overtime_rate_saturday }}x</td>
                                                            <td class="text-muted">Weekend/Holiday Rate:</td>
                                                            <td>{{ $employee->overtimeDetails->overtime_rate_weekend_holiday }}x</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .table-borderless td {
            border: none;
            padding: 0.25rem 0;
        }

        .badge-sm {
            font-size: 0.65rem;
        }

        .text-primary {
            color: #007bff !important;
        }

        .border-top {
            border-top: 1px solid #dee2e6 !important;
        }
    </style>
</x-app-layout> 
