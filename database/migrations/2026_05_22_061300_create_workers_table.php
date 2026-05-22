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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('role')->default('farmhand'); // farmhand, supervisor, manager_assistant, vet, driver
            $table->enum('employment_type', ['daily', 'weekly', 'monthly', 'contract'])->default('monthly');
            $table->enum('salary_type', ['fixed', 'daily_rate', 'hourly'])->default('fixed');
            $table->decimal('salary_amount', 12, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('id_number')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
