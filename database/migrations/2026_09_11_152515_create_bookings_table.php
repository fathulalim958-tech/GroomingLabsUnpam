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
        if (! Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
                $table->dateTime('appointment_at');
                $table->string('status')->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['appointment_at', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'user_id')) {
            Schema::dropIfExists('bookings');
        }
    }
};
