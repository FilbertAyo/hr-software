<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectDeductionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('direct_deductions')->insert([
            [
                'name' => 'NSSF (National Social Security Fund)',
                'employer_percent' => '10.00',
                'employee_percent' => '10.00',
                'deduction_type' => 'pension',
                'percentage_of' => 'basic',
                'status' => 'active',
                'require_member_no' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PPF (Public Provident Fund)',
                'employer_percent' => '15.00',
                'employee_percent' => '5.00',
                'deduction_type' => 'pension',
                'percentage_of' => 'basic',
                'status' => 'active',
                'require_member_no' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PSPF (Public Service Pension Fund)',
                'employer_percent' => '15.00',
                'employee_percent' => '5.00',
                'deduction_type' => 'pension',
                'percentage_of' => 'basic',
                'status' => 'active',
                'require_member_no' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WCF (Workers Compensation Fund)',
                'employer_percent' => '1.00',
                'employee_percent' => null,
                'deduction_type' => 'normal',
                'percentage_of' => 'gross',
                'status' => 'active',
                'require_member_no' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SDL (Skills Development Levy)',
                'employer_percent' => '5.00',
                'employee_percent' => null,
                'deduction_type' => 'normal',
                'percentage_of' => 'gross',
                'status' => 'active',
                'require_member_no' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NHIF (National Health Insurance Fund)',
                'employer_percent' => null,
                'employee_percent' => '3.00',
                'deduction_type' => 'normal',
                'percentage_of' => 'gross',
                'status' => 'active',
                'require_member_no' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GEPF (Government Employees Pension Fund)',
                'employer_percent' => '7.50',
                'employee_percent' => '7.50',
                'deduction_type' => 'normal',
                'percentage_of' => 'basic',
                'status' => 'active',
                'require_member_no' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

