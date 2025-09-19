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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('payroll_period_id');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('overtime_amount', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2);
            $table->decimal('tax_deduction', 15, 2)->default(0);
            $table->decimal('insurance_deduction', 15, 2)->default(0);
            $table->decimal('loan_deduction', 15, 2)->default(0);
            $table->decimal('other_deductions', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2);
            $table->enum('status', ['pending', 'processed', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('payroll_period_id')->references('id')->on('payroll_periods')->onDelete('cascade');
            $table->unique(['employee_id', 'payroll_period_id']);
            $table->index(['status', 'payroll_period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
