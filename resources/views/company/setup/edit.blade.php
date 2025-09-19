<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Edit Company</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('company.index') }}" class="btn mb-2 btn-secondary btn-sm">
                            <span class="fe fe-arrow-left fe-16 mr-2"></span>Back to List
                        </a>
                        <a href="{{ route('company.show', $company->id) }}" class="btn mb-2 btn-info btn-sm">
                            <span class="fe fe-eye fe-16 mr-2"></span>View
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <form method="POST" action="{{ route('company.update', $company->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Basic Information Row 1 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="company_name"
                                                   value="{{ old('company_name', $company->company_name) }}" required>
                                            @error('company_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label for="company_short_name">Company Short Name</label>
                                            <input type="text" class="form-control" name="company_short_name"
                                                   value="{{ old('company_short_name', $company->company_short_name) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="contact_person">Contact Person</label>
                                            <input type="text" class="form-control" name="contact_person"
                                                   value="{{ old('contact_person', $company->contact_person) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" name="city"
                                                   value="{{ old('city', $company->city) }}">
                                        </div>
                                    </div>

                                    <!-- Checkbox -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="alias_company_name" id="alias_company_name"
                                                       value="1" {{ old('alias_company_name', $company->alias_company_name) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="alias_company_name">
                                                    Alias Company Name
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Basic Information Row 2 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" name="country"
                                                   value="{{ old('country', $company->country) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" name="address"
                                                   value="{{ old('address', $company->address) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="phone_no">Phone No.</label>
                                            <input type="text" class="form-control" name="phone_no"
                                                   value="{{ old('phone_no', $company->phone_no) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fax_no">Fax No.</label>
                                            <input type="text" class="form-control" name="fax_no"
                                                   value="{{ old('fax_no', $company->fax_no) }}">
                                        </div>
                                    </div>

                                    <!-- Basic Information Row 3 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                   value="{{ old('email', $company->email) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="website">Website</label>
                                            <input type="url" class="form-control" name="website"
                                                   value="{{ old('website', $company->website) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tin_no">Tin No.</label>
                                            <input type="text" class="form-control" name="tin_no"
                                                   value="{{ old('tin_no', $company->tin_no) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="district_no">District No.</label>
                                            <input type="text" class="form-control" name="district_no"
                                                   value="{{ old('district_no', $company->district_no) }}">
                                        </div>
                                    </div>

                                    <!-- Basic Information Row 4 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="vat_no">Vat No</label>
                                            <input type="text" class="form-control" name="vat_no"
                                                   value="{{ old('vat_no', $company->vat_no) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="start_month">Start Month</label>
                                            <select class="form-control" name="start_month">
                                                <option value="">Select Month</option>
                                                @php
                                                    $months = ['January', 'February', 'March', 'April', 'May', 'June',
                                                              'July', 'August', 'September', 'October', 'November', 'December'];
                                                @endphp
                                                @foreach($months as $month)
                                                    <option value="{{ $month }}"
                                                        {{ old('start_month', $company->start_month) == $month ? 'selected' : '' }}>
                                                        {{ $month }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="start_year">Start Year</label>
                                            <input type="number" class="form-control" name="start_year"
                                                   min="1900" max="2099" value="{{ old('start_year', $company->start_year) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="leave_accumulation_per_month">Leave Accumulation Per Month</label>
                                            <input type="number" class="form-control" name="leave_accumulation_per_month"
                                                   step="0.01" value="{{ old('leave_accumulation_per_month', $company->leave_accumulation_per_month) }}">
                                        </div>
                                    </div>

                                    <!-- NSSF and WCF Row -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="nssf_control_number">NSSF Control Number</label>
                                            <input type="text" class="form-control" name="nssf_control_number"
                                                   value="{{ old('nssf_control_number', $company->nssf_control_number) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="wcf_registration_number">WCF Registration Number</label>
                                            <input type="text" class="form-control" name="wcf_registration_number"
                                                   value="{{ old('wcf_registration_number', $company->wcf_registration_number) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="weekday_overtime_rate">WeekDay Overtime Rate</label>
                                            <input type="number" class="form-control" name="weekday_overtime_rate"
                                                   step="0.01" value="{{ old('weekday_overtime_rate', $company->weekday_overtime_rate) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="saturday_overtime_rate">Saturday Overtime Rate</label>
                                            <input type="number" class="form-control" name="saturday_overtime_rate"
                                                   step="0.01" value="{{ old('saturday_overtime_rate', $company->saturday_overtime_rate) }}">
                                        </div>
                                    </div>

                                    <!-- Overtime and Rates Row -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="weekend_holiday_overtime_rate">WeekEnd/Holiday Overtime Rate</label>
                                            <input type="number" class="form-control" name="weekend_holiday_overtime_rate"
                                                   step="0.01" value="{{ old('weekend_holiday_overtime_rate', $company->weekend_holiday_overtime_rate) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="wcf_rate">WCF Rate %</label>
                                            <input type="number" class="form-control" name="wcf_rate"
                                                   step="0.01" value="{{ old('wcf_rate', $company->wcf_rate) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="sdl_rate">SDL %</label>
                                            <input type="number" class="form-control" name="sdl_rate"
                                                   step="0.01" value="{{ old('sdl_rate', $company->sdl_rate) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="max_leave_accumulated_days" id="max_leave_accumulated_days"
                                                       value="1" {{ old('max_leave_accumulated_days', $company->max_leave_accumulated_days) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="max_leave_accumulated_days">
                                                    Maximum Leave Accumulated Days
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Checkboxes Row 1 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="ot_included_wcf" id="ot_included_wcf"
                                                       value="1" {{ old('ot_included_wcf', $company->ot_included_wcf) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="ot_included_wcf">
                                                    OT Included While Calculation WCF
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="sdl_exempt" id="sdl_exempt"
                                                       value="1" {{ old('sdl_exempt', $company->sdl_exempt) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sdl_exempt">
                                                    SDL Exempt
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="omit_sundays_leave" id="omit_sundays_leave"
                                                       value="1" {{ old('omit_sundays_leave', $company->omit_sundays_leave) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="omit_sundays_leave">
                                                    Omit Sundays During Leave Transaction
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="omit_holidays_leave" id="omit_holidays_leave"
                                                       value="1" {{ old('omit_holidays_leave', $company->omit_holidays_leave) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="omit_holidays_leave">
                                                    Omit Holidays During Leave Transaction
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Checkboxes Row 2 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="leave_sold_included_wcf" id="leave_sold_included_wcf"
                                                       value="1" {{ old('leave_sold_included_wcf', $company->leave_sold_included_wcf) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="leave_sold_included_wcf">
                                                    Leave Sold Included While Calculation WCF
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="hod_approval_required" id="hod_approval_required"
                                                       value="1" {{ old('hod_approval_required', $company->hod_approval_required) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="hod_approval_required">
                                                    HOD Approval Required(Web Portal)
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Advance Rate Row -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="advance_rate">Advance Rate %</label>
                                            <input type="number" class="form-control" name="advance_rate"
                                                   step="0.01" value="{{ old('advance_rate', $company->advance_rate) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="approve_employee" id="approve_employee"
                                                       value="1" {{ old('approve_employee', $company->approve_employee) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="approve_employee">
                                                    Approve Employee
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="bypass_advance_limit" id="bypass_advance_limit"
                                                       value="1" {{ old('bypass_advance_limit', $company->bypass_advance_limit) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="bypass_advance_limit">
                                                    ByPass Advance Limit
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="leave_approve" id="leave_approve"
                                                       value="1" {{ old('leave_approve', $company->leave_approve) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="leave_approve">
                                                    Leave Approve
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="form-row">
                                        <div class="col-md-12 text-right">
                                            <a href="{{ route('company.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Update Company</button>
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
</x-app-layout>
