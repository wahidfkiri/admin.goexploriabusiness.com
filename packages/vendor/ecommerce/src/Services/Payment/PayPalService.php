<?php

namespace Vendor\Ecommerce\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    protected $gateway;
    protected $provider;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->initializePayPal();
    }

    protected function initializePayPal()
    {
        $config = [
            'mode' => $this->gateway->mode,
            'sandbox' => [
                'client_id' => $this->gateway->paypal_client_id,
                'client_secret' => $this->gateway->paypal_client_secret,
                'app_id' => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id' => $this->gateway->paypal_client_id,
                'client_secret' => $this->gateway->paypal_client_secret,
                'app_id' => '',
            ],
            'payment_action' => 'Sale',
            'currency' => 'EUR',
            'notify_url' => route('payments.webhook.paypal'),
            'locale' => 'fr_FR',
            'validate_ssl' => true,
        ];

        $this->provider = new PayPalClient($config);
        $this->provider->getAccessToken();
    }

    public function createPayment($amount, $currency = 'EUR', $metadata = [])
    {
        try {
            $data = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', '')
                        ],
                        'description' => $metadata['description'] ?? 'Paiement',
                        'custom_id' => $metadata['invoice_id'] ?? null,
                    ]
                ],
                'application_context' => [
                    'cancel_url' => route('payments.cancel'),
                    'return_url' => route('payments.success'),
                    'brand_name' => config('app.name'),
                    'locale' => 'fr-FR',
                    'landing_page' => 'BILLING',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                ]
            ];

            $order = $this->provider->createOrder($data);

            if (isset($order['id'])) {
                return [
                    'success' => true,
                    'order_id' => $order['id'],
                    'approval_url' => $order['links'][1]['href'],
                    'data' => $order
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de la création du paiement PayPal'
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Create Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function executePayment($orderId, $payerId = null)
    {
        try {
            $response = $this->provider->capturePaymentOrder($orderId);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                return [
                    'success' => true,
                    'transaction_id' => $response['purchase_units'][0]['payments']['captures'][0]['id'],
                    'data' => $response
                ];
            }

            return [
                'success' => false,
                'message' => 'Paiement non complété'
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Execute Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function refundPayment($captureId, $amount = null)
    {
        try {
            $response = $this->provider->refundCapturedPayment($captureId, $amount);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                return [
                    'success' => true,
                    'refund_id' => $response['id'],
                    'data' => $response
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur lors du remboursement'
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Refund Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function handleWebhook($payload)
    {
        $eventType = $payload['event_type'] ?? '';
        
        switch ($eventType) {
            case 'PAYMENT.CAPTURE.COMPLETED':
                $this->handlePaymentCompleted($payload);
                break;
            case 'PAYMENT.CAPTURE.REFUNDED':
                $this->handlePaymentRefunded($payload);
                break;
            case 'PAYMENT.CAPTURE.DENIED':
                $this->handlePaymentDenied($payload);
                break;
        }

        return true;
    }

    protected function handlePaymentCompleted($payload)
    {
        $resource = $payload['resource'] ?? [];
        $customId = $resource['custom_id'] ?? null;
        
        if ($customId) {
            $invoice = Invoice::find($customId);
            if ($invoice) {
                // Créer le paiement
                $payment = $invoice->payments()->create([
                    'payment_reference' => 'PAYPAL-' . $resource['id'],
                    'amount' => $resource['amount']['value'],
                    'method' => 'paypal',
                    'status' => 'complete',
                    'transaction_id' => $resource['id'],
                    'payment_date' => now(),
                ]);

                // Mettre à jour la transaction
                PaymentTransaction::where('gateway_transaction_id', $resource['id'])
                    ->update([
                        'status' => 'completed',
                        'payment_id' => $payment->id
                    ]);
            }
        }
    }

    protected function handlePaymentRefunded($payload)
    {
        // Logique pour les remboursements
    }

    protected function handlePaymentDenied($payload)
    {
        // Logique pour les paiements refusés
    }

    public function getClientConfig()
    {
        return [
            'client_id' => $this->gateway->paypal_client_id,
            'currency' => 'EUR',
            'intent' => 'capture',
            'mode' => $this->gateway->mode,
        ];
    }
}