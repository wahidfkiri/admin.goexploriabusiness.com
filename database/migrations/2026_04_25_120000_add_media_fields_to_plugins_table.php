<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->string('main_media_type', 20)->nullable()->after('description');
            $table->string('main_image_path')->nullable()->after('main_media_type');
            $table->string('main_video_path')->nullable()->after('main_image_path');
            $table->json('gallery_images')->nullable()->after('main_video_path');
        });
    }

    public function down(): void
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->dropColumn(['main_media_type', 'main_image_path', 'main_video_path', 'gallery_images']);
        });
    }
};
