@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-globe"></i></span>
                Gestion des Continents
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createContinentModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Continent
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
                <div class="col-md-4">
                    <label for="filterName" class="form-label-modern">Nom</label>
                    <input type="text" class="form-control-modern" id="filterName" placeholder="Rechercher par nom">
                </div>
                <div class="col-md-4">
                    <label for="filterCode" class="form-label-modern">Code</label>
                    <input type="text" class="form-control-modern" id="filterCode" placeholder="Ex: EU, AS, NA" maxlength="2">
                </div>
                <div class="col-md-4">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="population">Population</option>
                        <option value="area">Superficie</option>
                        <option value="countries_count">Nombre de pays</option>
                        <option value="created_at">Date de création</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalContinents">0</div>
                        <div class="stats-label-modern">Total Continents</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-globe"></i>
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
                        <div class="stats-label-modern">Total Pays</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-flag"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Continents</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un continent..." id="searchInput">
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
                                <th>Continent</th>
                                <th>Code</th>
                                <th>Pays</th>
                                <th>Population</th>
                                <th>Superficie</th>
                                <th>Status</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="continentsTableBody">
                            <!-- Continents will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun continent trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier continent.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createContinentModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un continent
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createContinentModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE CONTINENT MODAL -->
    <div class="modal fade" id="createContinentModal" tabindex="-1" aria-labelledby="createContinentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createContinentModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau continent
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createContinentForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="continentName" class="form-label-modern">Nom du continent *</label>
                                <input type="text" class="form-control-modern" id="continentName" name="name" 
                                       placeholder="Ex: Europe, Asie, Afrique..." required>
                                <div class="form-text-modern">Nom complet du continent</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="continentCode" class="form-label-modern">Code (2 lettres) *</label>
                                <input type="text" class="form-control-modern" id="continentCode" name="code" 
                                       placeholder="Ex: EU, AS, AF" maxlength="2" required>
                                <div class="form-text-modern">Code à 2 lettres (standard)</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="continentImage" class="form-label-modern">Image</label>
                                <input type="file" class="form-control-modern" id="continentImage" name="image" 
                                       accept="image/*">
                                <div class="form-text-modern">Format: jpg, png, gif, svg (max: 2MB)</div>
                                <div id="imagePreview" class="mt-2" style="display: none;">
                                    <img src="" alt="Aperçu" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="continentCountriesCount" class="form-label-modern">Nombre de pays</label>
                                <input type="number" class="form-control-modern" id="continentCountriesCount" name="countries_count" 
                                       placeholder="Ex: 44 (pour l'Europe)" min="0">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="continentPopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="continentPopulation" 
                                       name="population" placeholder="Ex: 746400000" min="0">
                                <div class="form-text-modern">Population totale du continent</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="continentArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="continentArea" name="area" 
                                       placeholder="Ex: 10180000" min="0" step="0.01">
                                <div class="form-text-modern">Superficie totale en kilomètres carrés</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="continentLanguages" class="form-label-modern">Langues principales</label>
                            <select class="form-select-modern" id="continentLanguages" name="languages[]" multiple>
                                <option value="Français">Français</option>
                                <option value="Anglais">Anglais</option>
                                <option value="Espagnol">Espagnol</option>
                                <option value="Portugais">Portugais</option>
                                <option value="Allemand">Allemand</option>
                                <option value="Italien">Italien</option>
                                <option value="Russe">Russe</option>
                                <option value="Chinois">Chinois</option>
                                <option value="Arabe">Arabe</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Japonais">Japonais</option>
                                <option value="Coréen">Coréen</option>
                                <option value="Swahili">Swahili</option>
                                <option value="Néerlandais">Néerlandais</option>
                            </select>
                            <div class="form-text-modern">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs langues</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="continentDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="continentDescription" name="description" 
                                      rows="4" placeholder="Description du continent..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitContinentBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le continent
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT CONTINENT MODAL -->
    <div class="modal fade" id="editContinentModal" tabindex="-1" aria-labelledby="editContinentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editContinentModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier le continent
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editContinentForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editContinentId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editContinentName" class="form-label-modern">Nom du continent *</label>
                                <input type="text" class="form-control-modern" id="editContinentName" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editContinentCode" class="form-label-modern">Code (2 lettres) *</label>
                                <input type="text" class="form-control-modern" id="editContinentCode" name="code" maxlength="2" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editContinentImage" class="form-label-modern">Image</label>
                                <div class="current-image mb-2" id="currentImageContainer">
                                    <!-- Current image will be loaded here -->
                                </div>
                                <input type="file" class="form-control-modern" id="editContinentImage" name="image" 
                                       accept="image/*">
                                <div class="form-text-modern">Format: jpg, png, gif, svg (max: 2MB)</div>
                                
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="removeImageCheckbox" name="remove_image" value="1">
                                    <label class="form-check-label" for="removeImageCheckbox">Supprimer l'image actuelle</label>
                                </div>
                                
                                <div id="editImagePreview" class="mt-2" style="display: none;">
                                    <img src="" alt="Nouvel aperçu" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editContinentCountriesCount" class="form-label-modern">Nombre de pays</label>
                                <input type="number" class="form-control-modern" id="editContinentCountriesCount" name="countries_count" min="0">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editContinentPopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="editContinentPopulation" name="population" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editContinentArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="editContinentArea" name="area" min="0" step="0.01">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editContinentLanguages" class="form-label-modern">Langues principales</label>
                            <select class="form-select-modern" id="editContinentLanguages" name="languages[]" multiple>
                                <option value="Français">Français</option>
                                <option value="Anglais">Anglais</option>
                                <option value="Espagnol">Espagnol</option>
                                <option value="Portugais">Portugais</option>
                                <option value="Allemand">Allemand</option>
                                <option value="Italien">Italien</option>
                                <option value="Russe">Russe</option>
                                <option value="Chinois">Chinois</option>
                                <option value="Arabe">Arabe</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Japonais">Japonais</option>
                                <option value="Coréen">Coréen</option>
                                <option value="Swahili">Swahili</option>
                                <option value="Néerlandais">Néerlandais</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editContinentDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="editContinentDescription" name="description" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateContinentBtn">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce continent ? Tous les pays associés seront également supprimés.</p>
                    
                    <div class="continent-to-delete" id="continentToDeleteInfo">
                        <!-- Continent info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible et supprimera tous les pays de ce continent.
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
    let allContinents = [];
    let continentToDelete = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setupAjax();
        loadContinents();
        loadStatistics();
        setupEventListeners();
        setupImagePreview();
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

    // Setup image preview
    const setupImagePreview = () => {
        // Create image preview
        const createImageInput = document.getElementById('continentImage');
        const editImageInput = document.getElementById('editContinentImage');
        
        if (createImageInput) {
            createImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('imagePreview');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.querySelector('img').src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });
        }
        
        if (editImageInput) {
            editImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('editImagePreview');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.querySelector('img').src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });
        }
    };

    // Load continents
    const loadContinents = (page = 1, filters = {}) => {
        showLoading();
        
        const searchTerm = document.getElementById('searchInput')?.value || '';
        
        $.ajax({
            url: '{{ route("continents.index") }}',
            type: 'GET',
            data: {
                page: page,
                search: searchTerm,
                ...filters,
                ajax: true
            },
            success: function(response) {
                console.log('Continents response:', response);
                
                if (response.success) {
                    allContinents = response.data || [];
                    renderContinents(allContinents);
                    renderPagination(response);
                    hideLoading();
                } else {
                    showError('Erreur lors du chargement des continents');
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
            url: '{{ route("continents.statistics") }}',
            type: 'GET',
            success: function(response) {
                console.log('Statistics response:', response);
                
                if (response.success) {
                    const stats = response.data;
                    document.getElementById('totalContinents').textContent = stats.total_continents || 0;
                    document.getElementById('totalPopulation').textContent = formatNumber(stats.total_population || 0);
                    document.getElementById('totalArea').textContent = formatNumber(stats.total_area || 0);
                    document.getElementById('totalCountries').textContent = formatNumber(stats.total_countries || 0);
                } else {
                    console.error('Error loading statistics:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Statistics AJAX error:', xhr.responseText, status, error);
                
                // Valeurs par défaut
                document.getElementById('totalContinents').textContent = '0';
                document.getElementById('totalPopulation').textContent = '0';
                document.getElementById('totalArea').textContent = '0';
                document.getElementById('totalCountries').textContent = '0';
            }
        });
    };

    // Render continents with modern design
 const renderContinents = (continents) => {
    const tbody = document.getElementById('continentsTableBody');
    tbody.innerHTML = '';
    
    if (!continents || !Array.isArray(continents) || continents.length === 0) {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
        return;
    }
    
    continents.forEach((continent, index) => {
        const row = document.createElement('tr');
        row.id = `continent-row-${continent.id}`;
        row.style.animationDelay = `${index * 0.05}s`;
        
        // Format population and area
        const population = continent.population ? formatNumber(continent.population) : 'N/A';
        const area = continent.area ? formatNumber(continent.area) : 'N/A';
        const countriesCount = continent.countries_count || 0;
        const isActive = continent.is_active ? true : false;
        
        // Get image URL or default
        let imageHtml = '<i class="fas fa-globe"></i>';
        if (continent.image) {
            const imageUrl = `/storage/continents/${continent.image}`;
            imageHtml = `<img src="${imageUrl}" alt="${continent.name}" class="continent-img">`;
        }
        
        row.innerHTML = `
            <td class="continent-name-cell">
                <div class="continent-name-modern">
                    <div class="continent-image-modern">
                        ${imageHtml}
                    </div>
                    <div>
                        <div class="continent-name-text">${continent.name}</div>
                        <small class="text-muted">${continent.description ? continent.description.substring(0, 50) + '...' : 'Aucune description'}</small>
                    </div>
                </div>
            </td>
            <td>
                <span class="continent-code-badge">${continent.code}</span>
            </td>
            <td>
                <div class="countries-count-modern">
                    <span class="countries-count-value">${countriesCount}</span>
                    <small class="text-muted">pays</small>
                </div>
            </td>
            <td>
                <div>${population}</div>
                <small class="text-muted">habitants</small>
            </td>
            <td>
                <div>${area}</div>
                <small class="text-muted">km²</small>
            </td>
            <td>
                <div class="status-toggle-container">
                    <div class="toggle-switch ${isActive ? 'active' : ''}" 
                         onclick="toggleContinentStatus(${continent.id}, ${isActive})">
                        <div class="toggle-slider"></div>
                    </div>
                    <span class="status-text ${isActive ? 'text-success' : 'text-danger'}">
                        ${isActive ? 'Actif' : 'Inactif'}
                    </span>
                </div>
            </td>
            <td>
                <div class="continent-actions-modern">
                    <button class="action-btn-modern view-btn-modern" title="Voir détails" 
                            onclick="viewContinent(${continent.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                            onclick="openEditModal(${continent.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                            onclick="showDeleteConfirmation(${continent.id})">
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


// Toggle continent status
const toggleContinentStatus = (continentId, currentStatus) => {
    // Find the toggle element
    const toggleElement = document.querySelector(`#continent-row-${continentId} .toggle-switch`);
    const statusText = document.querySelector(`#continent-row-${continentId} .status-text`);
    
    if (!toggleElement || !statusText) return;
    
    // Get the actual current status from the DOM, not from the parameter
    const isCurrentlyActive = toggleElement.classList.contains('active');
    const newStatus = !isCurrentlyActive;
    
    // Disable toggle during request
    toggleElement.style.pointerEvents = 'none';
    
    // Send AJAX request
    $.ajax({
        url: `/admin/continents/${continentId}/toggle-status`,
        type: 'PUT',
        data: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            is_active: newStatus
        },
        dataType: 'json',
        success: function(response) {
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
                
                // Update the continent in the array
                const continentIndex = allContinents.findIndex(c => c.id === continentId);
                if (continentIndex !== -1) {
                    allContinents[continentIndex].is_active = newStatus;
                }
                
                // Show success message
                showAlert('success', `Continent ${newStatus ? 'activé' : 'désactivé'} avec succès !`);
            } else {
                showAlert('danger', response.message || 'Erreur lors de la mise à jour du statut');
                // Revert toggle if error
                toggleElement.style.pointerEvents = 'auto';
            }
        },
        error: function(xhr, status, error) {
            showAlert('danger', 'Erreur lors de la mise à jour du statut: ' + error);
            // Revert toggle on error
            toggleElement.style.pointerEvents = 'auto';
        },
        complete: function() {
            // Only re-enable if not already done in error handlers
            if (toggleElement.style.pointerEvents === 'none') {
                setTimeout(() => {
                    toggleElement.style.pointerEvents = 'auto';
                }, 500);
            }
        }
    });
};
    // Render pagination
    const renderPagination = (response) => {
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('paginationInfo');
        
        if (!response || !response.current_page) {
            // Si pas de pagination côté serveur, afficher simplement le total
            const total = allContinents.length;
            paginationInfo.textContent = total > 0 ? `Total: ${total} continents` : 'Aucun continent';
            pagination.innerHTML = '';
            return;
        }
        
        // Update pagination info
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(response.current_page * response.per_page, response.total);
        paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} continents`;
        
        // Render pagination links
        let paginationHtml = '';
        
        // Previous button
        if (response.current_page > 1) {
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
        if (response.current_page < response.last_page) {
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
        loadContinents(page, currentFilters);
        // Scroll to top of table
        document.querySelector('.table-container-modern')?.scrollIntoView({ behavior: 'smooth' });
    };

    // Show delete confirmation modal
    const showDeleteConfirmation = (continentId) => {
        const continent = allContinents.find(c => c.id === continentId);
        
        if (!continent) {
            showAlert('danger', 'Continent non trouvé');
            return;
        }
        
        continentToDelete = continent;
        
        document.getElementById('continentToDeleteInfo').innerHTML = `
            <div class="continent-info">
                <div class="continent-info-image">
                    ${continent.image ? `<img src="/storage/continents/${continent.image}" alt="${continent.name}" class="continent-img-large">` : `<i class="fas fa-globe fa-2x"></i>`}
                </div>
                <div>
                    <div class="continent-info-name">${continent.name}</div>
                    <div class="continent-info-code">Code: ${continent.code}</div>
                </div>
            </div>
            <div class="row small text-muted">
                <div class="col-6">
                    <div><strong>Pays:</strong> ${continent.countries_count || 0}</div>
                    <div><strong>Population:</strong> ${formatNumber(continent.population)}</div>
                </div>
                <div class="col-6">
                    <div><strong>Superficie:</strong> ${formatNumber(continent.area)} km²</div>
                    <div><strong>Créé le:</strong> ${new Date(continent.created_at).toLocaleDateString('fr-FR')}</div>
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

    // Delete continent
    const deleteContinent = () => {
        if (!continentToDelete) {
            showAlert('danger', 'Aucun continent à supprimer');
            return;
        }
        
        const continentId = continentToDelete.id;
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
        const row = document.getElementById(`continent-row-${continentId}`);
        if (row) {
            row.classList.add('deleting-row');
        }
        
        // Send DELETE request
        $.ajax({
            url: `/continents/${continentId}`,
            type: 'DELETE',
            dataType: 'json',
            success: function(response) {
                // Hide modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                deleteModal.hide();
                
                if (response.success) {
                    // Remove continent from array
                    allContinents = allContinents.filter(c => c.id !== continentId);
                    
                    // Update statistics
                    loadStatistics();
                    
                    // Show success message
                    showAlert('success', response.message || 'Continent supprimé avec succès !');
                    
                    // Remove row after animation
                    if (row) {
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if table is now empty
                            const tbody = document.getElementById('continentsTableBody');
                            if (tbody.children.length === 0) {
                                document.getElementById('emptyState').style.display = 'block';
                                document.getElementById('tableContainer').style.display = 'none';
                                document.getElementById('paginationContainer').style.display = 'none';
                            }
                        }, 300);
                    } else {
                        // Reload table
                        setTimeout(() => {
                            loadContinents(currentPage, currentFilters);
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
                const row = document.getElementById(`continent-row-${continentId}`);
                if (row) {
                    row.classList.remove('deleting-row');
                }
                
                if (xhr.status === 404) {
                    showAlert('danger', 'Continent non trouvé.');
                    loadContinents(currentPage, currentFilters);
                } else {
                    showAlert('danger', 'Erreur lors de la suppression: ' + error);
                }
            },
            complete: function() {
                continentToDelete = null;
            }
        });
    };

    // Store continent (with file upload)
    const storeContinent = () => {
        const form = document.getElementById('createContinentForm');
        const submitBtn = document.getElementById('submitContinentBtn');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Show processing animation
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = `
            <span class="btn-text" style="display: none;">
                <i class="fas fa-save me-2"></i>Créer le continent
            </span>
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Création en cours...
        `;
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        
        // Send AJAX request with FormData
        $.ajax({
            url: '{{ route("continents.store") }}',
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
                        <i class="fas fa-save me-2"></i>Créer le continent
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (response.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createContinentModal'));
                    modal.hide();
                    
                    // Reset form
                    form.reset();
                    document.getElementById('imagePreview').style.display = 'none';
                    
                    // Reload continents
                    loadContinents(1, currentFilters);
                    loadStatistics();
                    
                    // Show success message
                    showAlert('success', 'Continent créé avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr, status, error) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le continent
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

    // Update continent (with file upload)
    const updateContinent = () => {
        const form = document.getElementById('editContinentForm');
        const submitBtn = document.getElementById('updateContinentBtn');
        const continentId = document.getElementById('editContinentId').value;
        
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
        
        // Send AJAX request with FormData
        $.ajax({
            url: `/admin/continents/${continentId}`,
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editContinentModal'));
                    modal.hide();
                    
                    // Reload continents
                    loadContinents(currentPage, currentFilters);
                    loadStatistics();
                    
                    // Show success message
                    showAlert('success', 'Continent mis à jour avec succès !');
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
    const openEditModal = (continentId) => {
        const continent = allContinents.find(c => c.id === continentId);
        
        if (continent) {
            document.getElementById('editContinentId').value = continent.id;
            document.getElementById('editContinentName').value = continent.name;
            document.getElementById('editContinentCode').value = continent.code;
            document.getElementById('editContinentPopulation').value = continent.population || '';
            document.getElementById('editContinentArea').value = continent.area || '';
            document.getElementById('editContinentCountriesCount').value = continent.countries_count || '';
            document.getElementById('editContinentDescription').value = continent.description || '';
            
            // Set current image
            const currentImageContainer = document.getElementById('currentImageContainer');
            if (continent.image) {
                const imageUrl = `/storage/continents/${continent.image}`;
                currentImageContainer.innerHTML = `
                    <label class="form-label-modern">Image actuelle:</label>
                    <div class="current-image-thumbnail">
                        <img src="${imageUrl}" alt="${continent.name}" class="img-thumbnail" style="max-height: 100px;">
                    </div>
                `;
            } else {
                currentImageContainer.innerHTML = `
                    <label class="form-label-modern">Image actuelle:</label>
                    <div class="current-image-thumbnail text-muted">
                        <i class="fas fa-image fa-2x mb-2"></i>
                        <div>Aucune image</div>
                    </div>
                `;
            }
            
            // Set languages
            const languagesSelect = document.getElementById('editContinentLanguages');
            try {
                const languages = continent.languages ? JSON.parse(continent.languages) : [];
                if (Array.isArray(languages)) {
                    Array.from(languagesSelect.options).forEach(option => {
                        option.selected = languages.includes(option.value);
                    });
                }
            } catch (e) {
                console.error('Error parsing languages:', e);
            }
            
            // Reset image preview and checkbox
            document.getElementById('editImagePreview').style.display = 'none';
            document.getElementById('removeImageCheckbox').checked = false;
            
            new bootstrap.Modal(document.getElementById('editContinentModal')).show();
        }
    };

    // View continent details
    const viewContinent = (continentId) => {
        const continent = allContinents.find(c => c.id === continentId);
        if (continent) {
            // Vous pouvez rediriger vers une page de détails ou afficher un modal
            window.location.href = `/continents/${continent.id}`;
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
                    loadContinents(1, currentFilters);
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
                    name: document.getElementById('filterName').value,
                    code: document.getElementById('filterCode').value,
                    sort_by: document.getElementById('filterSortBy').value,
                    sort_direction: document.getElementById('filterSortDirection') ? document.getElementById('filterSortDirection').value : 'asc'
                };
                loadContinents(1, currentFilters);
            });
        }
        
        // Clear filters
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                document.getElementById('filterName').value = '';
                document.getElementById('filterCode').value = '';
                document.getElementById('filterSortBy').value = 'name';
                if (document.getElementById('filterSortDirection')) {
                    document.getElementById('filterSortDirection').value = 'asc';
                }
                currentFilters = {};
                loadContinents(1);
            });
        }
        
        // Submit continent form
        const submitContinentBtn = document.getElementById('submitContinentBtn');
        if (submitContinentBtn) {
            submitContinentBtn.addEventListener('click', storeContinent);
        }
        
        // Update continent form
        const updateContinentBtn = document.getElementById('updateContinentBtn');
        if (updateContinentBtn) {
            updateContinentBtn.addEventListener('click', updateContinent);
        }
        
        // Confirm delete button
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', deleteContinent);
        }
        
        // Reset modals when hidden
        const modals = ['deleteConfirmationModal', 'createContinentModal', 'editContinentModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('hidden.bs.modal', function() {
                    if (modalId === 'deleteConfirmationModal') {
                        continentToDelete = null;
                        const deleteBtn = document.getElementById('confirmDeleteBtn');
                        if (deleteBtn) {
                            deleteBtn.innerHTML = `
                                <span class="btn-text">
                                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                                </span>
                            `;
                            deleteBtn.disabled = false;
                        }
                    } else if (modalId === 'createContinentModal') {
                        const form = document.getElementById('createContinentForm');
                        if (form) form.reset();
                        document.getElementById('imagePreview').style.display = 'none';
                    }
                });
            }
        });
    };
</script>

    <style>
        /* Styles spécifiques pour la page continents */
        .continent-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .continent-image-modern {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border: 1px solid #eaeaea;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .continent-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .continent-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 4px;
        }

        .continent-code-badge {
            display: inline-block;
            padding: 6px 12px;
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            min-width: 40px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(69, 183, 209, 0.3);
        }

        .countries-count-modern {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .countries-count-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .continent-actions-modern {
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

        .continent-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .continent-info-image {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #eaeaea;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .continent-img-large {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .continent-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
        }

        .continent-info-code {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .current-image-thumbnail {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            text-align: center;
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
            .continent-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .continent-image-modern {
                width: 40px;
                height: 40px;
            }
            
            .continent-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .countries-count-modern {
                align-items: flex-start;
            }
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
}
    </style>
@endsection