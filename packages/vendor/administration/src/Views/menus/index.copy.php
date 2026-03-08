@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-bars"></i></span>
                Gestion des Menus
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Menu
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
                    <label for="filterType" class="form-label-modern">Type</label>
                    <select class="form-select-modern" id="filterType">
                        <option value="">Tous les types</option>
                        <option value="custom">Personnalisé</option>
                        <option value="category">Catégorie</option>
                        <option value="activity">Activité</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterParent" class="form-label-modern">Niveau</label>
                    <select class="form-select-modern" id="filterParent">
                        <option value="">Tous les niveaux</option>
                        <option value="root">Menus principaux</option>
                        <option value="child">Sous-menus</option>
                        <option value="subchild">Sous-sous-menus</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="order">Ordre</option>
                        <option value="title">Titre</option>
                        <option value="created_at">Date de création</option>
                        <option value="type">Type</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalMenus">0</div>
                        <div class="stats-label-modern">Total Menus</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeMenus">0</div>
                        <div class="stats-label-modern">Menus Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="mainMenus">0</div>
                        <div class="stats-label-modern">Menus Principaux</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="subMenus">0</div>
                        <div class="stats-label-modern">Sous-menus</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-sitemap"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Structure des Menus</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un menu..." id="searchInput">
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Menu Tree View -->
                <div class="menu-tree-container" id="menuTreeContainer">
                    <!-- Menu tree will be loaded here via AJAX -->
                </div>
                
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
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Niveau</th>
                                <th>Parent</th>
                                <th>Ordre</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                                <th>Pages</th>
                            </tr>
                        </thead>
                        <tbody id="menusTableBody">
                            <!-- Menus will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-bars"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun menu trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier menu.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un menu
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createMenuModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE MENU MODAL -->
    <div class="modal fade" id="createMenuModal" tabindex="-1" aria-labelledby="createMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createMenuModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau menu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createMenuForm">
                        @csrf
                        
                        <!-- Menu Level Selection -->
                        <div class="form-section-modern">
                            <h6 class="section-title-modern">
                                <i class="fas fa-layer-group me-2"></i>Niveau du menu
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="level-selector">
                                        <div class="level-option" data-level="0">
                                            <div class="level-icon">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                            <div class="level-info">
                                                <div class="level-title">Menu Principal</div>
                                                <div class="level-description">Niveau supérieur du menu</div>
                                            </div>
                                            <div class="level-check">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="level-option" data-level="1">
                                            <div class="level-icon">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                            <div class="level-info">
                                                <div class="level-title">Sous-menu</div>
                                                <div class="level-description">Premier niveau enfant</div>
                                            </div>
                                            <div class="level-check">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="level-option" data-level="2">
                                            <div class="level-icon">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                            <div class="level-info">
                                                <div class="level-title">Sous-sous-menu</div>
                                                <div class="level-description">Deuxième niveau enfant</div>
                                            </div>
                                            <div class="level-check">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="selectedLevel" name="level" value="0">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Parent Selection (for submenus) -->
                        <div class="form-section-modern" id="parentSelectionSection" style="display: none;">
                            <h6 class="section-title-modern">
                                <i class="fas fa-sitemap me-2"></i>Menu Parent
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div id="parentSelectContainer">
                                        <!-- Parent selection will be loaded dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Basic Information -->
                        <div class="form-section-modern">
                            <h6 class="section-title-modern">
                                <i class="fas fa-info-circle me-2"></i>Informations de base
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
    <label for="menuTitle" class="form-label-modern">Titre *</label>
    <textarea class="form-control-modern ckeditor" id="menuTitle" name="title" 
              placeholder="Ex: Accueil, Produits, Contact..." rows="3" required></textarea>
</div>
                                <div class="col-md-12 mb-3">
                                    <label for="menuSlug" class="form-label-modern">Slug *</label>
                                    <input type="text" class="form-control-modern" id="menuSlug" name="slug" 
                                           placeholder="Ex: accueil, produits, contact..." required>
                                    <div class="form-text-modern">Identifiant unique pour le menu</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="menuType" class="form-label-modern">Type de menu *</label>
                                    <select class="form-select-modern" id="menuType" name="type" required>
                                        <option value="custom">Personnalisé</option>
                                        <option value="category">Catégorie</option>
                                        <option value="activity">Activité</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="menuOrder" class="form-label-modern">Ordre</label>
                                    <input type="number" class="form-control-modern" id="menuOrder" name="order" 
                                           value="0" min="0">
                                    <div class="form-text-modern">Position dans le menu</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content Selection (for category/activity types) -->
                        <div class="form-section-modern" id="contentSelectionSection" style="display: none;">
                            <h6 class="section-title-modern">
                                <i class="fas fa-link me-2"></i>Sélection du contenu
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div id="contentSelectContainer">
                                        <!-- Content selection will be loaded dynamically based on type -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- URL Configuration -->
                        <div class="form-section-modern d-none" id="urlConfigurationSection">
                            <h6 class="section-title-modern">
                                <i class="fas fa-link me-2"></i>Configuration de l'URL
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="menuUrl" class="form-label-modern">URL personnalisée</label>
                                    <input type="text" class="form-control-modern" id="menuUrl" name="url" 
                                           placeholder="Ex: /about, /products, https://example.com">
                                    <div class="form-text-modern">Laisser vide pour URL automatique</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="menuRoute" class="form-label-modern">Route Laravel</label>
                                    <input type="text" class="form-control-modern" id="menuRoute" name="route" 
                                           placeholder="Ex: home, products.index, contact.show">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Advanced Options -->
                        <div class="form-section-modern">
                            <h6 class="section-title-modern">
                                <i class="fas fa-cog me-2"></i>Options avancées
                            </h6>
                            <div class="row">
                                <!-- <div class="col-md-6 mb-3">
                                    <label for="menuIcon" class="form-label-modern">Icône</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                        <input type="text" class="form-control-modern" id="menuIcon" name="icon" 
                                               placeholder="Ex: fas fa-home, fas fa-user">
                                    </div>
                                    <div class="form-text-modern">Classes FontAwesome</div>
                                </div> -->
                                <div class="col-md-12 mb-3">
                                    <label for="menuStatus" class="form-label-modern">Statut</label>
                                    <select class="form-select-modern" id="menuStatus" name="is_active">
                                        <option value="1">Actif</option>
                                        <option value="0">Inactif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitMenuBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le menu
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT MENU MODAL -->
    <div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editMenuModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier le menu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editMenuForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editMenuId" name="id">
                        
                        <!-- Basic Information -->
                        <div class="form-section-modern">
                            <h6 class="section-title-modern">
                                <i class="fas fa-info-circle me-2"></i>Informations de base
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
    <label for="editMenuTitle" class="form-label-modern">Titre *</label>
    <textarea class="form-control-modern ckeditor" id="editMenuTitle" name="title" rows="3" required></textarea>
