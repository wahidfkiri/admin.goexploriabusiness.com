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
                        <option value="popularity">Popularité</option>
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
                        <div class="stats-value-modern" id="totalModules">{{ $totalModules ?? 0 }}</div>
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
                        <div class="stats-value-modern" id="activeModules">{{ $activeModules ?? 0 }}</div>
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
                        <div class="stats-value-modern" id="inactiveModules">{{ $inactiveModules ?? 0 }}</div>
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
                        <div class="stats-value-modern" id="updatesAvailable">{{ $updatesAvailable ?? 0 }}</div>
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
                        <div class="stats-value-modern" id="freeModules">{{ $freeModules ?? 0 }}</div>
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
                    <span id="visibleModulesCount">{{ count($modules) }}</span> modules
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
                    @forelse($modules as $module)
                        <div class="module-card" data-module-id="{{ $module->id }}" data-category="{{ $module->category_id }}" data-status="{{ $module->status }}" data-type="{{ $module->type }}" data-price="{{ $module->is_free ? 'free' : 'paid' }}">
                            <div class="module-card-header">
                                <div class="module-icon" style="background: {{ $module->icon_bg ?? getModuleColor($module->name) }};">
                                    <i class="{{ $module->icon ?? 'fas fa-puzzle-piece' }}"></i>
                                </div>
                                <div class="module-badges">
                                    @if($module->is_core)
                                        <span class="badge-core">Cœur</span>
                                    @endif
                                    @if($module->is_official)
                                        <span class="badge-official">Officiel</span>
                                    @endif
                                    @if(!$module->is_free)
                                        <span class="badge-paid">Payant</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="module-card-body">
                                <h4 class="module-name">{{ $module->name }}</h4>
                                <p class="module-description">{{ $module->description }}</p>
                                
                                <div class="module-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-code-branch"></i>
                                        <span>v{{ $module->version }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $module->author }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $module->installed_at?->format('d/m/Y') ?? 'Non installé' }}</span>
                                    </div>
                                </div>
                                
                                @if($module->rating)
                                <div class="module-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $module->rating ? 'star-filled' : 'star-empty' }}"></i>
                                    @endfor
                                    <span class="rating-count">({{ $module->rating_count ?? 0 }})</span>
                                </div>
                                @endif
                                
                                @if($module->has_update)
                                <div class="update-available">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Mise à jour v{{ $module->latest_version }} disponible</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="module-card-footer">
                                <div class="module-status">
                                    @if($module->status === 'active')
                                        <span class="status-badge status-active">
                                            <i class="fas fa-circle"></i> Actif
                                        </span>
                                    @elseif($module->status === 'inactive')
                                        <span class="status-badge status-inactive">
                                            <i class="fas fa-circle"></i> Inactif
                                        </span>
                                    @else
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-circle"></i> En attente
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="module-actions">
                                    @if($module->status === 'active')
                                        <button class="action-btn deactivate-btn" onclick="deactivateModule({{ $module->id }})" title="Désactiver">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    @else
                                        <button class="action-btn activate-btn" onclick="activateModule({{ $module->id }})" title="Activer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('modules.show', $module->id) }}" class="action-btn view-btn" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <button class="action-btn settings-btn" onclick="openModuleSettings({{ $module->id }})" title="Paramètres">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    
                                    <button class="action-btn delete-btn" onclick="showDeleteConfirmation({{ $module->id }})" title="Désinstaller">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-modern">
                            <div class="empty-icon-modern">
                                <i class="fas fa-puzzle-piece"></i>
                            </div>
                            <h3 class="empty-title-modern">Aucun module installé</h3>
                            <p class="empty-text-modern">Commencez par installer votre premier module.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#installModuleModal">
                                <i class="fas fa-download me-2"></i>Installer un module
                            </button>
                        </div>
                    @endforelse
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
                            @foreach($modules as $module)
                                <tr data-module-id="{{ $module->id }}">
                                    <td>
                                        <div class="list-module-info">
                                            <div class="list-module-icon" style="background: {{ $module->icon_bg ?? getModuleColor($module->name) }};">
                                                <i class="{{ $module->icon ?? 'fas fa-puzzle-piece' }}"></i>
                                            </div>
                                            <div class="list-module-details">
                                                <div class="list-module-name">
                                                    {{ $module->name }}
                                                    @if($module->is_core)
                                                        <span class="badge-core-sm">Cœur</span>
                                                    @endif
                                                    @if($module->is_official)
                                                        <span class="badge-official-sm">Officiel</span>
                                                    @endif
                                                </div>
                                                <div class="list-module-description">{{ Str::limit($module->description, 60) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="version-info">
                                            <span class="current-version">v{{ $module->version }}</span>
                                            @if($module->has_update)
                                                <span class="update-indicator" title="Mise à jour disponible">
                                                    <i class="fas fa-arrow-up"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $module->author }}</td>
                                    <td>{{ $module->installed_at?->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td>
                                        @if($module->status === 'active')
                                            <span class="status-badge status-active">
                                                <i class="fas fa-circle"></i> Actif
                                            </span>
                                        @elseif($module->status === 'inactive')
                                            <span class="status-badge status-inactive">
                                                <i class="fas fa-circle"></i> Inactif
                                            </span>
                                        @else
                                            <span class="status-badge status-pending">
                                                <i class="fas fa-circle"></i> En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="list-actions">
                                            @if($module->status === 'active')
                                                <button class="list-action-btn deactivate-btn" onclick="deactivateModule({{ $module->id }})" title="Désactiver">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            @else
                                                <button class="list-action-btn activate-btn" onclick="activateModule({{ $module->id }})" title="Activer">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            @endif
                                            
                                            <a href="{{ route('modules.show', $module->id) }}" class="list-action-btn view-btn" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <button class="list-action-btn settings-btn" onclick="openModuleSettings({{ $module->id }})" title="Paramètres">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            
                                            @if(!$module->is_core)
                                            <button class="list-action-btn delete-btn" onclick="showDeleteConfirmation({{ $module->id }})" title="Désinstaller">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container-modern" id="paginationContainer">
                {{ $modules->links('pagination::bootstrap-5') }}
            </div>
        </div>
        
        <!-- Category Navigation -->
        <div class="categories-nav">
            <button class="category-filter active" data-category="all">
                <i class="fas fa-th-large me-2"></i>Tous
            </button>
            @foreach($categories as $category)
                <button class="category-filter" data-category="{{ $category->id }}">
                    <i class="{{ $category->icon ?? 'fas fa-folder' }} me-2"></i>{{ $category->name }}
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
                        
                        <div class="marketplace-grid">
                            <div class="marketplace-card">
                                <div class="marketplace-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="marketplace-info">
                                    <h5>Analytics Pro</h5>
                                    <p>Statistiques avancées et rapports personnalisés</p>
                                    <div class="marketplace-meta">
                                        <span class="price">€49.99</span>
                                        <span class="rating">
                                            <i class="fas fa-star"></i>
                                            4.8
                                        </span>
                                    </div>
                                </div>
                                <button class="btn-install">Installer</button>
                            </div>
                            
                            <div class="marketplace-card">
                                <div class="marketplace-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="marketplace-info">
                                    <h5>Email Marketing Suite</h5>
                                    <p>Campagnes email et newsletters automatisées</p>
                                    <div class="marketplace-meta">
                                        <span class="price price-free">Gratuit</span>
                                        <span class="rating">
                                            <i class="fas fa-star"></i>
                                            4.5
                                        </span>
                                    </div>
                                </div>
                                <button class="btn-install">Installer</button>
                            </div>
                            
                            <div class="marketplace-card">
                                <div class="marketplace-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="marketplace-info">
                                    <h5>Security Plus</h5>
                                    <p>Sécurité renforcée et pare-feu</p>
                                    <div class="marketplace-meta">
                                        <span class="price">€29.99</span>
                                        <span class="rating">
                                            <i class="fas fa-star"></i>
                                            4.9
                                        </span>
                                    </div>
                                </div>
                                <button class="btn-install">Installer</button>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="#" class="view-more-link">
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

    <!-- JavaScript -->
    <script>
        // Configuration
        let currentView = 'grid'; // 'grid' or 'list'
        let modules = @json($modules);
        let currentModuleToDelete = null;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            initializeFilters();
            setupFileUpload();
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
            
            // Toggle filter section
            document.getElementById('toggleFilterBtn').addEventListener('click', toggleFilterSection);
            
            // Delete confirmation
            document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);
            
            // File upload
            document.getElementById('fileInput').addEventListener('change', handleFileSelect);
        }
        
        // Switch between grid and list view
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
        
        // Handle search with debounce
        function handleSearch(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            document.querySelectorAll('.module-card').forEach(card => {
                const moduleName = card.querySelector('.module-name').textContent.toLowerCase();
                const moduleDesc = card.querySelector('.module-description').textContent.toLowerCase();
                
                if (moduleName.includes(searchTerm) || moduleDesc.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            updateVisibleCount();
        }
        
        // Filter by category
        function filterByCategory(categoryId) {
            // Update active state
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.category === categoryId);
            });
            
            // Filter cards
            document.querySelectorAll('.module-card').forEach(card => {
                if (categoryId === 'all' || card.dataset.category === categoryId) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            updateVisibleCount();
        }
        
        // Apply all filters
        function applyFilters() {
            const category = document.getElementById('filterCategory').value;
            const status = document.getElementById('filterStatus').value;
            const type = document.getElementById('filterType').value;
            const price = document.getElementById('filterPrice').value;
            
            document.querySelectorAll('.module-card').forEach(card => {
                let show = true;
                
                if (category && card.dataset.category !== category) show = false;
                if (status && card.dataset.status !== status) show = false;
                if (type && card.dataset.type !== type) show = false;
                if (price && card.dataset.price !== price) show = false;
                
                card.style.display = show ? 'block' : 'none';
            });
            
            updateVisibleCount();
        }
        
        // Clear all filters
        function clearFilters() {
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterPrice').value = '';
            document.getElementById('filterSortBy').value = 'name';
            
            // Show all cards
            document.querySelectorAll('.module-card').forEach(card => {
                card.style.display = 'block';
            });
            
            // Reset category filters
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.category === 'all');
            });
            
            updateVisibleCount();
        }
        
        // Update visible modules count
        function updateVisibleCount() {
            const visibleCount = document.querySelectorAll('.module-card[style="display: block"]').length;
            document.getElementById('visibleModulesCount').textContent = visibleCount;
        }
        
        // Toggle filter section
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
        
        // Initialize filters
        function initializeFilters() {
            // Any initialization logic
        }
        
        // Debounce utility
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
        
        // Setup file upload drag & drop
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
                    uploadModule(file);
                } else {
                    showAlert('danger', 'Veuillez sélectionner un fichier ZIP valide.');
                }
            }
        }
        
        function uploadModule(file) {
            const formData = new FormData();
            formData.append('module', file);
            
            showLoading();
            
            fetch('{{ route("modules.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showAlert('success', 'Module installé avec succès !');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert('danger', data.message || 'Erreur lors de l\'installation.');
                }
            })
            .catch(error => {
                hideLoading();
                showAlert('danger', 'Erreur de connexion au serveur.');
            });
        }
        
        // Module actions
        function activateModule(moduleId) {
            const module = modules.find(m => m.id === moduleId);
            if (!module) return;
            
            showLoading();
            
            fetch(`{{ url("modules") }}/${moduleId}/activate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showAlert('success', `Module "${module.name}" activé avec succès !`);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message || 'Erreur lors de l\'activation.');
                }
            })
            .catch(error => {
                hideLoading();
                showAlert('danger', 'Erreur de connexion au serveur.');
            });
        }
        
        function deactivateModule(moduleId) {
            const module = modules.find(m => m.id === moduleId);
            if (!module) return;
            
            if (!confirm(`Voulez-vous vraiment désactiver le module "${module.name}" ?`)) {
                return;
            }
            
            showLoading();
            
            fetch(`{{ url("modules") }}/${moduleId}/deactivate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showAlert('success', `Module "${module.name}" désactivé.`);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message || 'Erreur lors de la désactivation.');
                }
            })
            .catch(error => {
                hideLoading();
                showAlert('danger', 'Erreur de connexion au serveur.');
            });
        }
        
        function openModuleSettings(moduleId) {
            window.location.href = `{{ url("modules") }}/${moduleId}/settings`;
        }
        
        function showDeleteConfirmation(moduleId) {
            const module = modules.find(m => m.id === moduleId);
            if (!module) return;
            
            currentModuleToDelete = module;
            
            const infoDiv = document.getElementById('moduleToDeleteInfo');
            infoDiv.innerHTML = `
                <div class="module-info">
                    <div class="module-info-icon" style="background: ${module.icon_bg || getModuleColor(module.name)};">
                        <i class="${module.icon || 'fas fa-puzzle-piece'}"></i>
                    </div>
                    <div>
                        <div class="module-info-name">${module.name}</div>
                        <div class="module-info-details">
                            <div><strong>Version:</strong> ${module.version}</div>
                            <div><strong>Auteur:</strong> ${module.author}</div>
                            <div><strong>Installé le:</strong> ${module.installed_at || 'N/A'}</div>
                        </div>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            modal.show();
        }
        
        function confirmDelete() {
            if (!currentModuleToDelete) return;
            
            const moduleId = currentModuleToDelete.id;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            
            deleteBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-trash me-2"></i>Désinstaller
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Désinstallation...</span>
                </div>
                Désinstallation en cours...
            `;
            deleteBtn.disabled = true;
            
            fetch(`{{ url("modules") }}/${moduleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                modal.hide();
                
                if (data.success) {
                    showAlert('success', data.message || 'Module désinstallé avec succès !');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message || 'Erreur lors de la désinstallation.');
                }
            })
            .catch(error => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                modal.hide();
                showAlert('danger', 'Erreur de connexion au serveur.');
            })
            .finally(() => {
                deleteBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-trash me-2"></i>Désinstaller
                    </span>
                `;
                deleteBtn.disabled = false;
                currentModuleToDelete = null;
            });
        }
        
        // Helper functions
        function getModuleColor(moduleName) {
            let hash = 0;
            for (let i = 0; i < moduleName.length; i++) {
                hash = moduleName.charCodeAt(i) + ((hash << 5) - hash);
            }
            
            const colors = [
                'linear-gradient(135deg, #6366f1, #8b5cf6)',
                'linear-gradient(135deg, #10b981, #059669)',
                'linear-gradient(135deg, #f59e0b, #d97706)',
                'linear-gradient(135deg, #ef4444, #dc2626)',
                'linear-gradient(135deg, #3b82f6, #2563eb)',
                'linear-gradient(135deg, #8b5cf6, #6d28d9)',
                'linear-gradient(135deg, #ec4899, #db2777)',
                'linear-gradient(135deg, #14b8a6, #0d9488)'
            ];
            
            return colors[Math.abs(hash) % colors.length];
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
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
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
        
        .update-available {
            background: #fff3cd;
            color: #856404;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }
        
        .update-available i {
            color: #f59e0b;
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
        
        .update-indicator {
            color: #f59e0b;
            cursor: pointer;
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
        
        /* Module info in delete modal */
        .module-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .module-info-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .module-info-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 5px;
        }
        
        .module-info-details {
            font-size: 0.9rem;
            color: #666;
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
        }
    </style>
@endsection