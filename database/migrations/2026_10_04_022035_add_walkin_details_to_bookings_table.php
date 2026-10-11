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
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'customer_name')) {
                $table->string('customer_name', 100)->nullable();
            }

            if (! Schema::hasColumn('bookings', 'category')) {
                $table->string('category', 20)->nullable();
            }

            if (! Schema::hasColumn('bookings', 'service_name')) {
                $table->string('service_name', 120)->nullable();
            }

            if (! Schema::hasColumn('bookings', 'service_price')) {
                $table->unsignedInteger('service_price')->nullable();
            }

            if (! Schema::hasColumn('bookings', 'staff_name')) {
                $table->string('staff_name', 100)->nullable();
            }

            if (! Schema::hasColumn('bookings', 'cashier_id')) {
                $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('bookings', 'appointment_at')) {
                $table->dateTime('appointment_at')->nullable();
            }

            if (! Schema::hasColumn('bookings', 'status')) {
                $table->string('status')->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'cashier_id')) {
                $table->dropForeign(['cashier_id']);
                $table->dropColumn('cashier_id');
            }

            $table->dropColumn([
                'customer_name',
                'category',
                'service_name',
                'service_price',
                'staff_name',
            ]);

            if (! Schema::hasColumn('bookings', 'user_id')) {
                $table->dropColumn(['appointment_at', 'status']);
            }
        });
    }
};
