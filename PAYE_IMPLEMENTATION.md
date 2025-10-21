# PAYE Tax Calculation Implementation

## Overview
This document describes the implementation of PAYE (Pay As You Earn) tax calculations for the HR payroll system, based on Tanzania Revenue Authority (TRA) tax structure.

## Changes Made

### 1. Updated Models

#### TaxRate Model (`app/Models/TaxRate.php`)
- Updated fillable fields to match migration schema: `tax_name`, `rate`, `description`
- Added relationships:
  - `taxTables()` - hasMany relationship to TaxTable
  - `employees()` - hasMany relationship to Employee
- Added tax calculation methods:
  - `calculateTax($taxableIncome)` - Main method to calculate tax based on tax type
  - `calculateProgressiveTax($taxableIncome)` - Private method for progressive tax calculation (PRIMARY)

**Tax Types Supported:**
- **PRIMARY**: Uses progressive tax brackets from `tax_tables` (Tanzania PAYE structure)
- **SECONDARY**: Flat 30% rate for secondary employment
- **DIRECTOR'S FEE (NON FULL TIME)**: Flat 15% withholding tax
- **NON-RESIDENT**: Flat 15% rate
- **CONSULTANT**: Flat 5% withholding tax

#### TaxTable Model (`app/Models/TaxTable.php`)
- Updated fillable fields to match migration schema: `tax_rate_id`, `min_income`, `max_income`, `rate_percentage`, `fixed_amount`
- Added relationship:
  - `taxRate()` - belongsTo relationship to TaxRate
- Added helper methods:
  - `getTaxBracketsForRate($taxRateId)` - Get ordered tax brackets for a specific tax rate
  - `calculatePAYE($taxableIncome)` - Convenience method for PRIMARY tax calculation

### 2. Updated PayrollController (`app/Http/Controllers/PayrollController.php`)

#### Added TaxRate Import
```php
use App\Models\TaxRate;
```

#### Added PAYE Calculation Method
```php
private function calculatePAYE(Employee $employee, $taxableIncome)
```

**Features:**
- Checks if employee is PAYE exempt (`paye_exempt` field)
- Uses employee's assigned `tax_rate_id` or defaults to PRIMARY
- Calculates tax using the appropriate tax rate's calculation method
- Returns rounded tax amount (2 decimal places)

#### Updated Processing Logic
- Modified `processSelected()` method to:
  - Eager load employee's `taxRate` relationship
  - Calculate PAYE tax using the new `calculatePAYE()` method
  - Include PAYE in `total_deductions`
  - Store PAYE amount in `tax_deduction` field

### 3. Updated Payroll Index View (`resources/views/payroll/index.blade.php`)

#### Added PAYE Variable
- Added `$payeTax` variable in the PHP block that calculates payroll preview
- For processed payroll: Uses `$payroll->tax_deduction`
- For unprocessed payroll: Shows 0.00 (tax calculated upon processing)

#### Updated Table Display
- Changed PAYE column from hardcoded `0.0` to `{{ number_format($payeTax, 2) }}`

### 4. Fixed Migration Bug (`database/migrations/2024_01_01_000004_create_reference_tables.php`)
- Fixed foreign key in `tax_tables` table
- Changed from: `references('id')->on('tax_tables')`
- Changed to: `references('id')->on('tax_rates')`

## Tanzania PAYE Tax Brackets (PRIMARY)

The system uses the following progressive tax structure (as seeded in `TaxTableSeeder`):

| Income Range (TZS)      | Rate | Fixed Amount |
|------------------------|------|--------------|
| 0 - 270,000           | 0%   | 0            |
| 270,001 - 520,000     | 8%   | 0            |
| 520,001 - 760,000     | 20%  | 20,000       |
| 760,001 - 1,000,000   | 25%  | 68,000       |
| 1,000,001+            | 30%  | 128,000      |

## How PAYE is Calculated

### For PRIMARY Tax Rate (Progressive)
The system finds the appropriate tax bracket and applies the formula:
```
PAYE = (Income in Bracket × Rate) + Fixed Amount
```

Example: Employee with taxable income of TZS 800,000
- Falls in bracket: 760,001 - 1,000,000
- Calculation: ((800,000 - 760,000) × 25%) + 68,000 = 78,000

