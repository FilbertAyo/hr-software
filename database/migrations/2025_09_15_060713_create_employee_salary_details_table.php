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
        Schema::create('employee_salary_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('basic_salary', 15, 2);
            // $table->decimal('total_payments', 15, 2)->nullable();
            // $table->decimal('net_salary', 15, 2)->nullable();
            // $table->boolean('housing')->default(false);
            // $table->boolean('advance_salary')->default(false);
            // $table->decimal('advance_rate', 5, 2)->default(0);

            if (!Schema::hasColumn('employee_salary_details', 'housing_allowance')) {
                $table->decimal('housing_allowance', 15, 2)->default(0)->after('basic_salary');
            }
            if (!Schema::hasColumn('employee_salary_details', 'transport_allowance')) {
                $table->decimal('transport_allowance', 15, 2)->default(0)->after('housing_allowance');
            }
            if (!Schema::hasColumn('employee_salary_details', 'medical_allowance')) {
                $table->decimal('medical_allowance', 15, 2)->default(0)->after('transport_allowance');
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_details');
    }
};
