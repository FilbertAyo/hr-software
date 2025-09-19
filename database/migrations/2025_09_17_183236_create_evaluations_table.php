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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('evaluation_name');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('general_factor_id')->constrained('general_factors')->onDelete('cascade'); // KPI Group
            $table->foreignId('rating_scale_id')->constrained('rating_scales')->onDelete('cascade');
            $table->date('evaluation_period_start');
            $table->date('evaluation_period_end');
            $table->text('description')->nullable();
            $table->enum('status', ['Draft', 'Active', 'Completed', 'Inactive'])->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
