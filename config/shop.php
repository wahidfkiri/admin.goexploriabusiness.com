<?php

return [
    'payment' => [
        'paypal' => [
            'name' => 'PayPal',
            'provider' => \App\Services\Payment\AimeosPayPalProvider::class,
        ],
        'stripe' => [
            'name' => 'Stripe',
            'provider' => \App\Services\Payment\AimeosStripeProvider::class,
        ],
        'banktransfer' => [
            'name' => 'Virement bancaire',
            'provider' => \App\Services\Payment\AimeosBankTransferProvider::class,
        ],
    ],
];