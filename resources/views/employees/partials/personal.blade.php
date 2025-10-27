{{-- employees/partials/personal.blade.php with session support --}}

<div class="card-header">
    <strong>Personal & Department Details</strong>
</div>
<div class="card-body">
    <div class="form-row">
        <!-- Full Name -->
        <div class="col-md-3 mb-3">
            <label for="employee_name">Full Name *</label>
            <input type="text" class="form-control"
                id="employee_name" name="employee_name"
                value="{{ getFormValue('employee_name', $employee->employee_name ?? '') }}" required>
        </div>

        <!-- Employee ID -->
        <div class="col-md-3 mb-3">
            <label for="employeeID">Employee ID</label>
            <input type="text" class="form-control"
                id="employeeID" name="employeeID"
                value="{{ getFormValue('employeeID', $employee->employeeID ?? '') }}">
        </div>

        <!-- Biometric ID -->
        <div class="col-md-3 mb-3">
            <label for="biometric_id">Biometric ID</label>
            <input type="text" class="form-control"
                id="biometricID" name="biometricID"
                value="{{ getFormValue('biometricID', $employee->biometricID ?? '') }}">
        </div>

        <!-- Date of Birth -->
        <div class="col-md-3 mb-3">
            <label for="date_of_birth">Date of Birth *</label>
            <input type="date" class="form-control"
                id="date_of_birth" name="date_of_birth"
                value="{{ getFormValue('date_of_birth', $employee->date_of_birth ?? '') }}" required>
        </div>

        <!-- Mobile No -->
        <div class="col-md-3 mb-3">
            <label for="mobile_no">Mobile No</label>
            <input type="text" class="form-control"
                id="mobile_no" name="mobile_no"
                value="{{ getFormValue('mobile_no', $employee->mobile_no ?? '') }}">
        </div>

        <!-- Email -->
        <div class="col-md-3 mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control"
                id="email" name="email"
                value="{{ getFormValue('email', $employee->email ?? '') }}">
        </div>

        <!-- TIN No -->
        <div class="col-md-3 mb-3">
            <label for="tin_no">TIN No (9 digits)</label>
            <input type="text" maxlength="9"
                class="form-control" id="tin_no"
                name="tin_no" value="{{ getFormValue('tin_no', $employee->tin_no ?? '') }}">
        </div>

        <!-- Gender -->
        <div class="col-md-3 mb-3">
            <label for="gender">Gender *</label>
            <select class="form-control" id="gender"
                name="gender" required>
                <option value="">Select Gender</option>
                <option value="male" {{ isSelected('gender', 'male') ? 'selected' : '' }}>
                    Male</option>
                <option value="female" {{ isSelected('gender', 'female') ? 'selected' : '' }}>
                    Female</option>
            </select>
        </div>

        <!-- Marital Status -->
        <div class="col-md-3 mb-3">
            <label for="marital_status">Marital Status *</label>
            <select class="form-control" id="marital_status"
                name="marital_status" required>
                <option value="">Select Marital Status</option>
                <option value="single" {{ isSelected('marital_status', 'single') ? 'selected' : '' }}>
                    Single</option>
                <option value="married" {{ isSelected('marital_status', 'married') ? 'selected' : '' }}>
                    Married</option>
                <option value="divorced" {{ isSelected('marital_status', 'divorced') ? 'selected' : '' }}>
                    Divorced</option>
                <option value="widowed" {{ isSelected('marital_status', 'widowed') ? 'selected' : '' }}>
                    Widowed</option>
            </select>
        </div>

        <!-- Nationality -->
        <div class="col-md-3 mb-3">
            <label for="nationality">Nationality</label>
            <select class="form-control" id="nationality"
                name="nationality_id">
                <option value="">Select Nationality</option>
                @foreach ($nationalities as $nationality)
                    <option value="{{ $nationality->id }}"
                        {{ isSelected('nationality_id', $nationality->id) ? 'selected' : '' }}>
                        {{ $nationality->nationality_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Religion -->
        <div class="col-md-3 mb-3">
            <label for="religion">Religion</label>
            <select class="form-control" id="religion"
                name="religion_id">
                <option value="">Select Religion</option>
                @foreach ($religions as $religion)
                    <option value="{{ $religion->id }}"
                        {{ isSelected('religion_id', $religion->id) ? 'selected' : '' }}>
                        {{ $religion->religion_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- NIDA Card No -->
        <div class="col-md-3 mb-3">
            <label for="nida_no">NIDA Card No</label>
            <input type="text" class="form-control"
                id="nida_no" name="nida_no"
                value="{{ getFormValue('nida_no', $employee->nida_no ?? '') }}">
        </div>

        <!-- Employee Type -->
        <div class="col-md-3 mb-3">
            <label for="employee_type">Employee Type</label>
            <select class="form-control" id="employee_type"
                name="employee_type">
                <option value="">Select Employee Type</option>
                <option value="permanent" {{ isSelected('employee_type', 'permanent') ? 'selected' : '' }}>
                    Permanent
                </option>
                <option value="casual" {{ isSelected('employee_type', 'casual') ? 'selected' : '' }}>
                    Casual
                </option>
                <option value="contract" {{ isSelected('employee_type', 'contract') ? 'selected' : '' }}>
                    On Contract
                </option>
                <option value="probation" {{ isSelected('employee_type', 'probation') ? 'selected' : '' }}>
                    Probation
                </option>
                <option value="consultant" {{ isSelected('employee_type', 'consultant') ? 'selected' : '' }}>
                    Consultant
                </option>
            </select>
        </div>

        <!-- Employee Status -->
        <div class="col-md-3 mb-3">
            <label for="employee_status">Employee Status</label>
            <select class="form-control" id="employee_status"
                name="employee_status">
                <option value="active" {{ isSelected('employee_status', 'active') ? 'selected' : '' }}>
                    Active</option>
                <option value="inactive" {{ isSelected('employee_status', 'inactive') ? 'selected' : '' }}>
                    Inactive</option>
                <option value="onhold" {{ isSelected('employee_status', 'onhold') ? 'selected' : '' }}>
                    OnHold Salary</option>
            </select>
        </div>

        <!-- Residential Status -->
        <div class="col-md-3 mb-3">
            <label for="residential_status">Residential Status</label>
            <select class="form-control"
                id="residential_status"
                name="residential_status">
                <option value="residential" {{ isSelected('residential_status', 'residential') ? 'selected' : '' }}>
                    Residential</option>
                <option value="non_residential" {{ isSelected('residential_status', 'non_residential') ? 'selected' : '' }}>
                    Non-Residential</option>
            </select>
        </div>

        <!-- Type of Tax -->
        <div class="col-md-3 mb-3">
            <label for="tax_rate_id">Type of Tax</label>
            <select class="form-control" id="tax_rate_id"
                name="tax_rate_id">
                <option value="">Select Tax rate type</option>
                @foreach ($tax_rates as $tax_rate)
                    <option value="{{ $tax_rate->id }}"
                        {{ isSelected('tax_rate_id', $tax_rate->id) ? 'selected' : '' }}>
                        {{ $tax_rate->tax_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label for="shift_id">Shift Type</label>
            <select class="form-control" id="shift_id"
                name="shift_id">
                <option value="">Select Shift</option>
                @if(isset($shifts))
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}"
                            {{ isSelected('shift_id', $shift->id) ? 'selected' : '' }}>
                            {{ $shift->shift_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label for="wcf_no">WCF No</label>
            <input type="text" class="form-control"
                id="wcf_no" name="wcf_no" value="{{ getFormValue('wcf_no', $employee->wcf_no ?? '') }}">
        </div>

        <!-- Address -->
        <div class="col-md-3 mb-3">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address">{{ getFormValue('address', $employee->address ?? '') }}</textarea>
        </div>

        <!-- Photo Upload -->
        <div class="col-md-3 mb-3">
            <label for="photo_path">Photo</label>
            <input type="file" class="form-control"
                id="photo_path" name="photo_path">
            @if(isset($employee) && $employee->photo_path)
                <small class="form-text text-muted">Current photo: {{ $employee->photo_path }}</small>
            @endif
        </div>

    </div>
</div>
