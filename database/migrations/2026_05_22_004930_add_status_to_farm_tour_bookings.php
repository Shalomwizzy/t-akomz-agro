<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('farm_tour_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('farm_tour_bookings', 'booking_status')) {
                $table->string('booking_status')->default('pending')->after('payment_status');
            }
            if (!Schema::hasColumn('farm_tour_bookings', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('booking_status');
            }
            if (!Schema::hasColumn('farm_tour_bookings', 'alternative_date')) {
                $table->date('alternative_date')->nullable()->after('admin_note');
            }
            if (!Schema::hasColumn('farm_tour_bookings', 'confirmed_date')) {
                $table->date('confirmed_date')->nullable()->after('alternative_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('farm_tour_bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_status', 'admin_note', 'alternative_date', 'confirmed_date']);
        });
    }
};
