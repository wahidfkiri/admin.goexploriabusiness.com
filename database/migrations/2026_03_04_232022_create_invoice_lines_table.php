<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null');
            
            $table->integer('line_number');
            $table->string('description');
            $table->text('detailed_description')->nullable();
            $table->enum('type', ['produit', 'service', 'prestation', 'remise', 'frais'])->default('produit');
            
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            
            // Taxes (au cas où ligne spécifique)
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->onDelete('set null');
            
            $table->decimal('total', 10, 2);
            
            // Pour suivi
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['invoice_id', 'line_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_lines');
    }
};