<?php

namespace Database\Seeders;

use App\Models\OtherDeductionType;
use Illuminate\Database\Seeder;

class OtherDeductionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deductionTypes = [
            [
                'deduction_type' => 'Internal',
                'requires_document' => false,
                'description' => 'Internal company-related deductions',
                'status' => true
            ],
            [
                'deduction_type' => 'Discipline',
                'requires_document' => true,
                'description' => 'Disciplinary action deductions - requires supporting documentation',
                'status' => true
            ],
            [
                'deduction_type' => 'Legal',
                'requires_document' => true,
                'description' => 'Court-ordered or legal deductions - requires legal documentation',
                'status' => true
            ],
            [
                'deduction_type' => 'Damages',
                'requires_document' => true,
                'description' => 'Deductions for company property damage or loss',
                'status' => true
            ],
            [
                'deduction_type' => 'Overpayment Recovery',
                'requires_document' => false,
                'description' => 'Recovery of previously overpaid salary or benefits',
                'status' => true
            ],
            [
                'deduction_type' => 'Garnishment',
                'requires_document' => true,
                'description' => 'Wage garnishment orders from court',
                'status' => true
            ],
            [
                'deduction_type' => 'Absence Without Leave',
                'requires_document' => false,
                'description' => 'Deductions for unauthorized absences',
                'status' => true
            ],
            [
                'deduction_type' => 'Training Recovery',
                'requires_document' => true,
                'description' => 'Recovery of training costs as per employment contract',
                'status' => true
            ],
            [
                'deduction_type' => 'Other',
                'requires_document' => false,
                'description' => 'Miscellaneous deductions not categorized elsewhere',
                'status' => true
            ]
        ];

        foreach ($deductionTypes as $type) {
            OtherDeductionType::create($type);
        }
    }
}
