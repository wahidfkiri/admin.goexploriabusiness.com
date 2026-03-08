<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_medias', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['image', 'video_local', 'video_youtube', 'video_vimeo', 'video_dailymotion', 'video_other'])->default('image');
            $table->string('image_path')->nullable(); // Pour les images ou thumbnail des vidéos
            $table->string('video_path')->nullable(); // Pour les vidéos locales
            $table->string('video_url')->nullable(); // Pour les vidéos externes (YouTube, Vimeo, etc.)
            $table->string('video_id')->nullable(); // ID de la vidéo (pour YouTube, Vimeo)
            $table->string('video_provider')->nullable(); // YouTube, Vimeo, etc.
            $table->integer('duration')->nullable(); // Durée en secondes
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable(); // En bytes
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->json('tags')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_medias');
    }
};