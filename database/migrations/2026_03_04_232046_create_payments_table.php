<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference')->unique();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->constrained('customers')->onDelete('cascade');
            
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->enum('method', [
                'carte',
                'virement',
                'cheque',
                'especes',
                'paypal',
                'stripe',
                'prelevement',
                'autres'
            ])->default('carte');
            
            $table->string('transaction_id')->nullable();
            $table->string('check_number')->nullable();
            $table->string('bank_name')->nullable();
            
            $table->enum('status', ['en_attente', 'complete', 'echoue', 'rembourse'])->default('en_attente');
            
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'status']);
            $table->index(['client_id', 'payment_date']);
            $table->index('invoice_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};