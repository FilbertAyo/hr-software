# Employee Deductions UI Implementation Guide

## Overview
This guide documents the implementation of the employee deductions assignment UI in the salary partial view.

## Features Implemented

### 1. Dynamic Deduction Assignment
Employees can now have multiple normal deductions (NHIF, WCF, SDL, etc.) assigned to them through an intuitive interface.

### 2. User Interface Components

#### A. Assigned Deductions Table
When editing an existing employee, assigned deductions are displayed in a table showing:
- Deduction Name
- Employee Percentage
- Employer Percentage
- Member Number
- Status (Active/Inactive)
- Remove Action button

#### B. Add Deduction Interface
- **Add Deduction Button**: Allows users to add new deduction rows dynamically
- **Deduction Row**: Each row contains:
  - Deduction dropdown (shows only normal deductions with employee_percent)
  - Member Number input field
  - Remove button

#### C. Smart Member Number Validation
- When a deduction with `require_member_no = true` is selected, the member number field becomes required
- Placeholder text changes to indicate if member number is required or optional

### 3. Controller Changes

#### EmployeeController - `create()` method
```php
// Added loading of normal deductions
$deductions = DirectDeduction::where('deduction_type', 'normal')
    ->where('status', 'active')
    ->whereNotNull('employee_percent')
    ->get();
```

#### EmployeeController - `edit()` method
```php
// Added loading of employee deductions with relationships
$employee->load([
    // ... other relationships
    'employeeDeductions.directDeduction'
]);

// Added loading of normal deductions
$deductions = DirectDeduction::where('deduction_type', 'normal')
    ->where('status', 'active')
    ->whereNotNull('employee_percent')
    ->get();
```

#### EmployeeController - `updateSalaryDetails()` method
```php
// Added validation for deduction arrays
'deduction_ids' => 'nullable|array',
'deduction_ids.*' => 'integer|exists:direct_deductions,id',
'deduction_member_numbers' => 'nullable|array',
'deduction_member_numbers.*' => 'nullable|string|max:255',

// Added syncing logic for employee deductions
// Deletes old normal deductions and creates new ones based on form input
```

### 4. View Implementation

#### Template Structure (salary.blade.php)
```blade
<!-- Section Header -->
Other Deductions (NHIF, WCF, SDL, etc.)

<!-- Display assigned deductions table (edit mode only) -->
@if(isset($employee) && $employee->employeeDeductions->count() > 0)
    <table> ... </table>
@endif

<!-- Add deduction button -->
<button id="addDeductionBtn">Add Deduction</button>

<!-- Dynamic rows container -->
<div id="deductionsContainer"></div>

<!-- Hidden template for cloning -->
<template id="deductionRowTemplate">
    <div class="deduction-row">
        <select name="deduction_ids[]">...</select>
        <input name="deduction_member_numbers[]">
        <button class="remove-row-btn">Remove</button>
    </div>
</template>
```

### 5. JavaScript Functionality

#### Add Deduction Row
```javascript
addDeductionBtn.addEventListener('click', function() {
    // Clone template
    const newRow = deductionRowTemplate.content.cloneNode(true);
    
    // Attach event listeners
    // - Remove button handler
    // - Deduction select change handler (for member number validation)
    
    // Append to container
    deductionsContainer.appendChild(newRow);
});
```

#### Member Number Smart Validation
```javascript
deductionSelect.addEventListener('change', function() {
    const requireMemberNo = selectedOption.getAttribute('data-require-member-no');
    
    if (requireMemberNo === '1') {
        memberNumberInput.setAttribute('required', 'required');
        memberNumberInput.placeholder = 'Member number required';
    } else {
        memberNumberInput.removeAttribute('required');
        memberNumberInput.placeholder = 'Enter member number if required';
    }
});
```

#### Remove Assigned Deduction
```javascript
// Removes row from table with confirmation
document.querySelectorAll('.remove-deduction-btn').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('tr');
        if (confirm('Are you sure?')) {
            row.remove();
        }
    });
});
```

## Data Flow

### Creating/Updating Employee Deductions

1. **User Action**: User clicks "Add Deduction" button
2. **UI Response**: New row is added dynamically
3. **User Input**: User selects deduction and optionally enters member number
4. **Form Submit**: Arrays are sent to controller:
   - `deduction_ids[]` - Array of deduction IDs
   - `deduction_member_numbers[]` - Array of member numbers (indexed to match deduction_ids)

5. **Controller Processing**:
   ```php
   // Delete existing normal deductions
   EmployeeDeduction::where('employee_id', $employee->id)
       ->whereHas('directDeduction', function($query) {
           $query->where('deduction_type', 'normal');
       })->delete();

   // Create new assignments
   foreach ($validatedData['deduction_ids'] as $index => $deductionId) {
       EmployeeDeduction::create([
           'employee_id' => $employee->id,
           'direct_deduction_id' => $deductionId,
           'member_number' => $validatedData['deduction_member_numbers'][$index] ?? null,
           'status' => 'active',
       ]);
   }
   ```

## Filtering Logic

### Deductions Shown in Dropdown
Only deductions that meet ALL these criteria are shown:
1. ✅ `deduction_type = 'normal'` (not pension)
2. ✅ `status = 'active'`
3. ✅ `employee_percent IS NOT NULL` (employees can be deducted)

### Deductions NOT Shown
- ❌ Pension deductions (handled separately in pension section)
- ❌ Inactive deductions
- ❌ Employer-only deductions (WCF, SDL when they have no employee_percent)

## Example Usage Scenarios

### Scenario 1: Adding NHIF Deduction
1. Employee form is open in edit mode
2. Click "Add Deduction" button
3. Select "NHIF" from dropdown
4. If NHIF has `require_member_no = true`, enter member number
5. Click "Save Changes"
6. NHIF deduction is now assigned to employee

### Scenario 2: Multiple Deductions
1. Click "Add Deduction" multiple times
2. Select different deductions in each row (e.g., NHIF, GEPF, etc.)
3. Enter member numbers where required
4. Click "Save Changes"
5. All deductions are assigned to employee

### Scenario 3: Removing a Deduction
1. In the assigned deductions table, click the trash icon
2. Confirm the deletion
3. Click "Save Changes"
4. Deduction is removed from employee

## Best Practices

1. **Always validate member numbers** when deduction has `require_member_no = true`
2. **Show clear feedback** to users about which fields are required
3. **Use confirmation dialogs** before removing assigned deductions
4. **Keep pension separate** from normal deductions (handled in different section)
5. **Load only relevant deductions** (active, normal, with employee_percent)

## Future Enhancements

### Potential Improvements
1. **AJAX Save**: Save deductions without full page reload
2. **Duplicate Prevention**: Prevent adding the same deduction twice
3. **Bulk Operations**: Activate/deactivate multiple deductions at once
4. **History Tracking**: Log when deductions are added/removed
5. **Member Number Validation**: Validate format based on deduction type (e.g., NSSF format)
6. **Auto-fill Member Numbers**: Remember member numbers from previous assignments

## Testing Checklist

- [ ] Can add new deduction row
- [ ] Can select deduction from dropdown
- [ ] Can enter member number
- [ ] Member number becomes required for deductions with require_member_no = true
- [ ] Can remove deduction row before saving
- [ ] Can remove assigned deduction from table
- [ ] Deductions are saved correctly to database
- [ ] Only normal deductions appear in dropdown
- [ ] Employer-only deductions don't appear in dropdown
- [ ] Multiple deductions can be assigned
- [ ] Member numbers are saved correctly
- [ ] Edit mode shows existing deductions
- [ ] Create mode allows adding deductions

