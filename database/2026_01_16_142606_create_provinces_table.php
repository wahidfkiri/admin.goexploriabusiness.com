<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 2)->nullable();
            $table->string('capital')->nullable();
            $table->string('largest_city')->nullable();
            $table->string('official_language')->nullable();
            $table->string('area_rank')->nullable();
            $table->integer('population')->nullable();
            $table->decimal('area', 15, 2)->nullable();
            $table->string('timezone')->nullable();
            $table->string('flag')->nullable();
            $table->text('description')->nullable();
            
            // Coordonnées géographiques
            $table->string('latitude')->nullable();  // Ajouté
            $table->string('longitude')->nullable(); // Ajouté
            
            // Clé étrangère
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('provinces');
    }
};