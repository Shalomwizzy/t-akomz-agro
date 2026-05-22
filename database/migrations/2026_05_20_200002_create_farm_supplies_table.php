<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farm_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['feed', 'medication', 'vaccination', 'equipment', 'seeds', 'chemicals', 'fuel', 'other']);
            $table->string('unit')->default('kg');
            $table->decimal('low_stock_threshold', 10, 2)->default(10);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_supplies');
    }
};
