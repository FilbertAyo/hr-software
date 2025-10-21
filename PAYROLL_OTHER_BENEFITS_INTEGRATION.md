# Payroll & Other Benefits Integration Guide

## Overview
This document explains how Other Benefits are integrated into the payroll processing system. Other Benefits are now automatically included in payroll calculations alongside earngroup allowances.

## How It Works

### During Payroll Processing

When processing payroll for an employee, the system now:

1. **Gets Earngroup Allowances**
   - Taxable allowances from earngroups
   - Non-taxable allowances from earngroups

2. **Gets Other Benefits for the Payroll Period**
   - Taxable other benefits (filtered by payroll period dates)
   - Non-taxable other benefits (filtered by payroll period dates)

3. **Combines Them**
   - `Taxable Allowances = Earngroup Taxable Allowances + Taxable Other Benefits`
   - `Non-Taxable Allowances = Earngroup Non-Taxable Allowances + Non-Taxable Other Benefits`

4. **Calculates Payroll**
   - Gross Salary = Basic Salary + Taxable Allowances
   - Taxable Income = Gross Salary - Pension
   - PAYE Tax = Calculated on Taxable Income
   - Net Salary = Gross Salary - Total Deductions + Non-Taxable Allowances

## Code Implementation

### In PayrollController (processSelected method)

```php
// Get taxable and non-taxable allowances from earngroups
$taxableAllowances = $employee->getTaxableAllowancesFromEarngroups();
$nonTaxableAllowances = $employee->getNonTaxableAllowancesFromEarngroups();

// Get other benefits for this payroll period
$taxableOtherBenefits = $employee->getTaxableOtherBenefits(
    $payrollPeriod->start_date,
    $payrollPeriod->end_date
);
$nonTaxableOtherBenefits = $employee->getNonTaxableOtherBenefits(
    $payrollPeriod->start_date,
    $payrollPeriod->end_date
);

// Combine allowances and other benefits
$taxableAllowances += $taxableOtherBenefits;
$nonTaxableAllowances += $nonTaxableOtherBenefits;
$totalAllowances = $taxableAllowances + $nonTaxableAllowances;

// Calculate gross salary (basic + TAXABLE allowances only)
$grossSalary = $basicSalary + $taxableAllowances;
```

### In Payroll Index View (Preview)

The same logic is applied in the view to show accurate preview before processing:

```php
// Get taxable and non-taxable allowances from earngroups
$taxableAllowances = $employee->getTaxableAllowancesFromEarngroups();
$nonTaxableAllowances = $employee->getNonTaxableAllowancesFromEarngroups();

// Get other benefits for this payroll period
$taxableOtherBenefits = $employee->getTaxableOtherBenefits(
    $payrollPeriod->start_date,
    $payrollPeriod->end_date
);
$nonTaxableOtherBenefits = $employee->getNonTaxableOtherBenefits(
    $payrollPeriod->start_date,
    $payrollPeriod->end_date
);

// Combine allowances and other benefits
$taxableAllowances += $taxableOtherBenefits;
$nonTaxableAllowances += $nonTaxableOtherBenefits;
```

## Database Storage

The payroll record stores the combined values:

```php
$payroll = Payroll::create([
    'employee_id' => $employeeId,
    'payroll_period_id' => $payrollPeriod->id,
    'basic_salary' => $basicSalary,
    'allowances' => $totalAllowances,
    'taxable_allowances' => $taxableAllowances,     // Includes taxable other benefits
    'non_taxable_allowances' => $nonTaxableAllowances, // Includes non-taxable other benefits
    'gross_salary' => $grossSalary,
    'taxable_income' => $taxableIncome,
    'tax_deduction' => $taxDeduction,
    'net_salary' => $netSalary,
    // ... other fields
]);
```

### Payroll Table Columns

| Column | Description |
|--------|-------------|
| `basic_salary` | Employee's base salary |
| `allowances` | Total of all allowances (taxable + non-taxable) |
| `taxable_allowances` | Earngroup allowances + Other benefits (taxable) |
| `non_taxable_allowances` | Earngroup allowances + Other benefits (non-taxable) |
| `gross_salary` | Basic salary + Taxable allowances |
| `taxable_income` | Gross salary - Pension deduction |
| `tax_deduction` | PAYE calculated on taxable income |
| `net_salary` | Gross - Total deductions + Non-taxable allowances |

## Example Calculation

### Employee Data
- Basic Salary: 1,000,000 TZS
- Pension: 100,000 TZS (10%)

### Earngroup Allowances
- Housing Allowance (Taxable): 200,000 TZS
- Transport Allowance (Non-taxable): 50,000 TZS

### Other Benefits (for current payroll period)
- Performance Bonus (Taxable): 100,000 TZS
- Lunch Allowance (Non-taxable): 30,000 TZS

### Calculation

