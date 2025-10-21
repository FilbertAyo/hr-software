# Employee Earning Groups Implementation

## Overview
This document describes the implementation of the employee earning groups feature, which allows administrators to assign multiple earning groups to employees during creation and editing.

## Database Structure

### Tables Created

#### `employee_earngroups` (Junction Table)
A many-to-many relationship table linking employees to earning groups.

**Columns:**
- `id` - Primary key
- `employee_id` - Foreign key to `employees` table
- `earngroup_id` - Foreign key to `earngroups` table
- `status` - Enum ('active', 'inactive') - Default: 'active'
- `created_at` - Timestamp
- `updated_at` - Timestamp
- **Unique constraint** on (`employee_id`, `earngroup_id`) - Prevents duplicate assignments

**Relationships:**
- Cascades on delete - If an employee or earngroup is deleted, the junction records are automatically removed

## Models

### EmployeeEarngroup Model
**Location:** `app/Models/EmployeeEarngroup.php`

**Fillable attributes:**
- `employee_id`
- `earngroup_id`
- `status`

**Relationships:**
- `employee()` - Belongs to Employee
- `earngroup()` - Belongs to Earngroup

### Employee Model Updates
**Location:** `app/Models/Employee.php`

**New Relationships Added:**
- `employeeEarngroups()` - Has many EmployeeEarngroup (direct relationship)
- `earngroups()` - Many-to-many relationship with Earngroup through `employee_earngroups` table
  - Includes pivot data: `status`
  - Includes timestamps

**Usage Example:**
```php
// Get all earngroups for an employee
$employee->earngroups; // Returns collection of Earngroup models

// Get with pivot data
foreach ($employee->earngroups as $earngroup) {
    echo $earngroup->pivot->status; // 'active' or 'inactive'
    echo $earngroup->pivot->created_at;
}

// Check if employee has specific earngroup
$employee->earngroups->contains($earngroupId);
```

### Earngroup Model Updates
**Location:** `app/Models/Earngroup.php`

**New Relationships Added:**
- `employeeEarngroups()` - Has many EmployeeEarngroup (direct relationship)
- `employees()` - Many-to-many relationship with Employee through `employee_earngroups` table
  - Includes pivot data: `status`
  - Includes timestamps

**Usage Example:**
```php
// Get all employees in an earngroup
$earngroup->employees; // Returns collection of Employee models
```

## Controller Implementation

### EmployeeController Updates
**Location:** `app/Http/Controllers/EmployeeController.php`

#### create() Method
- Added `$earngroups = \App\Models\Earngroup::all();` to fetch all earngroups
- Passed `earngroups` to the view

#### store() Method
**Validation Added:**
```php
'earngroup_ids' => 'nullable|array',
'earngroup_ids.*' => 'integer|exists:earngroups,id',
```

**Logic Added (after EmployeeDepartment creation):**
```php
if (!empty($validatedData['earngroup_ids'])) {
    foreach ($validatedData['earngroup_ids'] as $earngroupId) {
        \App\Models\EmployeeEarngroup::create([
            'employee_id' => $employee->id,
            'earngroup_id' => $earngroupId,
            'status' => 'active',
        ]);
    }
}
```

#### edit() Method
- Added `$earngroups = \App\Models\Earngroup::all();` to fetch all earngroups
- Passed `earngroups` to the view
- Added `'earngroups'` to the `load()` statement to eager load the relationship

#### updateSalaryDetails() Method
**Validation Added:**
```php
'earngroup_ids' => 'nullable|array',
'earngroup_ids.*' => 'integer|exists:earngroups,id',
```

**Logic Added (sync earngroups):**
```php
if (isset($validatedData['earngroup_ids'])) {
    // Delete existing earngroup assignments
    \App\Models\EmployeeEarngroup::where('employee_id', $employee->id)->delete();
    
    // Create new assignments
    foreach ($validatedData['earngroup_ids'] as $earngroupId) {
        \App\Models\EmployeeEarngroup::create([
            'employee_id' => $employee->id,
            'earngroup_id' => $earngroupId,
            'status' => 'active',
        ]);
    }
} else {
    // If no earngroups selected, delete all assignments
    \App\Models\EmployeeEarngroup::where('employee_id', $employee->id)->delete();
}
```

#### show() Method
- Added `'earngroups'` to the `load()` statement to eager load the relationship

## View Implementation

### Salary Partial View
**Location:** `resources/views/employees/partials/salary.blade.php`

**Implementation Details:**
- Replaced the placeholder input with a scrollable checkbox list
- Maximum height of 200px with vertical scrolling
- Shows earngroup name and description
- Pre-selects earngroups that are already assigned to the employee (for edit mode)
- Helpful text explaining the feature

