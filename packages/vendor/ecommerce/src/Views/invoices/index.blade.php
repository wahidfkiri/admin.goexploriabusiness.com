@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-file-invoice"></i></span>
                Gestion des Factures
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
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Facture
                </a>
            </div>
        </div>
        
        <!-- Filter Section -->
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
                        <option value="brouillon">Brouillon</option>
                        <option value="envoyee">Envoyée</option>
                        <option value="en_attente">En attente</option>
                        <option value="payee">Payée</option>
                        <option value="partiellement_payee">Partiellement payée</option>
                        <option value="en_retard">En retard</option>
                        <option value="annulee">Annulée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Client</label>
                    <select class="form-select-modern" id="filterClient">
                        <option value="">Tous les clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->nom_complet }}</option>
                        @endforeach
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
                        <option value="invoice_date">Date de facture</option>
                        <option value="due_date">Date d'échéance</option>
                        <option value="total">Montant</option>
                        <option value="created_at">Date de création</option>
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
                        <div class="stats-value-modern" id="totalInvoices">0</div>
                        <div class="stats-label-modern">Total Factures</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-file-invoice"></i>
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
                        <div class="stats-value-modern" id="paidAmount">0 €</div>
                        <div class="stats-label-modern">Montant payé</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="pendingAmount">0 €</div>
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
                        <div class="stats-value-modern" id="overdueCount">0</div>
                        <div class="stats-label-modern">En retard</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Factures</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une facture..." id="searchInput">
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
                                <th>N° Facture</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Échéance</th>
                                <th>Montant</th>
                                <th>Payé</th>
                                <th>Reste</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="invoicesTableBody">
                            <!-- Invoices will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune facture trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première facture.</p>
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Créer une facture
                    </a>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container-modern" id="paginationContainer" style="display: none;">
                <div class="pagination-info-modern" id="paginationInfo"></div>
                <nav aria-label="Page navigation">
                    <ul class="modern-pagination" id="pagination"></ul>
                </nav>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="charts-grid">
            <div class="chart-card">
                <h4 class="chart-title">Factures par statut</h4>
                <canvas id="statusChart" style="max-height: 300px;"></canvas>
            </div>
            <div class="chart-card">
                <h4 class="chart-title">Évolution mensuelle</h4>
                <canvas id="monthlyChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </main>

    <!-- Floating Action Button -->
    <div class="fab-modern" id="helpFab" data-bs-toggle="tooltip" title="Raccourcis">
        <i class="fas fa-keyboard"></i>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            let allInvoices = [];

            // Initialize
            loadInvoices();
            loadStatistics();
            setupEventListeners();

            // Load invoices
            function loadInvoices(page = 1, filters = {}) {
                showLoading();
                
                const searchTerm = document.getElementById('searchInput')?.value || '';
                
                $.ajax({
                    url: '{{ route("invoices.index") }}',
                    type: 'GET',
                    data: {
                        page: page,
                        search: searchTerm,
                        ...filters,
                        ajax: true
                    },
                    success: function(response) {
                        if (response.success) {
                            allInvoices = response.data || [];
                            renderInvoices(allInvoices);
                            renderPagination(response);
                            hideLoading();
                        } else {
                            showError('Erreur lors du chargement des factures');
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
                    url: '{{ route("invoices.statistics") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const stats = response.data;
                            document.getElementById('totalInvoices').textContent = stats.total || 0;
                            document.getElementById('totalAmount').textContent = formatCurrency(stats.total_amount || 0);
                            document.getElementById('paidAmount').textContent = formatCurrency(stats.paid_amount || 0);
                            document.getElementById('pendingAmount').textContent = formatCurrency(stats.pending_amount || 0);
                            document.getElementById('overdueCount').textContent = stats.overdue_count || 0;
                            
                            updateCharts(stats);
                        }
                    },
                    error: function(xhr) {
                        console.error('Statistics error:', xhr);
                        // Default values
                        document.getElementById('totalInvoices').textContent = '0';
                        document.getElementById('totalAmount').textContent = '0 €';
                        document.getElementById('paidAmount').textContent = '0 €';
                        document.getElementById('pendingAmount').textContent = '0 €';
                        document.getElementById('overdueCount').textContent = '0';
                    }
                });
            }

            // Render invoices
            function renderInvoices(invoices) {
                const tbody = document.getElementById('invoicesTableBody');
                tbody.innerHTML = '';
                
                if (!invoices || invoices.length === 0) {
                    document.getElementById('emptyState').style.display = 'block';
                    document.getElementById('tableContainer').style.display = 'none';
                    document.getElementById('paginationContainer').style.display = 'none';
                    return;
                }
                
                invoices.forEach((invoice, index) => {
                    const row = document.createElement('tr');
                    row.id = `invoice-row-${invoice.id}`;
                    row.style.animationDelay = `${index * 0.05}s`;
                    
                    const statusColors = {
                        'brouillon': 'secondary',
                        'envoyee': 'info',
                        'en_attente': 'warning',
                        'payee': 'success',
                        'partiellement_payee': 'primary',
                        'en_retard': 'danger',
                        'annulee': 'dark'
                    };
                    
                    const statusIcons = {
                        'brouillon': 'fa-pen',
                        'envoyee': 'fa-paper-plane',
                        'en_attente': 'fa-clock',
                        'payee': 'fa-check-circle',
                        'partiellement_payee': 'fa-adjust',
                        'en_retard': 'fa-exclamation-triangle',
                        'annulee': 'fa-times-circle'
                    };
                    
                    const statusLabels = {
                        'brouillon': 'Brouillon',
                        'envoyee': 'Envoyée',
                        'en_attente': 'En attente',
                        'payee': 'Payée',
                        'partiellement_payee': 'Partielle',
                        'en_retard': 'En retard',
                        'annulee': 'Annulée'
                    };
                    
                    row.innerHTML = `
                        <td>
                            <div class="invoice-reference" onclick="viewInvoice(${invoice.id})" style="cursor: pointer;">
                                <strong>${invoice.invoice_number}</strong>
                                <small class="text-muted d-block">Créé: ${formatDate(invoice.created_at)}</small>
                            </div>
                        </td>
                        <td>
                            <div class="client-info">
                                <div class="client-name">${invoice.client_name}</div>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>${invoice.client_city || 'N/A'}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="invoice-date">${formatDate(invoice.invoice_date)}</div>
                            </div>
                        </td>
                        <td>
                            <div class="due-date ${invoice.is_overdue ? 'text-danger' : ''}">
                                ${formatDate(invoice.due_date)}
                                ${invoice.is_overdue ? '<i class="fas fa-exclamation-circle ms-1"></i>' : ''}
                            </div>
                        </td>
                        <td>
                            <div class="amount-info">
                                <div class="total-amount">${formatCurrency(invoice.total)}</div>
                            </div>
                        </td>
                        <td>
                            <div class="paid-amount">${formatCurrency(invoice.paid_amount || 0)}</div>
                        </td>
                        <td>
                            <div class="remaining-amount ${invoice.remaining_amount > 0 ? 'text-danger' : 'text-success'}">
                                ${formatCurrency(invoice.remaining_amount || 0)}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-${statusColors[invoice.status]}">
                                <i class="fas ${statusIcons[invoice.status]} me-1"></i>
                                ${statusLabels[invoice.status]}
                            </span>
                        </td>
                        <td>
                            <div class="invoice-actions-modern">
                                <button class="action-btn-modern view-btn-modern" title="Voir détails" 
                                        onclick="viewInvoice(${invoice.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if(!in_array($invoice->status ?? '', ['payee', 'annulee']))
                                <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                        onclick="editInvoice(${invoice.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif
                                <button class="action-btn-modern pdf-btn-modern" title="Télécharger PDF" 
                                        onclick="downloadPdf(${invoice.id})">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="action-btn-modern email-btn-modern" title="Envoyer par email" 
                                        onclick="sendEmail(${invoice.id})">
                                    <i class="fas fa-envelope"></i>
                                </button>
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

            // View invoice details
            window.viewInvoice = function(invoiceId) {
                window.location.href = `/invoices/${invoiceId}`;
            };

            // Edit invoice
            window.editInvoice = function(invoiceId) {
                window.location.href = `/invoices/${invoiceId}/edit`;
            };

            // Download PDF
            window.downloadPdf = function(invoiceId) {
                window.open(`/invoices/${invoiceId}/pdf`, '_blank');
            };

            // Send email
            window.sendEmail = function(invoiceId) {
                if (!confirm('Envoyer cette facture par email au client ?')) return;
                
                $.ajax({
                    url: `/invoices/${invoiceId}/send`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            loadInvoices(currentPage, currentFilters);
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        showAlert('danger', 'Erreur lors de l\'envoi');
                    }
                });
            };

            // Render pagination
            function renderPagination(response) {
                const pagination = document.getElementById('pagination');
                const paginationInfo = document.getElementById('paginationInfo');
                
                const start = (response.current_page - 1) * response.per_page + 1;
                const end = Math.min(response.current_page * response.per_page, response.total);
                paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} factures`;
                
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
                loadInvoices(page, currentFilters);
            };

            // Update charts
            function updateCharts(stats) {
                // Status chart
                const statusCtx = document.getElementById('statusChart').getContext('2d');
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: stats.by_status.map(item => {
                            const labels = {
                                'brouillon': 'Brouillon',
                                'envoyee': 'Envoyée',
                                'en_attente': 'En attente',
                                'payee': 'Payée',
                                'partiellement_payee': 'Partielle',
                                'en_retard': 'En retard',
                                'annulee': 'Annulée'
                            };
                            return labels[item.status] || item.status;
                        }),
                        datasets: [{
                            data: stats.by_status.map(item => item.total),
                            backgroundColor: [
                                '#6c757d', '#17a2b8', '#ffc107', '#28a745', '#007bff', '#dc3545', '#343a40'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Monthly chart
                const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: stats.by_month.map(item => item.month).reverse(),
                        datasets: [{
                            label: 'Montant (€)',
                            data: stats.by_month.map(item => item.amount).reverse(),
                            borderColor: '#45b7d1',
                            backgroundColor: 'rgba(69, 183, 209, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + ' €';
                                    }
                                }
                            }
                        }
                    }
                });
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
                            loadInvoices(1, currentFilters);
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
                            client_id: document.getElementById('filterClient').value,
                            date_from: document.getElementById('filterDateFrom').value,
                            date_to: document.getElementById('filterDateTo').value,
                            min_amount: document.getElementById('filterAmountMin').value,
                            max_amount: document.getElementById('filterAmountMax').value,
                            sort_by: document.getElementById('filterSortBy').value,
                            sort_order: document.getElementById('filterSortOrder').value,
                        };
                        loadInvoices(1, currentFilters);
                    });
                }
                
                // Clear filters
                const clearFiltersBtn = document.getElementById('clearFiltersBtn');
                if (clearFiltersBtn) {
                    clearFiltersBtn.addEventListener('click', () => {
                        document.getElementById('filterStatus').value = '';
                        document.getElementById('filterClient').value = '';
                        document.getElementById('filterDateFrom').value = '';
                        document.getElementById('filterDateTo').value = '';
                        document.getElementById('filterAmountMin').value = '';
                        document.getElementById('filterAmountMax').value = '';
                        document.getElementById('filterSortBy').value = 'invoice_date';
                        document.getElementById('filterSortOrder').value = 'desc';
                        currentFilters = {};
                        loadInvoices(1);
                    });
                }
            }
        });
    </script>

    <style>
        /* Invoice Index Specific Styles */
        .invoice-reference {
            font-weight: 500;
            color: var(--text-color);
            cursor: pointer;
            transition: color 0.2s;
        }

        .invoice-reference:hover {
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

        .invoice-date, .due-date {
            font-weight: 500;
            color: #333;
        }

        .due-date.text-danger {
            color: #ef476f !important;
            font-weight: 600;
        }

        .total-amount {
            font-weight: 600;
            color: #06b48a;
        }

        .paid-amount {
            font-weight: 500;
            color: #28a745;
        }

        .remaining-amount {
            font-weight: 600;
        }

        .remaining-amount.text-danger {
            color: #ef476f;
        }

        .remaining-amount.text-success {
            color: #06b48a;
        }

        .invoice-actions-modern {
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

        .pdf-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: white;
        }

        .pdf-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #f39c12);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 209, 102, 0.3);
            color: white;
        }

        .email-btn-modern {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
        }

        .email-btn-modern:hover {
            background: linear-gradient(135deg, #8e44ad, #7d3c9b);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(155, 89, 182, 0.3);
            color: white;
        }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .chart-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .invoice-actions-modern {
                flex-wrap: wrap;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection