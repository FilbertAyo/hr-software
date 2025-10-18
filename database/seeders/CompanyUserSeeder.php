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
        // Create test companies
        $company1 = Company::create([
            'company_name' => 'Test Company 1',
            'company_short_name' => 'TC1',
            'contact_person' => 'John Doe',
            'email' => 'contact@tc1.com',
            'start_month' => 'January',
            'start_year' => 2024,
        ]);

        $company2 = Company::create([
            'company_name' => 'Test Company 2',
            'company_short_name' => 'TC2',
            'contact_person' => 'Jane Smith',
            'email' => 'contact@tc2.com',
            'start_month' => 'January',
            'start_year' => 2024,
        ]);

        // Create test user
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('123'),
                'status' => 'active',
            ]
        );

        // Attach user to both companies
        $user->companies()->syncWithoutDetaching([$company1->id, $company2->id]);

        // Create payroll periods for each company
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Company 1 payroll periods
        PayrollPeriod::firstOrCreate(
            ['period_name' => Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') . ' - TC1'],
            [
                'start_date' => Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth(),
                'end_date' => Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth(),
                'status' => 'draft',
                'company_id' => $company1->id,
            ]
        );

        // Company 2 payroll periods
        PayrollPeriod::firstOrCreate(
            ['period_name' => Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') . ' - TC2'],
            [
                'start_date' => Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth(),
                'end_date' => Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth(),
                'status' => 'draft',
                'company_id' => $company2->id,
            ]
        );
    }
}
