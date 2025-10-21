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
        Schema::create('earngroups', function (Blueprint $table) {
            $table->id();
            $table->string('earngroup_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

         // Allowances table
         Schema::create('allowances', function (Blueprint $table) {
            $table->id();
            $table->string('allowance_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('allowance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allowance_id')->constrained('allowances')->onDelete('cascade');
            $table->enum('calculation_type', ['amount', 'percentage']);
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('taxable')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('group_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('earngroup_id')->constrained('earngroups')->onDelete('cascade');
            $table->foreignId('allowance_id')->constrained('allowances')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Ensure unique combination of earngroup and allowance
            $table->unique(['earngroup_id', 'allowance_id']);
        });

        Schema::create('other_benefits', function (Blueprint $table) {
            $table->id();
            $table->string('other_benefit_name');
            $table->timestamps();
        });

        Schema::create('other_benefit_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('other_benefit_id')->constrained('other_benefits')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('benefit_date');
            $table->boolean('taxable')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_benefits');
        Schema::dropIfExists('allowance_details');
        Schema::dropIfExists('allowances');
        Schema::dropIfExists('earngroups');
        Schema::dropIfExists('other_benefits');
        Schema::dropIfExists('other_benefit_details');
    }
};
