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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_member')->default(false);
            $table->decimal('member_discount', 10, 2)->default(0);
            $table->string('member_discount_type')->default('percent');
            $table->integer('member_discount_quota')->default(0);
            $table->integer('member_discount_remaining')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('phone');
            $table->index('email');
        });

        // Add customer_id foreign key column to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('user_id')->constrained('customers')->nullOnDelete();
        });

        // Populate customers from existing registered users with role 'pelanggan'
        $pelangganUsers = DB::table('users')->where('role', 'pelanggan')->get();
        foreach ($pelangganUsers as $user) {
            $customerId = DB::table('customers')->insertGetId([
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'is_member' => $user->is_member ?? false,
                'member_discount' => $user->member_discount ?? 0,
                'member_discount_type' => $user->member_discount_type ?? 'percent',
                'member_discount_quota' => $user->member_discount_quota ?? 0,
                'member_discount_remaining' => $user->member_discount_remaining ?? 0,
                'created_at' => $user->created_at ?? now(),
                'updated_at' => $user->updated_at ?? now(),
            ]);

            DB::table('bookings')->where('user_id', $user->id)->update([
                'customer_id' => $customerId,
            ]);
        }

        // Link existing walk-in bookings to customer records
        $walkins = DB::table('bookings')
            ->whereNull('user_id')
            ->whereNotNull('customer_phone')
            ->select('customer_name', 'customer_phone')
            ->distinct()
            ->get();

        foreach ($walkins as $w) {
            $existing = DB::table('customers')->where('phone', $w->customer_phone)->first();
            if (! $existing) {
                $customerId = DB::table('customers')->insertGetId([
                    'user_id' => null,
                    'name' => $w->customer_name,
                    'phone' => $w->customer_phone,
                    'email' => null,
                    'is_member' => false,
                    'member_discount' => 0,
                    'member_discount_type' => 'percent',
                    'member_discount_quota' => 0,
                    'member_discount_remaining' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('bookings')->whereNull('user_id')->where('customer_phone', $w->customer_phone)->update([
                    'customer_id' => $customerId,
                ]);
            } else {
                DB::table('bookings')->whereNull('user_id')->where('customer_phone', $w->customer_phone)->update([
                    'customer_id' => $existing->id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'customer_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropConstrainedForeignId('customer_id');
            });
        }

        Schema::dropIfExists('customers');
    }
};
