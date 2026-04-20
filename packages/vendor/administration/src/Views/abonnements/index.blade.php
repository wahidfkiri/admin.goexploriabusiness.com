{{-- resources/views/admin/abonnements/index.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <!-- Header -->
    <div class="page-header-modern">
        <div class="page-header-left">
            <div class="page-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h1 class="page-title-modern">Gestion des Abonnements</h1>
                <p class="page-subtitle">Suivez et gérez les abonnements des établissements</p>
            </div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('abonnements.create') }}" class="btn-primary-modern">
                <i class="fas fa-plus-circle me-2"></i>Nouvel Abonnement
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
                <h3 class="stat-value" id="totalAbonnements">0</h3>
                <p class="stat-label">Total abonnements</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-success-gradient">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="activeAbonnements">0</h3>
                <p class="stat-label">Abonnements actifs</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-danger-gradient">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="expiredAbonnements">0</h3>
                <p class="stat-label">Expirés</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-warning-gradient">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="totalRevenue">0</h3>
                <p class="stat-label">Chiffre d'affaires</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar-modern">
        <div class="filter-group">
            <div class="filter-item">
                <label class="filter-label">Statut</label>
                <select id="filterStatus" class="filter-select">
                    <option value="">Tous</option>
                    <option value="active">Actif</option>
                    <option value="expired">Expiré</option>
                    <option value="cancelled">Annulé</option>
                    <option value="pending">En attente</option>
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">Paiement</label>
                <select id="filterPayment" class="filter-select">
                    <option value="">Tous</option>
                    <option value="paid">Payé</option>
                    <option value="unpaid">Impayé</option>
                    <option value="partial">Partiel</option>
                    <option value="refunded">Remboursé</option>
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">Plan</label>
                <select id="filterPlan" class="filter-select">
                    <option value="">Tous les plans</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">Date de début</label>
                <input type="date" id="filterDateFrom" class="filter-input">
            </div>
            <div class="filter-item">
                <label class="filter-label">Date de fin</label>
                <input type="date" id="filterDateTo" class="filter-input">
            </div>
            <div class="filter-item">
                <label class="filter-label">Recherche</label>
                <input type="text" id="filterSearch" class="filter-input" placeholder="Établissement, référence...">
            </div>
            <div class="filter-item filter-actions">
                <button id="applyFilters" class="btn-filter-apply">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <button id="resetFilters" class="btn-filter-reset">
                    <i class="fas fa-undo"></i> Réinitialiser
                </button>
                <button id="exportExcel" class="btn-filter-export">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Abonnements Table -->
    <div class="table-container-modern">
        <div id="loadingSpinner" class="loading-spinner">
            <div class="spinner"></div>
            <p>Chargement des abonnements...</p>
        </div>
        
        <div id="tableContent" style="display: none;"></div>
        
        <div id="emptyState" class="empty-state-modern" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3>Aucun abonnement trouvé</h3>
            <p>Commencez par créer un nouvel abonnement</p>
            <a href="{{ route('abonnements.create') }}" class="btn-primary-modern">
                <i class="fas fa-plus-circle me-2"></i>Créer un abonnement
            </a>
        </div>
    </div>
</main>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content-modern">
            <div class="modal-header-modern">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-modern text-center">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4>Êtes-vous sûr ?</h4>
                <p>Vous allez supprimer l'abonnement de <strong id="deleteAbonnementInfo"></strong></p>
                <p class="text-danger">Cette action est irréversible !</p>
            </div>
            <div class="modal-footer-modern">
                <button type="button" class="btn-secondary-modern" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirmDelete" class="btn-danger-modern">Supprimer</button>
            </div>
        </div>
    </div>
</div>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let currentDeleteId = null;
let currentCancelId = null;

