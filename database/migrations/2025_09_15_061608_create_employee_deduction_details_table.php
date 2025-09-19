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
        Schema::create('employee_deduction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('heslb_index_no')->nullable();
            $table->string('heslb_name_used')->nullable();
            $table->string('deduction');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('deduction_percentage', 5, 2)->default(0);
            $table->boolean('paid_by_employer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_deduction_details');
    }
};
