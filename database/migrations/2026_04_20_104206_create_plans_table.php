<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('services')->nullable(); // JSON ou texte avec liste des services
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->integer('duration_days')->default(365); // Durée en jours (1 an = 365)
            $table->enum('billing_cycle', ['monthly', 'yearly', 'custom'])->default('yearly');
            $table->json('features')->nullable(); // Fonctionnalités incluses
            $table->json('limits')->nullable(); // Limites (nombre d'utilisateurs, etc.)
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
};