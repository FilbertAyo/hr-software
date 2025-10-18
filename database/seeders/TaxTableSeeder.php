<?php

namespace Database\Seeders;

use App\Models\TaxTable;
use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class TaxTableSeeder extends Seeder
{
  /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Clear existing data
        TaxRate::truncate();
        TaxTable::truncate();

        // ---------------------------------------------------------------------
        // 1. Create TaxRate Records (Flat/Specific Tax Rates)
        // Note: Removing manual 'id' and 'status' (as it's not in the schema)
        // ---------------------------------------------------------------------

        $specificTaxRates = [
            [
                'tax_name' => 'PRIMARY',
                'rate' => 0.00, // Rate is 0.00 as it uses the progressive table
                'description' => 'Main progressive tax scale (PAYE) for standard employment.',
            ],
            [
                'tax_name' => 'SECONDARY',
                'rate' => 30.00, // Flat rate for secondary employment
                'description' => 'Flat tax rate for secondary employment income.',
            ],
            [
                'tax_name' => 'DIRECTOR`S FEE (NON FULL TIME)',
                'rate' => 15.00, // Withholding tax rate
                'description' => 'Withholding tax rate for non-full-time director fees.',
            ],
            [
                'tax_name' => 'NON-RESIDENT',
                'rate' => 15.00, // Flat rate for non-resident employment
                'description' => 'Flat tax rate for non-resident employee income.',
            ],
            [
                'tax_name' => 'CONSULTANT',
                'rate' => 5.00, // Withholding tax rate for consulting services
                'description' => 'Withholding tax rate for resident consultant or professional service fees.',
            ],
        ];

        // Use insert for efficiency and then retrieve the PRIMARY ID
        TaxRate::insert($specificTaxRates);

        // Retrieve the ID of the 'PRIMARY' tax rate record
        $primaryTaxRate = TaxRate::where('tax_name', 'PRIMARY')->first();
        $primaryTaxRateId = $primaryTaxRate->id;


        // ---------------------------------------------------------------------
        // 2. Create TaxTable Records (Progressive PAYE Brackets)
        // All brackets are assigned to the 'PRIMARY' tax rate ID.
        // ---------------------------------------------------------------------

        // The tax scale provided in your original request (Tanzania PAYE structure)
        $taxBrackets = [
            [
                'tax_rate_id' => $primaryTaxRateId,
                'min_income' => 0.00,
                'max_income' => 270000.00,
                'rate_percentage' => 0.00,
                'fixed_amount' => 0.00,
            ],
            [
                'tax_rate_id' => $primaryTaxRateId,
                'min_income' => 270000.01,
                'max_income' => 520000.00,
                'rate_percentage' => 8.00,
                'fixed_amount' => 0.00,
            ],
            [
                'tax_rate_id' => $primaryTaxRateId,
                'min_income' => 520000.01,
                'max_income' => 760000.00,
                'rate_percentage' => 20.00,
                'fixed_amount' => 20000.00,
            ],
            [
                'tax_rate_id' => $primaryTaxRateId,
                'min_income' => 760000.01,
                'max_income' => 1000000.00,
                'rate_percentage' => 25.00,
                'fixed_amount' => 68000.00,
            ],
            [
                'tax_rate_id' => $primaryTaxRateId,
                'min_income' => 1000000.01,
                'max_income' => 999999999.99, // Represents the highest bracket
                'rate_percentage' => 30.00,
                'fixed_amount' => 128000.00,
            ],
        ];

        // Use insert for efficiency
        TaxTable::insert($taxBrackets);
    }
}
