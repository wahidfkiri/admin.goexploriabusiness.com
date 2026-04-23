<?php
// database/migrations/2026_01_01_000000_add_presentation_fields_to_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPresentationFieldsToPlansTable extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            // Section Vision
            $table->text('vision_text')->nullable()->after('services');
            $table->text('vision_quote')->nullable()->after('vision_text');
            $table->string('vision_quote_author')->nullable()->after('vision_quote');
            
            // Section Investissement Marketing
            $table->decimal('marketing_budget', 15, 2)->nullable()->after('vision_quote_author');
            $table->text('marketing_features')->nullable()->after('marketing_budget');
            
            // Section Marchés
            $table->json('markets')->nullable()->after('marketing_features');
            $table->json('market_languages')->nullable()->after('markets');
            
            // Section Outils Marketing
            $table->json('marketing_tools')->nullable()->after('market_languages');
            
            // Section Espaces
            $table->string('space_type')->nullable()->after('marketing_tools');
            $table->json('space_features')->nullable()->after('space_type');
            
            // Section Résultats Concrets
            $table->json('concrete_results')->nullable()->after('space_features');
        });

        // Table des destinations
        Schema::create('plan_destination', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('destination_name');
            $table->string('destination_slug')->nullable();
            $table->string('destination_image')->nullable();
            $table->text('destination_description')->nullable();
            $table->string('destination_country')->nullable();
            $table->string('destination_city')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['plan_id', 'is_active']);
            $table->index('destination_slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan_destination');
        
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'vision_text', 'vision_quote', 'vision_quote_author',
                'marketing_budget', 'marketing_features',
                'markets', 'market_languages', 'marketing_tools',
                'space_type', 'space_features', 'concrete_results'
            ]);
        });
    }
}