<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_map_point_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_point_details', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('map_point_id')
                  ->unique()
                  ->constrained('map_points')
                  ->onDelete('cascade');
            
            // Informations détaillées
            $table->text('long_description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('horaires')->nullable();     // Horaires d'ouverture
            $table->json('services')->nullable();      // Services proposés
            $table->json('tarifs')->nullable();        // Prix / tarifs
            $table->string('contact_person')->nullable();
            
            // Réseaux sociaux
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            
            // Statistiques
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('slug')->unique()->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_point_details');
    }
};