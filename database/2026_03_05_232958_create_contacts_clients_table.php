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
        Schema::create('contacts_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('customers')->onDelete('cascade');
            
            $table->string('prenom');
            $table->string('nom');
            $table->string('poste')->nullable();
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->boolean('est_principal')->default(false);
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['client_id', 'est_principal']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts_clients');
    }
};