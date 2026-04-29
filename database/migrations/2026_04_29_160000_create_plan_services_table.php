<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->nullable()->index();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->enum('service_type', ['free', 'paid'])->default('free');
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 3)->default('CAD');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('main_media_type', ['image', 'video_upload', 'video_url'])->default('image');
            $table->string('main_image_path')->nullable();
            $table->string('main_video_path')->nullable();
            $table->string('main_video_url')->nullable();
            $table->json('gallery')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_services');
    }
};

