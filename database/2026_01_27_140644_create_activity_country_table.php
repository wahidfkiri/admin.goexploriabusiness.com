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
        // database/migrations/xxxx_create_activity_country_table.php
Schema::create('activity_country', function (Blueprint $table) {
    $table->id();
    $table->foreignId('activity_id')->constrained()->onDelete('cascade');
    $table->foreignId('country_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['activity_id', 'country_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_country');
    }
};
