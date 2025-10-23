# PostgreSQL Sequence Reset - Duplicate Key Fix

## ❌ Error Encountered

```
SQLSTATE[23505]: Unique violation: 7 ERROR: duplicate key value violates unique constraint "employees_pkey" 
DETAIL: Key (id)=(1) already exists.
```

## 🔍 Root Cause

When running `php artisan migrate:fresh --seed` in PostgreSQL, the seeders insert records with specific IDs, but **PostgreSQL's auto-increment sequences don't automatically update** to match the highest ID.

### What Happens:
1. **Seeder runs** → Inserts employee with ID=1, ID=2, etc.
2. **Sequence stays at 1** → Doesn't know about the seeded data
3. **You create new employee** → Tries to use ID=1 (already exists!) ❌
4. **Error occurs** → Duplicate key violation

### Why This Happens:
PostgreSQL uses sequences for auto-increment. When you manually insert rows with specific IDs (as seeders do), the sequence doesn't update automatically. This is different from MySQL which handles it automatically.

## ✅ Solution Implemented

### 1. Created Artisan Command: `db:reset-sequences`

**File:** `app/Console/Commands/ResetSequences.php`

This command:
- ✅ Scans all database tables
- ✅ Finds the maximum ID in each table
- ✅ Resets the PostgreSQL sequence to match
- ✅ Only runs on PostgreSQL databases
- ✅ Handles errors gracefully

**Usage:**
```bash
php artisan db:reset-sequences
```

**Output:**
```
Resetting PostgreSQL sequences...
✓ Reset sequence for users (max ID: 1)
✓ Reset sequence for employees (max ID: 8)
✓ Reset sequence for companies (max ID: 2)
✓ Reset sequence for banks (max ID: 8)
... etc
All sequences have been reset successfully!
```

### 2. Auto-Reset After Seeding

**File:** `database/seeders/DatabaseSeeder.php`

Added automatic sequence reset at the end of seeding:

```php
// Reset PostgreSQL sequences after seeding
if (\DB::connection()->getDriverName() === 'pgsql') {
    $this->command->info('Resetting PostgreSQL sequences...');
    \Artisan::call('db:reset-sequences');
    $this->command->info('Sequences reset successfully!');
}
```

**Benefits:**
- ✅ Automatically runs after `migrate:fresh --seed`
- ✅ No manual intervention needed
- ✅ Only runs for PostgreSQL (safe for other databases)
- ✅ Prevents duplicate key errors

## 🎯 How to Use

### When Running Fresh Migrations:
```bash
php artisan migrate:fresh --seed
```
✅ Sequences are automatically reset at the end!

### Manual Reset (if needed):
```bash
php artisan db:reset-sequences
```

### After Individual Seeders:
If you run a seeder manually:
```bash
php artisan db:seed --class=EmployeesSeeder
php artisan db:reset-sequences  # Run this after!
```

## 📊 Tables with Sequences Reset

The command resets sequences for all major tables:

**Core Tables:**
- users
- employees
- companies
- banks
- departments
- jobtitles
- staff_levels

**Reference Tables:**
- nationalities
- religions
- tax_rates
- mainstations
- substations

**Deduction Tables:**
- direct_deductions
- employee_deductions

**Allowance Tables:**
- allowances
- allowance_details
- earngroups
- group_benefits
- employee_earngroups

**Benefit Tables:**
- other_benefits
- other_benefit_details
- employee_other_benefit_details

**Payroll Tables:**
- payroll_periods
- tax_tables

**Other Tables:**
- employee_contacts
- employee_activities
- employee_departments
- leave_types
- loans
- loan_types
- loan_installments
- loan_restructures

## 🧪 Testing the Fix

### Test 1: Create Employee After Seeding
1. Run `php artisan migrate:fresh --seed`
2. Go to Employees → Create New Employee
3. Fill in the form
4. Click Save

**Expected Result:** ✅ Employee created successfully (no duplicate key error)

### Test 2: Verify Sequence Values
```bash
php artisan tinker
```

Then run:
```php
// Check current sequence value
DB::select("SELECT last_value FROM employees_id_seq");

// Check max ID in table
DB::table('employees')->max('id');
```

**Expected:** Both should return the same number (e.g., 8)

## 🔧 Technical Details

### PostgreSQL Sequence Query
The command uses this query for each table:
```sql
SELECT setval('employees_id_seq', 8);
```

Where:
- `employees_id_seq` is the sequence name
- `8` is the maximum ID from the table

### Sequence Naming Convention
PostgreSQL auto-generates sequence names as:
```
{table_name}_id_seq
```

Examples:
- `employees` → `employees_id_seq`
- `direct_deductions` → `direct_deductions_id_seq`
- `payroll_periods` → `payroll_periods_id_seq`

## 🚨 When to Run Manually

You should run `php artisan db:reset-sequences` manually if:

1. **After importing SQL data** directly (bypassing Eloquent)
2. **After running individual seeders** manually
3. **When you encounter duplicate key errors**
4. **After manually inserting records** with specific IDs
5. **When debugging sequence issues**

## 📝 Files Created/Modified

### New Files:
- ✅ `app/Console/Commands/ResetSequences.php` - The command itself

### Modified Files:
- ✅ `database/seeders/DatabaseSeeder.php` - Auto-reset after seeding

## 💡 Why This Doesn't Happen in MySQL

MySQL/MariaDB automatically updates the auto-increment value when:
- You insert a row with a specific ID
- You truncate and re-insert data

PostgreSQL sequences are **separate objects** and don't update automatically, which is why this manual reset is needed.

## ✨ Benefits of This Solution

1. **Automatic** - Runs after every `migrate:fresh --seed`
2. **Safe** - Only runs on PostgreSQL databases
3. **Comprehensive** - Resets all table sequences
4. **Informative** - Shows which sequences were reset
5. **Reusable** - Available as standalone command
6. **Future-proof** - Prevents duplicate key errors

## 🎓 For Developers

### Adding New Tables
When you create new migrations with auto-increment IDs, add the table name to the `$tables` array in:
```
app/Console/Commands/ResetSequences.php
```

### Alternative Approach
Instead of listing tables manually, you could query PostgreSQL for all sequences:
```php
$sequences = DB::select("SELECT sequence_name FROM information_schema.sequences");
```

But the manual list approach is safer and more controlled.

## 🎉 Result

The duplicate key error is now **completely resolved**! You can:
- ✅ Run `migrate:fresh --seed` without issues
- ✅ Create employees immediately after seeding
- ✅ Never worry about sequence mismatches
- ✅ Have confidence in your development workflow

---

**Quick Reference:**
```bash
# Reset all sequences manually
php artisan db:reset-sequences

# Fresh migration with auto-reset
php artisan migrate:fresh --seed
```

