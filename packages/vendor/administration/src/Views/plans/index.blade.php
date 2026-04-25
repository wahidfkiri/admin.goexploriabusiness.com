{{-- resources/views/admin/plans/index.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <!-- Header -->
    <div class="page-header-modern">
        <div class="page-header-left">
            <div class="page-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div>
                <h1 class="page-title-modern">Plans d'Abonnement</h1>
                <p class="page-subtitle">GÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rez les offres et tarifs pour vos ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissements</p>
            </div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('plans.create') }}" class="btn-primary-modern">
                <i class="fas fa-plus-circle me-2"></i>Nouveau Plan
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid-modern">
        <div class="stat-card">
            <div class="stat-icon bg-primary-gradient">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $totalPlans }}</h3>
                <p class="stat-label">Total des plans</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-success-gradient">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $activePlans }}</h3>
                <p class="stat-label">Plans actifs</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-warning-gradient">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $popularPlans }}</h3>
                <p class="stat-label">Plans populaires</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-info-gradient">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $activeSubscribers }}</h3>
                <p class="stat-label">AbonnÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©s actifs</p>
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
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">Cycle</label>
                <select id="filterCycle" class="filter-select">
                    <option value="">Tous</option>
                    <option value="monthly">Mensuel</option>
                    <option value="yearly">Annuel</option>
                    <option value="custom">PersonnalisÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©</option>
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">Recherche</label>
                <input type="text" id="filterSearch" class="filter-input" placeholder="Nom du plan...">
            </div>
            <div class="filter-item">
                <button id="applyFilters" class="btn-filter-apply">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <button id="resetFilters" class="btn-filter-reset">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Plans Grid -->
    <div class="plans-container">
        <div id="loadingSpinner" class="loading-spinner">
            <div class="spinner"></div>
            <p>Chargement des plans...</p>
        </div>
        
        <div id="plansGrid" class="plans-grid-modern" style="display: none;"></div>
        
        <div id="emptyState" class="empty-state-modern" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-crown"></i>
            </div>
            <h3>Aucun plan trouve</h3>
            <p>Commencez par creer votre premier plan d'abonnement</p>
            <a href="{{ route('plans.create') }}" class="btn-primary-modern">
                <i class="fas fa-plus-circle me-2"></i>Creer un plan
            </a>
        </div>
    </div>
