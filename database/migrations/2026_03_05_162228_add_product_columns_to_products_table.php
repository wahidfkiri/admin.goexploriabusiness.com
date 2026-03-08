<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Vérifier et ajouter les colonnes manquantes une par une
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('reference');
            }
            
            if (!Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('sku');
            }
            
            if (!Schema::hasColumn('products', 'product_family_id')) {
                $table->foreignId('product_family_id')->nullable()->constrained('product_families')->nullOnDelete()->after('category_id');
            }
            
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->text('short_description')->nullable()->after('long_description');
            }
            
            if (!Schema::hasColumn('products', 'price_ht')) {
                $table->decimal('price_ht', 10, 2)->nullable()->after('price_ttc');
            }
            
            if (!Schema::hasColumn('products', 'purchase_price_ht')) {
                $table->decimal('purchase_price_ht', 10, 2)->nullable()->after('price_ht');
            }
            
            if (!Schema::hasColumn('products', 'estimated_duration_minutes')) {
                $table->integer('estimated_duration_minutes')->nullable()->after('purchase_price_ht');
            }
            
            if (!Schema::hasColumn('products', 'requires_appointment')) {
                $table->boolean('requires_appointment')->default(false)->after('estimated_duration_minutes');
            }
            
            if (!Schema::hasColumn('products', 'billing_period')) {
                $table->enum('billing_period', ['mensuel', 'trimestriel', 'semestriel', 'annuel'])->nullable()->after('requires_appointment');
            }
            
            if (!Schema::hasColumn('products', 'has_commitment')) {
                $table->boolean('has_commitment')->default(false)->after('billing_period');
            }
            
            if (!Schema::hasColumn('products', 'commitment_months')) {
                $table->integer('commitment_months')->nullable()->after('has_commitment');
            }
            
            if (!Schema::hasColumn('products', 'stock_management')) {
                $table->enum('stock_management', ['oui', 'non', 'sur_commande'])->default('non')->after('commitment_months');
            }
            
            if (!Schema::hasColumn('products', 'current_stock')) {
                $table->integer('current_stock')->default(0)->after('stock_management');
            }
            
            if (!Schema::hasColumn('products', 'minimum_stock')) {
                $table->integer('minimum_stock')->default(0)->after('current_stock');
            }
            
            if (!Schema::hasColumn('products', 'maximum_stock')) {
                $table->integer('maximum_stock')->nullable()->after('minimum_stock');
            }
            
            if (!Schema::hasColumn('products', 'stock_location')) {
                $table->string('stock_location')->nullable()->after('maximum_stock');
            }
            
            if (!Schema::hasColumn('products', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('main_image');
            }
            
            if (!Schema::hasColumn('products', 'documents')) {
                $table->json('documents')->nullable()->after('gallery_images');
            }
            
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }
            
            if (!Schema::hasColumn('products', 'views_count')) {
                $table->integer('views_count')->default(0)->after('meta_keywords');
            }
            
            if (!Schema::hasColumn('products', 'sales_count')) {
                $table->integer('sales_count')->default(0)->after('views_count');
            }
            
            if (!Schema::hasColumn('products', 'commission_percentage')) {
                $table->decimal('commission_percentage', 5, 2)->default(0)->after('sales_count');
            }
            
            if (!Schema::hasColumn('products', 'metadata')) {
                $table->json('metadata')->nullable()->after('commission_percentage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'sku', 'barcode', 'product_family_id', 'short_description',
                'price_ht', 'purchase_price_ht', 'estimated_duration_minutes',
                'requires_appointment', 'billing_period', 'has_commitment',
                'commitment_months', 'stock_management', 'current_stock',
                'minimum_stock', 'maximum_stock', 'stock_location',
                'gallery_images', 'documents', 'meta_keywords',
                'views_count', 'sales_count', 'commission_percentage', 'metadata'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};