<?php
// database/migrations/2024_01_01_000001_create_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Créateur
            $table->foreignId('client_id')->nullable()->constrained('etablissements')->nullOnDelete();
            // $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contract_number')->nullable();
            $table->string('contact_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'])
                  ->default('planning');
            $table->integer('estimated_hours')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('estimated_budget', 10, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Index pour améliorer les performances
            $table->index('status');
            $table->index('is_active');
            $table->index(['etablissement_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};