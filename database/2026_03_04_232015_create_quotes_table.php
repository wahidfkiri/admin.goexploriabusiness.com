<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            
            $table->date('quote_date');
            $table->date('valid_until');
            $table->date('accepted_date')->nullable();
            $table->date('rejected_date')->nullable();
            
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fees', 10, 2)->default(0);
            $table->decimal('administration_fees', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            $table->json('taxes_breakdown')->nullable();
            
            $table->enum('status', [
                'brouillon',
                'envoye',
                'en_attente',
                'accepte',
                'refuse',
                'expire',
                'converti_en_facture',
                'annule'
            ])->default('brouillon');
            
            $table->text('notes')->nullable();
            $table->text('conditions')->nullable();
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'status']);
            $table->index(['client_id', 'status']);
            $table->index('valid_until');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotes');
    }
};