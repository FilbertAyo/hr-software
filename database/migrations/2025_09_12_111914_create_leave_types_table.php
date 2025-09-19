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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('leave_type_name');
            $table->string('other_name')->nullable();
            $table->integer('no_of_days')->default(0);

            $table->boolean('no_monthly_increment')->default(false);
            $table->boolean('extra_no_of_days')->default(false);

            $table->integer('no_of_monthly_increment')->nullable();
            $table->integer('extra_days')->nullable();

            $table->boolean('show_in_web_portal')->default(false);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
