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
        Schema::create('paygrades', function (Blueprint $table) {
            $table->id();
            $table->string('grade');
            $table->string('description');
            $table->string('currency');
            $table->string('initial_amount');
            $table->string('optimal_amount');
            $table->string('step_increase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paygrades');
    }
};
