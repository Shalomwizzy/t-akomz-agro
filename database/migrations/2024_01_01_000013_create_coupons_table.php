<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['PERCENTAGE', 'FIXED', 'FREE_DELIVERY']);
            $table->decimal('value', 10, 2); // % or ₦ amount
            $table->decimal('min_order_value', 10, 2)->nullable();
            $table->integer('max_uses')->nullable(); // null = unlimited
            $table->integer('used_count')->default(0);
            $table->timestamp('valid_from');
            $table->timestamp('valid_until');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
