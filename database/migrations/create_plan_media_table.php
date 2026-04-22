<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->enum('type', ['image', 'video']);
            $table->string('file_url');           // Full CDN/storage URL
            $table->string('file_path')->nullable(); // Relative path for local files
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->string('video_platform')->nullable(); // youtube, vimeo, upload, other
            $table->string('thumbnail_url')->nullable();  // Video thumbnail CDN URL
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_primary')->default(false); // One primary media per plan
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Add plugin and media pivot table
        Schema::create('plan_plugin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->foreignId('plugin_id')->constrained('plugins')->onDelete('cascade');
            $table->boolean('is_included')->default(true);
            $table->timestamps();
            $table->unique(['plan_id', 'plugin_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_plugin');
        Schema::dropIfExists('plan_media');
    }
};