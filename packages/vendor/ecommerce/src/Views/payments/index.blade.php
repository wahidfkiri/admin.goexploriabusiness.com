@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-credit-card"></i></span>
                Gestion des Paiements
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <div class="btn-group me-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" id="exportPdf"><i class="fas fa-file-pdf me-2 text-danger"></i>PDF</a></li>
                        <li><a class="dropdown-item" href="#" id="exportExcel"><i class="fas fa-file-excel me-2 text-success"></i>Excel</a></li>
                        <li><a class="dropdown-item" href="#" id="exportCsv"><i class="fas fa-file-csv me-2 text-info"></i>CSV</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Filter Section (Initially Hidden) -->
        <div class="filter-section-modern" id="filterSection" style="display: none;">
            <div class="filter-header-modern">
                <h3 class="filter-title-modern">Filtres avancés</h3>
                <div class="filter-actions-modern">
                    <button class="btn btn-sm btn-outline-secondary" id="clearFiltersBtn">
                        <i class="fas fa-times me-1"></i>Effacer
                    </button>
                    <button class="btn btn-sm btn-primary" id="applyFiltersBtn">
                        <i class="fas fa-check me-1"></i>Appliquer
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="complete">Complété</option>
                        <option value="echoue">Échoué</option>
                        <option value="rembourse">Remboursé</option>
                        <option value="partiel">Partiel</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Méthode de paiement</label>
                    <select class="form-select-modern" id="filterMethod">
                        <option value="">Toutes les méthodes</option>
                        <option value="carte">Carte bancaire</option>
                        <option value="virement">Virement</option>
                        <option value="cheque">Chèque</option>
                        <option value="especes">Espèces</option>
                        <option value="paypal">PayPal</option>
                        <option value="stripe">Stripe</option>
                        <option value="prelevement">Prélèvement</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Date début</label>
                    <input type="date" class="form-control-modern" id="filterDateFrom">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Date fin</label>
                    <input type="date" class="form-control-modern" id="filterDateTo">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Montant min</label>
                    <input type="number" step="0.01" class="form-control-modern" id="filterAmountMin" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Montant max</label>
                    <input type="number" step="0.01" class="form-control-modern" id="filterAmountMax" placeholder="10000">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="payment_date">Date de paiement</option>
                        <option value="amount">Montant</option>
                        <option value="created_at">Date de création</option>
                        <option value="status">Statut</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Ordre</label>
                    <select class="form-select-modern" id="filterSortOrder">
                        <option value="desc">Décroissant</option>
                        <option value="asc">Croissant</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalPayments">0</div>
                        <div class="stats-label-modern">Total Paiements</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalAmount">0 €</div>
                        <div class="stats-label-modern">Montant total</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="completedPayments">0</div>
                        <div class="stats-label-modern">Paiements complétés</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="pendingPayments">0</div>
                        <div class="stats-label-modern">En attente</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="averageAmount">0 €</div>
                        <div class="stats-label-modern">Moyenne</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Paiements</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un paiement..." id="searchInput">
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Loading Spinner -->
                <div class="spinner-container" id="loadingSpinner">
                    <div class="spinner-border text-primary spinner" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                
                <!-- Table Container -->
                <div class="table-container-modern" id="tableContainer" style="display: none;">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Statut</th>
                                <th>Facture</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="paymentsTableBody">
                            <!-- Payments will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun paiement trouvé</h3>
                    <p class="empty-text-modern">Les paiements apparaîtront ici lorsqu'ils seront créés.</p>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container-modern" id="paginationContainer" style="display: none;">
                <div class="pagination-info-modern" id="paginationInfo">
                    <!-- Pagination info will be loaded here -->
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="modern-pagination" id="pagination">
                        <!-- Pagination will be loaded here -->
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Quick Stats Cards -->
        <div class="quick-stats-grid" id="quickStats">
            <!-- Will be populated by AJAX -->
        </div>
        
        <!-- Floating Action Button -->
        <div class="fab-modern" id="helpFab" data-bs-toggle="tooltip" title="Raccourcis">
            <i class="fas fa-keyboard"></i>
        </div>
    </main>

    <!-- PAYMENT DETAILS MODAL -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails du paiement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="paymentDetailsContent">
                    <!-- Payment details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Configuration
            let currentPage = 1;
            let currentFilters = {};
            let allPayments = [];

            // Initialize
            loadPayments();
            loadStatistics();
            setupEventListeners();

            // Load payments
            function loadPayments(page = 1, filters = {}) {
                showLoading();
                
                const searchTerm = document.getElementById('searchInput')?.value || '';
                
                $.ajax({
                    url: '{{ route("payments.index") }}',
                    type: 'GET',
                    data: {
                        page: page,
                        search: searchTerm,
                        ...filters,
                        ajax: true
                    },
                    success: function(response) {
                        if (response.success) {
                            allPayments = response.data || [];
                            renderPayments(allPayments);
                            renderPagination(response);
                            hideLoading();
                        } else {
                            showError('Erreur lors du chargement des paiements');
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showError('Erreur de connexion au serveur');
                        console.error('Error:', xhr.responseText);
                    }
                });
            }

            // Load statistics
            function loadStatistics() {
                $.ajax({
                    url: '{{ route("payments.statistics") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const stats = response.data;
                            document.getElementById('totalPayments').textContent = stats.total || 0;
                            document.getElementById('totalAmount').textContent = formatCurrency(stats.total_amount || 0);
                            document.getElementById('completedPayments').textContent = stats.completed || 0;
                            document.getElementById('pendingPayments').textContent = stats.pending || 0;
                            document.getElementById('averageAmount').textContent = formatCurrency(stats.average || 0);
                            
                            updateQuickStats(stats);
                        } else {
                            console.error('Error loading statistics:', response.message);
                            
                            // Default values
                            document.getElementById('totalPayments').textContent = '0';
                            document.getElementById('totalAmount').textContent = '0 €';
                            document.getElementById('completedPayments').textContent = '0';
                            document.getElementById('pendingPayments').textContent = '0';
                            document.getElementById('averageAmount').textContent = '0 €';
                        }
                    },
                    error: function(xhr) {
                        console.error('Statistics error:', xhr);
                        
                        // Default values
                        document.getElementById('totalPayments').textContent = '0';
                        document.getElementById('totalAmount').textContent = '0 €';
                        document.getElementById('completedPayments').textContent = '0';
                        document.getElementById('pendingPayments').textContent = '0';
                        document.getElementById('averageAmount').textContent = '0 €';
                    }
                });
            }

            // Render payments
            function renderPayments(payments) {
                const tbody = document.getElementById('paymentsTableBody');
                tbody.innerHTML = '';
                
                if (!payments || !Array.isArray(payments) || payments.length === 0) {
                    document.getElementById('emptyState').style.display = 'block';
                    document.getElementById('tableContainer').style.display = 'none';
                    document.getElementById('paginationContainer').style.display = 'none';
                    return;
                }
                
                payments.forEach((payment, index) => {
                    const row = document.createElement('tr');
                    row.id = `payment-row-${payment.id}`;
                    row.style.animationDelay = `${index * 0.05}s`;
                    
                    const statusColors = {
                        'en_attente': 'warning',
                        'complete': 'success',
                        'echoue': 'danger',
                        'rembourse': 'info',
                        'partiel': 'secondary'
                    };
                    
                    const statusIcons = {
                        'en_attente': 'fa-clock',
                        'complete': 'fa-check-circle',
                        'echoue': 'fa-times-circle',
                        'rembourse': 'fa-undo',
                        'partiel': 'fa-circle'
                    };
                    
                    const statusLabels = {
                        'en_attente': 'En attente',
                        'complete': 'Complété',
                        'echoue': 'Échoué',
                        'rembourse': 'Remboursé',
                        'partiel': 'Partiel'
                    };
                    
                    const methodIcons = {
                        'carte': 'fa-credit-card',
                        'virement': 'fa-exchange-alt',
                        'cheque': 'fa-money-check',
                        'especes': 'fa-money-bill',
                        'paypal': 'fa-paypal',
                        'stripe': 'fa-stripe',
                        'prelevement': 'fa-hand-holding-usd'
                    };
                    
                    row.innerHTML = `
                        <td>
                            <div class="payment-reference" onclick="viewPayment(${payment.id})" style="cursor: pointer;">
                                <strong>${payment.payment_reference || payment.payment_number || 'N/A'}</strong>
                                <small class="text-muted d-block">ID: ${payment.transaction_id || 'N/A'}</small>
                            </div>
                        </td>
                        <td>
                            <div class="client-info">
                                <div class="client-name">${payment.client?.name || payment.client?.nom || 'Client inconnu'}</div>
                                <small class="text-muted">
                                    <i class="fas fa-envelope me-1"></i>${payment.client?.email || 'Email non renseigné'}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="payment-date">${formatDate(payment.payment_date)}</div>
                                <small class="text-muted">${payment.payment_date ? new Date(payment.payment_date).toLocaleTimeString('fr-FR') : ''}</small>
                            </div>
                        </td>
                        <td>
                            <div class="amount-info">
                                <div class="payment-amount">${formatCurrency(payment.amount)}</div>
                                <small class="text-muted">${payment.invoice?.invoice_number || 'Sans facture'}</small>
                            </div>
                        </td>
                        <td>
                            <div class="method-info">
                                <i class="fas ${methodIcons[payment.method] || 'fa-credit-card'} me-2"></i>
                                ${getMethodLabel(payment.method)}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-${statusColors[payment.status]}">
                                <i class="fas ${statusIcons[payment.status]} me-1"></i>
                                ${statusLabels[payment.status]}
                            </span>
                        </td>
                        <td>
                            ${payment.invoice ? `
                                <a href="/invoices/${payment.invoice_id}" class="invoice-link">
                                    <i class="fas fa-file-invoice me-1"></i>
                                    ${payment.invoice.invoice_number}
                                </a>
                            ` : '<span class="text-muted">-</span>'}
                        </td>
                        <td>
                            <div class="payment-actions-modern">
                                <button class="action-btn-modern view-btn-modern" title="Voir détails" 
                                        onclick="viewPayment(${payment.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn-modern edit-btn-modern" title="Modifier statut" 
                                        onclick="editPayment(${payment.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="{{ route('payments.receipt', '') }}/${payment.id}" 
                                   class="action-btn-modern print-btn-modern" title="Télécharger reçu">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    `;
                    
                    tbody.appendChild(row);
                });
                
                document.getElementById('emptyState').style.display = 'none';
                document.getElementById('tableContainer').style.display = 'block';
                document.getElementById('paginationContainer').style.display = 'flex';
            }

            // Helper functions
            function formatCurrency(amount) {
                return new Intl.NumberFormat('fr-FR', { 
                    style: 'currency', 
                    currency: 'EUR' 
                }).format(amount || 0);
            }

            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('fr-FR');
            }

            function getMethodLabel(method) {
                const labels = {
                    'carte': 'Carte bancaire',
                    'virement': 'Virement',
                    'cheque': 'Chèque',
                    'especes': 'Espèces',
                    'paypal': 'PayPal',
                    'stripe': 'Stripe',
                    'prelevement': 'Prélèvement'
                };
                return labels[method] || method;
            }

            // View payment details
            window.viewPayment = function(paymentId) {
                const payment = allPayments.find(p => p.id === paymentId);
                
                if (!payment) {
                    showAlert('danger', 'Paiement non trouvé');
                    return;
                }
                
                const modalContent = document.getElementById('paymentDetailsContent');
                const statusColors = {
                    'en_attente': 'warning',
                    'complete': 'success',
                    'echoue': 'danger',
                    'rembourse': 'info',
                    'partiel': 'secondary'
                };
                
                modalContent.innerHTML = `
                    <div class="payment-details-view">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="detail-title">Informations générales</h6>
                                <div class="detail-group">
                                    <div class="detail-item">
                                        <span class="detail-label">Référence:</span>
                                        <span class="detail-value">${payment.payment_reference || 'N/A'}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Date:</span>
                                        <span class="detail-value">${formatDate(payment.payment_date)}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Montant:</span>
                                        <span class="detail-value payment-amount">${formatCurrency(payment.amount)}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Méthode:</span>
                                        <span class="detail-value">${getMethodLabel(payment.method)}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Statut:</span>
                                        <span class="badge bg-${statusColors[payment.status]}">${payment.status}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="detail-title">Client</h6>
                                <div class="detail-group">
                                    <div class="detail-item">
                                        <span class="detail-label">Nom:</span>
                                        <span class="detail-value">${payment.client?.name || payment.client?.nom || 'N/A'}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Email:</span>
                                        <span class="detail-value">${payment.client?.email || 'N/A'}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Téléphone:</span>
                                        <span class="detail-value">${payment.client?.telephone || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mt-3">
                                <h6 class="detail-title">Détails de transaction</h6>
                                <div class="detail-group">
                                    <div class="detail-item">
                                        <span class="detail-label">Transaction ID:</span>
                                        <span class="detail-value">${payment.transaction_id || 'N/A'}</span>
                                    </div>
                                    ${payment.check_number ? `
                                    <div class="detail-item">
                                        <span class="detail-label">Numéro chèque:</span>
                                        <span class="detail-value">${payment.check_number}</span>
                                    </div>
                                    ` : ''}
                                    ${payment.bank_name ? `
                                    <div class="detail-item">
                                        <span class="detail-label">Banque:</span>
                                        <span class="detail-value">${payment.bank_name}</span>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            
                            ${payment.notes ? `
                            <div class="col-md-12 mt-3">
                                <h6 class="detail-title">Notes</h6>
                                <div class="detail-notes">
                                    ${payment.notes}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                
                const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
                modal.show();
            };

            // Edit payment status
            window.editPayment = function(paymentId) {
                // Rediriger vers la page d'édition ou ouvrir un modal de modification
                window.location.href = `/payments/${paymentId}/edit`;
            };

            // Render pagination
            function renderPagination(response) {
                const pagination = document.getElementById('pagination');
                const paginationInfo = document.getElementById('paginationInfo');
                
                const start = (response.current_page - 1) * response.per_page + 1;
                const end = Math.min(response.current_page * response.per_page, response.total);
                paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} paiements`;
                
                let paginationHtml = '';
                
                // Previous button
                if (response.prev_page_url) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link-modern" href="#" onclick="changePage(${response.current_page - 1})">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item disabled">
                            <span class="page-link-modern"><i class="fas fa-chevron-left"></i></span>
                        </li>
                    `;
                }
                
                // Page numbers
                const maxPages = 5;
                let startPage = Math.max(1, response.current_page - Math.floor(maxPages / 2));
                let endPage = Math.min(response.last_page, startPage + maxPages - 1);
                
                if (endPage - startPage + 1 < maxPages) {
                    startPage = Math.max(1, endPage - maxPages + 1);
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    if (i === response.current_page) {
                        paginationHtml += `
                            <li class="page-item active">
                                <span class="page-link-modern">${i}</span>
                            </li>
                        `;
                    } else {
                        paginationHtml += `
                            <li class="page-item">
                                <a class="page-link-modern" href="#" onclick="changePage(${i})">${i}</a>
                            </li>
                        `;
                    }
                }
                
                // Next button
                if (response.next_page_url) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link-modern" href="#" onclick="changePage(${response.current_page + 1})">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item disabled">
                            <span class="page-link-modern"><i class="fas fa-chevron-right"></i></span>
                        </li>
                    `;
                }
                
                pagination.innerHTML = paginationHtml;
            }

            // Change page
            window.changePage = function(page) {
                currentPage = page;
                loadPayments(page, currentFilters);
            };

            // Update quick stats
            function updateQuickStats(stats) {
                const quickStatsContainer = document.getElementById('quickStats');
                
                let methodsHtml = '';
                if (stats.by_method && stats.by_method.length > 0) {
                    stats.by_method.forEach(item => {
                        methodsHtml += `
                            <div class="quick-stat-item">
                                <span class="quick-stat-label">${getMethodLabel(item.method)}</span>
                                <span class="quick-stat-value">${item.total} (${formatCurrency(item.amount)})</span>
                            </div>
                        `;
                    });
                }
                
                let clientsHtml = '';
                if (stats.top_clients && stats.top_clients.length > 0) {
                    stats.top_clients.forEach(item => {
                        clientsHtml += `
                            <div class="quick-stat-item">
                                <span class="quick-stat-label">${item.client_name}</span>
                                <span class="quick-stat-value">${formatCurrency(item.total)}</span>
                            </div>
                        `;
                    });
                }
                
                quickStatsContainer.innerHTML = `
                    <div class="quick-stat-card">
                        <h4 class="quick-stat-title">
                            <i class="fas fa-chart-pie me-2"></i>Répartition par méthode
                        </h4>
                        <div class="quick-stat-content">
                            ${methodsHtml || '<p class="text-muted">Aucune donnée</p>'}
                        </div>
                    </div>
                    
                    <div class="quick-stat-card">
                        <h4 class="quick-stat-title">
                            <i class="fas fa-chart-line me-2"></i>Top clients
                        </h4>
                        <div class="quick-stat-content">
                            ${clientsHtml || '<p class="text-muted">Aucune donnée</p>'}
                        </div>
                    </div>
                    
                    <div class="quick-stat-card">
                        <h4 class="quick-stat-title">
                            <i class="fas fa-calendar me-2"></i>Répartition mensuelle
                        </h4>
                        <div class="quick-stat-content" id="monthlyChart">
                            <canvas id="paymentsChart" style="max-height: 200px;"></canvas>
                        </div>
                    </div>
                `;
                
                // Initialiser le graphique si Chart.js est disponible
                if (typeof Chart !== 'undefined' && stats.monthly) {
                    const ctx = document.getElementById('paymentsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: stats.monthly.map(item => item.month),
                            datasets: [{
                                label: 'Montant des paiements',
                                data: stats.monthly.map(item => item.amount),
                                borderColor: '#45b7d1',
                                backgroundColor: 'rgba(69, 183, 209, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            }

            // Show loading
            function showLoading() {
                document.getElementById('loadingSpinner').style.display = 'flex';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('emptyState').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
            }

            // Hide loading
            function hideLoading() {
                document.getElementById('loadingSpinner').style.display = 'none';
            }

            // Show alert
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
                }, 5000);
            }

            // Show error
            function showError(message) {
                showAlert('danger', message);
            }

            // Setup event listeners
            function setupEventListeners() {
                // Search input with debounce
                const searchInput = document.getElementById('searchInput');
                let searchTimeout;
                
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            loadPayments(1, currentFilters);
                        }, 500);
                    });
                }
                
                // Toggle filter section
                const toggleFilterBtn = document.getElementById('toggleFilterBtn');
                const filterSection = document.getElementById('filterSection');
                
                if (toggleFilterBtn && filterSection) {
                    toggleFilterBtn.addEventListener('click', () => {
                        const isVisible = filterSection.style.display === 'block';
                        filterSection.style.display = isVisible ? 'none' : 'block';
                        toggleFilterBtn.innerHTML = isVisible 
                            ? '<i class="fas fa-sliders-h me-2"></i>Filtres'
                            : '<i class="fas fa-times me-2"></i>Masquer les filtres';
                    });
                }
                
                // Apply filters
                const applyFiltersBtn = document.getElementById('applyFiltersBtn');
                if (applyFiltersBtn) {
                    applyFiltersBtn.addEventListener('click', () => {
                        currentFilters = {
                            status: document.getElementById('filterStatus').value,
                            method: document.getElementById('filterMethod').value,
                            date_from: document.getElementById('filterDateFrom').value,
                            date_to: document.getElementById('filterDateTo').value,
                            amount_min: document.getElementById('filterAmountMin').value,
                            amount_max: document.getElementById('filterAmountMax').value,
                            sort_by: document.getElementById('filterSortBy').value,
                            sort_order: document.getElementById('filterSortOrder').value,
                        };
                        loadPayments(1, currentFilters);
                    });
                }
                
                // Clear filters
                const clearFiltersBtn = document.getElementById('clearFiltersBtn');
                if (clearFiltersBtn) {
                    clearFiltersBtn.addEventListener('click', () => {
                        document.getElementById('filterStatus').value = '';
                        document.getElementById('filterMethod').value = '';
                        document.getElementById('filterDateFrom').value = '';
                        document.getElementById('filterDateTo').value = '';
                        document.getElementById('filterAmountMin').value = '';
                        document.getElementById('filterAmountMax').value = '';
                        document.getElementById('filterSortBy').value = 'payment_date';
                        document.getElementById('filterSortOrder').value = 'desc';
                        currentFilters = {};
                        loadPayments(1);
                    });
                }
                
                // Export buttons
                const exportPdf = document.getElementById('exportPdf');
                if (exportPdf) {
                    exportPdf.addEventListener('click', (e) => {
                        e.preventDefault();
                        exportData('pdf');
                    });
                }
                
                const exportExcel = document.getElementById('exportExcel');
                if (exportExcel) {
                    exportExcel.addEventListener('click', (e) => {
                        e.preventDefault();
                        exportData('excel');
                    });
                }
                
                const exportCsv = document.getElementById('exportCsv');
                if (exportCsv) {
                    exportCsv.addEventListener('click', (e) => {
                        e.preventDefault();
                        exportData('csv');
                    });
                }
            }

            // Export data
            function exportData(format) {
                const filters = { ...currentFilters };
                if (document.getElementById('searchInput').value) {
                    filters.search = document.getElementById('searchInput').value;
                }
                
                const queryString = Object.keys(filters)
                    .filter(key => filters[key])
                    .map(key => `${key}=${encodeURIComponent(filters[key])}`)
                    .join('&');
                
                window.location.href = `/payments/export/${format}?${queryString}`;
            }

            // Floating action button menu
            const helpFab = document.getElementById('helpFab');
            if (helpFab) {
                helpFab.addEventListener('click', function() {
                    showAlert('info', 'Raccourcis: Ctrl+F pour rechercher, Ctrl+E pour exporter');
                });
            }
        });
    </script>

    <style>
        /* Payment Index Specific Styles */
        .payment-reference {
            font-weight: 500;
            color: var(--text-color);
            cursor: pointer;
            transition: color 0.2s;
        }

        .payment-reference:hover {
            color: var(--primary-color);
        }

        .client-info {
            display: flex;
            flex-direction: column;
        }

        .client-name {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .date-info, .amount-info {
            display: flex;
            flex-direction: column;
        }

        .payment-date {
            font-weight: 500;
            color: #333;
        }

        .payment-amount {
            font-weight: 600;
            color: #06b48a;
        }

        .method-info {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .method-info i {
            width: 20px;
            color: #666;
        }

        .invoice-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .invoice-link:hover {
            text-decoration: underline;
        }

        .payment-actions-modern {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-btn-modern {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .view-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .view-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
            color: white;
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
            color: white;
        }

        .print-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: white;
        }

        .print-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #f39c12);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 209, 102, 0.3);
            color: white;
        }

        /* Payment Details Modal */
        .payment-details-view {
            padding: 10px;
        }

        .detail-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #f0f0f0;
        }

        .detail-group {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eaeaea;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: #666;
        }

        .detail-value {
            font-weight: 600;
            color: #333;
        }

        .detail-notes {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            white-space: pre-wrap;
        }

        /* Quick Stats Grid */
        .quick-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .quick-stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .quick-stat-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .quick-stat-content {
            max-height: 250px;
            overflow-y: auto;
        }

        .quick-stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .quick-stat-item:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .quick-stat-label {
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }

        .quick-stat-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .quick-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .payment-actions-modern {
                flex-wrap: wrap;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) {
            .quick-stats-grid {
                grid-template-columns: 1fr;
            }
            
            .method-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection