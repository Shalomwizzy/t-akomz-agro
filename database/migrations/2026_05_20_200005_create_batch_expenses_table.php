<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_batch_id')->constrained('livestock_batches')->cascadeOnDelete();
            $table->enum('type', ['feed', 'treatment', 'vaccination', 'labor', 'transport', 'equipment', 'other']);
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->string('vendor_name')->nullable();
            $table->string('receipt_file')->nullable();
            $table->date('expense_date');
            $table->unsignedBigInteger('linked_wallet_transaction_id')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_expenses');
    }
};
