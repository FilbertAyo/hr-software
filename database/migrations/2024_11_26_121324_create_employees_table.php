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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('biometricID')->nullable();
            $table->string('employeeID')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->string('tin_no', 9)->nullable(); // Must be 9 digits
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->enum('residential_status', ['residential', 'non_residential'])->default('residential');
            $table->string('nida_no')->nullable();
            $table->string('employee_type')->nullable();
            $table->enum('employee_status', ['active', 'inactive','onhold'])->default('active');
            $table->unsignedBigInteger('tax_rate_id')->nullable();
            $table->enum('payment_method', ['bank','cash','both','other'])->nullable();
            $table->string('wcf_no')->nullable();
            $table->text('address')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
