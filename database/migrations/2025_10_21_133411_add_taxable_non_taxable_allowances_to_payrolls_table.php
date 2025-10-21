<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Add columns for taxable and non-taxable allowances
            $table->decimal('taxable_allowances', 15, 2)->default(0)->after('allowances');
            $table->decimal('non_taxable_allowances', 15, 2)->default(0)->after('taxable_allowances');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['taxable_allowances', 'non_taxable_allowances']);
        });
    }
};