</main>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    loadPlans();
    
    function loadPlans() {
        $('#loadingSpinner').show();
        $('#plansGrid').hide();
        $('#emptyState').hide();
        
        $.ajax({
            url: '{{ route("plans.index") }}',
            type: 'GET',
            data: {
                status: $('#filterStatus').val(),
                billing_cycle: $('#filterCycle').val(),
                search: $('#filterSearch').val()
            },
            success: function(response) {
                $('#loadingSpinner').hide();
                
                if (response.plans && response.plans.length > 0) {
                    renderPlans(response.plans);
                    $('#plansGrid').show();
                } else {
                    $('#emptyState').show();
                }
            },
            error: function() {
                $('#loadingSpinner').hide();
                $('#emptyState').show();
                showToast('error', 'Erreur lors du chargement des plans');
            }
        });
    }
    
    function renderPlans(plans) {
        let html = '';
        plans.forEach(plan => {
            const statusClass = plan.is_active ? 'status-active' : 'status-inactive';
            const statusText = plan.is_active ? 'Actif' : 'Inactif';
            const priceFormatted = new Intl.NumberFormat('fr-FR').format(plan.price);
            const cycleText = plan.billing_cycle === 'yearly' ? 'an' : (plan.billing_cycle === 'monthly' ? 'mois' : 'personnalisÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©');
            
            html += `
                <div class="plan-card-modern">
                    ${plan.is_popular ? '<div class="popular-badge"><i class="fas fa-star"></i> Populaire</div>' : ''}
                    <div class="plan-card-header">
                        <div>
                            <h3 class="plan-name">${escapeHtml(plan.name)}</h3>
                            <div class="plan-status ${statusClass}">${statusText}</div>
                        </div>
                        <div class="plan-price">
                            ${priceFormatted} <span class="currency">${plan.currency}</span>
                            <span class="period">/${cycleText}</span>
                        </div>
                    </div>
                    
                    <div class="plan-description">
                        ${plan.description || 'Aucune description'}
                    </div>
                    
                    <div class="plan-services">
                        <div class="services-title">
                            <i class="fas fa-list-check"></i> Services inclus
                        </div>
                        <div class="services-content">
                            ${plan.services || '<span class="text-muted">Aucun service spÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©cifiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©</span>'}
                        </div>
                    </div>
                    
                    <div class="plan-footer">
                        <div class="plan-stats">
                            <div class="stat-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>${plan.duration_days} jours</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>${plan.subscribers_count || 0} abonnÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©s</span>
                            </div>
                        </div>
                        <div class="plan-actions">
                            <a href="/admin/plans/${plan.id}/edit" class="btn-icon edit-btn" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="toggleStatus(${plan.id}, ${plan.is_active})" class="btn-icon status-btn" title="${plan.is_active ? 'DÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sactiver' : 'Activer'}">
                                <i class="fas ${plan.is_active ? 'fa-pause-circle' : 'fa-play-circle'}"></i>
                            </button>
                            <button onclick="deletePlanWithConfirm(${plan.id}, '${escapeHtml(plan.name)}')" class="btn-icon delete-btn" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        $('#plansGrid').html(html);
    }
    
    $('#applyFilters').click(() => loadPlans());
    $('#resetFilters').click(() => {
        $('#filterStatus').val('');
        $('#filterCycle').val('');
        $('#filterSearch').val('');
        loadPlans();
    });
    
    window.toggleStatus = function(id, currentStatus) {
        $.ajax({
            url: `/admin/plans/${id}/toggle-status`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    loadPlans();
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', 'Erreur lors du changement de statut');
            }
        });
    };
    
    window.deletePlanWithConfirm = function(id, name) {
        const confirmed = window.confirm(`Vous allez supprimer le plan "${name}". Cette action est irrÃƒÆ’Ã‚Â©versible. Continuer ?`);
        if (!confirmed) return;

        $.ajax({
            url: `/admin/plans/${id}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    loadPlans();
                } else {
                    showToast('error', response.message);
                }
            },
            error: function(xhr) {
                let message = 'Erreur lors de la suppression';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast('error', message);
            }
        });
    };
    
    // Toast notification system
    function showToast(type, message) {
        const icon = type === 'success' ? 'ÃƒÆ’Ã‚Â¢Ãƒâ€¦Ã¢â‚¬Å“ÃƒÂ¢Ã¢â€šÂ¬Ã…â€œ' : 'ÃƒÆ’Ã‚Â¢Ãƒâ€¦Ã¢â‚¬Å“ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â';
        const bgClass = type === 'success' ? 'toast-success' : 'toast-error';
        const title = type === 'success' ? 'SuccÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨s' : 'Erreur';
        
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
        
        // Animate in
        setTimeout(() => toast.addClass('show'), 10);
        
        // Auto remove after 5 seconds
        const timeout = setTimeout(() => {
            removeToast(toast);
        }, 5000);
        
        // Close button
        toast.find('.toast-close').click(() => {
            clearTimeout(timeout);
            removeToast(toast);
        });
    }
    
    function removeToast(toast) {
        toast.removeClass('show');
        setTimeout(() => toast.remove(), 300);
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
/* Dashboard Content */
.dashboard-content {
    padding: 24px 32px;
    background: #f8fafc;
    min-height: 100vh;
}

/* Page Header Modern */
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

/* Stats Grid Modern */
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

/* Gradients */
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

/* Filter Bar */
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
    min-width: 180px;
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

.btn-filter-apply, .btn-filter-reset {
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
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

/* Plans Grid Modern */
.plans-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
}

.plan-card-modern {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.plan-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.popular-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 1;
}

.plan-card-header {
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
}

.plan-name {
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.plan-status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.status-active {
    background: #d1fae5;
    color: #059669;
}

.status-inactive {
    background: #fee2e2;
    color: #dc2626;
}

.plan-price {
    font-size: 32px;
    font-weight: 700;
    color: #6366f1;
    margin-top: 16px;
}

.plan-price .currency {
    font-size: 18px;
    font-weight: 500;
}

.plan-price .period {
    font-size: 14px;
    font-weight: 400;
    color: #64748b;
}

.plan-description {
    padding: 20px 24px;
    color: #475569;
    line-height: 1.6;
    border-bottom: 1px solid #f1f5f9;
}

.plan-services {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
}

.services-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.services-content {
    color: #475569;
    font-size: 14px;
    line-height: 1.6;
}

.services-content ul {
    margin: 0;
    padding-left: 20px;
}

.services-content li {
    margin-bottom: 6px;
}

.plan-footer {
    padding: 20px 24px;
    background: #f8fafc;
}

.plan-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #64748b;
}

.stat-item i {
    color: #6366f1;
}

.plan-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.btn-icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.edit-btn {
    background: #e0e7ff;
    color: #6366f1;
}

.edit-btn:hover {
    background: #c7d2fe;
    transform: translateY(-2px);
}

.status-btn {
    background: #fef3c7;
    color: #d97706;
}

.status-btn:hover {
    background: #fde68a;
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

/* Loading Spinner */
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

/* Empty State */
.empty-state-modern {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 24px;
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

/* Buttons */
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

/* Modal */
.modal-content-modern {
    border-radius: 24px;
    border: none;
    background: #ffffff;
}

.modal-header-modern {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
}

.modal-body-modern {
    padding: 24px;
    background: #ffffff;
}

.modal-footer-modern {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #ffffff;
}

.delete-icon {
    font-size: 56px;
    color: #dc2626;
    margin-bottom: 20px;
}

/* Toast Container */
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
    position: relative;
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
    color: #475569;
}

/* Responsive */
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
    
    .plans-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .filter-group {
        flex-direction: column;
    }
    
    .filter-item {
        width: 100%;
    }
    
    .page-header-modern {
        flex-direction: column;
        gap: 16px;
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

