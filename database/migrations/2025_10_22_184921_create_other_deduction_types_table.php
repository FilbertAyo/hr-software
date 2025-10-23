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
        Schema::create('other_deduction_types', function (Blueprint $table) {
            $table->id();
            $table->string('deduction_type')->unique();
            $table->boolean('requires_document')->default(false);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        Schema::create('employee_other_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('other_deduction_type_id')->constrained('other_deduction_types')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('deduction_date');
            $table->text('reason')->nullable();
            $table->string('document_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'processed'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_deduction_types');
        Schema::dropIfExists('employee_other_deductions');

    }
};
