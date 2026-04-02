<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->nullable(); // Code de région (ex: 01, 02, etc.)
            $table->string('capital')->nullable(); // Chef-lieu ou ville principale
            $table->string('largest_city')->nullable(); // Plus grande ville
            $table->string('classification')->nullable(); // Région administrative, touristique, etc.
            $table->integer('population')->nullable();
            $table->decimal('area', 15, 2)->nullable(); // en km²
            $table->integer('municipalities_count')->nullable(); // Nombre de municipalités
            $table->string('timezone')->nullable();
            $table->string('flag')->nullable(); // Drapeau ou symbole de la région
            $table->text('description')->nullable();
            $table->text('geography')->nullable(); // Description géographique
            $table->text('economy')->nullable(); // Principales activités économiques
            $table->text('tourism')->nullable(); // Attraits touristiques
            
            // Coordonnées géographiques
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            // Clé étrangère vers la province
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('regions');
    }
};