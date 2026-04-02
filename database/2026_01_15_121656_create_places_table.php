<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('category')->nullable();
            $table->json('images')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_id')->nullable();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->nullable()->constrained()->onDelete('set null');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->time('opening_hours')->nullable();
            $table->time('closing_hours')->nullable();
            $table->decimal('price_range', 8, 2)->nullable();
            $table->integer('rating')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('tags')->nullable();
            $table->json('amenities')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};