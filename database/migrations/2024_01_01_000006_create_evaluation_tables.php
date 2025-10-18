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
        // General factors table
        Schema::create('general_factors', function (Blueprint $table) {
            $table->id();
            $table->string('factor_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Factors table
        Schema::create('factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_factor_id')->constrained('general_factors')->onDelete('cascade');
            $table->string('factor_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Sub factors table
        Schema::create('sub_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factor_id')->constrained('factors')->onDelete('cascade');
            $table->string('sub_factor_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Rating scales table
        Schema::create('rating_scales', function (Blueprint $table) {
            $table->id();
            $table->string('scale_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Rating scale items table
        Schema::create('rating_scale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_scale_id')->constrained('rating_scales')->onDelete('cascade');
            $table->string('item_name');
            $table->integer('score');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Evaluations table
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('evaluation_name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });

        // Employee evaluations table
        Schema::create('employee_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->date('evaluation_date');
            $table->decimal('total_score', 5, 2)->nullable();
            $table->text('comments')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // Employee evaluation details table
        Schema::create('employee_evaluation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_evaluation_id')->constrained('employee_evaluations')->onDelete('cascade');
            $table->foreignId('sub_factor_id')->constrained('sub_factors')->onDelete('cascade');
            $table->foreignId('rating_scale_item_id')->constrained('rating_scale_items')->onDelete('cascade');
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
        Schema::dropIfExists('employee_evaluations');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('rating_scale_items');
        Schema::dropIfExists('rating_scales');
        Schema::dropIfExists('sub_factors');
        Schema::dropIfExists('factors');
        Schema::dropIfExists('general_factors');
    }
};
