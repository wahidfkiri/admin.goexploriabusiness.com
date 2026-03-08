<?php

namespace Vendor\Ecommerce\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\BankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentGatewayController extends Controller
{
    /**
     * Affiche la page de configuration
     */
    public function index()
    {
        $gateways = PaymentGateway::orderBy('order')->get();
        $bankDetails = BankDetail::where('is_default', true)
            ->first();

        // Préparer les données pour la vue
        $stripe = $gateways->where('code', 'stripe')->first();
        $paypal = $gateways->where('code', 'paypal')->first();

        return view('ecommerce::payments.gateways', compact('stripe', 'paypal', 'bankDetails'));
    }

    /**
     * Récupère la configuration actuelle
     */
    public function getConfig()
    {
        try {
            $gateways = PaymentGateway::all();
            
            $data = [];
            foreach ($gateways as $gateway) {
                $data[$gateway->code] = [
                    'id' => $gateway->id,
                    'name' => $gateway->name,
                    'code' => $gateway->code,
                    'type' => $gateway->type,
                    'is_active' => $gateway->is_active,
                    'is_default' => $gateway->is_default,
                    'order' => $gateway->order,
                    'mode' => $gateway->mode,
                    'use_env' => $gateway->use_env,
                    'fees' => $gateway->fees,
                    'supported_currencies' => $gateway->supported_currencies,
                    'description' => $gateway->description,
                    
                    // Champs spécifiques Stripe
                    'stripe_publishable_key' => $gateway->type === 'stripe' && !$gateway->use_env ? 
                        $gateway->stripe_publishable_key : null,
                    
                    // Champs spécifiques PayPal
                    'paypal_client_id' => $gateway->type === 'paypal' && !$gateway->use_env ? 
                        $gateway->paypal_client_id : null,
                ];
            }

            // Ajouter les variables d'environnement
            $data['env'] = [
                'stripe' => [
                    'key' => env('STRIPE_KEY'),
                    'has_secret' => !empty(env('STRIPE_SECRET')),
                    'has_webhook' => !empty(env('STRIPE_WEBHOOK_SECRET')),
                ],
                'paypal' => [
                    'client_id' => env('PAYPAL_CLIENT_ID'),
                    'has_secret' => !empty(env('PAYPAL_CLIENT_SECRET')),
                    'mode' => env('PAYPAL_MODE', 'sandbox'),
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur getConfig: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sauvegarde la configuration
     */
    public function saveConfig(Request $request)
    {
        try {
            $config = $request->input('config');

            // Sauvegarder Stripe
            if (isset($config['stripe'])) {
                $this->saveGatewayConfig('stripe', $config['stripe']);
            }

            // Sauvegarder PayPal
            if (isset($config['paypal'])) {
                $this->saveGatewayConfig('paypal', $config['paypal']);
            }

            // Sauvegarder configuration bancaire
            if (isset($config['bank'])) {
                $this->saveBankConfig($config['bank']);
            }

            // Sauvegarder paramètres généraux
            if (isset($config['general'])) {
                $this->saveGeneralConfig($config['general']);
            }

            // Mettre à jour le fichier .env si nécessaire
            if ($request->input('update_env', false)) {
                $this->updateEnvFile($config);
            }

            // Vider le cache de configuration
            Artisan::call('config:clear');

            return response()->json([
                'success' => true,
                'message' => 'Configuration sauvegardée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur saveConfig: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sauvegarde la configuration d'une passerelle
     */
    private function saveGatewayConfig($code, $data)
    {
        $gateway = PaymentGateway::where('code', $code)->firstOrFail();

        $updateData = [
            'is_active' => isset($data['active']) ? (bool)$data['active'] : $gateway->is_active,
            'mode' => $data['mode'] ?? $gateway->mode,
            'use_env' => isset($data['use_env']) ? (bool)$data['use_env'] : $gateway->use_env,
            'order' => $data['order'] ?? $gateway->order,
            'description' => $data['description'] ?? $gateway->description,
            'fees' => [
                'percentage' => $data['fee_percentage'] ?? ($gateway->fees['percentage'] ?? 0),
                'fixed' => $data['fee_fixed'] ?? ($gateway->fees['fixed'] ?? 0),
            ],
            'supported_currencies' => $data['currencies'] ?? $gateway->supported_currencies,
        ];

        // Si on n'utilise pas les variables d'env, sauvegarder les clés en DB
        if (!$updateData['use_env']) {
            if ($code === 'stripe') {
                $updateData['stripe_publishable_key'] = $data['publishable_key'] ?? null;
                if (!empty($data['secret_key'])) {
                    $updateData['stripe_secret_key'] = $data['secret_key'];
                }
                if (!empty($data['webhook_secret'])) {
                    $updateData['stripe_webhook_secret'] = $data['webhook_secret'];
                }
            } elseif ($code === 'paypal') {
                $updateData['paypal_client_id'] = $data['client_id'] ?? null;
                if (!empty($data['client_secret'])) {
                    $updateData['paypal_client_secret'] = $data['client_secret'];
                }
                if (!empty($data['webhook_id'])) {
                    $updateData['paypal_webhook_id'] = $data['webhook_id'];
                }
            }
        }

        $gateway->update($updateData);

        return $gateway;
    }

    /**
     * Sauvegarde la configuration bancaire
     */
    private function saveBankConfig($data)
    {
        $bankDetail = BankDetail::updateOrCreate(
            [
                'etablissement_id' => auth()->user()->etablissement_id,
                'is_default' => true
            ],
            [
                'account_holder' => $data['account_holder'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'iban' => $data['iban'] ?? null,
                'swift' => $data['bic'] ?? null,
                'branch_code' => $data['branch_code'] ?? null,
                'transit_number' => $data['transit_number'] ?? null,
                'account_number' => $data['account_number'] ?? null,
                'notes' => $data['instructions'] ?? null,
                'currency' => $data['currency'] ?? 'EUR',
            ]
        );

        return $bankDetail;
    }

    /**
     * Sauvegarde les paramètres généraux
     */
    private function saveGeneralConfig($data)
    {
        // Sauvegarder dans la table settings ou config
        settings()->set('default_currency', $data['default_currency'] ?? 'EUR');
        settings()->set('payment_confirmation_days', $data['confirmation_days'] ?? 2);
        settings()->set('payment_cancel_days', $data['cancel_days'] ?? 30);
        settings()->set('force_https', isset($data['force_https']));
        settings()->set('debug_mode', isset($data['debug_mode']));

        // Sauvegarder l'ordre des passerelles
        if (isset($data['gateway_order'])) {
            foreach ($data['gateway_order'] as $index => $code) {
                PaymentGateway::where('code', $code)->update(['order' => $index]);
            }
        }

        return true;
    }

    /**
     * Met à jour le fichier .env
     */
    private function updateEnvFile($config)
    {
        $envFile = base_path('.env');
        $content = file_get_contents($envFile);

        // Mise à jour Stripe
        if (isset($config['stripe'])) {
            $stripe = $config['stripe'];
            $content = $this->setEnvValue($content, 'STRIPE_KEY', $stripe['publishable_key'] ?? '');
            $content = $this->setEnvValue($content, 'STRIPE_SECRET', $stripe['secret_key'] ?? '');
            $content = $this->setEnvValue($content, 'STRIPE_WEBHOOK_SECRET', $stripe['webhook_secret'] ?? '');
        }

        // Mise à jour PayPal
        if (isset($config['paypal'])) {
            $paypal = $config['paypal'];
            $content = $this->setEnvValue($content, 'PAYPAL_CLIENT_ID', $paypal['client_id'] ?? '');
            $content = $this->setEnvValue($content, 'PAYPAL_CLIENT_SECRET', $paypal['client_secret'] ?? '');
            $content = $this->setEnvValue($content, 'PAYPAL_WEBHOOK_ID', $paypal['webhook_id'] ?? '');
            $content = $this->setEnvValue($content, 'PAYPAL_MODE', $paypal['mode'] ?? 'sandbox');
        }

        // Mise à jour configuration bancaire
        if (isset($config['bank'])) {
            $bank = $config['bank'];
            $content = $this->setEnvValue($content, 'BANK_ACCOUNT_HOLDER', $bank['account_holder'] ?? '');
            $content = $this->setEnvValue($content, 'BANK_NAME', $bank['bank_name'] ?? '');
            $content = $this->setEnvValue($content, 'BANK_IBAN', $bank['iban'] ?? '');
            $content = $this->setEnvValue($content, 'BANK_BIC', $bank['bic'] ?? '');
        }

        file_put_contents($envFile, $content);
    }

    /**
     * Modifie une valeur dans le fichier .env
     */
    private function setEnvValue($content, $key, $value)
    {
        if (strpos($content, $key) !== false) {
            // Remplacer la ligne existante
            return preg_replace("/{$key}=.*/", "{$key}={$value}", $content);
        } else {
            // Ajouter la ligne à la fin
            return $content . "\n{$key}={$value}";
        }
    }

    /**
     * Teste la connexion Stripe
     */
    public function testStripe(Request $request)
    {
        try {
            $config = $request->input('config');
            
            // Récupérer la clé secrète
            if (isset($config['use_env']) && $config['use_env'] == '1') {
                $secretKey = env('STRIPE_SECRET');
            } else {
                $gateway = PaymentGateway::where('code', 'stripe')->first();
                $secretKey = $gateway ? $gateway->getApiKey('secret_key') : null;
            }

            if (!$secretKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clé secrète Stripe non configurée'
                ]);
            }

            // Tester la connexion
            $stripe = new StripeClient($secretKey);
            $account = $stripe->accounts->retrieve();

            return response()->json([
                'success' => true,
                'message' => 'Connexion Stripe établie avec succès',
                'details' => [
                    'account_id' => $account->id,
                    'business_name' => $account->business_profile->name ?? 'N/A',
                    'country' => $account->country,
                    'email' => $account->email,
                ]
            ]);

        } catch (\Stripe\Exception\AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur d\'authentification: Clé API invalide'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Teste la connexion PayPal
     */
    public function testPayPal(Request $request)
    {
        try {
            $config = $request->input('config');
            
            // Récupérer les clés
            if (isset($config['use_env']) && $config['use_env'] == '1') {
                $clientId = env('PAYPAL_CLIENT_ID');
                $clientSecret = env('PAYPAL_CLIENT_SECRET');
                $mode = env('PAYPAL_MODE', 'sandbox');
            } else {
                $gateway = PaymentGateway::where('code', 'paypal')->first();
                $clientId = $gateway ? $gateway->getApiKey('client_id') : null;
                $clientSecret = $gateway ? $gateway->getApiKey('client_secret') : null;
                $mode = $gateway->mode ?? 'sandbox';
            }

            if (!$clientId || !$clientSecret) {
                return response()->json([
                    'success' => false,
                    'message' => 'Identifiants PayPal non configurés'
                ]);
            }

            // Configurer PayPal
            $config = [
                'mode' => $mode,
                'sandbox' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                ],
                'live' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                ],
                'payment_action' => 'Sale',
                'currency' => 'EUR',
                'notify_url' => '',
                'locale' => 'fr_FR',
                'validate_ssl' => true,
            ];

            $provider = new PayPalClient($config);
            $token = $provider->getAccessToken();

            return response()->json([
                'success' => true,
                'message' => 'Connexion PayPal établie avec succès',
                'details' => [
                    'mode' => $mode,
                    'token_received' => !empty($token),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Test générique de connexion
     */
    public function testConnection($gateway)
    {
        if ($gateway === 'stripe') {
            return $this->testStripe(request());
        } elseif ($gateway === 'paypal') {
            return $this->testPayPal(request());
        }

        return response()->json([
            'success' => false,
            'message' => 'Passerelle non supportée'
        ]);
    }

    /**
     * Met à jour le statut d'une passerelle
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $gateway = PaymentGateway::findOrFail($id);
            
            $request->validate([
                'is_active' => 'required|boolean'
            ]);

            $gateway->is_active = $request->is_active;
            $gateway->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}