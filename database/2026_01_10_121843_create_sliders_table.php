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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('image'); // 'image' or 'video'
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();
            $table->string('video_type')->nullable(); // 'youtube', 'vimeo', 'upload'
            $table->text('video_url')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->json('settings')->nullable(); // Pour des paramètres supplémentaires
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('order');
            $table->index('is_active');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};