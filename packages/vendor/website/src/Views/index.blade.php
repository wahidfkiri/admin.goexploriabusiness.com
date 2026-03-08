@extends('website::layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-globe"></i></span>
                Gestion des Sites Web
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWebsiteModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Site
                </button>
            </div>
        </div>
        
        <!-- Filter Section -->
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
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterCategory" class="form-label-modern">Catégorie</label>
                    <select class="form-select-modern" id="filterCategory">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterUser" class="form-label-modern">Client</label>
                    <select class="form-select-modern" id="filterUser">
                        <option value="">Tous les clients</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->customer->name ?? $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterDateFrom" class="form-label-modern">Date de début</label>
                    <input type="date" class="form-control-modern" id="filterDateFrom">
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalWebsites">0</div>
                        <div class="stats-label-modern">Total Sites</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeWebsites">0</div>
                        <div class="stats-label-modern">Sites Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="categoriesCount">0</div>
                        <div class="stats-label-modern">Catégories</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-tags"></i>
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
        
        <!-- Main Card -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Sites Web</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un site web..." id="searchInput">
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
                                <th>Site Web</th>
                                <th>URL</th>
                                <th>Client</th>
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="websitesTableBody">
                            <!-- Websites will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun site web trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier site web pour votre plateforme.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWebsiteModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un site web
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
                <span id="selectedCount">0</span> site(s) web sélectionné(s)
                <div class="bulk-buttons">
                    <button class="btn btn-sm btn-outline-success" id="bulkActivateBtn">
                        <i class="fas fa-play me-1"></i>Activer
                    </button>
                    <button class="btn btn-sm btn-outline-warning" id="bulkDeactivateBtn">
                        <i class="fas fa-pause me-1"></i>Désactiver
                    </button>
                    <button class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="clearSelectionBtn">
                        <i class="fas fa-times me-1"></i>Effacer
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createWebsiteModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE WEBSITE MODAL -->
    <div class="modal fade" id="createWebsiteModal" tabindex="-1" aria-labelledby="createWebsiteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createWebsiteModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau site web
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createWebsiteForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-4 d-grid">
                                <label for="websiteName" class="form-label-modern">Nom du site *</label>
                                <input type="text" class="form-control-modern" id="websiteName" name="name" placeholder="Ex: Site E-commerce" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="websiteUserId" class="form-label-modern">Client *</label>
                                <select class="form-select-modern" id="websiteUserId" name="user_id" required>
                                    <option value="">Sélectionnez un client</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->customer->name ?? $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="websiteCategoryId" class="form-label-modern">Catégorie *</label>
                                <select class="form-select-modern" id="websiteCategoryId" name="categorie_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4 d-grid">
                                <label for="websiteStatus" class="form-label-modern">Statut *</label>
                                <select class="form-select-modern" id="websiteStatus" name="status" required>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ $value == 'active' ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4 d-grid">
                            <label for="websiteDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="websiteDescription" name="description" rows="3" placeholder="Description du site web..."></textarea>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitWebsiteBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le site
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT WEBSITE MODAL -->
    <div class="modal fade" id="editWebsiteModal" tabindex="-1" aria-labelledby="editWebsiteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editWebsiteModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier le site web
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editWebsiteForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editWebsiteId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="editWebsiteName" class="form-label-modern">Nom du site *</label>
                                <input type="text" class="form-control-modern" id="editWebsiteName" name="name" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="editWebsiteUrl" class="form-label-modern">URL *</label>
                                <div class="input-group">
                                    <span class="input-group-text">https://</span>
                                    <input type="text" class="form-control-modern" id="editWebsiteUrl" name="url" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="editWebsiteUserId" class="form-label-modern">Client *</label>
                                <select class="form-select-modern" id="editWebsiteUserId" name="user_id" required>
                                    <option value="">Sélectionnez un client</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->customer->name ?? $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="editWebsiteCategoryId" class="form-label-modern">Catégorie *</label>
                                <select class="form-select-modern" id="editWebsiteCategoryId" name="categorie_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="editWebsiteStatus" class="form-label-modern">Statut *</label>
                                <select class="form-select-modern" id="editWebsiteStatus" name="status" required>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="editWebsiteTemplateType" class="form-label-modern">Type de template</label>
                                <select class="form-select-modern" id="editWebsiteTemplateType" name="template_type">
                                    <option value="">Sélectionnez un type</option>
                                    @foreach($templateTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="editWebsiteDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="editWebsiteDescription" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="editWebsiteColorScheme" class="form-label-modern">Schéma de couleurs</label>
                                <select class="form-select-modern" id="editWebsiteColorScheme" name="color_scheme">
                                    <option value="">Défaut</option>
                                    @foreach($colorSchemes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="editWebsitePrice" class="form-label-modern">Prix (€)</label>
                                <input type="number" step="0.01" class="form-control-modern" id="editWebsitePrice" name="price">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label-modern">Fonctionnalités</label>
                            <div class="features-checkbox-grid" id="editFeaturesContainer">
                                <!-- Features will be loaded dynamically -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateWebsiteBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce site web ? Cette action est irréversible.</p>
                    
                    <div class="website-to-delete" id="websiteToDeleteInfo">
                        <!-- Website info will be loaded here -->
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
    
    <!-- QUICK VIEW MODAL -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="quickViewModalLabel">
                        <i class="fas fa-eye me-2"></i>Vue rapide
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <div id="quickViewContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Configuration
    let currentPage = 1;
    let currentFilters = {};
    let allWebsites = [];
    let websiteToDelete = null;
    let selectedWebsites = new Set();

    // Validation configuration
    const validationRules = {
        name: {
            required: true,
            minLength: 3,
            maxLength: 100,
            pattern: /^[a-zA-Z0-9\s\-_&@#!?]+$/,
            message: "Le nom doit contenir entre 3 et 100 caractères (lettres, chiffres, espaces et caractères spéciaux de base)"
        },
        url: {
            required: true,
            pattern: /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/,
            message: "URL invalide (ex: mon-site.com ou www.mon-site.fr)"
        },
        user_id: {
            required: true,
            message: "Veuillez sélectionner un client"
        },
        categorie_id: {
            required: true,
            message: "Veuillez sélectionner une catégorie"
        },
        status: {
            required: true,
            message: "Veuillez sélectionner un statut"
        },
        description: {
            maxLength: 500,
            message: "La description ne peut pas dépasser 500 caractères"
        },
        price: {
            pattern: /^\d+(\.\d{1,2})?$/,
            min: 0,
            max: 999999.99,
            message: "Le prix doit être un nombre positif avec maximum 2 décimales"
        },
        template_type: {
            pattern: /^[a-z_]+$/,
            message: "Type de template invalide"
        },
        color_scheme: {
            pattern: /^[a-z_]+$/,
            message: "Schéma de couleurs invalide"
        }
    };

    // Validation state management
    let validationErrors = {
        create: {},
        edit: {}
    };

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setupAjax();
        loadWebsites();
        initializeValidation();
        setupCharacterCounters();
        setupEventListeners();
        addValidationStyles();
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

    // Load websites
    const loadWebsites = (page = 1, filters = {}) => {
        showLoading();
        
        const searchTerm = document.getElementById('searchInput')?.value || '';
        
        $.ajax({
            url: '{{ route("websites.index") }}',
            type: 'GET',
            data: {
                page: page,
                search: searchTerm,
                ...filters,
                ajax: true
            },
            success: function(response) {
                if (response.success) {
                    allWebsites = response.data || [];
                    renderWebsites(allWebsites);
                    renderPagination(response);
                    updateStats();
                    hideLoading();
                } else {
                    showError('Erreur lors du chargement des sites web');
                }
            },
            error: function(xhr) {
                hideLoading();
                showError('Erreur de connexion au serveur');
                console.error('Error:', xhr.responseText);
            }
        });
    };

    // Render websites
    const renderWebsites = (websites) => {
        const tbody = document.getElementById('websitesTableBody');
        tbody.innerHTML = '';
        
        if (!websites || !Array.isArray(websites) || websites.length === 0) {
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            document.getElementById('bulkActions').style.display = 'none';
            return;
        }
        
        websites.forEach((website, index) => {
            const row = document.createElement('tr');
            row.id = `website-row-${website.id}`;
            row.style.animationDelay = `${index * 0.05}s`;
            
            if (selectedWebsites.has(website.id)) {
                row.classList.add('selected-row');
            }
            
            // Format date
            const createdDate = new Date(website.created_at);
            const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            // Status badge
            let statusClass = 'status-active-modern';
            let statusText = 'Actif';
            
            switch(website.status) {
                case 'inactive':
                    statusClass = 'status-inactive-modern';
                    statusText = 'Inactif';
                    break;
                case 'maintenance':
                    statusClass = 'status-maintenance-modern';
                    statusText = 'Maintenance';
                    break;
                case 'development':
                    statusClass = 'status-development-modern';
                    statusText = 'En dev';
                    break;
            }
            
            // Customer name
            const customerName = website.customer_name || website.user?.name || 'N/A';
            
            row.innerHTML = `
                <td class="website-name-cell">
                    <div class="website-name-modern">
                        <div class="website-icon-modern">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div>
                            <div class="website-name-text">${website.name}</div>
                            <small class="text-muted">ID: ${website.id}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <a href="https://${website.url}" target="_blank" class="website-url-modern">
                        <i class="fas fa-link me-1"></i>${website.url}
                    </a>
                </td>
                <td>
                    <div class="customer-info-small">
                        <div>${customerName}</div>
                        <small class="text-muted">${website.user?.email || ''}</small>
                    </div>
                </td>
                <td>
                    <span class="website-category-modern">
                        ${website.category?.name || 'Non catégorisé'}
                    </span>
                </td>
                <td>
                    <span class="website-status-modern ${statusClass}">
                        <span class="status-dot-modern"></span>
                        ${statusText}
                    </span>
                </td>
                <td>
                    <div>${formattedDate}</div>
                    <small class="text-muted">${formatTimeAgo(createdDate)}</small>
                </td>
                <td>
                    <div class="website-actions-modern">
                        <button class="action-btn-modern view-btn-modern" title="Vue rapide" onclick="quickView(${website.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn-modern edit-btn-modern" title="Modifier" onclick="openEditModal(${website.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn-modern delete-btn-modern" title="Supprimer" onclick="showDeleteConfirmation(${website.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="action-btn-modern status-btn-modern" title="Changer statut" onclick="changeStatus(${website.id})">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <input type="checkbox" class="website-checkbox" onclick="toggleWebsiteSelection(${website.id})" ${selectedWebsites.has(website.id) ? 'checked' : ''}>
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
        
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('paginationContainer').style.display = 'flex';
        
        // Show/hide bulk actions
        updateBulkActions();
    };

    // Toggle website selection
    const toggleWebsiteSelection = (websiteId) => {
        if (selectedWebsites.has(websiteId)) {
            selectedWebsites.delete(websiteId);
        } else {
            selectedWebsites.add(websiteId);
        }
        
        // Update UI
        const row = document.getElementById(`website-row-${websiteId}`);
        if (row) {
            row.classList.toggle('selected-row');
        }
        
        updateBulkActions();
    };

    // Update bulk actions
    const updateBulkActions = () => {
        if (selectedWebsites.size > 0) {
            document.getElementById('bulkActions').style.display = 'block';
            document.getElementById('selectedCount').textContent = selectedWebsites.size;
        } else {
            document.getElementById('bulkActions').style.display = 'none';
        }
    };

    // Clear all selections
    const clearSelections = () => {
        selectedWebsites.clear();
        document.querySelectorAll('.selected-row').forEach(row => {
            row.classList.remove('selected-row');
        });
        document.querySelectorAll('.website-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActions();
    };

    // Bulk actions
    const bulkActivate = () => {
        if (selectedWebsites.size === 0) return;
        
        const websiteIds = Array.from(selectedWebsites);
        
        $.ajax({
            url: '{{ route("websites.bulk.activate") }}',
            type: 'POST',
            data: { website_ids: websiteIds },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    clearSelections();
                    loadWebsites(currentPage, currentFilters);
                }
            }
        });
    };

    const bulkDeactivate = () => {
        if (selectedWebsites.size === 0) return;
        
        const websiteIds = Array.from(selectedWebsites);
        
        $.ajax({
            url: '{{ route("websites.bulk.deactivate") }}',
            type: 'POST',
            data: { website_ids: websiteIds },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    clearSelections();
                    loadWebsites(currentPage, currentFilters);
                }
            }
        });
    };

    const bulkDelete = () => {
        if (selectedWebsites.size === 0) return;
        
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedWebsites.size} site(s) web ?`)) {
            return;
        }
        
        const websiteIds = Array.from(selectedWebsites);
        
        $.ajax({
            url: '{{ route("websites.bulk.delete") }}',
            type: 'POST',
            data: { website_ids: websiteIds },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    clearSelections();
                    loadWebsites(currentPage, currentFilters);
                }
            }
        });
    };

    // Show delete confirmation modal
    const showDeleteConfirmation = (websiteId) => {
        const website = allWebsites.find(w => w.id === websiteId);
        
        if (!website) {
            showAlert('danger', 'Site web non trouvé');
            return;
        }
        
        websiteToDelete = website;
        
        const createdDate = new Date(website.created_at);
        const formattedDate = createdDate.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        
        document.getElementById('websiteToDeleteInfo').innerHTML = `
            <div class="website-info">
                <div class="website-info-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div>
                    <div class="website-info-name">${website.name}</div>
                    <div class="website-info-url">https://${website.url}</div>
                </div>
            </div>
            <div class="row small text-muted">
                <div class="col-6">
                    <div><strong>ID:</strong> ${website.id}</div>
                    <div><strong>Client:</strong> ${website.customer_name || 'N/A'}</div>
                </div>
                <div class="col-6">
                    <div><strong>Créé le:</strong> ${formattedDate}</div>
                    <div><strong>Statut:</strong> ${website.status}</div>
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

    // Delete website
    const deleteWebsite = () => {
        if (!websiteToDelete) return;
        
        const websiteId = websiteToDelete.id;
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
        
        $.ajax({
            url: `/websites/${websiteId}`,
            type: 'DELETE',
            success: function(response) {
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                deleteModal.hide();
                
                if (response.success) {
                    // Remove from arrays
                    allWebsites = allWebsites.filter(w => w.id !== websiteId);
                    selectedWebsites.delete(websiteId);
                    
                    // Update UI
                    updateStats();
                    showAlert('success', response.message);
                    
                    // Remove row
                    const row = document.getElementById(`website-row-${websiteId}`);
                    if (row) {
                        row.classList.add('deleting-row');
                        setTimeout(() => {
                            row.remove();
                            checkEmptyState();
                        }, 300);
                    } else {
                        loadWebsites(currentPage, currentFilters);
                    }
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                deleteModal.hide();
                showError('Erreur lors de la suppression');
            },
            complete: function() {
                websiteToDelete = null;
            }
        });
    };

    // Check empty state
    const checkEmptyState = () => {
        const tbody = document.getElementById('websitesTableBody');
        if (tbody.children.length === 0) {
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            document.getElementById('bulkActions').style.display = 'none';
        }
    };

    // Update stats
    const updateStats = () => {
        // You can call statistics API or calculate from allWebsites
        const total = allWebsites.length;
        const active = allWebsites.filter(w => w.status === 'active').length;
        const categories = new Set(allWebsites.map(w => w.categorie_id).filter(Boolean));
        
        document.getElementById('totalWebsites').textContent = total;
        document.getElementById('activeWebsites').textContent = active;
        document.getElementById('categoriesCount').textContent = categories.size;
        document.getElementById('thisMonth').textContent = allWebsites.filter(w => {
            const created = new Date(w.created_at);
            const now = new Date();
            return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
        }).length;
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
        paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} site${response.total > 1 ? 's' : ''}`;
        
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
        loadWebsites(page, currentFilters);
    };

    // Store website function with validation
    const storeWebsite = () => {
        const form = document.getElementById('createWebsiteForm');
        const submitBtn = document.getElementById('submitWebsiteBtn');
        
        // Validate form before submission
        if (!validateForm(form, 'create')) {
            // Scroll to first error
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return false;
        }
        
        // Show processing animation
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = `
            <span class="btn-text" style="display: none;">
                <i class="fas fa-save me-2"></i>Créer le site
            </span>
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Création en cours...
        `;
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        
        // Get selected features
        const features = [];
        form.querySelectorAll('input[name="features[]"]:checked').forEach(checkbox => {
            features.push(checkbox.value);
        });
        formData.set('features', JSON.stringify(features));
        
        $.ajax({
            url: '{{ route("websites.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le site
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (response.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createWebsiteModal'));
                    modal.hide();
                    
                    // Reset form and validation
                    form.reset();
                    clearFormValidation(form, 'create');
                    
                    // Reload websites
                    loadWebsites(1, currentFilters);
                    
                    // Show success message
                    showAlert('success', response.message);
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le site
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
                    showAlert('danger', 'Erreur lors de la création du site web');
                }
            }
        });
    };

    // Update website function with validation
    const updateWebsite = () => {
        const form = document.getElementById('editWebsiteForm');
        const submitBtn = document.getElementById('updateWebsiteBtn');
        const websiteId = document.getElementById('editWebsiteId').value;
        
        // Validate form before submission
        if (!validateForm(form, 'edit')) {
            // Scroll to first error
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return false;
        }
        
        // Show processing animation
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = `
            <span class="btn-text" style="display: none;">
                <i class="fas fa-save me-2"></i>Enregistrer
            </span>
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Enregistrement...
        `;
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        
        // Get selected features
        const features = [];
        form.querySelectorAll('input[name="features[]"]:checked').forEach(checkbox => {
            features.push(checkbox.value);
        });
        formData.set('features', JSON.stringify(features));
        
        $.ajax({
            url: `/websites/${websiteId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (response.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editWebsiteModal'));
                    modal.hide();
                    
                    // Reload websites
                    loadWebsites(currentPage, currentFilters);
                    
                    // Show success message
                    showAlert('success', response.message);
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Enregistrer
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
                    showAlert('danger', 'Erreur lors de la mise à jour du site web');
                }
            }
        });
    };

    // Open edit modal
    const openEditModal = (websiteId) => {
        const website = allWebsites.find(w => w.id === websiteId);
        
        if (!website) return;
        
        // Fetch website details for edit
        $.ajax({
            url: `/websites/${websiteId}/edit`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const website = response.website;
                    
                    // Populate form
                    document.getElementById('editWebsiteId').value = website.id;
                    document.getElementById('editWebsiteName').value = website.name;
                    document.getElementById('editWebsiteUrl').value = website.url;
                    document.getElementById('editWebsiteUserId').value = website.user_id;
                    document.getElementById('editWebsiteCategoryId').value = website.categorie_id;
                    document.getElementById('editWebsiteStatus').value = website.status;
                    document.getElementById('editWebsiteTemplateType').value = website.template_type || '';
                    document.getElementById('editWebsiteDescription').value = website.description || '';
                    document.getElementById('editWebsiteColorScheme').value = website.color_scheme || '';
                    document.getElementById('editWebsitePrice').value = website.price || '';
                    
                    // Update character counter
                    const editDescription = document.getElementById('editWebsiteDescription');
                    const editCounter = editDescription.parentElement.querySelector('small');
                    if (editCounter) {
                        editCounter.textContent = `${editDescription.value.length}/${validationRules.description.maxLength}`;
                    }
                    
                    // Populate features
                    const featuresContainer = document.getElementById('editFeaturesContainer');
                    featuresContainer.innerHTML = '';
                    
                    const features = ['blog', 'ecommerce', 'contact_form', 'gallery', 'newsletter', 'multilingual', 'seo', 'analytics'];
                    const websiteFeatures = website.features ? JSON.parse(website.features) : [];
                    
                    features.forEach(feature => {
                        const isChecked = websiteFeatures.includes(feature);
                        featuresContainer.innerHTML += `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[]" value="${feature}" id="editFeature${ucfirst(feature)}" ${isChecked ? 'checked' : ''}>
                                <label class="form-check-label" for="editFeature${ucfirst(feature)}">
                                    ${ucfirst(feature.replace('_', ' '))}
                                </label>
                            </div>
                        `;
                    });
                    
                    // Clear validation errors
                    const form = document.getElementById('editWebsiteForm');
                    clearFormValidation(form, 'edit');
                    
                    // Show modal
                    new bootstrap.Modal(document.getElementById('editWebsiteModal')).show();
                }
            }
        });
    };

    // Quick view
    const quickView = (websiteId) => {
        const website = allWebsites.find(w => w.id === websiteId);
        
        if (!website) return;
        
        const createdDate = new Date(website.created_at);
        const formattedDate = createdDate.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Status text
        let statusText = 'Actif';
        switch(website.status) {
            case 'inactive': statusText = 'Inactif'; break;
            case 'maintenance': statusText = 'Maintenance'; break;
            case 'development': statusText = 'En développement'; break;
        }
        
        // Features
        let featuresHtml = '';
        if (website.features) {
            const features = JSON.parse(website.features);
            features.forEach(feature => {
                featuresHtml += `<span class="badge bg-info me-1 mb-1">${feature}</span>`;
            });
        }
        
        document.getElementById('quickViewContent').innerHTML = `
            <div class="website-quick-view">
                <div class="website-header">
                    <div class="website-icon-large">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div>
                        <h4>${website.name}</h4>
                        <p class="text-muted">ID: ${website.id}</p>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-link me-2"></i>URL</h6>
                        <p><a href="https://${website.url}" target="_blank">https://${website.url}</a></p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-user me-2"></i>Client</h6>
                        <p>${website.customer_name || website.user?.name || 'N/A'}</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6><i class="fas fa-tag me-2"></i>Catégorie</h6>
                        <p>${website.category?.name || 'Non catégorisé'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-chart-line me-2"></i>Statut</h6>
                        <span class="badge bg-${website.status === 'active' ? 'success' : website.status === 'inactive' ? 'danger' : 'warning'}">
                            ${statusText}
                        </span>
                    </div>
                </div>
                
                ${website.description ? `
                    <div class="mt-3">
                        <h6><i class="fas fa-align-left me-2"></i>Description</h6>
                        <p>${website.description}</p>
                    </div>
                ` : ''}
                
                ${featuresHtml ? `
                    <div class="mt-3">
                        <h6><i class="fas fa-cogs me-2"></i>Fonctionnalités</h6>
                        <div class="mt-2">${featuresHtml}</div>
                    </div>
                ` : ''}
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar me-2"></i>Créé le</h6>
                        <p>${formattedDate}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-sync-alt me-2"></i>Dernière mise à jour</h6>
                        <p>${formatTimeAgo(new Date(website.updated_at))}</p>
                    </div>
                </div>
            </div>
        `;
        
        new bootstrap.Modal(document.getElementById('quickViewModal')).show();
    };

    // Change status
    const changeStatus = (websiteId) => {
        const website = allWebsites.find(w => w.id === websiteId);
        if (!website) return;
        
        const newStatus = website.status === 'active' ? 'inactive' : 'active';
        
        $.ajax({
            url: `/websites/${websiteId}/status`,
            type: 'POST',
            data: { status: newStatus },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    loadWebsites(currentPage, currentFilters);
                }
            }
        });
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

    // Helper function
    const ucfirst = (str) => {
        return str.charAt(0).toUpperCase() + str.slice(1);
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

    // VALIDATION FUNCTIONS
    // Initialize validation
    const initializeValidation = () => {
        // Setup validation for create form
        const createForm = document.getElementById('createWebsiteForm');
        if (createForm) {
            setupFormValidation(createForm, 'create');
        }
        
        // Setup validation for edit form
        const editForm = document.getElementById('editWebsiteForm');
        if (editForm) {
            setupFormValidation(editForm, 'edit');
        }
    };

    // Setup form validation
    const setupFormValidation = (form, formType) => {
        // Clear existing validation
        clearFormValidation(form, formType);
        
        // Add validation event listeners to all inputs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            // Skip hidden inputs
            if (input.type === 'hidden') return;
            
            // Add event listeners for real-time validation
            input.addEventListener('blur', () => validateField(input, formType));
            input.addEventListener('input', () => {
                if (validationErrors[formType][input.name]) {
                    validateField(input, formType);
                }
            });
            
            // For selects, validate on change
            if (input.tagName === 'SELECT') {
                input.addEventListener('change', () => validateField(input, formType));
            }
            
            // For checkboxes (features)
            if (input.name === 'features[]') {
                input.addEventListener('change', () => validateFeatures(formType));
            }
        });
    };

    // Validate a single field
    const validateField = (field, formType) => {
        const fieldName = field.name;
        const value = field.value.trim();
        const rules = validationRules[fieldName];
        
        // If no rules for this field, skip validation
        if (!rules) return true;
        
        // Clear previous error
        clearFieldError(field, formType);
        
        // Check required field
        if (rules.required && !value) {
            setFieldError(field, rules.message || 'Ce champ est requis', formType);
            return false;
        }
        
        // Check min length
        if (rules.minLength && value.length < rules.minLength) {
            setFieldError(field, `Minimum ${rules.minLength} caractères requis`, formType);
            return false;
        }
        
        // Check max length
        if (rules.maxLength && value.length > rules.maxLength) {
            setFieldError(field, `Maximum ${rules.maxLength} caractères autorisés`, formType);
            return false;
        }
        
        // Check pattern
        if (rules.pattern && value && !rules.pattern.test(value)) {
            setFieldError(field, rules.message, formType);
            return false;
        }
        
        // Check numeric range for price
        if (fieldName === 'price' && value) {
            const numValue = parseFloat(value);
            if (rules.min !== undefined && numValue < rules.min) {
                setFieldError(field, `Le prix ne peut pas être inférieur à ${rules.min}`, formType);
                return false;
            }
            if (rules.max !== undefined && numValue > rules.max) {
                setFieldError(field, `Le prix ne peut pas dépasser ${rules.max}`, formType);
                return false;
            }
        }
        
        // Special validation for URL
        if (fieldName === 'url' && value) {
            if (!isValidUrl(value)) {
                setFieldError(field, 'URL invalide. Exemples valides: mon-site.com, www.mon-site.fr', formType);
                return false;
            }
        }
        
        // If all validations pass
        setFieldSuccess(field, formType);
        return true;
    };

    // Validate features checkboxes
    const validateFeatures = (formType) => {
        const form = formType === 'create' ? 
            document.getElementById('createWebsiteForm') : 
            document.getElementById('editWebsiteForm');
        
        if (!form) return true;
        
        return true;
    };

    // Set field error state
    const setFieldError = (field, message, formType) => {
        const fieldName = field.name;
        const parent = field.closest('.mb-4') || field.parentElement;
        
        // Store error
        validationErrors[formType][fieldName] = message;
        
        // Add error class to field
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        
        // Remove existing error message
        const existingError = parent.querySelector('.invalid-feedback');
        if (existingError) existingError.remove();
        
        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        
        parent.appendChild(errorDiv);
        
        // Highlight label
        const label = parent.querySelector('label');
        if (label) {
            label.classList.add('text-danger');
        }
    };

    // Set field success state
    const setFieldSuccess = (field, formType) => {
        const fieldName = field.name;
        const parent = field.closest('.mb-4') || field.parentElement;
        
        // Remove error from state
        delete validationErrors[formType][fieldName];
        
        // Update field classes
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        
        // Remove error message
        const errorDiv = parent.querySelector('.invalid-feedback');
        if (errorDiv) errorDiv.remove();
        
        // Reset label
        const label = parent.querySelector('label');
        if (label) {
            label.classList.remove('text-danger');
        }
    };

    // Clear field error
    const clearFieldError = (field, formType) => {
        const fieldName = field.name;
        const parent = field.closest('.mb-4') || field.parentElement;
        
        // Remove from state
        delete validationErrors[formType][fieldName];
        
        // Remove classes and messages
        field.classList.remove('is-invalid');
        
        const errorDiv = parent.querySelector('.invalid-feedback');
        if (errorDiv) errorDiv.remove();
        
        const label = parent.querySelector('label');
        if (label) {
            label.classList.remove('text-danger');
        }
    };

    // Clear all validation for a form
    const clearFormValidation = (form, formType) => {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            clearFieldError(input, formType);
            input.classList.remove('is-valid');
        });
        
        // Clear features error
        const featuresContainer = form.querySelector('.features-checkbox-grid');
        if (featuresContainer) {
            const featuresError = featuresContainer.querySelector('.features-error');
            if (featuresError) featuresError.remove();
        }
        
        validationErrors[formType] = {};
    };

    // Validate entire form
    const validateForm = (form, formType) => {
        let isValid = true;
        
        // Clear all previous errors
        clearFormValidation(form, formType);
        
        // Validate all fields
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.type !== 'checkbox' && !validateField(input, formType)) {
                isValid = false;
            }
        });
        
        // Validate features
        if (!validateFeatures(formType)) {
            isValid = false;
        }
        
        return isValid;
    };

    // URL validation helper
    const isValidUrl = (url) => {
        // Remove https:// if present
        const cleanUrl = url.replace(/^(https?:\/\/)?(www\.)?/, '');
        
        // Basic domain validation
        const domainRegex = /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/;
        
        // Check for invalid patterns
        const invalidPatterns = [
            /\.\./, // Double dots
            /^-/, // Starts with hyphen
            /-$/, // Ends with hyphen
            /[^a-zA-Z0-9.-]/ // Invalid characters
        ];
        
        // Check for valid TLD (at least 2 letters)
        const tldRegex = /\.[a-zA-Z]{2,}$/;
        
        return domainRegex.test(cleanUrl) && 
               !invalidPatterns.some(pattern => pattern.test(cleanUrl)) &&
               tldRegex.test(cleanUrl);
    };

    // Setup character counters
    const setupCharacterCounters = () => {
        const description = document.getElementById('websiteDescription');
        if (description) {
            const counter = document.createElement('small');
            counter.className = 'text-muted float-end mt-1 character-counter';
            counter.textContent = `0/${validationRules.description.maxLength}`;
            
            description.parentElement.appendChild(counter);
            
            description.addEventListener('input', function() {
                const length = this.value.length;
                counter.textContent = `${length}/${validationRules.description.maxLength}`;
                counter.className = length > validationRules.description.maxLength 
                    ? 'text-danger float-end mt-1 character-counter' 
                    : 'text-muted float-end mt-1 character-counter';
                
                // Validate in real-time
                if (length > validationRules.description.maxLength) {
                    setFieldError(this, `Maximum ${validationRules.description.maxLength} caractères autorisés`, 'create');
                } else {
                    setFieldSuccess(this, 'create');
                }
            });
        }
        
        const editDescription = document.getElementById('editWebsiteDescription');
        if (editDescription) {
            const counter = document.createElement('small');
            counter.className = 'text-muted float-end mt-1 character-counter';
            counter.textContent = `0/${validationRules.description.maxLength}`;
            
            editDescription.parentElement.appendChild(counter);
            
            editDescription.addEventListener('input', function() {
                const length = this.value.length;
                counter.textContent = `${length}/${validationRules.description.maxLength}`;
                counter.className = length > validationRules.description.maxLength 
                    ? 'text-danger float-end mt-1 character-counter' 
                    : 'text-muted float-end mt-1 character-counter';
                
                // Validate in real-time
                if (length > validationRules.description.maxLength) {
                    setFieldError(this, `Maximum ${validationRules.description.maxLength} caractères autorisés`, 'edit');
                } else {
                    setFieldSuccess(this, 'edit');
                }
            });
        }
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
                    loadWebsites(1, currentFilters);
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
                    categorie_id: document.getElementById('filterCategory').value,
                    user_id: document.getElementById('filterUser').value,
                    date_from: document.getElementById('filterDateFrom').value
                };
                loadWebsites(1, currentFilters);
            });
        }
        
        // Clear filters
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                document.getElementById('filterStatus').value = '';
                document.getElementById('filterCategory').value = '';
                document.getElementById('filterUser').value = '';
                document.getElementById('filterDateFrom').value = '';
                currentFilters = {};
                loadWebsites(1);
            });
        }
        
        // Submit website form
        const submitWebsiteBtn = document.getElementById('submitWebsiteBtn');
        if (submitWebsiteBtn) {
            submitWebsiteBtn.addEventListener('click', storeWebsite);
        }
        
        // Update website form
        const updateWebsiteBtn = document.getElementById('updateWebsiteBtn');
        if (updateWebsiteBtn) {
            updateWebsiteBtn.addEventListener('click', updateWebsite);
        }
        
        // Confirm delete button
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', deleteWebsite);
        }
        
        // Bulk action buttons
        const bulkActivateBtn = document.getElementById('bulkActivateBtn');
        if (bulkActivateBtn) {
            bulkActivateBtn.addEventListener('click', bulkActivate);
        }
        
        const bulkDeactivateBtn = document.getElementById('bulkDeactivateBtn');
        if (bulkDeactivateBtn) {
            bulkDeactivateBtn.addEventListener('click', bulkDeactivate);
        }
        
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', bulkDelete);
        }
        
        const clearSelectionBtn = document.getElementById('clearSelectionBtn');
        if (clearSelectionBtn) {
            clearSelectionBtn.addEventListener('click', clearSelections);
        }
        
        // Reset modals when hidden
        const modals = ['deleteConfirmationModal', 'createWebsiteModal', 'editWebsiteModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('hidden.bs.modal', function() {
                    if (modalId === 'deleteConfirmationModal') {
                        websiteToDelete = null;
                        const deleteBtn = document.getElementById('confirmDeleteBtn');
                        deleteBtn.innerHTML = `
                            <span class="btn-text">
                                <i class="fas fa-trash me-2"></i>Supprimer définitivement
                            </span>
                        `;
                        deleteBtn.disabled = false;
                    } else if (modalId === 'createWebsiteModal') {
                        const form = document.getElementById('createWebsiteForm');
                        clearFormValidation(form, 'create');
                        form.reset();
                        const submitBtn = document.getElementById('submitWebsiteBtn');
                        submitBtn.classList.remove('btn-processing');
                        submitBtn.innerHTML = `
                            <span class="btn-text">
                                <i class="fas fa-save me-2"></i>Créer le site
                            </span>
                        `;
                        submitBtn.disabled = false;
                        
                        // Reset character counter
                        const description = document.getElementById('websiteDescription');
                        const counter = description.parentElement.querySelector('.character-counter');
                        if (counter) {
                            counter.textContent = `0/${validationRules.description.maxLength}`;
                            counter.className = 'text-muted float-end mt-1 character-counter';
                        }
                    } else if (modalId === 'editWebsiteModal') {
                        const form = document.getElementById('editWebsiteForm');
                        clearFormValidation(form, 'edit');
                        const submitBtn = document.getElementById('updateWebsiteBtn');
                        submitBtn.classList.remove('btn-processing');
                        submitBtn.innerHTML = `
                            <span class="btn-text">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </span>
                        `;
                        submitBtn.disabled = false;
                    }
                });
            }
        });
    };

    // Add validation styles
    const addValidationStyles = () => {
        const style = document.createElement('style');
        style.textContent = `
            .is-valid {
                border-color: #198754 !important;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }
            
            .is-invalid {
                border-color: #dc3545 !important;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }
            
            .invalid-feedback {
                display: block;
                width: 100%;
                margin-top: 0.25rem;
                font-size: 0.875em;
                color: #dc3545;
            }
            
            .form-control-modern.is-invalid,
            .form-select-modern.is-invalid {
                border-color: #dc3545;
                padding-right: calc(1.5em + 0.75rem);
            }
            
            .form-control-modern.is-valid,
            .form-select-modern.is-valid {
                border-color: #198754;
                padding-right: calc(1.5em + 0.75rem);
            }
            
            /* Animation for validation errors */
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            
            .is-invalid:focus {
                animation: shake 0.5s ease-in-out;
            }
            
            /* Style for required field labels */
            label[for*="websiteName"]::after,
            label[for*="websiteUrl"]::after,
            label[for*="websiteUserId"]::after,
            label[for*="websiteCategoryId"]::after,
            label[for*="websiteStatus"]::after {
                content: " *";
                color: #dc3545;
            }
            
            /* Success message styling */
            .validation-success {
                border-left: 4px solid #198754;
                background-color: #f8fff9;
                padding: 10px;
                margin: 10px 0;
                border-radius: 4px;
            }
            
            .character-counter {
                font-size: 0.8rem;
            }
            
            .features-error {
                grid-column: 1 / -1;
                margin-top: 8px;
            }
        `;
        document.head.appendChild(style);
    };
</script>

    <style>
        /* Custom styles for websites page */
        .website-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .website-icon-modern {
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
        
        .website-name-text {
            font-weight: 600;
            color: #333;
        }
        
        .website-url-modern {
            color: #1976d2;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .website-url-modern:hover {
            color: #0d47a1;
            text-decoration: underline;
        }
        
        .customer-info-small {
            font-size: 13px;
        }
        
        .website-category-modern {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .website-status-modern {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active-modern {
            background: #e8f5e9;
            color: #388e3c;
        }
        
        .status-inactive-modern {
            background: #ffebee;
            color: #d32f2f;
        }
        
        .status-maintenance-modern {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .status-development-modern {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        
        .status-dot-modern {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
        }
        
        .website-actions-modern {
            display: flex;
            gap: 6px;
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
        
        .status-btn-modern {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .status-btn-modern:hover {
            background: #ffe0b2;
            transform: translateY(-2px);
        }
        
        .website-checkbox {
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
        
        .website-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 16px 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .website-info-icon {
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
        
        .website-info-name {
            font-weight: 600;
            font-size: 18px;
            color: #333;
        }
        
        .website-info-url {
            color: #666;
            font-size: 14px;
        }
        
        .features-checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 8px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .website-quick-view {
            padding: 10px;
        }
        
        .website-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .website-icon-large {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
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
            
            .features-checkbox-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection