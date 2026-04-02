<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'cms';

    public function up(): void
    {
        Schema::connection('cms')->create('cms_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('original_name');
            $table->string('filename');
            $table->string('path');
            $table->unsignedBigInteger('size')->default(0);
            $table->string('mime_type');
            $table->string('extension', 10);
            $table->string('type', 20)->default('other');
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('folder')->default('/');
            $table->boolean('is_public')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'type']);
            $table->index(['etablissement_id', 'folder']);
        });
    }

    public function down(): void
    {
        Schema::connection('cms')->dropIfExists('cms_media');
    }
};