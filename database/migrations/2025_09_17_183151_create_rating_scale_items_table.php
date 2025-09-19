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
        Schema::create('rating_scale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_scale_id')->constrained('rating_scales')->onDelete('cascade');
            $table->string('name'); // e.g., "Excellent", "Good", "Average"
            $table->decimal('score', 5, 2); // e.g., 5.00, 4.00, 3.00
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_scale_items');
    }
};
