<?php
// database/migrations/2024_01_01_000001_create_ads_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // Table principale : publicités
        // -------------------------------------------------------
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();

            // Annonceur
            $table->unsignedBigInteger('advertiser_id')->nullable(); // user ou entreprise
            $table->string('advertiser_name')->nullable();
            $table->string('advertiser_email')->nullable();

            // Contenu
            $table->enum('type', ['image', 'html', 'video', 'text'])->default('image');
            $table->string('image_path')->nullable();
            $table->string('video_url')->nullable();
            $table->text('html_content')->nullable();
            $table->text('text_content')->nullable();

            // Lien cible
            $table->string('destination_url')->nullable();
            $table->boolean('open_new_tab')->default(true);

            // Format / dimensions
            $table->string('format')->default('rectangle'); // banner, rectangle, square…
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();

            // Planification
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Ciblage
            $table->json('target_etablissements')->nullable(); // [] = tous
            $table->json('target_categories')->nullable();
            $table->json('target_pages')->nullable();          // home, detail, list…
            $table->string('target_audience')->nullable();     // all, students, parents…

            // Modèle de tarification
            $table->enum('pricing_model', ['cpm', 'cpc', 'cpa', 'flat'])->default('cpm');
            $table->decimal('rate', 10, 4)->default(0);       // tarif unitaire
            $table->decimal('budget_total', 10, 2)->nullable();
            $table->decimal('budget_spent', 10, 2)->default(0);
            $table->decimal('budget_daily', 10, 2)->nullable();

            // Limites
            $table->integer('impression_limit')->nullable();   // 0 = illimité
            $table->integer('click_limit')->nullable();
            $table->integer('frequency_cap')->nullable();      // max vues / user / jour

            // Statut
            $table->enum('status', ['draft', 'pending', 'active', 'paused', 'expired', 'rejected'])
                  ->default('draft');
            $table->text('rejection_reason')->nullable();

            // Priorité d'affichage (1 = haute)
            $table->unsignedTinyInteger('priority')->default(5);

            // Méta
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // -------------------------------------------------------
        // Zones / emplacements d'affichage
        // -------------------------------------------------------
        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();                  // ex: sidebar_right_home
            $table->text('description')->nullable();
            $table->string('position');                        // header, footer, sidebar…
            $table->string('format')->default('rectangle');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->boolean('is_active')->default(true);

            // Sur quel(s) établissement(s)
            $table->unsignedBigInteger('etablissement_id')->nullable(); // null = global
            $table->string('page_context')->nullable();        // home, list, detail…

            $table->integer('max_ads')->default(1);
            $table->timestamps();
        });

        // -------------------------------------------------------
        // Table pivot : publicité ↔ emplacement
        // -------------------------------------------------------
        Schema::create('ad_placement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
            $table->unsignedBigInteger('placement_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
            $table->foreign('placement_id')->references('id')->on('ad_placements')->onDelete('cascade');
        });

        // -------------------------------------------------------
        // Impressions
        // -------------------------------------------------------
        Schema::create('ad_impressions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
            $table->unsignedBigInteger('placement_id')->nullable();
            $table->unsignedBigInteger('etablissement_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('page_url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_unique')->default(false);
            $table->timestamp('viewed_at');

            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
            $table->index(['ad_id', 'viewed_at']);
            $table->index(['ip_address', 'ad_id', 'viewed_at']);
        });

        // -------------------------------------------------------
        // Clics
        // -------------------------------------------------------
        Schema::create('ad_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
            $table->unsignedBigInteger('placement_id')->nullable();
            $table->unsignedBigInteger('etablissement_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('page_url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_fraud')->default(false);
            $table->decimal('cost', 10, 4)->default(0);
            $table->timestamp('clicked_at');

            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
            $table->index(['ad_id', 'clicked_at']);
            $table->index(['ip_address', 'ad_id', 'clicked_at']);
        });

        // -------------------------------------------------------
        // Rapports agrégés par jour
        // -------------------------------------------------------
        Schema::create('ad_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
            $table->unsignedBigInteger('placement_id')->nullable();
            $table->date('report_date');
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('unique_impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('fraud_clicks')->default(0);
            $table->decimal('ctr', 5, 2)->default(0);          // click-through rate %
            $table->decimal('revenue', 10, 4)->default(0);
            $table->decimal('cost', 10, 4)->default(0);
            $table->timestamps();

            $table->unique(['ad_id', 'placement_id', 'report_date']);
            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_daily_reports');
        Schema::dropIfExists('ad_clicks');
        Schema::dropIfExists('ad_impressions');
        Schema::dropIfExists('ad_placement');
        Schema::dropIfExists('ad_placements');
        Schema::dropIfExists('ads');
    }
};