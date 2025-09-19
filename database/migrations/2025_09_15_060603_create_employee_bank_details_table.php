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
        Schema::create('employee_bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->boolean('is_primary')->default(true);
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('account_no')->nullable();
            // $table->string('branch')->nullable();
            // $table->string('branch_code')->nullable();
            // $table->decimal('amount', 15, 2)->default(0);
            // $table->enum('type', ['fixed', 'percentage'])->default('fixed');

          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_bank_details');
    }
};
