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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            
            // Type de client
            $table->enum('type', ['particulier', 'entreprise'])->default('particulier');
            $table->enum('civilite', ['M.', 'Mme', 'Mlle'])->nullable();
            
            // Informations personnelles
            $table->string('prenom')->nullable();
            $table->string('nom')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('telephone_secondaire')->nullable();
            
            // Informations entreprise
            $table->string('entreprise_nom')->nullable();
            $table->string('siret', 14)->nullable();
            $table->string('no_tva')->nullable();
            
            // Adresse
            $table->string('adresse')->nullable();
            $table->string('complement_adresse')->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('France');
            
            // Préférences de paiement
            $table->enum('mode_reglement_prefere', [
                'carte', 
                'virement', 
                'cheque', 
                'especes',
                'paypal',
                'prelevement'
            ])->nullable();
            
            $table->integer('delai_paiement_jours')->default(30);
            
            // Notes
            $table->text('notes')->nullable();
            
            // Statistiques
            $table->integer('total_commandes')->default(0);
            $table->decimal('chiffre_affaires_total', 10, 2)->default(0);
            $table->decimal('solde_compte', 10, 2)->default(0);
            
            // Métadonnées
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour les recherches
            $table->index(['etablissement_id', 'nom', 'prenom']);
            $table->index(['etablissement_id', 'entreprise_nom']);
            $table->index('email');
            $table->index('siret');
            $table->index('code_postal');
            $table->index('ville');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};