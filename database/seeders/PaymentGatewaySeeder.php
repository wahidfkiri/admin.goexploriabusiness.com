<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run()
    {
        $etablissement = \App\Models\Etablissement::first();
        $gateways = [
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'type' => 'paypal',
                'is_active' => true,
                'is_default' => true,
                'etablissement_id' => $etablissement->id,
                'order' => 1,
                'mode' => 'sandbox',
                'description' => 'Paiement sécurisé par PayPal',
                'supported_currencies' => ['EUR', 'USD', 'GBP'],
                'fees' => [
                    'percentage' => 2.9,
                    'fixed' => 0.35
                ]
            ],
            [
                'name' => 'Stripe',
                'code' => 'stripe',
                'type' => 'stripe',
                'etablissement_id' => $etablissement->id,
                'is_active' => true,
                'order' => 2,
                'mode' => 'sandbox',
                'description' => 'Paiement par carte bancaire avec Stripe',
                'supported_currencies' => ['EUR', 'USD', 'GBP', 'CHF'],
                'fees' => [
                    'percentage' => 1.4,
                    'fixed' => 0.25
                ]
            ]
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::updateOrCreate(
                ['code' => $gateway['code']],
                $gateway
            );
        }
    }
}