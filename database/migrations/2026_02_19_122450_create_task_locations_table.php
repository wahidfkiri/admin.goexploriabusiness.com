<?php
// database/migrations/2024_01_01_000003_create_task_locations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade'); // Si vous avez une table locations
            $table->timestamps();
            
            $table->unique(['task_id', 'region_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_regions');
    }
};