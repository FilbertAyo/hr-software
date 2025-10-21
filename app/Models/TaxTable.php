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
        'tax_rate_id',
        'min_income',
        'max_income',
        'rate_percentage',
        'fixed_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'min_income' => 'decimal:2',
        'max_income' => 'decimal:2',
        'rate_percentage' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
    ];

    /**
     * Get the tax rate that owns this tax table
     */
    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    /**
     * Get tax brackets for a specific tax rate ordered by minimum income
     */
    public static function getTaxBracketsForRate($taxRateId)
    {
        return self::where('tax_rate_id', $taxRateId)
            ->orderBy('min_income')
            ->get();
    }

    /**
     * Calculate PAYE tax for given taxable income using PRIMARY tax rate
     * This is a convenience method for the default progressive tax calculation
     *
     * @param float $taxableIncome
     * @return float
     */
    public static function calculatePAYE($taxableIncome)
    {
        // Get PRIMARY tax rate
        $primaryTaxRate = TaxRate::where('tax_name', 'PRIMARY')->first();

        if (!$primaryTaxRate) {
            return 0;
        }

        return $primaryTaxRate->calculateTax($taxableIncome);
    }
}
