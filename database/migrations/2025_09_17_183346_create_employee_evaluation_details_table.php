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
        Schema::create('employee_evaluation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_evaluation_id')->constrained('employee_evaluations')->onDelete('cascade');
            $table->foreignId('factor_id')->nullable()->constrained('factors')->onDelete('cascade');
            $table->foreignId('sub_factor_id')->nullable()->constrained('sub_factors')->onDelete('cascade');
            $table->foreignId('rating_scale_item_id')->constrained('rating_scale_items')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->decimal('weighted_score', 8, 2)->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_evaluation_details');
    }
};
