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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_member')->default(false)->after('is_active');
            $table->decimal('member_discount', 10, 2)->default(0)->after('is_member');
            $table->string('member_discount_type')->default('percent')->after('member_discount');
            $table->integer('member_discount_quota')->default(0)->after('member_discount_type');
            $table->integer('member_discount_remaining')->default(0)->after('member_discount_quota');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0)->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_member',
                'member_discount',
                'member_discount_type',
                'member_discount_quota',
                'member_discount_remaining',
            ]);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
};

