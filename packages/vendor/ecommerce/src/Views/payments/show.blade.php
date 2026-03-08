@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-credit-card"></i></span>
                Détails du paiement
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
                @if($payment->invoice)
                <a href="{{ route('invoices.show', $payment->invoice_id) }}" class="btn btn-outline-info me-2">
                    <i class="fas fa-file-invoice me-2"></i>Voir la facture
                </a>
                @endif
                <a href="{{ route('payments.receipt', $payment->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-download me-2"></i>Télécharger le reçu
                </a>
                <button type="button" class="btn btn-outline-primary" id="sendReceiptBtn">
                    <i class="fas fa-envelope me-2"></i>Envoyer par email
                </button>
            </div>
        </div>

        <!-- Payment Navigation Tabs -->
        <div class="payment-tabs-modern">
            <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                        <i class="fas fa-info-circle me-2"></i>Aperçu général
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="invoice-tab" data-bs-toggle="tab" data-bs-target="#invoice" type="button" role="tab">
                        <i class="fas fa-file-invoice me-2"></i>Facture associée
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                        <i class="fas fa-history me-2"></i>Historique client
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab">
                        <i class="fas fa-sticky-note me-2"></i>Notes
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="paymentTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <!-- Left Column - Payment Info -->
                    <div class="col-lg-8">
                        <!-- Payment Header Card -->
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-credit-card me-2"></i>Paiement #{{ $payment->payment_reference }}
                                </h3>
                                <div class="payment-badges">
                                    @php
                                        $statusColors = [
                                            'en_attente' => 'warning',
                                            'complete' => 'success',
                                            'echoue' => 'danger',
                                            'rembourse' => 'info',
                                            'partiel' => 'secondary'
                                        ];
                                        $statusLabels = [
                                            'en_attente' => 'En attente',
                                            'complete' => 'Complété',
                                            'echoue' => 'Échoué',
                                            'rembourse' => 'Remboursé',
                                            'partiel' => 'Partiel'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$payment->status] }} fs-6">
                                        <i class="fas 
                                            @if($payment->status == 'complete') fa-check-circle
                                            @elseif($payment->status == 'en_attente') fa-clock
                                            @elseif($payment->status == 'echoue') fa-times-circle
                                            @elseif($payment->status == 'rembourse') fa-undo
                                            @else fa-circle
                                            @endif me-1"></i>
                                        {{ $statusLabels[$payment->status] }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="payment-header">
                                    <div class="payment-amount">
                                        <div class="amount-label">Montant</div>
                                        <div class="amount-value">{{ number_format($payment->amount, 2) }} €</div>
                                    </div>
                                    
                                    <div class="payment-quick-info">
                                        <div class="info-item">
                                            <span class="info-label">Date</span>
                                            <span class="info-value">{{ $payment->payment_date->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Méthode</span>
                                            <span class="info-value">
                                                @switch($payment->method)
                                                    @case('carte')
                                                        <i class="fas fa-credit-card me-1 text-primary"></i>Carte bancaire
                                                        @break
                                                    @case('virement')
                                                        <i class="fas fa-exchange-alt me-1 text-info"></i>Virement
                                                        @break
                                                    @case('cheque')
                                                        <i class="fas fa-money-check me-1 text-success"></i>Chèque
                                                        @break
                                                    @case('especes')
                                                        <i class="fas fa-money-bill me-1 text-warning"></i>Espèces
                                                        @break
                                                    @case('paypal')
                                                        <i class="fab fa-paypal me-1 text-primary"></i>PayPal
                                                        @break
                                                    @case('stripe')
                                                        <i class="fab fa-stripe me-1 text-info"></i>Stripe
                                                        @break
                                                    @default
                                                        {{ $payment->method }}
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="section-title">Informations client</h5>
                                        <div class="client-info-card">
                                            <div class="client-avatar">
                                                {{ strtoupper(substr($payment->client->prenom ?? $payment->client->nom, 0, 1)) }}
                                            </div>
                                            <div class="client-details">
                                                <div class="client-name">
                                                    {{ $payment->client->prenom ?? '' }} {{ $payment->client->nom }}
                                                    @if($payment->client->entreprise_nom)
                                                        <small class="text-muted">({{ $payment->client->entreprise_nom }})</small>
                                                    @endif
                                                </div>
                                                <div class="client-contact">
                                                    <i class="fas fa-envelope me-2"></i>{{ $payment->client->email }}
                                                </div>
                                                <div class="client-contact">
                                                    <i class="fas fa-phone me-2"></i>{{ $payment->client->telephone ?? 'Non renseigné' }}
                                                </div>
                                                @if($payment->client->adresse)
                                                <div class="client-address mt-2">
                                                    <i class="fas fa-map-marker-alt me-2"></i>
                                                    {{ $payment->client->adresse }}<br>
                                                    {{ $payment->client->code_postal }} {{ $payment->client->ville }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h5 class="section-title">Détails du paiement</h5>
                                        <div class="payment-details-list">
                                            @if($payment->transaction_id)
                                            <div class="detail-row">
                                                <span>Transaction ID</span>
                                                <span class="detail-value">{{ $payment->transaction_id }}</span>
                                            </div>
                                            @endif
                                            
                                            @if($payment->check_number)
                                            <div class="detail-row">
                                                <span>Numéro de chèque</span>
                                                <span class="detail-value">{{ $payment->check_number }}</span>
                                            </div>
                                            @endif
                                            
                                            @if($payment->bank_name)
                                            <div class="detail-row">
                                                <span>Banque</span>
                                                <span class="detail-value">{{ $payment->bank_name }}</span>
                                            </div>
                                            @endif
                                            
                                            <div class="detail-row">
                                                <span>Reçu par</span>
                                                <span class="detail-value">{{ $payment->receiver->name ?? 'Système' }}</span>
                                            </div>
                                            
                                            <div class="detail-row">
                                                <span>Date de création</span>
                                                <span class="detail-value">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            
                                            <div class="detail-row">
                                                <span>Dernière modification</span>
                                                <span class="detail-value">{{ $payment->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status History Card -->
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-history me-2"></i>Historique du statut
                                </h3>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                    <i class="fas fa-edit me-1"></i>Modifier le statut
                                </button>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="status-timeline">
                                    <div class="timeline-item current">
                                        <div class="timeline-badge bg-{{ $statusColors[$payment->status] }}">
                                            <i class="fas 
                                                @if($payment->status == 'complete') fa-check
                                                @elseif($payment->status == 'en_attente') fa-clock
                                                @elseif($payment->status == 'echoue') fa-times
                                                @elseif($payment->status == 'rembourse') fa-undo
                                                @else fa-circle
                                                @endif"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-title">{{ $statusLabels[$payment->status] }}</div>
                                            <div class="timeline-date">{{ $payment->updated_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                    <!-- Add more timeline items if you have status history -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Stats & Actions -->
                    <div class="col-lg-4">
                        <!-- Stats Card -->
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-chart-pie me-2"></i>Statistiques client
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="stats-mini-grid">
                                    <div class="stat-mini-item">
                                        <div class="stat-mini-label">Total payé</div>
                                        <div class="stat-mini-value">{{ number_format($stats['total_paid'], 2) }} €</div>
                                    </div>
                                    <div class="stat-mini-item">
                                        <div class="stat-mini-label">Nombre de paiements</div>
                                        <div class="stat-mini-value">{{ $stats['payment_count'] }}</div>
                                    </div>
                                    <div class="stat-mini-item">
                                        <div class="stat-mini-label">Moyenne</div>
                                        <div class="stat-mini-value">{{ number_format($stats['average_payment'], 2) }} €</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-bolt me-2"></i>Actions rapides
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="quick-actions-grid">
                                    <button class="quick-action-btn" onclick="updateStatus('complete')">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>Marquer comme complété</span>
                                    </button>
                                    
                                    <button class="quick-action-btn" onclick="updateStatus('echoue')">
                                        <i class="fas fa-times-circle text-danger"></i>
                                        <span>Marquer comme échoué</span>
                                    </button>
                                    
                                    <button class="quick-action-btn" onclick="updateStatus('rembourse')">
                                        <i class="fas fa-undo text-info"></i>
                                        <span>Rembourser</span>
                                    </button>
                                    
                                    <a href="{{ route('payments.receipt', $payment->id) }}" class="quick-action-btn">
                                        <i class="fas fa-download text-primary"></i>
                                        <span>Télécharger reçu</span>
                                    </a>
                                    
                                    <button class="quick-action-btn" id="quickSendReceipt">
                                        <i class="fas fa-envelope text-warning"></i>
                                        <span>Envoyer reçu</span>
                                    </button>
                                    
                                    <button class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                                        <i class="fas fa-sticky-note text-secondary"></i>
                                        <span>Ajouter note</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Related Payments Card -->
                        @if($relatedPayments->count() > 0)
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-credit-card me-2"></i>Autres paiements sur cette facture
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="related-payments-list">
                                    @foreach($relatedPayments as $related)
                                    <a href="{{ route('payments.show', $related->id) }}" class="related-payment-item">
                                        <div class="related-payment-info">
                                            <div class="related-payment-ref">{{ $related->payment_reference }}</div>
                                            <div class="related-payment-date">{{ $related->payment_date->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="related-payment-amount">{{ number_format($related->amount, 2) }} €</div>
                                        <span class="badge bg-{{ $statusColors[$related->status] }}">
                                            {{ $statusLabels[$related->status] }}
                                        </span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Invoice Tab -->
            <div class="tab-pane fade" id="invoice" role="tabpanel">
                @if($payment->invoice)
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-file-invoice me-2"></i>Facture #{{ $payment->invoice->invoice_number }}
                        </h3>
                        <a href="{{ route('invoices.show', $payment->invoice_id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt me-1"></i>Voir la facture
                        </a>
                    </div>
                    
                    <div class="card-body-modern">
                        <div class="invoice-summary">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-detail">
                                        <span class="detail-label">Date de facture</span>
                                        <span class="detail-value">{{ $payment->invoice->invoice_date->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="invoice-detail">
                                        <span class="detail-label">Échéance</span>
                                        <span class="detail-value">{{ $payment->invoice->due_date->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="invoice-detail">
                                        <span class="detail-label">Montant total TTC</span>
                                        <span class="detail-value">{{ number_format($payment->invoice->total, 2) }} €</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="invoice-detail">
                                        <span class="detail-label">Montant payé</span>
                                        <span class="detail-value text-success">{{ number_format($payment->invoice->paid_amount, 2) }} €</span>
                                    </div>
                                    <div class="invoice-detail">
                                        <span class="detail-label">Reste à payer</span>
                                        <span class="detail-value {{ $payment->invoice->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($payment->invoice->remaining_amount, 2) }} €
                                        </span>
                                    </div>
                                    <div class="invoice-detail">
                                        <span class="detail-label">Statut</span>
                                        <span class="detail-value">
                                            @php
                                                $invoiceStatusColors = [
                                                    'brouillon' => 'secondary',
                                                    'envoyee' => 'info',
                                                    'en_attente' => 'warning',
                                                    'payee' => 'success',
                                                    'partiellement_payee' => 'primary',
                                                    'en_retard' => 'danger',
                                                    'annulee' => 'dark'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $invoiceStatusColors[$payment->invoice->status] }}">
                                                {{ str_replace('_', ' ', ucfirst($payment->invoice->status)) }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($payment->invoice->remaining_amount > 0)
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Il reste {{ number_format($payment->invoice->remaining_amount, 2) }} € à payer sur cette facture.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Ce paiement n'est pas associé à une facture.
                </div>
                @endif
            </div>

            <!-- History Tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-history me-2"></i>Historique des paiements du client
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        @if($clientHistory->count() > 0)
                        <div class="history-timeline">
                            @foreach($clientHistory as $history)
                            <div class="history-item">
                                <div class="history-badge bg-{{ $statusColors[$history->status] }}">
                                    <i class="fas 
                                        @if($history->status == 'complete') fa-check
                                        @elseif($history->status == 'en_attente') fa-clock
                                        @elseif($history->status == 'echoue') fa-times
                                        @elseif($history->status == 'rembourse') fa-undo
                                        @else fa-circle
                                        @endif"></i>
                                </div>
                                <div class="history-content">
                                    <div class="history-header">
                                        <a href="{{ route('payments.show', $history->id) }}" class="history-ref">
                                            {{ $history->payment_reference }}
                                        </a>
                                        <span class="history-amount">{{ number_format($history->amount, 2) }} €</span>
                                    </div>
                                    <div class="history-details">
                                        <span class="history-date">
                                            <i class="far fa-calendar me-1"></i>{{ $history->payment_date->format('d/m/Y') }}
                                        </span>
                                        <span class="history-method">
                                            <i class="fas fa-credit-card me-1"></i>{{ ucfirst($history->method) }}
                                        </span>
                                        <span class="badge bg-{{ $statusColors[$history->status] }}">
                                            {{ $statusLabels[$history->status] }}
                                        </span>
                                    </div>
                                    @if($history->invoice)
                                    <div class="history-invoice">
                                        <i class="fas fa-file-invoice me-1"></i>
                                        Facture: <a href="{{ route('invoices.show', $history->invoice_id) }}">{{ $history->invoice->invoice_number }}</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted text-center py-4">Aucun autre paiement pour ce client.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes Tab -->
            <div class="tab-pane fade" id="notes" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </h3>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i class="fas fa-plus me-1"></i>Ajouter une note
                        </button>
                    </div>
                    
                    <div class="card-body-modern">
                        @if($payment->notes)
                        <div class="notes-display">
                            {!! nl2br(e($payment->notes)) !!}
                        </div>
                        @else
                        <p class="text-muted text-center py-4">Aucune note pour ce paiement.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <div class="fab-modern" id="helpFab" data-bs-toggle="tooltip" title="Options">
            <i class="fas fa-ellipsis-v"></i>
        </div>

        <!-- Quick Actions Menu -->
        <div class="quick-actions-menu" id="quickActionsMenu" style="display: none;">
            <button class="quick-action-item" onclick="updateStatus('complete')">
                <i class="fas fa-check-circle text-success"></i>
                <span>Marquer complété</span>
            </button>
            <button class="quick-action-item" onclick="updateStatus('echoue')">
                <i class="fas fa-times-circle text-danger"></i>
                <span>Marquer échoué</span>
            </button>
            <button class="quick-action-item" onclick="updateStatus('rembourse')">
                <i class="fas fa-undo text-info"></i>
                <span>Rembourser</span>
            </button>
            <a href="{{ route('payments.receipt', $payment->id) }}" class="quick-action-item">
                <i class="fas fa-download text-primary"></i>
                <span>Télécharger</span>
            </a>
            <button class="quick-action-item" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                <i class="fas fa-sticky-note text-secondary"></i>
                <span>Ajouter note</span>
            </button>
        </div>
    </main>

    <!-- UPDATE STATUS MODAL -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le statut du paiement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nouveau statut</label>
                            <select class="form-select" name="status" id="newStatus" required>
                                <option value="en_attente" {{ $payment->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="complete" {{ $payment->status == 'complete' ? 'selected' : '' }}>Complété</option>
                                <option value="echoue" {{ $payment->status == 'echoue' ? 'selected' : '' }}>Échoué</option>
                                <option value="rembourse" {{ $payment->status == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                                <option value="partiel" {{ $payment->status == 'partiel' ? 'selected' : '' }}>Partiel</option>
                            </select>
                        </div>
                        
                        @if($payment->invoice && $payment->invoice->remaining_amount > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Cette facture a encore un solde de {{ number_format($payment->invoice->remaining_amount, 2) }} €.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ADD NOTE MODAL -->
    <div class="modal fade" id="addNoteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addNoteForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" name="note" rows="4" required 
                                      placeholder="Entrez votre note ici..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Floating action button menu
            const helpFab = document.getElementById('helpFab');
            const quickActionsMenu = document.getElementById('quickActionsMenu');

            if (helpFab && quickActionsMenu) {
                helpFab.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = quickActionsMenu.style.display === 'block';
                    quickActionsMenu.style.display = isVisible ? 'none' : 'block';
                });

                document.addEventListener('click', function() {
                    quickActionsMenu.style.display = 'none';
                });

                quickActionsMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Update status form
            const updateStatusForm = document.getElementById('updateStatusForm');
            if (updateStatusForm) {
                updateStatusForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const status = document.getElementById('newStatus').value;
                    updateStatus(status);
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
                    modal.hide();
                });
            }

            // Add note form
            const addNoteForm = document.getElementById('addNoteForm');
            if (addNoteForm) {
                addNoteForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    $.ajax({
                        url: '{{ route("payments.add-note", $payment->id) }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                showAlert('success', response.message);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        },
                        error: function(xhr) {
                            showAlert('danger', 'Erreur lors de l\'ajout de la note');
                        }
                    });
                });
            }

            // Send receipt
            document.getElementById('sendReceiptBtn')?.addEventListener('click', sendReceipt);
            document.getElementById('quickSendReceipt')?.addEventListener('click', sendReceipt);
        });

        function updateStatus(status) {
            $.ajax({
                url: '{{ route("payments.update-status", $payment->id) }}',
                type: 'POST',
                data: {
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    showAlert('danger', 'Erreur lors de la mise à jour du statut');
                }
            });
        }

        function sendReceipt() {
            $.ajax({
                url: '{{ route("payments.send-receipt", $payment->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('danger', 'Erreur lors de l\'envoi du reçu');
                }
            });
        }

        function showAlert(type, message) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alert.style.zIndex = '9999';
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
        /* Payment Show Specific Styles */
        .payment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .payment-amount {
            text-align: center;
        }

        .amount-label {
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .amount-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1.2;
        }

        .payment-quick-info {
            display: flex;
            gap: 30px;
        }

        .info-item {
            text-align: center;
        }

        .info-label {
            font-size: 0.85rem;
            color: #666;
            display: block;
        }

        .info-value {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #f0f0f0;
        }

        .client-info-card {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .client-avatar {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .client-details {
            flex: 1;
        }

        .client-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .client-contact {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 2px;
        }

        .client-contact i {
            width: 20px;
        }

        .payment-details-list {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eaeaea;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row span:first-child {
            color: #666;
        }

        .detail-value {
            font-weight: 600;
            color: #333;
        }

        /* Status Timeline */
        .status-timeline {
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-item.current .timeline-badge {
            background: var(--primary-color);
        }

        .timeline-badge {
            position: absolute;
            left: -30px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
        }

        .timeline-title {
            font-weight: 600;
            color: #333;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #666;
        }

        /* Stats Mini Grid */
        .stats-mini-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-mini-item {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-mini-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 3px;
        }

        .stat-mini-value {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        /* Quick Actions Grid */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px;
            background: #f8f9fa;
            border: none;
            border-radius: 8px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
        }

        .quick-action-btn:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .quick-action-btn i {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .quick-action-btn span {
            font-size: 0.8rem;
            text-align: center;
        }

        /* Related Payments List */
        .related-payments-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .related-payment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }

        .related-payment-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .related-payment-info {
            flex: 1;
        }

        .related-payment-ref {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .related-payment-date {
            font-size: 0.8rem;
            color: #666;
        }

        .related-payment-amount {
            font-weight: 600;
            color: var(--primary-color);
            margin-right: 10px;
        }

        /* Invoice Summary */
        .invoice-summary {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .invoice-detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eaeaea;
        }

        .invoice-detail:last-child {
            border-bottom: none;
        }

        .invoice-detail .detail-label {
            color: #666;
        }

        .invoice-detail .detail-value {
            font-weight: 600;
            color: #333;
        }

        /* History Timeline */
        .history-timeline {
            position: relative;
            padding-left: 40px;
        }

        .history-timeline::before {
            content: '';
            position: absolute;
            left: 19px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .history-item {
            position: relative;
            margin-bottom: 20px;
        }

        .history-badge {
            position: absolute;
            left: -40px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            z-index: 1;
        }

        .history-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .history-ref {
            font-weight: 600;
            color: var(--primary-color);
            text-decoration: none;
        }

        .history-amount {
            font-weight: 700;
            color: #333;
        }

        .history-details {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .history-invoice {
            font-size: 0.9rem;
            color: #666;
            padding-top: 5px;
            border-top: 1px dashed #eaeaea;
        }

        .history-invoice a {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* Notes Display */
        .notes-display {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            white-space: pre-wrap;
            font-family: inherit;
            line-height: 1.6;
        }

        /* Quick Actions Menu */
        .quick-actions-menu {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
            z-index: 999;
            min-width: 200px;
        }

        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }

        .quick-action-item:hover {
            background: #f8f9fa;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .payment-header {
                flex-direction: column;
                gap: 20px;
            }
            
            .payment-quick-info {
                width: 100%;
                justify-content: space-around;
            }
        }

        @media (max-width: 768px) {
            .stats-mini-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
            
            .history-details {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
@endsection