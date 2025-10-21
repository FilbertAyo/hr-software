# Employee Earning Groups - Quick Reference Guide

## What Was Implemented

A complete system for assigning **multiple earning groups** to employees during creation and editing.

## Key Differences: Earning Groups vs Other Benefits

| Feature | Earning Groups | Other Benefits |
|---------|---------------|----------------|
| **Storage** | Junction table (`employee_earngroups`) | JSON array in `other_benefit_details` |
| **Assigned During** | Employee creation/editing | Separate benefits management interface |
| **Structure** | Normalized (proper tables) | Denormalized (JSON) |
| **Best For** | Permanent employee allowances | Temporary/ad-hoc benefits |

## Files Created/Modified

### New Files
1. ✅ `database/migrations/2025_10_21_113239_create_employee_earngroups_table.php` - Database migration
2. ✅ `app/Models/EmployeeEarngroup.php` - Junction table model
3. ✅ `EMPLOYEE_EARNGROUPS_IMPLEMENTATION.md` - Full documentation
4. ✅ `EARNGROUPS_QUICK_REFERENCE.md` - This file

### Modified Files
1. ✅ `app/Models/Employee.php` - Added earngroups relationships
2. ✅ `app/Models/Earngroup.php` - Added employees relationships
3. ✅ `app/Http/Controllers/EmployeeController.php` - Added create/store/edit/update logic
4. ✅ `resources/views/employees/partials/salary.blade.php` - Added earngroup multi-select UI

## How to Use

### For Admins (Creating an Employee)
1. Go to **Create Employee** page
2. Fill in basic details
3. In the **Salary Details** section, you'll see **Earning Groups**
4. **Check the boxes** for all earning groups this employee should have
5. Each earning group contains multiple allowances
6. Click **Save**

### For Admins (Editing an Employee)
1. Go to **Edit Employee** page
2. In the **Salary Details** section, you'll see the **Earning Groups**
3. Previously assigned groups are **already checked**
4. **Add or remove** checkboxes as needed
5. Click **Update**

### For Developers (Displaying Earngroups)

**In Blade Templates:**
```blade
@foreach($employee->earngroups as $earngroup)
    <div>{{ $earngroup->earngroup_name }}</div>
@endforeach
```

**In Controllers:**
```php
// Load earngroups with employee
$employee = Employee::with('earngroups')->find($id);

// Get earngroup names
$earngroupNames = $employee->earngroups->pluck('earngroup_name');

// Check if employee has a specific earngroup
if ($employee->earngroups->contains($earngroupId)) {
    // Employee has this earngroup
}
```

**For Payroll Calculations:**
```php
// Load all related data
$employee = Employee::with([
    'earngroups.groupBenefits.allowance.details'
])->find($id);

// Calculate total allowances
$totalAllowances = 0;
foreach ($employee->earngroups as $earngroup) {
    foreach ($earngroup->groupBenefits as $groupBenefit) {
        if ($groupBenefit->status === 'active') {
            $detail = $groupBenefit->allowance->details->first();
            if ($detail) {
                if ($detail->calculation_type === 'amount') {
                    $totalAllowances += $detail->amount;
                } elseif ($detail->calculation_type === 'percentage') {
                    $totalAllowances += ($employee->basic_salary * $detail->percentage / 100);
                }
            }
        }
    }
}
```

## Database Schema

```
employee_earngroups
├── id (primary key)
├── employee_id (foreign key → employees.id)
├── earngroup_id (foreign key → earngroups.id)
├── status (enum: 'active', 'inactive')
├── created_at
└── updated_at

Unique constraint: (employee_id, earngroup_id)
```

## What Happens Behind the Scenes

### When Creating an Employee:
```
1. Validate earngroup_ids[]
2. Create Employee record
3. Create EmployeeDepartment record
4. For each selected earngroup:
   → Create EmployeeEarngroup record with status='active'
5. Commit transaction
```

### When Updating an Employee:
```
1. Validate earngroup_ids[]
2. Update Employee record
3. Delete ALL existing EmployeeEarngroup records for this employee
4. For each selected earngroup:
   → Create NEW EmployeeEarngroup record with status='active'
5. Commit transaction
```

This ensures the earngroups always match what's selected in the form.

## Example Use Case

**Scenario:** You have a "Sales Team" earngroup that includes:
- Transport Allowance
- Phone Allowance
- Commission Structure

**Setup:**
1. Create allowances: Transport, Phone, Commission
2. Create earngroup: "Sales Team"
3. Link allowances to earngroup via `group_benefits`

**Assignment:**
1. Create new employee: John Doe
2. Select "Sales Team" earngroup
3. John automatically gets all 3 allowances

**Benefits:**
- No need to assign allowances individually
- Easy to standardize compensation packages
- Update earngroup = updates all employees in that group

## Testing the Implementation

1. **Create Test:**
   - Create a new employee
   - Select 2-3 earngroups
   - Check database: `SELECT * FROM employee_earngroups WHERE employee_id = ?`
   - Should see records for each selected earngroup

2. **Edit Test:**
   - Edit the employee
   - Change earngroup selections
   - Check database again
   - Should see only the newly selected earngroups

3. **View Test:**
   - View employee details
   - Display `$employee->earngroups`
   - Should show all assigned earngroups

## Common Queries

**Get all employees in a specific earngroup:**
```php
$earngroup = Earngroup::with('employees')->find($id);
$employees = $earngroup->employees;
```

**Get active earngroups only:**
```php
$employee->earngroups()->wherePivot('status', 'active')->get();
```

**Count employees per earngroup:**
```php
$earngroup->employees()->count();
```

**Bulk assign earngroup to multiple employees:**
```php
$employeeIds = [1, 2, 3, 4, 5];
$earngroupId = 1;

foreach ($employeeIds as $employeeId) {
    EmployeeEarngroup::create([
        'employee_id' => $employeeId,
        'earngroup_id' => $earngroupId,
        'status' => 'active',
    ]);
}
```

## Migration Status

✅ Migration has been run successfully
✅ Table `employee_earngroups` created
✅ All relationships configured
✅ No linter errors

## Next Steps (Optional Enhancements)

1. **Add earngroups display to employee show page** - Show which earngroups an employee belongs to
2. **Bulk assignment interface** - Assign earngroup to multiple employees at once
3. **Earngroup management page** - View all employees in each earngroup
4. **Status toggle** - Activate/deactivate earngroup assignments without deleting them
5. **Payroll integration** - Auto-calculate allowances from earngroups during payroll processing

## Support

For detailed information, see: `EMPLOYEE_EARNGROUPS_IMPLEMENTATION.md`

For questions or issues:
1. Check the database structure
2. Verify relationships are loaded: `$employee->load('earngroups')`
3. Check validation rules in EmployeeController
4. Ensure `$earngroups` is passed to views

