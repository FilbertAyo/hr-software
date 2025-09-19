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
        Schema::create('sub_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_factor_id')->constrained('general_factors')->onDelete('cascade');
            $table->foreignId('factor_id')->constrained('factors')->onDelete('cascade');
            $table->string('sub_factor_name');
            $table->text('description')->nullable();
            $table->decimal('weight', 5, 2)->default(0.00);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_factors');
    }
};
