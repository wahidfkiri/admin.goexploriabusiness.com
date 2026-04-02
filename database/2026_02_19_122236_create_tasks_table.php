<?php
// database/migrations/2024_01_01_000002_create_tasks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('details')->nullable(); // Pour CKEditor
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assigné à
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Créateur
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->nullable(); // Lieu (texte libre ou ID selon votre structure)
            // $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            // $table->foreignId('sales_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contract_number')->nullable();
            $table->string('contact_name')->nullable();
            $table->dateTime('due_date')->nullable(); // Date de remise
            $table->dateTime('delivery_date')->nullable(); // Date de livraison
            $table->integer('estimated_hours')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->enum('status', ['pending', 'in_progress', 'test', 'integrated', 'delivered', 'approved', 'cancelled'])
                  ->default('pending');
            
            // Champs pour le suivi technique
            $table->dateTime('test_date')->nullable();
            $table->longText('test_details')->nullable();
            $table->dateTime('integration_date')->nullable();
            $table->dateTime('push_prod_date')->nullable();
            $table->string('module_url')->nullable();
            
            // Approbation
            $table->boolean('is_approved_by_manager')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            // Gestionnaires (on stocke les IDs car ce sont des relations)
            $table->foreignId('general_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('client_manager_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('status');
            $table->index('due_date');
            $table->index(['project_id', 'status']);
            $table->index(['etablissement_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};