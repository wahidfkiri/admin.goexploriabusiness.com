@extends('editor::layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-users"></i></span>
                Gestion des Clients
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Client
                </button>
            </div>
        </div>
        
        <!-- Filter Section (Initially Hidden) -->
        <div class="filter-section-modern" id="filterSection" style="display: none;">
            <div class="filter-header-modern">
                <h3 class="filter-title-modern">Filtres</h3>
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
                    <label for="filterCountry" class="form-label-modern">Pays</label>
                    <select class="form-select-modern" id="filterCountry">
                        <option value="">Tous les pays</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterCity" class="form-label-modern">Ville</label>
                    <input type="text" class="form-control-modern" id="filterCity" placeholder="Rechercher par ville">
                </div>
                <div class="col-md-3">
                    <label for="filterDateFrom" class="form-label-modern">Date de début</label>
                    <input type="date" class="form-control-modern" id="filterDateFrom">
                </div>
                <div class="col-md-3">
                    <label for="filterDateTo" class="form-label-modern">Date de fin</label>
                    <input type="date" class="form-control-modern" id="filterDateTo">
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalCustomers">0</div>
                        <div class="stats-label-modern">Total Clients</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="customersWithWebsites">0</div>
                        <div class="stats-label-modern">Avec Sites Web</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="countriesCount">0</div>
                        <div class="stats-label-modern">Pays</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-flag"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="thisMonth">0</div>
                        <div class="stats-label-modern">Ajoutés ce mois</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Clients</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un client..." id="searchInput">
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
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Localisation</th>
                                <th>Sites Web</th>
                                <th>Créé le</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customersTableBody">
                            <!-- Customers will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun client trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier client pour votre plateforme.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un client
                    </button>
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
        
        <!-- Bulk Actions -->
        <div class="bulk-actions-modern" id="bulkActions" style="display: none;">
            <div class="bulk-actions-content">
                <span id="selectedCount">0</span> client(s) sélectionné(s)
                <div class="bulk-buttons">
                    <button class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="clearSelectionBtn">
                        <i class="fas fa-times me-1"></i>Effacer la sélection
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE CUSTOMER MODAL -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createCustomerModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau client
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createCustomerForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="customerName" class="form-label-modern">Nom du client *</label>
                                <input type="text" class="form-control-modern" id="customerName" name="name" placeholder="Ex: Jean Dupont" required>
                            </div>
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="customerEmail" class="form-label-modern">Email</label>
                                <input type="email" class="form-control-modern" id="customerEmail" name="email" placeholder="exemple@email.com">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4 d-grid">
                                <label for="customerPhone" class="form-label-modern">Téléphone</label>
                                <input type="tel" class="form-control-modern" id="customerPhone" name="phone" placeholder="+33 1 23 45 67 89">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="customerCountry" class="form-label-modern">Pays</label>
                                <select class="form-select-modern" id="customerCountry" name="country">
                                    <option value="">Sélectionnez un pays</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}">{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="customerCity" class="form-label-modern">Ville</label>
                                <input type="text" class="form-control-modern" id="customerCity" name="city" placeholder="Paris">
                            </div>
                        </div>
                        
                        <div class="mb-4 d-grid">
                            <label for="customerAddress" class="form-label-modern">Adresse</label>
                            <textarea class="form-control-modern" id="customerAddress" name="address" rows="2" placeholder="123 Rue de l'Exemple"></textarea>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">Informations de connexion</small>
                            </div>
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="customerEmail" class="form-label-modern">Login *</label>
                                <input type="email" class="form-control-modern" id="customerEmail" name="login" required>
                            </div>
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="customerPassword" class="form-label-modern">Mot de passe *</label>
                                <input type="password" class="form-control-modern" id="customerPassword" name="password" required>
                            </div>
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="customerPasswordConfirmation" class="form-label-modern">Confirmer le mot de passe *</label>
                                <input type="password" class="form-control-modern" id="customerPasswordConfirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitCustomerBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le client
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT CUSTOMER MODAL -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editCustomerModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier le client
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editCustomerForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editCustomerId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="editCustomerName" class="form-label-modern">Nom du client *</label>
                                <input type="text" class="form-control-modern" id="editCustomerName" name="name" required>
                            </div>
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="editCustomerEmail" class="form-label-modern">Email</label>
                                <input type="email" class="form-control-modern" id="editCustomerEmail" name="email">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4 d-grid">
                                <label for="editCustomerPhone" class="form-label-modern">Téléphone</label>
                                <input type="tel" class="form-control-modern" id="editCustomerPhone" name="phone">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="editCustomerCountry" class="form-label-modern">Pays</label>
                                <select class="form-select-modern" id="editCustomerCountry" name="country">
                                    <option value="">Sélectionnez un pays</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}">{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" id="editCustomerUserId" name="user_id">
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="editCustomerCity" class="form-label-modern">Ville</label>
                                <input type="text" class="form-control-modern" id="editCustomerCity" name="city">
                            </div>
                        </div>
                        
                        <div class="mb-4 d-grid">
                            <label for="editCustomerAddress" class="form-label-modern">Adresse</label>
                            <textarea class="form-control-modern" id="editCustomerAddress" name="address" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateCustomerBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade delete-confirm-modal" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 class="delete-title">Confirmer la suppression</h4>
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.</p>
                    
                    <div class="customer-to-delete" id="customerToDeleteInfo">
                        <!-- Customer info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Tous les sites web associés à ce client seront également supprimés.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelDeleteBtn">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allCustomers = [];
        let customerToDelete = null;
        let selectedCustomers = new Set();

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadCustomers();
            setupEventListeners();
        });

        // AJAX setup
        const setupAjax = () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        };

        // Load customers
        const loadCustomers = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("customers.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    console.log('Server response:', response);
                    
                    if (response.success) {
                        allCustomers = response.data || [];
                        renderCustomers(allCustomers);
                        renderPagination(response);
                        updateStats(allCustomers);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des clients');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showError('Erreur de connexion au serveur');
                    console.error('Error:', xhr.responseText);
                }
            });
        };

        // Render customers with modern design
        const renderCustomers = (customers) => {
            const tbody = document.getElementById('customersTableBody');
            tbody.innerHTML = '';
            
            if (!customers || !Array.isArray(customers) || customers.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                document.getElementById('bulkActions').style.display = 'none';
                return;
            }
            
            customers.forEach((customer, index) => {
                const row = document.createElement('tr');
                row.id = `customer-row-${customer.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                if (selectedCustomers.has(customer.id)) {
                    row.classList.add('selected-row');
                }
                
                // Format date
                const createdDate = new Date(customer.created_at);
                const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                
                // Websites count
                const websitesCount = customer.websites_count || 0;
                let websitesBadge = '';
                if (websitesCount > 0) {
                    websitesBadge = `<span class="badge bg-success">${websitesCount}</span>`;
                }
                
                row.innerHTML = `
                    <td class="customer-name-cell">
                        <div class="customer-name-modern">
                            <div class="customer-icon-modern">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="customer-name-text">${customer.name}</div>
                                <small class="text-muted">ID: ${customer.id}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="contact-info">
                            <div><i class="fas fa-phone me-1"></i> ${customer.phone || 'Non renseigné'}</div>
                            <div><i class="fas fa-envelope me-1"></i> ${customer.email || 'Non renseigné'}</div>
                        </div>
                    </td>
                    <td>
                        <div class="location-info">
                            ${customer.city ? `<div><i class="fas fa-city me-1"></i> ${customer.city}</div>` : ''}
                            ${customer.country ? `<div><i class="fas fa-flag me-1"></i> ${customer.country}</div>` : ''}
                        </div>
                    </td>
                    <td>
                        ${websitesBadge}
                    </td>
                    <td>
                        <div>${formattedDate}</div>
                        <small class="text-muted">${formatTimeAgo(createdDate)}</small>
                    </td>
                    <td>
                        <div class="customer-actions-modern">
                            <button class="action-btn-modern view-btn-modern" title="Voir détails" onclick="viewCustomer(${customer.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn-modern edit-btn-modern" title="Modifier" onclick="openEditModal(${customer.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" onclick="showDeleteConfirmation(${customer.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <input type="checkbox" class="customer-checkbox" onclick="toggleCustomerSelection(${customer.id})" ${selectedCustomers.has(customer.id) ? 'checked' : ''}>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
            document.getElementById('paginationContainer').style.display = 'flex';
            
            // Show/hide bulk actions
            if (selectedCustomers.size > 0) {
                document.getElementById('bulkActions').style.display = 'block';
                document.getElementById('selectedCount').textContent = selectedCustomers.size;
            } else {
                document.getElementById('bulkActions').style.display = 'none';
            }
        };

        // Toggle customer selection
        const toggleCustomerSelection = (customerId) => {
            if (selectedCustomers.has(customerId)) {
                selectedCustomers.delete(customerId);
            } else {
                selectedCustomers.add(customerId);
            }
            
            // Update UI
            const row = document.getElementById(`customer-row-${customerId}`);
            if (row) {
                row.classList.toggle('selected-row');
            }
            
            // Show/hide bulk actions
            if (selectedCustomers.size > 0) {
                document.getElementById('bulkActions').style.display = 'block';
                document.getElementById('selectedCount').textContent = selectedCustomers.size;
            } else {
                document.getElementById('bulkActions').style.display = 'none';
            }
        };

        // Clear all selections
        const clearSelections = () => {
            selectedCustomers.clear();
            document.querySelectorAll('.selected-row').forEach(row => {
                row.classList.remove('selected-row');
            });
            document.querySelectorAll('.customer-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('bulkActions').style.display = 'none';
        };

        // Bulk delete customers
        const bulkDeleteCustomers = () => {
            if (selectedCustomers.size === 0) {
                showAlert('warning', 'Veuillez sélectionner au moins un client');
                return;
            }
            
            if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedCustomers.size} client(s) ?`)) {
                return;
            }
            
            const customerIds = Array.from(selectedCustomers);
            
            $.ajax({
                url: '{{ route("customers.bulk.delete") }}',
                type: 'POST',
                data: {
                    customer_ids: customerIds
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message || 'Clients supprimés avec succès');
                        clearSelections();
                        loadCustomers(currentPage, currentFilters);
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr) {
                    showError('Erreur lors de la suppression des clients');
                }
            });
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (customerId) => {
            const customer = allCustomers.find(c => c.id === customerId);
            
            if (!customer) {
                showAlert('danger', 'Client non trouvé');
                return;
            }
            
            customerToDelete = customer;
            
            // Format date
            const createdDate = new Date(customer.created_at);
            const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            document.getElementById('customerToDeleteInfo').innerHTML = `
                <div class="customer-info">
                    <div class="customer-info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="customer-info-name">${customer.name}</div>
                        <div class="customer-info-email">${customer.email || 'Pas d\'email'}</div>
                    </div>
                </div>
                <div class="row small text-muted">
                    <div class="col-6">
                        <div><strong>ID:</strong> ${customer.id}</div>
                        <div><strong>Téléphone:</strong> ${customer.phone || 'Non renseigné'}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Créé le:</strong> ${formattedDate}</div>
                        <div><strong>Ville:</strong> ${customer.city || 'Non renseignée'}</div>
                    </div>
                </div>
            `;
            
            // Reset delete button state
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                </span>
            `;
            deleteBtn.disabled = false;
            
            // Show modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        };

        // Delete customer
        const deleteCustomer = () => {
            if (!customerToDelete) {
                showAlert('danger', 'Aucun client à supprimer');
                return;
            }
            
            const customerId = customerToDelete.id;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            
            // Show processing animation
            deleteBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Suppression...</span>
                </div>
                Suppression en cours...
            `;
            deleteBtn.disabled = true;
            
            // Send DELETE request
            $.ajax({
                url: `/customers/${customerId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    console.log('Delete response:', response);
                    
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove from allCustomers array
                        allCustomers = allCustomers.filter(c => c.id !== customerId);
                        
                        // Remove from selected customers
                        selectedCustomers.delete(customerId);
                        
                        // Update stats
                        updateStats(allCustomers);
                        
                        // Show success message
                        showAlert('success', response.message || 'Client supprimé avec succès !');
                        
                        // Remove row from table
                        const row = document.getElementById(`customer-row-${customerId}`);
                        if (row) {
                            row.classList.add('deleting-row');
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('customersTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                    document.getElementById('bulkActions').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // If row doesn't exist, reload the table
                            setTimeout(() => {
                                loadCustomers(currentPage, currentFilters);
                            }, 500);
                        }
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la suppression du client');
                    }
                },
                error: function(xhr) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Client non trouvé');
                    } else if (xhr.status === 403) {
                        showAlert('danger', 'Vous n\'avez pas la permission de supprimer ce client');
                    } else {
                        showError('Erreur lors de la suppression du client');
                    }
                },
                complete: function() {
                    customerToDelete = null;
                }
            });
        };

        // Update stats
        const updateStats = (customers) => {
            const total = customers.length;
            const withWebsites = customers.filter(c => (c.websites_count || 0) > 0).length;
            const countries = new Set(customers.map(c => c.country).filter(Boolean));
            const thisMonth = customers.filter(c => {
                const created = new Date(c.created_at);
                const now = new Date();
                return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
            }).length;
            
            document.getElementById('totalCustomers').textContent = total;
            document.getElementById('customersWithWebsites').textContent = withWebsites;
            document.getElementById('countriesCount').textContent = countries.size;
            document.getElementById('thisMonth').textContent = thisMonth;
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            if (!response || !response.data || response.data.length === 0) {
                pagination.innerHTML = '';
                paginationInfo.textContent = '';
                return;
            }
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} client${response.total > 1 ? 's' : ''}`;
            
            // Render pagination links
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
        };

        // Change page
        const changePage = (page) => {
            currentPage = page;
            loadCustomers(page, currentFilters);
        };

        // Store customer function
        const storeCustomer = () => {
            const form = document.getElementById('createCustomerForm');
            const submitBtn = document.getElementById('submitCustomerBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Créer le client
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Création en cours...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            $.ajax({
                url: '{{ route("customers.store") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le client
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createCustomerModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        
                        // Reload customers
                        loadCustomers(1, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Client créé avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création du client');
                    }
                },
                error: function(xhr) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le client
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                        for (const field in errors) {
                            errorMessage += `- ${errors[field].join('<br>')}<br>`;
                        }
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de la création du client');
                    }
                }
            });
        };

        // Update customer function
        const updateCustomer = () => {
            const form = document.getElementById('editCustomerForm');
            const submitBtn = document.getElementById('updateCustomerBtn');
            const customerId = document.getElementById('editCustomerId').value;
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Enregistrement...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            
            $.ajax({
                url: `/customers/${customerId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editCustomerModal'));
                        modal.hide();
                        
                        // Reload customers
                        loadCustomers(currentPage, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Client mis à jour avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la mise à jour du client');
                    }
                },
                error: function(xhr) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '<strong>Veuillez corriger les erreurs suivantes :</strong><ul class="mb-0 mt-2">';
                        for (const field in errors) {
                            errorMessage += `<li>${errors[field].join('</li><li>')}</li>`;
                        }
                        errorMessage += '</ul>';
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de la mise à jour du client');
                    }
                }
            });
        };

        // Open edit modal
        const openEditModal = (customerId) => {
            const customer = allCustomers.find(c => c.id === customerId);
            
            if (customer) {
                document.getElementById('editCustomerId').value = customer.id;
                document.getElementById('editCustomerName').value = customer.name;
                document.getElementById('editCustomerEmail').value = customer.email || '';
                document.getElementById('editCustomerPhone').value = customer.phone || '';
                document.getElementById('editCustomerUserId').value = customer.user_id;
                document.getElementById('editCustomerCountry').value = customer.country || '';
                document.getElementById('editCustomerCity').value = customer.city || '';
                document.getElementById('editCustomerAddress').value = customer.address || '';
                
                new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
            }
        };

        // View customer details
        const viewCustomer = (customerId) => {
            window.location.href = `/customers/${customerId}`;
        };

        // Format time ago
        const formatTimeAgo = (date) => {
            const now = new Date();
            const diffMs = now - date;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            
            if (diffDays === 0) return "Aujourd'hui";
            if (diffDays === 1) return 'Hier';
            if (diffDays < 7) return `Il y a ${diffDays} jours`;
            if (diffDays < 30) return `Il y a ${Math.floor(diffDays / 7)} semaines`;
            if (diffDays < 365) return `Il y a ${Math.floor(diffDays / 30)} mois`;
            return `Il y a ${Math.floor(diffDays / 365)} ans`;
        };

        // Show loading state
        const showLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'flex';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
        };

        // Hide loading state
        const hideLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'none';
        };

        // Show alert
        const showAlert = (type, message) => {
            const existingAlert = document.querySelector('.alert-custom-modern');
            if (existingAlert) existingAlert.remove();
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-custom-modern alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        };

        // Show error
        const showError = (message) => {
            showAlert('danger', message);
        };

        // Setup event listeners
        const setupEventListeners = () => {
            // Search input with debounce
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        loadCustomers(1, currentFilters);
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
                        country: document.getElementById('filterCountry').value,
                        city: document.getElementById('filterCity').value,
                        date_from: document.getElementById('filterDateFrom').value,
                        date_to: document.getElementById('filterDateTo').value
                    };
                    loadCustomers(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterCountry').value = '';
                    document.getElementById('filterCity').value = '';
                    document.getElementById('filterDateFrom').value = '';
                    document.getElementById('filterDateTo').value = '';
                    currentFilters = {};
                    loadCustomers(1);
                });
            }
            
            // Submit customer form
            const submitCustomerBtn = document.getElementById('submitCustomerBtn');
            if (submitCustomerBtn) {
                submitCustomerBtn.addEventListener('click', storeCustomer);
            }
            
            // Update customer form
            const updateCustomerBtn = document.getElementById('updateCustomerBtn');
            if (updateCustomerBtn) {
                updateCustomerBtn.addEventListener('click', updateCustomer);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteCustomer);
            }
            
            // Bulk delete button
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', bulkDeleteCustomers);
            }
            
            // Clear selection button
            const clearSelectionBtn = document.getElementById('clearSelectionBtn');
            if (clearSelectionBtn) {
                clearSelectionBtn.addEventListener('click', clearSelections);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    customerToDelete = null;
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                });
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createCustomerModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createCustomerForm').reset();
                    const submitBtn = document.getElementById('submitCustomerBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le client
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editCustomerModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    const submitBtn = document.getElementById('updateCustomerBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
        };
    </script>

    <style>
        /* Custom styles for the customers page */
        .customer-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .customer-icon-modern {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .customer-name-text {
            font-weight: 600;
            color: #333;
        }
        
        .contact-info div, .location-info div {
            margin-bottom: 4px;
            font-size: 13px;
        }
        
        .contact-info i, .location-info i {
            width: 16px;
            color: #6c757d;
        }
        
        .customer-actions-modern {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }
        
        .action-btn-modern {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .view-btn-modern {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .view-btn-modern:hover {
            background: #bbdefb;
            transform: translateY(-2px);
        }
        
        .edit-btn-modern {
            background: #e8f5e9;
            color: #388e3c;
        }
        
        .edit-btn-modern:hover {
            background: #c8e6c9;
            transform: translateY(-2px);
        }
        
        .delete-btn-modern {
            background: #ffebee;
            color: #d32f2f;
        }
        
        .delete-btn-modern:hover {
            background: #ffcdd2;
            transform: translateY(-2px);
        }
        
        .customer-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .selected-row {
            background-color: rgba(33, 150, 243, 0.1) !important;
        }
        
        .deleting-row {
            opacity: 0.5;
            transform: scale(0.98);
            transition: all 0.3s ease;
        }
        
        .bulk-actions-modern {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            padding: 16px 24px;
            z-index: 1000;
            border: 1px solid #e0e0e0;
        }
        
        .bulk-actions-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .bulk-actions-content span {
            font-weight: 600;
            color: #1976d2;
        }
        
        .bulk-buttons {
            display: flex;
            gap: 8px;
        }
        
        .customer-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 16px 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .customer-info-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .customer-info-name {
            font-weight: 600;
            font-size: 18px;
            color: #333;
        }
        
        .customer-info-email {
            color: #666;
            font-size: 14px;
        }
        
        /* Modern table styles */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .modern-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .modern-table thead th {
            color: white;
            font-weight: 600;
            padding: 16px;
            text-align: left;
            border: none;
        }
        
        .modern-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .modern-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateX(4px);
        }
        
        .modern-table tbody td {
            padding: 16px;
            vertical-align: middle;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .modern-table {
                display: block;
                overflow-x: auto;
            }
            
            .bulk-actions-modern {
                width: 90%;
                padding: 12px;
            }
            
            .bulk-actions-content {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
@endsection