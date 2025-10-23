<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\Company;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies
        $companies = Company::all();

        foreach ($companies as $company) {
            // Create default shifts for each company
            $shifts = [
                [
                    'shift_name' => 'Morning Shift',
                    'start_time' => '08:00',
                    'end_time' => '17:00',
                    'break_duration_minutes' => 60,
                    'description' => 'Standard morning shift (8 AM - 5 PM)',
                    'company_id' => $company->id,
                    'is_active' => true,
                ],
                [
                    'shift_name' => 'Evening Shift',
                    'start_time' => '14:00',
                    'end_time' => '23:00',
                    'break_duration_minutes' => 60,
                    'description' => 'Evening shift (2 PM - 11 PM)',
                    'company_id' => $company->id,
                    'is_active' => true,
                ],
                [
                    'shift_name' => 'Night Shift',
                    'start_time' => '22:00',
                    'end_time' => '06:00',
                    'break_duration_minutes' => 60,
                    'description' => 'Night shift (10 PM - 6 AM)',
                    'company_id' => $company->id,
                    'is_active' => true,
                ],
                [
                    'shift_name' => 'Part Time Morning',
                    'start_time' => '08:00',
                    'end_time' => '12:00',
                    'break_duration_minutes' => 0,
                    'description' => 'Part time morning (8 AM - 12 PM)',
                    'company_id' => $company->id,
                    'is_active' => true,
                ],
                [
                    'shift_name' => 'Part Time Afternoon',
                    'start_time' => '13:00',
                    'end_time' => '17:00',
                    'break_duration_minutes' => 0,
                    'description' => 'Part time afternoon (1 PM - 5 PM)',
                    'company_id' => $company->id,
                    'is_active' => true,
                ],
            ];

            foreach ($shifts as $shiftData) {
                Shift::create($shiftData);
            }
        }
    }
}