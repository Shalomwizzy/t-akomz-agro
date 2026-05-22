<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->foreignId('allocated_to')->nullable()->constrained('users')->nullOnDelete()->after('approved_by');
            $table->string('project_name')->nullable()->after('allocated_to');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('allocated_to');
            $table->dropColumn('project_name');
        });
    }
};
