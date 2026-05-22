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
        Schema::create('farm_tour_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->date('preferred_date')->nullable();
            $table->string('group_size')->default('1-5');
            $table->string('package');
            $table->unsignedInteger('amount');
            $table->unsignedInteger('persons')->default(1);
            $table->text('notes')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('paystack_ref')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_tour_bookings');
    }
};
