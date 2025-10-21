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
        Schema::create('employee_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->enum('activity_type', ['leave', 'deduction', 'absent', 'late', 'department']);
            $table->date('activity_date');

            // Common fields
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Leave specific fields
            $table->string('leave_type')->nullable();
            $table->integer('allocated_days')->default(0);
            $table->integer('used_days')->default(0);
            $table->integer('remaining_days')->default(0);
            $table->date('leave_start_date')->nullable();
            $table->date('leave_end_date')->nullable();

            // Deduction specific fields
            $table->string('heslb_index_no')->nullable();
            $table->string('heslb_name_used')->nullable();
            $table->string('deduction_name')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('deduction_percentage', 5, 2)->default(0);
            $table->boolean('paid_by_employer')->default(false);

            // Absent specific fields
            $table->boolean('absent')->default(false);

            // Late specific fields
            $table->boolean('late')->default(false);
            $table->time('late_time')->nullable();
            $table->time('expected_time')->nullable();

            $table->timestamps();

            // Indexes for better performance
            $table->index(['employee_id', 'activity_type']);
            $table->index(['activity_date', 'activity_type']);
            $table->index(['status', 'activity_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_activities');
    }
};
