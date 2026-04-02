<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'cms';

    public function up(): void
    {
        Schema::connection('cms')->create('cms_themes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('path');
            $table->string('preview_image')->nullable();
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->string('version')->default('1.0.0');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::connection('cms')->dropIfExists('cms_themes');
    }
};