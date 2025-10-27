{{-- employees/partials/departments.blade.php with session support --}}

<div class="card-header">
    <strong>Department Information</strong>
</div>
<div class="card-body">
    <div class="form-row">

        <!-- Joining Date -->
        <div class="col-md-3 mb-3">
            <label for="joining_date">Date of Joining *</label>
            <input type="date" class="form-control" id="joining_date" name="joining_date"
                value="{{ getFormValue('joining_date', optional(optional($employee ?? null)->department)->joining_date?->format('Y-m-d') ?? '') }}" required>
        </div>

        <!-- Main Station -->
        <div class="col-md-3 mb-3">
            <label for="mainstation_id">Main Station *</label>
            <select class="form-control" id="mainstation_id" name="mainstation_id" required>
                <option value="">Select Main Station</option>
                @foreach ($mainstations as $station)
                    <option value="{{ $station->id }}"
                        {{ isSelected('mainstation_id', $station->id, optional(optional($employee ?? null)->department)->mainstation_id ?? '') ? 'selected' : '' }}>
                        {{ $station->station_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Sub Station -->
        <div class="col-md-3 mb-3">
            <label for="substation_id">Sub Station *</label>
            <select class="form-control" id="substation_id" name="substation_id" required>
                <option value="">Select Sub Station</option>
                @foreach ($substations as $substation)
                    <option value="{{ $substation->id }}"
                        {{ isSelected('substation_id', $substation->id, optional(optional($employee ?? null)->department)->substation_id ?? '') ? 'selected' : '' }}>
                        {{ $substation->substation_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Department -->
        <div class="col-md-3 mb-3">
            <label for="department_id">Department *</label>
            <select class="form-control" id="department_id" name="department_id" required>
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ isSelected('department_id', $department->id, optional(optional($employee ?? null)->department)->department_id ?? '') ? 'selected' : '' }}>
                        {{ $department->department_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Job Title -->
        <div class="col-md-3 mb-3">
            <label for="jobtitle_id">Job Title *</label>
            <select class="form-control" id="jobtitle_id" name="jobtitle_id" required>
                <option value="">Select Job Title</option>
                @foreach ($jobtitles as $title)
                    <option value="{{ $title->id }}"
                        {{ isSelected('jobtitle_id', $title->id, optional(optional($employee ?? null)->department)->jobtitle_id ?? '') ? 'selected' : '' }}>
                        {{ $title->job_title }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Staff Level -->
        <div class="col-md-3 mb-3">
            <label for="staff_level_id">Staff Level *</label>
            <select class="form-control" id="staff_level_id" name="staff_level_id" required>
                <option value="">Select Staff Level</option>
                @foreach ($level_names as $level)
                    <option value="{{ $level->id }}"
                        {{ isSelected('staff_level_id', $level->id, optional(optional($employee ?? null)->department)->staff_level_id ?? '') ? 'selected' : '' }}>
                        {{ $level->level_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Head of Department -->
        <div class="col-md-3 mb-3">
            <div class="form-check mt-4">
                <input type="hidden" name="hod" value="0">
                <input class="form-check-input" type="checkbox" name="hod" id="hod" value="1"
                    {{ isChecked('hod', '1', optional(optional($employee ?? null)->department)->hod ?? '') ? 'checked' : '' }}>
                <label class="form-check-label" for="hod">Head of Department</label>
            </div>
        </div>
    </div>
</div>
