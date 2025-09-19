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
        Schema::create('payroll_allowances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id');
            $table->string('allowance_type'); // housing, transport, medical, etc.
            $table->string('allowance_name');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
            $table->index(['payroll_id', 'allowance_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_allowances');
    }
};
