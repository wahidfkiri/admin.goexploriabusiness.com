<?php

namespace Vendor\Ecommerce\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $gateway;
    protected $provider;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->initializeProvider();
    }

    protected function initializeProvider()
    {
        switch ($this->gateway->type) {
            case 'paypal':
                $this->provider = new PayPalService($this->gateway);
                break;
            case 'stripe':
                $this->provider = new StripeService($this->gateway);
                break;
            default:
                throw new \Exception("Unsupported payment gateway: {$this->gateway->type}");
        }
    }

    public function createPayment($amount, $currency = 'EUR', $metadata = [])
    {
        return $this->provider->createPayment($amount, $currency, $metadata);
    }

    public function executePayment($paymentId, $payerId = null)
    {
        return $this->provider->executePayment($paymentId, $payerId);
    }

    public function refundPayment($transactionId, $amount = null)
    {
        return $this->provider->refundPayment($transactionId, $amount);
    }

    public function handleWebhook($payload)
    {
        return $this->provider->handleWebhook($payload);
    }

    public function getClientConfig()
    {
        return $this->provider->getClientConfig();
    }
}