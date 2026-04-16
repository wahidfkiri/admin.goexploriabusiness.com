<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ads Manager Configuration
    |--------------------------------------------------------------------------
    */

    'default_currency' => 'TND',

    'ad_formats' => [
        'banner'    => ['width' => 728, 'height' => 90,  'label' => 'Bannière horizontale'],
        'rectangle' => ['width' => 300, 'height' => 250, 'label' => 'Rectangle moyen'],
        'square'    => ['width' => 250, 'height' => 250, 'label' => 'Carré'],
        'skyscraper'=> ['width' => 160, 'height' => 600, 'label' => 'Gratte-ciel'],
        'leaderboard'=> ['width' => 970, 'height' => 90, 'label' => 'Leader board'],
        'interstitial'=> ['width' => 600, 'height' => 500,'label' => 'Interstitiel'],
    ],

    'pricing_models' => [
        'cpm'  => 'CPM — Coût par 1 000 impressions',
        'cpc'  => 'CPC — Coût par clic',
        'cpa'  => 'CPA — Coût par action',
        'flat' => 'Forfait journalier/mensuel',
    ],

    'ad_statuses' => [
        'draft'    => 'Brouillon',
        'pending'  => 'En attente de validation',
        'active'   => 'Active',
        'paused'   => 'Pausée',
        'expired'  => 'Expirée',
        'rejected' => 'Rejetée',
    ],

    'placement_positions' => [
        'header'          => 'En-tête de page',
        'footer'          => 'Pied de page',
        'sidebar_left'    => 'Barre latérale gauche',
        'sidebar_right'   => 'Barre latérale droite',
        'content_top'     => 'Haut du contenu',
        'content_bottom'  => 'Bas du contenu',
        'content_middle'  => 'Milieu du contenu',
        'popup'           => 'Pop-up',
        'interstitial'    => 'Interstitiel',
    ],

    'max_ads_per_zone' => 3,

    'default_cpm_rate'  => 5.00,
    'default_cpc_rate'  => 0.50,

    'tracking_pixel_url' => env('ADS_TRACKING_URL', null),

    'image_disk'   => 'public',
    'image_path'   => 'ads/images',
    'max_file_size'=> 2048, // KB

    'auto_approve'     => false,
    'click_fraud_threshold' => 10, // max clicks par IP par heure
];