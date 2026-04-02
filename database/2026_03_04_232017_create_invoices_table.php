<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('quote_id')->nullable()->constrained('quotes')->onDelete('set null');
            
            // Dates
            $table->date('invoice_date');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            
            // Montants
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fees', 10, 2)->default(0);
            $table->decimal('administration_fees', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->default(0);
            
            // Taxes
            $table->json('taxes_breakdown')->nullable();
            
            // Statut
            $table->enum('status', [
                'brouillon',
                'envoyee',
                'en_attente',
                'payee',
                'partiellement_payee',
                'en_retard',
                'annulee',
                'avoir'
            ])->default('brouillon');
            
            // Paiement
            $table->integer('installments_count')->default(1);
            $table->string('payment_method')->nullable();
            $table->text('payment_details')->nullable();
            
            // Informations client (snapshot)
            $table->string('client_name');
            $table->string('client_address')->nullable();
            $table->string('client_zipcode')->nullable();
            $table->string('client_city')->nullable();
            $table->string('client_country')->nullable();
            $table->string('client_vat_number')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('footer')->nullable();
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'status']);
            $table->index(['client_id', 'status']);
            $table->index('invoice_number');
            $table->index('due_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};