### For Flat Rate Tax Types (SECONDARY, CONSULTANT, etc.)
```
PAYE = Taxable Income × (Rate / 100)
```

Example: Consultant with taxable income of TZS 500,000
- Calculation: 500,000 × 5% = 25,000

## Salary Calculation Flow

### 1. Gross Salary
```
Gross Salary = Basic Salary + Allowances (Housing + Transport + Medical)
```

### 2. Taxable Income (for PAYE calculation)
```
Taxable Income = Gross Salary - Pension Contribution
```
*Note: Pension is deducted BEFORE calculating PAYE to reduce the tax burden*

### 3. PAYE Calculation
PAYE is calculated on the Taxable Income (see tax brackets below)

### 4. Total Deductions
```
Total Deductions = Pension + PAYE + Insurance + Loans + Other Deductions + Advance Salary
```
**Important**: Pension is included in total deductions as it's deducted from the employee's salary

### 5. Net Salary
```
Net Salary = Gross Salary - Total Deductions
```

## Employee Tax Configuration

Each employee has a `tax_rate_id` field in the `employees` table that determines which tax rate applies:
- If set: Uses the assigned tax rate
- If NULL: Defaults to PRIMARY (progressive PAYE)
- If `paye_exempt = true`: No tax is calculated (PAYE = 0)

## Testing the Implementation

### Prerequisites
1. Run migrations (if you haven't already):
   ```bash
   php artisan migrate:fresh
   ```

2. Seed the tax tables:
   ```bash
   php artisan db:seed --class=TaxTableSeeder
   ```

### Test Scenarios

1. **Test PRIMARY Tax Rate**
   - Create/edit an employee with `tax_rate_id` = PRIMARY (or NULL)
   - Set basic salary and allowances
   - Process payroll
   - Verify PAYE is calculated using progressive brackets

2. **Test SECONDARY Tax Rate**
   - Create/edit an employee with `tax_rate_id` = SECONDARY
   - Process payroll
   - Verify PAYE is 30% of taxable income

3. **Test PAYE Exempt**
   - Create/edit an employee with `paye_exempt = true`
   - Process payroll
   - Verify PAYE = 0.00

4. **Test with Pension**
   - Create/edit an employee with pension details
   - Set `employee_pension_amount`
   - Process payroll
   - Verify taxable income = gross salary - pension
   - Verify PAYE is calculated on reduced taxable income

## Database Schema

### tax_rates Table
```
- id (bigint)
- tax_name (string)
- rate (decimal 5,2)
- description (text, nullable)
- timestamps
```

### tax_tables Table
```
- id (bigint)
- tax_rate_id (bigint, foreign key to tax_rates)
- min_income (decimal 15,2)
- max_income (decimal 15,2, nullable)
- rate_percentage (decimal 5,2)
- fixed_amount (decimal 15,2)
- timestamps
```

### employees Table (relevant fields)
```
- tax_rate_id (bigint, nullable, foreign key to tax_rates)
- paye_exempt (boolean)
- basic_salary (decimal 15,2)
- housing_allowance (decimal 15,2)
- transport_allowance (decimal 15,2)
- medical_allowance (decimal 15,2)
- pension_details (boolean)
- employee_pension_amount (decimal 15,2)
```

### payrolls Table (relevant fields)
```
- gross_salary (decimal 15,2)
- pension_amount (decimal 15,2)
- taxable_income (decimal 15,2)
- tax_deduction (decimal 15,2) -- PAYE amount
- total_deductions (decimal 15,2)
- net_salary (decimal 15,2)
```

## Notes

1. **Rounding**: All PAYE calculations are rounded to 2 decimal places
2. **Negative Tax**: The system ensures PAYE is never negative (minimum 0)
3. **Missing Tax Rate**: If an employee's tax rate is not found, PAYE defaults to 0
4. **Advance Salary**: Advance salary is NOT included in taxable income; it's deducted from net salary
5. **Pension**: Employee pension contribution is deducted BEFORE calculating PAYE

## Future Enhancements

Potential improvements to consider:
1. Add tax relief/allowances configuration
2. Support for tax exemption thresholds
3. Historical tax rate tracking (for different tax years)
4. Tax report generation for TRA submission
5. Support for additional deductions that affect taxable income
6. Employee tax certificate generation (P9 forms)

