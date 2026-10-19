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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staffs')->nullOnDelete();
            $table->string('service_category'); // 'barbershop' or 'mua'
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('queue_number'); // e.g. 'B-001', 'M-001'
            $table->text('notes')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('waiting'); // waiting, in_progress, skipped, completed, cancelled
            $table->timestamps();

            $table->index('booking_date');
            $table->index('status');
            $table->index('queue_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
