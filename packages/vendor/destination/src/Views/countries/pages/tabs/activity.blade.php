<!-- ACTIVITIES TAB -->
                <div class="tab-pane fade show active" id="activities-tab-pane" role="tabpanel">
                    
                    <!-- Filter Section -->
                    <div class="card mb-4" id="filterSection" style="display: none;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Filtres</h5>
                                <div>
                                    <button class="btn btn-sm btn-outline-secondary me-2" id="clearFiltersBtn">
                                        <i class="fas fa-times me-1"></i>Effacer
                                    </button>
                                    <button class="btn btn-sm btn-primary" id="applyFiltersBtn">
                                        <i class="fas fa-check me-1"></i>Appliquer
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="filterCategory" class="form-label">Catégorie</label>
                                    <select class="form-select" id="filterCategory">
                                        <option value="">Toutes les catégories</option>
                                        <option value="sport">Sport</option>
                                        <option value="culture">Culture</option>
                                        <option value="nature">Nature</option>
                                        <option value="gastronomie">Gastronomie</option>
                                        <option value="aventure">Aventure</option>
                                        <option value="relaxation">Relaxation</option>
                                        <option value="urbain">Urbain</option>
                                        <option value="rural">Rural</option>
                                    </select>
                                </div>
                                @if(!$country)
                                <div class="col-md-3 mb-3">
                                    <label for="filterContinent" class="form-label">Continent</label>
                                    <select class="form-select" id="filterContinent">
                                        <option value="">Tous les continents</option>
                                        @foreach($continents ?? [] as $continent)
                                            <option value="{{ $continent->id }}">{{ $continent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="filterCountry" class="form-label">Pays</label>
                                    <select class="form-select" id="filterCountry">
                                        <option value="">Tous les pays</option>
                                        @foreach($countries ?? [] as $countryItem)
                                            <option value="{{ $countryItem->id }}">{{ $countryItem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="col-md-3 mb-3">
                                    <label for="filterStatus" class="form-label">Statut</label>
                                    <select class="form-select" id="filterStatus">
                                        <option value="">Tous</option>
                                        <option value="active">Actives</option>
                                        <option value="inactive">Inactives</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="filterSearch" class="form-label">Recherche par nom</label>
                                    <input type="text" class="form-control" id="filterSearch" placeholder="Nom de l'activité...">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="filterSortBy" class="form-label">Trier par</label>
                                    <select class="form-select" id="filterSortBy">
                                        <option value="name">Nom</option>
                                        <option value="created_at">Date de création</option>
                                        <option value="updated_at">Dernière modification</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="mb-0" id="totalActivities">0</h3>
                                            <p class="text-muted mb-0">Total Activités</p>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-hiking text-primary fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="mb-0" id="activeActivities">0</h3>
                                            <p class="text-muted mb-0">Actives</p>
                                        </div>
                                        <div class="bg-success bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="mb-0" id="totalCategories">0</h3>
                                            <p class="text-muted mb-0">Catégories</p>
                                        </div>
                                        <div class="bg-info bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-tags text-info fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="mb-0" id="withImage">0</h3>
                                            <p class="text-muted mb-0">Avec image</p>
                                        </div>
                                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-image text-warning fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Card -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Liste des Activités</h5>
                                
                <div class="page-actions">
                    <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                        <i class="fas fa-sliders-h me-2"></i>Filtres
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createActivityModal">
                        <i class="fas fa-plus-circle me-2"></i>Nouvelle Activité
                    </button>
                </div>
                                <div class="input-group" style="width: 300px;">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Rechercher une activité..." id="searchInput">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Loading Spinner -->
                            <div class="text-center py-5" id="loadingSpinner">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </div>
                            
                            <!-- Table Container -->
                            <div id="tableContainer" style="display: none;">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Activité</th>
                                                <th>Catégorie</th>
                                                @if(!$country)
                                                <th>Pays</th>
                                                @endif
                                                <th>Créée le</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="activitiesTableBody">
                                            <!-- Activities will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Empty State -->
                            <div class="text-center py-5" id="emptyState" style="display: none;">
                                <div class="mb-3">
                                    <i class="fas fa-hiking fa-3x text-muted"></i>
                                </div>
                                <h4 class="text-muted">Aucune activité trouvée</h4>
                                <p class="text-muted mb-4">Commencez par créer votre première activité.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createActivityModal">
                                    <i class="fas fa-plus-circle me-2"></i>Créer une activité
                                </button>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer" id="paginationContainer" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div id="paginationInfo"></div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination mb-0" id="pagination">
                                        <!-- Pagination will be loaded here -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CREATE ACTIVITY MODAL -->
    <div class="modal fade" id="createActivityModal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createActivityModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle activité
                        @if($country)
                        <small class="text-muted">pour {{ $country->name }}</small>
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createActivityForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Hidden field for country_id -->
                        @if($country)
                        <input type="hidden" name="country_id" value="{{ $country->id }}">
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="activityType" class="form-label">Type *</label>
                                <select class="form-select" id="activityType" name="type" required>
                                    <option value="">Sélectionnez Type</option>
                                    @foreach($categorie_types as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="activityCategory" class="form-label">Catégorie *</label>
                                <select class="form-select" id="activityCategory" name="categorie_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $categorie)
                                    <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="activityName" class="form-label">Nom de l'activité *</label>
                            <input type="text" class="form-control" id="activityName" name="name" 
                                   placeholder="Ex: Randonnée, Ski, Visite culturelle..." required>
                        </div>
                        <div class="mb-3">
                            <label for="activityName" class="form-label">Slug *</label>
                            <input type="text" class="form-control" id="activitySlug" name="slug" 
                                   placeholder="" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="activityDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="activityDescription" name="description" 
                                      rows="3" placeholder="Description détaillée de l'activité..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="activityImage" class="form-label">Image de l'activité</label>
                            <input type="file" class="form-control" id="activityImage" name="image" accept="image/*">
                            <div class="form-text">Formats acceptés: JPG, PNG, GIF, SVG (Max: 20MB)</div>
                            <div class="mt-2" id="activityImagePreview" style="display: none;">
                                <img id="previewActivityImage" class="img-thumbnail" style="max-width: 150px; max-height: 100px;">
                            </div>
                        </div>
                        
                        @if(!$country)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-map-marker-alt me-2"></i>Localisations
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Continents</label>
                                        <div class="border rounded p-3" style="max-height: 150px; overflow-y: auto;" id="continentsContainer">
                                            @foreach($continents ?? [] as $continent)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input continent-checkbox" type="checkbox" 
                                                       name="continents[]" 
                                                       value="{{ $continent->id }}" 
                                                       id="continent{{ $continent->id }}">
                                                <label class="form-check-label" for="continent{{ $continent->id }}">
                                                    {{ $continent->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Pays</label>
                                        <select class="form-select" id="activityCountries" name="countries[]" multiple style="height: 150px;">
                                            <option value="">Sélectionnez les pays</option>
                                            @foreach($countries ?? [] as $countryItem)
                                            <option value="{{ $countryItem->id }}">{{ $countryItem->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="activityTags" class="form-label">Tags (séparés par des virgules)</label>
                                <input type="text" class="form-control" id="activityTags" name="tags" 
                                       placeholder="Ex: extérieur, famille, sportif, culturel...">
                            </div>
                            <div class="col-md-6">
                                <label for="activityIsActive" class="form-label">Statut</label>
                                <select class="form-select" id="activityIsActive" name="is_active">
                                    <option value="1" selected>Activé</option>
                                    <option value="0">Désactivé</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="submitActivityBtn">
                        <i class="fas fa-plus-circle me-2"></i>Créer l'activité
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT ACTIVITY MODAL -->
    <div class="modal fade" id="editActivityModal" tabindex="-1" aria-labelledby="editActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editActivityModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier l'activité
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editActivityForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editActivityId" name="id">
                        
                        <!-- Hidden field for country_id -->
                        @if($country)
                        <input type="hidden" name="country_id" value="{{ $country->id }}">
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editActivityType" class="form-label">Type *</label>
                                <select class="form-select" id="editActivityType" name="type" required>
                                    <option value="">Sélectionnez Type</option>
                                    @foreach($categorie_types as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editActivityCategory" class="form-label">Catégorie *</label>
                                <select class="form-select" id="editActivityCategory" name="categorie_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $categorie)
                                    <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editActivityName" class="form-label">Nom de l'activité *</label>
                            <input type="text" class="form-control" id="editActivityName" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editActivityDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editActivityDescription" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editActivityImage" class="form-label">Image de l'activité</label>
                            
                            <!-- Current image preview -->
                            <div class="mb-2" id="currentActivityImagePreview" style="display: none;">
                                <img id="currentActivityImage" class="img-thumbnail" style="max-width: 150px; max-height: 100px;">
                                <small class="text-muted d-block">Image actuelle</small>
                            </div>
                            
                            <input type="file" class="form-control" id="editActivityImage" name="image" accept="image/*">
                            <div class="form-text">Laissez vide pour conserver l'image actuelle</div>
                            <div class="mt-2" id="editActivityImagePreview" style="display: none;">
                                <img id="editPreviewActivityImage" class="img-thumbnail" style="max-width: 150px; max-height: 100px;">
                                <small class="text-muted d-block">Nouvelle image</small>
                            </div>
                        </div>
                        
                        @if(!$country)
                        <!-- Localisations pour édition -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-map-marker-alt me-2"></i>Localisations associées
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Continents</label>
                                        <div class="border rounded p-3" style="max-height: 150px; overflow-y: auto;" id="editContinentsContainer">
                                            @foreach($continents ?? [] as $continent)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input edit-continent-checkbox" type="checkbox" 
                                                       name="continents[]" 
                                                       value="{{ $continent->id }}" 
                                                       id="editContinent{{ $continent->id }}">
                                                <label class="form-check-label" for="editContinent{{ $continent->id }}">
                                                    {{ $continent->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Pays</label>
                                        <select class="form-select" id="editActivityCountries" name="countries[]" multiple style="height: 150px;">
                                            <option value="">Sélectionnez les pays</option>
                                            @foreach($countries ?? [] as $countryItem)
                                            <option value="{{ $countryItem->id }}">{{ $countryItem->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Cette activité est associée à <strong>{{ $country->name }}</strong>.
                            <div id="associatedCountriesList"></div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editActivityTags" class="form-label">Tags (séparés par des virgules)</label>
                                <input type="text" class="form-control" id="editActivityTags" name="tags">
                            </div>
                            <div class="col-md-6">
                                <label for="editActivityIsActive" class="form-label">Statut</label>
                                <select class="form-select" id="editActivityIsActive" name="is_active">
                                    <option value="1">Activé</option>
                                    <option value="0">Désactivé</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateActivityBtn">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade" id="deleteActivityModal" tabindex="-1" aria-labelledby="deleteActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteActivityModalLabel">
                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette activité ? Toutes les associations avec les pays seront également supprimées.</p>
                    
                    <div class="activity-to-delete" id="activityToDeleteInfo">
                        <!-- Activity info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelDeleteActivityBtn">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteActivityBtn">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
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
        let allActivities = [];
        let activityToDelete = null;
        const countryId = {{ $country->id ?? 'null' }};

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadActivities();
            loadActivityStatistics();
            setupEventListeners();
            setupImagePreviews();
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

        // Load activities
        const loadActivities = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            let url = '{{ route("activities.index") }}';
            if (countryId) {
                url = `/countries/activities/${countryId}`;
            }
            
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allActivities = response.data || [];
                        renderActivities(allActivities);
                        renderPagination(response);
                        hideLoading();
                        
                        // Update activities count
                        document.getElementById('activitiesCount').textContent = response.total || 0;
                    } else {
                        showError('Erreur lors du chargement des activités');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showError('Erreur de connexion au serveur');
                    console.error('Error:', xhr.responseText);
                }
            });
        };

        // Load activity statistics
        const loadActivityStatistics = () => {
            let url = '{{ route("activities.statistics") }}';
            const data = {};
            
            if (countryId) {
                url = `/countries/activities/statistics/${countryId}`;
                data.country_id = countryId;
            }
            
            $.ajax({
                url: url,
                type: 'GET',
                data: data,
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalActivities').textContent = stats.total_activities || 0;
                        document.getElementById('activeActivities').textContent = stats.active_activities || 0;
                        document.getElementById('totalCategories').textContent = stats.categories_count || 0;
                        document.getElementById('withImage').textContent = stats.with_image || 0;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Statistics AJAX error:', error);
                }
            });
        };

        // Setup image previews
        const setupImagePreviews = () => {
            // Create activity image preview
            const activityImageInput = document.getElementById('activityImage');
            const activityPreview = document.getElementById('activityImagePreview');
            const activityPreviewImage = document.getElementById('previewActivityImage');
            
            if (activityImageInput) {
                activityImageInput.addEventListener('change', function(e) {
                    handleImagePreview(e, activityPreview, activityPreviewImage);
                });
            }
            
            // Edit activity image preview
            const editActivityImageInput = document.getElementById('editActivityImage');
            const editActivityPreview = document.getElementById('editActivityImagePreview');
            const editActivityPreviewImage = document.getElementById('editPreviewActivityImage');
            
            if (editActivityImageInput) {
                editActivityImageInput.addEventListener('change', function(e) {
                    handleImagePreview(e, editActivityPreview, editActivityPreviewImage);
                });
            }
        };

        const handleImagePreview = (e, previewContainer, previewImage) => {
            const file = e.target.files[0];
            if (file) {
                // Check file size (max 20MB)
                if (file.size > 20 * 1024 * 1024) {
                    showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
                    e.target.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }
                
                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                if (!validTypes.includes(file.type)) {
                    showAlert('danger', 'Format d\'image non supporté. Utilisez JPG, PNG, GIF ou SVG.');
                    e.target.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        };

        // Render activities
        const renderActivities = (activities) => {
            const tbody = document.getElementById('activitiesTableBody');
            tbody.innerHTML = '';
            
            if (!activities || !Array.isArray(activities) || activities.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            activities.forEach((activity, index) => {
                const row = document.createElement('tr');
                row.id = `activity-row-${activity.id}`;
                
                // Get associated countries
                let countriesList = 'N/A';
                if (activity.countries && activity.countries.length > 0) {
                    countriesList = activity.countries.map(c => c.name).join(', ');
                }
                
                // Format date
                const createdDate = formatDate(activity.created_at);
                
                row.innerHTML = `
                    <td class="activity-name-cell">
                        <div class="activity-name-modern">
                            <div class="activity-icon-modern">
${activity.image ? `<img src="/storage/${activity.image}" alt="${activity.name}" class="activity-img" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">` : `<i class="fas fa-hiking"></i>`}                            </div>
                            <div>
                                <div class="activity-name-text">${activity.name}</div>
                                <small class="text-muted">${activity.type_name || 'Non typé'}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="category-badge">${activity.category_name || 'N/A'}</span>
                    </td>
                    ${!countryId ? `
                    <td>
                        <div class="countries-list">
                            ${countriesList}
                        </div>
                    </td>
                    ` : ''}
                    <td>
                        <small>${createdDate}</small>
                    </td>
                    <td>
                        <div class="status-toggle-container">
                            <div class="toggle-switch ${activity.is_active ? 'active' : ''}" 
                                 onclick="toggleActivityStatus(${activity.id}, ${activity.is_active})">
                                <div class="toggle-slider"></div>
                            </div>
                            <span class="status-text ${activity.is_active ? 'text-success' : 'text-danger'}">
                                ${activity.is_active ? 'Actif' : 'Inactif'}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="activity-actions-modern">
                            <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                    onclick="openEditActivityModal(${activity.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteActivityConfirmation(${activity.id})">
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

        // Toggle activity status
const toggleActivityStatus = (activityId, currentStatus) => {
    const toggleElement = document.querySelector(`#activity-row-${activityId} .toggle-switch`);
    const statusText = document.querySelector(`#activity-row-${activityId} .status-text`);
    
    if (!toggleElement || !statusText) return;
    
    // Get the actual current status from the DOM
    const isCurrentlyActive = toggleElement.classList.contains('active');
    const newStatus = !isCurrentlyActive;
    
    toggleElement.style.pointerEvents = 'none';
    toggleElement.classList.add('loading');
    
    $.ajax({
        url: `/countries/activities/${activityId}/toggle-status`,
        type: 'PUT',
        data: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            is_active: newStatus
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Update toggle and status text based on newStatus
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
                
                // Update the data in allActivities array
                const activityIndex = allActivities.findIndex(a => a.id === activityId);
                if (activityIndex !== -1) {
                    allActivities[activityIndex].is_active = newStatus;
                }
                
                showAlert('success', `Activité ${newStatus ? 'activée' : 'désactivée'} avec succès !`);
                
                // Update statistics
                loadActivityStatistics();
            } else {
                showAlert('danger', response.message || 'Erreur lors de la mise à jour du statut');
                // Re-enable the toggle on error
                toggleElement.style.pointerEvents = 'auto';
                toggleElement.classList.remove('loading');
            }
        },
        error: function(xhr, status, error) {
            showAlert('danger', 'Erreur lors de la mise à jour du statut: ' + error);
            // Re-enable the toggle on error
            toggleElement.style.pointerEvents = 'auto';
            toggleElement.classList.remove('loading');
        },
        complete: function() {
            // Only re-enable if not already done in error handlers
            if (toggleElement.style.pointerEvents === 'none') {
                setTimeout(() => {
                    toggleElement.style.pointerEvents = 'auto';
                    toggleElement.classList.remove('loading');
                }, 500);
            }
        }
    });
};

        // Show current image in edit modal
        const showCurrentActivityImageInEdit = (activity) => {
            const currentPreview = document.getElementById('currentActivityImagePreview');
            const currentImage = document.getElementById('currentActivityImage');
            
            if (activity.image) {
                currentImage.src = `/storage/${activity.image}`;
                currentPreview.style.display = 'block';
            } else {
                currentPreview.style.display = 'none';
            }
        };

        // Open edit activity modal
        const openEditActivityModal = (activityId) => {
            const activity = allActivities.find(a => a.id === activityId);
            
            if (activity) {
                document.getElementById('editActivityId').value = activity.id;
                document.getElementById('editActivityName').value = activity.name;
                document.getElementById('editActivityType').value = activity.type || '';
                document.getElementById('editActivityCategory').value = activity.category || '';
                document.getElementById('editActivityDescription').value = activity.description || '';
                document.getElementById('editActivityTags').value = activity.tags || '';
                document.getElementById('editActivityIsActive').value = activity.is_active ? '1' : '0';
                
                // Clear file input
                document.getElementById('editActivityImage').value = '';
                
                // Show current image
                showCurrentActivityImageInEdit(activity);
                
                // Hide new image preview
                document.getElementById('editActivityImagePreview').style.display = 'none';
                
                // Show associated countries
                if (countryId && activity.countries && Array.isArray(activity.countries)) {
                    const countriesList = activity.countries.map(c => 
                        `<span class="badge bg-info me-1">${c.name}</span>`
                    ).join('');
                    document.getElementById('associatedCountriesList').innerHTML = countriesList;
                }
                
                // Set continents (only if not in country-specific view)
                if (!countryId && activity.continents && Array.isArray(activity.continents)) {
                    const continentIds = activity.continents.map(c => c.id);
                    const checkboxes = document.querySelectorAll('.edit-continent-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = continentIds.includes(parseInt(checkbox.value));
                    });
                }
                
                // Set countries (only if not in country-specific view)
                const countriesSelect = document.getElementById('editActivityCountries');
                if (!countryId && countriesSelect && activity.countries && Array.isArray(activity.countries)) {
                    const countryIds = activity.countries.map(c => c.id);
                    Array.from(countriesSelect.options).forEach(option => {
                        option.selected = countryIds.includes(parseInt(option.value));
                    });
                }
                
                new bootstrap.Modal(document.getElementById('editActivityModal')).show();
            }
        };

        // Show delete activity confirmation
        const showDeleteActivityConfirmation = (activityId) => {
            const activity = allActivities.find(a => a.id === activityId);
            
            if (!activity) {
                showAlert('danger', 'Activité non trouvée');
                return;
            }
            
            activityToDelete = activity;
            
            // Determine image URL
            let imageUrl = '';
            if (activity.image) {
                imageUrl = `/storage/${activity.image}`;
            }
            
            document.getElementById('activityToDeleteInfo').innerHTML = `
                <div class="activity-info">
                    <div class="activity-info-image">
                        ${imageUrl ? `<img src="${imageUrl}" alt="${activity.name}" class="activity-img-large" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\"fas fa-hiking fa-2x\"></i>';">` : `<i class="fas fa-hiking fa-2x"></i>`}
                    </div>
                    <div>
                        <div class="activity-info-name">${activity.name}</div>
                        <div class="activity-info-category">${activity.category_name || 'Non catégorisé'}</div>
                    </div>
                </div>
                <div class="row small text-muted mt-2">
                    <div class="col-6">
                        <div><strong>Créée le:</strong> ${formatDate(activity.created_at)}</div>
                        <div><strong>Type:</strong> ${activity.type_name || 'N/A'}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Statut:</strong> ${activity.is_active ? 'Actif' : 'Inactif'}</div>
                        <div><strong>Pays associés:</strong> ${activity.countries_count || 0}</div>
                    </div>
                </div>
            `;
            
            const deleteBtn = document.getElementById('confirmDeleteActivityBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                </span>
            `;
            deleteBtn.disabled = false;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteActivityModal'));
            deleteModal.show();
        };

        // Delete activity
        const deleteActivity = () => {
            if (!activityToDelete) {
                showAlert('danger', 'Aucune activité à supprimer');
                return;
            }
            
            const activityId = activityToDelete.id;
            const deleteBtn = document.getElementById('confirmDeleteActivityBtn');
            
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
            
            const row = document.getElementById(`activity-row-${activityId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            $.ajax({
                url: `/activities/${activityId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteActivityModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        allActivities = allActivities.filter(a => a.id !== activityId);
                        
                        loadActivityStatistics();
                        
                        showAlert('success', response.message || 'Activité supprimée avec succès !');
                        
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                const tbody = document.getElementById('activitiesTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            setTimeout(() => {
                                loadActivities(currentPage, currentFilters);
                            }, 500);
                        }
                    } else {
                        if (row) row.classList.remove('deleting-row');
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr, status, error) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteActivityModal'));
                    deleteModal.hide();
                    
                    const row = document.getElementById(`activity-row-${activityId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Activité non trouvée.');
                        loadActivities(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    activityToDelete = null;
                }
            });
        };

        // Store activity
        const storeActivity = () => {
            const form = document.getElementById('createActivityForm');
            const submitBtn = document.getElementById('submitActivityBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const fileInput = document.getElementById('activityImage');
            if (fileInput.files[0] && fileInput.files[0].size > 20 * 1024 * 1024) {
                showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
                return;
            }
            
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-plus-circle me-2"></i>Créer l'activité
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Création en cours...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            // Add continents (only if not in country-specific view)
            if (!countryId) {
                const continentCheckboxes = document.querySelectorAll('input[name="continents[]"]:checked');
                continentCheckboxes.forEach(checkbox => {
                    formData.append('continents[]', checkbox.value);
                });
            }
            
            // Add countries (only if not in country-specific view)
            if (!countryId) {
                const countriesSelect = document.getElementById('activityCountries');
                if (countriesSelect) {
                    const selectedCountries = Array.from(countriesSelect.selectedOptions)
                        .map(option => option.value);
                    selectedCountries.forEach(countryId => {
                        formData.append('countries[]', countryId);
                    });
                }
            }
            
            let url = '{{ url("countries/activities/store") }}';
            // if (countryId) {
            //     url = `/countries/${countryId}/activities`;
            // }
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-plus-circle me-2"></i>Créer l'activité
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createActivityModal'));
                        modal.hide();
                        
                        form.reset();
                        document.getElementById('activityImagePreview').style.display = 'none';
                        
                        loadActivities(1, currentFilters);
                        loadActivityStatistics();
                        
                        showAlert('success', 'Activité créée avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création');
                    }
                },
                error: function(xhr, status, error) {
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-plus-circle me-2"></i>Créer l'activité
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

        // Update activity
        const updateActivity = () => {
            const form = document.getElementById('editActivityForm');
            const submitBtn = document.getElementById('updateActivityBtn');
            const activityId = document.getElementById('editActivityId').value;
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const fileInput = document.getElementById('editActivityImage');
            if (fileInput.files[0] && fileInput.files[0].size > 20 * 1024 * 1024) {
                showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
                return;
            }
            
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
            
            // Add continents (only if not in country-specific view)
            if (!countryId) {
                const continentCheckboxes = document.querySelectorAll('#editContinentsContainer input[name="continents[]"]:checked');
                continentCheckboxes.forEach(checkbox => {
                    formData.append('continents[]', checkbox.value);
                });
            }
            
            // Add countries (only if not in country-specific view)
            if (!countryId) {
                const countriesSelect = document.getElementById('editActivityCountries');
                if (countriesSelect) {
                    const selectedCountries = Array.from(countriesSelect.selectedOptions)
                        .map(option => option.value);
                    selectedCountries.forEach(countryId => {
                        formData.append('countries[]', countryId);
                    });
                }
            }
            
            formData.append('_method', 'PUT');
            
            $.ajax({
                url: `/activities/${activityId}`,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editActivityModal'));
                        modal.hide();
                        
                        loadActivities(currentPage, currentFilters);
                        loadActivityStatistics();
                        
                        showAlert('success', 'Activité mise à jour avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la mise à jour');
                    }
                },
                error: function(xhr, status, error) {
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

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} activités`;
            
            let paginationHtml = '';
            
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
            loadActivities(page, currentFilters);
        };

        // Format date
        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR');
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
            
            const pageHeader = document.querySelector('.page-header');
            if (pageHeader && pageHeader.nextSibling) {
                pageHeader.parentNode.insertBefore(alert, pageHeader.nextSibling);
            } else {
                document.body.appendChild(alert);
            }
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        };

        // Show error
        const showError = (message) => {
            showAlert('danger', message);
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
                        loadActivities(1, currentFilters);
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
                        category: document.getElementById('filterCategory').value,
                        continent: document.getElementById('filterContinent')?.value || '',
                        country: document.getElementById('filterCountry')?.value || '',
                        status: document.getElementById('filterStatus').value,
                        search: document.getElementById('filterSearch').value,
                        sort_by: document.getElementById('filterSortBy').value
                    };
                    loadActivities(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterCategory').value = '';
                    if (document.getElementById('filterContinent')) {
                        document.getElementById('filterContinent').value = '';
                    }
                    if (document.getElementById('filterCountry')) {
                        document.getElementById('filterCountry').value = '';
                    }
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterSearch').value = '';
                    document.getElementById('filterSortBy').value = 'name';
                    currentFilters = {};
                    loadActivities(1);
                });
            }
            
            // Submit activity form
            const submitActivityBtn = document.getElementById('submitActivityBtn');
            if (submitActivityBtn) {
                submitActivityBtn.addEventListener('click', storeActivity);
            }
            
            // Update activity form
            const updateActivityBtn = document.getElementById('updateActivityBtn');
            if (updateActivityBtn) {
                updateActivityBtn.addEventListener('click', updateActivity);
            }
            
            // Confirm delete activity button
            const confirmDeleteActivityBtn = document.getElementById('confirmDeleteActivityBtn');
            if (confirmDeleteActivityBtn) {
                confirmDeleteActivityBtn.addEventListener('click', deleteActivity);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteActivityModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    activityToDelete = null;
                    const deleteBtn = document.getElementById('confirmDeleteActivityBtn');
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                });
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createActivityModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createActivityForm').reset();
                    document.getElementById('activityImagePreview').style.display = 'none';
                    const submitBtn = document.getElementById('submitActivityBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-plus-circle me-2"></i>Créer l'activité
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editActivityModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('editActivityForm').reset();
                    document.getElementById('currentActivityImagePreview').style.display = 'none';
                    document.getElementById('editActivityImagePreview').style.display = 'none';
                    const submitBtn = document.getElementById('updateActivityBtn');
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
        /* Activity name modern */
        .activity-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .activity-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .activity-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .activity-name-text {
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .category-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .countries-list {
            font-size: 0.9rem;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .activity-actions-modern {
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

        .activity-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .activity-info-image {
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

        .activity-img-large {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .activity-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: #333;
        }

        .activity-info-category {
            font-size: 0.9rem;
            color: #6c757d;
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

        /* Badge styles */
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
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

        /* Location select container */
        .location-select-container {
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }

        .location-select-container .form-check {
            margin-bottom: 8px;
            padding: 5px 10px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .location-select-container .form-check:hover {
            background: #f8f9fa;
        }

        /* Custom alerts */
        .alert-custom-modern {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: calc(100% - 40px);
        }

        /* Pagination */
        .page-link-modern {
            border: none;
            color: #667eea;
            font-weight: 500;
            border-radius: 8px;
            margin: 0 2px;
        }

        .page-item.active .page-link-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .page-item.disabled .page-link-modern {
            color: #ccc;
        }

        /* Button processing state */
        .btn-processing {
            position: relative;
        }

        .btn-processing .btn-text {
            display: none;
        }

        .btn-processing .spinner-border {
            margin-right: 8px;
        }
    </style>