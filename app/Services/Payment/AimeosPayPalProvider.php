<?php

namespace App\Services\Payment;

use Aimeos\MShop\Order\Item\Iface;
use Aimeos\MShop\Plugin\Provider\Factory\Base;
use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;

class AimeosPayPalProvider extends Base
{
    protected $gateway;

    public function __construct($context, $item)
    {
        parent::__construct($context, $item);
        
        $this->gateway = PaymentGateway::where('code', 'paypal')
            ->where('is_active', true)
            ->first();
    }

    public function process($what, $item)
    {
        switch ($what) {
            case 'check':
                return $this->check($item);
            case 'pay':
                return $this->pay($item);
            case 'status':
                return $this->status($item);
            default:
                return true;
        }
    }

    protected function check($item)
    {
        if (!$this->gateway) {
            return false;
        }
        
        return true;
    }

    protected function pay($item)
    {
        $service = new PaymentService($this->gateway);
        
        $result = $service->createPayment(
            $item->getPrice()->getValue(),
            $item->getPrice()->getCurrency(),
            [
                'order_id' => $item->getId(),
                'description' => 'Commande #' . $item->getId()
            ]
        );

        if ($result['success']) {
            $item->setAttribute('paypal_order_id', $result['order_id']);
            $item->setAttribute('paypal_approval_url', $result['approval_url']);
            
            PaymentTransaction::create([
                'etablissement_id' => auth()->user()->etablissement_id,
                'gateway_type' => 'paypal',
                'amount' => $item->getPrice()->getValue(),
                'currency' => $item->getPrice()->getCurrency(),
                'status' => 'pending',
                'gateway_transaction_id' => $result['order_id'],
                'gateway_response' => $result['data']
            ]);
        }

        return $result['success'];
    }

    protected function status($item)
    {
        $transaction = PaymentTransaction::where('gateway_transaction_id', $item->getAttribute('paypal_order_id'))
            ->first();

        if ($transaction && $transaction->status === 'completed') {
            $item->setPaymentStatus('paid');
        }

        return true;
    }
}