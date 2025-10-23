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
        // Drop existing tables with foreign key constraints first
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('leave_types');

        // Create comprehensive leave_types table
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('leave_type_name');
            $table->string('other_name')->nullable();
            $table->integer('no_of_days')->default(0);
            $table->boolean('no_monthly_increment')->default(false);
            $table->boolean('extra_no_of_days')->default(false);
            $table->decimal('no_of_monthly_increment', 8, 2)->default(0);
            $table->integer('extra_days')->default(0);
            $table->boolean('show_in_web_portal')->default(true);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('description')->nullable();
            $table->boolean('carry_forward')->default(false);
            $table->integer('max_carry_forward_days')->nullable();
            $table->boolean('requires_approval')->default(true);
            $table->boolean('requires_documentation')->default(false);
            $table->enum('gender_restriction', ['All', 'Male', 'Female'])->default('All');
            $table->integer('min_service_days')->default(0);
            $table->timestamps();
        });

        // Create comprehensive leaves table
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->enum('leave_action', ['proceed', 'sold', 'emergency', 'compensatory']);
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('no_of_days');
            $table->text('remarks')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Cancelled'])->default('Pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('document_path')->nullable();
            $table->boolean('is_emergency')->default(false);
            $table->boolean('is_compensatory')->default(false);
            $table->date('compensatory_date')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['employee_id', 'leave_type_id']);
            $table->index(['status', 'from_date']);
        });

        // Create employee_leave_balances table
        Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->year('leave_year');
            $table->integer('allocated_days')->default(0);
            $table->integer('used_days')->default(0);
            $table->integer('remaining_days')->default(0);
            $table->integer('carry_forward_days')->default(0);
            $table->decimal('monthly_increment', 8, 2)->default(0);
            $table->integer('extra_days_allocated')->default(0);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');

            $table->unique(['employee_id', 'leave_type_id', 'leave_year']);
            $table->index(['employee_id', 'leave_year']);
        });

        // Create leave_approvers table
        Schema::create('leave_approvers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('approver_id');
            $table->enum('approval_level', ['Level 1', 'Level 2', 'Level 3'])->default('Level 1');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['employee_id', 'approver_id', 'approval_level']);
        });

        // Create leave_policies table
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('policy_name');
            $table->text('policy_description');
            $table->json('policy_rules'); // Store complex rules as JSON
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_policies');
        Schema::dropIfExists('leave_approvers');
        Schema::dropIfExists('employee_leave_balances');
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('leave_types');
    }
};
