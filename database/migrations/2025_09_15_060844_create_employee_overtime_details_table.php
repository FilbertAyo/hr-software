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
        Schema::create('employee_overtime_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->boolean('overtime_given')->default(false);
            $table->decimal('overtime_rate_weekday', 5, 2)->default(1.50);
            $table->decimal('overtime_rate_saturday', 5, 2)->default(1.50);
            $table->decimal('overtime_rate_weekend_holiday', 5, 2)->default(2.00);
            $table->boolean('overtime_do_not_start_immediately')->default(false);
            $table->time('weekday_overtime_starts_after')->nullable();
            $table->time('saturday_overtime_starts_after')->nullable();
            $table->time('sunday_holiday_overtime_starts_after')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_overtime_details');
    }
};
