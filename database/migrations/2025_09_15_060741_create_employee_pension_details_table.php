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
        Schema::create('employee_pension_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->boolean('pension_details')->default(false);
            $table->string('pension')->nullable();
            $table->string('employee_pension_no')->nullable();
            $table->decimal('employer_percentage', 5, 2)->default(0);
            $table->decimal('employee_percentage', 5, 2)->default(0);
            $table->boolean('previous_pension_amount')->default(false);
            $table->boolean('paye_exempt')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_pension_details');
    }
};
