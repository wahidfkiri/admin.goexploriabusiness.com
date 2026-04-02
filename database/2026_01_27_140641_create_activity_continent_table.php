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
        // database/migrations/xxxx_create_activity_continent_table.php
Schema::create('activity_continent', function (Blueprint $table) {
    $table->id();
    $table->foreignId('activity_id')->constrained()->onDelete('cascade');
    $table->foreignId('continent_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['activity_id', 'continent_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_continent');
    }
};
