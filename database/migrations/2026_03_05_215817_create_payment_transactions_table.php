<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('payment_gateway_id')->nullable()->constrained()->onDelete('set null');
            
            $table->enum('gateway_type', ['paypal', 'stripe', 'bank_transfer'])->default('paypal');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            
            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'failed',
                'refunded',
                'partially_refunded',
                'disputed'
            ])->default('pending');
            
            // Gateway specific
            $table->string('gateway_transaction_id')->nullable();
            $table->string('gateway_payment_id')->nullable();
            $table->string('gateway_status')->nullable();
            $table->json('gateway_response')->nullable(); // Réponse brute de l'API
            
            // Pour paiements récurrents
            $table->string('subscription_id')->nullable();
            $table->string('plan_id')->nullable();
            
            // Logs et erreurs
            $table->text('error_message')->nullable();
            $table->json('error_details')->nullable();
            
            // Métadonnées
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['etablissement_id', 'status']);
            $table->index(['client_id', 'created_at']);
            $table->index('gateway_transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
};