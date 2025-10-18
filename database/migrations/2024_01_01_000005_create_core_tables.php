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
        // Companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // General Information
            $table->string('company_name');
            $table->string('company_short_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Tax / Registration
            $table->string('tin_no')->nullable();
            $table->string('vat_no')->nullable();
            $table->string('district_no')->nullable();
            $table->string('nssf_control_number')->nullable();
            $table->string('wcf_registration_number')->nullable();

            // Payroll & Leave Config
            $table->string('start_month')->nullable();   // e.g. July
            $table->year('start_year')->nullable();
            $table->decimal('leave_accumulation_per_month', 8, 2)->nullable();
            $table->boolean('alias_company_name')->default(false);

            // Overtime Rates
            $table->decimal('weekday_overtime_rate', 8, 2)->default(0);
            $table->decimal('saturday_overtime_rate', 8, 2)->default(0);
            $table->decimal('weekend_holiday_overtime_rate', 8, 2)->default(0);

            // Rates & Contributions
            $table->decimal('wcf_rate', 8, 2)->default(0);
            $table->decimal('sdl_rate', 8, 2)->default(0);
            $table->decimal('advance_rate', 8, 2)->default(0);

            // Flags
            $table->boolean('sdl_exempt')->default(false);
            $table->boolean('ot_included_wcf')->default(false);
            $table->boolean('leave_sold_included_wcf')->default(false);
            $table->boolean('max_leave_accumulated_days')->default(false);
            $table->boolean('omit_sundays_leave')->default(false);
            $table->boolean('omit_holidays_leave')->default(false);
            $table->boolean('hod_approval_required')->default(false);
            $table->boolean('approve_employee')->default(false);
            $table->boolean('bypass_advance_limit')->default(false);
            $table->boolean('leave_approve')->default(false);

            $table->timestamps();
        });

        // Direct deductions table
        Schema::create('direct_deductions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('employer_percent')->nullable();
            $table->string('employee_percent')->nullable();
            $table->enum('deduction_type', ['normal','pension'])->nullable();
            $table->enum('percentage_of', ['basic','gross'])->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->boolean('must_include')->default(false);
            $table->timestamps();
        });

        // Loans table
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('loan_type_id')->constrained('loan_types')->onDelete('cascade');
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->integer('installment_count');
            $table->decimal('monthly_payment', 15, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
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

        // Advances table
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('advance_amount', 15, 2);
            $table->date('advance_date');
            $table->date('repayment_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'repaid'])->default('pending');
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });


        // Payroll periods table
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period_name'); // e.g., "January 2024"
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'processing', 'completed', 'cancelled', 'closed'])->default('draft');
            $table->decimal('total_gross_amount', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('total_net_amount', 15, 2)->default(0);
            $table->integer('total_employees')->default(0);
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->foreign('processed_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['start_date', 'end_date']);
            $table->unique(['period_name']);
        });

        // Payrolls table
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

        // Payroll deductions table
        Schema::create('payroll_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->string('deduction_name');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        // Payroll allowances table
        Schema::create('payroll_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->string('allowance_name');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        // Allowances table
        Schema::create('allowances', function (Blueprint $table) {
            $table->id();
            $table->string('allowance_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Allowance details table
        Schema::create('allowance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allowance_id')->constrained('allowances')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        // Leaves table
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Company users table
        Schema::create('company_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['company_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_users');
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('allowance_details');
        Schema::dropIfExists('allowances');
        Schema::dropIfExists('payroll_allowances');
        Schema::dropIfExists('payroll_deductions');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('payroll_periods');
        Schema::dropIfExists('advances');
        Schema::dropIfExists('loan_installments');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('direct_deductions');
        Schema::dropIfExists('companies');
    }
};
