<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Company</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('company.index') }}" class="btn mb-2 btn-secondary btn-sm">
                            <span class="fe fe-arrow-left fe-16 mr-2"></span>Back to List
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <form method="POST" action="{{ route('company.store') }}">
                                    @csrf

                                    <!-- Basic Information Row 1 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="company_name"
                                                   value="{{ old('company_name') }}" required>
                                            @error('company_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label for="company_short_name">Company Short Name</label>
                                            <input type="text" class="form-control" name="company_short_name"
                                                   value="{{ old('company_short_name') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="contact_person">Contact Person</label>
                                            <input type="text" class="form-control" name="contact_person"
                                                   value="{{ old('contact_person') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" name="city"
                                                   value="{{ old('city') }}">
                                        </div>
                                    </div>

                                    <!-- Checkbox -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="alias_company_name" id="alias_company_name"
                                                       value="1" {{ old('alias_company_name') ? 'checked' : '' }}>
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
                                                   value="{{ old('country') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" name="address"
                                                   value="{{ old('address') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="phone_no">Phone No.</label>
                                            <input type="text" class="form-control" name="phone_no"
                                                   value="{{ old('phone_no') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fax_no">Fax No.</label>
                                            <input type="text" class="form-control" name="fax_no"
                                                   value="{{ old('fax_no') }}">
                                        </div>
                                    </div>

                                    <!-- Basic Information Row 3 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                   value="{{ old('email') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="website">Website</label>
                                            <input type="url" class="form-control" name="website"
                                                   value="{{ old('website') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tin_no">Tin No.</label>
                                            <input type="text" class="form-control" name="tin_no"
                                                   value="{{ old('tin_no') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="district_no">District No.</label>
                                            <input type="text" class="form-control" name="district_no"
                                                   value="{{ old('district_no') }}">
                                        </div>
                                    </div>

                                    <!-- Basic Information Row 4 -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="vat_no">Vat No</label>
                                            <input type="text" class="form-control" name="vat_no"
                                                   value="{{ old('vat_no') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="start_month">Start Month <small class="text-muted">(First Payroll Period)</small></label>
                                            <select class="form-control" name="start_month" id="start_month">
                                                <option value="">Select Month</option>
                                                <option value="January" {{ old('start_month') == 'January' ? 'selected' : '' }}>January</option>
                                                <option value="February" {{ old('start_month') == 'February' ? 'selected' : '' }}>February</option>
                                                <option value="March" {{ old('start_month') == 'March' ? 'selected' : '' }}>March</option>
                                                <option value="April" {{ old('start_month') == 'April' ? 'selected' : '' }}>April</option>
                                                <option value="May" {{ old('start_month') == 'May' ? 'selected' : '' }}>May</option>
                                                <option value="June" {{ old('start_month') == 'June' ? 'selected' : '' }}>June</option>
                                                <option value="July" {{ old('start_month') == 'July' ? 'selected' : '' }}>July</option>
                                                <option value="August" {{ old('start_month') == 'August' ? 'selected' : '' }}>August</option>
                                                <option value="September" {{ old('start_month') == 'September' ? 'selected' : '' }}>September</option>
                                                <option value="October" {{ old('start_month') == 'October' ? 'selected' : '' }}>October</option>
                                                <option value="November" {{ old('start_month') == 'November' ? 'selected' : '' }}>November</option>
                                                <option value="December" {{ old('start_month') == 'December' ? 'selected' : '' }}>December</option>
                                            </select>
                                            @error('start_month')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label for="start_year">Start Year <small class="text-muted">(First Payroll Period)</small></label>
                                            <input type="number" class="form-control" name="start_year" id="start_year"
                                                   min="1900" max="2099" value="{{ old('start_year') }}">
                                            @error('start_year')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label for="leave_accumulation_per_month">Leave Accumulation Per Month</label>
                                            <input type="number" class="form-control" name="leave_accumulation_per_month"
                                                   step="0.01" value="{{ old('leave_accumulation_per_month') }}">
                                        </div>
                                    </div>

                                    <!-- Payroll Period Information -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <h6><i class="fe fe-info"></i> Payroll Period Information</h6>
                                                <p class="mb-0">
                                                    <strong>If you provide Start Month and Start Year:</strong><br>
                                                    • The first payroll period will be created automatically<br>
                                                    • Future payroll periods will be created sequentially (next month)<br>
                                                    • Previous periods will be closed when creating new ones
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NSSF and WCF Row -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="nssf_control_number">NSSF Control Number</label>
                                            <input type="text" class="form-control" name="nssf_control_number"
                                                   value="{{ old('nssf_control_number') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="wcf_registration_number">WCF Registration Number</label>
                                            <input type="text" class="form-control" name="wcf_registration_number"
                                                   value="{{ old('wcf_registration_number') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="weekday_overtime_rate">WeekDay Overtime Rate</label>
                                            <input type="number" class="form-control" name="weekday_overtime_rate"
                                                   step="0.01" value="{{ old('weekday_overtime_rate', '0.00') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="saturday_overtime_rate">Saturday Overtime Rate</label>
                                            <input type="number" class="form-control" name="saturday_overtime_rate"
                                                   step="0.01" value="{{ old('saturday_overtime_rate', '0.00') }}">
                                        </div>
                                    </div>

                                    <!-- Overtime and Rates Row -->
                                    <div class="form-row mb-3">
                                        <div class="col-md-3">
                                            <label for="weekend_holiday_overtime_rate">WeekEnd/Holiday Overtime Rate</label>
                                            <input type="number" class="form-control" name="weekend_holiday_overtime_rate"
                                                   step="0.01" value="{{ old('weekend_holiday_overtime_rate', '0.00') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="wcf_rate">WCF Rate %</label>
                                            <input type="number" class="form-control" name="wcf_rate"
                                                   step="0.01" value="{{ old('wcf_rate', '0.00') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="sdl_rate">SDL %</label>
                                            <input type="number" class="form-control" name="sdl_rate"
                                                   step="0.01" value="{{ old('sdl_rate', '0.00') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="max_leave_accumulated_days" id="max_leave_accumulated_days"
                                                       value="1" {{ old('max_leave_accumulated_days') ? 'checked' : '' }}>
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
                                                       value="1" {{ old('ot_included_wcf') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="ot_included_wcf">
                                                    OT Included While Calculation WCF
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="sdl_exempt" id="sdl_exempt"
                                                       value="1" {{ old('sdl_exempt') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sdl_exempt">
                                                    SDL Exempt
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="omit_sundays_leave" id="omit_sundays_leave"
                                                       value="1" {{ old('omit_sundays_leave') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="omit_sundays_leave">
                                                    Omit Sundays During Leave Transaction
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="omit_holidays_leave" id="omit_holidays_leave"
                                                       value="1" {{ old('omit_holidays_leave') ? 'checked' : '' }}>
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
                                                       value="1" {{ old('leave_sold_included_wcf') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="leave_sold_included_wcf">
                                                    Leave Sold Included While Calculation WCF
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="hod_approval_required" id="hod_approval_required"
                                                       value="1" {{ old('hod_approval_required') ? 'checked' : '' }}>
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
                                                   step="0.01" value="{{ old('advance_rate', '0.00') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="approve_employee" id="approve_employee"
                                                       value="1" {{ old('approve_employee') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="approve_employee">
                                                    Approve Employee
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="bypass_advance_limit" id="bypass_advance_limit"
                                                       value="1" {{ old('bypass_advance_limit') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="bypass_advance_limit">
                                                    ByPass Advance Limit
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="leave_approve" id="leave_approve"
                                                       value="1" {{ old('leave_approve') ? 'checked' : '' }}>
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
                                            <button type="submit" class="btn btn-primary">Save Company</button>
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
        // Show preview of payroll period that will be created
        function updatePayrollPreview() {
            const startMonth = document.getElementById('start_month').value;
            const startYear = document.getElementById('start_year').value;
            const previewDiv = document.getElementById('payroll-preview');

            if (startMonth && startYear) {
                if (!previewDiv) {
                    // Create preview div if it doesn't exist
                    const preview = document.createElement('div');
                    preview.id = 'payroll-preview';
                    preview.className = 'alert alert-success mt-2';
                    preview.innerHTML = '<i class="fe fe-calendar"></i> <strong>Payroll Period Preview:</strong> <span id="preview-text"></span>';
                    document.getElementById('start_year').parentNode.parentNode.appendChild(preview);
                }

                document.getElementById('preview-text').textContent = `${startMonth} ${startYear} will be created automatically`;
                document.getElementById('payroll-preview').style.display = 'block';
            } else if (previewDiv) {
                previewDiv.style.display = 'none';
            }
        }

        // Add event listeners
        document.getElementById('start_month').addEventListener('change', updatePayrollPreview);
        document.getElementById('start_year').addEventListener('input', updatePayrollPreview);

        // Initial call
        updatePayrollPreview();
    </script>
</x-app-layout>
