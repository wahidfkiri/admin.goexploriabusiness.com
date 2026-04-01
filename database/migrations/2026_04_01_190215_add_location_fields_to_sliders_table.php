<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Ajouter les clés étrangères pour la hiérarchie
            $table->foreignId('country_id')->nullable()->after('settings')
                  ->constrained('countries')->onDelete('set null');
            $table->foreignId('province_id')->nullable()->after('country_id')
                  ->constrained('provinces')->onDelete('set null');
            $table->foreignId('region_id')->nullable()->after('province_id')
                  ->constrained('regions')->onDelete('set null');
            $table->foreignId('ville_id')->nullable()->after('region_id')
                  ->constrained('villes')->onDelete('set null');
            
            // Chemin hiérarchique complet pour recherche rapide
            $table->string('location_path')->nullable()->after('ville_id');
            
            // Index pour recherche rapide
            $table->index(['country_id', 'province_id', 'region_id', 'ville_id']);
            $table->index('location_path');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['province_id']);
            $table->dropForeign(['region_id']);
            $table->dropForeign(['ville_id']);
            $table->dropColumn(['country_id', 'province_id', 'region_id', 'ville_id', 'location_path']);
        });
    }
};