</div>
                                <div class="col-md-12 mb-3">
                                    <label for="editMenuSlug" class="form-label-modern">Slug *</label>
                                    <input type="text" class="form-control-modern" id="editMenuSlug" name="slug" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editMenuType" class="form-label-modern">Type de menu *</label>
                                    <select class="form-select-modern" id="editMenuType" name="type" required>
                                        <option value="custom">Personnalisé</option>
                                        <option value="category">Catégorie</option>
                                        <option value="activity">Activité</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editMenuOrder" class="form-label-modern">Ordre</label>
                                    <input type="number" class="form-control-modern" id="editMenuOrder" name="order" min="0">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editMenuParent" class="form-label-modern">Menu Parent</label>
                                    <select class="form-select-modern" id="editMenuParent" name="parent_id">
                                        <option value="">Aucun (menu principal)</option>
                                        <!-- Parent options will be loaded dynamically -->
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editMenuReference" class="form-label-modern">Référence</label>
                                    <select class="form-select-modern" id="editMenuReference" name="reference_id">
                                        <option value="">Sélectionner...</option>
                                        <!-- Reference options will be loaded dynamically -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- URL Configuration -->
                        <div class="form-section-modern">
                            <h6 class="section-title-modern">
                                <i class="fas fa-link me-2"></i>Configuration de l'URL
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editMenuUrl" class="form-label-modern">URL personnalisée</label>
                                    <input type="text" class="form-control-modern" id="editMenuUrl" name="url">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editMenuRoute" class="form-label-modern">Route Laravel</label>
                                    <input type="text" class="form-control-modern" id="editMenuRoute" name="route">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Advanced Options -->
                        <div class="form-section-modern">
                            <h6 class="section-title-modern">
                                <i class="fas fa-cog me-2"></i>Options avancées
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editMenuIcon" class="form-label-modern">Icône</label>
                                    <input type="text" class="form-control-modern" id="editMenuIcon" name="icon">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editMenuStatus" class="form-label-modern">Statut</label>
                                    <select class="form-select-modern" id="editMenuStatus" name="is_active">
                                        <option value="1">Actif</option>
                                        <option value="0">Inactif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateMenuBtn">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce menu ? Tous les sous-menus seront également supprimés.</p>
                    
                    <div class="menu-to-delete" id="menuToDeleteInfo">
                        <!-- Menu info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible et supprimera tous les sous-menus.
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

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    // Configuration
    let currentPage = 1;
    let currentFilters = {};
    let allMenus = [];
    let menuToDelete = null;
    let categories = [];
    let activities = [];

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setupAjax();
        loadMenus();
        loadStatistics();
        loadCategories();
        loadActivities();
        setupEventListeners();
        setupMenuTypeHandlers();
        setupLevelSelector();
        initializeCKEditors();
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

    // Initialize CKEditors
    const initializeCKEditors = () => {
        // Initialiser CKEditor pour le formulaire de création
        $('#createMenuModal').on('shown.bs.modal', function() {
            if (CKEDITOR.instances.menuTitle) {
                CKEDITOR.instances.menuTitle.destroy();
            }
            
            CKEDITOR.replace('menuTitle', {
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList'],
                    ['Link', 'Unlink'],
                    ['RemoveFormat', 'Source']
                ],
                height: 150,
                removePlugins: 'elementspath',
                resize_enabled: false,
                startupFocus: false
            });
        });
        
        // Nettoyer CKEditor à la fermeture du modal de création
        $('#createMenuModal').on('hidden.bs.modal', function() {
            if (CKEDITOR.instances.menuTitle) {
                CKEDITOR.instances.menuTitle.destroy();
            }
            cleanupModalBackdrop();
        });
    };

    // Load menus
    const loadMenus = (page = 1, filters = {}) => {
        showLoading();
        
        const searchTerm = document.getElementById('searchInput')?.value || '';
        
        $.ajax({
            url: '{{ route("menus.index") }}',
            type: 'GET',
            data: {
                page: page,
                search: searchTerm,
                ...filters,
                ajax: true
            },
            success: function(response) {
                console.log('Menus response:', response);
                
                if (response.success) {
                    allMenus = response.data || [];
                    renderMenus(allMenus);
                    renderPagination(response);
                    updateMenuTree(allMenus);
                    hideLoading();
                } else {
                    showError('Erreur lors du chargement des menus');
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
            url: '{{ route("menus.statistics") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const stats = response.data;
                    document.getElementById('totalMenus').textContent = stats.total_menus || 0;
                    document.getElementById('activeMenus').textContent = stats.active_menus || 0;
                    document.getElementById('mainMenus').textContent = stats.main_menus || 0;
                    document.getElementById('subMenus').textContent = stats.sub_menus || 0;
                }
            },
            error: function(xhr) {
                console.error('Statistics error:', xhr.responseText);
            }
        });
    };

    // Load categories
    const loadCategories = () => {
        $.ajax({
            url: '{{ route("menus.categories") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    categories = response.data || [];
                }
            }
        });
    };

    // Load activities
    const loadActivities = () => {
        $.ajax({
            url: '{{ route("menus.activities") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    activities = response.data || [];
                }
            }
        });
    };

    // Render menus table
    const renderMenus = (menus) => {
        const tbody = document.getElementById('menusTableBody');
        tbody.innerHTML = '';
        
        if (!menus || menus.length === 0) {
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            return;
        }
        
        menus.forEach((menu, index) => {
            const row = document.createElement('tr');
            row.id = `menu-row-${menu.id}`;
            row.style.animationDelay = `${index * 0.05}s`;
            
            const level = menu.parent_id ? (menu.parent?.parent_id ? 'Sous-sous-menu' : 'Sous-menu') : 'Principal';
            const levelClass = menu.parent_id ? (menu.parent?.parent_id ? 'level-2' : 'level-1') : 'level-0';
            
            row.innerHTML = `
                <td>
                    <div class="menu-name-cell">
                        <div class="menu-icons ${menu.icon || 'default-icon'}">
                            <i class="${menu.icon || 'fas fa-link'}"></i>
                        </div>
                        <div>
                            <div class="menu-name-text">${menu.title}</div>
                            <small class="text-muted">/${menu.slug}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="menu-type-badge ${menu.type}">
                        ${getTypeLabel(menu.type)}
                    </span>
                </td>
                <td>
                    <span class="level-badge ${levelClass}">${level}</span>
                </td>
                <td>
                    ${menu.parent ? menu.parent.title : '<em>Aucun</em>'}
                </td>
                <td>
                    <span class="order-badge">${menu.order}</span>
                </td>
                <td>
                    <span class="status-badge ${menu.is_active ? 'active' : 'inactive'}">
                        ${menu.is_active ? 'Actif' : 'Inactif'}
                    </span>
                </td>
                <td>
                    <div class="menu-actions-modern">
                        <button class="action-btn-modern move-up-btn-modern" title="Déplacer vers le haut" 
                                onclick="moveMenu(${menu.id}, 'up')">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <button class="action-btn-modern move-down-btn-modern" title="Déplacer vers le bas" 
                                onclick="moveMenu(${menu.id}, 'down')">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                        <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                onclick="openEditModal(${menu.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                onclick="showDeleteConfirmation(${menu.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <div class="page-management">
                        <div class="page-status-badge ${menu.has_page ? 'has-page' : 'no-page'}">
                            ${menu.has_page ? '📄 Page' : '❌ Pas de page'}
                        </div>
                        ${menu.has_page ? `
                            <div class="page-actions">
                                <a href="{{ url('menus/template/view') }}/${menu.id}" target="_blank" class="btn btn-sm btn-outline-primary" title="Voir la page">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ url('menus/template/edit') }}/${menu.id}" class="btn btn-sm btn-outline-success" title="Éditer la page" target="_blank">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-warning toggle-page-btn" 
                                        data-id="${menu.id}" 
                                        title="${menu.has_page ? 'Désactiver la page' : 'Activer la page'}">
                                    <i class="fas ${menu.has_page ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
                                </button>
                            </div>
                        ` : `
                            <button class="btn btn-sm btn-outline-success create-page-btn" data-id="${menu.id}">
                                <i class="fas fa-plus"></i> Créer une page
                            </button>
                        `}
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
        
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('paginationContainer').style.display = 'flex';
    };

    // Update menu tree view
    const updateMenuTree = (menus) => {
        const container = document.getElementById('menuTreeContainer');
        const rootMenus = menus.filter(menu => !menu.parent_id);
        
        let treeHtml = '<div class="menu-tree">';
        
        rootMenus.forEach(menu => {
            treeHtml += buildTreeItem(menu, menus);
        });
        
        treeHtml += '</div>';
        container.innerHTML = treeHtml;
    };

    // Build tree item recursively
    const buildTreeItem = (menu, allMenus, level = 0) => {
        const children = allMenus.filter(m => m.parent_id === menu.id);
        const hasChildren = children.length > 0;
        
        let html = `
            <div class="tree-item level-${level}" data-id="${menu.id}">
                <div class="tree-item-header">
                    <div class="tree-item-toggle ${hasChildren ? 'has-children' : ''}" onclick="toggleTreeItem(${menu.id})">
                        ${hasChildren ? '<i class="fas fa-chevron-right"></i>' : ''}
                    </div>
                    <div class="tree-item-content">
                        <div class="tree-item-icon">
                            <i class="${menu.icon || 'fas fa-link'}"></i>
                        </div>
                        <div class="tree-item-info">
                            <div class="tree-item-title">${menu.title}</div>
                            <div class="tree-item-details">
                                <span class="badge badge-sm ${menu.type}">${getTypeLabel(menu.type)}</span>
                                <span class="badge badge-sm order">Ordre: ${menu.order}</span>
                                ${menu.is_active ? '<span class="badge badge-sm active">Actif</span>' : '<span class="badge badge-sm inactive">Inactif</span>'}
                            </div>
                        </div>
                    </div>
                    <div class="tree-item-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${menu.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="showDeleteConfirmation(${menu.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
        `;
        
        if (hasChildren) {
            html += '<div class="tree-item-children" style="display: none;">';
            children.forEach(child => {
                html += buildTreeItem(child, allMenus, level + 1);
            });
            html += '</div>';
        }
        
        html += '</div>';
        return html;
    };

    // Toggle tree item
    const toggleTreeItem = (menuId) => {
        const item = document.querySelector(`.tree-item[data-id="${menuId}"]`);
        if (!item) return;
        
        const children = item.querySelector('.tree-item-children');
        const toggle = item.querySelector('.tree-item-toggle');
        
        if (children) {
            if (children.style.display === 'none') {
                children.style.display = 'block';
                toggle.querySelector('i').className = 'fas fa-chevron-down';
            } else {
                children.style.display = 'none';
                toggle.querySelector('i').className = 'fas fa-chevron-right';
            }
        }
    };

    // Get type label
    const getTypeLabel = (type) => {
        const labels = {
            'custom': 'Personnalisé',
            'category': 'Catégorie',
            'activity': 'Activité'
        };
        return labels[type] || type;
    };

    // Setup level selector
    const setupLevelSelector = () => {
        const levelOptions = document.querySelectorAll('.level-option');
        const parentSection = document.getElementById('parentSelectionSection');
        
        levelOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                levelOptions.forEach(opt => opt.classList.remove('active'));
                
                // Add active class to clicked option
                this.classList.add('active');
                
                // Update hidden input
                const level = this.dataset.level;
                document.getElementById('selectedLevel').value = level;
                
                // Show/hide parent selection
                if (level > 0) {
                    parentSection.style.display = 'block';
                    loadParentOptions(level);
                } else {
                    parentSection.style.display = 'none';
                }
            });
        });
        
        // Select first level by default
        levelOptions[0].classList.add('active');
    };

    // Load parent options based on level
    const loadParentOptions = (level) => {
        const container = document.getElementById('parentSelectContainer');
        
        let url = '{{ route("menus.parents") }}';
        if (level == 2) {
            url = '{{ route("menus.subparents") }}';
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const parents = response.data;
                    
                    let options = '<select class="form-select-modern" id="menuParent" name="parent_id" required>';
                    options += '<option value="">Sélectionnez un menu parent</option>';
                    
                    parents.forEach(parent => {
                        const prefix = level == 2 ? '-- ' : '';
                        options += `<option value="${parent.id}">${prefix}${parent.title}</option>`;
                    });
                    
                    options += '</select>';
                    container.innerHTML = options;
                }
            }
        });
    };

    // Setup menu type handlers
    const setupMenuTypeHandlers = () => {
        const menuType = document.getElementById('menuType');
        const contentSection = document.getElementById('contentSelectionSection');
        const urlSection = document.getElementById('urlConfigurationSection');
        
        if (menuType) {
            menuType.addEventListener('change', function() {
                const type = this.value;
                
                if (type === 'custom') {
                    contentSection.style.display = 'none';
                    urlSection.style.display = 'block';
                } else {
                    contentSection.style.display = 'block';
                    urlSection.style.display = 'none';
                    loadContentOptions(type);
                }
            });
        }
        
        // Handle edit modal type change
        const editMenuType = document.getElementById('editMenuType');
        if (editMenuType) {
            editMenuType.addEventListener('change', function() {
                const type = this.value;
                loadEditReferenceOptions(type);
            });
        }
    };

    // Load content options based on type
    const loadContentOptions = (type) => {
        const container = document.getElementById('contentSelectContainer');
        
        let items = [];
        let label = '';
        
        if (type === 'category') {
            items = categories;
            label = 'Catégorie';
        } else if (type === 'activity') {
            items = activities;
            label = 'Activité';
        }
        
        let html = `
            <label class="form-label-modern">Sélectionner une ${label}</label>
            <select class="form-select-modern" id="menuReference" name="reference_id" required>
                <option value="">Sélectionnez une ${label}</option>
        `;
        
        items.forEach(item => {
            html += `<option value="${item.id}">${item.name || item.title}</option>`;
        });
        
        html += '</select>';
        container.innerHTML = html;
    };

    // Load edit reference options
    const loadEditReferenceOptions = (type) => {
        const select = document.getElementById('editMenuReference');
        if (!select) return;
        
        select.innerHTML = '<option value="">Sélectionner...</option>';
        
        if (type === 'category') {
            categories.forEach(category => {
                select.innerHTML += `<option value="${category.id}">${category.name}</option>`;
            });
        } else if (type === 'activity') {
            activities.forEach(activity => {
                select.innerHTML += `<option value="${activity.id}">${activity.name} (${activity.category?.name})</option>`;
            });
        }
    };

    // Store menu
    const storeMenu = () => {
        const form = document.getElementById('createMenuForm');
        const submitBtn = document.getElementById('submitMenuBtn');
        
        // Validation
        if (!validateCreateMenuForm()) {
            return;
        }
        
        // Désactiver le bouton et montrer le loader
        $(submitBtn).prop('disabled', true).html(
            '<div class="spinner-border spinner-border-sm me-2"></div>Création en cours...'
        );
        
        // Récupérer les valeurs
        const level = parseInt($('#selectedLevel').val());
        let titleContent = '';
        
        // Récupérer le contenu CKEditor si disponible
        if (CKEDITOR.instances.menuTitle) {
            titleContent = CKEDITOR.instances.menuTitle.getData().trim();
        } else {
            titleContent = $('#menuTitle').val().trim();
        }
        
        const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            title: titleContent,
            slug: $('#menuSlug').val().trim(),
            type: $('#menuType').val(),
            parent_id: level > 0 ? $('#menuParent').val() : '',
            order: $('#menuOrder').val() || 0,
            url: $('#menuUrl').val(),
            route: $('#menuRoute').val(),
            icon: $('#menuIcon').val(),
            is_active: $('#menuStatus').val() || 1
        };
        
        // Ajouter reference_id si nécessaire
        if ($('#menuType').val() !== 'custom') {
            data.reference_id = $('#menuReference').val();
        }
        
        // Envoyer la requête
        $.ajax({
            url: '{{ route("menus.store") }}',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Fermer le modal proprement
                    closeModalProperly('#createMenuModal');
                    
                    // Réinitialiser le formulaire
                    form.reset();
                    
                    // Réinitialiser le level selector
                    $('.level-option').removeClass('active');
                    $('.level-option[data-level="0"]').addClass('active');
                    $('#selectedLevel').val('0');
                    $('#parentSelectionSection').hide();
                    
                    // Recharger les données
                    loadMenus(1, currentFilters);
                    loadStatistics();
                    
                    // Afficher le message de succès
                    showAlert('success', 'Menu créé avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                    for (const field in errors) {
                        errorMessage += `- ${errors[field].join('<br>')}<br>`;
                    }
                    showAlert('danger', errorMessage);
                    
                    // Marquer les champs invalides
                    for (const field in errors) {
                        $(`[name="${field}"]`).addClass('is-invalid')
                            .after(`<div class="invalid-feedback">${errors[field].join('<br>')}</div>`);
                    }
                } else {
                    showAlert('danger', 'Erreur lors de la création: ' + error);
                }
            },
            complete: function() {
                // Réactiver le bouton
                $(submitBtn).prop('disabled', false).html(
                    '<i class="fas fa-save me-2"></i>Créer le menu'
                );
            }
        });
    };

    // Validation pour le formulaire de création
    const validateCreateMenuForm = () => {
        let isValid = true;
        let errorMessage = '';
        
        // Réinitialiser toutes les classes d'erreur
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Titre (CKEditor ou textarea)
        let title = '';
        if (CKEDITOR.instances.menuTitle) {
            title = CKEDITOR.instances.menuTitle.getData().trim();
        } else {
            title = $('#menuTitle').val().trim();
        }
        
        if (!title) {
            isValid = false;
            errorMessage += '- Le titre est requis<br>';
            $('#menuTitle').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le titre est requis</div>');
        }
        
        // Slug
        const slug = $('#menuSlug').val().trim();
        if (!slug) {
            isValid = false;
            errorMessage += '- Le slug est requis<br>';
            $('#menuSlug').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le slug est requis</div>');
        }
        
        // Type
        const type = $('#menuType').val();
        if (!type) {
            isValid = false;
            errorMessage += '- Le type de menu est requis<br>';
            $('#menuType').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le type de menu est requis</div>');
        }
        
        // Vérifier le niveau
        const level = parseInt($('#selectedLevel').val());
        
        // Parent (seulement si level > 0)
        if (level > 0) {
            const parentSelect = $('#menuParent');
            if (!parentSelect.length || !parentSelect.val()) {
                isValid = false;
                errorMessage += '- Veuillez sélectionner un menu parent<br>';
                parentSelect.addClass('is-invalid')
                    .after('<div class="invalid-feedback">Veuillez sélectionner un menu parent</div>');
            }
        }
        
        // Référence (seulement si type n'est pas custom)
        if (type !== 'custom') {
            const referenceSelect = $('#menuReference');
            if (!referenceSelect.length || !referenceSelect.val()) {
                isValid = false;
                const typeLabel = type === 'category' ? 'catégorie' : 'activité';
                errorMessage += `- Veuillez sélectionner une ${typeLabel}<br>`;
                referenceSelect.addClass('is-invalid')
                    .after(`<div class="invalid-feedback">Veuillez sélectionner une ${typeLabel}</div>`);
            }
        }
        
        // Valider que le slug ne contient pas d'espaces
        if (slug && /\s/.test(slug)) {
            isValid = false;
            errorMessage += '- Le slug ne doit pas contenir d\'espaces<br>';
            $('#menuSlug').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le slug ne doit pas contenir d\'espaces</div>');
        }
        
        // Valider le format du slug
        if (slug && !/^[a-z0-9-]+$/.test(slug)) {
            isValid = false;
            errorMessage += '- Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets<br>';
            $('#menuSlug').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets</div>');
        }
        
        if (!isValid) {
            showAlert('danger', errorMessage);
            
            // Scroll vers le premier champ invalide
            const firstInvalid = $('.is-invalid').first();
            if (firstInvalid.length) {
                $('html, body').animate({
                    scrollTop: firstInvalid.offset().top - 100
                }, 500);
                firstInvalid.focus();
            }
        }
        
        return isValid;
    };

    // Fonction updateMenu corrigée
    const updateMenu = () => {
        const form = document.getElementById('editMenuForm');
        const submitBtn = document.getElementById('updateMenuBtn');
        const menuId = document.getElementById('editMenuId').value;
        
        // Validation personnalisée
        if (!validateEditMenuForm()) {
            return;
        }
        
        // Désactiver le bouton et montrer le loader
        $(submitBtn).prop('disabled', true).html(
            '<div class="spinner-border spinner-border-sm me-2"></div>Enregistrement...'
        );
        
        // Récupérer le titre de CKEditor
        let titleContent = '';
        if (CKEDITOR.instances.editMenuTitle) {
            titleContent = CKEDITOR.instances.editMenuTitle.getData().trim();
        } else {
            titleContent = $('#editMenuTitle').val().trim();
        }
        
        // Créer l'objet data
        const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT',
            title: titleContent,
            slug: $('#editMenuSlug').val().trim(),
            type: $('#editMenuType').val(),
            parent_id: $('#editMenuParent').val() || null,
            order: $('#editMenuOrder').val() || 0,
            url: $('#editMenuUrl').val() || '',
            route: $('#editMenuRoute').val() || '',
            icon: $('#editMenuIcon').val() || '',
            is_active: $('#editMenuStatus').val() || 1
        };
        
        // Ajouter reference_id si nécessaire
        const menuType = $('#editMenuType').val();
        if (menuType !== 'custom') {
            data.reference_id = $('#editMenuReference').val() || null;
        }
        
        // Envoyer la requête AJAX
        $.ajax({
            url: `/menus/${menuId}`,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Fermer le modal proprement
                    closeModalProperly('#editMenuModal');
                    
                    // Recharger les données
                    loadMenus(currentPage, currentFilters);
                    loadStatistics();
                    
                    // Afficher le message de succès
                    showAlert('success', 'Menu mis à jour avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la mise à jour');
                }
            },
            error: function(xhr, status, error) {
                console.error('Update error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                let errorMessage = 'Erreur lors de la mise à jour';
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                        for (const field in errors) {
                            errorMessage += `- ${errors[field].join('<br>')}<br>`;
                        }
                        
                        // Marquer les champs invalides
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        
                        for (const field in errors) {
                            $(`[name="${field}"]`).addClass('is-invalid')
                                .after(`<div class="invalid-feedback">${errors[field].join('<br>')}</div>`);
                        }
                    }
                } else if (xhr.status === 404) {
                    errorMessage = 'Menu non trouvé. Veuillez rafraîchir la page.';
                } else if (xhr.status === 419) {
                    errorMessage = 'Session expirée. Veuillez rafraîchir la page.';
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Réactiver le bouton
                $(submitBtn).prop('disabled', false).html(
                    '<i class="fas fa-save me-2"></i>Enregistrer les modifications'
                );
            }
        });
    };

    // Fonction validateEditMenuForm
    const validateEditMenuForm = () => {
        let isValid = true;
        let errorMessage = '';
        
        // Réinitialiser les erreurs
        $('#editMenuForm .is-invalid').removeClass('is-invalid');
        $('#editMenuForm .invalid-feedback').remove();
        
        // Titre (CKEditor ou textarea)
        let title = '';
        if (CKEDITOR.instances.editMenuTitle) {
            title = CKEDITOR.instances.editMenuTitle.getData().trim();
        } else {
            title = $('#editMenuTitle').val().trim();
        }
        
        if (!title) {
            isValid = false;
            errorMessage += '- Le titre est requis<br>';
            $('#editMenuTitle').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le titre est requis</div>');
        }
        
        // Slug
        const slug = $('#editMenuSlug').val().trim();
        if (!slug) {
            isValid = false;
            errorMessage += '- Le slug est requis<br>';
            $('#editMenuSlug').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le slug est requis</div>');
        }
        
        // Type
        const type = $('#editMenuType').val();
        if (!type) {
            isValid = false;
            errorMessage += '- Le type de menu est requis<br>';
            $('#editMenuType').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le type de menu est requis</div>');
        }
        
        // Référence (seulement si type n'est pas custom)
        if (type !== 'custom') {
            const referenceId = $('#editMenuReference').val();
            if (!referenceId) {
                isValid = false;
                const typeLabel = type === 'category' ? 'catégorie' : 'activité';
                errorMessage += `- Veuillez sélectionner une ${typeLabel}<br>`;
                $('#editMenuReference').addClass('is-invalid')
                    .after(`<div class="invalid-feedback">Veuillez sélectionner une ${typeLabel}</div>`);
            }
        }
        
        // Valider le format du slug
        if (slug && !/^[a-z0-9-]+$/.test(slug)) {
            isValid = false;
            errorMessage += '- Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets<br>';
            $('#editMenuSlug').addClass('is-invalid')
                .after('<div class="invalid-feedback">Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets</div>');
        }
        
        if (!isValid) {
            showAlert('danger', errorMessage);
            
            // Scroll vers le premier champ invalide
            const firstInvalid = $('#editMenuForm .is-invalid').first();
            if (firstInvalid.length) {
                $('html, body').animate({
                    scrollTop: firstInvalid.offset().top - 100
                }, 500);
                firstInvalid.focus();
            }
        }
        
        return isValid;
    };

    // Open edit modal
    const openEditModal = (menuId) => {
        const menu = allMenus.find(m => m.id === menuId);
        
        if (menu) {
            document.getElementById('editMenuId').value = menu.id;
            document.getElementById('editMenuSlug').value = menu.slug;
            document.getElementById('editMenuType').value = menu.type;
            document.getElementById('editMenuOrder').value = menu.order;
            document.getElementById('editMenuParent').value = menu.parent_id || '';
            document.getElementById('editMenuReference').value = menu.reference_id || '';
            document.getElementById('editMenuUrl').value = menu.url || '';
            document.getElementById('editMenuRoute').value = menu.route || '';
            document.getElementById('editMenuIcon').value = menu.icon || '';
            document.getElementById('editMenuStatus').value = menu.is_active ? '1' : '0';
            
            // Load parent options
            loadEditParentOptions(menu.parent_id);
            
            // Load reference options based on type
            loadEditReferenceOptions(menu.type);
            
            const editModal = $('#editMenuModal');
            const modalInstance = new bootstrap.Modal(document.getElementById('editMenuModal'));
            
            // Initialiser CKEditor quand le modal est montré
            editModal.on('shown.bs.modal', function() {
                // Détruire l'instance CKEditor existante
                if (CKEDITOR.instances.editMenuTitle) {
                    CKEDITOR.instances.editMenuTitle.destroy(true);
                }
                
                // Vérifier si le textarea existe
                let textarea = document.getElementById('editMenuTitle');
                if (!textarea) {
                    // Créer le textarea si nécessaire
                    textarea = document.createElement('textarea');
                    textarea.id = 'editMenuTitle';
                    textarea.name = 'title';
                    textarea.className = 'form-control-modern ckeditor';
                    textarea.rows = 3;
                    textarea.required = true;
                    
                    // Trouver le bon endroit pour l'insérer
                    const titleLabel = document.querySelector('label[for="editMenuTitle"]');
                    if (titleLabel) {
                        const container = titleLabel.parentElement;
                        if (container) {
                            container.appendChild(textarea);
                        }
                    }
                }
                
                // Initialiser CKEditor
                try {
                    CKEDITOR.replace('editMenuTitle', {
                        toolbar: [
                            ['Bold', 'Italic', 'Underline', 'Strike'],
                            ['NumberedList', 'BulletedList'],
                            ['Link', 'Unlink'],
                            ['RemoveFormat', 'Source']
                        ],
                        height: 150,
                        removePlugins: 'elementspath',
                        resize_enabled: false,
                        startupFocus: false
                    });
                    
                    // Définir le contenu
                    CKEDITOR.instances.editMenuTitle.setData(menu.title || '');
                } catch (error) {
                    console.error('CKEditor error:', error);
                    // Fallback: utiliser le textarea normal
                    if (textarea) {
                        textarea.value = menu.title || '';
                    }
                }
            });
            
            // Nettoyage à la fermeture
            editModal.on('hidden.bs.modal', function() {
                // Détruire CKEditor
                if (CKEDITOR.instances.editMenuTitle) {
                    CKEDITOR.instances.editMenuTitle.destroy(true);
                }
                
                // Nettoyer le backdrop
                cleanupModalBackdrop();
                
                // Réinitialiser le formulaire
                $('#editMenuForm')[0].reset();
            });
            
            // Montrer le modal
            modalInstance.show();
        } else {
            showAlert('danger', 'Menu non trouvé');
        }
    };

    // Load edit parent options
    const loadEditParentOptions = (selectedId) => {
        $.ajax({
            url: '{{ route("menus.all-parents") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const select = document.getElementById('editMenuParent');
                    let options = '<option value="">Aucun (menu principal)</option>';
                    
                    response.data.forEach(parent => {
                        const selected = parent.id == selectedId ? 'selected' : '';
                        options += `<option value="${parent.id}" ${selected}>${parent.title}</option>`;
                    });
                    
                    select.innerHTML = options;
                }
            }
        });
    };

    // Move menu order
    const moveMenu = (menuId, direction) => {
        $.ajax({
            url: `/menus/${menuId}/move/${direction}`,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadMenus(currentPage, currentFilters);
                    showAlert('success', 'Ordre mis à jour');
                }
            }
        });
    };

    // Show delete confirmation
    const showDeleteConfirmation = (menuId) => {
        const menu = allMenus.find(m => m.id === menuId);
        
        if (!menu) {
            showAlert('danger', 'Menu non trouvé');
            return;
        }
        
        menuToDelete = menu;
        
        document.getElementById('menuToDeleteInfo').innerHTML = `
            <div class="menu-info">
                <div class="menu-info-icon">
                    <i class="${menu.icon || 'fas fa-link'} fa-2x"></i>
                </div>
                <div>
                    <div class="menu-info-title">${menu.title}</div>
                    <div class="menu-info-details">
                        <span class="badge ${menu.type}">${getTypeLabel(menu.type)}</span>
                        <span class="badge level">Niveau: ${menu.parent_id ? (menu.parent?.parent_id ? '2' : '1') : '0'}</span>
                    </div>
                </div>
            </div>
        `;
        
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.innerHTML = `
            <span class="btn-text">
                <i class="fas fa-trash me-2"></i>Supprimer définitivement
            </span>
        `;
        deleteBtn.disabled = false;
        
        new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
    };

    // Delete menu
    const deleteMenu = () => {
        if (!menuToDelete) return;
        
        const menuId = menuToDelete.id;
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        
        deleteBtn.innerHTML = `
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="visually-hidden">Suppression...</span>
            </div>
            Suppression...
        `;
        deleteBtn.disabled = true;
        
        $.ajax({
            url: `/menus/${menuId}`,
            type: 'DELETE',
            dataType: 'json',
            success: function(response) {
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                deleteModal.hide();
                
                if (response.success) {
                    allMenus = allMenus.filter(m => m.id !== menuId);
                    loadStatistics();
                    showAlert('success', response.message || 'Menu supprimé avec succès !');
                    
                    setTimeout(() => {
                        loadMenus(currentPage, currentFilters);
                    }, 500);
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la suppression');
                }
            },
            error: function(xhr) {
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                deleteModal.hide();
                
                if (xhr.status === 404) {
                    showAlert('danger', 'Menu non trouvé');
                    loadMenus(currentPage, currentFilters);
                } else {
                    showAlert('danger', 'Erreur lors de la suppression');
                }
            },
            complete: function() {
                menuToDelete = null;
            }
        });
    };

    // Render pagination
    const renderPagination = (response) => {
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('paginationInfo');
        
        if (!response || !response.current_page) {
            paginationContainer.style.display = 'none';
            return;
        }
        
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(response.current_page * response.per_page, response.total);
        paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} menus`;
        
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
        loadMenus(page, currentFilters);
    };

    // Show loading
    const showLoading = () => {
        document.getElementById('loadingSpinner').style.display = 'flex';
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
    };

    // Hide loading
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

    // Helper function to close modal properly
    const closeModalProperly = (modalId) => {
        $(modalId).modal('hide');
        setTimeout(() => {
            cleanupModalBackdrop();
        }, 300);
    };

    // Cleanup modal backdrop
    const cleanupModalBackdrop = () => {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    };

    // Fonction pour générer le slug automatiquement
    const generateSlug = (text) => {
        return text.toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    };

    // Setup event listeners
    const setupEventListeners = () => {
        // Search input
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadMenus(1, currentFilters);
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
                    parent: document.getElementById('filterParent').value,
                    status: document.getElementById('filterStatus').value,
                    sort_by: document.getElementById('filterSortBy').value,
                    sort_direction: 'asc'
                };
                loadMenus(1, currentFilters);
            });
        }
        
        // Clear filters
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                document.getElementById('filterType').value = '';
                document.getElementById('filterParent').value = '';
                document.getElementById('filterStatus').value = '';
                document.getElementById('filterSortBy').value = 'order';
                currentFilters = {};
                loadMenus(1);
            });
        }
        
        // Submit menu form
        const submitMenuBtn = document.getElementById('submitMenuBtn');
        if (submitMenuBtn) {
            submitMenuBtn.addEventListener('click', storeMenu);
        }
        
        // Update menu form
        const updateMenuBtn = document.getElementById('updateMenuBtn');
        if (updateMenuBtn) {
            updateMenuBtn.addEventListener('click', updateMenu);
        }
        
        // Confirm delete button
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', deleteMenu);
        }
        
        // Reset create form when modal is hidden
        const createModal = document.getElementById('createMenuModal');
        if (createModal) {
            createModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createMenuForm').reset();
                document.getElementById('parentSelectionSection').style.display = 'none';
                document.getElementById('contentSelectionSection').style.display = 'none';
                document.getElementById('urlConfigurationSection').style.display = 'block';
                
                // Reset level selector
                document.querySelectorAll('.level-option').forEach(opt => opt.classList.remove('active'));
                document.querySelector('.level-option[data-level="0"]').classList.add('active');
                document.getElementById('selectedLevel').value = '0';
            });
        }
        
        // Auto-generate slug from title
        $('#menuTitle').on('input', function() {
            const title = $(this).val().trim();
            const slugInput = $('#menuSlug');
            
            if (title && (!slugInput.val() || slugInput.data('auto-generated'))) {
                const slug = generateSlug(title);
                slugInput.val(slug);
                slugInput.data('auto-generated', true);
            }
        });
        
        $('#menuSlug').on('input', function() {
            $(this).data('auto-generated', false);
        });
        
        // Gestion des pages
        $(document).on('click', '.toggle-page-btn', function() {
            const menuId = $(this).data('id');
            togglePage(menuId);
        });
        
        $(document).on('click', '.create-page-btn', function() {
            const menuId = $(this).data('id');
            createPage(menuId);
        });
    };

    // Toggle page
    const togglePage = (menuId) => {
        $.ajax({
            url: `/menus/${menuId}/toggle-page`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadMenus(currentPage, currentFilters);
                    showAlert('success', response.message);
                }
            }
        });
    };

    // Create page
    const createPage = (menuId) => {
        window.location.href = `/menus/${menuId}/page`;
    };
