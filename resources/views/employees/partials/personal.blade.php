
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
                    value="{{ old('employee_name', $employee->employee_name ?? '') }}" required>
            </div>

            <!-- Employee ID -->
            <div class="col-md-3 mb-3">
                <label for="employeeID">Employee ID</label>
                <input type="text" class="form-control"
                    id="employeeID" name="employeeID"
                    value="{{ old('employeeID', $employee->employeeID ?? '') }}">
            </div>

            <!-- Biometric ID -->
            <div class="col-md-3 mb-3">
                <label for="biometric_id">Biometric ID</label>
                <input type="text" class="form-control"
                    id="biometricID" name="biometricID"
                    value="{{ old('biometricID', $employee->biometricID ?? '') }}">
            </div>

            <!-- Date of Birth -->
            <div class="col-md-3 mb-3">
                <label for="date_of_birth">Date of Birth *</label>
                <input type="date" class="form-control"
                    id="date_of_birth" name="date_of_birth"
                    value="{{ old('date_of_birth', $employee->date_of_birth ?? '') }}" required>
            </div>

            <!-- Mobile No -->
            <div class="col-md-3 mb-3">
                <label for="mobile_no">Mobile No</label>
                <input type="text" class="form-control"
                    id="mobile_no" name="mobile_no"
                    value="{{ old('mobile_no', $employee->mobile_no ?? '') }}">
            </div>

            <!-- Email -->
            <div class="col-md-3 mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control"
                    id="email" name="email"
                    value="{{ old('email', $employee->email ?? '') }}">
            </div>

            <!-- TIN No -->
            <div class="col-md-3 mb-3">
                <label for="tin_no">TIN No (9 digits)</label>
                <input type="text" maxlength="9"
                    class="form-control" id="tin_no"
                    name="tin_no" value="{{ old('tin_no', $employee->tin_no ?? '') }}">
            </div>

            <!-- Gender -->
            <div class="col-md-3 mb-3">
                <label for="gender">Gender *</label>
                <select class="form-control" id="gender"
                    name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male"
                        {{ old('gender', $employee->gender ?? '') == 'male' ? 'selected' : '' }}>
                        Male</option>
                    <option value="female"
                        {{ old('gender', $employee->gender ?? '') == 'female' ? 'selected' : '' }}>
                        Female</option>
                </select>
            </div>

            <!-- Marital Status -->
            <div class="col-md-3 mb-3">
                <label for="marital_status">Marital Status *</label>
                <select class="form-control" id="marital_status"
                    name="marital_status" required>
                    <option value="">Select Marital Status</option>
                    <option value="single"
                        {{ old('marital_status', $employee->marital_status ?? '') == 'single' ? 'selected' : '' }}>
                        Single</option>
                    <option value="married"
                        {{ old('marital_status', $employee->marital_status ?? '') == 'married' ? 'selected' : '' }}>
                        Married</option>
                    <option value="divorced"
                        {{ old('marital_status', $employee->marital_status ?? '') == 'divorced' ? 'selected' : '' }}>
                        Divorced</option>
                    <option value="widowed"
                        {{ old('marital_status', $employee->marital_status ?? '') == 'widowed' ? 'selected' : '' }}>
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
                            {{ old('nationality_id', $employee->nationality_id ?? '') == $nationality->id ? 'selected' : '' }}>
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
                            {{ old('religion_id', $employee->religion_id ?? '') == $religion->id ? 'selected' : '' }}>
                            {{ $religion->religion_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- NIDA Card No -->
            <div class="col-md-3 mb-3">
                <label for="nida_no">NIDA Card No</label>
                <input type="text" class="form-control"
                    id="nida_no" name="nida_no"
                    value="{{ old('nida_no', $employee->nida_no ?? '') }}">
            </div>

            <!-- Employee Type -->
            <div class="col-md-3 mb-3">
                <label for="employee_type">Employee Type</label>
                <select class="form-control" id="employee_type"
                    name="employee_type">
                    <option value="">Select Employee Type</option>
                    <option value="permanent"
                        {{ old('employee_type', $employee->employee_type ?? '') == 'permanent' ? 'selected' : '' }}>
                        Permanent
                    </option>
                    <option value="casual"
                        {{ old('employee_type', $employee->employee_type ?? '') == 'casual' ? 'selected' : '' }}>
                        Casual
                    </option>
                    <option value="contract"
                        {{ old('employee_type', $employee->employee_type ?? '') == 'contract' ? 'selected' : '' }}>
                        On Contract
                    </option>
                    <option value="probation"
                        {{ old('employee_type', $employee->employee_type ?? '') == 'probation' ? 'selected' : '' }}>
                        Probation
                    </option>
                    <option value="consultant"
                        {{ old('employee_type', $employee->employee_type ?? '') == 'consultant' ? 'selected' : '' }}>
                        Consultant
                    </option>
                </select>
            </div>

            <!-- Employee Status -->
            <div class="col-md-3 mb-3">
                <label for="employee_status">Employee Status</label>
                <select class="form-control" id="employee_status"
                    name="employee_status">
                    <option value="active"
                        {{ old('employee_status', $employee->employee_status ?? '') == 'active' ? 'selected' : '' }}>
                        Active</option>
                    <option value="inactive"
                        {{ old('employee_status', $employee->employee_status ?? '') == 'inactive' ? 'selected' : '' }}>
                        Inactive</option>
                    <option value="onhold"
                        {{ old('employee_status', $employee->employee_status ?? '') == 'onhold' ? 'selected' : '' }}>
                        OnHold Salary</option>
                </select>
            </div>

            <!-- Residential Status -->
            <div class="col-md-3 mb-3">
                <label for="residential_status">Residential Status</label>
                <select class="form-control"
                    id="residential_status"
                    name="residential_status">
                    <option value="residential"
                        {{ old('residential_status', $employee->residential_status ?? '') == 'residential' ? 'selected' : '' }}>
                        Residential</option>
                    <option value="non_residential"
                        {{ old('residential_status', $employee->residential_status ?? '') == 'non_residential' ? 'selected' : '' }}>
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
                            {{ old('tax_rate_id', $employee->tax_rate_id ?? '') == $tax_rate->id ? 'selected' : '' }}>
                            {{ $tax_rate->tax_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="wcf_no">WCF No</label>
                <input type="text" class="form-control"
                    id="wcf_no" name="wcf_no" value="{{ old('wcf_no', $employee->wcf_no ?? '') }}">
            </div>

            <!-- Address -->
            <div class="col-md-3 mb-3">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address">{{ old('address', $employee->address ?? '') }}</textarea>
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
