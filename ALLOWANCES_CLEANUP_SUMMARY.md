# Allowances Cleanup Summary

## Issue
The system was trying to insert `housing_allowance`, `transport_allowance`, and `medical_allowance` columns into the `employees` table, but these columns don't exist in the database schema.

## Root Cause
These individual allowance fields were legacy fields that have been replaced by the **Earning Groups** system, which provides a more flexible way to manage employee allowances.

## Changes Made

### 1. EmployeeController (`app/Http/Controllers/EmployeeController.php`)

#### Removed from Validation Rules (store method):
```php
// REMOVED:
'housing_allowance' => 'nullable|numeric|min:0',
'transport_allowance' => 'nullable|numeric|min:0',
'medical_allowance' => 'nullable|numeric|min:0',
```

#### Removed from Employee Creation:
```php
// REMOVED:
'housing_allowance' => $validatedData['housing_allowance'] ?? 0,
'transport_allowance' => $validatedData['transport_allowance'] ?? 0,
'medical_allowance' => $validatedData['medical_allowance'] ?? 0,
```

### 2. Employee Model (`app/Models/Employee.php`)

#### Removed from Fillable Array:
```php
// REMOVED from fillable:
'housing_allowance',
'transport_allowance',
'medical_allowance',
```

#### Removed from Casts:
```php
// REMOVED from casts:
'housing_allowance' => 'decimal:2',
'transport_allowance' => 'decimal:2',
'medical_allowance' => 'decimal:2',
```

#### Updated getTotalSalary() Method:
**Before:**
```php
// Fallback to individual allowances for backwards compatibility
return $this->basic_salary +
       ($this->housing_allowance ?? 0) +
       ($this->transport_allowance ?? 0) +
       ($this->medical_allowance ?? 0);
```

**After:**
```php
// Return basic salary only if no earngroups
return $this->basic_salary;
```

### 3. Employee Show View (`resources/views/employees/show.blade.php`)

#### Updated Salary Card (Top Summary):
**Before:**
```php
@if($employee->housing_allowance || $employee->transport_allowance || $employee->medical_allowance)
    <div class="col-12">
        <small class="text-muted">Total Allowances</small>
        <h6 class="mb-0">
            TZS {{ number_format(($employee->housing_allowance ?? 0) + ($employee->transport_allowance ?? 0) + ($employee->medical_allowance ?? 0), 2) }}
        </h6>
    </div>
@endif
```

**After:**
```php
@if($employee->getTotalAllowancesFromEarngroups() > 0)
    <div class="col-12">
        <small class="text-muted">Total Allowances (from Earning Groups)</small>
        <h6 class="mb-0">
            TZS {{ number_format($employee->getTotalAllowancesFromEarngroups(), 2) }}
        </h6>
    </div>
@endif
```

#### Updated Salary Details Table:
**Before:**
```php
@if($employee->housing_allowance)
    <tr>
        <td class="text-muted">Housing Allowance:</td>
        <td>TZS {{ number_format($employee->housing_allowance, 2) }}</td>
    </tr>
@endif
@if($employee->transport_allowance)
    <tr>
        <td class="text-muted">Transport Allowance:</td>
        <td>TZS {{ number_format($employee->transport_allowance, 2) }}</td>
    </tr>
@endif
@if($employee->medical_allowance)
    <tr>
        <td class="text-muted">Medical Allowance:</td>
        <td>TZS {{ number_format($employee->medical_allowance, 2) }}</td>
    </tr>
@endif
```

**After:**
```php
@if($employee->getTotalAllowancesFromEarngroups() > 0)
    <tr>
        <td class="text-muted">Total Allowances:</td>
        <td>TZS {{ number_format($employee->getTotalAllowancesFromEarngroups(), 2) }}</td>
    </tr>
@endif
@if($employee->earngroups->count() > 0)
    <tr>
        <td class="text-muted">Earning Groups:</td>
        <td>
            @foreach($employee->earngroups as $earngroup)
                <span class="badge badge-info">{{ $earngroup->earngroup_name }}</span>
            @endforeach
        </td>
    </tr>
@endif
```

## Database Schema

### Employees Table (Current)
The `employees` table does **NOT** have individual allowance columns. Instead, it has:
- `basic_salary` - Employee's base salary
- Relations to `earning_groups` through `employee_earngroups` pivot table

### How Allowances Work Now

**Earning Groups System:**
1. Create Earning Groups (e.g., "Manager Benefits", "Field Staff Benefits")
2. Each earning group contains multiple allowances (Housing, Transport, Medical, etc.)
3. Assign earning groups to employees via checkboxes in the salary form
4. Allowances are calculated dynamically through relationships

**Benefits:**
- ✅ More flexible - create any combination of allowances
- ✅ Reusable - same earning group can be assigned to multiple employees
- ✅ Easier to maintain - update allowances in one place
- ✅ Supports both taxable and non-taxable allowances
- ✅ Supports percentage-based and fixed amount allowances

## Employee Model Methods for Allowances

The Employee model has these methods to calculate allowances from earning groups:

```php
// Get taxable allowances from assigned earning groups
$employee->getTaxableAllowancesFromEarngroups()

// Get non-taxable allowances from assigned earning groups
$employee->getNonTaxableAllowancesFromEarngroups()

// Get total allowances (taxable + non-taxable)
$employee->getTotalAllowancesFromEarngroups()

// Get total salary (basic + taxable allowances)
$employee->getTotalSalary()
```

## Impact on Payroll

When processing payroll, the system now:
1. Gets employee's basic salary
2. Calculates allowances from assigned earning groups
3. Computes gross salary = basic + taxable allowances
4. Applies deductions
5. Calculates PAYE on taxable income

## Testing

✅ No linter errors
✅ All references to individual allowances removed
✅ Replaced with earning groups-based calculations
✅ Views updated to show earning groups

## What You Should Do

1. **Create Earning Groups** if you haven't already:
   - Go to Allowances → Earning Groups
   - Create groups like "Standard Benefits", "Manager Benefits", etc.
   - Add allowances to each group (housing, transport, medical, etc.)

2. **Assign Earning Groups to Employees**:
   - Edit Employee → Step 2 (Salary)
   - Check the earning groups that apply to this employee
   - Save

3. **Verify Allowances Display**:
   - View employee profile
   - Should see "Total Allowances (from Earning Groups)"
   - Should see earning group badges

## Migration Note

If you have existing employees with legacy allowance data in a different database:
- You'll need to create appropriate earning groups
- Migrate the data by assigning correct earning groups to employees
- The old individual allowance fields are no longer supported

## Files Modified

- ✅ `app/Http/Controllers/EmployeeController.php`
- ✅ `app/Models/Employee.php`
- ✅ `resources/views/employees/show.blade.php`

## Result

The error you encountered:
```
SQLSTATE[42703]: Undefined column: 7 ERROR: column "housing_allowance" of relation "employees" does not exist
```

Should now be **RESOLVED**. The system will use earning groups for all allowance calculations.

