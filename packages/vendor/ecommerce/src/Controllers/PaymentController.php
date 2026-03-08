<?php

namespace Vendor\Ecommerce\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Payment::with(['client', 'invoice', 'receiver']);
            
            // Apply filters
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('payment_reference', 'like', '%' . $request->search . '%')
                      ->orWhere('transaction_id', 'like', '%' . $request->search . '%')
                      ->orWhereHas('client', function($cq) use ($request) {
                          $cq->where('name', 'like', '%' . $request->search . '%');
                      });
                });
            }
            
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->method) {
                $query->where('method', $request->method);
            }
            
            if ($request->date_from) {
                $query->whereDate('payment_date', '>=', $request->date_from);
            }
            
            if ($request->date_to) {
                $query->whereDate('payment_date', '<=', $request->date_to);
            }
            
            $payments = $query->orderBy('payment_date', 'desc')
                ->paginate(15);
            
            return response()->json([
                'success' => true,
                'data' => $payments->items(),
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'total' => $payments->total()
            ]);
        }
        
        return view('ecommerce::payments.index');
    }

    /**
     * Show payment details.
     */
    public function show($id)
    {
        try {
            $payment = Payment::with(['client', 'invoice', 'receiver', 'etablissement'])
                ->findOrFail($id);

            // Get related payments for the same invoice
            $relatedPayments = collect();
            if ($payment->invoice_id) {
                $relatedPayments = Payment::where('invoice_id', $payment->invoice_id)
                    ->where('id', '!=', $payment->id)
                    ->orderBy('payment_date', 'desc')
                    ->get();
            }

            // Get client payment history
            $clientHistory = Payment::where('client_id', $payment->client_id)
                ->where('id', '!=', $payment->id)
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get();

            // Calculate statistics
            $stats = [
                'total_paid' => Payment::where('client_id', $payment->client_id)
                    ->where('status', 'complete')
                    ->sum('amount'),
                'payment_count' => Payment::where('client_id', $payment->client_id)
                    ->where('status', 'complete')
                    ->count(),
                'average_payment' => Payment::where('client_id', $payment->client_id)
                    ->where('status', 'complete')
                    ->avg('amount'),
            ];

            return view('ecommerce::payments.show', compact(
                'payment', 
                'relatedPayments', 
                'clientHistory', 
                'stats'
            ));

        } catch (\Exception $e) {
            return redirect()->route('payments.index')
                ->with('error', 'Paiement non trouvé.');
        }
    }

    /**
     * Update payment status.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            $request->validate([
                'status' => 'required|in:en_attente,complete,echoue,rembourse,partiel'
            ]);

            $oldStatus = $payment->status;
            $payment->status = $request->status;
            
            if ($request->status === 'complete' && $payment->invoice) {
                // Update invoice paid amount
                $invoice = $payment->invoice;
                $invoice->paid_amount += $payment->amount;
                $invoice->remaining_amount = $invoice->total - $invoice->paid_amount;
                
                if ($invoice->remaining_amount <= 0) {
                    $invoice->status = 'payee';
                    $invoice->payment_date = now();
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->status = 'partiellement_payee';
                }
                
                $invoice->save();
            }

            if ($request->status === 'rembourse' && $payment->invoice) {
                // Reverse payment
                $invoice = $payment->invoice;
                $invoice->paid_amount -= $payment->amount;
                $invoice->remaining_amount = $invoice->total - $invoice->paid_amount;
                $invoice->save();
            }

            $payment->save();


            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès !'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add note to payment.
     */
    public function addNote(Request $request, $id)
    {
            try {
                $payment = Payment::findOrFail($id);

            $request->validate([
                'note' => 'required|string'
            ]);

            $payment->notes = $payment->notes 
                ? $payment->notes . "\n\n[" . now()->format('d/m/Y H:i') . "] " . $request->note
                : "[" . now()->format('d/m/Y H:i') . "] " . $request->note;
            
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Note ajoutée avec succès !',
                'note' => $payment->notes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download receipt.
     */
    public function downloadReceipt($id)
    {
        try {
                $payment = Payment::findOrFail($id);

            // Generate PDF receipt
            $pdf = \PDF::loadView('ecommerce::payments.receipt', compact('payment'));
            
            return $pdf->download('recu-' . $payment->payment_reference . '.pdf');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors du téléchargement du reçu.');
        }
    }

    /**
     * Send receipt by email.
     */
    public function sendReceipt($id)
    {
        try {
            $payment = Payment::with('client')
                ->findOrFail($id);

            // Send email with receipt
            \Mail::to($payment->client->email)->send(new \Vendor\Ecommerce\Mail\PaymentReceipt($payment));

            return response()->json([
                'success' => true,
                'message' => 'Reçu envoyé par email avec succès !'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * Get payments statistics.
 */
public function statistics()
{
    try {
        $etablissementId = auth()->user()->etablissement_id;
        
        $stats = [
            'total' => Payment::count(),
            'total_amount' => Payment::where('status', 'complete')->sum('amount'),
            'completed' => Payment::where('status', 'complete')->count(),
            'pending' => Payment::where('status', 'en_attente')->count(),
            'average' => Payment::where('status', 'complete')->avg('amount'),
            'by_method' => DB::table('payments')
                ->select('method', DB::raw('count(*) as total'), DB::raw('sum(amount) as amount'))
                ->groupBy('method')
                ->get(),
            'top_clients' => DB::table('payments')
                ->join('customers', 'payments.client_id', '=', 'customers.id')
                ->select('customers.nom as client_name', DB::raw('sum(amount) as total'))
                ->where('payments.status', 'complete')
                ->groupBy('customers.id', 'customers.nom')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get(),
            'monthly' => DB::table('payments')
                ->select(
                    DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
                    DB::raw('sum(amount) as amount')
                )
                ->where('status', 'complete')
                ->groupBy(DB::raw("DATE_FORMAT(payment_date, '%Y-%m')"))
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}
}