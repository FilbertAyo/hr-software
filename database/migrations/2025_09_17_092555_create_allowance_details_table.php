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
        Schema::create('allowance_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allowance_id');
            $table->string('amount')->nullable();
            $table->boolean('taxable')->default(false);
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_details');
    }
};
