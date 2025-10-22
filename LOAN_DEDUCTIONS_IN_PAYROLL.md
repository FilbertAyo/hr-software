# Loan Deductions in Payroll Processing

## Overview
This document describes the implementation of automatic loan installment deductions during payroll processing.

## Implementation Details

### 1. **Payroll Processing Flow**

When payroll is processed for a period, the system now:

1. **Calculates loan deductions** by finding all pending loan installments due within the payroll period
2. **Adds loan deductions** to the total deductions (along with pension, PAYE, advance, etc.)
3. **Marks installments as paid** after successful payroll processing
4. **Updates loan remaining amount** by subtracting the paid installment amount
5. **Auto-completes loans** when all installments are paid

### 2. **Key Changes**

#### **PayrollController.php**
- Added imports for `Loan` and `LoanInstallment` models
- Added `calculateLoanDeduction()` method to calculate total loan deductions for a period
- Added `markLoanInstallmentsAsPaid()` method to mark installments as paid and update loan status
- Added `resetLoanInstallments()` method to reverse payments when payroll is cancelled
- Updated `processSelected()` to calculate and include loan deductions
- Updated `destroy()` to reset loan installments when payroll is cancelled
- Added eager loading of `loans.installments` to prevent N+1 queries

#### **payroll/index.blade.php**
- Added "Loan Deduction" column to the payroll table
- Added loan deduction calculation in the preview (before processing)
- Updated total deductions to include loan amounts

#### **LoanInstallment.php**
- Added proper date and decimal casts for better data handling

### 3. **Loan Deduction Calculation Logic**

```php
// For each employee, the system:
1. Gets all active/approved loans for the employee
2. Finds pending installments with due_date within the payroll period
3. Sums up the installment amounts
4. Returns total loan deduction amount
```

### 4. **Installment Payment Flow**

**When Payroll is Processed:**
```
1. Find pending installments due in the period
2. Mark each installment as 'paid' with paid_date = now()
3. Decrease loan remaining_amount by installment amount
4. Check if all installments are paid
5. If yes, mark loan as 'completed'
```

**When Payroll is Cancelled/Reprocessed:**
```
1. Find paid installments that were due in the period
2. Mark each installment back to 'pending' with paid_date = null
3. Increase loan remaining_amount by installment amount
4. If loan was completed, change status back to 'active'
5. (If reprocessing) Calculate loan deductions again and mark as paid again
```

**Note on Reprocessing:**
When you process payroll again for the same period:
- The system first resets the loan installments to 'pending'
- Then recalculates the loan deduction (same amount as before)
- Marks the same installments as paid again
- **Result:** The loan_deduction amount remains the same, no duplication occurs
- **Exception:** If a loan was cancelled between processing, it won't be included

### 5. **Payroll Deduction Components**

The total deductions now include:
- **Pension Amount** - Employee pension contribution
- **PAYE Tax** - Income tax based on tax rate
- **Insurance Deduction** - Insurance deductions (if any)
- **Loan Deduction** - Sum of loan installments due in the period ✨ **NEW**
- **Other Deductions** - Miscellaneous deductions
- **Advance Salary** - Approved salary advances

**Formula:**
```
Total Deductions = Pension + PAYE + Insurance + Loan + Other + Advance
Net Salary = Gross Salary - Total Deductions + Non-Taxable Allowances
```

### 6. **Database Structure**

**loan_installments table:**
- `loan_id` - Foreign key to loans table
- `installment_number` - Sequential number of installment
- `amount` - Installment amount
- `due_date` - When the installment is due
- `paid_date` - When the installment was paid (null if pending)
- `status` - 'pending', 'paid', or 'overdue'

**loans table (relevant fields):**
- `remaining_amount` - Updated when installments are paid/reversed
- `status` - 'pending', 'active', 'completed', 'cancelled'

### 7. **Key Features**

✅ **Automatic Deduction** - Loan installments are automatically deducted during payroll processing
✅ **Preview Support** - Shows estimated loan deductions before processing
✅ **Transaction Safety** - Uses database transactions to ensure data integrity
✅ **Reversible** - Cancelling payroll reverses the installment payments
✅ **Auto-completion** - Loans are marked as completed when all installments are paid
✅ **Performance Optimized** - Uses eager loading to prevent N+1 queries

### 8. **Usage**

**Processing Payroll:**
1. Select a payroll period
2. View employees with their loan deductions in the preview
3. Click "Process All" or select specific employees
4. System automatically:
   - Calculates loan deductions
   - Includes them in total deductions
   - Marks installments as paid
   - Updates loan remaining amounts

**Cancelling Payroll:**
1. Click cancel on a processed payroll
2. System automatically:
   - Resets installments to pending
   - Restores loan remaining amounts
   - Reactivates completed loans if needed

### 9. **Example Scenario**

**Employee:** John Doe
**Payroll Period:** January 1-31, 2025
**Active Loans:** 
- Car Loan: 1 installment of 200,000 TZS due on Jan 15
- Personal Loan: 1 installment of 100,000 TZS due on Jan 20

**Calculation:**
```
Loan Deduction = 200,000 + 100,000 = 300,000 TZS
Total Deductions = Pension + PAYE + Loan + ... = X + Y + 300,000 + ...
Net Salary = Gross Salary - Total Deductions + Non-Taxable Allowances
```

**After Processing:**
- Both installments marked as 'paid'
- Car Loan remaining_amount decreased by 200,000
- Personal Loan remaining_amount decreased by 100,000
- If all installments paid, loans marked as 'completed'

## Testing Recommendations

1. **Test with single loan** - Verify installment is deducted and marked as paid
2. **Test with multiple loans** - Verify all installments are included in deduction
3. **Test payroll cancellation** - Verify installments are reset to pending
4. **Test loan completion** - Verify loan status changes to completed when all installments paid
5. **Test period boundaries** - Verify only installments due within period are deducted
6. **Test preview calculations** - Verify loan deductions show correctly before processing
7. **Test reprocessing payroll** - Process, then reprocess same period, verify:
   - Same loan deduction amount
   - No duplication in total deductions
   - Installments properly reset and re-marked as paid
   - Loan remaining_amount is correct (not double-deducted)
8. **Test cancelled loan during reprocessing** - Process payroll, cancel a loan, reprocess payroll, verify the cancelled loan is not included

## Notes

- Only **pending** installments with `due_date` within the payroll period are deducted
- Only loans with status **'active'** or **'approved'** are considered
- Loan deductions are **not taxable** - they don't affect PAYE calculation
- The system handles multiple loans per employee automatically

### Important: Reprocessing Payroll

When you reprocess payroll for the same period:
1. **Installments are reset first** - Marked back to 'pending' status
2. **Same amount is calculated** - The same installments are found and summed
3. **No duplication** - The loan_deduction amount stays the same
4. **Remaining amount is restored** - Then decremented again with the same value
5. **Cancelled loans excluded** - If a loan was cancelled, it won't be included in recalculation

**Example:**
- First processing: Loan deduction = 300,000 TZS → Installments marked as paid
- Reprocessing: Installments reset to pending → Loan deduction = 300,000 TZS again → Installments marked as paid again
- **Total deductions remain accurate** - No duplicate amounts added

