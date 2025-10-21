<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_name',
        'rate',
        'description',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
    ];

    /**
     * Get the tax tables (brackets) for this tax rate
     */
    public function taxTables()
    {
        return $this->hasMany(TaxTable::class);
    }

    /**
     * Get employees using this tax rate
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Calculate tax for a given taxable income
     *
     * @param float $taxableIncome
     * @return float
     */
    public function calculateTax($taxableIncome)
    {
        // If this tax rate uses progressive brackets (like PRIMARY)
        if ($this->tax_name === 'PRIMARY') {
            return $this->calculateProgressiveTax($taxableIncome);
        }

        // Otherwise, use flat rate
        return $taxableIncome * ($this->rate / 100);
    }

    /**
     * Calculate progressive tax using tax table brackets
     *
     * @param float $taxableIncome
     * @return float
     */
    private function calculateProgressiveTax($taxableIncome)
    {
        $taxBrackets = $this->taxTables()->orderBy('min_income')->get();

        if ($taxBrackets->isEmpty()) {
            // Fallback to flat rate if no brackets defined
            return $taxableIncome * ($this->rate / 100);
        }

        $totalTax = 0;

        foreach ($taxBrackets as $bracket) {
            // Skip if income is below this bracket
            if ($taxableIncome <= $bracket->min_income) {
                continue;
            }

            // Calculate the portion of income in this bracket
            $incomeInBracket = min($taxableIncome, $bracket->max_income) - $bracket->min_income;

            if ($incomeInBracket > 0) {
                // Calculate tax for this bracket
                $bracketTax = ($incomeInBracket * $bracket->rate_percentage / 100) + $bracket->fixed_amount;
                $totalTax = $bracketTax; // For Tanzania PAYE, we use the bracket's formula directly
            }

            // If we've reached the bracket containing the full income, stop
            if ($taxableIncome <= $bracket->max_income) {
                break;
            }
        }

        return max(0, $totalTax); // Ensure tax is never negative
    }
}
