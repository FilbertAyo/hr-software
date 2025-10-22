<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'id' => 1,
                'company_name' => 'MARSCOMM Technologies Ltd',
                'company_short_name' => 'MARSCOMM',
                'contact_person' => 'John Mwamba',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'address' => 'Plot 123, Samora Avenue, Dar es Salaam',
                'phone_no' => '+255 22 2123456',
                'fax_no' => '+255 22 2123457',
                'email' => 'info@marscomm.co.tz',
                'website' => 'www.marscomm.co.tz',
                'tin_no' => '123456789',
                'vat_no' => 'VAT123456',
                'district_no' => 'DSM001',
                'nssf_control_number' => 'NSSF123456',
                'wcf_registration_number' => 'WCF123456',
                'start_month' => 'January',
                'start_year' => 2024,
                'leave_accumulation_per_month' => 2.33,
                'alias_company_name' => false,
                'weekday_overtime_rate' => 1.50,
                'saturday_overtime_rate' => 1.50,
                'weekend_holiday_overtime_rate' => 2.00,
                'wcf_rate' => 1.00,
                'sdl_rate' => 5.00,
                'advance_rate' => 25.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'company_name' => 'Horizon Consulting Group',
                'company_short_name' => 'HCG',
                'contact_person' => 'Sarah Kimaro',
                'city' => 'Arusha',
                'country' => 'Tanzania',
                'address' => 'Njiro Block A, Arusha',
                'phone_no' => '+255 27 2501234',
                'fax_no' => null,
                'email' => 'info@horizontz.com',
                'website' => 'www.horizontz.com',
                'tin_no' => '987654321',
                'vat_no' => 'VAT987654',
                'district_no' => 'ARU002',
                'nssf_control_number' => 'NSSF987654',
                'wcf_registration_number' => 'WCF987654',
                'start_month' => 'January',
                'start_year' => 2024,
                'leave_accumulation_per_month' => 2.33,
                'alias_company_name' => false,
                'weekday_overtime_rate' => 1.50,
                'saturday_overtime_rate' => 1.50,
                'weekend_holiday_overtime_rate' => 2.00,
                'wcf_rate' => 1.00,
                'sdl_rate' => 5.00,
                'advance_rate' => 25.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

