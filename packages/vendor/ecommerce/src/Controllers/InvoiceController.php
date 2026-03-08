<?php

namespace Vendor\Ecommerce\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Customer;
use App\Models\Etablissement;
use App\Models\Product;
use App\Models\Project;
use App\Models\Tax;
use App\Models\BillingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::with(['client', 'project']);
            
            // Apply filters
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('invoice_number', 'like', '%' . $request->search . '%')
                      ->orWhereHas('client', function($cq) use ($request) {
                          $cq->where('nom', 'like', '%' . $request->search . '%')
                             ->orWhere('prenom', 'like', '%' . $request->search . '%')
                             ->orWhere('entreprise_nom', 'like', '%' . $request->search . '%');
                      });
                });
            }
            
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->client_id) {
                $query->where('client_id', $request->client_id);
            }
            
            if ($request->date_from) {
                $query->whereDate('invoice_date', '>=', $request->date_from);
            }
            
            if ($request->date_to) {
                $query->whereDate('invoice_date', '<=', $request->date_to);
            }
            
            if ($request->min_amount) {
                $query->where('total', '>=', $request->min_amount);
            }
            
            if ($request->max_amount) {
                $query->where('total', '<=', $request->max_amount);
            }
            
            $invoices = $query->orderBy('invoice_date', 'desc')
                ->paginate(15);
            
            return response()->json([
                'success' => true,
                'data' => $invoices->items(),
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'total' => $invoices->total()
            ]);
        }
        
        $clients = Customer::orderBy('nom')
            ->get(['id', 'nom', 'prenom', 'entreprise_nom']);
        
        $stats = $this->getStatistics();
        
        return view('ecommerce::invoices.index', compact('clients', 'stats'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $clients = Etablissement::orderBy('name')->get();
        
        $projects = Project::whereIn('status', ['en_cours', 'termine'])
            ->orderBy('name')
            ->get();
        
        $products = Product::with(['category', 'tax'])
            ->where('is_available_for_sale', true)
            ->orderBy('name')
            ->get();
        
        $taxes = Tax::where('is_active', true)->orderBy('rate')->get();
        
        $billingSettings = BillingSetting::first();
        
        $defaultDueDays = $billingSettings->payment_deadline_days ?? 30;
        
        return view('ecommerce::invoices.create', compact('clients', 'projects', 'products', 'taxes', 'defaultDueDays'));
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:customers,id',
                'project_id' => 'nullable|exists:projects,id',
                'invoice_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:invoice_date',
                'lines' => 'required|array|min:1',
                'lines.*.description' => 'required|string',
                'lines.*.quantity' => 'required|numeric|min:0',
                'lines.*.unit_price' => 'required|numeric|min:0',
                'lines.*.tax_rate' => 'required|numeric|min:0',
                'lines.*.product_id' => 'nullable|exists:products,id',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'shipping_fees' => 'nullable|numeric|min:0',
                'administration_fees' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'footer' => 'nullable|string',
                'status' => 'required|in:brouillon,envoyee',
            ]);

            DB::beginTransaction();

            // Get client info
            $client = Customer::findOrFail($validated['client_id']);
            
            // Calculate totals
            $subtotal = 0;
            $taxTotal = 0;
            $taxesBreakdown = [];
            
            foreach ($validated['lines'] as $line) {
                $lineSubtotal = $line['quantity'] * $line['unit_price'];
                $lineTax = $lineSubtotal * ($line['tax_rate'] / 100);
                
                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;
                
                // Build taxes breakdown
                if (!isset($taxesBreakdown[$line['tax_rate']])) {
                    $taxesBreakdown[$line['tax_rate']] = [
                        'rate' => $line['tax_rate'],
                        'base' => 0,
                        'amount' => 0
                    ];
                }
                $taxesBreakdown[$line['tax_rate']]['base'] += $lineSubtotal;
                $taxesBreakdown[$line['tax_rate']]['amount'] += $lineTax;
            }
            
            // Apply discount
            $discountPercentage = $validated['discount_percentage'] ?? 0;
            $discountAmount = $subtotal * ($discountPercentage / 100);
            
            // Add fees
            $shippingFees = $validated['shipping_fees'] ?? 0;
            $adminFees = $validated['administration_fees'] ?? 0;
            
            $total = $subtotal - $discountAmount + $shippingFees + $adminFees + $taxTotal;

            // Create invoice
            $invoice = Invoice::create([
                'etablissement_id' => auth()->user()->etablissement_id,
                'client_id' => $validated['client_id'],
                'project_id' => $validated['project_id'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'shipping_fees' => $shippingFees,
                'administration_fees' => $adminFees,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'tax_total' => $taxTotal,
                'total' => $total,
                'paid_amount' => 0,
                'remaining_amount' => $total,
                'taxes_breakdown' => array_values($taxesBreakdown),
                'status' => $validated['status'],
                'client_name' => $client->nom_complet,
                'client_address' => $client->adresse,
                'client_zipcode' => $client->code_postal,
                'client_city' => $client->ville,
                'client_country' => $client->pays ?? 'France',
                'client_vat_number' => $client->no_tva,
                'notes' => $validated['notes'] ?? null,
                'footer' => $validated['footer'] ?? null,
            ]);

            // Create invoice lines
            foreach ($validated['lines'] as $index => $line) {
                $lineSubtotal = $line['quantity'] * $line['unit_price'];
                $lineTax = $lineSubtotal * ($line['tax_rate'] / 100);
                
                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $line['product_id'] ?? null,
                    'line_number' => $index + 1,
                    'description' => $line['description'],
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'discount_percentage' => 0,
                    'discount_amount' => 0,
                    'subtotal' => $lineSubtotal,
                    'tax_rate' => $line['tax_rate'],
                    'tax_amount' => $lineTax,
                    'total' => $lineSubtotal + $lineTax,
                ]);
            }

            DB::commit();

            // If status is 'envoyee', send email
            if ($validated['status'] === 'envoyee') {
                $this->sendInvoiceEmail($invoice);
            }

            return response()->json([
                'success' => true,
                'message' => 'Facture créée avec succès',
                'data' => [
                    'id' => $invoice->id,
                    'number' => $invoice->invoice_number
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        try {
            $invoice = Invoice::with(['client', 'project', 'lines.product', 'payments'])
                ->where('etablissement_id', auth()->user()->etablissement_id)
                ->findOrFail($id);

            $relatedInvoices = Invoice::where('client_id', $invoice->client_id)
                ->where('id', '!=', $invoice->id)
                ->where('etablissement_id', auth()->user()->etablissement_id)
                ->orderBy('invoice_date', 'desc')
                ->limit(5)
                ->get();

            $paymentSuggestions = [
                'total_paid' => $invoice->payments()->where('status', 'complete')->sum('amount'),
                'pending_payments' => $invoice->payments()->where('status', 'en_attente')->count(),
            ];

            return view('invoices.show', compact('invoice', 'relatedInvoices', 'paymentSuggestions'));

        } catch (\Exception $e) {
            return redirect()->route('invoices.index')
                ->with('error', 'Facture non trouvée');
        }
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit($id)
    {
        try {
            $invoice = Invoice::with(['lines', 'client', 'project'])
                ->where('etablissement_id', auth()->user()->etablissement_id)
                ->findOrFail($id);

            // Can't edit paid or cancelled invoices
            if (in_array($invoice->status, ['payee', 'annulee'])) {
                return redirect()->route('invoices.show', $id)
                    ->with('error', 'Cette facture ne peut pas être modifiée');
            }

            $clients = Customer::where('etablissement_id', auth()->user()->etablissement_id)
                ->orderBy('nom')
                ->get();
            
            $projects = Project::where('etablissement_id', auth()->user()->etablissement_id)
                ->orderBy('nom')
                ->get();
            
            $products = Product::with(['category', 'tax'])
                ->where('etablissement_id', auth()->user()->etablissement_id)
                ->where('is_available_for_sale', true)
                ->orderBy('name')
                ->get();
            
            $taxes = Tax::where('is_active', true)->orderBy('rate')->get();

            return view('invoices.edit', compact('invoice', 'clients', 'projects', 'products', 'taxes'));

        } catch (\Exception $e) {
            return redirect()->route('invoices.index')
                ->with('error', 'Facture non trouvée');
        }
    }

    /**
     * Update the specified invoice.
     */
    public function update(Request $request, $id)
    {
        try {
            $invoice = Invoice::where('etablissement_id', auth()->user()->etablissement_id)
                ->findOrFail($id);

            // Can't update paid or cancelled invoices
            if (in_array($invoice->status, ['payee', 'annulee'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette facture ne peut pas être modifiée'
                ], 400);
            }

            $validated = $request->validate([
                'client_id' => 'required|exists:customers,id',
                'project_id' => 'nullable|exists:projects,id',
                'invoice_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:invoice_date',
                'lines' => 'required|array|min:1',
                'lines.*.id' => 'nullable|exists:invoice_lines,id',
                'lines.*.description' => 'required|string',
                'lines.*.quantity' => 'required|numeric|min:0',
                'lines.*.unit_price' => 'required|numeric|min:0',
                'lines.*.tax_rate' => 'required|numeric|min:0',
                'lines.*.product_id' => 'nullable|exists:products,id',
                'lines.*.deleted' => 'nullable|boolean',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'shipping_fees' => 'nullable|numeric|min:0',
                'administration_fees' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'footer' => 'nullable|string',
                'status' => 'required|in:brouillon,envoyee',
            ]);

            DB::beginTransaction();

            // Get client info
            $client = Customer::findOrFail($validated['client_id']);
            
            // Calculate totals
            $subtotal = 0;
            $taxTotal = 0;
            $taxesBreakdown = [];
            
            foreach ($validated['lines'] as $line) {
                if (isset($line['deleted']) && $line['deleted']) {
                    continue;
                }
                
                $lineSubtotal = $line['quantity'] * $line['unit_price'];
                $lineTax = $lineSubtotal * ($line['tax_rate'] / 100);
                
                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;
                
                // Build taxes breakdown
                if (!isset($taxesBreakdown[$line['tax_rate']])) {
                    $taxesBreakdown[$line['tax_rate']] = [
                        'rate' => $line['tax_rate'],
                        'base' => 0,
                        'amount' => 0
                    ];
                }
                $taxesBreakdown[$line['tax_rate']]['base'] += $lineSubtotal;
                $taxesBreakdown[$line['tax_rate']]['amount'] += $lineTax;
            }
            
            // Apply discount
            $discountPercentage = $validated['discount_percentage'] ?? 0;
            $discountAmount = $subtotal * ($discountPercentage / 100);
            
            // Add fees
            $shippingFees = $validated['shipping_fees'] ?? 0;
            $adminFees = $validated['administration_fees'] ?? 0;
            
            $total = $subtotal - $discountAmount + $shippingFees + $adminFees + $taxTotal;

            // Update invoice
            $invoice->update([
                'client_id' => $validated['client_id'],
                'project_id' => $validated['project_id'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'shipping_fees' => $shippingFees,
                'administration_fees' => $adminFees,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'tax_total' => $taxTotal,
                'total' => $total,
                'remaining_amount' => $total - $invoice->paid_amount,
                'taxes_breakdown' => array_values($taxesBreakdown),
                'status' => $validated['status'],
                'client_name' => $client->nom_complet,
                'client_address' => $client->adresse,
                'client_zipcode' => $client->code_postal,
                'client_city' => $client->ville,
                'client_country' => $client->pays ?? 'France',
                'client_vat_number' => $client->no_tva,
                'notes' => $validated['notes'] ?? null,
                'footer' => $validated['footer'] ?? null,
            ]);

            // Handle lines
            $existingLineIds = [];
            
            foreach ($validated['lines'] as $index => $line) {
                if (isset($line['deleted']) && $line['deleted']) {
                    if (isset($line['id'])) {
                        InvoiceLine::find($line['id'])->delete();
                    }
                    continue;
                }
                
                $lineSubtotal = $line['quantity'] * $line['unit_price'];
                $lineTax = $lineSubtotal * ($line['tax_rate'] / 100);
                
                $lineData = [
                    'invoice_id' => $invoice->id,
                    'product_id' => $line['product_id'] ?? null,
                    'line_number' => $index + 1,
                    'description' => $line['description'],
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'discount_percentage' => 0,
                    'discount_amount' => 0,
                    'subtotal' => $lineSubtotal,
                    'tax_rate' => $line['tax_rate'],
                    'tax_amount' => $lineTax,
                    'total' => $lineSubtotal + $lineTax,
                ];
                
                if (isset($line['id'])) {
                    $invoiceLine = InvoiceLine::find($line['id']);
                    $invoiceLine->update($lineData);
                    $existingLineIds[] = $line['id'];
                } else {
                    $newLine = InvoiceLine::create($lineData);
                    $existingLineIds[] = $newLine->id;
                }
            }
            
            // Delete lines not in the request
            $invoice->lines()->whereNotIn('id', $existingLineIds)->delete();

            DB::commit();

            // If status changed to 'envoyee', send email
            if ($validated['status'] === 'envoyee' && $invoice->getOriginal('status') !== 'envoyee') {
                $this->sendInvoiceEmail($invoice);
            }

            return response()->json([
                'success' => true,
                'message' => 'Facture mise à jour avec succès',
                'data' => [
                    'id' => $invoice->id,
                    'number' => $invoice->invoice_number
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified invoice.
     */
    public function destroy($id)
    {
        try {
            $invoice = Invoice::where('etablissement_id', auth()->user()->etablissement_id)
                ->findOrFail($id);

            // Can't delete paid invoices
            if ($invoice->status === 'payee') {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer une facture payée'
                ], 400);
            }

            // Check for payments
            if ($invoice->payments()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer une facture avec des paiements associés'
                ], 400);
            }

            $invoice->lines()->delete();
            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Facture supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send invoice by email.
     */
    public function sendEmail($id)
    {
        try {
            $invoice = Invoice::with(['client', 'etablissement'])
                ->where('etablissement_id', auth()->user()->etablissement_id)
                ->findOrFail($id);

            $this->sendInvoiceEmail($invoice);

            $invoice->update(['status' => 'envoyee']);

            return response()->json([
                'success' => true,
                'message' => 'Facture envoyée par email avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download PDF invoice.
     */
    public function downloadPdf($id)
    {
        try {
            $invoice = Invoice::with(['client', 'lines', 'etablissement'])
                ->where('etablissement_id', auth()->user()->etablissement_id)
                ->findOrFail($id);

            $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
            
            return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors du téléchargement');
        }
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid($id)
    {
        try {
            $invoice = Invoice::where('etablissement_id', auth()->user()->etablissement_id)
                ->findOrFail($id);

            $invoice->update([
                'status' => 'payee',
                'payment_date' => now(),
                'paid_amount' => $invoice->total,
                'remaining_amount' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Facture marquée comme payée'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoice statistics.
     */
    public function statistics()
    {
        try {
            $stats = $this->getStatistics();
            
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

    /**
     * Get statistics helper.
     */
    private function getStatistics()
    {
        $etablissementId = auth()->user()->etablissement_id;
        
        $stats = [
            'total' => Invoice::where('etablissement_id', $etablissementId)->count(),
            'total_amount' => Invoice::where('etablissement_id', $etablissementId)
                ->where('status', '!=', 'annulee')
                ->sum('total'),
            'paid_amount' => Invoice::where('etablissement_id', $etablissementId)
                ->where('status', 'payee')
                ->sum('total'),
            'pending_amount' => Invoice::where('etablissement_id', $etablissementId)
                ->whereIn('status', ['en_attente', 'partiellement_payee'])
                ->sum(DB::raw('total - paid_amount')),
            'overdue_count' => Invoice::where('etablissement_id', $etablissementId)
                ->where('status', 'en_retard')
                ->count(),
            'by_status' => Invoice::where('etablissement_id', $etablissementId)
                ->select('status', DB::raw('count(*) as total'), DB::raw('sum(total) as amount'))
                ->groupBy('status')
                ->get(),
            'by_month' => Invoice::where('etablissement_id', $etablissementId)
                ->where('status', '!=', 'annulee')
                ->select(
                    DB::raw('DATE_FORMAT(invoice_date, "%Y-%m") as month'),
                    DB::raw('count(*) as total'),
                    DB::raw('sum(total) as amount')
                )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get(),
        ];
        
        return $stats;
    }

    /**
     * Send invoice email.
     */
    private function sendInvoiceEmail($invoice)
    {
        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        // Send email
        Mail::send('emails.invoice', compact('invoice'), function ($message) use ($invoice, $pdf) {
            $message->to($invoice->client->email, $invoice->client_name)
                ->subject('Facture ' . $invoice->invoice_number)
                ->attachData($pdf->output(), 'facture-' . $invoice->invoice_number . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });
    }
}