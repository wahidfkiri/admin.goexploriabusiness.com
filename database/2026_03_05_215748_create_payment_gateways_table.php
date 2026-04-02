<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->string('name'); // PayPal, Stripe, etc.
            $table->string('code')->unique(); // paypal, stripe, etc.
            $table->enum('type', ['paypal', 'stripe', 'bank_transfer', 'other'])->default('other');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->integer('order')->default(0);
            
            // Configuration credentials (encrypted)
            $table->text('config')->nullable(); // Stockage JSON des configurations
            
            // Mode test/production
            $table->enum('mode', ['sandbox', 'live'])->default('sandbox');
            
            // Pour PayPal
            $table->string('paypal_client_id')->nullable();
            $table->string('paypal_client_secret')->nullable();
            $table->string('paypal_webhook_id')->nullable();
            
            // Pour Stripe
            $table->string('stripe_publishable_key')->nullable();
            $table->string('stripe_secret_key')->nullable();
            $table->string('stripe_webhook_secret')->nullable();
            
            // Configuration générale
            $table->json('supported_currencies')->nullable();
            $table->json('fees')->nullable(); // Frais par transaction
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
};