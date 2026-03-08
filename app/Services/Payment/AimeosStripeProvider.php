<?php

namespace App\Services\Payment;

use Aimeos\MShop\Order\Item\Iface;
use Aimeos\MShop\Plugin\Provider\Factory\Base;
use Aimeos\MShop\Plugin\Provider\Iface as PluginInterface;
use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class AimeosStripeProvider extends Base implements PluginInterface
{
    protected $gateway;
    protected $stripe;
    protected $context;
    protected $item;

    /**
     * Constructor
     */
    public function __construct($context, $item)
    {
        parent::__construct($context, $item);
        
        $this->context = $context;
        $this->item = $item;
        
        // Récupérer la configuration Stripe
        $this->gateway = PaymentGateway::where('code', 'stripe')
            ->where('is_active', true)
            ->first();

        if ($this->gateway) {
            $this->initializeStripe();
        }
    }

    /**
     * Initialise le client Stripe
     */
    protected function initializeStripe()
    {
        try {
            $this->stripe = new \Stripe\StripeClient($this->gateway->stripe_secret_key);
        } catch (\Exception $e) {
            Log::error('Stripe initialization error: ' . $e->getMessage());
        }
    }

    /**
     * Processes the plugin
     *
     * @param string $what What should be processed
     * @param mixed $item Object to process
     * @return bool True if processing was successful
     */
    public function process($what, $item)
    {
        switch ($what) {
            case 'check':
                return $this->check($item);
            case 'pay':
                return $this->pay($item);
            case 'status':
                return $this->status($item);
            case 'refund':
                return $this->refund($item);
            default:
                return true;
        }
    }

    /**
     * Vérifie si le plugin est disponible
     */
    protected function check($item)
    {
        if (!$this->gateway) {
            $this->addError(['payment' => 'Stripe gateway not configured']);
            return false;
        }

        if (!$this->gateway->is_active) {
            $this->addError(['payment' => 'Stripe gateway is disabled']);
            return false;
        }

        // Vérifier la devise supportée
        $currency = $item->getPrice()->getCurrency();
        $supported = $this->gateway->supported_currencies ?? ['EUR', 'USD'];

        if (!in_array($currency, $supported)) {
            $this->addError(['payment' => "Currency {$currency} not supported by Stripe"]);
            return false;
        }

        return true;
    }

    /**
     * Traite le paiement
     */
    protected function pay($item)
    {
        try {
            // Créer un PaymentIntent Stripe
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $this->convertAmount($item->getPrice()->getValue(), $item->getPrice()->getCurrency()),
                'currency' => strtolower($item->getPrice()->getCurrency()),
                'metadata' => [
                    'order_id' => $item->getId(),
                    'order_number' => $item->getOrderNumber(),
                    'customer_email' => $item->getCustomer()->getEmail(),
                    'customer_name' => $item->getCustomer()->getFirstName() . ' ' . $item->getCustomer()->getLastName(),
                ],
                'description' => 'Commande #' . $item->getOrderNumber(),
                'receipt_email' => $item->getCustomer()->getEmail(),
                'capture_method' => 'automatic',
                'payment_method_types' => ['card'],
            ]);

            // Sauvegarder la transaction
            $transaction = PaymentTransaction::create([
                'etablissement_id' => auth()->user()->etablissement_id ?? $this->getEtablissementId($item),
                'payment_gateway_id' => $this->gateway->id,
                'gateway_type' => 'stripe',
                'amount' => $item->getPrice()->getValue(),
                'currency' => $item->getPrice()->getCurrency(),
                'status' => 'pending',
                'gateway_transaction_id' => $paymentIntent->id,
                'gateway_status' => $paymentIntent->status,
                'gateway_response' => json_encode($paymentIntent),
                'metadata' => [
                    'order_id' => $item->getId(),
                    'order_number' => $item->getOrderNumber()
                ]
            ]);

            // Stocker l'ID de transaction dans la commande
            $item->setAttribute('stripe_payment_intent_id', $paymentIntent->id);
            $item->setAttribute('stripe_client_secret', $paymentIntent->client_secret);

            return true;

        } catch (\Stripe\Exception\CardException $e) {
            $this->addError(['payment' => 'Card error: ' . $e->getError()->message]);
            Log::error('Stripe Card Error: ' . $e->getMessage());
            return false;

        } catch (\Stripe\Exception\RateLimitException $e) {
            $this->addError(['payment' => 'Too many requests - please try again later']);
            Log::error('Stripe Rate Limit: ' . $e->getMessage());
            return false;

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $this->addError(['payment' => 'Invalid request: ' . $e->getError()->message]);
            Log::error('Stripe Invalid Request: ' . $e->getMessage());
            return false;

        } catch (\Stripe\Exception\AuthenticationException $e) {
            $this->addError(['payment' => 'Authentication error with payment provider']);
            Log::error('Stripe Auth Error: ' . $e->getMessage());
            return false;

        } catch (\Stripe\Exception\ApiConnectionException $e) {
            $this->addError(['payment' => 'Network error - please try again']);
            Log::error('Stripe API Connection Error: ' . $e->getMessage());
            return false;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->addError(['payment' => 'Payment error: ' . $e->getMessage()]);
            Log::error('Stripe API Error: ' . $e->getMessage());
            return false;

        } catch (\Exception $e) {
            $this->addError(['payment' => 'An unexpected error occurred']);
            Log::error('Stripe Unexpected Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie le statut du paiement
     */
    protected function status($item)
    {
        try {
            $paymentIntentId = $item->getAttribute('stripe_payment_intent_id');
            
            if (!$paymentIntentId) {
                return false;
            }

            // Récupérer le PaymentIntent
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);

            // Mettre à jour la transaction
            $transaction = PaymentTransaction::where('gateway_transaction_id', $paymentIntentId)->first();
            
            if ($transaction) {
                $transaction->update([
                    'gateway_status' => $paymentIntent->status,
                    'gateway_response' => json_encode($paymentIntent)
                ]);
            }

            // Mettre à jour le statut de la commande en fonction du paiement
            switch ($paymentIntent->status) {
                case 'succeeded':
                    $item->setPaymentStatus('paid');
                    if ($transaction) {
                        $transaction->update(['status' => 'completed']);
                    }
                    break;

                case 'processing':
                    $item->setPaymentStatus('pending');
                    if ($transaction) {
                        $transaction->update(['status' => 'processing']);
                    }
                    break;

                case 'requires_payment_method':
                case 'requires_confirmation':
                case 'requires_action':
                    $item->setPaymentStatus('pending');
                    if ($transaction) {
                        $transaction->update(['status' => 'pending']);
                    }
                    break;

                case 'canceled':
                    $item->setPaymentStatus('canceled');
                    if ($transaction) {
                        $transaction->update(['status' => 'failed']);
                    }
                    break;

                default:
                    if ($transaction) {
                        $transaction->update(['status' => 'failed']);
                    }
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Stripe status check error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Traite un remboursement
     */
    protected function refund($item)
    {
        try {
            $paymentIntentId = $item->getAttribute('stripe_payment_intent_id');
            
            if (!$paymentIntentId) {
                $this->addError(['refund' => 'No payment intent found']);
                return false;
            }

            // Montant à rembourser (null = remboursement total)
            $amount = null;
            if (method_exists($item, 'getRefundAmount')) {
                $amount = $this->convertAmount($item->getRefundAmount(), $item->getPrice()->getCurrency());
            }

            // Créer le remboursement
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $paymentIntentId,
                'amount' => $amount,
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'order_id' => $item->getId(),
                    'order_number' => $item->getOrderNumber()
                ]
            ]);

            // Mettre à jour la transaction
            $transaction = PaymentTransaction::where('gateway_transaction_id', $paymentIntentId)->first();
            
            if ($transaction) {
                $transaction->update([
                    'status' => 'refunded',
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'refund_id' => $refund->id,
                        'refund_amount' => $refund->amount / 100,
                        'refund_status' => $refund->status,
                        'refund_date' => now()->toDateTimeString()
                    ])
                ]);
            }

            return true;

        } catch (\Exception $e) {
            $this->addError(['refund' => 'Refund failed: ' . $e->getMessage()]);
            Log::error('Stripe refund error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gère le webhook Stripe
     */
    public function handleWebhook($payload, $signature)
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $this->gateway->stripe_webhook_secret
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event->data->object);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event->data->object);
                    break;

                case 'payment_intent.canceled':
                    $this->handlePaymentIntentCanceled($event->data->object);
                    break;

                case 'charge.refunded':
                    $this->handleChargeRefunded($event->data->object);
                    break;

                case 'charge.dispute.created':
                    $this->handleDisputeCreated($event->data->object);
                    break;

                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;

                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;
            }

            return true;

        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload: ' . $e->getMessage());
            return false;

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid Stripe webhook signature: ' . $e->getMessage());
            return false;

        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gère le succès du paiement
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $orderId = $paymentIntent->metadata['order_id'] ?? null;
        
        if (!$orderId) {
            Log::warning('Stripe webhook: No order_id in metadata');
            return;
        }

        // Mettre à jour la transaction
        PaymentTransaction::where('gateway_transaction_id', $paymentIntent->id)
            ->update([
                'status' => 'completed',
                'gateway_status' => 'succeeded',
                'gateway_response' => json_encode($paymentIntent)
            ]);

        // Ici, vous pouvez également mettre à jour le statut de la commande Aimeos
        // via le service approprié
    }

    /**
     * Gère l'échec du paiement
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        PaymentTransaction::where('gateway_transaction_id', $paymentIntent->id)
            ->update([
                'status' => 'failed',
                'gateway_status' => 'failed',
                'error_message' => $paymentIntent->last_payment_error->message ?? 'Payment failed',
                'error_details' => json_encode($paymentIntent->last_payment_error ?? null)
            ]);
    }

    /**
     * Gère l'annulation du paiement
     */
    protected function handlePaymentIntentCanceled($paymentIntent)
    {
        PaymentTransaction::where('gateway_transaction_id', $paymentIntent->id)
            ->update([
                'status' => 'canceled',
                'gateway_status' => 'canceled'
            ]);
    }

    /**
     * Gère le remboursement
     */
    protected function handleChargeRefunded($charge)
    {
        PaymentTransaction::where('gateway_transaction_id', $charge->payment_intent)
            ->update([
                'status' => 'refunded',
                'gateway_status' => 'refunded',
                'metadata' => [
                    'refund_id' => $charge->refunds->data[0]->id ?? null,
                    'refund_amount' => ($charge->amount_refunded ?? 0) / 100
                ]
            ]);
    }

    /**
     * Gère les litiges
     */
    protected function handleDisputeCreated($dispute)
    {
        $paymentIntentId = $dispute->payment_intent ?? $dispute->charge;
        
        PaymentTransaction::where('gateway_transaction_id', $paymentIntentId)
            ->update([
                'status' => 'disputed',
                'gateway_status' => 'disputed',
                'metadata' => [
                    'dispute_id' => $dispute->id,
                    'dispute_reason' => $dispute->reason,
                    'dispute_status' => $dispute->status
                ]
            ]);
    }

    /**
     * Gère la création d'abonnement
     */
    protected function handleSubscriptionCreated($subscription)
    {
        Log::info('Stripe subscription created: ' . $subscription->id);
        // Implémenter la logique d'abonnement
    }

    /**
     * Gère la mise à jour d'abonnement
     */
    protected function handleSubscriptionUpdated($subscription)
    {
        Log::info('Stripe subscription updated: ' . $subscription->id);
        // Implémenter la logique de mise à jour d'abonnement
    }

    /**
     * Gère la suppression d'abonnement
     */
    protected function handleSubscriptionDeleted($subscription)
    {
        Log::info('Stripe subscription deleted: ' . $subscription->id);
        // Implémenter la logique de suppression d'abonnement
    }

    /**
     * Convertit le montant pour Stripe (en centimes)
     */
    protected function convertAmount($amount, $currency)
    {
        // Les devises sans décimales (JPY, etc.)
        $zeroDecimalCurrencies = ['JPY', 'KRW', 'VND'];
        
        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return (int) $amount;
        }
        
        // Convertir en centimes
        return (int) round($amount * 100);
    }

    /**
     * Récupère l'ID de l'établissement
     */
    protected function getEtablissementId($item)
    {
        // Logique pour récupérer l'établissement depuis la commande Aimeos
        // À adapter selon votre structure
        return auth()->user()->etablissement_id ?? 1;
    }

    /**
     * Ajoute une erreur au contexte
     */
    protected function addError(array $errors)
    {
        foreach ($errors as $code => $msg) {
            $this->context->session()->set('aimeos/plugin/errors', $msg);
        }
    }

    /**
     * Retourne la configuration Stripe pour le frontend
     */
    public function getClientConfig()
    {
        return [
            'publishable_key' => $this->gateway->stripe_publishable_key,
            'mode' => $this->gateway->mode,
            'currency' => 'eur',
            'locale' => 'fr',
            'elements' => [
                'fonts' => [
                    [
                        'cssSrc' => 'https://fonts.googleapis.com/css?family=Roboto'
                    ]
                ]
            ]
        ];
    }

    /**
     * Crée un PaymentIntent avec confirmation automatique
     */
    public function createPaymentIntent($amount, $currency = 'eur', $metadata = [])
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $this->convertAmount($amount, $currency),
                'currency' => strtolower($currency),
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ];

        } catch (\Exception $e) {
            Log::error('Stripe createPaymentIntent error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Confirme un PaymentIntent
     */
    public function confirmPaymentIntent($paymentIntentId, $paymentMethodId = null)
    {
        try {
            $params = [];
            if ($paymentMethodId) {
                $params['payment_method'] = $paymentMethodId;
            }

            $paymentIntent = $this->stripe->paymentIntents->confirm($paymentIntentId, $params);

            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'payment_intent' => $paymentIntent
            ];

        } catch (\Exception $e) {
            Log::error('Stripe confirmPaymentIntent error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Crée un client Stripe
     */
    public function createCustomer($email, $name = null, $metadata = [])
    {
        try {
            $customer = $this->stripe->customers->create([
                'email' => $email,
                'name' => $name,
                'metadata' => $metadata
            ]);

            return [
                'success' => true,
                'customer_id' => $customer->id,
                'customer' => $customer
            ];

        } catch (\Exception $e) {
            Log::error('Stripe createCustomer error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Crée un abonnement Stripe
     */
    public function createSubscription($customerId, $priceId, $metadata = [])
    {
        try {
            $subscription = $this->stripe->subscriptions->create([
                'customer' => $customerId,
                'items' => [
                    ['price' => $priceId],
                ],
                'metadata' => $metadata,
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            return [
                'success' => true,
                'subscription_id' => $subscription->id,
                'client_secret' => $subscription->latest_invoice->payment_intent->client_secret,
                'subscription' => $subscription
            ];

        } catch (\Exception $e) {
            Log::error('Stripe createSubscription error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}