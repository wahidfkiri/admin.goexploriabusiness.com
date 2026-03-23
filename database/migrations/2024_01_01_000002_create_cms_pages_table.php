<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'cms';

    public function up(): void
    {
        Schema::connection('cms')->create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('title');
            $table->string('slug');
            $table->longText('content')->nullable();
            $table->json('meta')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'password'])->default('public');
            $table->string('password')->nullable();
            $table->json('settings')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['etablissement_id', 'slug']);
            $table->index(['etablissement_id', 'status', 'published_at']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::connection('cms')->dropIfExists('cms_pages');
    }
};