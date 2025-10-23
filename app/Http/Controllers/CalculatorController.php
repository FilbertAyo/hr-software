<?php

namespace App\Http\Controllers;

use App\Models\DirectDeduction;
use App\Models\TaxTable;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    /**
     * Display netpay calculator
     */
    public function netpay()
    {
        // Get all active deductions split by pension vs other
        $pensionOptions = DirectDeduction::where('status', 'active')
            ->where('deduction_type', 'pension')
            ->get();

        $optionalDeductions = DirectDeduction::where('status', 'active')
            ->where('deduction_type', '!=', 'pension')
            ->get();

        return view("payroll.calculator.netpay", compact("pensionOptions", "optionalDeductions"));
    }

    /**
     * Display grosspay calculator
     */
    public function grosspay()
    {
        // Get all active deductions split by pension vs other
        $pensionOptions = DirectDeduction::where('status', 'active')
            ->where('deduction_type', 'pension')
            ->get();

        $optionalDeductions = DirectDeduction::where('status', 'active')
            ->where('deduction_type', '!=', 'pension')
            ->get();

        return view("payroll.calculator.grosspay", compact("pensionOptions", "optionalDeductions"));
    }

    /**
     * Calculate netpay via AJAX
     */
    public function calculateNetpay(Request $request)
    {
        // Validate input
        $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'selected_pension_id' => 'nullable|integer|exists:direct_deductions,id',
            'selected_deductions' => 'nullable|array',
            'selected_deductions.*' => 'integer|exists:direct_deductions,id'
        ]);

        $basicSalary = (float) $request->basic_salary;
        $allowances = (float) ($request->allowances ?? 0);
        $selectedDeductions = $request->selected_deductions ?? [];
        $selectedPensionId = $request->selected_pension_id;

        // Calculate gross salary
        $grossSalary = $basicSalary + $allowances;

        // Prepare deductions
        $pensionDeductions = [];
        $otherDeductions = [];
        $employerContributions = [];

        $totalPensionDeductions = 0;
        $totalOtherDeductions = 0;
        $totalEmployerContributions = 0;

        // Apply selected pension if any
        if ($selectedPensionId) {
            $pension = DirectDeduction::where('id', $selectedPensionId)
                ->where('status', 'active')
                ->where('deduction_type', 'pension')
                ->first();
            if ($pension) {
                $baseAmount = $pension->percentage_of === 'basic' ? $basicSalary : $grossSalary;
                $employeeAmount = ($baseAmount * (float)$pension->employee_percent) / 100;
                $employerAmount = ($baseAmount * (float)$pension->employer_percent) / 100;

                $pensionDeductions[] = [
                    'name' => $pension->name,
                    'amount' => $employeeAmount,
                    'is_mandatory' => false,
                    'percentage_of' => $pension->percentage_of
                ];
                $totalPensionDeductions += $employeeAmount;

                if ($employerAmount > 0) {
                    $employerContributions[] = [
                        'name' => $pension->name,
                        'amount' => $employerAmount,
                        'type' => $pension->deduction_type
                    ];
                    $totalEmployerContributions += $employerAmount;
                }
            }
        }

        // Apply other selected deductions (exclude pensions)
        if (!empty($selectedDeductions)) {
            $otherSelected = DirectDeduction::whereIn('id', $selectedDeductions)
                ->where('status', 'active')
                ->where('deduction_type', '!=', 'pension')
                ->get();

            foreach ($otherSelected as $deduction) {
                $baseAmount = $deduction->percentage_of === 'basic' ? $basicSalary : $grossSalary;
                $employeeAmount = ($baseAmount * (float)$deduction->employee_percent) / 100;
                $employerAmount = ($baseAmount * (float)$deduction->employer_percent) / 100;

                $otherDeductions[] = [
                    'name' => $deduction->name,
                    'amount' => $employeeAmount,
                    'is_mandatory' => false,
                    'percentage_of' => $deduction->percentage_of
                ];
                $totalOtherDeductions += $employeeAmount;

                if ($employerAmount > 0) {
                    $employerContributions[] = [
                        'name' => $deduction->name,
                        'amount' => $employerAmount,
                        'type' => $deduction->deduction_type
                    ];
                    $totalEmployerContributions += $employerAmount;
                }
            }
        }

        // Calculate taxable income (gross minus pension deductions)
        $taxableIncome = $grossSalary - $totalPensionDeductions;

        // Calculate PAYE tax
        $payeAmount = $this->calculatePAYE($taxableIncome);

        // Add PAYE to other deductions
        if ($payeAmount > 0) {
            $otherDeductions[] = [
                'name' => 'PAYE (Income Tax)',
                'amount' => $payeAmount,
                'is_mandatory' => true,
                'percentage_of' => 'taxable_income'
            ];
            $totalOtherDeductions += $payeAmount;
        }

        // Calculate total employee deductions
        $totalEmployeeDeductions = $totalPensionDeductions + $totalOtherDeductions;

        // Calculate net pay (Take Home)
        $takeHome = $grossSalary - $totalEmployeeDeductions;

        // Total cost to company
        $totalCostToCompany = $grossSalary + $totalEmployerContributions;

        return response()->json([
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'gross_salary' => $grossSalary,
            'pension_deductions' => $pensionDeductions,
            'total_pension_deductions' => $totalPensionDeductions,
            'taxable_income' => $taxableIncome,
            'paye_amount' => $payeAmount,
            'other_deductions' => $otherDeductions,
            'total_other_deductions' => $totalOtherDeductions,
            'total_employee_deductions' => $totalEmployeeDeductions,
            'take_home' => $takeHome,
            'employer_contributions' => $employerContributions,
            'total_employer_contributions' => $totalEmployerContributions,
            'total_cost_to_company' => $totalCostToCompany
        ]);
    }

    /**
     * Calculate grosspay from netpay via AJAX
     */
    public function calculateGrosspay(Request $request)
    {
        // Validate input
        $request->validate([
            'target_net_pay' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'selected_pension_id' => 'nullable|integer|exists:direct_deductions,id',
            'selected_deductions' => 'nullable|array',
            'selected_deductions.*' => 'integer|exists:direct_deductions,id'
        ]);

        $targetTakeHome = (float) $request->target_net_pay;
        $allowances = (float) ($request->allowances ?? 0);
        $selectedDeductions = $request->selected_deductions ?? [];
        $selectedPensionId = $request->selected_pension_id;

        // This is an iterative calculation to find the basic salary that results in the target take home
        $basicSalary = $targetTakeHome * 1.3; // Initial guess (take home is usually ~70-80% of basic)
        $tolerance = 1; // TZS 1 tolerance
        $maxIterations = 50;
        $adjustment = 100; // Initial adjustment amount

        for ($i = 0; $i < $maxIterations; $i++) {
            // Calculate what the take home would be with this basic salary
            $calculationResult = $this->performPayrollCalculation($basicSalary, $allowances, $selectedDeductions, $selectedPensionId);

            $difference = $calculationResult['take_home'] - $targetTakeHome;

            if (abs($difference) <= $tolerance) {
                break; // Close enough
            }

            // Adjust basic salary for next iteration
            if ($difference > 0) {
                // Take home is too high, reduce basic salary
                $basicSalary -= $adjustment;
            } else {
                // Take home is too low, increase basic salary
                $basicSalary += $adjustment;
            }

            // Reduce adjustment size as we get closer
            if ($i > 10 && abs($difference) < 1000) {
                $adjustment = 10;
            }
        }

        return response()->json($calculationResult);
    }

    /**
     * Perform payroll calculation (helper method)
     */
    private function performPayrollCalculation($basicSalary, $allowances, $selectedDeductions, $selectedPensionId = null)
    {
        $grossSalary = $basicSalary + $allowances;

        // Separate deductions by type
        $pensionDeductions = [];
        $otherDeductions = [];
        $employerContributions = [];

        $totalPensionDeductions = 0;
        $totalOtherDeductions = 0;
        $totalEmployerContributions = 0;

        // Apply selected pension if any
        if ($selectedPensionId) {
            $pension = DirectDeduction::where('id', $selectedPensionId)
                ->where('status', 'active')
                ->where('deduction_type', 'pension')
                ->first();
            if ($pension) {
                $baseAmount = $pension->percentage_of === 'basic' ? $basicSalary : $grossSalary;
                $employeeAmount = ($baseAmount * (float)$pension->employee_percent) / 100;
                $employerAmount = ($baseAmount * (float)$pension->employer_percent) / 100;

                $pensionDeductions[] = [
                    'name' => $pension->name,
                    'amount' => $employeeAmount,
                    'is_mandatory' => false
                ];
                $totalPensionDeductions += $employeeAmount;

                if ($employerAmount > 0) {
                    $employerContributions[] = [
                        'name' => $pension->name,
                        'amount' => $employerAmount
                    ];
                    $totalEmployerContributions += $employerAmount;
                }
            }
        }

        // Apply other selected deductions (exclude pensions)
        if (!empty($selectedDeductions)) {
            $otherSelected = DirectDeduction::whereIn('id', $selectedDeductions)
                ->where('status', 'active')
                ->where('deduction_type', '!=', 'pension')
                ->get();

            foreach ($otherSelected as $deduction) {
                $baseAmount = $deduction->percentage_of === 'basic' ? $basicSalary : $grossSalary;
                $employeeAmount = ($baseAmount * (float)$deduction->employee_percent) / 100;
                $employerAmount = ($baseAmount * (float)$deduction->employer_percent) / 100;

                $otherDeductions[] = [
                    'name' => $deduction->name,
                    'amount' => $employeeAmount,
                    'is_mandatory' => false
                ];
                $totalOtherDeductions += $employeeAmount;

                if ($employerAmount > 0) {
                    $employerContributions[] = [
                        'name' => $deduction->name,
                        'amount' => $employerAmount
                    ];
                    $totalEmployerContributions += $employerAmount;
                }
            }
        }

        // Calculate taxable income and PAYE
        $taxableIncome = $grossSalary - $totalPensionDeductions;
        $payeAmount = $this->calculatePAYE($taxableIncome);

        if ($payeAmount > 0) {
            $otherDeductions[] = [
                'name' => 'PAYE (Income Tax)',
                'amount' => $payeAmount,
                'is_mandatory' => true
            ];
            $totalOtherDeductions += $payeAmount;
        }

        $totalEmployeeDeductions = $totalPensionDeductions + $totalOtherDeductions;
        $takeHome = $grossSalary - $totalEmployeeDeductions;
        $totalCostToCompany = $grossSalary + $totalEmployerContributions;

        return [
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'gross_salary' => $grossSalary,
            'pension_deductions' => $pensionDeductions,
            'total_pension_deductions' => $totalPensionDeductions,
            'taxable_income' => $taxableIncome,
            'paye_amount' => $payeAmount,
            'other_deductions' => $otherDeductions,
            'total_other_deductions' => $totalOtherDeductions,
            'total_employee_deductions' => $totalEmployeeDeductions,
            'take_home' => $takeHome,
            'employer_contributions' => $employerContributions,
            'total_employer_contributions' => $totalEmployerContributions,
            'total_cost_to_company' => $totalCostToCompany
        ];
    }

    /**
     * Calculate PAYE tax using TaxTable model
     */
    private function calculatePAYE($taxableIncome)
    {
        return TaxTable::calculatePAYE($taxableIncome);
    }
}