**HTML Structure:**
```blade
<div class="col-md-9 mb-3">
    <label for="earngroups">Earning Groups</label>
    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa;">
        @if(isset($earngroups) && $earngroups->count() > 0)
            @foreach($earngroups as $earngroup)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                        name="earngroup_ids[]" 
                        value="{{ $earngroup->id }}" 
                        id="earngroup_{{ $earngroup->id }}"
                        {{ (isset($employee) && $employee->earngroups->contains($earngroup->id)) ? 'checked' : '' }}>
                    <label class="form-check-label" for="earngroup_{{ $earngroup->id }}">
                        {{ $earngroup->earngroup_name }}
                        @if($earngroup->description)
                            <small class="text-muted">({{ $earngroup->description }})</small>
                        @endif
                    </label>
                </div>
            @endforeach
        @else
            <p class="text-muted mb-0">No earning groups available</p>
        @endif
    </div>
    <small class="form-text text-muted">
        Select one or more earning groups for this employee. Each earning group contains multiple allowances.
    </small>
</div>
```

## Comparison: Employee Earning Groups vs Other Benefits

### Employee Earning Groups
- **Storage:** Junction table (`employee_earngroups`)
- **Relationship:** Many-to-many (employees ↔ earngroups)
- **Assignment:** During employee creation/editing
- **Structure:** Normalized database design
- **Benefits:** 
  - Proper relational integrity
  - Easy to query and manage
  - Can track status per assignment
  - Efficient for frequent queries

### Other Benefits
- **Storage:** JSON array in `other_benefit_details.employee_ids`
- **Relationship:** One-to-many with embedded employee list
- **Assignment:** Separate interface for assigning benefits to employees
- **Structure:** Denormalized (JSON storage)
- **Benefits:**
  - Simpler for temporary/ad-hoc benefits
  - Good for "apply to all" scenarios
  - Includes amount and date per benefit instance

## How It Works

### Creating an Employee
1. Admin fills out employee form
2. In the salary section, admin selects one or more earning groups via checkboxes
3. On submit:
   - Employee record is created
   - EmployeeDepartment record is created
   - For each selected earngroup, an EmployeeEarngroup record is created with status 'active'
4. All operations happen within a database transaction

### Editing an Employee
1. Admin opens employee edit form
2. Previously assigned earngroups are pre-checked
3. Admin can add or remove earngroups
4. On submit:
   - All existing EmployeeEarngroup records for this employee are deleted
   - New EmployeeEarngroup records are created for each selected earngroup
5. This ensures the assignments always match the current selection

### Viewing Employee Earngroups
```php
// In blade templates
@foreach($employee->earngroups as $earngroup)
    <p>{{ $earngroup->earngroup_name }}</p>
    <p>Status: {{ $earngroup->pivot->status }}</p>
@endforeach
```

## Data Flow

```
Earngroup (1) ←→ (Many) GroupBenefit (Many) ←→ (1) Allowance
                        ↓
                   (via earngroup_id)
                        ↓
                EmployeeEarngroup (Junction)
                        ↓
                   (via employee_id)
                        ↓
                    Employee
```

**Meaning:**
- An earngroup contains multiple allowances (through group_benefits)
- An employee can have multiple earngroups
- When you assign an earngroup to an employee, they inherit all the allowances in that earngroup

## Migration

**File:** `database/migrations/2025_10_21_113239_create_employee_earngroups_table.php`

**To run:**
```bash
php artisan migrate
```

**To rollback:**
```bash
php artisan migrate:rollback
```

## Best Practices

1. **Always validate earngroup_ids** when receiving them from forms
2. **Use transactions** when creating/updating employee earngroups to ensure data consistency
3. **Eager load earngroups** when displaying employee lists to avoid N+1 queries:
   ```php
   $employees = Employee::with('earngroups')->get();
   ```
4. **Check existence** before displaying in views:
   ```php
   @if(isset($earngroups) && $earngroups->count() > 0)
   ```

## Future Enhancements

Consider implementing:
1. **Bulk assignment** - Assign an earngroup to multiple employees at once
2. **Effective dates** - Add `effective_from` and `effective_to` dates to track when an earngroup is active
3. **Audit trail** - Track who assigned/removed earngroups and when
4. **Permissions** - Role-based access control for managing earngroup assignments
5. **Reports** - Generate reports showing which employees are in which earngroups

## Payroll Integration

When calculating payroll, you can now:
1. Get all earngroups for an employee
2. For each earngroup, get all allowances
3. Calculate allowance amounts based on `allowance_details`
4. Sum up all allowances for the employee's total benefits

**Example:**
```php
$employee = Employee::with(['earngroups.groupBenefits.allowance.details'])->find($id);

$totalAllowances = 0;
foreach ($employee->earngroups as $earngroup) {
    foreach ($earngroup->groupBenefits as $groupBenefit) {
        if ($groupBenefit->status === 'active') {
            $allowanceDetail = $groupBenefit->allowance->details;
            // Calculate based on calculation_type (amount or percentage)
            // Add to totalAllowances
        }
    }
}
```

## Summary

The employee earning groups feature provides a clean, normalized way to assign multiple earning groups (and their associated allowances) to employees. It uses a proper many-to-many relationship through a junction table, ensuring data integrity and making it easy to manage, query, and report on employee benefits.

