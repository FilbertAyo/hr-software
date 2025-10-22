# Edit Loan Installments - Active Loans Feature

## Overview
This feature allows you to modify installment amounts and dates for **active loans**, with the important restriction that **paid installments are locked** and cannot be changed. Only pending (unpaid) installments can be modified.

---

## 🎯 Key Features

### 1. **Edit Active Loans**
- Available for both **active** and **pending** loans
- Works even after loan has been activated
- Doesn't require full restructure

### 2. **Paid Installments Protection**
- **Paid installments are locked** (read-only)
- Displayed in a separate section with gray background
- Cannot be modified or deleted
- Shows paid date for reference

### 3. **Pending Installments Editing**
- Fully editable amounts and dates
- Real-time balance validation
- Must equal remaining loan amount
- Color-coded feedback

### 4. **Automatic Tracking**
- Changes recorded in loan history
- Restructure count incremented
- Full audit trail maintained
- Snapshot of old installments saved

---

## 📍 Where to Find It

### Location on Loan Details Page

At the **top right** of any active or pending loan with installments, you'll see:

```
┌────────────────────────────────────────────────────────┐
│  Loan Details                                          │
│                                                        │
│  [Edit Installments] [Restructure] [History] [Back]   │
└────────────────────────────────────────────────────────┘
        ↑ GREEN BUTTON
    Click here!
```

**Button:** **"Edit Installments"** (Green button with edit icon)

### When It Appears

The button shows when:
- ✅ Loan status is **active** OR **pending**
- ✅ Loan has **installments already set up**

### When It Doesn't Show

The button is hidden when:
- ❌ Loan has **no installments yet** (use setup instead)
- ❌ Loan is **completed** or **rejected**

---

## 📋 How to Use

### Step 1: Open Loan Details
1. Go to Loans page
2. Click on an **active** or **pending** loan
3. Look at the top right corner

### Step 2: Click "Edit Installments"
1. Click the **green "Edit Installments"** button
2. You'll see two sections:
   - **Paid Installments** (locked, gray background)
   - **Pending Installments** (editable, white background)

### Step 3: Modify Pending Amounts
1. Only the **pending installments** can be edited
2. Change amounts as needed
3. Update dates if needed
4. Watch the balance indicator

### Step 4: Balance Check
```
Total Pending: Must Equal Remaining Amount
─────────────────────────────────────────
If BALANCED  → Green ✅ Can submit
If UNDER     → Yellow ⚠️ Add more
If OVER      → Red ❌ Reduce amounts
```

### Step 5: Save Changes
1. When balance shows **green**
2. Click "Save Changes" button
3. Changes recorded in history

---

## 🎨 User Interface

### Page Layout

```
┌─────────────────────────────────────────────────────┐
│ LOAN INFORMATION (blue card)                       │
│ Employee | Loan Type | Total | Paid | Remaining    │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│ BALANCE INDICATOR (changes color)                  │
│ Total Pending | Should Equal | Status              │
│ ████████████████████████ 100% ✅                   │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│ PAID INSTALLMENTS (green header, locked)           │
│ #  | Amount      | Due Date    | Paid Date        │
│ 1  | 50,000 🔒  | Oct 2025    | Oct 15, 2025     │
│ 2  | 36,000 🔒  | Nov 2025    | Nov 12, 2025     │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│ PENDING INSTALLMENTS (yellow header, editable)     │
│ #  | Amount ✏️    | Due Date ✏️                    │
│ 3  | [140,000]   | [Dec 2025]                     │
│ 4  | [274,000]   | [Jan 2026]                     │
│                                   [Save Changes]    │
└─────────────────────────────────────────────────────┘
```

---

## ✅ Example Scenario

### Original Loan: 500,000

**Initial Setup:**
```
1. Oct 2025: 50,000  → PAID ✅
2. Nov 2025: 36,000  → PAID ✅
3. Dec 2025: 140,000 → Pending
4. Jan 2026: 274,000 → Pending
```

**You Want to Change:**
```
Keep paid amounts as is (locked)
But change pending amounts:
3. Dec 2025: 200,000 (instead of 140,000)
4. Jan 2026: 214,000 (instead of 274,000)
```

**How to Do It:**
1. Click "Edit Installments" button
2. See installments #1-2 are **gray/locked** (paid)
3. Edit installment #3: Change 140,000 → 200,000
4. Edit installment #4: Change 274,000 → 214,000
5. Balance indicator shows: 414,000 (green ✅)
6. Click "Save Changes"
7. Done! Changes recorded in history

---

## 🔒 Security & Validation

### What's Protected:

1. **Paid Installments Cannot Be Changed**
   ```javascript
   if (installment.status == 'paid') {
       throw 'Cannot modify paid installments';
   }
   ```

2. **Must Equal Remaining Amount**
   ```
   Paid Amount + Pending Total = Original Loan Amount
   86,000 + 414,000 = 500,000 ✅
   ```

3. **Company Isolation**
   - Can only edit loans in your company
   - Authorization checks on every action

4. **Audit Trail**
   - All changes logged in restructure history
   - Old amounts saved in snapshot
   - Who made the change tracked

---

## 📊 Validation Rules

### Required:
- ✅ Total pending must equal remaining amount
- ✅ Each amount must be >= 0
- ✅ Each installment must have a date
- ✅ Can only edit installments belonging to the loan

### Automatic:
- Loan remaining amount recalculated
- Restructure count incremented
- History record created
- Start/end dates updated

### Prohibited:
- ❌ Cannot modify paid installments
- ❌ Cannot submit if unbalanced
- ❌ Cannot edit completed loans

