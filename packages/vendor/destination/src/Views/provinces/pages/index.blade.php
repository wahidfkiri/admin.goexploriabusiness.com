@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-file-alt"></i></span>
                Gestion des Pages
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPageModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Page
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
                <div class="col-md-4">
                    <label for="filterType" class="form-label-modern">Type de destination</label>
                    <select class="form-select-modern" id="filterType">
                        <option value="">Tous les types</option>
                        <option value="continent">Continent</option>
                        <option value="country">Pays</option>
                        <option value="region">Région</option>
                        <option value="province">Province</option>
                        <option value="city">Ville</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="created_at">Date de création</option>
                        <option value="updated_at">Dernière modification</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalPages">0</div>
                        <div class="stats-label-modern">Total Pages</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activePages">0</div>
                        <div class="stats-label-modern">Pages Actives</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="pagesByType">0</div>
                        <div class="stats-label-modern">Types différents</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="recentPages">0</div>
                        <div class="stats-label-modern">Ajoutés ce mois</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Pages</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une page..." id="searchInput">
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
                                <th>Page</th>
                                <th>Type</th>
                                <th>Destination</th>
                                <th>Slug</th>
                                <th>Statut</th>
                                <th>Dernière modification</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pagesTableBody">
                            <!-- Pages will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune page trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première page.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPageModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer une page
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
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createPageModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE PAGE MODAL -->
    <div class="modal fade" id="createPageModal" tabindex="-1" aria-labelledby="createPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createPageModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle page
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createPageForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pageName" class="form-label-modern">Nom de la page *</label>
                                <input type="text" class="form-control-modern" id="pageName" name="name" 
                                       placeholder="Ex: Page d'accueil, À propos..." required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pageSlug" class="form-label-modern">Slug *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control-modern" id="pageSlug" name="slug" 
                                           placeholder="Ex: accueil, a-propos, contact" required>
                                    <button type="button" class="btn btn-outline-secondary" id="generateSlugBtn">
                                        <i class="fas fa-magic"></i> Générer
                                    </button>
                                </div>
                                <div class="form-text-modern">URL de la page (auto-généré si vide)</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pageType" class="form-label-modern">Type de destination *</label>
                                <select class="form-select-modern" id="pageType" name="pageable_type" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="continent">Continent</option>
                                    <option value="country">Pays</option>
                                    <option value="region">Région</option>
                                    <option value="province">Province</option>
                                    <option value="city">Ville</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pageDestination" class="form-label-modern">Destination *</label>
                                <select class="form-select-modern" id="pageDestination" name="pageable_id" required disabled>
                                    <option value="">Sélectionnez d'abord un type</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="pageIsActive" name="is_active" checked>
                                <label class="form-check-label" for="pageIsActive">Page active</label>
                            </div>
                        </div>
                        
                        <!-- Tab Navigation for HTML/CSS -->
                        <ul class="nav nav-tabs-modern" id="pageContentTabs" role="tablist">
                            <li class="nav-item-modern" role="presentation">
                                <button class="nav-link-modern active" id="html-tab" data-bs-toggle="tab" 
                                        data-bs-target="#html-content" type="button" role="tab">
                                    <i class="fas fa-code me-2"></i>Contenu HTML
                                </button>
                            </li>
                            <li class="nav-item-modern" role="presentation">
                                <button class="nav-link-modern" id="css-tab" data-bs-toggle="tab" 
                                        data-bs-target="#css-content" type="button" role="tab">
                                    <i class="fas fa-paint-brush me-2"></i>Styles CSS
                                </button>
                            </li>
                            <li class="nav-item-modern" role="presentation">
                                <button class="nav-link-modern" id="preview-tab" data-bs-toggle="tab" 
                                        data-bs-target="#preview-content" type="button" role="tab">
                                    <i class="fas fa-eye me-2"></i>Aperçu
                                </button>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content-modern" id="pageContentTabsContent">
                            <!-- HTML Content Tab -->
                            <div class="tab-pane-modern fade show active" id="html-content" role="tabpanel">
                                <label class="form-label-modern">Contenu HTML</label>
                                <textarea class="form-control-modern code-editor" id="pageHtmlContent" 
                                          name="html_content" rows="15" placeholder="Contenu HTML..."></textarea>
                            </div>
                            
                            <!-- CSS Content Tab -->
                            <div class="tab-pane-modern fade" id="css-content" role="tabpanel">
                                <label class="form-label-modern">Styles CSS</label>
                                <textarea class="form-control-modern code-editor" id="pageCssContent" 
                                          name="css_content" rows="15" placeholder="Styles CSS..."></textarea>
                            </div>
                            
                            <!-- Preview Tab -->
                            <div class="tab-pane-modern fade" id="preview-content" role="tabpanel">
                                <label class="form-label-modern">Aperçu de la page</label>
                                <div id="pagePreview" class="preview-container">
                                    <div class="preview-placeholder">
                                        <i class="fas fa-eye fa-3x text-muted mb-3"></i>
                                        <p>Aperçu de la page s'affichera ici</p>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="refreshPreviewBtn">
                                            <i class="fas fa-sync me-1"></i> Rafraîchir l'aperçu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitPageBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la page
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT PAGE MODAL -->
    <div class="modal fade" id="editPageModal" tabindex="-1" aria-labelledby="editPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editPageModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier la page
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editPageForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editPageId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editPageName" class="form-label-modern">Nom de la page *</label>
                                <input type="text" class="form-control-modern" id="editPageName" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editPageSlug" class="form-label-modern">Slug *</label>
                                <input type="text" class="form-control-modern" id="editPageSlug" name="slug" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editPageType" class="form-label-modern">Type de destination</label>
                                <select class="form-select-modern" id="editPageType" name="pageable_type" disabled>
                                    <option value="">Type</option>
                                </select>
                                <div class="form-text-modern">Le type de destination ne peut pas être modifié</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editPageDestination" class="form-label-modern">Destination</label>
                                <select class="form-select-modern" id="editPageDestination" name="pageable_id" disabled>
                                    <option value="">Destination</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editPageIsActive" name="is_active">
                                <label class="form-check-label" for="editPageIsActive">Page active</label>
                            </div>
                        </div>
                        
                        <!-- Tab Navigation for HTML/CSS -->
                        <ul class="nav nav-tabs-modern" id="editPageContentTabs" role="tablist">
                            <li class="nav-item-modern" role="presentation">
                                <button class="nav-link-modern active" id="edit-html-tab" data-bs-toggle="tab" 
                                        data-bs-target="#edit-html-content" type="button" role="tab">
                                    <i class="fas fa-code me-2"></i>Contenu HTML
                                </button>
                            </li>
                            <li class="nav-item-modern" role="presentation">
                                <button class="nav-link-modern" id="edit-css-tab" data-bs-toggle="tab" 
                                        data-bs-target="#edit-css-content" type="button" role="tab">
                                    <i class="fas fa-paint-brush me-2"></i>Styles CSS
                                </button>
                            </li>
                            <li class="nav-item-modern" role="presentation">
                                <button class="nav-link-modern" id="edit-preview-tab" data-bs-toggle="tab" 
                                        data-bs-target="#edit-preview-content" type="button" role="tab">
                                    <i class="fas fa-eye me-2"></i>Aperçu
                                </button>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content-modern" id="editPageContentTabsContent">
                            <!-- HTML Content Tab -->
                            <div class="tab-pane-modern fade show active" id="edit-html-content" role="tabpanel">
                                <label class="form-label-modern">Contenu HTML</label>
                                <textarea class="form-control-modern code-editor" id="editPageHtmlContent" 
                                          name="html_content" rows="15"></textarea>
                            </div>
                            
                            <!-- CSS Content Tab -->
                            <div class="tab-pane-modern fade" id="edit-css-content" role="tabpanel">
                                <label class="form-label-modern">Styles CSS</label>
                                <textarea class="form-control-modern code-editor" id="editPageCssContent" 
                                          name="css_content" rows="15"></textarea>
                            </div>
                            
                            <!-- Preview Tab -->
                            <div class="tab-pane-modern fade" id="edit-preview-content" role="tabpanel">
                                <label class="form-label-modern">Aperçu de la page</label>
                                <div id="editPagePreview" class="preview-container">
                                    <div class="preview-placeholder">
                                        <i class="fas fa-eye fa-3x text-muted mb-3"></i>
                                        <p>Aperçu de la page s'affichera ici</p>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="editRefreshPreviewBtn">
                                            <i class="fas fa-sync me-1"></i> Rafraîchir l'aperçu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updatePageBtn">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cette page ? Cette action est irréversible.</p>
                    
                    <div class="page-to-delete" id="pageToDeleteInfo">
                        <!-- Page info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette page ne sera plus accessible.
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
    
    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Aperçu de la page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="previewIframe" style="width: 100%; height: 70vh; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allPages = [];
        let pageToDelete = null;
        let destinationsCache = {};

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadPages();
            loadStatistics();
            setupEventListeners();
            setupTypeDestinationBinding();
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

        // Load pages
        const loadPages = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("pages.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allPages = response.data || [];
                        renderPages(allPages);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des pages');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showError('Erreur de connexion au serveur');
                    console.error('Error:', xhr.responseText);
                }
            });
        };

        // Load statistics
        const loadStatistics = () => {
            $.ajax({
                url: '{{ route("pages.statistics") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalPages').textContent = stats.total_pages || 0;
                        document.getElementById('activePages').textContent = stats.active_pages || 0;
                        document.getElementById('pagesByType').textContent = stats.unique_types || 0;
                        document.getElementById('recentPages').textContent = stats.recent_pages || 0;
                    }
                },
                error: function(xhr) {
                    console.error('Statistics AJAX error:', xhr.responseText);
                }
            });
        };

        // Render pages
        const renderPages = (pages) => {
            const tbody = document.getElementById('pagesTableBody');
            tbody.innerHTML = '';
            
            if (!pages || !Array.isArray(pages) || pages.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            pages.forEach((page, index) => {
                const row = document.createElement('tr');
                row.id = `page-row-${page.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                // Get destination name
                const destinationName = page.pageable?.name || 'N/A';
                const pageType = getPageTypeLabel(page.pageable_type);
                const typeColor = getTypeColor(page.pageable_type);
                
                row.innerHTML = `
                    <td class="page-name-cell">
                        <div class="page-name-modern">
                            <div class="page-icon-modern" style="background: ${typeColor}">
                                <i class="${getTypeIcon(page.pageable_type)}"></i>
                            </div>
                            <div>
                                <div class="page-name-text">${page.name}</div>
                                <small class="text-muted">ID: ${page.id}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-modern" style="background: ${typeColor}">
                            ${pageType}
                        </span>
                    </td>
                    <td>
                        <div class="destination-info">
                            <strong>${destinationName}</strong>
                            ${page.pageable?.code ? `<small class="text-muted d-block">Code: ${page.pageable.code}</small>` : ''}
                        </div>
                    </td>
                    <td>
                        <code class="slug-code">/${page.slug}</code>
                        <br>
                        <small class="text-muted">${page.slug}</small>
                    </td>
                    <td>
                        ${page.is_active 
                            ? '<span class="badge-modern bg-success"><i class="fas fa-check me-1"></i>Actif</span>' 
                            : '<span class="badge-modern bg-secondary"><i class="fas fa-times me-1"></i>Inactif</span>'
                        }
                    </td>
                    <td>
                        <div>${formatDate(page.updated_at)}</div>
                        <small class="text-muted">Créé: ${formatDate(page.created_at)}</small>
                    </td>
                    <td>
                        <div class="page-actions-modern">
                            <a href="/pages/${page.slug}" 
                               class="action-btn-modern view-btn-modern" target="_blank" title="Voir la page">
                                <i class="fas fa-globe"></i>
                            </a>
                            <a class="action-btn-modern code-btn-modern" title="Modifier" 
                                    href="{{url('pages/edit')}}/${page.id}" target="_blank">
                                <i class="fas fa-code"></i>
                            </a>
                            <button class="action-btn-modern preview-btn-modern" title="Aperçu" 
                                    onclick="previewPage(${page.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                    onclick="openEditModal(${page.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteConfirmation(${page.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
            document.getElementById('paginationContainer').style.display = 'flex';
        };

        // Get page type label
        const getPageTypeLabel = (type) => {
            const types = {
                'continent': 'Continent',
                'country': 'Pays',
                'region': 'Région',
                'province': 'Province',
                'city': 'Ville'
            };
            return types[type] || type;
        };

        // Get type color
        const getTypeColor = (type) => {
            const colors = {
                'continent': 'linear-gradient(135deg, #007bff, #3a56e4)',
                'country': 'linear-gradient(135deg, #28a745, #06b48a)',
                'region': 'linear-gradient(135deg, #ffc107, #ffb347)',
                'province': 'linear-gradient(135deg, #dc3545, #d4335f)',
                'city': 'linear-gradient(135deg, #6f42c1, #6610f2)'
            };
            return colors[type] || 'linear-gradient(135deg, #6c757d, #495057)';
        };

        // Get type icon
        const getTypeIcon = (type) => {
            const icons = {
                'continent': 'fas fa-globe',
                'country': 'fas fa-flag',
                'region': 'fas fa-mountain',
                'province': 'fas fa-map-marked-alt',
                'city': 'fas fa-city'
            };
            return icons[type] || 'fas fa-file-alt';
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (pageId) => {
            const page = allPages.find(p => p.id === pageId);
            
            if (!page) {
                showAlert('danger', 'Page non trouvée');
                return;
            }
            
            pageToDelete = page;
            
            document.getElementById('pageToDeleteInfo').innerHTML = `
                <div class="page-info">
                    <div class="page-info-icon" style="background: ${getTypeColor(page.pageable_type)}">
                        <i class="${getTypeIcon(page.pageable_type)}"></i>
                    </div>
                    <div>
                        <div class="page-info-name">${page.name}</div>
                        <div class="page-info-type">${getPageTypeLabel(page.pageable_type)}</div>
                    </div>
                </div>
                <div class="row small text-muted mt-3">
                    <div class="col-6">
                        <div><strong>Slug:</strong> ${page.slug}</div>
                        <div><strong>Destination:</strong> ${page.pageable?.name || 'N/A'}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Statut:</strong> ${page.is_active ? 'Actif' : 'Inactif'}</div>
                        <div><strong>Créé le:</strong> ${formatDate(page.created_at)}</div>
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
            new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
        };

        // Delete page
        const deletePage = () => {
            if (!pageToDelete) {
                showAlert('danger', 'Aucune page à supprimer');
                return;
            }
            
            const pageId = pageToDelete.id;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            
            // Show processing animation
            deleteBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Suppression...</span>
                </div>
                Suppression en cours...
            `;
            deleteBtn.disabled = true;
            
            // Add deleting animation to table row
            const row = document.getElementById(`page-row-${pageId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/pages/${pageId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove page from array
                        allPages = allPages.filter(p => p.id !== pageId);
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Page supprimée avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('pagesTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadPages(currentPage, currentFilters);
                            }, 500);
                        }
                    } else {
                        if (row) row.classList.remove('deleting-row');
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    // Remove deleting animation
                    const row = document.getElementById(`page-row-${pageId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Page non trouvée.');
                        loadPages(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression');
                    }
                },
                complete: function() {
                    pageToDelete = null;
                }
            });
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} pages`;
            
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
            loadPages(page, currentFilters);
        };

        // Store page
        const storePage = () => {
            const form = document.getElementById('createPageForm');
            const submitBtn = document.getElementById('submitPageBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Création en cours...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            // Get pageable type from select
            data.pageable_type = 'App\\Models\\' + data.pageable_type.charAt(0).toUpperCase() + data.pageable_type.slice(1);
            
            $.ajax({
                url: '{{ route("pages.store") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la page
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createPageModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        
                        // Reload pages
                        loadPages(1, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Page créée avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création');
                    }
                },
                error: function(xhr) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la page
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
                        showAlert('danger', 'Erreur lors de la création');
                    }
                }
            });
        };

        // Update page
        const updatePage = () => {
            const form = document.getElementById('editPageForm');
            const submitBtn = document.getElementById('updatePageBtn');
            const pageId = document.getElementById('editPageId').value;
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Enregistrement...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            data._method = 'PUT';
            
            $.ajax({
                url: `/pages/${pageId}`,
                type: 'POST',
                data: data,
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editPageModal'));
                        modal.hide();
                        
                        // Reload pages
                        loadPages(currentPage, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Page mise à jour avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la mise à jour');
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
                        let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                        for (const field in errors) {
                            errorMessage += `- ${errors[field].join('<br>')}<br>`;
                        }
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de la mise à jour');
                    }
                }
            });
        };

        // Open edit modal
        const openEditModal = (pageId) => {
            const page = allPages.find(p => p.id === pageId);
            
            if (page) {
                document.getElementById('editPageId').value = page.id;
                document.getElementById('editPageName').value = page.name;
                document.getElementById('editPageSlug').value = page.slug;
                document.getElementById('editPageType').value = page.pageable_type.replace('App\\Models\\', '').toLowerCase();
                document.getElementById('editPageDestination').value = page.pageable_id;
                document.getElementById('editPageIsActive').checked = page.is_active;
                document.getElementById('editPageHtmlContent').value = page.html_content || '';
                document.getElementById('editPageCssContent').value = page.css_content || '';
                
                // Update destination select
                const type = page.pageable_type.replace('App\\Models\\', '').toLowerCase();
                loadDestinationsForType(type, page.pageable_id);
                
                new bootstrap.Modal(document.getElementById('editPageModal')).show();
            }
        };

        // Preview page
        const previewPage = (pageId) => {
            const page = allPages.find(p => p.id === pageId);
            
            if (page) {
                // Create a temporary iframe with page content
                const iframe = document.getElementById('previewIframe');
                const htmlContent = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <style>${page.css_content || ''}</style>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            .page-preview { max-width: 1000px; margin: 0 auto; }
                        </style>
                    </head>
                    <body>
                        <div class="page-preview">
                            ${page.html_content || '<p>Aucun contenu</p>'}
                        </div>
                    </body>
                    </html>
                `;
                
                iframe.srcdoc = htmlContent;
                
                const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
                previewModal.show();
            }
        };

        // Setup type-destination binding
        const setupTypeDestinationBinding = () => {
            const typeSelect = document.getElementById('pageType');
            const destinationSelect = document.getElementById('pageDestination');
            
            if (typeSelect && destinationSelect) {
                typeSelect.addEventListener('change', function() {
                    const type = this.value;
                    
                    if (type) {
                        destinationSelect.disabled = false;
                        loadDestinationsForType(type);
                    } else {
                        destinationSelect.disabled = true;
                        destinationSelect.innerHTML = '<option value="">Sélectionnez d\'abord un type</option>';
                    }
                });
            }
        };

        // Load destinations for type
        const loadDestinationsForType = (type, selectedId = null) => {
            const destinationSelect = document.getElementById('pageDestination');
            const editDestinationSelect = document.getElementById('editPageDestination');
            
            const targetSelect = destinationSelect ? destinationSelect : editDestinationSelect;
            
            if (!targetSelect) return;
            
            // Check cache first
            if (destinationsCache[type]) {
                populateDestinationSelect(targetSelect, destinationsCache[type], selectedId);
                return;
            }
            
            // Show loading
            targetSelect.innerHTML = '<option value="">Chargement...</option>';
            
            $.ajax({
                url: `/api/pages/destinations/${type}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        destinationsCache[type] = response.data;
                        populateDestinationSelect(targetSelect, response.data, selectedId);
                    }
                },
                error: function() {
                    targetSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                }
            });
        };

        // Populate destination select
        const populateDestinationSelect = (select, destinations, selectedId = null) => {
            let html = '<option value="">Sélectionnez une destination</option>';
            
            destinations.forEach(destination => {
                const selected = selectedId == destination.id ? 'selected' : '';
                html += `<option value="${destination.id}" ${selected}>${destination.name}${destination.code ? ' (' + destination.code + ')' : ''}</option>`;
            });
            
            select.innerHTML = html;
        };

        // Generate slug from name
        const generateSlug = () => {
            const nameInput = document.getElementById('pageName');
            const slugInput = document.getElementById('pageSlug');
            
            if (nameInput && slugInput && !slugInput.value) {
                const name = nameInput.value;
                const slug = name.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s]/g, '')
                    .trim()
                    .replace(/\s+/g, '-');
                slugInput.value = slug;
            }
        };

        // Format date
        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
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
                        loadPages(1, currentFilters);
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
                        type: document.getElementById('filterType').value,
                        status: document.getElementById('filterStatus').value,
                        sort_by: document.getElementById('filterSortBy').value,
                        sort_direction: document.getElementById('filterSortDirection')?.value || 'desc'
                    };
                    loadPages(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterType').value = '';
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterSortBy').value = 'created_at';
                    document.getElementById('filterSortDirection').value = 'desc';
                    currentFilters = {};
                    loadPages(1);
                });
            }
            
            // Submit page form
            const submitPageBtn = document.getElementById('submitPageBtn');
            if (submitPageBtn) {
                submitPageBtn.addEventListener('click', storePage);
            }
            
            // Update page form
            const updatePageBtn = document.getElementById('updatePageBtn');
            if (updatePageBtn) {
                updatePageBtn.addEventListener('click', updatePage);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deletePage);
            }
            
            // Generate slug button
            const generateSlugBtn = document.getElementById('generateSlugBtn');
            if (generateSlugBtn) {
                generateSlugBtn.addEventListener('click', generateSlug);
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createPageModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createPageForm').reset();
                    const submitBtn = document.getElementById('submitPageBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la page
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
        };
    </script>

    <style>
        /* Styles spécifiques pour la page pages */
        .page-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .page-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .badge-modern {
            padding: 4px 10px;
            border-radius: 20px;
            color: white;
            font-weight: 500;
            font-size: 0.85rem;
            border: none;
        }

        .page-actions-modern {
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
            color: white;
        }

        .preview-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
        }

        .preview-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
        }

        .view-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
        }

        
        .code-btn-modern {
            background: linear-gradient(135deg, #272828ff, #232423ff);
        }

        .view-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #ff9a1e);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 209, 102, 0.3);
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 71, 111, 0.3);
        }

        .page-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .page-info-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        .page-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
        }

        .page-info-type {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .slug-code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            color: #dc3545;
        }

        .tab-content-modern {
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 20px;
            background: #fff;
        }

        .nav-tabs-modern {
            border-bottom: 1px solid #dee2e6;
        }

        .nav-item-modern {
            margin-bottom: -1px;
        }

        .nav-link-modern {
            padding: 12px 20px;
            border: 1px solid transparent;
            border-radius: 8px 8px 0 0;
            background: #f8f9fa;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link-modern:hover {
            background: #e9ecef;
            color: #495057;
        }

        .nav-link-modern.active {
            background: white;
            border-color: #dee2e6 #dee2e6 white;
            color: #007bff;
        }

        .code-editor {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
            background: #f8f9fa;
        }

        .preview-container {
            min-height: 300px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            background: white;
        }

        .preview-placeholder {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        /* Animation for deleting row */
        .deleting-row {
            animation: slideOut 0.3s ease forwards;
            opacity: 0.5;
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .page-icon-modern {
                width: 35px;
                height: 35px;
            }
            
            .page-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .nav-tabs-modern {
                flex-wrap: wrap;
            }
            
            .nav-link-modern {
                flex: 1;
                text-align: center;
                font-size: 0.9rem;
                padding: 8px 12px;
            }
        }
    </style>
@endsections