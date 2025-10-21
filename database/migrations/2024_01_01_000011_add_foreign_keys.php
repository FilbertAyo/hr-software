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
        // Add foreign key constraints to payroll_periods table
        Schema::table('payroll_periods', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add foreign key constraints to employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('nationality_id')->references('id')->on('nationalities')->onDelete('set null');
            $table->foreign('religion_id')->references('id')->on('religions')->onDelete('set null');
            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->onDelete('set null');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');
            $table->foreign('pension_id')->references('id')->on('direct_deductions')->onDelete('set null');
        });

        // Add foreign key constraints to employee_contacts table
        Schema::table('employee_contacts', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // Add foreign key constraints to employee_activities table
        Schema::table('employee_activities', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_periods', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['processed_by']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['nationality_id']);
            $table->dropForeign(['religion_id']);
            $table->dropForeign(['tax_rate_id']);
            $table->dropForeign(['bank_id']);
            $table->dropForeign(['pension_id']);
        });

        Schema::table('employee_contacts', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });

        Schema::table('employee_activities', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['approved_by']);
        });
    }
};
