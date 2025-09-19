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
        Schema::create('sdls', function (Blueprint $table) {
            $table->id();
            $table->decimal('sdl_percentage', 5, 2)->default(0); // e.g., 10.50%
            $table->boolean('exemption')->default(false); // SDL Exemption yes/no
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdls');
    }
};
