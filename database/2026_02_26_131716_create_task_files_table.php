<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_task_files_table.php

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
        Schema::create('task_files', function (Blueprint $table) {
            $table->id();
            
            // Clé étrangère vers la table tasks
            $table->foreignId('task_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('ID de la tâche associée');
            
            // Relation avec l'utilisateur qui a uploadé
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Utilisateur ayant uploadé le fichier');
            
            // Informations du fichier
            $table->string('file_name')
                  ->comment('Nom système du fichier (généré)');
            
            $table->string('original_name')
                  ->comment('Nom original du fichier');
            
            $table->string('file_path')
                  ->comment('Chemin de stockage du fichier');
            
            $table->unsignedBigInteger('file_size')
                  ->nullable()
                  ->comment('Taille du fichier en bytes');
            
            $table->string('mime_type')
                  ->nullable()
                  ->comment('Type MIME du fichier');
            
            $table->string('file_extension', 20)
                  ->nullable()
                  ->comment('Extension du fichier');
            
            $table->string('storage_disk', 50)
                  ->default('public')
                  ->comment('Disque de stockage');
            
            $table->text('description')
                  ->nullable()
                  ->comment('Description du fichier');
            
            // Métadonnées JSON pour flexibilité
            $table->json('custom_properties')
                  ->nullable()
                  ->comment('Propriétés personnalisées');
            
            // Statut du fichier
            $table->boolean('is_public')
                  ->default(true)
                  ->comment('Fichier accessible publiquement');
            
            $table->boolean('is_temporary')
                  ->default(false)
                  ->comment('Fichier temporaire');
            
            $table->timestamp('expires_at')
                  ->nullable()
                  ->comment("Date d'expiration");
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour améliorer les performances
            $table->index('task_id');
            $table->index('user_id');
            $table->index('file_extension');
            $table->index('created_at');
            $table->index('expires_at');
            
            // Index composé pour les recherches courantes
            $table->index(['task_id', 'created_at']);
            $table->index(['task_id', 'file_extension']);
            
            // Index fulltext pour recherche dans les noms
            $table->fullText(['original_name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_files');
    }
};