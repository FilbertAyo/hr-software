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
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('payroll_period_id')->nullable()->after('company_id')->constrained('payroll_periods')->onDelete('set null');
            $table->integer('original_installment_count')->nullable()->after('installment_count');
            $table->boolean('is_restructured')->default(false)->after('status');
            $table->integer('restructure_count')->default(0)->after('is_restructured');
            $table->timestamp('approved_at')->nullable()->after('restructure_count');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->foreignId('rejected_by')->nullable()->after('rejected_at')->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['payroll_period_id']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'payroll_period_id',
                'original_installment_count',
                'is_restructured',
                'restructure_count',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'rejection_reason'
            ]);
        });
    }
};
