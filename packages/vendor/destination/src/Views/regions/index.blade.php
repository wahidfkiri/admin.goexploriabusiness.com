@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-mountain"></i></span>
                Gestion des Régions
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRegionModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Région
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
                    <label for="filterProvince" class="form-label-modern">Province</label>
                    <select class="form-select-modern" id="filterProvince">
                        <option value="">Toutes les provinces</option>
                        @foreach($provinces ?? [] as $province)
                            <option value="{{ $province->code }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterClassification" class="form-label-modern">Classification</label>
                    <select class="form-select-modern" id="filterClassification">
                        <option value="">Toutes les classifications</option>
                        @foreach($classifications ?? [] as $classification)
                            @if($classification)
                                <option value="{{ $classification }}">{{ $classification }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="population">Population</option>
                        <option value="area">Superficie</option>
                        <option value="municipalities_count">Municipalités</option>
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
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalRegions">0</div>
                        <div class="stats-label-modern">Total Régions</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-mountain"></i>
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
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalMunicipalities">0</div>
                        <div class="stats-label-modern">Municipalités</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Régions</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une région..." id="searchInput">
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
                                <th>Région</th>
                                <th>Province</th>
                                <th>Capitale</th>
                                <th>Classification</th>
                                <th>Population</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="regionsTableBody">
                            <!-- Regions will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune région trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première région.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRegionModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer une région
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createRegionModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE REGION MODAL -->
    <div class="modal fade" id="createRegionModal" tabindex="-1" aria-labelledby="createRegionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createRegionModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle région
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createRegionForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regionName" class="form-label-modern">Nom de la région *</label>
                                <input type="text" class="form-control-modern" id="regionName" name="name" 
                                       placeholder="Ex: Estrie, Montérégie, Côte-Nord..." required>
                                <div class="form-text-modern">Nom complet de la région</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="regionCode" class="form-label-modern">Code</label>
                                <input type="text" class="form-control-modern" id="regionCode" name="code" 
                                       placeholder="Ex: 05, 16, 09" maxlength="10">
                                <div class="form-text-modern">Code de la région</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="regionClassification" class="form-label-modern">Classification</label>
                                <input type="text" class="form-control-modern" id="regionClassification" name="classification" 
                                       placeholder="Ex: Administrative, Touristique, Naturelle...">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regionProvince" class="form-label-modern">Province *</label>
                                <select class="form-select-modern" id="regionProvince" name="province_id" required>
                                    <option value="">Sélectionnez une province</option>
                                    @foreach($provinces ?? [] as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }} ({{ $province->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="regionTimezone" class="form-label-modern">Fuseau horaire</label>
                                <input type="text" class="form-control-modern" id="regionTimezone" name="timezone" 
                                       placeholder="Ex: UTC-05:00, UTC-07:00">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regionCapital" class="form-label-modern">Capitale</label>
                                <input type="text" class="form-control-modern" id="regionCapital" name="capital" 
                                       placeholder="Ex: Sherbrooke, Saint-Jean-sur-Richelieu">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="regionLargestCity" class="form-label-modern">Plus grande ville</label>
                                <input type="text" class="form-control-modern" id="regionLargestCity" name="largest_city" 
                                       placeholder="Ex: Longueuil, Sherbrooke">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="regionPopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="regionPopulation" 
                                       name="population" placeholder="Ex: 1500000" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="regionArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="regionArea" name="area" 
                                       placeholder="Ex: 11231.35" min="0" step="0.01">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="regionMunicipalities" class="form-label-modern">Municipalités</label>
                                <input type="number" class="form-control-modern" id="regionMunicipalities" 
                                       name="municipalities_count" placeholder="Ex: 176" min="0">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regionLatitude" class="form-label-modern">Latitude</label>
                                <input type="number" class="form-control-modern" id="regionLatitude" name="latitude" 
                                       placeholder="Ex: 45.5017" step="any" min="-90" max="90">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="regionLongitude" class="form-label-modern">Longitude</label>
                                <input type="number" class="form-control-modern" id="regionLongitude" name="longitude" 
                                       placeholder="Ex: -73.5673" step="any" min="-180" max="180">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regionFlag" class="form-label-modern">Drapeau/Image (URL)</label>
                                <input type="text" class="form-control-modern" id="regionFlag" name="flag" 
                                       placeholder="Ex: estrie-flag.svg, monteregie-flag.png">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="regionFlagPreview" class="form-label-modern">Aperçu</label>
                                <div id="regionFlagPreview" class="flag-preview">
                                    <i class="fas fa-mountain text-muted"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="regionDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="regionDescription" name="description" 
                                      rows="2" placeholder="Brève description de la région..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="regionGeography" class="form-label-modern">Géographie</label>
                                <textarea class="form-control-modern" id="regionGeography" name="geography" 
                                          rows="2" placeholder="Description géographique..."></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="regionEconomy" class="form-label-modern">Économie</label>
                                <textarea class="form-control-modern" id="regionEconomy" name="economy" 
                                          rows="2" placeholder="Secteurs économiques..."></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="regionTourism" class="form-label-modern">Tourisme</label>
                                <textarea class="form-control-modern" id="regionTourism" name="tourism" 
                                          rows="2" placeholder="Attractions touristiques..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitRegionBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la région
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT REGION MODAL -->
    <div class="modal fade" id="editRegionModal" tabindex="-1" aria-labelledby="editRegionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editRegionModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier la région
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editRegionForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editRegionId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editRegionName" class="form-label-modern">Nom de la région *</label>
                                <input type="text" class="form-control-modern" id="editRegionName" name="name" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editRegionCode" class="form-label-modern">Code</label>
                                <input type="text" class="form-control-modern" id="editRegionCode" name="code" maxlength="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editRegionClassification" class="form-label-modern">Classification</label>
                                <input type="text" class="form-control-modern" id="editRegionClassification" name="classification">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editRegionProvince" class="form-label-modern">Province *</label>
                                <select class="form-select-modern" id="editRegionProvince" name="province_id" required>
                                    <option value="">Sélectionnez une province</option>
                                    @foreach($provinces ?? [] as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }} ({{ $province->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editRegionTimezone" class="form-label-modern">Fuseau horaire</label>
                                <input type="text" class="form-control-modern" id="editRegionTimezone" name="timezone">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editRegionCapital" class="form-label-modern">Capitale</label>
                                <input type="text" class="form-control-modern" id="editRegionCapital" name="capital">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editRegionLargestCity" class="form-label-modern">Plus grande ville</label>
                                <input type="text" class="form-control-modern" id="editRegionLargestCity" name="largest_city">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editRegionPopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="editRegionPopulation" name="population" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editRegionArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="editRegionArea" name="area" min="0" step="0.01">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editRegionMunicipalities" class="form-label-modern">Municipalités</label>
                                <input type="number" class="form-control-modern" id="editRegionMunicipalities" name="municipalities_count" min="0">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editRegionLatitude" class="form-label-modern">Latitude</label>
                                <input type="number" class="form-control-modern" id="editRegionLatitude" name="latitude" step="any" min="-90" max="90">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editRegionLongitude" class="form-label-modern">Longitude</label>
                                <input type="number" class="form-control-modern" id="editRegionLongitude" name="longitude" step="any" min="-180" max="180">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editRegionFlag" class="form-label-modern">Drapeau/Image (URL)</label>
                                <input type="text" class="form-control-modern" id="editRegionFlag" name="flag">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editRegionFlagPreview" class="form-label-modern">Aperçu</label>
                                <div id="editRegionFlagPreview" class="flag-preview">
                                    <i class="fas fa-mountain text-muted"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editRegionDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="editRegionDescription" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editRegionGeography" class="form-label-modern">Géographie</label>
                                <textarea class="form-control-modern" id="editRegionGeography" name="geography" rows="2"></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editRegionEconomy" class="form-label-modern">Économie</label>
                                <textarea class="form-control-modern" id="editRegionEconomy" name="economy" rows="2"></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editRegionTourism" class="form-label-modern">Tourisme</label>
                                <textarea class="form-control-modern" id="editRegionTourism" name="tourism" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateRegionBtn">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cette région ? Toutes les villes et secteurs associés seront également supprimés.</p>
                    
                    <div class="region-to-delete" id="regionToDeleteInfo">
                        <!-- Region info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible et supprimera toutes les villes et secteurs de cette région.
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
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allRegions = [];
        let regionToDelete = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadRegions();
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

        // Load regions
        const loadRegions = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("regions.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    console.log('Regions response:', response);
                    
                    if (response.success) {
                        allRegions = response.data || [];
                        renderRegions(allRegions);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des régions');
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
                url: '{{ route("regions.statistics") }}',
                type: 'GET',
                success: function(response) {
                    console.log('Statistics response:', response);
                    
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalRegions').textContent = stats.total_regions || 0;
                        document.getElementById('totalPopulation').textContent = formatNumber(stats.total_population || 0);
                        document.getElementById('totalArea').textContent = formatNumber(stats.total_area || 0);
                        document.getElementById('totalMunicipalities').textContent = formatNumber(stats.total_municipalities || 0);
                        
                        // Mettre à jour les statistiques avancées
                        updateAdvancedStats(stats);
                    } else {
                        console.error('Error loading statistics:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Statistics AJAX error:', xhr.responseText, status, error);
                    
                    // Valeurs par défaut
                    document.getElementById('totalRegions').textContent = '0';
                    document.getElementById('totalPopulation').textContent = '0';
                    document.getElementById('totalArea').textContent = '0';
                    document.getElementById('totalMunicipalities').textContent = '0';
                }
            });
        };

        // Render regions with modern design
        const renderRegions = (regions) => {
            const tbody = document.getElementById('regionsTableBody');
            tbody.innerHTML = '';
            
            if (!regions || !Array.isArray(regions) || regions.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            regions.forEach((region, index) => {
                const row = document.createElement('tr');
                row.id = `region-row-${region.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                // Format data
                const population = region.population ? formatNumber(region.population) : 'N/A';
                const area = region.area ? formatNumber(region.area) + ' km²' : 'N/A';
                const municipalities = region.municipalities_count ? formatNumber(region.municipalities_count) : 'N/A';
                
                row.innerHTML = `
                    <td class="region-name-cell">
                        <div class="region-name-modern">
                           <div class="region-flag-modern">
    <div class="region-circle" style="background-color: ${getRegionColor(region.id)}">
        ${region.name ? `<span class="region-initial">${region.name.charAt(0).toUpperCase()}</span>` : `<i class="fas fa-map-marked-alt"></i>`}
    </div>
</div>
                            <div>
                                <div class="region-name-text">${region.name}</div>
                                <small class="text-muted">${region.code ? 'Code: ' + region.code : ''} | Municipalités: ${municipalities}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="province-badge">
                            <i class="fas fa-map-marked-alt me-1"></i>
                            ${region.province?.name || 'N/A'}
                        </div>
                        <small class="text-muted">${region.province?.code || ''}</small>
                    </td>
                    <td>
                        <div>${region.capital || 'N/A'}</div>
                        <small class="text-muted">${region.largest_city ? 'Plus grande: ' + region.largest_city : ''}</small>
                    </td>
                    <td>
                        <span class="classification-badge ${getClassificationClass(region.classification)}">
                            ${region.classification || 'Non spécifiée'}
                        </span>
                    </td>
                    <td>
                        <div>${population}</div>
                        <small class="text-muted">${region.population_density ? region.formatted_population_density : ''}</small>
                    </td>
                    <td>
                        <div class="region-actions-modern">
                            <a href="{{ url('regions.view') }}/${region.country?.code}/${region.province?.code}/${region.id}" 
                               class="action-btn-modern view-btn-modern" title="Voir détails">
                                <i class="fas fa-globe"></i>
                            </a>
                            <a href="{{ route('regions.show', '') }}/${region.id}" 
                               class="action-btn-modern view-btn-modern" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                    onclick="openEditModal(${region.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteConfirmation(${region.id})">
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

 function getRegionColor(regionId) {
    const colors = [
        '#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8',
        '#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#6610f2'
    ];
    return colors[regionId % colors.length] || '#007bff';
}
        // Get classification CSS class
        const getClassificationClass = (classification) => {
            if (!classification) return '';
            
            classification = classification.toLowerCase();
            
            if (classification.includes('administrative')) return 'classification-admin';
            if (classification.includes('touristique')) return 'classification-tourist';
            if (classification.includes('naturelle')) return 'classification-nature';
            if (classification.includes('urbaine')) return 'classification-urban';
            if (classification.includes('rurale')) return 'classification-rural';
            
            return '';
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (regionId) => {
            const region = allRegions.find(r => r.id === regionId);
            
            if (!region) {
                showAlert('danger', 'Région non trouvée');
                return;
            }
            
            regionToDelete = region;
            
            document.getElementById('regionToDeleteInfo').innerHTML = `
                <div class="region-info">
                    <div class="region-info-flag">
                        ${region.flag ? `<img src="${region.flag}" alt="${region.name}" class="flag-img-large">` : `<i class="fas fa-mountain fa-2x"></i>`}
                    </div>
                    <div>
                        <div class="region-info-name">${region.name}</div>
                        <div class="region-info-code">${region.code ? 'Code: ' + region.code : ''} | ${region.classification ? 'Classification: ' + region.classification : ''}</div>
                    </div>
                </div>
                <div class="row small text-muted">
                    <div class="col-6">
                        <div><strong>Province:</strong> ${region.province?.name || 'N/A'}</div>
                        <div><strong>Population:</strong> ${formatNumber(region.population)}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Superficie:</strong> ${formatNumber(region.area)} km²</div>
                        <div><strong>Capitale:</strong> ${region.capital || 'N/A'}</div>
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

        // Delete region
        const deleteRegion = () => {
            if (!regionToDelete) {
                showAlert('danger', 'Aucune région à supprimer');
                return;
            }
            
            const regionId = regionToDelete.id;
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
            const row = document.getElementById(`region-row-${regionId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/regions/${regionId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove region from array
                        allRegions = allRegions.filter(r => r.id !== regionId);
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Région supprimée avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('regionsTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadRegions(currentPage, currentFilters);
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
                    const row = document.getElementById(`region-row-${regionId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Région non trouvée.');
                        loadRegions(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    regionToDelete = null;
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
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} régions`;
            
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
            loadRegions(page, currentFilters);
        };

        // Store region
        const storeRegion = () => {
            const form = document.getElementById('createRegionForm');
            const submitBtn = document.getElementById('submitRegionBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Créer la région
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
                url: '{{ route("regions.store") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la région
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createRegionModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        resetFlagPreview();
                        
                        // Reload regions
                        loadRegions(1, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Région créée avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création');
                    }
                },
                error: function(xhr, status, error) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la région
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

        // Update region
        const updateRegion = () => {
            const form = document.getElementById('editRegionForm');
            const submitBtn = document.getElementById('updateRegionBtn');
            const regionId = document.getElementById('editRegionId').value;
            
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
                url: `/regions/${regionId}`,
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editRegionModal'));
                        modal.hide();
                        
                        // Reload regions
                        loadRegions(currentPage, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Région mise à jour avec succès !');
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
        const openEditModal = (regionId) => {
            const region = allRegions.find(r => r.id === regionId);
            
            if (region) {
                document.getElementById('editRegionId').value = region.id;
                document.getElementById('editRegionName').value = region.name;
                document.getElementById('editRegionCode').value = region.code || '';
                document.getElementById('editRegionClassification').value = region.classification || '';
                document.getElementById('editRegionProvince').value = region.province_id || '';
                document.getElementById('editRegionTimezone').value = region.timezone || '';
                document.getElementById('editRegionCapital').value = region.capital || '';
                document.getElementById('editRegionLargestCity').value = region.largest_city || '';
                document.getElementById('editRegionPopulation').value = region.population || '';
                document.getElementById('editRegionArea').value = region.area || '';
                document.getElementById('editRegionMunicipalities').value = region.municipalities_count || '';
                document.getElementById('editRegionLatitude').value = region.latitude || '';
                document.getElementById('editRegionLongitude').value = region.longitude || '';
                document.getElementById('editRegionFlag').value = region.flag || '';
                document.getElementById('editRegionDescription').value = region.description || '';
                document.getElementById('editRegionGeography').value = region.geography || '';
                document.getElementById('editRegionEconomy').value = region.economy || '';
                document.getElementById('editRegionTourism').value = region.tourism || '';
                
                // Update flag preview
                updateFlagPreview('edit', region.flag);
                
                new bootstrap.Modal(document.getElementById('editRegionModal')).show();
            }
        };

        // Setup flag preview
        const setupFlagPreview = () => {
            const createFlagInput = document.getElementById('regionFlag');
            const editFlagInput = document.getElementById('editRegionFlag');
            
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
            const previewId = type === 'create' ? 'regionFlagPreview' : 'editRegionFlagPreview';
            const preview = document.getElementById(previewId);
            
            if (preview) {
                if (flagUrl && flagUrl.trim() !== '') {
                    preview.innerHTML = `<img src="${flagUrl}" alt="Drapeau" class="flag-preview-img" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'fas fa-mountain text-muted\\'></i>';">`;
                } else {
                    preview.innerHTML = '<i class="fas fa-mountain text-muted"></i>';
                }
            }
        };

        // Reset flag preview
        const resetFlagPreview = () => {
            const preview = document.getElementById('regionFlagPreview');
            if (preview) {
                preview.innerHTML = '<i class="fas fa-mountain text-muted"></i>';
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
                        <div class="advanced-stat-title">Région la plus peuplée</div>
                        <div class="advanced-stat-value">
                            ${stats.most_populous ? stats.most_populous.name : 'N/A'}
                        </div>
                        <div class="advanced-stat-subtext">
                            ${stats.most_populous ? formatNumber(stats.most_populous.population) + ' habitants' : ''}
                        </div>
                    </div>
                    
                    <div class="advanced-stat-card">
                        <div class="advanced-stat-title">Région la plus grande</div>
                        <div class="advanced-stat-value">
                            ${stats.largest ? stats.largest.name : 'N/A'}
                        </div>
                        <div class="advanced-stat-subtext">
                            ${stats.largest ? formatNumber(stats.largest.area) + ' km²' : ''}
                        </div>
                    </div>
                    
                    <div class="advanced-stat-card">
                        <div class="advanced-stat-title">Densité la plus élevée</div>
                        <div class="advanced-stat-value">
                            ${stats.density?.highest ? stats.density.highest.name : 'N/A'}
                        </div>
                        <div class="advanced-stat-subtext">
                            ${stats.density?.highest ? stats.density.highest.formatted_density + ' hab/km²' : ''}
                        </div>
                    </div>
                    
                    <div class="advanced-stat-card">
                        <div class="advanced-stat-title">Classifications</div>
                        <div class="advanced-stat-value">
                            ${stats.by_classification ? stats.by_classification.length : 0}
                        </div>
                        <div class="advanced-stat-subtext">
                            Types différents
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
                        loadRegions(1, currentFilters);
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
                        province: document.getElementById('filterProvince').value,
                        classification: document.getElementById('filterClassification').value,
                        sort_by: document.getElementById('filterSortBy').value,
                        sort_direction: document.getElementById('filterSortDirection').value
                    };
                    loadRegions(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterProvince').value = '';
                    document.getElementById('filterClassification').value = '';
                    document.getElementById('filterSortBy').value = 'name';
                    document.getElementById('filterSortDirection').value = 'asc';
                    currentFilters = {};
                    loadRegions(1);
                });
            }
            
            // Submit region form
            const submitRegionBtn = document.getElementById('submitRegionBtn');
            if (submitRegionBtn) {
                submitRegionBtn.addEventListener('click', storeRegion);
            }
            
            // Update region form
            const updateRegionBtn = document.getElementById('updateRegionBtn');
            if (updateRegionBtn) {
                updateRegionBtn.addEventListener('click', updateRegion);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteRegion);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    regionToDelete = null;
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
            const createModal = document.getElementById('createRegionModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createRegionForm').reset();
                    const submitBtn = document.getElementById('submitRegionBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la région
                        </span>
                    `;
                    submitBtn.disabled = false;
                    resetFlagPreview();
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editRegionModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    const submitBtn = document.getElementById('updateRegionBtn');
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
        /* Styles spécifiques pour la page régions */
        .region-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .region-flag-modern {
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

        .region-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .province-badge {
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

        .classification-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .classification-admin {
            background: linear-gradient(135deg, #3a56e4, #2947c2);
            color: white;
        }

        .classification-tourist {
            background: linear-gradient(135deg, #06b48a, #059672);
            color: white;
        }

        .classification-nature {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: #333;
        }

        .classification-urban {
            background: linear-gradient(135deg, #495057, #343a40);
            color: white;
        }

        .classification-rural {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .region-actions-modern {
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

        .region-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .region-info-flag {
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

        .region-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
        }

        .region-info-code {
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

        /* Additional styles for region page */
        .map-coordinates {
            font-family: monospace;
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            color: #495057;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .region-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .region-flag-modern {
                width: 35px;
                height: 35px;
            }
            
            .region-actions-modern {
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
            
            .classification-badge {
                display: block;
                text-align: center;
                margin-bottom: 5px;
            }
        }
     /* Style pour le cercle de région */
.region-flag-modern {
    display: flex;
    align-items: center;
    justify-content: center;
}

.region-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.region-initial {
    color: white;
    font-weight: bold;
    font-size: 18px;
    text-transform: uppercase;
}

.region-circle i {
    color: white;
    font-size: 18px;
}
    </style>
@endsection