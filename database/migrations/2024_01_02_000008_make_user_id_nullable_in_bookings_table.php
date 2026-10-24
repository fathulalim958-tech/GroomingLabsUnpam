<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            if (!Schema::hasColumn('bookings', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('bookings', 'customer_phone')) {
                $table->string('customer_phone', 30)->nullable()->after('customer_name');
            }
        });

        // Migrate existing walk-in users to bookings and remove them from users table
        $walkinUsers = DB::table('users')->where('email', 'like', '%@walkin.local')->get();
        foreach ($walkinUsers as $u) {
            DB::table('bookings')->where('user_id', $u->id)->update([
                'customer_name' => $u->name,
                'customer_phone' => $u->phone,
                'user_id' => null,
            ]);
            DB::table('users')->where('id', $u->id)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'customer_phone')) {
                $table->dropColumn('customer_phone');
            }
            if (Schema::hasColumn('bookings', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};

