// database/migrations/xxxx_xx_xx_create_blocks_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            
            // Informations de base
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('icon')->default('fa-cube');
            
            // Contenu
            $table->longText('html_content');
            $table->longText('css_content')->nullable();
            $table->longText('js_content')->nullable();
            
            // Catégorisation
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('set null');
            
            // Propriétés
            $table->string('category')->default('Basic'); // Basic, Layout, Advanced
            $table->string('website_type')->default('General'); // Ecommerce, Portfolio, Blog, Restaurant, etc.
            $table->json('tags')->nullable();
            
            // Paramètres techniques
            $table->boolean('is_responsive')->default(true);
            $table->boolean('is_free')->default(true);
            $table->integer('width')->nullable(); // Largeur recommandée
            $table->integer('height')->nullable(); // Hauteur recommandée
            
            // Statistiques
            $table->integer('usage_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->float('rating')->default(0);
            
            // Métadonnées
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blocks');
    }
};