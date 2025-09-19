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
        Schema::create('employee_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('joining_date');
            $table->foreignId('mainstation_id')->constrained('mainstations')->onDelete('cascade');
            $table->foreignId('substation_id')->constrained('substations')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('jobtitle_id')->constrained('jobtitles')->onDelete('cascade');
            $table->foreignId('staff_level_id')->constrained('staff_levels')->onDelete('cascade');
            $table->string('wcf_no')->nullable();
            $table->boolean('hod')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_departments');
    }
};
