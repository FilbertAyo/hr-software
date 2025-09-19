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
        Schema::create('tax_tables', function (Blueprint $table) {
            $table->id();
            $table->decimal('min', 10, 2);
            $table->decimal('max', 10, 2);
            $table->decimal('tax_percent', 5, 2);
            $table->decimal('add_amount', 10, 2);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_tables');
    }
};