```
1. Combine Allowances:
   Taxable Allowances = 200,000 + 100,000 = 300,000 TZS
   Non-Taxable Allowances = 50,000 + 30,000 = 80,000 TZS
   Total Allowances = 300,000 + 80,000 = 380,000 TZS

2. Calculate Gross Salary:
   Gross Salary = 1,000,000 + 300,000 = 1,300,000 TZS

3. Calculate Taxable Income:
   Taxable Income = 1,300,000 - 100,000 = 1,200,000 TZS

4. Calculate PAYE Tax (assume 15%):
   PAYE = 1,200,000 * 0.15 = 180,000 TZS

5. Calculate Total Deductions:
   Total Deductions = 100,000 + 180,000 = 280,000 TZS

6. Calculate Net Salary:
   Net Salary = 1,300,000 - 280,000 + 80,000 = 1,100,000 TZS
```

## Date Filtering

Other benefits are filtered by the payroll period dates:

- Only benefits with `benefit_date` between `payroll_period.start_date` and `payroll_period.end_date` are included
- This ensures each benefit is only applied to the correct payroll period
- Benefits can be one-time or recurring depending on how they're set up

## Status Management

Only **active** other benefits are included in payroll:

```php
// The helper methods automatically filter by status
$employee->getTaxableOtherBenefits($startDate, $endDate);
// This internally calls:
// $employee->activeOtherBenefitDetails()->where('taxable', true)->...
```

## Testing Checklist

When testing the payroll integration:

- [ ] Create a taxable other benefit and assign to an employee
- [ ] Create a non-taxable other benefit and assign to an employee
- [ ] Process payroll and verify both are included
- [ ] Check that taxable benefits increase gross salary
- [ ] Check that taxable benefits affect PAYE calculation
- [ ] Check that non-taxable benefits are added to net salary but don't affect tax
- [ ] Verify benefits with dates outside payroll period are excluded
- [ ] Verify inactive benefits are excluded
- [ ] Check preview shows correct amounts before processing
- [ ] Reprocess payroll and verify amounts remain consistent

## Impact on Tax Calculation

### Taxable Other Benefits
✅ **Included in gross salary**
✅ **Subject to PAYE tax**
✅ **Affects taxable income**

### Non-Taxable Other Benefits
❌ **NOT included in gross salary**
❌ **NOT subject to PAYE tax**
✅ **Added to net salary after tax calculation**

## Admin Workflow

1. **Create Other Benefit Type**
   - Go to Allowances → Other Benefits
   - Create benefit (e.g., "Performance Bonus")

2. **Assign Benefit to Employees**
   - Go to Allowances → Other Benefits → Details
   - Create assignment:
     - Select benefit type
     - Set amount
     - Set benefit date
     - Choose taxable/non-taxable
     - Assign to all employees or selected employees

3. **Process Payroll**
   - Go to Payroll → Process Payroll
   - Select payroll period
   - Preview shows benefits included in allowances
   - Process payroll
   - Benefits are automatically included in calculations

## Troubleshooting

### Other benefits not showing in payroll

**Check:**
1. Is the benefit assignment status "active"?
2. Is the benefit_date within the payroll period dates?
3. Is the employee assigned to the benefit?
4. Is the employee's status active?

### Amounts don't match expectations

**Check:**
1. Are there multiple benefit assignments for the same employee?
2. Is the benefit marked as taxable/non-taxable correctly?
3. Are pension and other deductions calculated correctly?

### Duplicate benefits in payroll

**Check:**
1. Ensure benefit_date is set correctly
2. Don't assign the same benefit multiple times for the same period
3. Check that old payroll records were deleted before reprocessing

## Files Modified

### Controllers
- `app/Http/Controllers/PayrollController.php`
  - Updated `processSelected()` method to include other benefits

### Views
- `resources/views/payroll/index.blade.php`
  - Updated preview calculation to include other benefits

### Models (Previously Created)
- `app/Models/Employee.php` - Helper methods for getting other benefits
- `app/Models/OtherBenefitDetail.php` - Relationships
- `app/Models/EmployeeOtherBenefitDetail.php` - Pivot model

## Related Documentation

- `OTHER_BENEFITS_IMPLEMENTATION.md` - Complete other benefits guide
- `OTHER_BENEFITS_QUICK_REFERENCE.md` - Quick reference for common operations
- `EMPLOYEE_EARNGROUPS_IMPLEMENTATION.md` - Earngroups documentation
- `PAYE_IMPLEMENTATION.md` - PAYE tax calculation guide

## Benefits of This Integration

1. ✅ **Automatic** - No manual calculation needed
2. ✅ **Accurate** - Date-based filtering ensures correct period
3. ✅ **Flexible** - Supports both taxable and non-taxable benefits
4. ✅ **Consistent** - Same logic in preview and processing
5. ✅ **Auditable** - All benefits stored and tracked in database
6. ✅ **Simple API** - Easy helper methods for developers

