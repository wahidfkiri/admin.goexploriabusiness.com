{{-- resources/views/admin/abonnements/show.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <!-- Header -->
    <div class="page-header-modern">
        <div class="page-header-left">
            <div class="page-icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <h1 class="page-title-modern">Détail de l'abonnement</h1>
                <p class="page-subtitle">
                    Référence : <strong>{{ $abonnement->reference }}</strong>
                </p>
            </div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('abonnements.index') }}" class="btn-secondary-modern">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('abonnements.edit', $abonnement->id) }}" class="btn-primary-modern ms-2">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
        </div>
    </div>

    <div class="details-container-modern">
        <div class="details-grid">
            <!-- Left Column - Informations principales -->
            <div class="details-main">
                <!-- Établissement Card -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="header-title">Établissement</h3>
                    </div>
                    <div class="detail-card-body">
                        <div class="info-row">
                            <div class="info-label">Nom :</div>
                            <div class="info-value">
                                <strong>{{ $abonnement->etablissement->name }}</strong>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Ville :</div>
                            <div class="info-value">{{ $abonnement->etablissement->ville ?? 'Non renseigné' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Adresse :</div>
                            <div class="info-value">{{ $abonnement->etablissement->adresse ?? 'Non renseignée' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email :</div>
                            <div class="info-value">{{ $abonnement->etablissement->email_contact ?? 'Non renseigné' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Téléphone :</div>
                            <div class="info-value">{{ $abonnement->etablissement->phone ?? 'Non renseigné' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Plan Card -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h3 class="header-title">Plan d'abonnement</h3>
                    </div>
                    <div class="detail-card-body">
                        <div class="info-row">
                            <div class="info-label">Plan :</div>
                            <div class="info-value">
                                <span class="plan-badge">{{ $abonnement->plan->name }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Prix :</div>
                            <div class="info-value">
                                <strong class="price-value">{{ number_format($abonnement->plan->price, 0, ',', ' ') }} {{ $abonnement->plan->currency }}</strong>
                                <span class="cycle-badge">/{{ $abonnement->plan->billing_cycle === 'yearly' ? 'an' : ($abonnement->plan->billing_cycle === 'monthly' ? 'mois' : 'personnalisé') }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Durée :</div>
                            <div class="info-value">{{ $abonnement->plan->duration_days }} jours</div>
                        </div>
                        @if($abonnement->plan->description)
                        <div class="info-row">
                            <div class="info-label">Description :</div>
                            <div class="info-value">{{ $abonnement->plan->description }}</div>
                        </div>
                        @endif
                        @if($abonnement->plan->services)
                        <div class="info-row">
                            <div class="info-label">Services :</div>
                            <div class="info-value services-content">
                                {!! $abonnement->plan->services !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Période Card -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="header-title">Période de validité</h3>
                    </div>
                    <div class="detail-card-body">
                        <div class="info-row">
                            <div class="info-label">Date de début :</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-day me-1"></i>
                                {{ $abonnement->start_date->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date de fin :</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-day me-1"></i>
                                {{ $abonnement->end_date->format('d/m/Y') }}
                                @if($abonnement->isActive())
                                    <span class="days-remaining">
                                        <i class="fas fa-hourglass-half"></i>
                                        {{ $abonnement->daysRemaining() }} jours restants
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Durée totale :</div>
                            <div class="info-value">
                                {{ $abonnement->start_date->diffInDays($abonnement->end_date) }} jours
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Statut et Paiements -->
            <div class="details-sidebar">
                <!-- Statut Card -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="header-title">Statut</h3>
                    </div>
                    <div class="detail-card-body">
                        <div class="status-container">
                            <div class="status-item">
                                <span class="status-label">Abonnement :</span>
                                <span class="status-badge status-{{ $abonnement->status }}">
                                    @if($abonnement->status === 'active' && $abonnement->end_date >= now())
                                        <i class="fas fa-check-circle"></i> Actif
                                    @elseif($abonnement->status === 'cancelled')
                                        <i class="fas fa-ban"></i> Annulé
                                    @elseif($abonnement->status === 'expired' || $abonnement->end_date < now())
                                        <i class="fas fa-clock"></i> Expiré
                                    @else
                                        <i class="fas fa-hourglass-half"></i> En attente
                                    @endif
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Paiement :</span>
                                <span class="status-badge payment-{{ $abonnement->payment_status }}">
                                    @if($abonnement->payment_status === 'paid')
                                        <i class="fas fa-check-circle"></i> Payé
                                    @elseif($abonnement->payment_status === 'unpaid')
                                        <i class="fas fa-times-circle"></i> Impayé
                                    @elseif($abonnement->payment_status === 'partial')
                                        <i class="fas fa-chart-line"></i> Partiel
                                    @else
                                        <i class="fas fa-undo"></i> Remboursé
                                    @endif
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Renouvellement :</span>
                                <span class="status-badge {{ $abonnement->auto_renew ? 'auto-renew-on' : 'auto-renew-off' }}">
                                    <i class="fas {{ $abonnement->auto_renew ? 'fa-sync-alt' : 'fa-ban' }}"></i>
                                    {{ $abonnement->auto_renew ? 'Automatique' : 'Manuel' }}
                                </span>
                            </div>
                        </div>
                        
                        @if($abonnement->cancelled_at)
                        <div class="cancel-info">
                            <div class="cancel-title">
                                <i class="fas fa-ban"></i> Annulation
                            </div>
                            <div class="cancel-date">
                                Date : {{ $abonnement->cancelled_at->format('d/m/Y H:i') }}
                            </div>
                            @if($abonnement->cancellation_reason)
                            <div class="cancel-reason">
                                Motif : {{ $abonnement->cancellation_reason }}
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Montant Card -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h3 class="header-title">Montant</h3>
                    </div>
                    <div class="detail-card-body amount-card">
                        <div class="amount-display">
                            <span class="amount-label">Montant payé</span>
                            <span class="amount-value">{{ number_format($abonnement->amount_paid, 0, ',', ' ') }} {{ $abonnement->currency }}</span>
                        </div>
                    </div>
                </div>

                <!-- Paiements Card -->
                @if($abonnement->paiements->count() > 0)
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="header-title">
                            Historique des paiements
                            <span class="badge-count">{{ $abonnement->paiements->count() }}</span>
                        </h3>
                    </div>
                    <div class="detail-card-body payments-list">
                        @foreach($abonnement->paiements as $paiement)
                        <div class="payment-card">
                            <div class="payment-header">
                                <span class="payment-ref">{{ $paiement->reference }}</span>
                                <span class="payment-status-badge status-{{ $paiement->status }}">
                                    {{ ucfirst($paiement->status) }}
                                </span>
                            </div>
                            <div class="payment-body">
                                <div class="payment-row">
                                    <span class="payment-label">Montant :</span>
                                    <span class="payment-value">{{ number_format($paiement->amount, 0, ',', ' ') }} {{ $paiement->currency }}</span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Méthode :</span>
                                    <span class="payment-value">
                                        <i class="fas {{ $paiement->payment_method === 'card' ? 'fa-credit-card' : ($paiement->payment_method === 'bank_transfer' ? 'fa-university' : ($paiement->payment_method === 'mobile_money' ? 'fa-mobile-alt' : 'fa-money-bill')) }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $paiement->payment_method)) }}
                                    </span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Date :</span>
                                    <span class="payment-value">{{ $paiement->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($paiement->paid_at)
                                <div class="payment-row">
                                    <span class="payment-label">Payé le :</span>
                                    <span class="payment-value">{{ $paiement->paid_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif
                                @if($paiement->notes)
                                <div class="payment-row">
                                    <span class="payment-label">Notes :</span>
                                    <span class="payment-value">{{ $paiement->notes }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions Card -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="header-title">Actions</h3>
                    </div>
                    <div class="detail-card-body actions-container">
                        @if($abonnement->status === 'active' && $abonnement->end_date >= now())
                        <button onclick="cancelAbonnement({{ $abonnement->id }})" class="action-btn btn-cancel">
                            <i class="fas fa-ban"></i> Annuler l'abonnement
                        </button>
                        @endif
                        
                        @if($abonnement->end_date < now() || $abonnement->status === 'expired')
                        <button onclick="renewAbonnement({{ $abonnement->id }})" class="action-btn btn-renew">
                            <i class="fas fa-sync-alt"></i> Renouveler
                        </button>
                        @endif
                        
                        <button onclick="printAbonnement({{ $abonnement->id }})" class="action-btn btn-print">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content-modern">
            <div class="modal-header-modern">
                <h5 class="modal-title">Annuler l'abonnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-modern">
                <div class="cancel-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <h4 class="text-center">Motif d'annulation</h4>
                <div class="form-group mt-3">
                    <textarea id="cancelReason" class="form-control-modern" rows="3" placeholder="Veuillez indiquer le motif de l'annulation..."></textarea>
                </div>
            </div>
            <div class="modal-footer-modern">
                <button type="button" class="btn-secondary-modern" data-bs-dismiss="modal">Retour</button>
                <button type="button" id="confirmCancel" class="btn-warning-modern">Confirmer l'annulation</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let currentCancelId = null;

$(document).ready(function() {
    window.cancelAbonnement = function(id) {
        currentCancelId = id;
        $('#cancelReason').val('');
        $('#cancelModal').modal('show');
    };
    
    window.renewAbonnement = function(id) {
        if (confirm('Voulez-vous renouveler cet abonnement ?')) {
            $.ajax({
                url: `/admin/abonnements/${id}/renew`,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        setTimeout(function() {
                            window.location.href = '{{ route("abonnements.index") }}';
                        }, 1500);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Erreur lors du renouvellement');
                }
            });
        }
    };
    
    window.printAbonnement = function(id) {
        window.open(`/admin/abonnements/${id}/print`, '_blank');
    };
    
    $('#confirmCancel').click(function() {
        if (currentCancelId) {
            const reason = $('#cancelReason').val();
            
            $.ajax({
                url: `/admin/abonnements/${currentCancelId}/cancel`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        $('#cancelModal').modal('hide');
                        showToast('success', response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Erreur lors de l\'annulation');
                    $('#cancelModal').modal('hide');
                }
            });
        }
    });
    
    function showToast(type, message) {
        const icon = type === 'success' ? '✓' : '✗';
        const bgClass = type === 'success' ? 'toast-success' : 'toast-error';
        const title = type === 'success' ? 'Succès' : 'Erreur';
        
        const toast = $(`
            <div class="toast-notification ${bgClass}">
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${escapeHtml(message)}</div>
                </div>
                <button class="toast-close">&times;</button>
            </div>
        `);
        
        $('#toastContainer').append(toast);
        
        setTimeout(() => toast.addClass('show'), 10);
        
        const timeout = setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
        
        toast.find('.toast-close').click(() => {
            clearTimeout(timeout);
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>

<style>
.dashboard-content {
    padding: 24px 32px;
    background: #f8fafc;
    min-height: 100vh;
}

.page-header-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.page-icon i {
    font-size: 28px;
    color: white;
}

.page-title-modern {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.page-subtitle {
    color: #64748b;
    margin: 0;
    font-size: 14px;
}

.details-container-modern {
    max-width: 1400px;
    margin: 0 auto;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
}

.detail-card {
    background: white;
    border-radius: 20px;
    margin-bottom: 24px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.detail-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 24px;
    background: #fafbfc;
    border-bottom: 1px solid #f1f5f9;
}

.header-icon {
    width: 40px;
    height: 40px;
    background: #e0e7ff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon i {
    font-size: 20px;
    color: #6366f1;
}

.header-title {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.detail-card-body {
    padding: 20px 24px;
}

.info-row {
    display: flex;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 8px;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    width: 110px;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
}

.info-value {
    flex: 1;
    font-size: 14px;
    color: #1e293b;
}

.plan-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #e0e7ff;
    color: #6366f1;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.price-value {
    font-size: 18px;
    color: #6366f1;
}

.cycle-badge {
    display: inline-block;
    padding: 2px 8px;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 12px;
    font-size: 11px;
    margin-left: 8px;
}

.days-remaining {
    display: inline-block;
    padding: 2px 8px;
    background: #dbeafe;
    color: #2563eb;
    border-radius: 12px;
    font-size: 11px;
    margin-left: 10px;
}

.services-content {
    font-size: 13px;
    line-height: 1.6;
}

.services-content ul {
    margin: 0;
    padding-left: 20px;
}

.services-content li {
    margin-bottom: 6px;
}

.status-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
}

.status-item:last-child {
    border-bottom: none;
}

.status-label {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-active {
    background: #d1fae5;
    color: #059669;
}

.status-cancelled {
    background: #fee2e2;
    color: #dc2626;
}

.status-expired {
    background: #fef3c7;
    color: #d97706;
}

.status-pending {
    background: #e2e8f0;
    color: #64748b;
}

.payment-paid {
    background: #d1fae5;
    color: #059669;
}

.payment-unpaid {
    background: #fee2e2;
    color: #dc2626;
}

.payment-partial {
    background: #fef3c7;
    color: #d97706;
}

.payment-refunded {
    background: #e2e8f0;
    color: #64748b;
}

.auto-renew-on {
    background: #dbeafe;
    color: #2563eb;
}

.auto-renew-off {
    background: #fee2e2;
    color: #dc2626;
}

.cancel-info {
    margin-top: 16px;
    padding: 12px;
    background: #fef3c7;
    border-radius: 12px;
}

.cancel-title {
    font-weight: 600;
    color: #92400e;
    margin-bottom: 8px;
}

.cancel-date, .cancel-reason {
    font-size: 12px;
    color: #78350f;
    margin-top: 4px;
}

.amount-card {
    text-align: center;
}

.amount-display {
    padding: 16px;
}

.amount-label {
    display: block;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 8px;
}

.amount-value {
    font-size: 32px;
    font-weight: 700;
    color: #6366f1;
}

.badge-count {
    display: inline-block;
    margin-left: 8px;
    padding: 2px 8px;
    background: #e2e8f0;
    color: #64748b;
    border-radius: 20px;
    font-size: 11px;
}

.payments-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-card {
    background: #f8fafc;
    border-radius: 12px;
    overflow: hidden;
}

.payment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
}

.payment-ref {
    font-weight: 600;
    font-size: 13px;
    color: #1e293b;
    font-family: monospace;
}

.payment-status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}

.payment-status-badge.status-completed,
.payment-status-badge.status-paid {
    background: #d1fae5;
    color: #059669;
}

.payment-status-badge.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.payment-status-badge.status-failed,
.payment-status-badge.status-refunded {
    background: #fee2e2;
    color: #dc2626;
}

.payment-body {
    padding: 12px 16px;
}

.payment-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 12px;
}

.payment-row:last-child {
    margin-bottom: 0;
}

.payment-label {
    color: #64748b;
}

.payment-value {
    color: #1e293b;
    font-weight: 500;
}

.actions-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-cancel {
    background: #fee2e2;
    color: #dc2626;
}

.btn-cancel:hover {
    background: #fecaca;
    transform: translateY(-2px);
}

.btn-renew {
    background: #d1fae5;
    color: #059669;
}

.btn-renew:hover {
    background: #a7f3d0;
    transform: translateY(-2px);
}

.btn-print {
    background: #e2e8f0;
    color: #475569;
}

.btn-print:hover {
    background: #cbd5e1;
    transform: translateY(-2px);
}

.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99,102,241,0.3);
    color: white;
}

.btn-secondary-modern {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-secondary-modern:hover {
    background: #e2e8f0;
    color: #475569;
}

.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast-notification {
    min-width: 300px;
    background: white;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateX(400px);
    transition: transform 0.3s ease;
}

.toast-notification.show {
    transform: translateX(0);
}

.toast-success {
    border-left: 4px solid #10b981;
}

.toast-error {
    border-left: 4px solid #dc2626;
}

.toast-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
}

.toast-success .toast-icon {
    background: #d1fae5;
    color: #059669;
}

.toast-error .toast-icon {
    background: #fee2e2;
    color: #dc2626;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.toast-success .toast-title {
    color: #059669;
}

.toast-error .toast-title {
    color: #dc2626;
}

.toast-message {
    font-size: 13px;
    color: #64748b;
}

.toast-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #94a3b8;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
}

.toast-close:hover {
    background: #f1f5f9;
}

.modal-content-modern {
    border-radius: 24px;
    border: none;
}

.modal-header-modern {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
}

.modal-body-modern {
    padding: 24px;
}

.modal-footer-modern {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.cancel-icon {
    font-size: 56px;
    text-align: center;
    color: #d97706;
    margin-bottom: 20px;
}

.form-control-modern {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    font-size: 14px;
}

.form-control-modern:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}

.btn-warning-modern {
    padding: 10px 20px;
    background: #d97706;
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 500;
}

.btn-warning-modern:hover {
    background: #b45309;
}

@media (max-width: 1024px) {
    .details-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-content {
        padding: 16px;
    }
    
    .page-header-modern {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .info-label {
        width: 100%;
    }
    
    .status-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    
    .toast-container {
        left: 16px;
        right: 16px;
    }
    
    .toast-notification {
        width: calc(100% - 32px);
        min-width: auto;
    }
}
</style>
@endsection