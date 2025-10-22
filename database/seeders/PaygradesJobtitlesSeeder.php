<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaygradesJobtitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Paygrades
        $paygrades = [
            [
                'id' => 1,
                'paygrade_name' => 'Executive Grade',
                'grade' => 'EX-1',
                'currency' => 'TZS',
                'initial_amount' => 5000000.00,
                'optimal_amount' => 8000000.00,
                'step_increase' => 500000.00,
                'min_salary' => 5000000.00,
                'max_salary' => 10000000.00,
                'description' => 'C-Level executives and top management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'paygrade_name' => 'Senior Management Grade',
                'grade' => 'SM-1',
                'currency' => 'TZS',
                'initial_amount' => 3000000.00,
                'optimal_amount' => 4500000.00,
                'step_increase' => 300000.00,
                'min_salary' => 3000000.00,
                'max_salary' => 6000000.00,
                'description' => 'Senior managers and directors',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'paygrade_name' => 'Middle Management Grade',
                'grade' => 'MM-1',
                'currency' => 'TZS',
                'initial_amount' => 1800000.00,
                'optimal_amount' => 2500000.00,
                'step_increase' => 200000.00,
                'min_salary' => 1800000.00,
                'max_salary' => 3500000.00,
                'description' => 'Department heads and team managers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'paygrade_name' => 'Professional Grade A',
                'grade' => 'PG-A',
                'currency' => 'TZS',
                'initial_amount' => 1200000.00,
                'optimal_amount' => 1800000.00,
                'step_increase' => 150000.00,
                'min_salary' => 1200000.00,
                'max_salary' => 2500000.00,
                'description' => 'Senior professionals and specialists',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'paygrade_name' => 'Professional Grade B',
                'grade' => 'PG-B',
                'currency' => 'TZS',
                'initial_amount' => 800000.00,
                'optimal_amount' => 1200000.00,
                'step_increase' => 100000.00,
                'min_salary' => 800000.00,
                'max_salary' => 1500000.00,
                'description' => 'Mid-level professionals',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'paygrade_name' => 'General Staff Grade',
                'grade' => 'GS-1',
                'currency' => 'TZS',
                'initial_amount' => 500000.00,
                'optimal_amount' => 800000.00,
                'step_increase' => 50000.00,
                'min_salary' => 500000.00,
                'max_salary' => 1000000.00,
                'description' => 'General employees and support staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'paygrade_name' => 'Entry Level Grade',
                'grade' => 'EL-1',
                'currency' => 'TZS',
                'initial_amount' => 400000.00,
                'optimal_amount' => 600000.00,
                'step_increase' => 30000.00,
                'min_salary' => 400000.00,
                'max_salary' => 700000.00,
                'description' => 'Entry-level employees and trainees',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('paygrades')->insert($paygrades);

        // Jobtitles
        $jobtitles = [
            // Executive Level
            ['id' => 1, 'job_title' => 'Chief Executive Officer', 'occupation_id' => 1, 'pay_grade_id' => 1, 'department_id' => 6, 'description' => 'Overall company leadership', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'job_title' => 'Chief Financial Officer', 'occupation_id' => 1, 'pay_grade_id' => 1, 'department_id' => 2, 'description' => 'Financial leadership', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'job_title' => 'Chief Technology Officer', 'occupation_id' => 1, 'pay_grade_id' => 1, 'department_id' => 3, 'description' => 'Technology leadership', 'created_at' => now(), 'updated_at' => now()],

            // Senior Management
            ['id' => 4, 'job_title' => 'HR Director', 'occupation_id' => 1, 'pay_grade_id' => 2, 'department_id' => 1, 'description' => 'Human resources management', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'job_title' => 'Finance Director', 'occupation_id' => 1, 'pay_grade_id' => 2, 'department_id' => 2, 'description' => 'Financial management', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'job_title' => 'IT Director', 'occupation_id' => 1, 'pay_grade_id' => 2, 'department_id' => 3, 'description' => 'IT management', 'created_at' => now(), 'updated_at' => now()],

            // Middle Management
            ['id' => 7, 'job_title' => 'HR Manager', 'occupation_id' => 1, 'pay_grade_id' => 3, 'department_id' => 1, 'description' => 'HR operations management', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'job_title' => 'Finance Manager', 'occupation_id' => 1, 'pay_grade_id' => 3, 'department_id' => 2, 'description' => 'Finance operations', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'job_title' => 'IT Manager', 'occupation_id' => 1, 'pay_grade_id' => 3, 'department_id' => 3, 'description' => 'IT operations', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'job_title' => 'Operations Manager', 'occupation_id' => 1, 'pay_grade_id' => 3, 'department_id' => 4, 'description' => 'Operations management', 'created_at' => now(), 'updated_at' => now()],

            // Professional Staff
            ['id' => 11, 'job_title' => 'Senior Accountant', 'occupation_id' => 6, 'pay_grade_id' => 4, 'department_id' => 2, 'description' => 'Senior accounting role', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'job_title' => 'Senior Software Developer', 'occupation_id' => 3, 'pay_grade_id' => 4, 'department_id' => 3, 'description' => 'Senior development role', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'job_title' => 'Senior HR Officer', 'occupation_id' => 2, 'pay_grade_id' => 4, 'department_id' => 1, 'description' => 'Senior HR role', 'created_at' => now(), 'updated_at' => now()],

            ['id' => 14, 'job_title' => 'Accountant', 'occupation_id' => 6, 'pay_grade_id' => 5, 'department_id' => 2, 'description' => 'Accounting role', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'job_title' => 'Software Developer', 'occupation_id' => 3, 'pay_grade_id' => 5, 'department_id' => 3, 'description' => 'Development role', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'job_title' => 'HR Officer', 'occupation_id' => 2, 'pay_grade_id' => 5, 'department_id' => 1, 'description' => 'HR role', 'created_at' => now(), 'updated_at' => now()],

            // General Staff
            ['id' => 17, 'job_title' => 'Administrative Assistant', 'occupation_id' => 2, 'pay_grade_id' => 6, 'department_id' => 6, 'description' => 'Administrative support', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'job_title' => 'Receptionist', 'occupation_id' => 4, 'pay_grade_id' => 6, 'department_id' => 6, 'description' => 'Reception duties', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'job_title' => 'Office Assistant', 'occupation_id' => 4, 'pay_grade_id' => 6, 'department_id' => 6, 'description' => 'Office support', 'created_at' => now(), 'updated_at' => now()],

            // Entry Level
            ['id' => 20, 'job_title' => 'Intern', 'occupation_id' => 4, 'pay_grade_id' => 7, 'department_id' => 1, 'description' => 'Internship position', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('jobtitles')->insert($jobtitles);
    }
}

