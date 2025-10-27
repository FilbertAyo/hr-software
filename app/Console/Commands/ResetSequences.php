<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetSequences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-sequences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all PostgreSQL sequences to match current max IDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            $this->error('This command only works with PostgreSQL databases.');
            return 1;
        }

        $this->info('Resetting PostgreSQL sequences...');

        // Get all tables with sequences
        $tables = [
            'users',
            'employees',
            'companies',
            'banks',
            'departments',
            'jobtitles',
            'staff_levels',
            'nationalities',
            'religions',
            'tax_rates',
            'mainstations',
            'substations',
            'direct_deductions',
            'employee_deductions',
            'advances',
            'leaves',
            'leave_types',
            'allowances',
            'allowance_details',
            'earngroups',
            'group_benefits',
            'employee_earngroups',
            'other_benefits',
            'other_benefit_details',
            'employee_other_benefit_details',
            'loans',
            'loan_types',
            'loan_installments',
            'loan_restructures',
            'payroll_periods',
            'payrolls',
            'payroll_allowances',
            'payroll_deductions',
            'payments',
            'tax_tables',
            'employee_contacts',
            'employee_departments',
        ];

        foreach ($tables as $table) {
            try {
                // Check if table exists and has data
                $maxId = DB::table($table)->max('id');

                if ($maxId) {
                    $sequenceName = "{$table}_id_seq";
                    DB::statement("SELECT setval('{$sequenceName}', {$maxId})");
                    $this->info("✓ Reset sequence for {$table} (max ID: {$maxId})");
                }
            } catch (\Exception $e) {
                // Table might not exist or have no id column, skip it
                $this->warn("- Skipped {$table}: " . $e->getMessage());
            }
        }

        $this->info('');
        $this->info('All sequences have been reset successfully!');
        return 0;
    }
}
