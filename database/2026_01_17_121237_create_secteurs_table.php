<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('secteurs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->nullable(); // Code de secteur
            $table->string('classification')->nullable(); // Type: arrondissement, quartier, etc.
            $table->integer('population')->nullable();
            $table->decimal('area', 15, 2)->nullable(); // en km²
            $table->integer('households')->nullable(); // Nombre de ménages
            $table->decimal('density', 10, 2)->nullable(); // Densité hab/km²
            $table->string('mayor')->nullable(); // Maire de l'arrondissement
            $table->string('website')->nullable(); // Site web officiel
            $table->text('description')->nullable();
            $table->text('history')->nullable(); // Historique
            $table->text('attractions')->nullable(); // Attractions principales
            $table->text('transport')->nullable(); // Transport en commun
            $table->text('education')->nullable(); // Établissements scolaires
            $table->text('parks')->nullable(); // Parcs et espaces verts
            
            // Coordonnées géographiques
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            // Clé étrangère vers la région (Montréal)
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('secteurs');
    }
};