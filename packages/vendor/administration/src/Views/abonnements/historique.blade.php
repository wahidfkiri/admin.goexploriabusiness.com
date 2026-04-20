{{-- resources/views/admin/abonnements/historique.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <!-- Header -->
    <div class="page-header-modern">
        <div class="page-header-left">
            <div class="page-icon">
                <i class="fas fa-history"></i>
            </div>
            <div>
                <h1 class="page-title-modern">Historique des abonnements</h1>
                <p class="page-subtitle">
                    Établissement : <strong>{{ $etablissement->name }}</strong> 
                    @if($etablissement->ville) - {{ $etablissement->ville }} @endif
                </p>
            </div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('abonnements.index') }}" class="btn-secondary-modern">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('abonnements.create') }}?etablissement={{ $etablissement->id }}" class="btn-primary-modern ms-2">
                <i class="fas fa-plus-circle me-2"></i>Nouvel abonnement
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid-modern">
        <div class="stat-card">
            <div class="stat-icon bg-primary-gradient">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $historique->count() }}</h3>
                <p class="stat-label">Total abonnements</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-success-gradient">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">
                    {{ $historique->where('status', 'active')->where('end_date', '>=', now())->count() }}
                </h3>
                <p class="stat-label">Abonnements actifs</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-warning-gradient">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">
                    {{ number_format($historique->sum('amount_paid'), 0, ',', ' ') }} FCFA
                </h3>
                <p class="stat-label">Total payé</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-info-gradient">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">
                    @php
                        $currentSub = $historique->where('status', 'active')->where('end_date', '>=', now())->first();
                    @endphp
                    @if($currentSub)
                        {{ $currentSub->daysRemaining() }} jours
                    @else
                        Aucun
                    @endif
                </h3>
                <p class="stat-label">Jours restants</p>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="timeline-container-modern">
        <div class="timeline-header">
            <h3 class="timeline-title">
                <i class="fas fa-stream me-2"></i>
                Chronologie des abonnements
            </h3>
        </div>
        
        @if($historique->count() > 0)
            <div class="timeline">
                @foreach($historique as $abonnement)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $abonnement->status }}">
                            @if($abonnement->status === 'active' && $abonnement->end_date >= now())
                                <i class="fas fa-check-circle"></i>
                            @elseif($abonnement->status === 'cancelled')
                                <i class="fas fa-ban"></i>
                            @elseif($abonnement->status === 'expired' || $abonnement->end_date < now())
                                <i class="fas fa-clock"></i>
                            @else
                                <i class="fas fa-hourglass-half"></i>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header-content">
                                <div class="timeline-reference">
                                    <strong>{{ $abonnement->reference }}</strong>
                                    <span class="timeline-status status-{{ $abonnement->status }}">
                                        @if($abonnement->status === 'active' && $abonnement->end_date >= now())
                                            Actif
                                        @elseif($abonnement->status === 'cancelled')
                                            Annulé
                                        @elseif($abonnement->status === 'expired' || $abonnement->end_date < now())
                                            Expiré
                                        @else
                                            En attente
                                        @endif
                                    </span>
                                </div>
                                <div class="timeline-date">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $abonnement->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            
                            <div class="timeline-details">
                                <div class="detail-row">
                                    <div class="detail-label">Plan :</div>
                                    <div class="detail-value">
                                        <span class="plan-badge">{{ $abonnement->plan->name }}</span>
                                    </div>
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-label">Période :</div>
                                    <div class="detail-value">
                                        {{ $abonnement->start_date->format('d/m/Y') }} 
                                        <i class="fas fa-arrow-right mx-1"></i> 
                                        {{ $abonnement->end_date->format('d/m/Y') }}
                                        @if($abonnement->end_date >= now() && $abonnement->status === 'active')
                                            <span class="days-badge">
                                                <i class="fas fa-hourglass-half"></i>
                                                {{ $abonnement->daysRemaining() }} jours restants
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-label">Montant :</div>
                                    <div class="detail-value">
                                        <strong class="amount">{{ number_format($abonnement->amount_paid, 0, ',', ' ') }} {{ $abonnement->currency }}</strong>
                                        <span class="payment-status payment-{{ $abonnement->payment_status }}">
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
                                </div>
                                
                                @if($abonnement->auto_renew)
                                <div class="detail-row">
                                    <div class="detail-label"></div>
                                    <div class="detail-value">
                                        <span class="auto-renew-badge">
                                            <i class="fas fa-sync-alt"></i> Renouvellement automatique activé
                                        </span>
                                    </div>
                                </div>
                                @endif
                                
                                @if($abonnement->cancelled_at)
                                <div class="detail-row">
                                    <div class="detail-label">Annulé le :</div>
                                    <div class="detail-value">
                                        {{ $abonnement->cancelled_at->format('d/m/Y H:i') }}
                                        @if($abonnement->cancellation_reason)
                                            <div class="cancel-reason">
                                                <i class="fas fa-comment"></i> Motif : {{ $abonnement->cancellation_reason }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            @if($abonnement->paiements->count() > 0)
                            <div class="timeline-payments">
                                <div class="payments-title">
                                    <i class="fas fa-credit-card"></i> Paiements associés
                                </div>
                                <div class="payments-list">
                                    @foreach($abonnement->paiements as $paiement)
                                    <div class="payment-item">
                                        <div class="payment-ref">{{ $paiement->reference }}</div>
                                        <div class="payment-amount">{{ number_format($paiement->amount, 0, ',', ' ') }} {{ $paiement->currency }}</div>
                                        <div class="payment-method">
                                            <i class="fas {{ $paiement->payment_method === 'card' ? 'fa-credit-card' : ($paiement->payment_method === 'bank_transfer' ? 'fa-university' : ($paiement->payment_method === 'mobile_money' ? 'fa-mobile-alt' : 'fa-money-bill')) }}"></i>
                                            {{ ucfirst(str_replace('_', ' ', $paiement->payment_method)) }}
                                        </div>
                                        <div class="payment-date">{{ $paiement->created_at->format('d/m/Y') }}</div>
                                        <div class="payment-status-badge status-{{ $paiement->status }}">
                                            {{ ucfirst($paiement->status) }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <div class="timeline-actions">
                                <a href="{{ route('abonnements.edit', $abonnement->id) }}" class="btn-sm-edit">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                @if($abonnement->status === 'active' && $abonnement->end_date >= now())
                                <button onclick="cancelAbonnement({{ $abonnement->id }})" class="btn-sm-cancel">
                                    <i class="fas fa-ban"></i> Annuler
                                </button>
                                @endif
                                @if($abonnement->end_date < now() || $abonnement->status === 'expired')
                                <button onclick="renewAbonnement({{ $abonnement->id }})" class="btn-sm-renew">
                                    <i class="fas fa-sync-alt"></i> Renouveler
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state-modern">
                <div class="empty-icon">
                    <i class="fas fa-history"></i>
                </div>
                <h3>Aucun historique</h3>
                <p>Aucun abonnement trouvé pour cet établissement</p>
                <a href="{{ route('abonnements.create') }}?etablissement={{ $etablissement->id }}" class="btn-primary-modern">
                    <i class="fas fa-plus-circle me-2"></i>Créer un abonnement
                </a>
            </div>
        @endif
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
    // Fonctions globales
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
                            location.reload();
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

.stats-grid-modern {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 28px;
    color: white;
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.stat-label {
    color: #64748b;
    margin: 0;
    font-size: 13px;
}

.bg-primary-gradient {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.bg-success-gradient {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.bg-warning-gradient {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.bg-info-gradient {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.timeline-container-modern {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.timeline-header {
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbfc;
}

.timeline-title {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.timeline {
    padding: 24px;
}

.timeline-item {
    display: flex;
    gap: 20px;
    margin-bottom: 32px;
    position: relative;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 23px;
    top: 48px;
    bottom: -32px;
    width: 2px;
    background: #e2e8f0;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    z-index: 1;
}

.timeline-marker.active {
    background: #d1fae5;
    color: #059669;
}

.timeline-marker.cancelled {
    background: #fee2e2;
    color: #dc2626;
}

.timeline-marker.expired {
    background: #fef3c7;
    color: #d97706;
}

.timeline-marker.pending {
    background: #e2e8f0;
    color: #64748b;
}

.timeline-content {
    flex: 1;
    background: #f8fafc;
    border-radius: 16px;
    padding: 20px;
    transition: all 0.2s;
}

.timeline-content:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.timeline-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
}

.timeline-reference {
    font-size: 16px;
}

.timeline-status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    margin-left: 10px;
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

.timeline-date {
    font-size: 12px;
    color: #94a3b8;
}

.timeline-details {
    margin-bottom: 16px;
}

.detail-row {
    display: flex;
    margin-bottom: 10px;
    flex-wrap: wrap;
    gap: 8px;
}

.detail-label {
    width: 100px;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
}

.detail-value {
    flex: 1;
    font-size: 13px;
    color: #1e293b;
}

.plan-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #e0e7ff;
    color: #6366f1;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.amount {
    font-size: 16px;
    color: #6366f1;
}

.payment-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    margin-left: 10px;
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

.days-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #dbeafe;
    color: #2563eb;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    margin-left: 10px;
}

.auto-renew-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: #e0e7ff;
    color: #6366f1;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}

.cancel-reason {
    margin-top: 8px;
    padding: 8px 12px;
    background: #fef3c7;
    border-radius: 10px;
    font-size: 12px;
    color: #92400e;
}

.timeline-payments {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.payments-title {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 12px;
}

.payments-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.payment-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 10px 12px;
    background: white;
    border-radius: 12px;
    font-size: 12px;
    flex-wrap: wrap;
}

.payment-ref {
    font-weight: 600;
    color: #1e293b;
    font-family: monospace;
}

.payment-amount {
    font-weight: 700;
    color: #6366f1;
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
}

.payment-date {
    color: #94a3b8;
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

.timeline-actions {
    display: flex;
    gap: 12px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.btn-sm-edit, .btn-sm-cancel, .btn-sm-renew {
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-sm-edit {
    background: #e0e7ff;
    color: #6366f1;
}

.btn-sm-edit:hover {
    background: #c7d2fe;
    transform: translateY(-1px);
}

.btn-sm-cancel {
    background: #fee2e2;
    color: #dc2626;
}

.btn-sm-cancel:hover {
    background: #fecaca;
    transform: translateY(-1px);
}

.btn-sm-renew {
    background: #d1fae5;
    color: #059669;
}

.btn-sm-renew:hover {
    background: #a7f3d0;
    transform: translateY(-1px);
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
    border: none;
    cursor: pointer;
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
    border: none;
    cursor: pointer;
}

.btn-secondary-modern:hover {
    background: #e2e8f0;
    color: #475569;
}

.empty-state-modern {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    font-size: 80px;
    color: #cbd5e1;
    margin-bottom: 24px;
}

.empty-state-modern h3 {
    font-size: 24px;
    color: #1e293b;
    margin-bottom: 12px;
}

.empty-state-modern p {
    color: #64748b;
    margin-bottom: 24px;
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
    transition: all 0.2s;
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

@media (max-width: 1200px) {
    .stats-grid-modern {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-content {
        padding: 16px;
    }
    
    .stats-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .page-header-modern {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .timeline {
        padding: 16px;
    }
    
    .timeline-item {
        flex-direction: column;
    }
    
    .timeline-item::before {
        display: none;
    }
    
    .timeline-marker {
        align-self: flex-start;
    }
    
    .timeline-header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .detail-label {
        width: 100%;
    }
    
    .payment-item {
        flex-direction: column;
        align-items: flex-start;
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