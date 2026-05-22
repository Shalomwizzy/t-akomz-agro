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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedInteger('total_days_in_period');
            $table->unsignedInteger('days_present')->default(0);
            $table->unsignedInteger('days_absent')->default(0);
            $table->unsignedInteger('half_days')->default(0);
            $table->decimal('base_salary', 12, 2);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('bonuses', 12, 2)->default(0);
            $table->decimal('total_payable', 12, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
