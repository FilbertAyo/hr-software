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
        Schema::create('employee_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->foreignId('relationship_id')->nullable()->constrained('relations')->onDelete('set null');
            $table->string('mobile');
            $table->string('home_mobile')->nullable();
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('division')->nullable();
            $table->string('region')->nullable();
            $table->string('tribe')->nullable();
            $table->string('religion')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('is_dependant')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_families');
    }
};
