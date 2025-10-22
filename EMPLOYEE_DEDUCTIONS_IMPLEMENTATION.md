# Employee Deductions Implementation

## Overview
This document describes the implementation of the employee-specific deductions system that allows employees to have multiple direct deductions assigned to them.

## Changes Made

### 1. Database Schema Changes

#### `direct_deductions` table
- **Removed:** `must_include` column
- **Added:** `require_member_no` (boolean) - Indicates if a member number is required for this deduction type

#### `employee_deductions` table (NEW)
A pivot table that connects employees with their assigned deductions:
- `id` - Primary key
- `employee_id` - Foreign key to employees table
- `direct_deduction_id` - Foreign key to direct_deductions table
- `member_number` - Nullable string for storing member numbers (e.g., NSSF number, PPF number)
- `status` - Enum ('active', 'inactive') - Allows deactivating specific deductions
- `timestamps` - Created at and updated at

### 2. Models Updated

#### DirectDeduction Model
```php
// Fillable fields updated
'require_member_no' // replaces 'must_include'

// New relationships
public function employeeDeductions() // hasMany relationship
public function employees() // belongsToMany through employee_deductions
```

#### Employee Model
```php
// New relationships
public function employeeDeductions() // hasMany EmployeeDeduction
public function directDeductions() // belongsToMany DirectDeduction
public function activeDirectDeductions() // Only active deductions
```

#### EmployeeDeduction Model (NEW)
```php
// Fillable fields
'employee_id', 'direct_deduction_id', 'member_number', 'status'

// Relationships
public function employee() // belongsTo Employee
public function directDeduction() // belongsTo DirectDeduction
```

### 3. Controller Updates

#### DirectDeductionController
- Updated `store()` method to use `require_member_no` instead of `must_include`
- Updated `update()` method to use `require_member_no` instead of `must_include`

### 4. View Updates

#### `resources/views/deductions/direct/index.blade.php`
- Added "Require Member No" column to the table
- Updated create modal to use "Require Member Number" checkbox
- Updated edit modal to use "Require Member Number" checkbox
- JavaScript updated to handle the new field

## Seeded Data

The following deductions are seeded by default:

### Pension Deductions (can have ONE per employee)
1. **NSSF** - 10% employer, 10% employee (requires member number)
2. **PPF** - 15% employer, 5% employee (requires member number)
3. **PSPF** - 15% employer, 5% employee (requires member number)

### Normal Deductions (can have MULTIPLE per employee)
1. **WCF** - 1% employer only (no member number required)
2. **SDL** - 5% employer only (no member number required)

## Business Rules

### Deduction Selection Rules
1. **One Pension:** An employee can only have ONE pension deduction (NSSF, PPF, or PSPF)
2. **Multiple Normal:** An employee can have MULTIPLE normal deductions (WCF, SDL, NHIF, etc.)
3. **Employer-Only Exclusion:** Deductions with only employer percentage (employee_percent is null) should NOT appear in employee deduction selection dropdowns
4. **Member Numbers:** When a deduction has `require_member_no = true`, the member number must be provided when assigning to an employee

## Usage Examples

### Assigning Deductions to an Employee

```php
use App\Models\EmployeeDeduction;

// Assign NSSF pension with member number
EmployeeDeduction::create([
    'employee_id' => $employeeId,
    'direct_deduction_id' => $nssfId,
    'member_number' => 'NSSF12345678',
    'status' => 'active'
]);

// Assign WCF (no member number required)
EmployeeDeduction::create([
    'employee_id' => $employeeId,
    'direct_deduction_id' => $wcfId,
    'member_number' => null,
    'status' => 'active'
]);
```

### Retrieving Employee's Active Deductions

```php
// Get all active deductions for an employee
$employee = Employee::find($id);
$activeDeductions = $employee->activeDirectDeductions;

// Access with pivot data
foreach ($activeDeductions as $deduction) {
    echo $deduction->name;
    echo $deduction->pivot->member_number;
    echo $deduction->pivot->status;
}
```

### Filtering Deductions for Selection

```php
// Get only deductions that employees can select (have employee_percent)
$selectableDeductions = DirectDeduction::where('status', 'active')
    ->whereNotNull('employee_percent')
    ->get();

// Get pension deductions for selection
$pensionDeductions = DirectDeduction::where('deduction_type', 'pension')
    ->where('status', 'active')
    ->whereNotNull('employee_percent')
    ->get();

// Get normal deductions for selection
$normalDeductions = DirectDeduction::where('deduction_type', 'normal')
    ->where('status', 'active')
    ->whereNotNull('employee_percent')
    ->get();
```

### Checking if Member Number is Required

```php
$deduction = DirectDeduction::find($id);

if ($deduction->require_member_no) {
    // Validate and require member number input
    $validated = $request->validate([
        'member_number' => 'required|string|max:255'
    ]);
}
```

## Next Steps

To implement the employee deduction assignment UI, you'll need to:

1. **Create Employee Deduction Management View**
   - Add a section in the employee edit/create form for managing deductions
   - Show pension deductions as radio buttons (only one can be selected)
   - Show normal deductions as checkboxes (multiple can be selected)
   - Add member number input fields for deductions that require them

2. **Create Controller Methods**
   - Add methods to handle assigning/removing employee deductions
   - Validate that only one pension is assigned
   - Validate member numbers when required

3. **Integrate with Payroll**
   - Update payroll calculation to use `employee_deductions` table
   - Calculate deductions based on active employee deductions
   - Use employee-specific member numbers in reports

## Migration Note

All existing data has been migrated using `php artisan migrate:fresh --seed`. The `must_include` column has been completely removed and replaced with `require_member_no`.

