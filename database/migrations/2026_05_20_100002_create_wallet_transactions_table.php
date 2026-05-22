<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->enum('type', ['funding', 'expense_request', 'expense_approved', 'expense_spent', 'adjustment']);
            $table->decimal('amount', 15, 2);
            $table->enum('category', ['feed', 'labor', 'fuel', 'veterinary', 'equipment', 'maintenance', 'logistics', 'other']);
            $table->string('short_title');
            $table->text('description');
            $table->string('vendor_name')->nullable();
            $table->string('receipt_file')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('expense_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
