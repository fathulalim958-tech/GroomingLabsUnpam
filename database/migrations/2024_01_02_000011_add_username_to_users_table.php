<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        // Populate default username for existing admin accounts
        $admins = DB::table('users')->where('role', 'admin')->whereNull('username')->get();
        foreach ($admins as $index => $admin) {
            $username = $index === 0 ? 'admin' : 'admin_' . $admin->id;
            DB::table('users')->where('id', $admin->id)->update([
                'username' => $username,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};

