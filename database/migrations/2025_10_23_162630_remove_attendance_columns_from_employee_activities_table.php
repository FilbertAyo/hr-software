<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_activities', function (Blueprint $table) {
            // Remove attendance-related columns
            $table->dropColumn([
                'absent',
                'late', 
                'late_time',
                'expected_time'
            ]);
        });
        
        // For PostgreSQL, we need to recreate the enum constraint
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE employee_activities DROP CONSTRAINT IF EXISTS employee_activities_activity_type_check");
            DB::statement("ALTER TABLE employee_activities ADD CONSTRAINT employee_activities_activity_type_check CHECK (activity_type::text = ANY (ARRAY['leave'::character varying, 'deduction'::character varying, 'department'::character varying]::text[]))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_activities', function (Blueprint $table) {
            // Add back attendance-related columns
            $table->boolean('absent')->default(false);
            $table->boolean('late')->default(false);
            $table->time('late_time')->nullable();
            $table->time('expected_time')->nullable();
        });
        
        // For PostgreSQL, restore the original enum constraint
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE employee_activities DROP CONSTRAINT IF EXISTS employee_activities_activity_type_check");
            DB::statement("ALTER TABLE employee_activities ADD CONSTRAINT employee_activities_activity_type_check CHECK (activity_type::text = ANY (ARRAY['leave'::character varying, 'deduction'::character varying, 'absent'::character varying, 'late'::character varying, 'department'::character varying]::text[]))");
        }
    }
};
