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
        Schema::table('payments', function (Blueprint $table) {
            // Drop old columns that don't match the form
            $table->dropColumn(['amount', 'percentage', 'is_taxable', 'description']);

            // Change payment_type enum to match form values
            $table->dropColumn('payment_type');
            $table->enum('payment_type', ['Dynamic', 'Static'])->default('Static')->after('payment_name');

            // Add new columns that match the form
            $table->boolean('rate_check')->default(false)->after('payment_type');
            $table->decimal('payment_rate', 5, 2)->nullable()->after('rate_check');
            $table->string('status')->default('Active')->after('payment_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Reverse the changes
            $table->dropColumn(['rate_check', 'payment_rate', 'status']);

            $table->dropColumn('payment_type');
            $table->enum('payment_type', ['allowance', 'deduction', 'bonus'])->after('payment_name');

            $table->decimal('amount', 15, 2)->nullable()->after('payment_type');
            $table->decimal('percentage', 5, 2)->nullable()->after('amount');
            $table->boolean('is_taxable')->default(true)->after('percentage');
            $table->text('description')->nullable()->after('is_taxable');
        });
    }
};
