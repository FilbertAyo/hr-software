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
        // Loan types table
        Schema::create('loan_types', function (Blueprint $table) {
            $table->id();
            $table->string('loan_type_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        // Loans table
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('loan_type_id')->constrained('loan_types')->onDelete('cascade');
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->integer('installment_count')->nullable();
            $table->foreignId('payroll_period_id')->nullable()->after('company_id')->constrained('payroll_periods')->onDelete('set null');
            $table->integer('original_installment_count')->nullable()->after('installment_count');
            $table->boolean('is_restructured')->default(false)->after('status');
            $table->integer('restructure_count')->default(0)->after('is_restructured');
            $table->timestamp('approved_at')->nullable()->after('restructure_count');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->foreignId('rejected_by')->nullable()->after('rejected_at')->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
            $table->decimal('monthly_payment', 15, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Loan installments table
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->integer('installment_number');
            $table->decimal('amount', 15, 2);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_installments');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('loan_types');
    }
};
