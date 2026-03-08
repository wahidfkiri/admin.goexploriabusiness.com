<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            
            $table->date('renewal_date');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['en_attente', 'effectue', 'echoue', 'annule'])->default('en_attente');
            
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            $table->index(['contract_id', 'renewal_date']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('renewals');
    }
};