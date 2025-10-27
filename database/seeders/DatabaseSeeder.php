<?php

namespace Database\Seeders;

use App\Models\TaxTable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(ReferenceTablesSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(PaygradesJobtitlesSeeder::class);
        $this->call(AllowancesBenefitsSeeder::class);
        $this->call(DirectDeductionsSeeder::class);
        $this->call(TaxTableSeeder::class);
        $this->call(LeaveTypesSeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(CompanyUserSeeder::class);
        $this->call(EmployeesSeeder::class);
        $this->call(PerformanceSeeder::class);

        // Reset PostgreSQL sequences after seeding
        if (DB::connection()->getDriverName() === 'pgsql') {
            $this->command->info('Resetting PostgreSQL sequences...');
            Artisan::call('db:reset-sequences');
            $this->command->info('Sequences reset successfully!');
        }
    }

}

