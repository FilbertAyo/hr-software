<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxTable extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'min',
        'max',
        'tax_percent',
        'add_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'min' => 'decimal:2',
        'max' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'add_amount' => 'decimal:2',
    ];

    /**
     * Get tax brackets ordered by minimum amount
     */
    public static function getTaxBrackets()
    {
        return self::orderBy('min')->get();
    }

    /**
     * Calculate PAYE tax for given taxable income
     */
    public static function calculatePAYE($taxableIncome)
    {
        $taxBrackets = self::getTaxBrackets();

        if ($taxBrackets->isEmpty()) {
            return 0; // No tax brackets defined
        }

        $tax = 0;

        foreach ($taxBrackets as $bracket) {
            // Check if income falls in this bracket
            if ($taxableIncome > $bracket->min) {
                // Calculate taxable amount in this bracket
                $taxableInThisBracket = min($taxableIncome, $bracket->max) - $bracket->min;

                if ($taxableInThisBracket > 0) {
                    // Add tax for this bracket
                    $tax += ($taxableInThisBracket * $bracket->tax_percent / 100) + $bracket->add_amount;
                }
            }

            // If we've reached the bracket containing the full income, stop
            if ($taxableIncome <= $bracket->max) {
                break;
            }
        }

        return max(0, $tax); // Ensure tax is never negative
    }
}
