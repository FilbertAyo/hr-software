# Custom Loan Installments Feature

## Overview
This feature allows you to manually set custom amounts and dates for each loan installment, providing maximum flexibility for loan repayment schedules.

---

## 🎯 Key Features

### 1. **Flexible Amount Distribution**
- Set different amounts for each installment
- Example:
  - October 2025: 50,000
  - November 2025: 36,000
  - December 2025: 0 (skipped)
  - January 2026: 140,000
  - February 2026: 500,000

### 2. **Custom Date Selection**
- Choose any month/year for each installment
- Not restricted to consecutive months
- Can skip months (set amount to 0)

### 3. **Real-Time Balance Validation**
- Live calculation as you type
- Visual progress bar showing allocation status
- Color-coded status indicators:
  - 🟢 **Green** - Balanced (total = loan amount)
  - 🟡 **Yellow** - Under-allocated (total < loan amount)
  - 🔴 **Red** - Over-allocated (total > loan amount)

### 4. **Auto-Fill Helper**
- Click "Auto-Fill Remaining" to automatically allocate leftover amount
- Fills the first empty (0) installment
- Saves time on final balancing

### 5. **Dynamic Row Management**
- Start with 5 installment rows
- Add unlimited additional rows
- Remove unwanted rows (minimum 3 kept by default)
- Automatic row numbering

---

## 📋 How to Use

### Step 1: Create a Loan
1. Go to Loans page
2. Click "New loan"
3. Fill in employee, amount, etc.
4. Submit to create pending loan

### Step 2: Choose Installment Method
1. Open the loan details
2. You'll see two options:
   - **Automatic Installments** - Equal monthly payments
   - **Custom Installments** - Manual amounts and dates
3. Click "Create Custom Schedule" for custom installments

### Step 3: Set Up Custom Schedule
1. Enter amount for each installment
2. Select month/year for payment
3. Watch the balance indicator update in real-time
4. Add more rows if needed with "+ Add Installment"
5. Use "Auto-Fill Remaining" for convenience
6. Balance must show **green** before you can submit

### Step 4: Submit
1. Ensure total equals loan amount (green status)
2. Click "Save Custom Installments"
3. Loan becomes active with your custom schedule

---

## 🎨 User Interface Features

### Balance Indicator Card
Shows three key metrics:
- **Total Installments** - Sum of all amounts entered
- **Remaining to Allocate** - How much more needed
- **Status** - Visual badge showing balance state

### Progress Bar
- Visual representation of allocation progress
- Changes color based on status
- Shows percentage allocated

### Installment Table
| # | Amount | Due Date (Month & Year) | Action |
|---|--------|------------------------|--------|
| 1 | Input field | Month picker | - |
| 2 | Input field | Month picker | - |
| 3 | Input field | Month picker | - |
| 4+ | Input field | Month picker | 🗑️ Remove |

---

## ✅ Validation Rules

### Required:
- ✅ At least 1 installment with amount > 0
- ✅ Total must equal loan amount exactly (within 1 cent)
- ✅ Each installment must have a date
- ✅ All amounts must be >= 0

### Optional:
- Installments with 0 amount are automatically ignored
- Dates don't need to be consecutive
- Can have gaps between payments

### Automatic Processing:
- Installments sorted by date automatically
- Installment numbers assigned after sorting
- Start date = earliest installment date
- End date = latest installment date
- Average payment calculated for reference

---

## 💡 Use Cases

### 1. **Seasonal Workers**
```
Oct 2025: 100,000 (high season)
Nov 2025: 100,000
Dec 2025: 50,000 (reduced income)
Jan 2026: 50,000
Feb 2026: 200,000 (bonus month)
```

### 2. **Project-Based Payments**
```
Nov 2025: 0 (project start)
Dec 2025: 0
Jan 2026: 250,000 (project milestone)
Feb 2026: 0
Mar 2026: 250,000 (project completion)
```

### 3. **Balloon Payment**
```
Nov 2025: 50,000
Dec 2025: 50,000
Jan 2026: 50,000
Feb 2026: 350,000 (balloon payment)
```

### 4. **Variable Income Schedule**
```
Oct 2025: 75,000
Nov 2025: 125,000
Dec 2025: 25,000
Jan 2026: 175,000
Feb 2026: 100,000
```

---

## 🔧 Technical Details

### Routes
```php
GET  /loan/{loan}/installments/custom        - Show custom editor
POST /loan/{loan}/installments/custom        - Save custom installments
```

