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
        // database/migrations/xxxx_create_activity_province_table.php
Schema::create('activity_province', function (Blueprint $table) {
    $table->id();
    $table->foreignId('activity_id')->constrained()->onDelete('cascade');
    $table->foreignId('province_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['activity_id', 'province_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_province');
    }
};
