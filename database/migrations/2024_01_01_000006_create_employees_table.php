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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('employee_name');
            $table->string('biometricID')->nullable();
            $table->string('employeeID')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->string('tin_no', 9)->nullable(); // Must be 9 digits
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->enum('residential_status', ['residential', 'non_residential'])->default('residential');
            $table->string('nida_no')->nullable();
            $table->string('employee_type')->nullable();
            $table->enum('employee_status', ['active', 'inactive','onhold'])->default('active');
            $table->unsignedBigInteger('tax_rate_id')->nullable();
            $table->enum('payment_method', ['bank','cash','both','other'])->nullable();
            $table->string('wcf_no')->nullable();
            $table->text('address')->nullable();
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('registration_step')->default('pending');

            // Salary Details (consolidated from employee_salary_details)
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->boolean('advance_option')->default(false);
            $table->decimal('advance_percentage', 5, 2)->default(0);
            $table->decimal('advance_salary', 15, 2)->nullable();
            $table->boolean('paye_exempt')->default(false);
          
            // Bank Details (consolidated from employee_bank_details)
            $table->boolean('is_primary_bank')->default(true);
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('account_no')->nullable();

            // Pension Details (consolidated from employee_pension_details)
            $table->unsignedBigInteger('pension_id')->nullable();
            $table->boolean('pension_details')->default(false);
            $table->decimal('employee_pension_amount', 15, 2)->default(0);
            $table->decimal('employer_pension_amount', 15, 2)->default(0);
            $table->string('employee_pension_no')->nullable();

            // NHIF Details (consolidated from employee_nhif_details)
            $table->boolean('nhif')->default(false);
            $table->boolean('nhif_fixed_amount')->default(false);
            $table->decimal('nhif_amount', 10, 2)->default(0);

            // Overtime Details (consolidated from employee_overtime_details)
            $table->boolean('overtime_given')->default(false);
            $table->decimal('overtime_rate_weekday', 5, 2)->default(1.50);
            $table->decimal('overtime_rate_saturday', 5, 2)->default(1.50);
            $table->decimal('overtime_rate_weekend_holiday', 5, 2)->default(2.00);
            $table->boolean('overtime_do_not_start_immediately')->default(false);
            $table->time('weekday_overtime_starts_after')->nullable();
            $table->time('saturday_overtime_starts_after')->nullable();
            $table->time('sunday_holiday_overtime_starts_after')->nullable();

            // Timing Details (consolidated from employee_timing_details)
            $table->boolean('use_office_timing')->default(true);
            $table->boolean('use_biometrics')->default(false);

            // Payment Details (consolidated from employee_payment_details)
            $table->boolean('payments')->default(false);
            $table->boolean('dynamic_payments_paid_in_rates')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
