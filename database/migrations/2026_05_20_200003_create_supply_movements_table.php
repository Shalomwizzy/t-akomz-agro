<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supply_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_supply_id')->constrained('farm_supplies')->cascadeOnDelete();
            $table->enum('type', ['stock_in', 'stock_out', 'adjustment']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('linked_wallet_transaction_id')->nullable();
            $table->unsignedBigInteger('linked_batch_id')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply_movements');
    }
};
