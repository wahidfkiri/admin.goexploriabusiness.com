@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-puzzle-piece"></i></span>
                Modules & Extensions
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#installModuleModal">
                    <i class="fas fa-download me-2"></i>Installer un module
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
                    <label for="filterCategory" class="form-label-modern">Catégorie</label>
                    <select class="form-select-modern" id="filterCategory">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous</option>
                        <option value="active">Actifs</option>
                        <option value="inactive">Inactifs</option>
                        <option value="pending">En attente</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterType" class="form-label-modern">Type</label>
                    <select class="form-select-modern" id="filterType">
                        <option value="">Tous</option>
                        <option value="core">Cœur</option>
                        <option value="official">Officiel</option>
                        <option value="third-party">Tiers</option>
                        <option value="custom">Personnalisé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterPrice" class="form-label-modern">Prix</label>
                    <select class="form-select-modern" id="filterPrice">
                        <option value="">Tous</option>
                        <option value="free">Gratuit</option>
                        <option value="paid">Payant</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="installed_at">Date d'installation</option>
                        <option value="downloads">Popularité</option>
                        <option value="rating">Note</option>
                        <option value="updated_at">Dernière mise à jour</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalModules">0</div>
                        <div class="stats-label-modern">Total Modules</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeModules">0</div>
                        <div class="stats-label-modern">Modules Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="inactiveModules">0</div>
                        <div class="stats-label-modern">Modules Inactifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-pause-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="updatesAvailable">0</div>
                        <div class="stats-label-modern">Mises à jour</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="freeModules">0</div>
                        <div class="stats-label-modern">Modules Gratuits</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="search-bar-modern">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input-modern" placeholder="Rechercher un module..." id="searchInput">
            </div>
            <div class="view-options">
                <button class="view-option active" id="gridViewBtn">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="view-option" id="listViewBtn">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Tous les modules</h3>
                <div class="modules-count">
                    <span id="visibleModulesCount">0</span> modules
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Loading Spinner -->
                <div class="spinner-container" id="loadingSpinner" style="display: none;">
                    <div class="spinner-border text-primary spinner" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                
                <!-- Modules Grid (Cards View) -->
                <div class="modules-grid" id="modulesGridView">
                    <!-- Dynamic content will be loaded here -->
                </div>
                
                <!-- Modules List (List View) -->
                <div class="modules-list" id="modulesListView" style="display: none;">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Version</th>
                                <th>Auteur</th>
                                <th>Installé le</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic content will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                    <div class="empty-title-modern">Aucun module trouvé</div>
                    <div class="empty-text-modern">Aucun module ne correspond à vos critères de recherche.</div>
                    <button class="btn btn-primary" id="resetFiltersBtn">
                        <i class="fas fa-redo me-2"></i>Réinitialiser les filtres
                    </button>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container-modern" id="paginationContainer">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- Dynamic pagination will be loaded here -->
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Category Navigation -->
        <div class="categories-nav">
            <button class="category-filter active" data-category="all">
                <i class="fas fa-th-large me-2"></i>Tous
            </button>
            @foreach($categories as $category)
                <button class="category-filter" data-category="{{ $category->id }}">
                    <i class="{{ $category->icon }} me-2"></i>{{ $category->name }}
                </button>
            @endforeach
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#installModuleModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- INSTALL MODULE MODAL -->
    <div class="modal fade" id="installModuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modern-modal">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-download me-2" style="color: var(--primary-color);"></i>
                        Installer un module
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Upload Section -->
                    <div class="upload-section">
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <h4>Glissez-déposez votre module</h4>
                            <p>ou</p>
                            <button class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-folder-open me-2"></i>Parcourir
                            </button>
                            <input type="file" id="fileInput" accept=".zip" style="display: none;">
                            <p class="upload-hint">Format accepté : ZIP (max 50 Mo)</p>
                        </div>
                        <div class="upload-progress" id="uploadProgress" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                            <p class="text-center mt-2" id="uploadStatus">Upload en cours...</p>
                        </div>
                    </div>
                    
                    <!-- OR Separator -->
                    <div class="separator">
                        <span>OU</span>
                    </div>
                    
                    <!-- Marketplace Section -->
                    <div class="marketplace-section">
                        <h4 class="marketplace-title">
                            <i class="fas fa-store me-2"></i>
                            Explorer le marketplace
                        </h4>
                        
                        <div class="marketplace-grid" id="marketplaceGrid">
                            <!-- Marketplace items will be loaded dynamically -->
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="#" class="view-more-link" id="viewMoreMarketplace">
                                Voir tous les modules <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade delete-confirm-modal" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 class="delete-title">Confirmer la désinstallation</h4>
                    <p class="delete-message">Êtes-vous sûr de vouloir désinstaller ce module ? Toutes les données associées seront supprimées.</p>
                    
                    <div class="module-to-delete" id="moduleToDeleteInfo">
                        <!-- Module info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Désinstaller
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- UPDATE MODAL -->
    <div class="modal fade" id="updateModuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sync-alt me-2" style="color: var(--primary-color);"></i>
                        Mise à jour disponible
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="updateModalContent">
                    <!-- Update content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Plus tard</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdateBtn">
                        <i class="fas fa-download me-2"></i>Mettre à jour
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW MODULE MODAL -->
    <div class="modal fade" id="viewModuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modern-modal">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="viewModuleTitle">
                        <i class="fas fa-info-circle me-2" style="color: var(--primary-color);"></i>
                        Détails du module
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewModuleContent">
                    <!-- Module details will be loaded here -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SETTINGS MODAL -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modern-modal">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-cog me-2" style="color: var(--primary-color);"></i>
                        Paramètres du module
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="settingsModalContent">
                    <!-- Settings form will be loaded here -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="saveSettingsBtn">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // ============================================
        // BACKEND AJAX INTEGRATION
        // ============================================
        
        // Configuration
        let currentView = 'grid';
        let currentModuleToDelete = null;
        let currentModuleToView = null;
        let currentModuleSettings = null;
        let currentPage = 1;
        let itemsPerPage = 9;
        let totalPlugins = 0;
        
        // API Routes
        const API = {
            getPlugins: '{{ route("modules.get-plugins") }}',
            getStats: '{{ route("modules.stats") }}',
            storePlugin: '{{ route("modules.store") }}',
            uploadPlugin: '{{ route("modules.upload") }}',
            updatePlugin: '{{ route("modules.update", "") }}',
            deletePlugin: '{{ route("modules.destroy", "") }}',
            activatePlugin: '{{ route("modules.activate", "") }}',
            deactivatePlugin: '{{ route("modules.deactivate", "") }}',
            getSettings: '{{ route("modules.settings.get", "") }}',
            updateSettings: '{{ route("modules.settings.update", "") }}',
            getCategories: '{{ route("modules.categories") }}',
        };
        
        let currentPluginData = [];
        let currentFilters = {
            category: '',
            status: '',
            type: '',
            price: '',
            sort_by: 'name',
            sort_order: 'asc',
            search: ''
        };
        
        // ============================================
        // INITIALIZATION
        // ============================================
        
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            setupFileUpload();
            loadPluginsFromBackend();
            loadStats();
            loadMarketplaceItems();
            
            // Add CSRF token meta tag if not present
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }
        });
        
        // Setup event listeners
        function setupEventListeners() {
            // View toggle
            document.getElementById('gridViewBtn').addEventListener('click', () => switchView('grid'));
            document.getElementById('listViewBtn').addEventListener('click', () => switchView('list'));
            
            // Search
            document.getElementById('searchInput').addEventListener('input', debounce(handleSearch, 300));
            
            // Category filters
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.addEventListener('click', () => filterByCategory(btn.dataset.category));
            });
            
            // Filter buttons
            document.getElementById('applyFiltersBtn').addEventListener('click', applyFilters);
            document.getElementById('clearFiltersBtn').addEventListener('click', clearFilters);
            document.getElementById('resetFiltersBtn').addEventListener('click', () => {
                clearFilters();
                document.getElementById('emptyState').style.display = 'none';
            });
            
            // Toggle filter section
            document.getElementById('toggleFilterBtn').addEventListener('click', toggleFilterSection);
            
            // Delete confirmation
            document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);
            
            // Save settings
            document.getElementById('saveSettingsBtn').addEventListener('click', saveSettings);
            
            // File upload
            document.getElementById('fileInput').addEventListener('change', handleFileSelect);
        }
        
        // ============================================
        // LOAD DATA FROM BACKEND
        // ============================================
        
        async function loadPluginsFromBackend() {
            showLoading();
            
            try {
                const params = new URLSearchParams({
                    ...currentFilters,
                    page: currentPage,
                    per_page: itemsPerPage
                });
                const response = await fetch(`${API.getPlugins}?${params}`);
                const result = await response.json();
                
                if (result.success) {
                    currentPluginData = result.data;
                    totalPlugins = result.total || result.data.length;
                    renderPlugins(currentPluginData);
                    updateStatsFromData(currentPluginData);
                    updateVisibleCount(currentPluginData.length);
                    renderPagination();
                    
                    // Show/hide empty state
                    if (currentPluginData.length === 0) {
                        document.getElementById('emptyState').style.display = 'block';
                        document.getElementById('modulesGridView').style.display = 'none';
                        document.getElementById('modulesListView').style.display = 'none';
                    } else {
                        document.getElementById('emptyState').style.display = 'none';
                        if (currentView === 'grid') {
                            document.getElementById('modulesGridView').style.display = 'grid';
                        } else {
                            document.getElementById('modulesListView').style.display = 'block';
                        }
                    }
                } else {
                    showAlert('danger', 'Erreur lors du chargement des modules');
                }
            } catch (error) {
                console.error('Error loading plugins:', error);
                showAlert('danger', 'Erreur de connexion au serveur');
            } finally {
                hideLoading();
            }
        }
        
        async function loadStats() {
            try {
                const response = await fetch(API.getStats);
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('totalModules').textContent = result.data.total;
                    document.getElementById('activeModules').textContent = result.data.active;
                    document.getElementById('inactiveModules').textContent = result.data.inactive;
                    document.getElementById('freeModules').textContent = result.data.free;
                    document.getElementById('updatesAvailable').textContent = result.data.updates_available;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }
        
        async function loadMarketplaceItems() {
            const marketplaceGrid = document.getElementById('marketplaceGrid');
            if (!marketplaceGrid) return;
            
            // Sample marketplace items - in production, this would come from an API
            const marketplaceItems = [
                { name: 'Analytics Pro', description: 'Statistiques avancées', price: '€49.99', rating: 4.8, icon: 'fas fa-chart-bar', gradient: 'linear-gradient(135deg, #6366f1, #8b5cf6)' },
                { name: 'Email Marketing Suite', description: 'Campagnes email automatisées', price: 'Gratuit', rating: 4.5, icon: 'fas fa-envelope', gradient: 'linear-gradient(135deg, #10b981, #059669)' },
                { name: 'Security Plus', description: 'Sécurité renforcée', price: '€29.99', rating: 4.9, icon: 'fas fa-shield-alt', gradient: 'linear-gradient(135deg, #f59e0b, #d97706)' },
                { name: 'E-commerce Essentials', description: 'Boutique en ligne', price: 'Gratuit', rating: 4.6, icon: 'fas fa-shopping-cart', gradient: 'linear-gradient(135deg, #3b82f6, #2563eb)' },
            ];
            
            marketplaceGrid.innerHTML = marketplaceItems.map(item => `
                <div class="marketplace-card">
                    <div class="marketplace-icon" style="background: ${item.gradient};">
                        <i class="${item.icon}"></i>
                    </div>
                    <div class="marketplace-info">
                        <h5>${item.name}</h5>
                        <p>${item.description}</p>
                        <div class="marketplace-meta">
                            <span class="price ${item.price === 'Gratuit' ? 'price-free' : ''}">${item.price}</span>
                            <span class="rating">
                                <i class="fas fa-star"></i>
                                ${item.rating}
                            </span>
                        </div>
                    </div>
                    <button class="btn-install" onclick="installMarketplaceItem('${item.name}')">Installer</button>
                </div>
            `).join('');
        }
        
        // ============================================
        // RENDER FUNCTIONS
        // ============================================
        
        function renderPlugins(plugins) {
            // Render grid view
            const gridContainer = document.getElementById('modulesGridView');
            gridContainer.innerHTML = plugins.map(plugin => renderPluginCard(plugin)).join('');
            
            // Render list view
            const listContainer = document.querySelector('#modulesListView tbody');
            listContainer.innerHTML = plugins.map(plugin => renderPluginRow(plugin)).join('');
            
            // Re-attach event listeners
            attachPluginEventListeners();
        }
        
        function renderPluginCard(plugin) {
            const categoryId = plugin.category_id || 0;
            const starRating = getStarRatingHTML(plugin.rating);
            const statusBadge = getStatusBadge(plugin.status);
            const typeBadge = getTypeBadge(plugin.type);
            const priceBadge = plugin.price_type === 'paid' ? '<span class="badge-paid">Payant</span>' : '';
            const actionButtons = getActionButtons(plugin);
            
            return `
                <div class="module-card" data-module-id="${plugin.id}" data-category="${categoryId}" data-status="${plugin.status}" data-type="${plugin.type}" data-price="${plugin.price_type}">
                    <div class="module-card-header">
                        <div class="module-icon" style="background: ${getIconGradient(plugin.type)};">
                            <i class="${plugin.icon || 'fas fa-puzzle-piece'}"></i>
                        </div>
                        <div class="module-badges">
                            ${typeBadge}
                            ${priceBadge}
                        </div>
                    </div>
                    
                    <div class="module-card-body">
                        <h4 class="module-name">${escapeHtml(plugin.name)}</h4>
                        <p class="module-description">${escapeHtml(plugin.description)}</p>
                        
                        <div class="module-meta">
                            <div class="meta-item">
                                <i class="fas fa-code-branch"></i>
                                <span>v${plugin.version}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>${escapeHtml(plugin.author)}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>${formatDate(plugin.installed_at)}</span>
                            </div>
                        </div>
                        
                        <div class="module-rating">
                            ${starRating}
                            <span class="rating-count">(${plugin.rating_count})</span>
                        </div>
                    </div>
                    
                    <div class="module-card-footer">
                        <div class="module-status">
                            ${statusBadge}
                        </div>
                        
                        <div class="module-actions">
                            ${actionButtons}
                        </div>
                    </div>
                </div>
            `;
        }
        
        function renderPluginRow(plugin) {
            const starRating = getStarRatingHTML(plugin.rating);
            const statusBadge = getStatusBadge(plugin.status);
            const typeBadge = plugin.type === 'official' ? '<span class="badge-official-sm">Officiel</span>' : 
                             (plugin.type === 'core' ? '<span class="badge-core-sm">Cœur</span>' : 
                             (plugin.type === 'custom' ? '<span class="badge-core-sm">Personnalisé</span>' : ''));
            const actionButtons = getActionButtons(plugin, 'list');
            
            return `
                <tr data-module-id="${plugin.id}">
                    <td>
                        <div class="list-module-info">
                            <div class="list-module-icon" style="background: ${getIconGradient(plugin.type)};">
                                <i class="${plugin.icon || 'fas fa-puzzle-piece'}"></i>
                            </div>
                            <div class="list-module-details">
                                <div class="list-module-name">
                                    ${escapeHtml(plugin.name)}
                                    ${typeBadge}
                                </div>
                                <div class="list-module-description">${escapeHtml(plugin.description.substring(0, 80))}...</div>
                            </div>
                        </div>
                    </td>
                    <td>v${plugin.version}</td>
                    <td>${escapeHtml(plugin.author)}</td>
                    <td>${formatDate(plugin.installed_at)}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="list-actions">
                            ${actionButtons}
                        </div>
                    </td>
                </tr>
            `;
        }
        
        function getActionButtons(plugin, view = 'grid') {
            const btnClass = view === 'grid' ? 'action-btn' : 'list-action-btn';
            
            if (plugin.type === 'core') {
                return `
                    <button class="${btnClass} deactivate-btn" disabled style="opacity: 0.5; cursor: not-allowed;" title="Module système">
                        <i class="fas fa-pause"></i>
                    </button>
                    <button class="${btnClass} view-btn" title="Voir détails" data-id="${plugin.id}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="${btnClass} settings-btn" title="Paramètres" data-id="${plugin.id}">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button class="${btnClass} delete-btn" disabled style="opacity: 0.5; cursor: not-allowed;" title="Module système">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            }
            
            if (plugin.status === 'active') {
                return `
                    <button class="${btnClass} deactivate-btn" title="Désactiver" data-id="${plugin.id}">
                        <i class="fas fa-pause"></i>
                    </button>
                    <button class="${btnClass} view-btn" title="Voir détails" data-id="${plugin.id}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="${btnClass} settings-btn" title="Paramètres" data-id="${plugin.id}">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button class="${btnClass} delete-btn" title="Désinstaller" data-id="${plugin.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            } else {
                return `
                    <button class="${btnClass} activate-btn" title="Activer" data-id="${plugin.id}">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="${btnClass} view-btn" title="Voir détails" data-id="${plugin.id}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="${btnClass} settings-btn" title="Paramètres" data-id="${plugin.id}">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button class="${btnClass} delete-btn" title="Désinstaller" data-id="${plugin.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            }
        }
        
        function renderPagination() {
            const totalPages = Math.ceil(totalPlugins / itemsPerPage);
            const paginationContainer = document.getElementById('pagination');
            
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }
            
            let paginationHtml = '';
            
            // Previous button
            paginationHtml += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <button class="page-link" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </li>
            `;
            
            // Page numbers
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                paginationHtml += `<li class="page-item"><button class="page-link" data-page="1">1</button></li>`;
                if (startPage > 2) {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `
                    <li class="page-item ${currentPage === i ? 'active' : ''}">
                        <button class="page-link" data-page="${i}">${i}</button>
                    </li>
                `;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                paginationHtml += `<li class="page-item"><button class="page-link" data-page="${totalPages}">${totalPages}</button></li>`;
            }
            
            // Next button
            paginationHtml += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <button class="page-link" data-page="${currentPage + 1}" ${currentPage === totalPages ? 'disabled' : ''}>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </li>
            `;
            
            paginationContainer.innerHTML = paginationHtml;
            
            // Add event listeners to pagination buttons
            document.querySelectorAll('#pagination .page-link[data-page]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const page = parseInt(btn.dataset.page);
                    if (!isNaN(page) && page !== currentPage && page >= 1 && page <= totalPages) {
                        currentPage = page;
                        loadPluginsFromBackend();
                    }
                });
            });
        }
        
        // ============================================
        // HELPER FUNCTIONS
        // ============================================
        
        function getStarRatingHTML(rating) {
            const fullStars = Math.floor(rating);
            const halfStar = (rating - fullStars) >= 0.5;
            const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
            
            let stars = '';
            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star star-filled"></i>';
            }
            if (halfStar) {
                stars += '<i class="fas fa-star-half-alt star-filled"></i>';
            }
            for (let i = 0; i < emptyStars; i++) {
                stars += '<i class="fas fa-star star-empty"></i>';
            }
            
            return stars;
        }
        
        function getStatusBadge(status) {
            const badges = {
                'active': '<span class="status-badge status-active"><i class="fas fa-circle"></i> Actif</span>',
                'inactive': '<span class="status-badge status-inactive"><i class="fas fa-circle"></i> Inactif</span>',
                'pending': '<span class="status-badge status-pending"><i class="fas fa-circle"></i> En attente</span>'
            };
            return badges[status] || badges['inactive'];
        }
        
        function getTypeBadge(type) {
            const badges = {
                'core': '<span class="badge-core">Cœur</span>',
                'official': '<span class="badge-official">Officiel</span>',
                'custom': '<span class="badge-core">Personnalisé</span>'
            };
            return badges[type] || '';
        }
        
        function getIconGradient(type) {
            const gradients = {
                'core': 'linear-gradient(135deg, #6366f1, #8b5cf6)',
                'official': 'linear-gradient(135deg, #10b981, #059669)',
                'third-party': 'linear-gradient(135deg, #f59e0b, #d97706)',
                'custom': 'linear-gradient(135deg, #3b82f6, #2563eb)'
            };
            return gradients[type] || 'linear-gradient(135deg, #6b7280, #4b5563)';
        }
        
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR');
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function updateStatsFromData(plugins) {
            // Stats are already loaded from dedicated endpoint
            // This is just for visual consistency
        }
        
        function updateVisibleCount(count) {
            document.getElementById('visibleModulesCount').textContent = count;
        }
        
        // ============================================
        // API CALLS
        // ============================================
        
        async function activatePlugin(pluginId) {
            showLoading();
            try {
                const response = await fetch(`${API.activatePlugin}/${pluginId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    await loadPluginsFromBackend();
                    await loadStats();
                } else {
                    showAlert('danger', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'Erreur lors de l\'activation');
            } finally {
                hideLoading();
            }
        }
        
        async function deactivatePlugin(pluginId) {
            showLoading();
            try {
                const response = await fetch(`${API.deactivatePlugin}/${pluginId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    await loadPluginsFromBackend();
                    await loadStats();
                } else {
                    showAlert('danger', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'Erreur lors de la désactivation');
            } finally {
                hideLoading();
            }
        }
        
        async function deletePlugin(pluginId) {
            showLoading();
            try {
                const response = await fetch(`${API.deletePlugin}/${pluginId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    await loadPluginsFromBackend();
                    await loadStats();
                } else {
                    showAlert('danger', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'Erreur lors de la désinstallation');
            } finally {
                hideLoading();
            }
        }
        
        async function uploadPluginFile(file) {
            showLoading();
            const formData = new FormData();
            formData.append('plugin_file', file);
            
            // Show progress bar
            const uploadArea = document.getElementById('uploadArea');
            const uploadProgress = document.getElementById('uploadProgress');
            uploadArea.style.display = 'none';
            uploadProgress.style.display = 'block';
            
            try {
                const response = await fetch(API.uploadPlugin, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    await loadPluginsFromBackend();
                    await loadStats();
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('installModuleModal'));
                    if (modal) modal.hide();
                } else {
                    showAlert('danger', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'Erreur lors de l\'upload');
            } finally {
                hideLoading();
                uploadArea.style.display = 'block';
                uploadProgress.style.display = 'none';
                document.querySelector('#uploadProgress .progress-bar').style.width = '0%';
            }
        }
        
        async function viewModuleDetails(pluginId) {
            const plugin = currentPluginData.find(p => p.id == pluginId);
            if (!plugin) return;
            
            const modalContent = document.getElementById('viewModuleContent');
            modalContent.innerHTML = `
                <div class="text-center mb-4">
                    <div class="module-icon-lg" style="background: ${getIconGradient(plugin.type)}; width: 80px; height: 80px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="${plugin.icon || 'fas fa-puzzle-piece'}" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 class="mt-3">${escapeHtml(plugin.name)}</h3>
                    <p class="text-muted">Version ${plugin.version} par ${escapeHtml(plugin.author)}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group">
                            <label>Description</label>
                            <p>${escapeHtml(plugin.description)}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <label>Informations</label>
                            <ul class="list-unstyled">
                                <li><strong>Type:</strong> ${plugin.type}</li>
                                <li><strong>Prix:</strong> ${plugin.price_type === 'paid' ? '€' + plugin.price : 'Gratuit'}</li>
                                <li><strong>Installé le:</strong> ${formatDate(plugin.installed_at)}</li>
                                <li><strong>Téléchargements:</strong> ${plugin.downloads || 0}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                ${plugin.documentation_url ? `
                <div class="text-center mt-3">
                    <a href="${plugin.documentation_url}" target="_blank" class="btn btn-outline-primary">
                        <i class="fas fa-book me-2"></i>Voir la documentation
                    </a>
                </div>
                ` : ''}
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('viewModuleModal'));
            modal.show();
        }
        
        async function loadModuleSettings(pluginId) {
            showLoading();
            try {
                const response = await fetch(`${API.getSettings}/${pluginId}`);
                const result = await response.json();
                
                if (result.success) {
                    currentModuleSettings = {
                        id: pluginId,
                        settings: result.data
                    };
                    
                    const modalContent = document.getElementById('settingsModalContent');
                    modalContent.innerHTML = `
                        <div class="settings-form">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Configurez les paramètres spécifiques de ce module.
                            </div>
                            <div id="settingsForm">
                                <!-- Settings fields will be populated based on module type -->
                                <div class="mb-3">
                                    <label class="form-label">Configuration JSON</label>
                                    <textarea class="form-control" id="settingsJson" rows="10">${JSON.stringify(result.data, null, 2)}</textarea>
                                    <small class="text-muted">Modifiez la configuration JSON du module</small>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    const modal = new bootstrap.Modal(document.getElementById('settingsModal'));
                    modal.show();
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'Erreur lors du chargement des paramètres');
            } finally {
                hideLoading();
            }
        }
        
        async function saveSettings() {
            if (!currentModuleSettings) return;
            
            const settingsJson = document.getElementById('settingsJson')?.value;
            if (!settingsJson) return;
            
            showLoading();
            try {
                let settings;
                try {
                    settings = JSON.parse(settingsJson);
                } catch (e) {
                    showAlert('danger', 'JSON invalide');
                    return;
                }
                
                const response = await fetch(`${API.updateSettings}/${currentModuleSettings.id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ settings })
                });
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('settingsModal'));
                    if (modal) modal.hide();
                } else {
                    showAlert('danger', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'Erreur lors de l\'enregistrement');
            } finally {
                hideLoading();
            }
        }
        
        // ============================================
        // EVENT HANDLERS
        // ============================================
        
        function attachPluginEventListeners() {
            // Activate buttons
            document.querySelectorAll('.activate-btn').forEach(btn => {
                btn.removeEventListener('click', handleActivate);
                btn.addEventListener('click', handleActivate);
            });
            
            // Deactivate buttons
            document.querySelectorAll('.deactivate-btn:not([disabled])').forEach(btn => {
                btn.removeEventListener('click', handleDeactivate);
                btn.addEventListener('click', handleDeactivate);
            });
            
            // Delete buttons
            document.querySelectorAll('.delete-btn:not([disabled])').forEach(btn => {
                btn.removeEventListener('click', handleDelete);
                btn.addEventListener('click', handleDelete);
            });
            
            // View buttons
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.removeEventListener('click', handleView);
                btn.addEventListener('click', handleView);
            });
            
            // Settings buttons
            document.querySelectorAll('.settings-btn:not([disabled])').forEach(btn => {
                btn.removeEventListener('click', handleSettings);
                btn.addEventListener('click', handleSettings);
            });
        }
        
        function handleActivate(e) {
            e.preventDefault();
            const btn = e.currentTarget;
            const pluginId = btn.dataset.id;
            if (pluginId) {
                activatePlugin(pluginId);
            }
        }
        
        function handleDeactivate(e) {
            e.preventDefault();
            const btn = e.currentTarget;
            const pluginId = btn.dataset.id;
            if (pluginId) {
                deactivatePlugin(pluginId);
            }
        }
        
        function handleDelete(e) {
            e.preventDefault();
            const btn = e.currentTarget;
            const pluginId = btn.dataset.id;
            if (pluginId) {
                if (confirm('Êtes-vous sûr de vouloir désinstaller ce module ? Cette action est irréversible.')) {
                    deletePlugin(pluginId);
                }
            }
        }
        
        function handleView(e) {
            e.preventDefault();
            const btn = e.currentTarget;
            const pluginId = btn.dataset.id;
            if (pluginId) {
                viewModuleDetails(pluginId);
            }
        }
        
        function handleSettings(e) {
            e.preventDefault();
            const btn = e.currentTarget;
            const pluginId = btn.dataset.id;
            if (pluginId) {
                loadModuleSettings(pluginId);
            }
        }
        
        // ============================================
        // UI CONTROLS
        // ============================================
        
        function switchView(view) {
            currentView = view;
            
            const gridView = document.getElementById('modulesGridView');
            const listView = document.getElementById('modulesListView');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');
            
            if (view === 'grid') {
                gridView.style.display = 'grid';
                listView.style.display = 'none';
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
            } else {
                gridView.style.display = 'none';
                listView.style.display = 'block';
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
            }
        }
        
        function handleSearch(e) {
            currentFilters.search = e.target.value.toLowerCase();
            currentPage = 1;
            loadPluginsFromBackend();
        }
        
        function filterByCategory(categoryId) {
            // Update active state
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.category === categoryId);
            });
            
            currentFilters.category = categoryId === 'all' ? '' : categoryId;
            currentPage = 1;
            loadPluginsFromBackend();
        }
        
        function applyFilters() {
            currentFilters.category = document.getElementById('filterCategory').value;
            currentFilters.status = document.getElementById('filterStatus').value;
            currentFilters.type = document.getElementById('filterType').value;
            currentFilters.price = document.getElementById('filterPrice').value;
            currentFilters.sort_by = document.getElementById('filterSortBy').value;
            currentPage = 1;
            
            loadPluginsFromBackend();
        }
        
        function clearFilters() {
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterPrice').value = '';
            document.getElementById('filterSortBy').value = 'name';
            
            currentFilters = {
                category: '',
                status: '',
                type: '',
                price: '',
                sort_by: 'name',
                sort_order: 'asc',
                search: currentFilters.search || ''
            };
            currentPage = 1;
            
            // Reset category filters
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.category === 'all');
            });
            
            loadPluginsFromBackend();
        }
        
        function toggleFilterSection() {
            const filterSection = document.getElementById('filterSection');
            const btn = document.getElementById('toggleFilterBtn');
            
            if (filterSection.style.display === 'none') {
                filterSection.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-times me-2"></i>Masquer les filtres';
            } else {
                filterSection.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-sliders-h me-2"></i>Filtres';
            }
        }
        
        // ============================================
        // FILE UPLOAD
        // ============================================
        
        function setupFileUpload() {
            const uploadArea = document.getElementById('uploadArea');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });
            
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });
            
            uploadArea.addEventListener('drop', handleDrop, false);
        }
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight() {
            document.getElementById('uploadArea').classList.add('highlight');
        }
        
        function unhighlight() {
            document.getElementById('uploadArea').classList.remove('highlight');
        }
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }
        
        function handleFileSelect(e) {
            const files = e.target.files;
            handleFiles(files);
        }
        
        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                if (file.name.endsWith('.zip')) {
                    uploadPluginFile(file);
                } else {
                    showAlert('danger', 'Veuillez sélectionner un fichier ZIP valide.');
                }
            }
        }
        
        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        function showLoading() {
            document.getElementById('loadingSpinner').style.display = 'flex';
        }
        
        function hideLoading() {
            document.getElementById('loadingSpinner').style.display = 'none';
        }
        
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.style.minWidth = '300px';
            alertDiv.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
        
        // Global functions for onclick handlers
        window.activateModule = function(moduleId) {
            activatePlugin(moduleId);
        };
        
        window.deactivateModule = function(moduleId) {
            deactivatePlugin(moduleId);
        };
        
        window.showDeleteConfirmation = function(moduleId) {
            if (confirm('Êtes-vous sûr de vouloir désinstaller ce module ? Cette action est irréversible.')) {
                deletePlugin(moduleId);
            }
        };
        
        window.openModuleSettings = function(moduleId) {
            loadModuleSettings(moduleId);
        };
        
        window.installMarketplaceItem = function(moduleName) {
            showAlert('info', `Installation de ${moduleName} en cours...`);
        };
        
        function confirmDelete() {
            if (currentModuleToDelete) {
                deletePlugin(currentModuleToDelete);
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                if (modal) modal.hide();
            }
        }
    </script>

    <style>
        /* Modules Grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }
        
        /* Module Card */
        .module-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #eaeaea;
            display: flex;
            flex-direction: column;
        }
        
        .module-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            border-color: transparent;
        }
        
        .module-card-header {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .module-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .module-badges {
            display: flex;
            gap: 6px;
        }
        
        .badge-core {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-official {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-paid {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-core-sm, .badge-official-sm {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.6rem;
            font-weight: 600;
            margin-left: 6px;
            vertical-align: middle;
        }
        
        .badge-core-sm {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
        }
        
        .badge-official-sm {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .module-card-body {
            padding: 20px;
            flex: 1;
        }
        
        .module-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .module-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 15px;
            min-height: 60px;
        }
        
        .module-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #666;
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 20px;
        }
        
        .meta-item i {
            color: var(--primary-color);
            font-size: 0.8rem;
        }
        
        .module-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 10px;
        }
        
        .star-filled {
            color: #ffd700;
        }
        
        .star-empty {
            color: #d1d5db;
        }
        
        .rating-count {
            color: #666;
            font-size: 0.8rem;
            margin-left: 6px;
        }
        
        .module-card-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }
        
        .status-active i {
            color: #10b981;
            font-size: 0.6rem;
        }
        
        .status-inactive {
            background: rgba(107, 114, 128, 0.1);
            color: #4b5563;
        }
        
        .status-inactive i {
            color: #6b7280;
            font-size: 0.6rem;
        }
        
        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }
        
        .status-pending i {
            color: #f59e0b;
            font-size: 0.6rem;
        }
        
        .module-actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            color: #666;
            border: 1px solid #eaeaea;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .activate-btn:hover {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }
        
        .deactivate-btn:hover {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }
        
        .view-btn:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .settings-btn:hover {
            background: #6b7280;
            border-color: #6b7280;
            color: white;
        }
        
        .delete-btn:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }
        
        /* Search Bar */
        .search-bar-modern {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 400px;
        }
        
        .search-input-modern {
            width: 100%;
            height: 40px;
            padding: 0 15px 0 40px;
            border: 1px solid #eaeaea;
            border-radius: 30px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .search-input-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .search-wrapper .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .view-options {
            display: flex;
            gap: 8px;
        }
        
        .view-option {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            background: white;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .view-option:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }
        
        .view-option.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Categories Navigation */
        .categories-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        
        .category-filter {
            padding: 8px 16px;
            border-radius: 30px;
            border: 1px solid #eaeaea;
            background: white;
            color: #666;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .category-filter:hover {
            background: #f8f9fa;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .category-filter.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* List View */
        .modules-list {
            overflow-x: auto;
        }
        
        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .modern-table th {
            text-align: left;
            padding: 15px;
            background: #f8f9fa;
            color: #666;
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 2px solid #eaeaea;
        }
        
        .modern-table td {
            padding: 15px;
            border-bottom: 1px solid #eaeaea;
            vertical-align: middle;
        }
        
        .modern-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .list-module-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .list-module-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .list-module-details {
            display: flex;
            flex-direction: column;
        }
        
        .list-module-name {
            font-weight: 500;
            color: #333;
            margin-bottom: 2px;
        }
        
        .list-module-description {
            font-size: 0.8rem;
            color: #666;
        }
        
        .version-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .current-version {
            font-weight: 500;
        }
        
        .list-actions {
            display: flex;
            gap: 6px;
            justify-content: center;
        }
        
        .list-action-btn {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #666;
            text-decoration: none;
        }
        
        .list-action-btn:hover {
            transform: translateY(-2px);
        }
        
        /* Install Modal */
        .modern-modal .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }
        
        .upload-section {
            margin-bottom: 30px;
        }
        
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .upload-area.highlight {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.05);
        }
        
        .upload-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .upload-hint {
            color: #999;
            font-size: 0.8rem;
            margin-top: 15px;
        }
        
        .upload-progress {
            margin-top: 20px;
        }
        
        .separator {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .separator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #eaeaea;
            z-index: 1;
        }
        
        .separator span {
            background: white;
            padding: 0 15px;
            color: #999;
            font-size: 0.9rem;
            position: relative;
            z-index: 2;
        }
        
        .marketplace-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        
        .marketplace-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }
        
        .marketplace-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            border: 1px solid #eaeaea;
        }
        
        .marketplace-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        }
        
        .marketplace-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .marketplace-info {
            flex: 1;
        }
        
        .marketplace-info h5 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .marketplace-info p {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 6px;
        }
        
        .marketplace-meta {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .price {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        .price-free {
            color: #10b981;
        }
        
        .rating {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #ffd700;
            font-size: 0.8rem;
        }
        
        .btn-install {
            background: white;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .btn-install:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .view-more-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .view-more-link:hover {
            text-decoration: underline;
        }
        
        /* Empty State */
        .empty-state-modern {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon-modern {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }
        
        .empty-title-modern {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .empty-text-modern {
            color: #666;
            margin-bottom: 20px;
        }
        
        /* Pagination */
        .pagination-container-modern {
            margin-top: 20px;
            padding: 20px;
            border-top: 1px solid #eaeaea;
        }
        
        .pagination {
            margin-bottom: 0;
        }
        
        .page-link {
            color: var(--primary-color);
            border: 1px solid #eaeaea;
            margin: 0 3px;
            border-radius: 8px !important;
            cursor: pointer;
        }
        
        .page-link:hover {
            background: #f8f9fa;
            color: var(--primary-color);
            border-color: #eaeaea;
        }
        
        .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stats-card-modern {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #eaeaea;
        }
        
        .stats-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stats-value-modern {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            line-height: 1.2;
        }
        
        .stats-label-modern {
            color: #666;
            font-size: 0.9rem;
        }
        
        .stats-icon-modern {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        /* Main Card */
        .main-card-modern {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
            overflow: hidden;
        }
        
        .card-header-modern {
            padding: 20px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title-modern {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .modules-count {
            color: #666;
            font-size: 0.9rem;
        }
        
        .card-body-modern {
            padding: 20px;
        }
        
        /* Filter Section */
        .filter-section-modern {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #eaeaea;
        }
        
        .filter-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .filter-title-modern {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .filter-actions-modern {
            display: flex;
            gap: 10px;
        }
        
        .form-label-modern {
            font-size: 0.85rem;
            font-weight: 500;
            color: #666;
            margin-bottom: 5px;
            display: block;
        }
        
        .form-select-modern {
            width: 100%;
            height: 40px;
            padding: 0 15px;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #333;
            background: white;
            cursor: pointer;
        }
        
        .form-select-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        /* Floating Action Button */
        .fab-modern {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .fab-modern:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.5);
        }
        
        /* Loading Spinner */
        .spinner-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
        }
        
        /* Info Group */
        .info-group {
            margin-bottom: 20px;
        }
        
        .info-group label {
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }
        
        .info-group p {
            color: #666;
            line-height: 1.5;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .modules-grid {
                grid-template-columns: 1fr;
            }
            
            .search-bar-modern {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-wrapper {
                max-width: 100%;
            }
            
            .categories-nav {
                justify-content: center;
            }
            
            .marketplace-grid {
                grid-template-columns: 1fr;
            }
            
            .list-module-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .modern-table {
                font-size: 0.85rem;
            }
            
            .modern-table td, .modern-table th {
                padding: 10px;
            }
            
            .list-actions {
                flex-wrap: wrap;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-section-modern .row > div {
                margin-bottom: 10px;
            }
            
            .fab-modern {
                bottom: 20px;
                right: 20px;
                width: 48px;
                height: 48px;
                font-size: 1rem;
            }
        }
    </style>
@endsection