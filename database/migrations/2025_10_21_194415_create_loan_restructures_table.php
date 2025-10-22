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
        Schema::create('loan_restructures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->foreignId('restructured_by')->constrained('users')->onDelete('cascade');
            $table->integer('old_installment_count');
            $table->integer('new_installment_count');
            $table->decimal('old_monthly_payment', 10, 2);
            $table->decimal('new_monthly_payment', 10, 2);
            $table->date('old_start_date')->nullable();
            $table->date('new_start_date')->nullable();
            $table->date('old_end_date')->nullable();
            $table->date('new_end_date')->nullable();
            $table->decimal('remaining_amount_at_restructure', 10, 2);
            $table->text('reason')->nullable();
            $table->json('old_installments_snapshot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_restructures');
    }
};
