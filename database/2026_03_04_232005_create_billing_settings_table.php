<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('billing_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            
            // Configuration des factures
            $table->boolean('hide_invoice_button')->default(false);
            $table->string('last_invoice_number')->default('F-26000');
            $table->string('invoice_prefix')->default('F-');
            $table->integer('invoice_number_length')->default(5);
            
            // Numéros de taxes (Québec/Canada)
            $table->string('tax_number_tps')->nullable();
            $table->string('tax_number_tvq')->nullable();
            $table->string('neq')->nullable();
            
            // Frais par défaut
            $table->decimal('default_shipping_fees', 10, 2)->nullable();
            $table->decimal('default_administration_fees', 10, 2)->nullable();
            $table->decimal('default_discount_percentage', 5, 2)->nullable();
            
            // Paiement
            $table->string('cheque_order')->nullable();
            $table->json('bank_details')->nullable();
            $table->string('payment_button_code')->nullable();
            
            // Instructions
            $table->text('procedure')->nullable();
            $table->text('instructions')->nullable();
            $table->text('default_note')->nullable();
            
            // Délais
            $table->integer('payment_deadline_days')->default(30);
            $table->integer('quote_validity_days')->default(30);
            
            // Mentions légales
            $table->text('legal_mentions')->nullable();
            $table->string('rcs_number')->nullable();
            $table->string('siret')->nullable();
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['etablissement_id'], 'billing_settings_etablissement_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('billing_settings');
    }
};