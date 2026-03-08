<?php

namespace Vendor\Ecommerce\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\Etablissement;
use App\Models\BankDetail;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);
        $gateway = PaymentGateway::where('is_active', true)
            ->where('code', $request->gateway)
            ->firstOrFail();

        $paymentService = new PaymentService($gateway);
        
        $result = $paymentService->createPayment(
            $invoice->remaining_amount,
            'EUR',
            [
                'invoice_id' => $invoice->id,
                'description' => "Facture #{$invoice->invoice_number}",
                'email' => $invoice->client->email
            ]
        );

        if ($result['success']) {
            $etablissement = Etablissement::first();
            // Créer une transaction
            PaymentTransaction::create([
                'etablissement_id' => $etablissement->id,
                'invoice_id' => $invoice->id,
                'client_id' => $invoice->client_id,
                'payment_gateway_id' => $gateway->id,
                'gateway_type' => $gateway->type,
                'amount' => $invoice->remaining_amount,
                'currency' => 'EUR',
                'status' => 'pending',
                'gateway_transaction_id' => $result['order_id'] ?? $result['payment_intent_id'],
                'gateway_response' => $result['data'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => $result['approval_url'] ?? null,
                'client_secret' => $result['client_secret'] ?? null,
                'data' => $result
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 400);
    }

    public function success(Request $request)
    {
        $token = $request->token;
        $payerId = $request->PayerID;
        
        $transaction = PaymentTransaction::where('gateway_transaction_id', $token)->first();
        
        if (!$transaction) {
            return redirect()->route('payments.failed')->with('error', 'Transaction non trouvée');
        }

        $gateway = $transaction->gateway;
        $paymentService = new PaymentService($gateway);
        
        $result = $paymentService->executePayment($token, $payerId);

        if ($result['success']) {
            // Mettre à jour la transaction
            $transaction->update([
                'status' => 'completed',
                'gateway_status' => 'completed',
                'gateway_payment_id' => $result['transaction_id']
            ]);

            return redirect()->route('invoices.show', $transaction->invoice_id)
                ->with('success', 'Paiement effectué avec succès !');
        }

        return redirect()->route('payments.failed')
            ->with('error', $result['message'] ?? 'Erreur lors du paiement');
    }

    public function cancel(Request $request)
    {
        $token = $request->token;
        
        PaymentTransaction::where('gateway_transaction_id', $token)
            ->update(['status' => 'failed']);

        return redirect()->route('invoices.index')
            ->with('error', 'Paiement annulé');
    }

    public function webhook(Request $request, $gateway)
    {
        $gateway = PaymentGateway::where('code', $gateway)->firstOrFail();
        
        $paymentService = new PaymentService($gateway);
        
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature') ?? $request->header('Paypal-Auth-Algo');
        
        $result = $paymentService->handleWebhook($payload, $signature);

        return response()->json(['status' => 'success']);
    }

    public function getGateways()
    {
        $gateways = PaymentGateway::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($gateway) {
                $service = new PaymentService($gateway);
                return [
                    'id' => $gateway->id,
                    'name' => $gateway->name,
                    'code' => $gateway->code,
                    'type' => $gateway->type,
                    'description' => $gateway->description,
                    'config' => $service->getClientConfig(),
                    'fees' => $gateway->fees
                ];
            });

        // Ajouter le virement bancaire
        $bankDetails = BankDetail::where('etablissement_id', auth()->user()->etablissement_id)
            ->where('is_default', true)
            ->first();

        if ($bankDetails) {
            $gateways->push([
                'id' => 'bank',
                'name' => 'Virement bancaire',
                'code' => 'bank_transfer',
                'type' => 'bank_transfer',
                'description' => 'Paiement par virement bancaire',
                'bank_details' => $bankDetails,
                'fees' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $gateways
        ]);
    }
}