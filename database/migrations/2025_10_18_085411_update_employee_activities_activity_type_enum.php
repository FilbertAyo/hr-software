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
        Schema::table('employee_activities', function (Blueprint $table) {
            // Drop the existing enum constraint and recreate with 'department' added
            $table->dropColumn('activity_type');
        });

        Schema::table('employee_activities', function (Blueprint $table) {
            $table->enum('activity_type', ['leave', 'deduction', 'absent', 'late', 'department'])->after('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_activities', function (Blueprint $table) {
            // Drop the enum constraint and recreate without 'department'
            $table->dropColumn('activity_type');
        });

        Schema::table('employee_activities', function (Blueprint $table) {
            $table->enum('activity_type', ['leave', 'deduction', 'absent', 'late'])->after('employee_id');
        });
    }
};
