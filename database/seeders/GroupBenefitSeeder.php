<?php

namespace Database\Seeders;

use App\Models\Earngroup;
use App\Models\Allowance;
use App\Models\AllowanceDetail;
use App\Models\GroupBenefit;
use Illuminate\Database\Seeder;

class GroupBenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample earning groups
        $earngroups = [
            ['earngroup_name' => 'MARKETING', 'description' => 'Marketing Department'],
            ['earngroup_name' => 'SALES', 'description' => 'Sales Department'],
            ['earngroup_name' => 'IT', 'description' => 'Information Technology'],
            ['earngroup_name' => 'HR', 'description' => 'Human Resources'],
        ];

        foreach ($earngroups as $earngroup) {
            Earngroup::firstOrCreate(
                ['earngroup_name' => $earngroup['earngroup_name']],
                $earngroup
            );
        }

        // Create sample allowances
        $allowances = [
            ['allowance_name' => 'OT', 'description' => 'Overtime Allowance'],
            ['allowance_name' => 'MEAL ALLOWANCE', 'description' => 'Meal Allowance'],
            ['allowance_name' => 'Transport', 'description' => 'Transport Allowance'],
            ['allowance_name' => 'Housing', 'description' => 'Housing Allowance'],
        ];

        foreach ($allowances as $allowance) {
            $created = Allowance::firstOrCreate(
                ['allowance_name' => $allowance['allowance_name']],
                $allowance
            );

            // Create allowance details
            if ($created->allowanceDetails()->count() == 0) {
                $amounts = [1000.00, 20000.00, 5.00, 5000.00];
                $types = ['amount', 'amount', 'percentage', 'amount'];
                $taxable = [true, false, true, true];

                AllowanceDetail::create([
                    'allowance_id' => $created->id,
                    'calculation_type' => $types[array_search($created->allowance_name, array_column($allowances, 'allowance_name'))],
                    'amount' => $types[array_search($created->allowance_name, array_column($allowances, 'allowance_name'))] == 'amount' ? $amounts[array_search($created->allowance_name, array_column($allowances, 'allowance_name'))] : null,
                    'percentage' => $types[array_search($created->allowance_name, array_column($allowances, 'allowance_name'))] == 'percentage' ? $amounts[array_search($created->allowance_name, array_column($allowances, 'allowance_name'))] : null,
                    'taxable' => $taxable[array_search($created->allowance_name, array_column($allowances, 'allowance_name'))],
                    'status' => 'active',
                ]);
            }
        }

        // Create sample group benefits
        $marketingGroup = Earngroup::where('earngroup_name', 'MARKETING')->first();
        $salesGroup = Earngroup::where('earngroup_name', 'SALES')->first();
        $otAllowance = Allowance::where('allowance_name', 'OT')->first();
        $mealAllowance = Allowance::where('allowance_name', 'MEAL ALLOWANCE')->first();
        $transportAllowance = Allowance::where('allowance_name', 'Transport')->first();

        if ($marketingGroup && $otAllowance) {
            GroupBenefit::firstOrCreate([
                'earngroup_id' => $marketingGroup->id,
                'allowance_id' => $otAllowance->id,
            ], [
                'status' => 'active',
            ]);
        }

        if ($salesGroup && $mealAllowance) {
            GroupBenefit::firstOrCreate([
                'earngroup_id' => $salesGroup->id,
                'allowance_id' => $mealAllowance->id,
            ], [
                'status' => 'active',
            ]);
        }

        if ($marketingGroup && $transportAllowance) {
            GroupBenefit::firstOrCreate([
                'earngroup_id' => $marketingGroup->id,
                'allowance_id' => $transportAllowance->id,
            ], [
                'status' => 'active',
            ]);
        }
    }
}
