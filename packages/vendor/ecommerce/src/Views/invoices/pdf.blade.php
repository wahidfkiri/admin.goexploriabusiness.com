<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #45b7d1;
        }
        
        .company-info h1 {
            color: #45b7d1;
            margin: 0 0 10px;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info h2 {
            color: #45b7d1;
            margin: 0 0 10px;
        }
        
        .client-section {
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background: #45b7d1;
            color: white;
            padding: 10px;
            text-align: left;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .summary {
            width: 400px;
            margin-left: auto;
            margin-top: 20px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
        }
        
        .summary-row.total {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            padding: 12px 0;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .bank-details {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .bank-details h3 {
            margin: 0 0 10px;
            color: #45b7d1;
        }
        
        .bank-details p {
            margin: 5px 0;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h1>{{ config('app.name') }}</h1>
            <p>{{ auth()->user()->etablissement->adresse ?? '' }}</p>
            <p>{{ auth()->user()->etablissement->code_postal ?? '' }} {{ auth()->user()->etablissement->ville ?? '' }}</p>
            <p>Tél: {{ auth()->user()->etablissement->telephone ?? '' }}</p>
            <p>Email: {{ auth()->user()->etablissement->email_contact ?? '' }}</p>
            @if(auth()->user()->etablissement->website)
                <p>Web: {{ auth()->user()->etablissement->website }}</p>
            @endif
        </div>
        
        <div class="invoice-info">
            <h2>FACTURE</h2>
            <p><strong>N° {{ $invoice->invoice_number }}</strong></p>
            <p>Date d'émission: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
            <p>Date d'échéance: {{ $invoice->due_date->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="client-section">
        <h3>Facturer à :</h3>
        <p><strong>{{ $invoice->client_name }}</strong></p>
        <p>{{ $invoice->client_address }}</p>
        <p>{{ $invoice->client_zipcode }} {{ $invoice->client_city }}</p>
        <p>{{ $invoice->client_country }}</p>
        @if($invoice->client_vat_number)
            <p>TVA: {{ $invoice->client_vat_number }}</p>
        @endif
    </div>

    <table>
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

    <div class="summary">
        <div class="summary-row">
            <span>Sous-total HT</span>
            <span>{{ number_format($invoice->subtotal, 2) }} €</span>
        </div>
        
        @if($invoice->discount_percentage > 0)
        <div class="summary-row">
            <span>Remise ({{ $invoice->discount_percentage }}%)</span>
            <span>- {{ number_format($invoice->discount_amount, 2) }} €</span>
        </div>
        @endif
        
        @if($invoice->shipping_fees > 0)
        <div class="summary-row">
            <span>Frais de livraison</span>
            <span>{{ number_format($invoice->shipping_fees, 2) }} €</span>
        </div>
        @endif
        
        @if($invoice->administration_fees > 0)
        <div class="summary-row">
            <span>Frais d'administration</span>
            <span>{{ number_format($invoice->administration_fees, 2) }} €</span>
        </div>
        @endif
        
        <div class="summary-row">
            <span>Total TVA</span>
            <span>{{ number_format($invoice->tax_total, 2) }} €</span>
        </div>
        
        <div class="summary-row total">
            <span>Total TTC</span>
            <span>{{ number_format($invoice->total, 2) }} €</span>
        </div>
    </div>

    <div class="bank-details">
        <h3>Coordonnées bancaires</h3>
        @php
            $bank = \App\Models\BankDetail::where('etablissement_id', auth()->user()->etablissement_id)
                ->where('is_default', true)
                ->first();
        @endphp
        
        @if($bank)
            <p><strong>IBAN:</strong> {{ $bank->iban }}</p>
            <p><strong>BIC:</strong> {{ $bank->swift }}</p>
            <p><strong>Titulaire:</strong> {{ $bank->account_holder }}</p>
            <p><strong>Banque:</strong> {{ $bank->bank_name }}</p>
        @else
            <p>Coordonnées bancaires non configurées</p>
        @endif
    </div>

    @if($invoice->notes)
    <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h3>Notes</h3>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif

    @if($invoice->footer)
    <div style="margin-top: 30px; text-align: center; color: #666;">
        <p>{{ $invoice->footer }}</p>
    </div>
    @endif

    <div class="footer">
        <p>{{ config('app.name') }} - {{ now()->format('d/m/Y H:i') }}</p>
        <p>Merci de votre confiance</p>
    </div>
</body>
</html>