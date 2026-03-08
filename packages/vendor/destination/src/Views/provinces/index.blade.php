@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-map-marked-alt"></i></span>
                Gestion des Provinces
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProvinceModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Province
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
                        @foreach($countries ?? [] as $country)
                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="population">Population</option>
                        <option value="area">Superficie</option>
                        <option value="created_at">Date de création</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortDirection" class="form-label-modern">Ordre</label>
                    <select class="form-select-modern" id="filterSortDirection">
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterMinPopulation" class="form-label-modern">Population (min)</label>
                    <input type="number" class="form-control-modern" id="filterMinPopulation" min="0" placeholder="0">
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalProvinces">0</div>
                        <div class="stats-label-modern">Total Provinces</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalPopulation">0</div>
                        <div class="stats-label-modern">Population Totale</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalArea">0</div>
                        <div class="stats-label-modern">Superficie Totale</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-mountain"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalCountries">0</div>
                        <div class="stats-label-modern">Pays représentés</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Provinces</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une province..." id="searchInput">
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
                                <th>Province</th>
                                <th>Pays</th>
                                <th>Capitale</th>
                                <th>Population</th>
                                <th>Superficie</th>
                                <th>Status</th>
                                <th>Pages</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="provincesTableBody">
                            <!-- Provinces will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune province trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première province.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProvinceModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer une province
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createProvinceModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE PROVINCE MODAL -->
    <div class="modal fade" id="createProvinceModal" tabindex="-1" aria-labelledby="createProvinceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createProvinceModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle province
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createProvinceForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provinceName" class="form-label-modern">Nom de la province *</label>
                                <input type="text" class="form-control-modern" id="provinceName" name="name" 
                                       placeholder="Ex: Québec, Ontario, Alberta..." required>
                                <div class="form-text-modern">Nom complet de la province</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="provinceCode" class="form-label-modern">Code</label>
                                <input type="text" class="form-control-modern" id="provinceCode" name="code" 
                                       placeholder="Ex: QC, ON, AB" maxlength="10">
                                <div class="form-text-modern">Code de la province (2-10 lettres)</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="provinceAreaRank" class="form-label-modern">Rang superficie</label>
                                <input type="text" class="form-control-modern" id="provinceAreaRank" name="area_rank" 
                                       placeholder="Ex: 1, 2, 3...">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provinceCountry" class="form-label-modern">Pays *</label>
                                <select class="form-select-modern" id="provinceCountry" name="country_id" required>
                                    <option value="">Sélectionnez un pays</option>
                                    @foreach($countries ?? [] as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }} ({{ $country->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="provinceTimezone" class="form-label-modern">Fuseau horaire</label>
                                <input type="text" class="form-control-modern" id="provinceTimezone" name="timezone" 
                                       placeholder="Ex: UTC-05:00, UTC-07:00">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provinceCapital" class="form-label-modern">Capitale</label>
                                <input type="text" class="form-control-modern" id="provinceCapital" name="capital" 
                                       placeholder="Ex: Québec, Toronto, Edmonton">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="provinceLargestCity" class="form-label-modern">Plus grande ville</label>
                                <input type="text" class="form-control-modern" id="provinceLargestCity" name="largest_city" 
                                       placeholder="Ex: Montréal, Toronto, Calgary">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="provincePopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="provincePopulation" 
                                       name="population" placeholder="Ex: 8574000" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="provinceArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="provinceArea" name="area" 
                                       placeholder="Ex: 1667000" min="0" step="0.01">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="provinceOfficialLanguage" class="form-label-modern">Langue officielle</label>
                                <input type="text" class="form-control-modern" id="provinceOfficialLanguage" name="official_language" 
                                       placeholder="Ex: Français, Anglais">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provinceLatitude" class="form-label-modern">Latitude</label>
                                <input type="text" class="form-control-modern" id="provinceLatitude" name="latitude" 
                                       placeholder="Ex: 52.9399">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="provinceLongitude" class="form-label-modern">Longitude</label>
                                <input type="text" class="form-control-modern" id="provinceLongitude" name="longitude" 
                                       placeholder="Ex: -73.5491">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provinceFlag" class="form-label-modern">Drapeau (URL)</label>
                                <input type="text" class="form-control-modern" id="provinceFlag" name="flag" 
                                       placeholder="Ex: qc-flag.svg, on-flag.svg">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="provinceFlagPreview" class="form-label-modern">Aperçu du drapeau</label>
                                <div id="provinceFlagPreview" class="flag-preview">
                                    <i class="fas fa-flag text-muted"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="provinceDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="provinceDescription" name="description" 
                                      rows="3" placeholder="Description de la province..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitProvinceBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la province
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT PROVINCE MODAL -->
    <div class="modal fade" id="editProvinceModal" tabindex="-1" aria-labelledby="editProvinceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editProvinceModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier la province
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editProvinceForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editProvinceId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceName" class="form-label-modern">Nom de la province *</label>
                                <input type="text" class="form-control-modern" id="editProvinceName" name="name" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editProvinceCode" class="form-label-modern">Code</label>
                                <input type="text" class="form-control-modern" id="editProvinceCode" name="code" maxlength="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editProvinceAreaRank" class="form-label-modern">Rang superficie</label>
                                <input type="text" class="form-control-modern" id="editProvinceAreaRank" name="area_rank">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceCountry" class="form-label-modern">Pays *</label>
                                <select class="form-select-modern" id="editProvinceCountry" name="country_id" required>
                                    <option value="">Sélectionnez un pays</option>
                                    @foreach($countries ?? [] as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }} ({{ $country->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceTimezone" class="form-label-modern">Fuseau horaire</label>
                                <input type="text" class="form-control-modern" id="editProvinceTimezone" name="timezone">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceCapital" class="form-label-modern">Capitale</label>
                                <input type="text" class="form-control-modern" id="editProvinceCapital" name="capital">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceLargestCity" class="form-label-modern">Plus grande ville</label>
                                <input type="text" class="form-control-modern" id="editProvinceLargestCity" name="largest_city">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editProvincePopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="editProvincePopulation" name="population" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editProvinceArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="editProvinceArea" name="area" min="0" step="0.01">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editProvinceOfficialLanguage" class="form-label-modern">Langue officielle</label>
                                <input type="text" class="form-control-modern" id="editProvinceOfficialLanguage" name="official_language">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceLatitude" class="form-label-modern">Latitude</label>
                                <input type="text" class="form-control-modern" id="editProvinceLatitude" name="latitude">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceLongitude" class="form-label-modern">Longitude</label>
                                <input type="text" class="form-control-modern" id="editProvinceLongitude" name="longitude">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceFlag" class="form-label-modern">Drapeau (URL)</label>
                                <input type="text" class="form-control-modern" id="editProvinceFlag" name="flag">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProvinceFlagPreview" class="form-label-modern">Aperçu du drapeau</label>
                                <div id="editProvinceFlagPreview" class="flag-preview">
                                    <i class="fas fa-flag text-muted"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editProvinceDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="editProvinceDescription" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateProvinceBtn">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cette province ? Toutes les régions et villes associées seront également supprimées.</p>
                    
                    <div class="province-to-delete" id="provinceToDeleteInfo">
                        <!-- Province info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible et supprimera toutes les régions et villes de cette province.
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allProvinces = [];
        let provinceToDelete = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadProvinces();
            loadStatistics();
            setupEventListeners();
            setupFlagPreview();
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

        // Load provinces
        const loadProvinces = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("provinces.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    console.log('Provinces response:', response);
                    
                    if (response.success) {
                        allProvinces = response.data || [];
                        renderProvinces(allProvinces);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des provinces');
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
                url: '{{ route("provinces.statistics") }}',
                type: 'GET',
                success: function(response) {
                    console.log('Statistics response:', response);
                    
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalProvinces').textContent = stats.total_provinces || 0;
                        document.getElementById('totalPopulation').textContent = formatNumber(stats.total_population || 0);
                        document.getElementById('totalArea').textContent = formatNumber(stats.total_area || 0);
                        
                        // Nombre de pays représentés
                        const uniqueCountries = stats.by_country ? stats.by_country.length : 0;
                        document.getElementById('totalCountries').textContent = uniqueCountries;
                        
                        // Optionnel: Mettre à jour les statistiques avancées
                        updateAdvancedStats(stats);
                    } else {
                        console.error('Error loading statistics:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Statistics AJAX error:', xhr.responseText, status, error);
                    
                    // Valeurs par défaut
                    document.getElementById('totalProvinces').textContent = '0';
                    document.getElementById('totalPopulation').textContent = '0';
                    document.getElementById('totalArea').textContent = '0';
                    document.getElementById('totalCountries').textContent = '0';
                }
            });
        };

   // Render provinces with modern design
const renderProvinces = (provinces) => {
    const tbody = document.getElementById('provincesTableBody');
    tbody.innerHTML = '';
    
    if (!provinces || !Array.isArray(provinces) || provinces.length === 0) {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
        return;
    }
    
    provinces.forEach((province, index) => {
        const row = document.createElement('tr');
        row.id = `province-row-${province.id}`;
        row.style.animationDelay = `${index * 0.05}s`;
        
        // Format population and area
        const population = province.population ? formatNumber(province.population) : 'N/A';
        const area = province.area ? formatNumber(province.area) : 'N/A';
        const density = province.population && province.area && province.area > 0 
            ? (province.population / province.area).toFixed(2) 
            : null;
        const isActive = province.is_active ? true : false;
        
        row.innerHTML = `
            <td class="province-name-cell">
                <div class="province-name-modern">
                    <div class="province-flag-modern">
                        <div class="province-circle" style="background-color: ${getProvinceColor(province.id)}">
                            ${province.name ? `<span class="province-initial">${province.code}</span>` : `<i class="fas fa-map-marked-alt"></i>`}
                        </div>
                    </div>
                    <div>
                        <div class="province-name-text">${province.name}</div>
                        <small class="text-muted">${province.code ? 'Code: ' + province.code : ''}${province.area_rank ? ' | Rang: ' + province.area_rank : ''}</small>
                    </div>
                </div>
            </td>
            <td>
                <div class="country-badge">
                    <img src="${province.country?.flag || 'default-country-flag.svg'}" 
                         alt="${province.country?.name || 'N/A'}" width="24" height="24" 
                         class="country-flag-img-modern">
                    ${province.country?.name || 'N/A'}
                </div>
            </td>
            <td>
                <div>${province.capital || 'N/A'}</div>
                <small class="text-muted">${province.largest_city ? 'Plus grande: ' + province.largest_city : ''}</small>
            </td>
            <td>
                <div>${population}</div>
                <small class="text-muted">${density ? density + ' hab/km²' : ''}</small>
            </td>
            <td>
                <div>${area}</div>
                <small class="text-muted">km²</small>
            </td>
            <td>
                <div class="status-toggle-container">
                    <div class="toggle-switch ${isActive ? 'active' : ''}" 
                         onclick="toggleProvinceStatus(${province.id}, ${isActive})">
                        <div class="toggle-slider"></div>
                    </div>
                    <span class="status-text ${isActive ? 'text-success' : 'text-danger'}">
                        ${isActive ? 'Actif' : 'Inactif'}
                    </span>
                </div>
            </td>
            <td>
                <div class="province-actions-modern">
                    <a href="{{ url('countrie') }}/${province.country?.code}/${province.code}" 
                       class="action-btn-modern view-btn-modern" target="_blank" title="Afficher la page">
                        <i class="fas fa-globe"></i>
                    </a>
                    <a href="{{url('provinces/page')}}/${province.id}" 
                       class="action-btn-modern pages-btn-modern" title="Gérer les pages">
                        <i class="fas fa-file-alt"></i>
                    </a>
                    <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                            onclick="openEditModal(${province.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                            onclick="showDeleteConfirmation(${province.id})">
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

// Toggle province status
const toggleProvinceStatus = (provinceId, currentStatus) => {
    // Find the toggle element
    const toggleElement = document.querySelector(`#province-row-${provinceId} .toggle-switch`);
    const statusText = document.querySelector(`#province-row-${provinceId} .status-text`);
    
    if (!toggleElement || !statusText) return;
    
    // Disable toggle during request
    toggleElement.style.pointerEvents = 'none';
    toggleElement.classList.add('loading');
    
    const newStatus = !currentStatus;
    
    console.log('Toggle province status - Début:', {
        provinceId,
        currentStatus,
        newStatus
    });
    
    // Send AJAX request
    $.ajax({
        url: `/provinces/${provinceId}/toggle-status`,
        type: 'PUT',
        data: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            is_active: newStatus
        },
        dataType: 'json',
        success: function(response) {
            console.log('Toggle status - Réponse:', response);
            
            if (response.success) {
                // Update UI
                if (newStatus) {
                    toggleElement.classList.add('active');
                    statusText.textContent = 'Actif';
                    statusText.classList.remove('text-danger');
                    statusText.classList.add('text-success');
                } else {
                    toggleElement.classList.remove('active');
                    statusText.textContent = 'Inactif';
                    statusText.classList.remove('text-success');
                    statusText.classList.add('text-danger');
                }
                
                // Update the province in the array
                const provinceIndex = allProvinces.findIndex(p => p.id === provinceId);
                if (provinceIndex !== -1) {
                    allProvinces[provinceIndex].is_active = newStatus;
                }
                
                // Show success message
                showAlert('success', `Province ${newStatus ? 'activée' : 'désactivée'} avec succès !`);
            } else {
                showAlert('danger', response.message || 'Erreur lors de la mise à jour du statut');
                // Revert toggle if error
                toggleElement.style.pointerEvents = 'auto';
                toggleElement.classList.remove('loading');
            }
        },
        error: function(xhr, status, error) {
            console.error('Toggle status - Erreur:', {
                status: xhr.status,
                error: error,
                responseText: xhr.responseText
            });
            
            showAlert('danger', 'Erreur lors de la mise à jour du statut: ' + error);
            // Revert toggle on error
            toggleElement.style.pointerEvents = 'auto';
            toggleElement.classList.remove('loading');
        },
        complete: function() {
            // Re-enable toggle after delay
            setTimeout(() => {
                toggleElement.style.pointerEvents = 'auto';
                toggleElement.classList.remove('loading');
            }, 500);
        }
    });
};

        function getProvinceColor(provinceId) {
    const colors = [
        '#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8',
        '#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#6610f2'
    ];
    return colors[provinceId % colors.length] || '#007bff';
}

        // Show delete confirmation modal
        const showDeleteConfirmation = (provinceId) => {
            const province = allProvinces.find(p => p.id === provinceId);
            
            if (!province) {
                showAlert('danger', 'Province non trouvée');
                return;
            }
            
            provinceToDelete = province;
            
            document.getElementById('provinceToDeleteInfo').innerHTML = `
                <div class="province-info">
                    <div class="province-info-flag">
                        ${province.flag ? `<img src="${province.flag}" alt="${province.name}" class="flag-img-large">` : `<i class="fas fa-map-marked-alt fa-2x"></i>`}
                    </div>
                    <div>
                        <div class="province-info-name">${province.name}</div>
                        <div class="province-info-code">${province.code ? 'Code: ' + province.code : ''}</div>
                    </div>
                </div>
                <div class="row small text-muted">
                    <div class="col-6">
                        <div><strong>Pays:</strong> ${province.country?.name || 'N/A'}</div>
                        <div><strong>Population:</strong> ${formatNumber(province.population)}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Superficie:</strong> ${formatNumber(province.area)} km²</div>
                        <div><strong>Capitale:</strong> ${province.capital || 'N/A'}</div>
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

        // Delete province
        const deleteProvince = () => {
            if (!provinceToDelete) {
                showAlert('danger', 'Aucune province à supprimer');
                return;
            }
            
            const provinceId = provinceToDelete.id;
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
            
            // Add deleting animation to table row
            const row = document.getElementById(`province-row-${provinceId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/provinces/${provinceId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove province from array
                        allProvinces = allProvinces.filter(p => p.id !== provinceId);
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Province supprimée avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('provincesTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadProvinces(currentPage, currentFilters);
                            }, 500);
                        }
                    } else {
                        if (row) row.classList.remove('deleting-row');
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr, status, error) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    // Remove deleting animation
                    const row = document.getElementById(`province-row-${provinceId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Province non trouvée.');
                        loadProvinces(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    provinceToDelete = null;
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
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} provinces`;
            
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
            loadProvinces(page, currentFilters);
        };

        // Store province
        const storeProvince = () => {
            const form = document.getElementById('createProvinceForm');
            const submitBtn = document.getElementById('submitProvinceBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Créer la province
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Création en cours...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            // Convert FormData to object
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            $.ajax({
                url: '{{ route("provinces.store") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la province
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createProvinceModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        resetFlagPreview();
                        
                        // Reload provinces
                        loadProvinces(1, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Province créée avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création');
                    }
                },
                error: function(xhr, status, error) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la province
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
                        showAlert('danger', 'Erreur lors de la création: ' + error);
                    }
                }
            });
        };

        // Update province
        const updateProvince = () => {
            const form = document.getElementById('editProvinceForm');
            const submitBtn = document.getElementById('updateProvinceBtn');
            const provinceId = document.getElementById('editProvinceId').value;
            
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
            
            // Convert FormData to object
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            data._method = 'PUT';
            
            $.ajax({
                url: `/provinces/${provinceId}`,
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editProvinceModal'));
                        modal.hide();
                        
                        // Reload provinces
                        loadProvinces(currentPage, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Province mise à jour avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la mise à jour');
                    }
                },
                error: function(xhr, status, error) {
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
                        showAlert('danger', 'Erreur lors de la mise à jour: ' + error);
                    }
                }
            });
        };

        // Open edit modal
        const openEditModal = (provinceId) => {
            const province = allProvinces.find(p => p.id === provinceId);
            
            if (province) {
                document.getElementById('editProvinceId').value = province.id;
                document.getElementById('editProvinceName').value = province.name;
                document.getElementById('editProvinceCode').value = province.code || '';
                document.getElementById('editProvinceAreaRank').value = province.area_rank || '';
                document.getElementById('editProvinceCountry').value = province.country_id || '';
                document.getElementById('editProvinceTimezone').value = province.timezone || '';
                document.getElementById('editProvinceCapital').value = province.capital || '';
                document.getElementById('editProvinceLargestCity').value = province.largest_city || '';
                document.getElementById('editProvincePopulation').value = province.population || '';
                document.getElementById('editProvinceArea').value = province.area || '';
                document.getElementById('editProvinceOfficialLanguage').value = province.official_language || '';
                document.getElementById('editProvinceLatitude').value = province.latitude || '';
                document.getElementById('editProvinceLongitude').value = province.longitude || '';
                document.getElementById('editProvinceFlag').value = province.flag || '';
                document.getElementById('editProvinceDescription').value = province.description || '';
                
                // Update flag preview
                updateFlagPreview('edit', province.flag);
                
                new bootstrap.Modal(document.getElementById('editProvinceModal')).show();
            }
        };

        // Setup flag preview
        const setupFlagPreview = () => {
            const createFlagInput = document.getElementById('provinceFlag');
            const editFlagInput = document.getElementById('editProvinceFlag');
            
            if (createFlagInput) {
                createFlagInput.addEventListener('input', function() {
                    updateFlagPreview('create', this.value);
                });
            }
            
            if (editFlagInput) {
                editFlagInput.addEventListener('input', function() {
                    updateFlagPreview('edit', this.value);
                });
            }
        };

        // Update flag preview
        const updateFlagPreview = (type, flagUrl) => {
            const previewId = type === 'create' ? 'provinceFlagPreview' : 'editProvinceFlagPreview';
            const preview = document.getElementById(previewId);
            
            if (preview) {
                if (flagUrl && flagUrl.trim() !== '') {
                    preview.innerHTML = `<img src="${flagUrl}" alt="Drapeau" class="flag-preview-img" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'fas fa-flag text-muted\\'></i>';">`;
                } else {
                    preview.innerHTML = '<i class="fas fa-flag text-muted"></i>';
                }
            }
        };

        // Reset flag preview
        const resetFlagPreview = () => {
            const preview = document.getElementById('provinceFlagPreview');
            if (preview) {
                preview.innerHTML = '<i class="fas fa-flag text-muted"></i>';
            }
        };

        // Format number
        const formatNumber = (num) => {
            if (num === null || num === undefined) return 'N/A';
            
            // Convertir en nombre si c'est une chaîne
            const number = typeof num === 'string' ? parseFloat(num) : num;
            
            if (isNaN(number)) return 'N/A';
            
            // Formater avec séparateurs de milliers
            return new Intl.NumberFormat('fr-FR').format(number);
        };

        // Update advanced stats
        const updateAdvancedStats = (stats) => {
            const advancedStatsContainer = document.getElementById('advancedStats') || createAdvancedStatsContainer();
            
            const html = `
                <div class="advanced-stats-grid">
                    <div class="advanced-stat-card">
                        <div class="advanced-stat-title">Province la plus peuplée</div>
                        <div class="advanced-stat-value">
                            ${stats.most_populous ? stats.most_populous.name : 'N/A'}
                        </div>
                        <div class="advanced-stat-subtext">
                            ${stats.most_populous ? formatNumber(stats.most_populous.population) + ' habitants' : ''}
                        </div>
                    </div>
                    
                    <div class="advanced-stat-card">
                        <div class="advanced-stat-title">Province la plus grande</div>
                        <div class="advanced-stat-value">
                            ${stats.largest ? stats.largest.name : 'N/A'}
                        </div>
                        <div class="advanced-stat-subtext">
                            ${stats.largest ? formatNumber(stats.largest.area) + ' km²' : ''}
                        </div>
                    </div>
                    
                    <div class="advanced-stat-card">
                        <div class="advanced-stat-title">Province la plus petite</div>
                        <div class="advanced-stat-value">
                            ${stats.smallest ? stats.smallest.name : 'N/A'}
                        </div>
                        <div class="advanced-stat-subtext">
                            ${stats.smallest ? formatNumber(stats.smallest.area) + ' km²' : ''}
                        </div>
                    </div>
                    
                    <div class="advanced-stat-card">
                        <div class="advanced-stat-title">Densité la plus élevée</div>
                        <div class="advanced-stat-value">
                            ${stats.density?.highest ? stats.density.highest.name : 'N/A'}
                        </div>
                        <div class="advanced-stat-subtext">
                            ${stats.density?.highest ? stats.density.highest.density?.toFixed(2) + ' hab/km²' : ''}
                        </div>
                    </div>
                </div>
            `;
            
            advancedStatsContainer.innerHTML = html;
        };

        // Create advanced stats container
        const createAdvancedStatsContainer = () => {
            const container = document.createElement('div');
            container.id = 'advancedStats';
            container.className = 'advanced-stats-section';
            
            // Insérer après les cartes de statistiques
            const statsGrid = document.querySelector('.stats-grid');
            if (statsGrid) {
                statsGrid.parentNode.insertBefore(container, statsGrid.nextSibling);
            }
            
            return container;
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
                        loadProvinces(1, currentFilters);
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
                        sort_by: document.getElementById('filterSortBy').value,
                        sort_direction: document.getElementById('filterSortDirection').value,
                        min_population: document.getElementById('filterMinPopulation').value
                    };
                    loadProvinces(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterCountry').value = '';
                    document.getElementById('filterSortBy').value = 'name';
                    document.getElementById('filterSortDirection').value = 'asc';
                    document.getElementById('filterMinPopulation').value = '';
                    currentFilters = {};
                    loadProvinces(1);
                });
            }
            
            // Submit province form
            const submitProvinceBtn = document.getElementById('submitProvinceBtn');
            if (submitProvinceBtn) {
                submitProvinceBtn.addEventListener('click', storeProvince);
            }
            
            // Update province form
            const updateProvinceBtn = document.getElementById('updateProvinceBtn');
            if (updateProvinceBtn) {
                updateProvinceBtn.addEventListener('click', updateProvince);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteProvince);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    provinceToDelete = null;
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
            const createModal = document.getElementById('createProvinceModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createProvinceForm').reset();
                    const submitBtn = document.getElementById('submitProvinceBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la province
                        </span>
                    `;
                    submitBtn.disabled = false;
                    resetFlagPreview();
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editProvinceModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    const submitBtn = document.getElementById('updateProvinceBtn');
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
        /* Styles spécifiques pour la page provinces */
        .province-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .province-flag-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border: 1px solid #eaeaea;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .flag-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .province-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .country-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #495057;
            font-weight: 500;
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .province-actions-modern {
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

        .view-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .view-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 71, 111, 0.3);
        }

        .country-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .country-info-flag {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #eaeaea;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .flag-img-large {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .country-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
        }

        .country-info-code {
            font-size: 0.9rem;
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

        /* Advanced stats */
        .advanced-stats-section {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .advanced-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .advanced-stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
            transition: transform 0.3s ease;
        }

        .advanced-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .advanced-stat-title {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .advanced-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .advanced-stat-subtext {
            font-size: 0.9rem;
            color: #888;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .country-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .country-flag-modern {
                width: 35px;
                height: 35px;
            }
            
            .country-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .advanced-stats-grid {
                grid-template-columns: 1fr;
            }
        }
        /* Style pour le cercle de province */
.province-flag-modern {
    display: flex;
    align-items: center;
    justify-content: center;
}

.province-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.province-initial {
    color: white;
    font-weight: bold;
    font-size: 18px;
    text-transform: uppercase;
}

.province-circle i {
    color: white;
    font-size: 18px;
}
/* Toggle Switch Styles */
.status-toggle-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.toggle-switch {
    width: 60px;
    height: 30px;
    background-color: #ccc;
    border-radius: 15px;
    position: relative;
    cursor: pointer;
    transition: background-color 0.3s;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.toggle-switch.active {
    background-color: #28a745;
}

.toggle-slider {
    width: 26px;
    height: 26px;
    background-color: white;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: transform 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.toggle-switch.active .toggle-slider {
    transform: translateX(30px);
}

.status-text {
    font-weight: 500;
    font-size: 0.9rem;
    min-width: 50px;
}

.text-success {
    color: #28a745 !important;
}

.text-danger {
    color: #dc3545 !important;
}

/* Loading state for toggle */
.toggle-switch.loading {
    opacity: 0.7;
    cursor: not-allowed;
}

.toggle-switch.loading .toggle-slider {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* New pages button style */
.pages-btn-modern {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.pages-btn-modern:hover {
    background: linear-gradient(135deg, #8e44ad, #7d3c98);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(155, 89, 182, 0.3);
    color: white;
    text-decoration: none;
}

/* Responsive adjustments for toggle */
@media (max-width: 768px) {
    .status-toggle-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .toggle-switch {
        width: 50px;
        height: 25px;
    }
    
    .toggle-switch.active .toggle-slider {
        transform: translateX(25px);
    }
    
    .toggle-slider {
        width: 21px;
        height: 21px;
    }
    
    .province-actions-modern {
        flex-direction: column;
        gap: 5px;
    }
    
    .action-btn-modern, .pages-btn-modern {
        width: 100%;
        height: 36px;
    }
}
    </style>
@endsection