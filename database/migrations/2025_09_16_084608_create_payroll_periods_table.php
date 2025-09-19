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
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period_name'); // e.g., "January 2024"
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'processing', 'completed', 'cancelled'])->default('draft');
            $table->decimal('total_gross_amount', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('total_net_amount', 15, 2)->default(0);
            $table->integer('total_employees')->default(0);
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();

            $table->foreign('processed_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['start_date', 'end_date']);
            $table->unique(['period_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_periods');
    }
};
