{{-- Shared Helper Functions for Employee Forms --}}
@php
    // Helper function to get form data from session or old input
    function getFormValue($fieldName, $default = '')
    {
        // First check old input (for validation errors)
        if (old($fieldName) !== null) {
            return old($fieldName);
        }

        // Then check session
        $formData = session('employee_form_data', []);
        return $formData[$fieldName] ?? $default;
    }

    // Helper function for checkboxes
    function isChecked($fieldName, $value = '1', $default = '')
    {
        $formValue = getFormValue($fieldName, $default);

        if (is_array($formValue)) {
            return in_array($value, $formValue);
        }

        return $formValue == $value || $formValue === true;
    }

    // Helper function for radio buttons and select dropdowns
    function isSelected($fieldName, $value, $default = '')
    {
        return getFormValue($fieldName, $default) == $value;
    }
@endphp
