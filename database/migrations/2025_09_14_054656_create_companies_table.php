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

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
