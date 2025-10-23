<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Leave;
use App\Models\Employee;

class CheckCompletedLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaves:check-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update employee status for completed leaves';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();
        $updatedCount = 0;

        $this->info('Checking for completed leaves...');

        // Find approved leaves that have ended
        $completedLeaves = Leave::where('status', 'Approved')
            ->where('leave_action', 'proceed')
            ->where('to_date', '<', $today)
            ->with('employee')
            ->get();

        $this->info("Found {$completedLeaves->count()} completed leaves to process.");

        foreach ($completedLeaves as $leave) {
            // Check if employee has any other active leaves
            $hasActiveLeave = Leave::where('employee_id', $leave->employee_id)
                ->where('status', 'Approved')
                ->where('leave_action', 'proceed')
                ->where('from_date', '<=', $today)
                ->where('to_date', '>=', $today)
                ->exists();

            // If no active leave, reactivate employee
            if (!$hasActiveLeave && $leave->employee->employee_status === 'onhold') {
                $leave->employee->update(['employee_status' => 'active']);
                $updatedCount++;
                $this->line("Reactivated employee: {$leave->employee->employee_name} (ID: {$leave->employee->id})");
            }
        }

        $this->info("Successfully updated {$updatedCount} employee statuses.");

        return Command::SUCCESS;
    }
}
