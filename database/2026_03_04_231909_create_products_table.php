<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->foreignId('product_family_id')->nullable()->constrained('product_families')->onDelete('set null');
            
            // Type principal
            $table->enum('main_type', [
                'produit_physique',
                'produit_numerique',
                'service',
                'prestation',
                'forfait',
                'abonnement',
                'licence',
                'hebergement',
                'maintenance',
                'formation'
            ])->default('service');
            
            // Informations de base
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('reference')->unique();
            $table->string('barcode')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            
            // Prix et facturation
            $table->decimal('price_ht', 10, 2);
            $table->decimal('price_ttc', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(20.00);
            $table->enum('billing_unit', [
                'unite',
                'heure',
                'jour',
                'mois',
                'an',
                'forfait',
                'projet'
            ])->default('unite');
            
            // Coûts
            $table->decimal('purchase_price_ht', 10, 2)->nullable();
            $table->decimal('cost_price_ht', 10, 2)->nullable();
            
            // Pour services
            $table->integer('estimated_duration_minutes')->nullable();
            $table->boolean('requires_appointment')->default(false);
            
            // Pour abonnements
            $table->enum('billing_period', ['mensuel', 'trimestriel', 'semestriel', 'annuel'])->nullable();
            $table->boolean('has_commitment')->default(false);
            $table->integer('commitment_months')->nullable();
            
            // Stock (pour produits)
            $table->enum('stock_management', ['oui', 'non', 'sur_commande'])->default('non');
            $table->integer('current_stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->integer('maximum_stock')->nullable();
            $table->string('stock_location')->nullable();
            
            // Média
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('documents')->nullable();
            
            // Options
            $table->boolean('is_public')->default(true);
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_available_for_sale')->default(true);
            $table->date('availability_date')->nullable();
            $table->date('end_sale_date')->nullable();
            
            // Commission
            $table->decimal('commission_percentage', 5, 2)->default(0);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Statistiques
            $table->integer('views_count')->default(0);
            $table->integer('sales_count')->default(0);
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'main_type']);
            $table->index('reference');
            $table->index('product_category_id');
            $table->index('is_available_for_sale');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};