<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_map_points_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('map_points')) {
            Schema::create('map_points', function (Blueprint $table) {
                $table->id();
                
                // Informations de base
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('category')->default('general');
                $table->string('type')->default('point');
                
                // Médias pour le popup
                $table->string('main_image')->nullable();
                $table->string('youtube_url')->nullable();
                $table->string('youtube_id')->nullable();
                
                // Localisation
                $table->decimal('latitude', 10, 8);
                $table->decimal('longitude', 11, 8);
                $table->text('adresse')->nullable();
                $table->string('ville')->nullable();
                $table->string('code_postal', 20)->nullable();
                
                // Bouton more details
                $table->string('details_url')->nullable();
                $table->boolean('has_details_page')->default(false);
                
                // Relations
                $table->foreignId('etablissement_id')->nullable()
                      ->constrained('etablissements')
                      ->onDelete('cascade');
                $table->foreignId('user_id')->nullable()
                      ->constrained('users')
                      ->onDelete('set null');
                
                // Statut
                $table->boolean('is_active')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->integer('views')->default(0);
                
                $table->timestamps();
                $table->softDeletes();
                
                // Index - UN SEUL DE CHAQUE
                $table->index('category');
                $table->index(['latitude', 'longitude']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('map_points');
    }
};