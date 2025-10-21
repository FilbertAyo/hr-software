# Other Benefits - Quick Reference for Payroll Processing

## Get Employee's Other Benefits

### Option 1: Simple Helper Methods (Recommended)

```php
$employee = Employee::find($employeeId);
$payrollPeriod = PayrollPeriod::find($periodId);

// Get taxable other benefits for the period
$taxableOtherBenefits = $employee->getTaxableOtherBenefits(
    $payrollPeriod->start_date,
    $payrollPeriod->end_date
);

// Get non-taxable other benefits for the period
$nonTaxableOtherBenefits = $employee->getNonTaxableOtherBenefits(
    $payrollPeriod->start_date,
    $payrollPeriod->end_date
);

// Get total other benefits (both taxable and non-taxable)
$totalOtherBenefits = $employee->getTotalOtherBenefits(
    $payrollPeriod->start_date,
    $payrollPeriod->end_date
);
```

### Option 2: Without Date Filter (All Active Benefits)

```php
$employee = Employee::find($employeeId);

// Get all taxable other benefits (no date filter)
$taxableOtherBenefits = $employee->getTaxableOtherBenefits();

// Get all non-taxable other benefits (no date filter)
$nonTaxableOtherBenefits = $employee->getNonTaxableOtherBenefits();

// Get all other benefits
$totalOtherBenefits = $employee->getTotalOtherBenefits();
```

### Option 3: Access Individual Benefits

```php
$employee = Employee::find($employeeId);

// Get collection of active benefit details
$benefits = $employee->activeOtherBenefitDetails()
    ->whereDate('benefit_date', '>=', $startDate)
    ->whereDate('benefit_date', '<=', $endDate)
    ->with('otherBenefit')
    ->get();

foreach ($benefits as $benefitDetail) {
    echo "Benefit: {$benefitDetail->otherBenefit->other_benefit_name}\n";
    echo "Amount: {$benefitDetail->amount}\n";
    echo "Taxable: " . ($benefitDetail->taxable ? 'Yes' : 'No') . "\n";
    echo "---\n";
}
```

---

## Typical Payroll Processing Example

```php
public function processPayroll($employeeId, $payrollPeriodId)
{
    $employee = Employee::find($employeeId);
    $payrollPeriod = PayrollPeriod::find($payrollPeriodId);
    
    // Get other benefits for the period
    $taxableOtherBenefits = $employee->getTaxableOtherBenefits(
        $payrollPeriod->start_date,
        $payrollPeriod->end_date
    );
    
    $nonTaxableOtherBenefits = $employee->getNonTaxableOtherBenefits(
        $payrollPeriod->start_date,
        $payrollPeriod->end_date
    );
    
    // Get allowances from earngroups
    $taxableAllowances = $employee->getTaxableAllowancesFromEarngroups();
    $nonTaxableAllowances = $employee->getNonTaxableAllowancesFromEarngroups();
    
    // Calculate gross salary
    $grossSalary = $employee->basic_salary + $taxableAllowances + $taxableOtherBenefits;
    
    // Create or update payroll record
    $payroll = Payroll::updateOrCreate(
        [
            'employee_id' => $employee->id,
            'payroll_period_id' => $payrollPeriod->id,
        ],
        [
            'basic_salary' => $employee->basic_salary,
            'taxable_allowances' => $taxableAllowances,
            'non_taxable_allowances' => $nonTaxableAllowances,
            'taxable_other_benefits' => $taxableOtherBenefits,
            'non_taxable_other_benefits' => $nonTaxableOtherBenefits,
            'gross_salary' => $grossSalary,
            // ... other payroll calculations
        ]
    );
    
    return $payroll;
}
```

---

## Check if Employee Has Benefits

```php
// Check if employee has any active other benefits
$hasBenefits = $employee->activeOtherBenefitDetails()->exists();

// Check if employee has benefits for a specific period
$hasBenefitsInPeriod = $employee->activeOtherBenefitDetails()
    ->whereDate('benefit_date', '>=', $startDate)
    ->whereDate('benefit_date', '<=', $endDate)
    ->exists();

// Count active benefits
$benefitsCount = $employee->activeOtherBenefitDetails()->count();
```

---

## Get Employees with Specific Benefit

```php
// From the benefit detail side
$benefitDetail = OtherBenefitDetail::find($detailId);

// Get all employees with this benefit
$employees = $benefitDetail->employees()->get();

// Get only active employees with this benefit
$activeEmployees = $benefitDetail->activeEmployees()->get();

// Count employees with this benefit
$employeeCount = $benefitDetail->employees()->count();
```

---

## Admin Operations

### Assign Benefit to All Employees
```php
$benefitDetail = OtherBenefitDetail::find($detailId);
$allEmployeeIds = Employee::pluck('id')->toArray();

$syncData = [];
foreach ($allEmployeeIds as $empId) {
    $syncData[$empId] = ['status' => 'active'];
}

$benefitDetail->employees()->sync($syncData);
```

### Assign Benefit to Selected Employees
```php
$benefitDetail = OtherBenefitDetail::find($detailId);
$selectedEmployeeIds = [1, 5, 10, 15];

$syncData = [];
foreach ($selectedEmployeeIds as $empId) {
    $syncData[$empId] = ['status' => 'active'];
}

$benefitDetail->employees()->sync($syncData);
```

### Remove Benefit from Employee
```php
$benefitDetail = OtherBenefitDetail::find($detailId);
$employeeId = 5;

$benefitDetail->employees()->detach($employeeId);
```

### Remove Benefit from All Employees
```php
$benefitDetail = OtherBenefitDetail::find($detailId);
$benefitDetail->employees()->detach();
```

---

## Database Structure

```
employee_other_benefit_details (Pivot Table)
├── employee_id → employees.id
├── other_benefit_detail_id → other_benefit_details.id
└── status (active/inactive)

Relationships:
Employee → (many) → EmployeeOtherBenefitDetail → (many) ← OtherBenefitDetail
```

---

## Key Differences from Old System

### Before (JSON-based)
```php
// ❌ OLD WAY - Don't use this anymore
$detail = OtherBenefitDetail::find($id);
$employeeIds = $detail->employee_ids; // JSON array
$applyToAll = $detail->apply_to_all; // boolean
```

### After (Relational)
```php
// ✅ NEW WAY - Use this
$detail = OtherBenefitDetail::find($id);
$employees = $detail->employees; // Collection of Employee models
$employeeIds = $detail->employees->pluck('id')->toArray(); // Array of IDs
```

---

## Benefits Summary

| Feature | Old System (JSON) | New System (Relational) |
|---------|------------------|------------------------|
| Query Performance | ❌ Slow | ✅ Fast with indexes |
| Data Integrity | ❌ No constraints | ✅ Foreign keys |
| Complex Queries | ❌ Difficult | ✅ Easy with Eloquent |
| Scalability | ❌ Poor | ✅ Excellent |
| Status Management | ❌ All or nothing | ✅ Per-employee status |

---

## See Also

- `OTHER_BENEFITS_IMPLEMENTATION.md` - Full implementation guide
- `OTHER_BENEFITS_RESTRUCTURE_SUMMARY.md` - Summary of changes
- `EMPLOYEE_EARNGROUPS_IMPLEMENTATION.md` - Similar pattern for earngroups

