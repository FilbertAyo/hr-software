<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $payments = [
            // Allowances (Dynamic payments with percentage rates)
            [
                'payment_name' => 'Housing Allowance',
                'payment_type' => 'Dynamic',
                'rate_check' => true,
                'payment_rate' => 15.00,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'Transport Allowance',
                'payment_type' => 'Dynamic',
                'rate_check' => true,
                'payment_rate' => 10.00,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'Medical Allowance',
                'payment_type' => 'Dynamic',
                'rate_check' => true,
                'payment_rate' => 5.00,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'Communication Allowance',
                'payment_type' => 'Dynamic',
                'rate_check' => false,
                'payment_rate' => null,
                'status' => 'Active'
            ],

            // Bonuses (Static fixed amounts)
            [
                'payment_name' => 'Performance Bonus',
                'payment_type' => 'Static',
                'rate_check' => false,
                'payment_rate' => null,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'Annual Bonus',
                'payment_type' => 'Static',
                'rate_check' => false,
                'payment_rate' => null,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'Project Completion Bonus',
                'payment_type' => 'Static',
                'rate_check' => false,
                'payment_rate' => null,
                'status' => 'Active'
            ],

            // Deductions (Dynamic payments with percentage rates)
            [
                'payment_name' => 'Income Tax',
                'payment_type' => 'Dynamic',
                'rate_check' => true,
                'payment_rate' => 20.00,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'Pension Contribution',
                'payment_type' => 'Dynamic',
                'rate_check' => true,
                'payment_rate' => 8.00,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'NHIF Contribution',
                'payment_type' => 'Dynamic',
                'rate_check' => true,
                'payment_rate' => 2.75,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'NSSF Contribution',
                'payment_type' => 'Dynamic',
                'rate_check' => true,
                'payment_rate' => 6.00,
                'status' => 'Active'
            ],

            // Other deductions
            [
                'payment_name' => 'Loan Deduction',
                'payment_type' => 'Static',
                'rate_check' => false,
                'payment_rate' => null,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'Advance Deduction',
                'payment_type' => 'Static',
                'rate_check' => false,
                'payment_rate' => null,
                'status' => 'Active'
            ],
            [
                'payment_name' => 'Late Coming Penalty',
                'payment_type' => 'Static',
                'rate_check' => false,
                'payment_rate' => null,
                'status' => 'Active'
            ]
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }
    }
}
