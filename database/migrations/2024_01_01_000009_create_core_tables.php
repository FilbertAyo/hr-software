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
            $table->boolean('require_member_no')->default(false);
            $table->timestamps();
        });

        // Advances table
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('advance_amount', 15, 2);
            $table->date('advance_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'repaid'])->default('pending');
            $table->text('reason')->nullable();
            $table->foreignId('payroll_period_id')
            ->constrained('payroll_periods')
            ->cascadeOnDelete();
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
        Schema::dropIfExists('advances');
        Schema::dropIfExists('direct_deductions');
        Schema::dropIfExists('companies');
    }
};
