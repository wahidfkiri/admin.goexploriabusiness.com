<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_page_fields_to_menus_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPageFieldsToMenusTable extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Indicateur si le menu a une page dédiée
            $table->boolean('has_page')->default(false)->after('is_active');
            
            // ID de la page associée (null si pas de page)
            $table->unsignedBigInteger('page_id')->nullable()->after('has_page');
            
            // Contenu HTML de la page (pour l'éditeur GrapeJS)
            $table->longText('page_content')->nullable()->after('page_id');
            
            // Styles CSS de la page
            $table->longText('page_styles')->nullable()->after('page_content');
            
            // Métadonnées de la page
            $table->json('page_meta')->nullable()->after('page_styles');
            
            // Statut de publication de la page
            $table->enum('page_status', ['draft', 'published', 'archived'])->default('draft')->after('page_meta');
            
            // URL personnalisée pour la page
            $table->string('page_slug')->nullable()->unique()->after('page_status');
            
            // Foreign key vers la table pages si vous en avez une
            // $table->foreign('page_id')->references('id')->on('pages')->onDelete('set null');
        });
        
        // Créer la table pour les révisions de pages
        Schema::create('page_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->longText('content')->nullable();
            $table->longText('styles')->nullable();
            $table->json('meta')->nullable();
            $table->string('version')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('change_description')->nullable();
            $table->timestamps();
            
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('page_revisions');
        
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn([
                'has_page', 'page_id', 'page_content', 
                'page_styles', 'page_meta', 'page_status', 'page_slug'
            ]);
        });
    }
}