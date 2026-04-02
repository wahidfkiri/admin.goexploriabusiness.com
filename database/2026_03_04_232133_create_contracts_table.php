<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', [
                'brouillon',
                'en_attente_signature',
                'actif',
                'en_cours',
                'suspendu',
                'resilie',
                'termine'
            ])->default('brouillon');
            
            $table->enum('type', [
                'prestation',
                'maintenance',
                'hebergement',
                'abonnement',
                'licence',
                'sav'
            ])->default('prestation');
            
            $table->decimal('amount', 10, 2);
            $table->enum('billing_frequency', ['unique', 'mensuel', 'trimestriel', 'annuel'])->default('unique');
            
            $table->boolean('auto_renew')->default(false);
            $table->integer('renewal_notice_days')->default(30);
            
            $table->text('terms')->nullable();
            $table->text('special_conditions')->nullable();
            
            $table->json('signed_document')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'status']);
            $table->index(['client_id', 'status']);
            $table->index('end_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};