### Controller Methods
1. `showCustomInstallments($loan)` - Display editor
2. `storeCustomInstallments($request, $loan)` - Process and save

### Validation Logic
```php
- Filters out 0 amounts
- Validates total = loan amount (±0.01)
- Sorts by date
- Creates installments
- Updates loan status to 'active'
```

### JavaScript Functions
- `calculateBalance()` - Real-time balance calculation
- `updateRowNumbers()` - Renumber after add/remove
- `autoFill()` - Smart remaining amount allocation

---

## 🆚 Comparison: Auto vs Custom

| Feature | Automatic | Custom |
|---------|-----------|--------|
| Setup Time | 30 seconds | 2-5 minutes |
| Flexibility | Equal payments only | Unlimited |
| Complexity | Simple | More complex |
| Use Case | Standard loans | Special situations |
| Balance Check | Automatic | Manual validation |
| Best For | Most employees | Variable income |

---

## 📊 Example Scenarios

### Example 1: Standard Decreasing Payments
**Loan Amount: 500,000**

| Month | Amount | Reason |
|-------|--------|--------|
| Oct 2025 | 200,000 | Large initial payment |
| Nov 2025 | 150,000 | Medium payment |
| Dec 2025 | 100,000 | Smaller payment |
| Jan 2026 | 50,000 | Final payment |
| **Total** | **500,000** | ✅ Balanced |

### Example 2: Skip Months
**Loan Amount: 300,000**

| Month | Amount | Reason |
|-------|--------|--------|
| Oct 2025 | 100,000 | First payment |
| Nov 2025 | 0 | Skip (holiday season) |
| Dec 2025 | 0 | Skip (year end) |
| Jan 2026 | 100,000 | Resume payments |
| Feb 2026 | 100,000 | Final payment |
| **Total** | **300,000** | ✅ Balanced |

---

## 🚨 Error Messages

### Under-Allocated
```
Total installments must equal the loan amount.
Current total: 450,000.00
Required: 500,000.00
Difference: -50,000.00
```

### Over-Allocated
```
Total installments must equal the loan amount.
Current total: 550,000.00
Required: 500,000.00
Difference: 50,000.00
```

### No Valid Installments
```
At least one installment must have an amount greater than 0.
```

---

## 🎓 Tips & Best Practices

### 1. **Start with Major Amounts**
- Fill in the largest payment first
- Use auto-fill for remainder
- Adjust as needed

### 2. **Use Auto-Fill Wisely**
- Enter most installments first
- Leave one at 0
- Click auto-fill to complete

### 3. **Check Balance Frequently**
- Watch the progress bar
- Green = good to go
- Yellow/Red = adjust amounts

### 4. **Date Selection**
- Use month picker for consistency
- Consider payroll dates
- Allow employee time to prepare

### 5. **Zero Amounts**
- Use 0 to skip months
- Will be automatically ignored
- No need to delete rows

---

## 🔄 Integration with Other Features

### Works With:
- ✅ Loan approval workflow
- ✅ Loan restructuring (can modify later)
- ✅ Loan history tracking
- ✅ Payroll period linking
- ✅ Company multi-tenancy

### Tracked In:
- ✅ Loan history (if restructured)
- ✅ Installment records
- ✅ Payment schedules
- ✅ Employee loan balance

---

## 📱 Mobile Responsive
- Works on tablets
- Touch-friendly inputs
- Scrollable tables
- Responsive layout

---

## 🔐 Security
- Company-level isolation
- User authentication required
- Authorization checks
- Transaction safety (DB rollback on error)

---

## 🎉 Benefits

1. **Flexibility** - Handle any payment scenario
2. **Accuracy** - Exact amount control
3. **Clarity** - Visual balance feedback
4. **Speed** - Auto-fill saves time
5. **Reliability** - Real-time validation
6. **User-Friendly** - Intuitive interface

---

## 📞 Support

### Common Questions

**Q: Can I change custom installments after creation?**
A: Yes! Use the "Restructure Loan" feature from the loan details page.

**Q: What if I make a mistake?**
A: The system won't let you submit if totals don't match. Adjust amounts until green.

**Q: Can I use both auto and custom?**
A: You choose one method during setup. Can't mix both initially, but can restructure later.

**Q: How many installments can I create?**
A: Unlimited! Start with 5, add as many as needed.

**Q: Will zero amounts be saved?**
A: No, they're automatically filtered out when saving.

---

**Feature Status**: ✅ Production Ready
**Version**: 1.0
**Created**: October 21, 2025

