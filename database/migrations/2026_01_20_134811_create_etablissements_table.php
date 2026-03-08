<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lname')->nullable(); // Si "lname" signifie "last name"
            $table->string('ville');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('adresse');
            $table->string('zip_code');
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour les performances
            $table->index('user_id');
            $table->index('ville');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etablissements');
    }
};