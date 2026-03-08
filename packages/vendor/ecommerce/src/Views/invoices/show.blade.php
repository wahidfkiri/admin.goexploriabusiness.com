@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-file-invoice"></i></span>
                Facture #{{ $invoice->invoice_number }}
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                
                @if(!in_array($invoice->status, ['payee', 'annulee']))
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                @endif
                
                <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-download me-2"></i>PDF
                </a>
                
                @if($invoice->status !== 'payee')
                    <button class="btn btn-success me-2" onclick="markAsPaid()">
                        <i class="fas fa-check-circle me-2"></i>Marquer payée
                    </button>
                @endif
                
                @if($invoice->status === 'envoyee' || $invoice->status === 'en_attente')
                    <button class="btn btn-warning me-2" onclick="sendReminder()">
                        <i class="fas fa-bell me-2"></i>Relance
                    </button>
                @endif
            </div>
        </div>

        <!-- Status Banner -->
        <div class="status-banner status-{{ $invoice->status }}">
            <div class="status-icon">
                @switch($invoice->status)
                    @case('brouillon')
                        <i class="fas fa-pen"></i>
                        @break
                    @case('envoyee')
                        <i class="fas fa-paper-plane"></i>
                        @break
                    @case('en_attente')
                        <i class="fas fa-clock"></i>
                        @break
                    @case('payee')
                        <i class="fas fa-check-circle"></i>
                        @break
                    @case('partiellement_payee')
                        <i class="fas fa-adjust"></i>
                        @break
                    @case('en_retard')
                        <i class="fas fa-exclamation-triangle"></i>
                        @break
                    @case('annulee')
                        <i class="fas fa-times-circle"></i>
                        @break
                @endswitch
            </div>
            <div class="status-content">
                <h3>Statut: {{ $invoice->status_label }}</h3>
                @if($invoice->is_overdue)
                    <p class="text-danger">Cette facture est en retard de paiement</p>
                @endif
            </div>
        </div>

        <!-- Invoice Content -->
        <div class="invoice-container">
            <!-- Header -->
            <div class="invoice-header">
                <div class="company-info">
                    <h2>{{ config('app.name') }}</h2>
                    <p>{{ auth()->user()->etablissement->adresse ?? '' }}</p>
                    <p>{{ auth()->user()->etablissement->code_postal ?? '' }} {{ auth()->user()->etablissement->ville ?? '' }}</p>
                    <p>Tél: {{ auth()->user()->etablissement->telephone ?? '' }}</p>
                    <p>Email: {{ auth()->user()->etablissement->email_contact ?? '' }}</p>
                </div>
                
                <div class="invoice-info">
                    <h1>FACTURE</h1>
                    <p><strong>N° {{ $invoice->invoice_number }}</strong></p>
                    <p>Date d'émission: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                    <p>Date d'échéance: {{ $invoice->due_date->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Client Info -->
            <div class="client-section">
                <h4>Facturer à :</h4>
                <div class="client-details">
                    <p><strong>{{ $invoice->client_name }}</strong></p>
                    <p>{{ $invoice->client_address }}</p>
                    <p>{{ $invoice->client_zipcode }} {{ $invoice->client_city }}</p>
                    <p>{{ $invoice->client_country }}</p>
                    @if($invoice->client_vat_number)
                        <p>TVA: {{ $invoice->client_vat_number }}</p>
                    @endif
                </div>
            </div>

            <!-- Invoice Lines -->
            <div class="invoice-lines">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-right">Prix unitaire</th>
                            <th class="text-right">TVA</th>
                            <th class="text-right">Total HT</th>
                            <th class="text-right">Total TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->lines as $line)
                        <tr>
                            <td>{{ $line->description }}</td>
                            <td class="text-center">{{ $line->quantity }}</td>
                            <td class="text-right">{{ number_format($line->unit_price, 2) }} €</td>
                            <td class="text-right">{{ $line->tax_rate }}%</td>
                            <td class="text-right">{{ number_format($line->subtotal, 2) }} €</td>
                            <td class="text-right">{{ number_format($line->total, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="invoice-summary">
                <div class="summary-item">
                    <span>Sous-total HT</span>
                    <span>{{ number_format($invoice->subtotal, 2) }} €</span>
                </div>
                
                @if($invoice->discount_percentage > 0)
                <div class="summary-item">
                    <span>Remise ({{ $invoice->discount_percentage }}%)</span>
                    <span>- {{ number_format($invoice->discount_amount, 2) }} €</span>
                </div>
                @endif
                
                @if($invoice->shipping_fees > 0)
                <div class="summary-item">
                    <span>Frais de livraison</span>
                    <span>{{ number_format($invoice->shipping_fees, 2) }} €</span>
                </div>
                @endif
                
                @if($invoice->administration_fees > 0)
                <div class="summary-item">
                    <span>Frais d'administration</span>
                    <span>{{ number_format($invoice->administration_fees, 2) }} €</span>
                </div>
                @endif
                
                <div class="summary-item">
                    <span>Total TVA</span>
                    <span>{{ number_format($invoice->tax_total, 2) }} €</span>
                </div>
                
                <div class="summary-item total">
                    <span>Total TTC</span>
                    <span>{{ number_format($invoice->total, 2) }} €</span>
                </div>
                
                @if($invoice->paid_amount > 0)
                <div class="summary-item paid">
                    <span>Déjà payé</span>
                    <span>- {{ number_format($invoice->paid_amount, 2) }} €</span>
                </div>
                
                <div class="summary-item remaining">
                    <span>Reste à payer</span>
                    <span>{{ number_format($invoice->remaining_amount, 2) }} €</span>
                </div>
                @endif
            </div>

            <!-- Payment Info -->
            @if($invoice->payments->count() > 0)
            <div class="payments-section">
                <h4>Paiements reçus</h4>
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Méthode</th>
                            <th class="text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td>{{ $payment->payment_reference }}</td>
                            <td>{{ ucfirst($payment->method) }}</td>
                            <td class="text-right">{{ number_format($payment->amount, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Notes & Footer -->
            @if($invoice->notes)
            <div class="notes-section">
                <h4>Notes</h4>
                <p>{{ $invoice->notes }}</p>
            </div>
            @endif
            
            @if($invoice->footer)
            <div class="footer-section">
                <p>{{ $invoice->footer }}</p>
            </div>
            @endif

            <!-- Bank Details -->
            <div class="bank-details">
                <h4>Coordonnées bancaires</h4>
                @php
                    $bank = \App\Models\BankDetail::where('etablissement_id', auth()->user()->etablissement_id)
                        ->where('is_default', true)
                        ->first();
                @endphp
                
                @if($bank)
                <p>IBAN: {{ $bank->iban }}</p>
                <p>BIC: {{ $bank->swift }}</p>
                <p>Titulaire: {{ $bank->account_holder }}</p>
                <p>Banque: {{ $bank->bank_name }}</p>
                @endif
            </div>
        </div>

        <!-- Related Invoices -->
        @if($relatedInvoices->count() > 0)
        <div class="related-invoices">
            <h4>Autres factures pour ce client</h4>
            <div class="related-list">
                @foreach($relatedInvoices as $related)
                <a href="{{ route('invoices.show', $related->id) }}" class="related-item">
                    <span class="related-number">{{ $related->invoice_number }}</span>
                    <span class="related-date">{{ $related->invoice_date->format('d/m/Y') }}</span>
                    <span class="related-amount">{{ number_format($related->total, 2) }} €</span>
                    <span class="badge bg-{{ $related->status_badge }}">{{ $related->status_label }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function markAsPaid() {
            if (!confirm('Marquer cette facture comme payée ?')) return;
            
            $.ajax({
                url: '{{ route("invoices.mark-paid", $invoice->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors de l\'opération');
                }
            });
        }

        function sendReminder() {
            if (!confirm('Envoyer une relance au client ?')) return;
            
            $.ajax({
                url: '{{ route("invoices.send", $invoice->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Relance envoyée avec succès');
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors de l\'envoi');
                }
            });
        }

        function showAlert(type, message) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-custom-modern alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    </script>

    <style>
        /* Invoice Show Specific Styles */
        .status-banner {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            color: white;
        }

        .status-banner.status-brouillon { background: linear-gradient(135deg, #6c757d, #5a6268); }
        .status-banner.status-envoyee { background: linear-gradient(135deg, #17a2b8, #138496); }
        .status-banner.status-en_attente { background: linear-gradient(135deg, #ffc107, #e0a800); }
        .status-banner.status-payee { background: linear-gradient(135deg, #28a745, #218838); }
        .status-banner.status-partiellement_payee { background: linear-gradient(135deg, #007bff, #0069d9); }
        .status-banner.status-en_retard { background: linear-gradient(135deg, #dc3545, #c82333); }
        .status-banner.status-annulee { background: linear-gradient(135deg, #343a40, #23272b); }

        .status-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .status-content h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .status-content p {
            margin: 5px 0 0;
            opacity: 0.9;
        }

        .invoice-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .company-info h2 {
            margin: 0 0 10px;
            color: var(--primary-color);
        }

        .company-info p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin: 0 0 10px;
        }

        .invoice-info p {
            margin: 5px 0;
            font-size: 1.1rem;
        }

        .client-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .client-section h4 {
            margin: 0 0 15px;
            color: #333;
        }

        .client-details p {
            margin: 5px 0;
        }

        .invoice-lines {
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th {
            background: #f8f9fa;
            padding: 12px;
            font-weight: 600;
            color: #333;
        }

        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #eaeaea;
        }

        .invoice-table .text-center {
            text-align: center;
        }

        .invoice-table .text-right {
            text-align: right;
        }

        .invoice-summary {
            width: 400px;
            margin-left: auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .summary-item.total {
            font-weight: 700;
            font-size: 1.2rem;
            border-bottom: 2px solid #333;
            padding: 15px 0;
            margin-top: 10px;
        }

        .summary-item.paid {
            color: #28a745;
        }

        .summary-item.remaining {
            font-weight: 600;
            color: #ef476f;
        }

        .payments-section {
            margin-top: 30px;
        }

        .payments-section h4 {
            margin-bottom: 15px;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payments-table th {
            background: #f8f9fa;
            padding: 10px;
            text-align: left;
        }

        .payments-table td {
            padding: 10px;
            border-bottom: 1px solid #eaeaea;
        }

        .notes-section, .footer-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .notes-section h4, .footer-section h4 {
            margin-bottom: 10px;
        }

        .bank-details {
            margin-top: 30px;
            padding: 20px;
            background: #e9ecef;
            border-radius: 8px;
        }

        .bank-details h4 {
            margin-bottom: 15px;
        }

        .bank-details p {
            margin: 5px 0;
            font-family: monospace;
            font-size: 1.1rem;
        }

        .related-invoices {
            margin-top: 30px;
        }

        .related-invoices h4 {
            margin-bottom: 15px;
        }

        .related-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .related-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }

        .related-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .related-number {
            font-weight: 600;
            min-width: 150px;
        }

        .related-date {
            color: #666;
            min-width: 100px;
        }

        .related-amount {
            font-weight: 600;
            color: #06b48a;
            min-width: 100px;
        }

        @media print {
            .page-actions, .status-banner, .related-invoices {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .invoice-header {
                flex-direction: column;
                gap: 20px;
            }
            
            .invoice-info {
                text-align: left;
            }
            
            .invoice-summary {
                width: 100%;
            }
            
            .related-item {
                flex-wrap: wrap;
            }
        }
    </style>
@endsection