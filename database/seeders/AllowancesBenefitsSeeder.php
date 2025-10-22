<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllowancesBenefitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Earngroups
        $earngroups = [
            ['id' => 1, 'earngroup_name' => 'Executive Management', 'description' => 'C-Level executives and top management', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'earngroup_name' => 'Senior Management', 'description' => 'Senior managers and directors', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'earngroup_name' => 'Middle Management', 'description' => 'Department heads and team managers', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'earngroup_name' => 'Professional Staff', 'description' => 'Professional and technical employees', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'earngroup_name' => 'General Staff', 'description' => 'General employees and support staff', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'earngroup_name' => 'Contract Staff', 'description' => 'Contract and temporary employees', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('earngroups')->insert($earngroups);

        // Allowances
        $allowances = [
            ['id' => 1, 'allowance_name' => 'Housing Allowance', 'description' => 'Monthly housing allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'allowance_name' => 'Transport Allowance', 'description' => 'Monthly transport allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'allowance_name' => 'Meal Allowance', 'description' => 'Daily meal allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'allowance_name' => 'Medical Allowance', 'description' => 'Medical insurance allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'allowance_name' => 'Communication Allowance', 'description' => 'Phone and communication allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'allowance_name' => 'Responsibility Allowance', 'description' => 'Additional responsibility allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'allowance_name' => 'Education Allowance', 'description' => 'Children education support', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'allowance_name' => 'Utility Allowance', 'description' => 'Electricity and water allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'allowance_name' => 'Risk Allowance', 'description' => 'Hazard and risk allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'allowance_name' => 'Hardship Allowance', 'description' => 'Remote area hardship allowance', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('allowances')->insert($allowances);

        // Allowance Details
        $allowanceDetails = [
            // Housing Allowance - Different rates for different levels
            ['allowance_id' => 1, 'calculation_type' => 'percentage', 'amount' => null, 'percentage' => 40.00, 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Transport Allowance - Fixed amounts
            ['allowance_id' => 2, 'calculation_type' => 'amount', 'amount' => 150000.00, 'percentage' => null, 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Meal Allowance - Fixed daily rate
            ['allowance_id' => 3, 'calculation_type' => 'amount', 'amount' => 10000.00, 'percentage' => null, 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Medical Allowance - Percentage based
            ['allowance_id' => 4, 'calculation_type' => 'percentage', 'amount' => null, 'percentage' => 15.00, 'taxable' => false, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Communication Allowance - Fixed amount
            ['allowance_id' => 5, 'calculation_type' => 'amount', 'amount' => 50000.00, 'percentage' => null, 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Responsibility Allowance - Percentage based
            ['allowance_id' => 6, 'calculation_type' => 'percentage', 'amount' => null, 'percentage' => 20.00, 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Education Allowance - Fixed amount
            ['allowance_id' => 7, 'calculation_type' => 'amount', 'amount' => 100000.00, 'percentage' => null, 'taxable' => false, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Utility Allowance - Fixed amount
            ['allowance_id' => 8, 'calculation_type' => 'amount', 'amount' => 75000.00, 'percentage' => null, 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Risk Allowance - Percentage based
            ['allowance_id' => 9, 'calculation_type' => 'percentage', 'amount' => null, 'percentage' => 25.00, 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Hardship Allowance - Fixed amount
            ['allowance_id' => 10, 'calculation_type' => 'amount', 'amount' => 200000.00, 'percentage' => null, 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('allowance_details')->insert($allowanceDetails);

        // Group Benefits - Linking earngroups to allowances
        $groupBenefits = [
            // Executive Management (All allowances)
            ['earngroup_id' => 1, 'allowance_id' => 1, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 1, 'allowance_id' => 2, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 1, 'allowance_id' => 3, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 1, 'allowance_id' => 4, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 1, 'allowance_id' => 5, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 1, 'allowance_id' => 6, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 1, 'allowance_id' => 7, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 1, 'allowance_id' => 8, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Senior Management (Most allowances)
            ['earngroup_id' => 2, 'allowance_id' => 1, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 2, 'allowance_id' => 2, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 2, 'allowance_id' => 3, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 2, 'allowance_id' => 4, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 2, 'allowance_id' => 5, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 2, 'allowance_id' => 7, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Middle Management
            ['earngroup_id' => 3, 'allowance_id' => 1, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 3, 'allowance_id' => 2, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 3, 'allowance_id' => 3, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 3, 'allowance_id' => 4, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 3, 'allowance_id' => 5, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Professional Staff
            ['earngroup_id' => 4, 'allowance_id' => 1, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 4, 'allowance_id' => 2, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 4, 'allowance_id' => 3, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 4, 'allowance_id' => 4, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // General Staff (Basic allowances)
            ['earngroup_id' => 5, 'allowance_id' => 1, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 5, 'allowance_id' => 2, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 5, 'allowance_id' => 3, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Contract Staff (Minimal allowances)
            ['earngroup_id' => 6, 'allowance_id' => 2, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['earngroup_id' => 6, 'allowance_id' => 3, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('group_benefits')->insert($groupBenefits);

        // Other Benefits
        $otherBenefits = [
            ['id' => 1, 'other_benefit_name' => 'Annual Performance Bonus', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'other_benefit_name' => 'Overtime Payment', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'other_benefit_name' => 'Acting Allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'other_benefit_name' => 'Festival Bonus', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'other_benefit_name' => 'Project Completion Bonus', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'other_benefit_name' => 'Travel Reimbursement', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'other_benefit_name' => 'Training Allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'other_benefit_name' => 'Special Assignment Allowance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'other_benefit_name' => 'Leave Encashment', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'other_benefit_name' => 'End of Year Bonus', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('other_benefits')->insert($otherBenefits);

        // Other Benefit Details - Sample data
        $otherBenefitDetails = [
            // Annual Performance Bonus - Q4 2024
            ['other_benefit_id' => 1, 'amount' => 500000.00, 'benefit_date' => '2024-12-15', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['other_benefit_id' => 1, 'amount' => 750000.00, 'benefit_date' => '2024-12-15', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['other_benefit_id' => 1, 'amount' => 1000000.00, 'benefit_date' => '2024-12-15', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Overtime Payment - Monthly
            ['other_benefit_id' => 2, 'amount' => 150000.00, 'benefit_date' => '2025-01-31', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['other_benefit_id' => 2, 'amount' => 200000.00, 'benefit_date' => '2025-02-28', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Acting Allowance
            ['other_benefit_id' => 3, 'amount' => 300000.00, 'benefit_date' => '2025-01-31', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Festival Bonus - Eid/Christmas
            ['other_benefit_id' => 4, 'amount' => 200000.00, 'benefit_date' => '2024-12-20', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['other_benefit_id' => 4, 'amount' => 250000.00, 'benefit_date' => '2024-12-20', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Project Completion Bonus
            ['other_benefit_id' => 5, 'amount' => 800000.00, 'benefit_date' => '2025-03-31', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Travel Reimbursement
            ['other_benefit_id' => 6, 'amount' => 450000.00, 'benefit_date' => '2025-01-15', 'taxable' => false, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['other_benefit_id' => 6, 'amount' => 350000.00, 'benefit_date' => '2025-02-10', 'taxable' => false, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Training Allowance
            ['other_benefit_id' => 7, 'amount' => 500000.00, 'benefit_date' => '2025-02-28', 'taxable' => false, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Special Assignment Allowance
            ['other_benefit_id' => 8, 'amount' => 600000.00, 'benefit_date' => '2025-01-31', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // Leave Encashment
            ['other_benefit_id' => 9, 'amount' => 1200000.00, 'benefit_date' => '2024-12-31', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],

            // End of Year Bonus
            ['other_benefit_id' => 10, 'amount' => 1500000.00, 'benefit_date' => '2024-12-31', 'taxable' => true, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('other_benefit_details')->insert($otherBenefitDetails);
    }
}

