<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_map_point_videos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_point_videos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('map_point_id')
                  ->constrained('map_points')
                  ->onDelete('cascade');
            
            $table->string('title')->nullable();
            $table->string('youtube_url');
            $table->string('youtube_id');
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            // Index
            $table->index('map_point_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_point_videos');
    }
};