<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">View Company</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('company.index') }}" class="btn mb-2 btn-secondary btn-sm">
                            <span class="fe fe-arrow-left fe-16 mr-2"></span>Back to List
                        </a>
                        <a href="{{ route('company.edit', $company->id) }}" class="btn mb-2 btn-primary btn-sm">
                            <span class="fe fe-edit fe-16 mr-2"></span>Edit
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">

                               <!-- Basic Information Row 1 -->
                               <div class="form-row mb-3">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Company Name</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->company_name ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Company Short Name</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->company_short_name ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Contact Person</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->contact_person ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">City</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->city ?: '-' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Checkbox -->
                            <div class="form-row mb-3">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Alias Company Name</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        <span class="badge {{ $company->alias_company_name ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $company->alias_company_name ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Information Row 2 -->
                            <div class="form-row mb-3">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Country</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->country ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Address</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->address ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Phone No.</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->phone_no ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Fax No.</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->fax_no ?: '-' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Information Row 3 -->
                            <div class="form-row mb-3">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Email</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->email ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Website</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        @if($company->website)
                                            <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Tin No.</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->tin_no ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">District No.</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->district_no ?: '-' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Information Row 4 -->
                            <div class="form-row mb-3">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Vat No</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->vat_no ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Start Month</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->start_month ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Start Year</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->start_year ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Leave Accumulation Per Month</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->leave_accumulation_per_month ?: '-' }}
                                    </div>
                                </div>
                            </div>

                            <!-- NSSF and WCF Row -->
                            <div class="form-row mb-3">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">NSSF Control Number</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->nssf_control_number ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">WCF Registration Number</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ $company->wcf_registration_number ?: '-' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">WeekDay Overtime Rate</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ number_format($company->weekday_overtime_rate, 2) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Saturday Overtime Rate</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ number_format($company->saturday_overtime_rate, 2) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Overtime and Rates Row -->
                            <div class="form-row mb-3">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">WeekEnd/Holiday Overtime Rate</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ number_format($company->weekend_holiday_overtime_rate, 2) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">WCF Rate %</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ number_format($company->wcf_rate, 2) }}%
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">SDL %</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ number_format($company->sdl_rate, 2) }}%
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Maximum Leave Accumulated Days</label>
                                    <div class="form-control-plaintext border bg-light p-2 rounded">
                                        {{ number_format($company->advance_rate) }}
                                    </div>
                                </div>

                                <!-- Continue Overtime and Rates Row -->
                                <div class="form-row mb-3">
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Advance Rate %</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            {{ number_format($company->advance_rate, 2) }}%
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">SDL Exempt</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->sdl_exempt ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->sdl_exempt ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">OT Included While Calculation WCF</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->ot_included_wcf ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->ot_included_wcf ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Leave Sold Included While Calculation WCF</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->leave_sold_included_wcf ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->leave_sold_included_wcf ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Leave and Approval Flags -->
                                <div class="form-row mb-3">
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Maximum Leave Accumulated Days</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->max_leave_accumulated_days ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->max_leave_accumulated_days ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Omit Sundays During Leave Transaction</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->omit_sundays_leave ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->omit_sundays_leave ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Omit Holidays During Leave Transaction</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->omit_holidays_leave ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->omit_holidays_leave ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">HOD Approval Required (Web Portal)</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->hod_approval_required ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->hod_approval_required ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employee & Leave Approvals -->
                                <div class="form-row mb-3">
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Approve Employee</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->approve_employee ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->approve_employee ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">ByPass Advance Limit</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->bypass_advance_limit ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->bypass_advance_limit ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Leave Approve</label>
                                        <div class="form-control-plaintext border bg-light p-2 rounded">
                                            <span class="badge {{ $company->leave_approve ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $company->leave_approve ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
