<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livestock_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_name');
            $table->enum('animal_type', ['pig', 'chicken', 'cattle', 'goat', 'sheep', 'fish', 'turkey', 'other']);
            $table->unsignedInteger('quantity_purchased');
            $table->unsignedInteger('quantity_current');
            $table->decimal('purchase_cost', 15, 2);
            $table->date('purchase_date');
            $table->date('expected_sale_date')->nullable();
            $table->enum('status', ['active', 'partially_sold', 'sold', 'closed'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livestock_batches');
    }
};
