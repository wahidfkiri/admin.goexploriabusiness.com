<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('villes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->nullable(); // Code postal ou code municipal
            $table->string('classification')->nullable(); // Ville, village, municipalité, etc.
            $table->string('status')->nullable(); // Statut: capitale, métropole, etc.
            $table->integer('population')->nullable();
            $table->decimal('area', 15, 2)->nullable(); // en km²
            $table->integer('households')->nullable();
            $table->decimal('density', 10, 2)->nullable(); // Densité hab/km²
            $table->integer('altitude')->nullable(); // Altitude en mètres
            $table->string('founding_year')->nullable(); // Année de fondation
            $table->string('mayor')->nullable(); // Maire
            $table->string('website')->nullable(); // Site web officiel
            $table->text('description')->nullable();
            $table->text('history')->nullable();
            $table->text('economy')->nullable(); // Économie principale
            $table->text('attractions')->nullable(); // Attractions touristiques
            $table->text('transport')->nullable(); // Transport
            $table->text('education')->nullable(); // Établissements éducatifs
            $table->text('culture')->nullable(); // Vie culturelle
            $table->string('postal_code_prefix')->nullable(); // Préfixe postal
            
            // Coordonnées géographiques
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            // Clés étrangères
            $table->foreignId('secteur_id')->nullable()->constrained('secteurs')->onDelete('set null');
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour les performances
            $table->index(['region_id', 'province_id', 'country_id']);
            $table->index('secteur_id');
            $table->index('population');
        });
    }

    public function down()
    {
        Schema::dropIfExists('villes');
    }
};