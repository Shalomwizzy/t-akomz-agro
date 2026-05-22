<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // TAK-YYYY-NNNNN
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Customer snapshot (for guest checkout too)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            // Delivery
            $table->string('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_state');
            $table->text('delivery_notes')->nullable();
            $table->enum('delivery_type', ['PICKUP', 'STANDARD', 'EXPRESS'])->default('STANDARD');
            $table->decimal('delivery_fee', 10, 2)->default(0);

            // Financials
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();

            // Status
            $table->enum('status', [
                'PENDING', 'CONFIRMED', 'PROCESSING', 'PACKED',
                'DISPATCHED', 'OUT_FOR_DELIVERY', 'DELIVERED',
                'CANCELLED', 'REFUNDED'
            ])->default('PENDING');
            $table->enum('payment_status', ['UNPAID', 'PAID', 'PARTIALLY_PAID', 'REFUNDED'])->default('UNPAID');
            $table->string('payment_method')->nullable(); // paystack, flutterwave, cod, bank_transfer
            $table->string('payment_reference')->nullable();

            $table->text('notes')->nullable(); // admin internal notes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
