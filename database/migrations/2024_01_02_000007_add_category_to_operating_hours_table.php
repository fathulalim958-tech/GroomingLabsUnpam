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
        if (!Schema::hasColumn('operating_hours', 'category')) {
            Schema::table('operating_hours', function (Blueprint $table) {
                $table->dropUnique(['day_of_week']);
            });

            Schema::table('operating_hours', function (Blueprint $table) {
                $table->string('category', 20)->default('barbershop')->after('id');
                $table->unique(['category', 'day_of_week']);
            });

            // Ensure existing rows are set to 'barbershop'
            DB::table('operating_hours')->whereNull('category')->orWhere('category', '')->update(['category' => 'barbershop']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('operating_hours', 'category')) {
            DB::table('operating_hours')->where('category', '!=', 'barbershop')->delete();

            Schema::table('operating_hours', function (Blueprint $table) {
                $table->dropUnique(['category', 'day_of_week']);
                $table->dropColumn('category');
                $table->unique('day_of_week');
            });
        }
    }
};

