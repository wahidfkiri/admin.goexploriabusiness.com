<?php

namespace Vendor\Ecommerce\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripeService
{
    protected $gateway;
    protected $stripe;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->stripe = new \Stripe\StripeClient($gateway->stripe_secret_key);
    }

    public function createPayment($amount, $currency = 'EUR', $metadata = [])
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount * 100, // Stripe utilise les centimes
                'currency' => strtolower($currency),
                'metadata' => $metadata,
                'description' => $metadata['description'] ?? 'Paiement',
                'receipt_email' => $metadata['email'] ?? null,
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'data' => $paymentIntent
            ];

        } catch (\Exception $e) {
            Log::error('Stripe Create Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function executePayment($paymentIntentId)
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                return [
                    'success' => true,
                    'transaction_id' => $paymentIntent->id,
                    'data' => $paymentIntent
                ];
            }

            return [
                'success' => false,
                'message' => 'Paiement non complété'
            ];

        } catch (\Exception $e) {
            Log::error('Stripe Execute Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function refundPayment($paymentIntentId, $amount = null)
    {
        try {
            $params = ['payment_intent' => $paymentIntentId];
            
            if ($amount) {
                $params['amount'] = $amount * 100;
            }

            $refund = $this->stripe->refunds->create($params);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'data' => $refund
            ];

        } catch (\Exception $e) {
            Log::error('Stripe Refund Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function createSubscription($planId, $customerId, $paymentMethodId)
    {
        try {
            $subscription = $this->stripe->subscriptions->create([
                'customer' => $customerId,
                'items' => [['price' => $planId]],
                'default_payment_method' => $paymentMethodId,
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            return [
                'success' => true,
                'subscription_id' => $subscription->id,
                'client_secret' => $subscription->latest_invoice->payment_intent->client_secret,
                'data' => $subscription
            ];

        } catch (\Exception $e) {
            Log::error('Stripe Create Subscription Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

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
                case 'charge.refunded':
                    $this->handleChargeRefunded($event->data->object);
                    break;
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return false;
        }
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $invoiceId = $paymentIntent->metadata['invoice_id'] ?? null;
        
        if ($invoiceId) {
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $payment = $invoice->payments()->create([
                    'payment_reference' => 'STRIPE-' . $paymentIntent->id,
                    'amount' => $paymentIntent->amount / 100,
                    'method' => 'stripe',
                    'status' => 'complete',
                    'transaction_id' => $paymentIntent->id,
                    'payment_date' => now(),
                ]);

                PaymentTransaction::where('gateway_transaction_id', $paymentIntent->id)
                    ->update([
                        'status' => 'completed',
                        'payment_id' => $payment->id
                    ]);
            }
        }
    }

    protected function handlePaymentIntentFailed($paymentIntent)
    {
        PaymentTransaction::where('gateway_transaction_id', $paymentIntent->id)
            ->update([
                'status' => 'failed',
                'error_message' => $paymentIntent->last_payment_error->message ?? 'Paiement échoué'
            ]);
    }

    protected function handleChargeRefunded($charge)
    {
        PaymentTransaction::where('gateway_transaction_id', $charge->payment_intent)
            ->update(['status' => 'refunded']);
    }

    protected function handleSubscriptionCreated($subscription)
    {
        // Logique pour les abonnements
    }

    protected function handleSubscriptionDeleted($subscription)
    {
        // Logique pour les abonnements annulés
    }

    public function getClientConfig()
    {
        return [
            'publishable_key' => $this->gateway->stripe_publishable_key,
            'mode' => $this->gateway->mode,
        ];
    }
}