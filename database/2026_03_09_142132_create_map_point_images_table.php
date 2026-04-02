<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_map_point_images_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_point_images', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('map_point_id')
                  ->constrained('map_points')
                  ->onDelete('cascade');
            
            $table->string('image');
            $table->string('thumbnail')->nullable();
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_main')->default(false);
            
            $table->timestamps();
            
            // Index
            $table->index('map_point_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_point_images');
    }
};