<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_social_media_columns_to_map_point_details_table.php

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
        Schema::table('map_point_details', function (Blueprint $table) {
            // Ajout des colonnes de réseaux sociaux manquantes
            $table->string('youtube')->nullable()->after('linkedin');
            $table->string('tiktok')->nullable()->after('youtube');
            $table->string('pinterest')->nullable()->after('tiktok');
            $table->string('snapchat')->nullable()->after('pinterest');
            $table->string('whatsapp')->nullable()->after('snapchat');
            $table->string('telegram')->nullable()->after('whatsapp');
            $table->string('discord')->nullable()->after('telegram');
            $table->string('twitch')->nullable()->after('discord');
            $table->string('reddit')->nullable()->after('twitch');
            $table->string('github')->nullable()->after('reddit');
            $table->string('medium')->nullable()->after('github');
            $table->string('tumblr')->nullable()->after('medium');
            $table->string('vimeo')->nullable()->after('tumblr');
            $table->string('dribbble')->nullable()->after('vimeo');
            $table->string('behance')->nullable()->after('dribbble');
            $table->string('soundcloud')->nullable()->after('behance');
            $table->string('spotify')->nullable()->after('soundcloud');
            $table->string('tripadvisor')->nullable()->after('spotify');
            $table->string('foursquare')->nullable()->after('tripadvisor');
            $table->string('yelp')->nullable()->after('foursquare');
            $table->string('google_maps')->nullable()->after('yelp');

            // Ajout d'index pour améliorer les performances des recherches
            $table->index('facebook');
            $table->index('instagram');
            $table->index('twitter');
            $table->index('youtube');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_point_details', function (Blueprint $table) {
            // Suppression des colonnes ajoutées
            $table->dropColumn([
                'youtube',
                'tiktok',
                'pinterest',
                'snapchat',
                'whatsapp',
                'telegram',
                'discord',
                'twitch',
                'reddit',
                'github',
                'medium',
                'tumblr',
                'vimeo',
                'dribbble',
                'behance',
                'soundcloud',
                'spotify',
                'tripadvisor',
                'foursquare',
                'yelp',
                'google_maps'
            ]);

            // Suppression des index
            $table->dropIndex(['facebook']);
            $table->dropIndex(['instagram']);
            $table->dropIndex(['twitter']);
            $table->dropIndex(['youtube']);
        });
    }
};