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
        // Skills table
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('skill_name');
            $table->timestamps();
        });

        // Languages table
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('language');
            $table->timestamps();
        });

        // Education table
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('education_level');
            $table->timestamps();
        });

        // Banks table
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('bank_code')->nullable();
            $table->timestamps();
        });

        // Relations table
        Schema::create('relations', function (Blueprint $table) {
            $table->id();
            $table->string('relation_name');
            $table->timestamps();
        });

        // Departments table
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('department_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Occupations table
        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->string('occupation_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Supervisors table
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id();
            $table->string('supervisor_name');
            $table->string('supervisor_title')->nullable();
            $table->timestamps();
        });

        // Reportings table
        Schema::create('reportings', function (Blueprint $table) {
            $table->id();
            $table->string('reporting_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

           // Pay grades table
           Schema::create('paygrades', function (Blueprint $table) {
            $table->id();
            $table->string('paygrade_name');
            $table->string('grade');
            $table->enum('currency', ['TZS', 'USD'])->default('TZS');
            $table->decimal('initial_amount', 15, 2)->nullable();
            $table->decimal('optimal_amount', 15, 2)->nullable();
            $table->decimal('step_increase', 15, 2)->nullable();
            $table->decimal('min_salary', 15, 2)->nullable();
            $table->decimal('max_salary', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('jobtitles', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->foreignId('occupation_id')->constrained('occupations')->onDelete('cascade');
            $table->foreignId('pay_grade_id')->constrained('paygrades')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();
        });


        // Main stations table
        Schema::create('mainstations', function (Blueprint $table) {
            $table->id();
            $table->string('station_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Sub stations table
        Schema::create('substations', function (Blueprint $table) {
            $table->id();
            $table->string('substation_name');
            $table->unsignedBigInteger('mainstation_id');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('mainstation_id')->references('id')->on('mainstations')->onDelete('cascade');
        });

        // Nationalities table
        Schema::create('nationalities', function (Blueprint $table) {
            $table->id();
            $table->string('nationality_name');
            $table->string('country_code', 3)->nullable();
            $table->timestamps();
        });

        // Staff levels table
        Schema::create('staff_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level_name');
            $table->integer('level_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tax tables table
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('tax_name');
            $table->decimal('rate', 5, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });


        // Tax rates table
        Schema::create('tax_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_rate_id');
            $table->decimal('min_income', 15, 2);
            $table->decimal('max_income', 15, 2)->nullable();
            $table->decimal('rate_percentage', 5, 2);
            $table->decimal('fixed_amount', 15, 2)->default(0);
            $table->timestamps();
            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->onDelete('cascade');
        });


        // Leave types table
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('leave_type_name');
            $table->integer('max_days_per_year')->default(0);
            $table->boolean('carry_forward')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Formulas table
        Schema::create('formulas', function (Blueprint $table) {
            $table->id();
            $table->string('formula_name');
            $table->text('formula_expression');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_name');
            $table->enum('payment_type', ['allowance', 'deduction', 'bonus']);
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('is_taxable')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Religions table
        Schema::create('religions', function (Blueprint $table) {
            $table->id();
            $table->string('religion_name');
            $table->timestamps();
        });

        // Holidays table
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('holiday_name');
            $table->date('holiday_date');
            $table->boolean('is_recurring')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Terminations table
        Schema::create('terminations', function (Blueprint $table) {
            $table->id();
            $table->string('termination_type');
            $table->text('description')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminations');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('religions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('formulas');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('tax_tables');
        Schema::dropIfExists('staff_levels');
        Schema::dropIfExists('nationalities');
        Schema::dropIfExists('substations');
        Schema::dropIfExists('mainstations');
        Schema::dropIfExists('jobtitles');
        Schema::dropIfExists('paygrades');
        Schema::dropIfExists('reportings');
        Schema::dropIfExists('supervisors');
        Schema::dropIfExists('occupations');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('relations');
        Schema::dropIfExists('banks');
        Schema::dropIfExists('education');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('skills');
    }
};
