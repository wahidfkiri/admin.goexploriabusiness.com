<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('page_revisions', function (Blueprint $table) {
            $table->id();
            
            // Référence au menu
            $table->unsignedBigInteger('menu_id');
            
            // Contenu de la révision
            $table->longText('content')->nullable()->comment('Contenu HTML de la page');
            $table->longText('styles')->nullable()->comment('Styles CSS de la page');
            $table->json('meta')->nullable()->comment('Métadonnées SEO et autres');
            
            // Informations de version
            $table->string('version', 20)->nullable()->comment('Numéro de version (ex: v1.0)');
            $table->string('change_description')->nullable()->comment('Description des changements');
            
            // Auteur de la révision
            $table->unsignedBigInteger('user_id')->nullable()->comment('Utilisateur qui a créé la révision');
            
            // Informations système
            $table->string('ip_address', 45)->nullable()->comment('Adresse IP de création');
            $table->string('user_agent')->nullable()->comment('User agent du navigateur');
            
            // Statistiques
            $table->integer('content_size')->default(0)->comment('Taille du contenu en octets');
            $table->integer('style_size')->default(0)->comment('Taille des styles en octets');
            
            // Flags
            $table->boolean('is_auto_save')->default(false)->comment('Si c\'est une sauvegarde automatique');
            $table->boolean('is_published_version')->default(false)->comment('Si cette révision était publiée');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['menu_id', 'created_at']);
            $table->index(['menu_id', 'version']);
            $table->index('user_id');
            $table->index('is_published_version');
            $table->index(['menu_id', 'is_published_version']);
            
            // Foreign keys
            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });

        // Table pour les composants sauvegardés (optionnel)
        Schema::create('page_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->string('name');
            $table->string('category')->default('custom');
            $table->longText('content');
            $table->longText('styles')->nullable();
            $table->json('attributes')->nullable();
            $table->integer('usage_count')->default(0);
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');
                  
            $table->index(['menu_id', 'category']);
        });

        // Table pour les sauvegardes automatiques
        Schema::create('page_auto_saves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('content')->nullable();
            $table->longText('styles')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
                  
            $table->index(['menu_id', 'expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_auto_saves');
        Schema::dropIfExists('page_components');
        Schema::dropIfExists('page_revisions');
    }
};