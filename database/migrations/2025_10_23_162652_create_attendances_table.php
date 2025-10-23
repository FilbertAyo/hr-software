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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('payroll_period_id');
            // $table->date('attendance_date');
            $table->enum('attendance_type', ['absent', 'late', 'present']);
            
            // Common fields
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // Late specific fields
            $table->time('late_time')->nullable();
            $table->time('expected_time')->nullable();
            $table->integer('late_minutes')->default(0);

            $table->decimal('late_hours', 5, 2)->default(0)->after('late_minutes');
            $table->integer('absent_days')->default(0)->after('is_absent');
            
            // Absent specific fields
            $table->boolean('is_absent')->default(false);
            $table->boolean('is_late')->default(false);
            
            // Additional attendance tracking
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->decimal('hours_worked', 5, 2)->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for better performance
            // $table->index(['employee_id', 'attendance_date']);
            // $table->index(['attendance_date', 'attendance_type']);
            // $table->index(['status', 'attendance_type']);
            // $table->index(['employee_id', 'attendance_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
