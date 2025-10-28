# Payslip Report Implementation

## Overview
A comprehensive payslip report feature that allows filtering by multiple criteria and generates detailed payslips for employees.

## Features Implemented

### 1. Filter Options
- **Payroll Period** (Required): Select the specific payroll period for the report
- **Tax Rate**: Filter by specific tax rate or view all tax rates
- **Employee Selection**: Multiple options available:
  - **All Employees**: Generate payslips for all employees
  - **Selected Employees**: Choose specific employees (multi-select)
  - **By Branch (Main Station)**: Filter by branch/main station
  - **By Department**: Filter by specific department

### 2. Payslip Details
Each payslip includes:

#### Employee Information
- Employee Name
- Employee ID
- Department
- Job Title
- Branch (Main Station)
- Tax Rate
- TIN Number
- Payment Date

#### Earnings Section
- Basic Salary
- Taxable Allowances
- Non-Taxable Allowances
- Overtime
- Bonus
- **Gross Salary** (Total)

#### Deductions Section
- PAYE Tax
- Employee Pension
- WCF (Workers Compensation Fund)
- SDL (Skills Development Levy)
- Loan Deduction
- Advance Salary
- Absent/Late Deduction
- Normal Deduction
- Other Deductions
- **Total Deductions**

#### Summary Information
- **Net Salary** (prominently displayed)
- Taxable Income
- Employer Pension
- Status (Pending/Processed/Paid)
- Processed Date
- Notes (if any)

### 3. Summary Cards
Before the payslips, summary cards display:
- Total Employees
- Total Gross Salary
- Total Deductions
- Total Net Salary

### 4. Print Functionality
- Print button available when payslips are loaded
- Print-optimized layout with page breaks between payslips
- Hides filter section when printing

## Files Modified/Created

### 1. Controller
**File**: `app/Http/Controllers/ReportController.php`
- Added `payslipReport()` method
- Implements comprehensive filtering logic
- Handles all filter combinations

### 2. Route
**File**: `routes/web.php`
- Added route: `Route::get('/payslip', [ReportController::class, 'payslipReport'])->name('payslip');`

### 3. View
**File**: `resources/views/reports/payslip.blade.php`
- Complete payslip report interface
- Dynamic filter form with conditional fields
- Professional payslip layout
- Print-ready styling

### 4. Reports Index
**File**: `resources/views/reports/index.blade.php`
- Added "Payslip Report" link card

## Usage Instructions

### Accessing the Report
1. Navigate to Reports section
2. Click on "Payslip Report"

### Generating Payslips
1. **Select Payroll Period** (Required)
2. **Select Tax Rate** (Optional - defaults to "All Tax Rates")
3. **Choose Employee Selection Method**:
   - **All Employees**: No additional selection needed
   - **Selected Employees**: Hold Ctrl/Cmd and click to select multiple employees
   - **By Branch**: Select a main station from dropdown
   - **By Department**: Select a department from dropdown
4. Click "Get Report" button

### Viewing Results
- Summary cards show aggregate data
- Individual payslips display below
- Each payslip shows complete earnings and deductions breakdown

### Printing
- Click the "Print" button in the top right
- Payslips will print with proper page breaks
- Filter section is hidden in print view

## Filter Logic

### Employee Filter Types
```php
'all'        => Get all employees in the selected period
'selected'   => Get only specified employee IDs
'branch'     => Filter by mainstation_id through employee.department
'department' => Filter by department_id through employee.department
```

### Tax Rate Filter
```php
'all'        => No tax rate filtering
[tax_rate_id] => Filter employees with specific tax rate
```

## Database Relationships Used
- `Payroll` → `Employee`
- `Employee` → `EmployeeDepartment` (department)
- `EmployeeDepartment` → `Department`
- `EmployeeDepartment` → `Mainstation`
- `Employee` → `TaxRate`
- `Payroll` → `PayrollPeriod`
- `Payroll` → `PayrollAllowance` (allowanceDetails)
- `Payroll` → `PayrollDeduction` (deductions)

## Styling Features
- Bootstrap 4 responsive layout
- Print-optimized CSS
- Professional payslip design
- Color-coded summary cards
- Status badges
- Page breaks for printing

## JavaScript Functionality
- Dynamic filter field visibility
- Shows/hides conditional filters based on employee selection type
- Maintains selected values on form submission

## Security
- Company-scoped queries (uses session company_id)
- Only shows active employees by default
- Proper relationship constraints

## Future Enhancements (Optional)
- Export to PDF
- Email payslips to employees
- Bulk download as ZIP
- Payslip history comparison
- Custom payslip templates
- Multi-language support

## Testing Checklist
- [ ] Access report from Reports index
- [ ] Select payroll period and generate report
- [ ] Test "All Employees" filter
- [ ] Test "Selected Employees" with multiple selections
- [ ] Test "By Branch" filter
- [ ] Test "By Department" filter
- [ ] Test "Tax Rate" filter
- [ ] Test combinations of filters
- [ ] Verify all payslip details display correctly
- [ ] Test print functionality
- [ ] Verify page breaks in print preview
- [ ] Check responsive layout on mobile
- [ ] Verify summary cards calculations

## Notes
- Payroll period selection is mandatory
- Empty state messages guide users
- All monetary values formatted with 2 decimal places
- Conditional display of zero-value fields
- Company name pulled from employee's company relationship
