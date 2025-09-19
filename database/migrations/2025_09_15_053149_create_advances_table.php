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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('advance_amount', 12, 2);
            $table->date('request_date'); // store as YYYY-MM-DD but restrict input to month
            $table->boolean('advance_taken')->default(false);
            $table->string('remarks')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            // Ensure one advance per employee per month
            $table->unique(['employee_id', 'request_date']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
