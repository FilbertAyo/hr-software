# Quick Reference: Family & Guarantor Implementation

## Files Created/Modified

### Models
- ✅ `app/Models/EmployeeFamily.php` - NEW
- ✅ `app/Models/EmployeeGuarantor.php` - UPDATED
- ✅ `app/Models/Employee.php` - UPDATED (added relationships)

### Controllers
- ✅ `app/Http/Controllers/EmployeeFamilyController.php` - NEW
- ✅ `app/Http/Controllers/EmployeeGuarantorController.php` - NEW
- ✅ `app/Http/Controllers/EmployeeController.php` - UPDATED (added relationships data)

### Migrations
- ✅ `database/migrations/2025_10_27_084826_create_employee_families_table.php` - NEW
- ✅ `database/migrations/2025_10_27_084903_create_employee_guarantors_table.php` - NEW

### Views
- ✅ `resources/views/employees/create.blade.php` - UPDATED (added step 3)
- ✅ `resources/views/employees/edit.blade.php` - UPDATED (added step 3)
- ✅ `resources/views/employees/partials/family.blade.php` - UPDATED (fixed conditionals)
- ✅ `resources/views/employees/partials/gurantor.blade.php` - UPDATED (fixed conditionals)

### Routes
- ✅ `routes/web.php` - UPDATED (added 6 new routes)

## Key Routes

```php
// Family
POST   /employees/{employee}/family
PUT    /employees/{employee}/family/{family}
DELETE /employees/{employee}/family/{family}

// Guarantor
POST   /employees/{employee}/guarantor
PUT    /employees/{employee}/guarantor/{guarantor}
DELETE /employees/{employee}/guarantor/{guarantor}
```

## Database Tables

### employee_families
- `id`, `employee_id`, `first_name`, `middle_name`, `last_name`
- `relationship_id` (FK to relations table)
- `mobile`, `home_mobile`, `email`, `date_of_birth`, `age`
- `postal_address`, `district`, `ward`, `division`, `region`
- `tribe`, `religion`, `attachment`, `is_dependant`
- `created_at`, `updated_at`

### employee_guarantors
- `id`, `employee_id`, `full_name`, `relationship`
- `mobile`, `email`, `occupation`, `id_number`
- `address`, `attachment`
- `created_at`, `updated_at`

## Required Fields

### Family Member
- ✅ first_name
- ✅ last_name
- ✅ relationship (from relations table)
- ✅ mobile

### Guarantor
- ✅ full_name
- ✅ relationship
- ✅ mobile
- ✅ id_number

## Usage Instructions

### 1. Create Employee
Navigate to: `/employees/create`
- Complete Steps 1 & 2
- Step 3 shows info message (family/guarantor added later)
- Submit to create employee

### 2. Add Family/Guarantor
Navigate to: `/employees/{id}/edit`
- Go to Step 3
- Click "Add Family Member" or "Add Guarantor"
- Fill modal form
- Submit to save

### 3. Delete Records
- In Step 3 of edit page
- Click "Delete" button in table row
- Confirm deletion

## Important Commands

```bash
# Run migrations
php artisan migrate

# Link storage (for file uploads)
php artisan storage:link

# Clear cache if needed
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Troubleshooting

### Issue: Relationship dropdown is empty
**Solution**: Ensure `relations` table has data
```sql
SELECT * FROM relations;
```

### Issue: File uploads not working
**Solution**: Run `php artisan storage:link` and check permissions

### Issue: "Employee not found" error
**Solution**: Ensure employee exists before accessing family/guarantor routes

### Issue: Modal not showing
**Solution**: Check that `$employee->exists` is true in edit mode

## Next Steps for Enhancement

1. **Add Edit Functionality**: Implement edit modals for family/guarantor
2. **Bulk Operations**: Add ability to import multiple family members
3. **Validation**: Add more specific validation rules
4. **Reports**: Create reports showing employees with dependants
5. **Search**: Add search/filter for family members and guarantors