---

## 🆚 Comparison with Other Features

| Feature | Edit Installments | Restructure | Custom Setup |
|---------|------------------|-------------|--------------|
| **When Available** | Active/Pending loans | Active loans only | Pending loans only |
| **Paid Installments** | Locked (kept) | Deleted & recreated | N/A |
| **Pending Installments** | Editable | Deleted & recreated | Created new |
| **Use Case** | Quick amount changes | Complete schedule change | Initial setup |
| **Tracked in History** | Yes | Yes | No (initial) |
| **Speed** | Fast (1-2 min) | Medium (3-5 min) | Medium (2-5 min) |

---

## 💡 Use Cases

### 1. **Adjust Remaining Payments**
Employee's income changed, need to adjust future payments without touching what's already paid.

### 2. **Correct Data Entry Error**
Noticed wrong amount in upcoming installment, fix it quickly.

### 3. **Accommodate Income Fluctuation**
Employee has variable income, adjust pending amounts accordingly.

### 4. **Defer Payment**
Employee needs more time, move one installment's amount to another month.

---

## 📝 Step-by-Step Example

**Scenario:** Employee has 5 installments of 100,000 each. 2 are paid, 3 pending. Need to change pending ones.

### Before Editing:
```
Original Loan: 500,000

✅ 1. Oct 2025: 100,000 - PAID (Oct 15, 2025)
✅ 2. Nov 2025: 100,000 - PAID (Nov 10, 2025)
⏳ 3. Dec 2025: 100,000 - PENDING
⏳ 4. Jan 2026: 100,000 - PENDING
⏳ 5. Feb 2026: 100,000 - PENDING

Paid: 200,000
Remaining: 300,000
```

### Action:
1. Open loan details
2. Click **"Edit Installments"** (green button)
3. Paid installments #1-2 shown as **locked** (gray)
4. Pending installments #3-5 shown as **editable**

### Make Changes:
```
Change #3: 100,000 → 50,000
Change #4: 100,000 → 100,000 (no change)
Change #5: 100,000 → 150,000
```

### Validation:
```
Paid:    200,000 (locked, can't change)
Pending:  50,000 + 100,000 + 150,000 = 300,000 ✅
Total:   200,000 + 300,000 = 500,000 ✅ BALANCED
```

### Result:
```
✅ 1. Oct 2025: 100,000 - PAID (Oct 15, 2025) [unchanged]
✅ 2. Nov 2025: 100,000 - PAID (Nov 10, 2025) [unchanged]
⏳ 3. Dec 2025:  50,000 - PENDING [changed from 100,000]
⏳ 4. Jan 2026: 100,000 - PENDING [unchanged]
⏳ 5. Feb 2026: 150,000 - PENDING [changed from 100,000]

Changes recorded in history ✅
```

---

## 🎓 Best Practices

### 1. **Review Paid Installments First**
- Check what's already been paid
- This amount is locked
- Plan pending amounts around it

### 2. **Use Calculator**
```
Remaining = Loan Amount - Paid Amount
Your pending total must = Remaining
```

### 3. **Watch the Balance Indicator**
- **Green** = Good to go
- **Yellow** = Need more
- **Red** = Too much

### 4. **Consider Future Income**
- Set amounts employee can afford
- Check employee's payroll schedule
- Plan for seasonal variations

### 5. **Document Reason**
- Changes tracked automatically
- Note: "Manual installment amounts modified"
- Check history tab to see changes

---

## 🔄 Integration

### Works With:
- ✅ Loan restructure (can still restructure after editing)
- ✅ Loan history (all edits tracked)
- ✅ Payroll processing
- ✅ Approval workflow

### Updates:
- ✅ Loan remaining amount
- ✅ Monthly payment (average)
- ✅ Start/end dates
- ✅ Restructure count

---

## ⚠️ Important Notes

1. **Paid Installments Are Sacred**
   - Once paid, forever locked
   - No exceptions
   - Financial integrity protected

2. **Must Balance**
   - Submit button disabled until balanced
   - Red/Yellow indicator = can't save
   - Green indicator = ready to save

3. **History Tracking**
   - Every change logged
   - Old amounts saved in snapshot
   - Audit compliance maintained

4. **Automatic Recalculation**
   - Remaining amount updated
   - Average payment recalculated
   - Dates adjusted if changed

---

## 🚨 Troubleshooting

### "Submit button is disabled"
- Check balance indicator
- Total must equal remaining amount exactly
- Add/subtract from pending amounts

### "All installments are gray/locked"
- All have been paid
- Use restructure instead for new schedule
- Or loan is completed

### "Can't find Edit Installments button"
- Check loan has installments set up
- Check loan status is active/pending
- Button won't show for completed/rejected loans

### "Error: Cannot modify paid installments"
- You tried to change a paid one
- System protection working correctly
- Only change pending ones

---

## 📞 Support

**Feature Status:** ✅ Production Ready  
**Version:** 1.0  
**Created:** October 21, 2025

### Quick Reference

**Route:** `/loan/{id}/installments/edit`  
**Button Location:** Loan Details page, top right  
**Button Color:** Green  
**Icon:** Edit (pencil) icon  
**Availability:** Active & Pending loans with installments

---

## 🎉 Summary

The "Edit Installments" feature gives you **flexible control** over loan payments while maintaining **financial integrity** by:

✅ Protecting paid installments  
✅ Allowing pending installment changes  
✅ Real-time balance validation  
✅ Complete audit trail  
✅ Easy-to-use interface  

**Perfect for adjusting payments without full restructure!**

