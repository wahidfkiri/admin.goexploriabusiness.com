<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Thèmes d'emails
    |--------------------------------------------------------------------------
    */
    'themes' => [
        'modern' => [
            'name' => 'Moderne & Professionnel',
            'view' => 'emails.themes.modern',
            'description' => 'Design moderne avec dégradés et animations',
            'icon' => 'fas fa-chart-line',
            'colors' => [
                'primary' => '#667eea',
                'secondary' => '#764ba2',
            ],
        ],
        'elegant' => [
            'name' => 'Élégant & Minimaliste',
            'view' => 'emails.themes.elegant',
            'description' => 'Style épuré et sophistiqué',
            'icon' => 'fas fa-feather-alt',
            'colors' => [
                'primary' => '#8b6b4d',
                'secondary' => '#c7a17b',
            ],
        ],
        'dynamic' => [
            'name' => 'Dynamique & Coloré',
            'view' => 'emails.themes.dynamic',
            'description' => 'Design vibrant avec animations',
            'icon' => 'fas fa-bolt',
            'colors' => [
                'primary' => '#ff6b6b',
                'secondary' => '#4ecdc4',
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Thème par défaut
    |--------------------------------------------------------------------------
    */
    'default_theme' => 'modern',
    
    /*
    |--------------------------------------------------------------------------
    | Paramètres généraux
    |--------------------------------------------------------------------------
    */
    'address' => env('MAIL_MARKETING_ADDRESS', '123 Rue de l\'Innovation, 75001 Paris'),
    'phone' => env('MAIL_MARKETING_PHONE', '+33 1 23 45 67 89'),
];