<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skills
        DB::table('skills')->insert([
            ['skill_name' => 'Microsoft Office', 'created_at' => now(), 'updated_at' => now()],
            ['skill_name' => 'Communication', 'created_at' => now(), 'updated_at' => now()],
            ['skill_name' => 'Leadership', 'created_at' => now(), 'updated_at' => now()],
            ['skill_name' => 'Project Management', 'created_at' => now(), 'updated_at' => now()],
            ['skill_name' => 'Data Analysis', 'created_at' => now(), 'updated_at' => now()],
            ['skill_name' => 'Problem Solving', 'created_at' => now(), 'updated_at' => now()],
            ['skill_name' => 'Customer Service', 'created_at' => now(), 'updated_at' => now()],
            ['skill_name' => 'Technical Writing', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Languages
        DB::table('languages')->insert([
            ['language' => 'English', 'created_at' => now(), 'updated_at' => now()],
            ['language' => 'Swahili', 'created_at' => now(), 'updated_at' => now()],
            ['language' => 'French', 'created_at' => now(), 'updated_at' => now()],
            ['language' => 'Arabic', 'created_at' => now(), 'updated_at' => now()],
            ['language' => 'Spanish', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Education
        DB::table('education')->insert([
            ['education_level' => 'Primary Education', 'created_at' => now(), 'updated_at' => now()],
            ['education_level' => 'Secondary Education (O-Level)', 'created_at' => now(), 'updated_at' => now()],
            ['education_level' => 'Secondary Education (A-Level)', 'created_at' => now(), 'updated_at' => now()],
            ['education_level' => 'Certificate', 'created_at' => now(), 'updated_at' => now()],
            ['education_level' => 'Diploma', 'created_at' => now(), 'updated_at' => now()],
            ['education_level' => 'Bachelor\'s Degree', 'created_at' => now(), 'updated_at' => now()],
            ['education_level' => 'Master\'s Degree', 'created_at' => now(), 'updated_at' => now()],
            ['education_level' => 'PhD/Doctorate', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Banks
        DB::table('banks')->insert([
            ['bank_name' => 'CRDB Bank', 'bank_code' => '01', 'created_at' => now(), 'updated_at' => now()],
            ['bank_name' => 'National Microfinance Bank (NMB)', 'bank_code' => '02', 'created_at' => now(), 'updated_at' => now()],
            ['bank_name' => 'NBC Bank', 'bank_code' => '03', 'created_at' => now(), 'updated_at' => now()],
            ['bank_name' => 'Azania Bank', 'bank_code' => '04', 'created_at' => now(), 'updated_at' => now()],
            ['bank_name' => 'TPB Bank', 'bank_code' => '05', 'created_at' => now(), 'updated_at' => now()],
            ['bank_name' => 'Stanbic Bank', 'bank_code' => '06', 'created_at' => now(), 'updated_at' => now()],
            ['bank_name' => 'Standard Chartered Bank', 'bank_code' => '07', 'created_at' => now(), 'updated_at' => now()],
            ['bank_name' => 'Exim Bank', 'bank_code' => '08', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Relations
        DB::table('relations')->insert([
            ['relation_name' => 'Spouse', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Father', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Mother', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Son', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Daughter', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Brother', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Sister', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Uncle', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Aunt', 'created_at' => now(), 'updated_at' => now()],
            ['relation_name' => 'Friend', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Departments
        DB::table('departments')->insert([
            ['department_name' => 'Human Resources', 'description' => 'Manages employee relations and recruitment', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Finance', 'description' => 'Handles financial planning and accounting', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'IT', 'description' => 'Information Technology and Systems', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Operations', 'description' => 'Manages daily operations', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Sales & Marketing', 'description' => 'Sales and marketing activities', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Administration', 'description' => 'Administrative support services', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Customer Service', 'description' => 'Customer support and relations', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Occupations
        DB::table('occupations')->insert([
            ['occupation_name' => 'Management', 'description' => 'Management and executive roles', 'created_at' => now(), 'updated_at' => now()],
            ['occupation_name' => 'Administration', 'description' => 'Administrative and clerical roles', 'created_at' => now(), 'updated_at' => now()],
            ['occupation_name' => 'Technical', 'description' => 'Technical and specialized roles', 'created_at' => now(), 'updated_at' => now()],
            ['occupation_name' => 'Support Staff', 'description' => 'Support and auxiliary roles', 'created_at' => now(), 'updated_at' => now()],
            ['occupation_name' => 'Sales', 'description' => 'Sales and business development', 'created_at' => now(), 'updated_at' => now()],
            ['occupation_name' => 'Finance', 'description' => 'Finance and accounting roles', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Religions
        DB::table('religions')->insert([
            ['religion_name' => 'Christianity', 'created_at' => now(), 'updated_at' => now()],
            ['religion_name' => 'Islam', 'created_at' => now(), 'updated_at' => now()],
            ['religion_name' => 'Hinduism', 'created_at' => now(), 'updated_at' => now()],
            ['religion_name' => 'Buddhism', 'created_at' => now(), 'updated_at' => now()],
            ['religion_name' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Nationalities
        DB::table('nationalities')->insert([
            ['nationality_name' => 'Tanzanian', 'country_code' => 'TZ', 'created_at' => now(), 'updated_at' => now()],
            ['nationality_name' => 'Kenyan', 'country_code' => 'KE', 'created_at' => now(), 'updated_at' => now()],
            ['nationality_name' => 'Ugandan', 'country_code' => 'UG', 'created_at' => now(), 'updated_at' => now()],
            ['nationality_name' => 'Rwandan', 'country_code' => 'RW', 'created_at' => now(), 'updated_at' => now()],
            ['nationality_name' => 'Burundian', 'country_code' => 'BI', 'created_at' => now(), 'updated_at' => now()],
            ['nationality_name' => 'South African', 'country_code' => 'ZA', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Staff Levels
        DB::table('staff_levels')->insert([
            ['level_name' => 'Executive', 'level_order' => 1, 'description' => 'C-Level executives', 'created_at' => now(), 'updated_at' => now()],
            ['level_name' => 'Senior Management', 'level_order' => 2, 'description' => 'Senior managers and directors', 'created_at' => now(), 'updated_at' => now()],
            ['level_name' => 'Middle Management', 'level_order' => 3, 'description' => 'Department heads and managers', 'created_at' => now(), 'updated_at' => now()],
            ['level_name' => 'Junior Management', 'level_order' => 4, 'description' => 'Team leaders and supervisors', 'created_at' => now(), 'updated_at' => now()],
            ['level_name' => 'Senior Staff', 'level_order' => 5, 'description' => 'Experienced professionals', 'created_at' => now(), 'updated_at' => now()],
            ['level_name' => 'Mid-Level Staff', 'level_order' => 6, 'description' => 'Regular employees', 'created_at' => now(), 'updated_at' => now()],
            ['level_name' => 'Entry Level', 'level_order' => 7, 'description' => 'New hires and trainees', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Main Stations
        DB::table('mainstations')->insert([
            ['station_name' => 'Dar es Salaam', 'description' => 'Head office', 'created_at' => now(), 'updated_at' => now()],
            ['station_name' => 'Arusha', 'description' => 'Northern region office', 'created_at' => now(), 'updated_at' => now()],
            ['station_name' => 'Mwanza', 'description' => 'Lake zone office', 'created_at' => now(), 'updated_at' => now()],
            ['station_name' => 'Dodoma', 'description' => 'Central region office', 'created_at' => now(), 'updated_at' => now()],
            ['station_name' => 'Mbeya', 'description' => 'Southern highlands office', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Sub Stations
        DB::table('substations')->insert([
            ['substation_name' => 'Kinondoni', 'mainstation_id' => 1, 'description' => 'Kinondoni branch', 'created_at' => now(), 'updated_at' => now()],
            ['substation_name' => 'Temeke', 'mainstation_id' => 1, 'description' => 'Temeke branch', 'created_at' => now(), 'updated_at' => now()],
            ['substation_name' => 'Ilala', 'mainstation_id' => 1, 'description' => 'Ilala branch', 'created_at' => now(), 'updated_at' => now()],
            ['substation_name' => 'Moshi', 'mainstation_id' => 2, 'description' => 'Moshi branch', 'created_at' => now(), 'updated_at' => now()],
            ['substation_name' => 'Karatu', 'mainstation_id' => 2, 'description' => 'Karatu branch', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Leave Types are now handled by LeaveTypesSeeder

        // Holidays
        DB::table('holidays')->insert([
            ['holiday_name' => 'New Year\'s Day', 'holiday_date' => '2025-01-01', 'is_recurring' => true, 'description' => 'New Year celebration', 'created_at' => now(), 'updated_at' => now()],
            ['holiday_name' => 'Zanzibar Revolution Day', 'holiday_date' => '2025-01-12', 'is_recurring' => true, 'description' => 'Zanzibar Revolution', 'created_at' => now(), 'updated_at' => now()],
            ['holiday_name' => 'Union Day', 'holiday_date' => '2025-04-26', 'is_recurring' => true, 'description' => 'Union of Tanganyika and Zanzibar', 'created_at' => now(), 'updated_at' => now()],
            ['holiday_name' => 'Labour Day', 'holiday_date' => '2025-05-01', 'is_recurring' => true, 'description' => 'International Workers Day', 'created_at' => now(), 'updated_at' => now()],
            ['holiday_name' => 'Saba Saba', 'holiday_date' => '2025-07-07', 'is_recurring' => true, 'description' => 'Peasants Day', 'created_at' => now(), 'updated_at' => now()],
            ['holiday_name' => 'Nane Nane', 'holiday_date' => '2025-08-08', 'is_recurring' => true, 'description' => 'Farmers Day', 'created_at' => now(), 'updated_at' => now()],
            ['holiday_name' => 'Independence Day', 'holiday_date' => '2025-12-09', 'is_recurring' => true, 'description' => 'Tanzania Independence', 'created_at' => now(), 'updated_at' => now()],
            ['holiday_name' => 'Christmas Day', 'holiday_date' => '2025-12-25', 'is_recurring' => true, 'description' => 'Christmas celebration', 'created_at' => now(), 'updated_at' => now()],
            ['holiday_name' => 'Boxing Day', 'holiday_date' => '2025-12-26', 'is_recurring' => true, 'description' => 'Day after Christmas', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Terminations
        DB::table('terminations')->insert([
            ['termination_type' => 'Resignation', 'description' => 'Voluntary resignation by employee', 'created_at' => now(), 'updated_at' => now()],
            ['termination_type' => 'Retirement', 'description' => 'Normal retirement', 'created_at' => now(), 'updated_at' => now()],
            ['termination_type' => 'Dismissal', 'description' => 'Termination for cause', 'created_at' => now(), 'updated_at' => now()],
            ['termination_type' => 'Contract End', 'description' => 'End of contract period', 'created_at' => now(), 'updated_at' => now()],
            ['termination_type' => 'Redundancy', 'description' => 'Position no longer needed', 'created_at' => now(), 'updated_at' => now()],
            ['termination_type' => 'Death', 'description' => 'Death of employee', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Payments (Common allowances and deductions)
        // DB::table('payments')->insert([
        //     ['payment_name' => 'Transport Allowance', 'payment_type' => 'allowance', 'amount' => 50000, 'percentage' => null, 'is_taxable' => true, 'description' => 'Monthly transport allowance', 'created_at' => now(), 'updated_at' => now()],
        //     ['payment_name' => 'Housing Allowance', 'payment_type' => 'allowance', 'amount' => null, 'percentage' => 30.00, 'is_taxable' => true, 'description' => '30% of basic salary', 'created_at' => now(), 'updated_at' => now()],
        //     ['payment_name' => 'Meal Allowance', 'payment_type' => 'allowance', 'amount' => 30000, 'percentage' => null, 'is_taxable' => true, 'description' => 'Monthly meal allowance', 'created_at' => now(), 'updated_at' => now()],
        //     ['payment_name' => 'NSSF - Employee', 'payment_type' => 'deduction', 'amount' => null, 'percentage' => 10.00, 'is_taxable' => false, 'description' => 'Employee NSSF contribution', 'created_at' => now(), 'updated_at' => now()],
        //     ['payment_name' => 'NSSF - Employer', 'payment_type' => 'deduction', 'amount' => null, 'percentage' => 10.00, 'is_taxable' => false, 'description' => 'Employer NSSF contribution', 'created_at' => now(), 'updated_at' => now()],
        //     ['payment_name' => 'Performance Bonus', 'payment_type' => 'bonus', 'amount' => null, 'percentage' => null, 'is_taxable' => true, 'description' => 'Annual performance bonus', 'created_at' => now(), 'updated_at' => now()],
        // ]);

        // Reportings
        DB::table('reportings')->insert([
            ['reporting_name' => 'Direct Report', 'description' => 'Reports directly to supervisor', 'created_at' => now(), 'updated_at' => now()],
            ['reporting_name' => 'Indirect Report', 'description' => 'Reports through another manager', 'created_at' => now(), 'updated_at' => now()],
            ['reporting_name' => 'Matrix Report', 'description' => 'Reports to multiple supervisors', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

