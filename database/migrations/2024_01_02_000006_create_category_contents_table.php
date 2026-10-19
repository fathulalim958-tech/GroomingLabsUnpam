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
        Schema::create('category_contents', function (Blueprint $table) {
            $table->id();
            $table->string('category')->unique(); // 'barbershop' or 'mua'
            $table->string('title');
            $table->string('tagline')->nullable();
            $table->text('description');
            $table->string('banner_image')->nullable();
            $table->text('facilities')->nullable(); // JSON or newline list
            $table->text('terms')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_contents');
    }
};

