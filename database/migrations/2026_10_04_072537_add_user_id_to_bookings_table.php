<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("bookings", function (Blueprint $table) {
            if (! Schema::hasColumn("bookings", "user_id")) {
                $table->foreignId("user_id")->nullable()->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn("bookings", "notes")) {
                $table->text("notes")->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table("bookings", function (Blueprint $table) {
            if (Schema::hasColumn("bookings", "user_id")) {
                $table->dropForeign(["user_id"]);
                $table->dropColumn("user_id");
            }
            if (Schema::hasColumn("bookings", "notes")) {
                $table->dropColumn("notes");
            }
        });
    }
};

