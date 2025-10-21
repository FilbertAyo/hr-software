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
        Schema::create('employee_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->enum('contact_type', ['guarantor', 'next_of_kin', 'qualification']);

            // Common fields for all contact types
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('relationship')->nullable(); // For guarantors and next of kin

            // Specific fields for qualifications
            $table->text('qualification_details')->nullable(); // For qualifications
            $table->string('institution')->nullable(); // For qualifications
            $table->date('start_date')->nullable(); // For qualifications
            $table->date('end_date')->nullable(); // For qualifications
            $table->string('grade')->nullable(); // For qualifications

            // Specific fields for guarantors
            $table->string('guarantor_occupation')->nullable();
            $table->string('guarantor_employer')->nullable();

            // Specific fields for next of kin
            $table->enum('kin_priority', ['primary', 'secondary'])->default('primary');

            $table->timestamps();

            // Indexes for better performance
            $table->index(['employee_id', 'contact_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contacts');
    }
};
