<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Ajouter les nouvelles colonnes nullable
            $table->foreignId('type')->nullable()->after('categorie_id')->constrained('categorie_types')->onDelete('set null');
            $table->text('description')->nullable()->after('name');
            $table->string('image')->nullable()->after('description');
            $table->text('tags')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            $table->dropColumn(['type', 'description', 'image', 'tags']);
        });
    }
};