$(document).ready(function() {
    loadAbonnements();
    
    // Apply filters on button click
    $('#applyFilters').click(function() {
        loadAbonnements();
    });
    
    // Reset filters
    $('#resetFilters').click(function() {
        $('#filterStatus').val('');
        $('#filterPayment').val('');
        $('#filterPlan').val('');
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        $('#filterSearch').val('');
        loadAbonnements();
    });
    
    // Export Excel
    $('#exportExcel').click(function(e) {
        e.preventDefault();
        
        let params = [];
        
        let status = $('#filterStatus').val();
        let paymentStatus = $('#filterPayment').val();
        let planId = $('#filterPlan').val();
        let dateFrom = $('#filterDateFrom').val();
        let dateTo = $('#filterDateTo').val();
        let search = $('#filterSearch').val();
        
        if (status && status !== '') params.push('status=' + encodeURIComponent(status));
        if (paymentStatus && paymentStatus !== '') params.push('payment_status=' + encodeURIComponent(paymentStatus));
        if (planId && planId !== '') params.push('plan_id=' + encodeURIComponent(planId));
        if (dateFrom && dateFrom !== '') params.push('date_from=' + encodeURIComponent(dateFrom));
        if (dateTo && dateTo !== '') params.push('date_to=' + encodeURIComponent(dateTo));
        if (search && search !== '') params.push('search=' + encodeURIComponent(search));
        
        let url = '{{ route("abonnements.export") }}';
        if (params.length > 0) {
            url += '?' + params.join('&');
        }
        
        window.location.href = url;
    });
    
    function loadAbonnements() {
        $('#loadingSpinner').show();
        $('#tableContent').hide();
        $('#emptyState').hide();
        
        let params = {};
        
        let status = $('#filterStatus').val();
        let paymentStatus = $('#filterPayment').val();
        let planId = $('#filterPlan').val();
        let dateFrom = $('#filterDateFrom').val();
        let dateTo = $('#filterDateTo').val();
        let search = $('#filterSearch').val();
        
        if (status && status !== '') params.status = status;
        if (paymentStatus && paymentStatus !== '') params.payment_status = paymentStatus;
        if (planId && planId !== '') params.plan_id = planId;
        if (dateFrom && dateFrom !== '') params.date_from = dateFrom;
        if (dateTo && dateTo !== '') params.date_to = dateTo;
        if (search && search !== '') params.search = search;
        
        $.ajax({
            url: '{{ route("abonnements.index") }}',
            type: 'GET',
            data: params,
            dataType: 'json',
            success: function(response) {
                $('#loadingSpinner').hide();
                
                if (response.success) {
                    if (response.abonnements && response.abonnements.data && response.abonnements.data.length > 0) {
                        renderTable(response.abonnements);
                        $('#tableContent').show();
                    } else {
                        $('#emptyState').show();
                    }
                    
                    if (response.stats) {
                        updateStats(response.stats);
                    }
                } else {
                    $('#emptyState').show();
                    showToast('error', response.message || 'Erreur lors du chargement');
                }
            },
            error: function(xhr) {
                $('#loadingSpinner').hide();
                $('#emptyState').show();
                console.error('AJAX Error:', xhr);
                showToast('error', 'Erreur lors du chargement des abonnements');
            }
        });
    }
    
    function renderTable(response) {
        let html = `
            <div class="table-responsive-modern">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Établissement</th>
                            <th>Plan</th>
                            <th>Montant</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        if (response.data && Array.isArray(response.data)) {
            for (let i = 0; i < response.data.length; i++) {
                let abonnement = response.data[i];
                let statusBadge = getStatusBadge(abonnement.status);
                let paymentBadge = getPaymentBadge(abonnement.payment_status);
                let isActive = abonnement.status === 'active' && new Date(abonnement.end_date) > new Date();
                let isExpired = new Date(abonnement.end_date) < new Date() || abonnement.status === 'expired';
                let etablissementName = abonnement.etablissement ? abonnement.etablissement.name : 'N/A';
                let etablissementVille = abonnement.etablissement ? abonnement.etablissement.ville : '';
                let planName = abonnement.plan ? abonnement.plan.name : 'N/A';
                let etablissementId = abonnement.etablissement ? abonnement.etablissement.id : abonnement.etablissement_id;
                
                html += `
                    <tr>
                        <td>
                            <strong>${escapeHtml(abonnement.reference)}</strong>
                            <br>
                            <small class="text-muted">${formatDate(abonnement.created_at)}</small>
                        </td>
                        <td>
                            <strong>${escapeHtml(etablissementName)}</strong>
                            <br>
                            <small>${escapeHtml(etablissementVille)}</small>
                        </td>
                        <td>
                            <span class="plan-badge">${escapeHtml(planName)}</span>
                        </td>
                        <td>
                            <strong>${formatNumber(abonnement.amount_paid)} ${abonnement.currency || 'CAD'}</strong>
                        </td>
                        <td>
                            ${formatDate(abonnement.start_date)}
                            <br>
                            <small>→ ${formatDate(abonnement.end_date)}</small>
                            ${isActive ? `<br><small class="text-success">+${abonnement.days_remaining || 0} jours</small>` : ''}
                        </td>
                        <td>${statusBadge}</td>
                        <td>${paymentBadge}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="/admin/abonnements/${abonnement.id}/edit" class="btn-icon edit-btn" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="showDeleteModal(${abonnement.id}, '${escapeHtml(etablissementName)}')" class="btn-icon delete-btn" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                ${isActive ? `<button type="button" onclick="showCancelModal(${abonnement.id})" class="btn-icon cancel-btn" title="Annuler">
                                    <i class="fas fa-ban"></i>
                                </button>` : ''}
                                ${isExpired ? `<button type="button" onclick="renewAbonnement(${abonnement.id})" class="btn-icon renew-btn" title="Renouveler">
                                    <i class="fas fa-sync-alt"></i>
                                </button>` : ''}
                                <button type="button" onclick="viewHistorique(${etablissementId})" class="btn-icon history-btn" title="Historique">
                                    <i class="fas fa-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        // Add pagination if exists
        if (response.links) {
            if (typeof response.links === 'string') {
                html += `<div class="pagination-modern">${response.links}</div>`;
            } else if (response.links.html) {
                html += `<div class="pagination-modern">${response.links.html}</div>`;
            }
        }
        
        $('#tableContent').html(html);
        
        // Re-attach event handlers for pagination links
        $('.pagination-modern a').on('click', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            if (url) {
                loadPage(url);
            }
        });
    }
    
    function loadPage(url) {
        $('#loadingSpinner').show();
        $('#tableContent').hide();
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#loadingSpinner').hide();
                if (response.success) {
                    if (response.abonnements && response.abonnements.data && response.abonnements.data.length > 0) {
                        renderTable(response.abonnements);
                        $('#tableContent').show();
                    } else {
                        $('#emptyState').show();
                    }
                    if (response.stats) updateStats(response.stats);
                } else {
                    $('#emptyState').show();
                }
            },
            error: function() {
                $('#loadingSpinner').hide();
                $('#emptyState').show();
                showToast('error', 'Erreur lors du chargement de la page');
            }
        });
    }
    
    function getStatusBadge(status) {
        const badges = {
            'active': '<span class="badge-active"><i class="fas fa-check-circle"></i> Actif</span>',
            'expired': '<span class="badge-expired"><i class="fas fa-clock"></i> Expiré</span>',
            'cancelled': '<span class="badge-cancelled"><i class="fas fa-ban"></i> Annulé</span>',
            'pending': '<span class="badge-pending"><i class="fas fa-hourglass"></i> En attente</span>'
        };
        return badges[status] || '<span class="badge-secondary">Inconnu</span>';
    }
    
    function getPaymentBadge(status) {
        const badges = {
            'paid': '<span class="badge-payment-paid"><i class="fas fa-check"></i> Payé</span>',
            'unpaid': '<span class="badge-payment-unpaid"><i class="fas fa-times"></i> Impayé</span>',
            'partial': '<span class="badge-payment-partial"><i class="fas fa-chart-line"></i> Partiel</span>',
            'refunded': '<span class="badge-payment-refunded"><i class="fas fa-undo"></i> Remboursé</span>'
        };
        return badges[status] || '<span class="badge-secondary">Inconnu</span>';
    }
    
    function updateStats(stats) {
        $('#totalAbonnements').text(stats.total || 0);
        $('#activeAbonnements').text(stats.active || 0);
        $('#expiredAbonnements').text(stats.expired || 0);
        $('#totalRevenue').text(formatNumber(stats.total_revenue || 0) + ' CAD');
    }
    
    // Delete functions
    window.showDeleteModal = function(id, name) {
        currentDeleteId = id;
        $('#deleteAbonnementInfo').text(name);
        $('#deleteModal').modal('show');
    };
    
    $('#confirmDelete').click(function() {
        if (currentDeleteId) {
            $.ajax({
                url: '/admin/abonnements/' + currentDeleteId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').modal('hide');
                        showToast('success', response.message);
                        loadAbonnements();
                    } else {
                        showToast('error', response.message);
                        $('#deleteModal').modal('hide');
                    }
                },
                error: function(xhr) {
                    let message = 'Erreur lors de la suppression';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showToast('error', message);
                    $('#deleteModal').modal('hide');
                }
            });
        }
    });
    
    // Cancel functions
    window.showCancelModal = function(id) {
        currentCancelId = id;
        $('#cancelReason').val('');
        $('#cancelModal').modal('show');
    };
    
    $('#confirmCancel').click(function() {
        if (currentCancelId) {
            let reason = $('#cancelReason').val();
            
            $.ajax({
                url: '/admin/abonnements/' + currentCancelId + '/cancel',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        $('#cancelModal').modal('hide');
                        showToast('success', response.message);
                        loadAbonnements();
                    } else {
                        showToast('error', response.message);
                        $('#cancelModal').modal('hide');
                    }
                },
                error: function() {
                    showToast('error', 'Erreur lors de l\'annulation');
                    $('#cancelModal').modal('hide');
                }
            });
        }
    });
    
    // Renew function
    window.renewAbonnement = function(id) {
        if (confirm('Voulez-vous renouveler cet abonnement ?')) {
            $.ajax({
                url: '/admin/abonnements/' + id + '/renew',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        loadAbonnements();
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
    
    // View history
    window.viewHistorique = function(etablissementId) {
        window.location.href = '/admin/abonnements/historique/' + etablissementId;
    };
    
    // Toast notification system
    function showToast(type, message) {
        let icon = type === 'success' ? '✓' : '✗';
        let bgClass = type === 'success' ? 'toast-success' : 'toast-error';
        let title = type === 'success' ? 'Succès' : 'Erreur';
        
        let toast = $(`
            <div class="toast-notification ${bgClass}">
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${escapeHtml(message)}</div>
                </div>
                <button type="button" class="toast-close">&times;</button>
            </div>
        `);
        
        $('#toastContainer').append(toast);
        
        setTimeout(function() {
            toast.addClass('show');
        }, 10);
        
        let timeout = setTimeout(function() {
            removeToast(toast);
        }, 5000);
        
        toast.find('.toast-close').click(function() {
            clearTimeout(timeout);
            removeToast(toast);
        });
    }
    
    function removeToast(toast) {
        toast.removeClass('show');
        setTimeout(function() {
            toast.remove();
        }, 300);
    }
    
    // Utility functions
    function formatDate(date) {
        if (!date) return 'N/A';
        let d = new Date(date);
        if (isNaN(d.getTime())) return 'N/A';
        return d.getDate().toString().padStart(2, '0') + '/' + 
               (d.getMonth() + 1).toString().padStart(2, '0') + '/' + 
               d.getFullYear();
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('fr-FR').format(number);
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        let div = document.createElement('div');
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

.bg-danger-gradient {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.bg-warning-gradient {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.filter-bar-modern {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 32px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.filter-group {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-item {
    flex: 1;
    min-width: 160px;
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.filter-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-select, .filter-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s;
    background: white;
}

.filter-select:focus, .filter-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}

.btn-filter-apply, .btn-filter-reset, .btn-filter-export {
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    white-space: nowrap;
}

.btn-filter-apply {
    background: #6366f1;
    color: white;
}

.btn-filter-apply:hover {
    background: #4f46e5;
    transform: translateY(-1px);
}

.btn-filter-reset {
    background: #f1f5f9;
    color: #64748b;
}

.btn-filter-reset:hover {
    background: #e2e8f0;
}

.btn-filter-export {
    background: #10b981;
    color: white;
}

.btn-filter-export:hover {
    background: #059669;
}

.table-container-modern {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.table-responsive-modern {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead {
    background: #f8fafc;
}

.modern-table th {
    padding: 16px 20px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
}

.modern-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    color: #1e293b;
}

.modern-table tr:hover {
    background: #f8fafc;
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

.badge-active {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #d1fae5;
    color: #059669;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-expired {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #fee2e2;
    color: #dc2626;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-cancelled {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #fef3c7;
    color: #d97706;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-pending {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #e2e8f0;
    color: #64748b;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-payment-paid {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #d1fae5;
    color: #059669;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-payment-unpaid {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #fee2e2;
    color: #dc2626;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-payment-partial {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #fef3c7;
    color: #d97706;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-payment-refunded {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #e2e8f0;
    color: #64748b;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.edit-btn {
    background: #e0e7ff;
    color: #6366f1;
}

.edit-btn:hover {
    background: #c7d2fe;
    transform: translateY(-2px);
}

.delete-btn {
    background: #fee2e2;
    color: #dc2626;
}

.delete-btn:hover {
    background: #fecaca;
    transform: translateY(-2px);
}

.cancel-btn {
    background: #fef3c7;
    color: #d97706;
}

.cancel-btn:hover {
    background: #fde68a;
    transform: translateY(-2px);
}

.renew-btn {
    background: #d1fae5;
    color: #059669;
}

.renew-btn:hover {
    background: #a7f3d0;
    transform: translateY(-2px);
}

.history-btn {
    background: #e2e8f0;
    color: #64748b;
}

.history-btn:hover {
    background: #cbd5e1;
    transform: translateY(-2px);
}

.loading-spinner {
    text-align: center;
    padding: 60px;
}

.spinner {
    width: 48px;
    height: 48px;
    border: 3px solid #e2e8f0;
    border-top-color: #6366f1;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
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

.pagination-modern {
    padding: 20px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: center;
}

.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    border-radius: 14px;
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
    padding: 10px 20px;
    background: #f1f5f9;
    color: #64748b;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 500;
}

.btn-danger-modern {
    padding: 10px 20px;
    background: #dc2626;
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 500;
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
    background:white;
    padding: 24px;
}

.modal-footer-modern {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background:white;
}

.delete-icon, .cancel-icon {
    font-size: 56px;
    text-align: center;
    margin-bottom: 20px;
}

.delete-icon {
    color: #dc2626;
}

.cancel-icon {
    color: #d97706;
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
    
    .filter-group {
        flex-direction: column;
    }
    
    .filter-item {
        width: 100%;
    }
    
    .filter-actions {
        flex-wrap: wrap;
    }
    
    .page-header-modern {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .action-buttons {
        flex-wrap: wrap;
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