</script>

    <style>
        /* Styles spécifiques pour la page menus */
        .menu-tree-container {
            margin-bottom: 20px;
        }

        .menu-tree {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #eaeaea;
        }

        .tree-item {
            margin-bottom: 5px;
        }

        .tree-item-header {
            display: flex;
            align-items: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            transition: all 0.3s ease;
        }

        .tree-item-header:hover {
            background: #f0f7ff;
            border-color: #cfe2ff;
        }

        .tree-item-toggle {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 10px;
            color: #6c757d;
        }

        .tree-item-toggle.has-children:hover {
            color: #007bff;
        }

        .tree-item-content {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tree-item-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 6px;
            color: #495057;
        }

        .tree-item-info {
            flex: 1;
        }

        .tree-item-title {
            font-weight: 600;
            color: #333;
        }

        .tree-item-details {
            display: flex;
            gap: 5px;
            margin-top: 3px;
        }

        .tree-item-actions {
            display: flex;
            gap: 5px;
        }

        .tree-item-children {
            margin-left: 34px;
            padding-left: 10px;
            border-left: 2px dashed #dee2e6;
        }

        .level-1 .tree-item-header {
            background: #f8f9fa;
        }

        .level-2 .tree-item-header {
            background: #e9ecef;
        }

        /* Menu cells */
        .menu-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-icons {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .menu-icons.default-icon {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .menu-name-text {
            font-weight: 600;
            color: #333;
        }

        /* Badges */
        .menu-type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .menu-type-badge.custom {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .menu-type-badge.category {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
        }

        .menu-type-badge.activity {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #333;
        }

        .level-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .level-badge.level-0 {
            background: #e3f2fd;
            color: #1565c0;
        }

        .level-badge.level-1 {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .level-badge.level-2 {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .order-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        /* Menu actions */
        .menu-actions-modern {
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
        }

        .move-up-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .move-up-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
        }

        .move-down-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .move-down-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: #333;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #ff9e1a);
            transform: translateY(-2px);
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
        }

        /* Level selector */
        .level-selector {
            display: flex;
            gap: 10px;
        }

        .level-option {
            flex: 1;
            padding: 15px;
            border: 2px solid #eaeaea;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .level-option:hover {
            border-color: #cfe2ff;
            background: #f0f7ff;
        }

        .level-option.active {
            border-color: #007bff;
            background: #e7f1ff;
        }

        .level-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 8px;
            color: #495057;
        }

        .level-option.active .level-icon {
            background: #007bff;
            color: white;
        }

        .level-info {
            flex: 1;
        }

        .level-title {
            font-weight: 600;
            color: #333;
        }

        .level-description {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .level-check {
            color: #28a745;
            opacity: 0;
        }

        .level-option.active .level-check {
            opacity: 1;
        }

        /* Form sections */
        .form-section-modern {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eaeaea;
        }

        .form-section-modern:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title-modern {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Menu info for delete modal */
        .menu-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .menu-info-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .menu-info-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: #333;
        }

        .menu-info-details {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .level-selector {
                flex-direction: column;
            }
            
            .menu-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .tree-item-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .tree-item-content {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .tree-item-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        @media (max-width: 576px) {
            .menu-name-cell {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .tree-item-children {
                margin-left: 20px;
            }
        }
    </style>
@endsection