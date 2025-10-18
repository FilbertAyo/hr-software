# Fresh Database Migration Guide

## Overview
This guide explains the fresh database migration that consolidates multiple related tables into fewer, more efficient tables.

## What Changed

### Before (71 separate tables)
The original database had many separate tables for employee details:
- `employee_salary_details`
- `employee_bank_details`
- `employee_pension_details`
- `employee_nhif_details`
- `employee_overtime_details`
- `employee_timing_details`
- `employee_payment_details`
- `employee_guarantors`
- `employee_next_of_kin`
- `employee_qualifications`
- `employee_leave_details`
- `employee_deduction_details`
- `employee_absent_details`
- `employee_late_details`
- And many more...

### After (6 consolidated migration files)
The new structure consolidates related tables:

#### 1. `employees` table (2024_01_01_000001)
**Consolidated from:** `employee_salary_details`, `employee_bank_details`, `employee_pension_details`, `employee_nhif_details`, `employee_overtime_details`, `employee_timing_details`, `employee_payment_details`

**Contains:**
- Basic employee information
- Salary details (basic salary, allowances, advance options)
- Bank details (primary bank, account number)
- Pension details (pension amounts, pension number)
- NHIF details (NHIF settings and amounts)
- Overtime details (rates and timing)
- Timing details (office timing, biometrics)
- Payment details (payment settings)

#### 2. `employee_contacts` table (2024_01_01_000002)
**Consolidated from:** `employee_guarantors`, `employee_next_of_kin`, `employee_qualifications`

**Contains:**
- Contact type (guarantor, next_of_kin, qualification)
- Common fields (name, phone, email, address, relationship)
- Qualification-specific fields (institution, dates, grade)
- Guarantor-specific fields (occupation, employer)
- Next of kin specific fields (priority level)

#### 3. `employee_activities` table (2024_01_01_000003)
**Consolidated from:** `employee_leave_details`, `employee_deduction_details`, `employee_absent_details`, `employee_late_details`

**Contains:**
- Activity type (leave, deduction, absent, late)
- Common fields (date, reason, notes, status, approval)
- Leave-specific fields (leave type, days, dates)
- Deduction-specific fields (HESLB info, amounts, percentages)
- Absent-specific fields (absent flag)
- Late-specific fields (late time, expected time)

#### 4. Reference tables (2024_01_01_000004)
**Consolidated from:** All lookup/reference tables

**Contains:**
- Skills, Languages, Education, Banks
- Relations, Departments, Occupations
- Supervisors, Reportings, Earning Groups
- Job Titles, Pay Grades, Stations
- Nationalities, Staff Levels, Tax Tables
- Loan Types, Leave Types, Formulas
- Payments, Religions, Holidays
- Terminations, SDLs

#### 5. Core tables (2024_01_01_000005)
**Contains:**
- Companies, Direct Deductions
- Loans, Loan Installments, Advances
- Employee Departments, Payroll Periods
- Payrolls, Payroll Deductions/Allowances
- Allowances, Allowance Details
- Leaves, Company Users

#### 6. Evaluation tables (2024_01_01_000006)
**Contains:**
- General Factors, Factors, Sub Factors
- Rating Scales, Rating Scale Items
- Evaluations, Employee Evaluations
- Employee Evaluation Details

## Benefits of Consolidation

1. **Reduced Complexity**: Fewer tables to manage and maintain
2. **Better Performance**: Fewer JOINs required for common queries
3. **Easier Maintenance**: Related data is stored together
4. **Simplified Relationships**: Clearer foreign key relationships
5. **Reduced Redundancy**: Eliminated duplicate fields across tables

## Migration Process

### Step 1: Backup Current Database
```bash
# Create a backup before proceeding
php artisan backup:run
```

### Step 2: Run Fresh Migration
```bash
# Remove old migration files and run fresh migration
php fresh_migration.php

# Or manually run:
php artisan migrate:fresh --seed
```

### Step 3: Update Models
The following models need to be updated to match the new table structure:
- `Employee.php` - Update relationships and fillable fields
- `EmployeeContact.php` - New model for employee_contacts table
- `EmployeeActivity.php` - New model for employee_activities table
- Remove old models that are no longer needed

### Step 4: Update Controllers
Update controllers to work with the new consolidated tables:
- `EmployeeController.php`
- `PayrollController.php`
- Any other controllers that reference the old tables

### Step 5: Update Views
Update Blade templates to work with the new table structure:
- Employee forms
- Payroll views
- Any other views that display employee data

## Data Migration Notes

### Employee Data
- All employee detail data will be consolidated into the main `employees` table
- The `employee_id` foreign key is maintained across all related tables
- No data loss occurs during consolidation

### Contact Data
- Guarantors, next of kin, and qualifications are now stored in `employee_contacts`
- Use the `contact_type` field to distinguish between different types
- Multiple records per employee are supported

### Activity Data
- Leave, deduction, absent, and late records are now in `employee_activities`
- Use the `activity_type` field to distinguish between different activities
- Historical data is preserved

## Testing the Migration

After running the migration:

1. **Check Table Structure**:
   ```bash
   php artisan tinker
   Schema::getColumnListing('employees')
   ```

2. **Test Data Insertion**:
   ```bash
   php artisan tinker
   Employee::create([...])
   ```

3. **Test Relationships**:
   ```bash
   php artisan tinker
   $employee = Employee::with('contacts', 'activities')->first()
   ```

## Rollback Plan

If you need to rollback:

1. Restore from backup
2. Or run the original migrations in reverse order
3. The cleanup migration (2024_01_01_000007) will drop all consolidated tables

## Support

If you encounter any issues during migration:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify database connection settings
3. Ensure all required dependencies are installed
4. Check for any custom code that might reference old table names

## Next Steps

After successful migration:

1. Update all model relationships
2. Update controller methods
3. Update view templates
4. Test all functionality
5. Update documentation
6. Deploy to production

---

**Important**: Always test the migration in a development environment before applying to production!
