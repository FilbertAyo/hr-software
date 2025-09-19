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
        Schema::create('employee_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade'); // Who is doing the evaluation
            $table->decimal('total_score', 8, 2)->nullable();
            $table->decimal('final_rating', 5, 2)->nullable();
            $table->text('overall_comments')->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Approved'])->default('Pending');
            $table->date('evaluation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_evaluations');
    }
};
