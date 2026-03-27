<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'cms';

    public function up(): void
    {
        Schema::connection('cms')->create('cms_etablissement_theme', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id');
            $table->unsignedBigInteger('theme_id');
            $table->boolean('is_active')->default(false);
            $table->json('config')->nullable();
            $table->timestamps();
            
            $table->unique(['etablissement_id', 'theme_id']);
            $table->index(['etablissement_id', 'is_active']);
            $table->index(['theme_id']);
        });
    }

    public function down(): void
    {
        Schema::connection('cms')->dropIfExists('cms_etablissement_theme');
    }
};