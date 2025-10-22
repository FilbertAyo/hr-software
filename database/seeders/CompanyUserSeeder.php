<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\PayrollPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing companies from CompanySeeder
        $company1 = Company::find(1);
        $company2 = Company::find(2);

        if (!$company1 || !$company2) {
            $this->command->warn('Companies not found. Make sure CompanySeeder runs before this seeder.');
            return;
        }

        // Create test user
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('123'),
                'status' => 'active',
            ]
        );

        // Assign super admin role if it exists
        if ($user->hasRole('Super Admin') === false) {
            $user->assignRole('Super Admin');
        }

        // Attach user to both companies
        $user->companies()->syncWithoutDetaching([$company1->id, $company2->id]);

        // Create payroll periods for each company
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Company 1 payroll periods
        PayrollPeriod::firstOrCreate(
            ['period_name' => Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') . ' - MARSCOMM'],
            [
                'start_date' => Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth(),
                'end_date' => Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth(),
                'status' => 'draft',
                'company_id' => $company1->id,
            ]
        );

        // Company 2 payroll periods
        PayrollPeriod::firstOrCreate(
            ['period_name' => Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') . ' - HCG'],
            [
                'start_date' => Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth(),
                'end_date' => Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth(),
                'status' => 'draft',
                'company_id' => $company2->id,
            ]
        );
    }
}
