

<!-- Dans le contenu des tabs (ajouter après le tab-pane media-tab-pane) -->
<div class="tab-pane fade" id="places-tab-pane" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>Gestion des Lieux
                    @if($country)
                    <small class="text-muted">pour {{ $country->name }}</small>
                    @endif
                </h5>
                <div>
                    <button class="btn btn-outline-secondary me-2" id="togglePlacesFilterBtn">
                        <i class="fas fa-sliders-h me-2"></i>Filtres
                    </button>
                    <button class="btn btn-outline-info me-2" id="showPlacesMapBtn">
                        <i class="fas fa-map me-2"></i>Voir carte
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlaceModal">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter un lieu
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Filter Section -->
            <div class="card mb-4" id="placesFilterSection" style="display: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Filtres Lieux</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-2" id="clearPlacesFiltersBtn">
                                <i class="fas fa-times me-1"></i>Effacer
                            </button>
                            <button class="btn btn-sm btn-primary" id="applyPlacesFiltersBtn">
                                <i class="fas fa-check me-1"></i>Appliquer
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="placesFilterCategory" class="form-label">Catégorie</label>
                            <select class="form-select" id="placesFilterCategory">
                                <option value="">Toutes les catégories</option>
                                <option value="restaurant">Restaurant</option>
                                <option value="hotel">Hôtel</option>
                                <option value="museum">Musée</option>
                                <option value="park">Parc</option>
                                <option value="beach">Plage</option>
                                <option value="shopping">Shopping</option>
                                <option value="attraction">Attraction</option>
                                <option value="historic">Historique</option>
                                <option value="religious">Religieux</option>
                                <option value="natural">Naturel</option>
                                <option value="cultural">Culturel</option>
                                <option value="sport">Sport</option>
                                <option value="entertainment">Divertissement</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="placesFilterStatus" class="form-label">Statut</label>
                            <select class="form-select" id="placesFilterStatus">
                                <option value="">Tous</option>
                                <option value="active">Actifs</option>
                                <option value="inactive">Inactifs</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="placesFilterFeatured" class="form-label">À la une</label>
                            <select class="form-select" id="placesFilterFeatured">
                                <option value="">Tous</option>
                                <option value="featured">À la une</option>
                                <option value="not_featured">Pas à la une</option>
                            </select>
                        </div>
                        @if(!$country)
                        <div class="col-md-3 mb-3">
                            <label for="placesFilterCountry" class="form-label">Pays</label>
                            <select class="form-select" id="placesFilterCountry">
                                <option value="">Tous les pays</option>
                                @foreach($countries ?? [] as $countryItem)
                                <option value="{{ $countryItem->id }}">{{ $countryItem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="placesFilterActivity" class="form-label">Activité</label>
                            <select class="form-select" id="placesFilterActivity">
                                <option value="">Toutes les activités</option>
                                @foreach($activities ?? [] as $activity)
                                <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="placesFilterSortBy" class="form-label">Trier par</label>
                            <select class="form-select" id="placesFilterSortBy">
                                <option value="name">Nom</option>
                                <option value="created_at">Date d'ajout</option>
                                <option value="rating">Note</option>
                                <option value="sort_order">Ordre manuel</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="placesFilterSortOrder" class="form-label">Ordre</label>
                            <select class="form-select" id="placesFilterSortOrder">
                                <option value="asc">Croissant</option>
                                <option value="desc">Décroissant</option>
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
                                    <h3 class="mb-0" id="totalPlaces">0</h3>
                                    <p class="text-muted mb-0">Total Lieux</p>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-map-marker-alt text-primary fs-4"></i>
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
                                    <h3 class="mb-0" id="activePlaces">0</h3>
                                    <p class="text-muted mb-0">Actifs</p>
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
                                    <h3 class="mb-0" id="featuredPlaces">0</h3>
                                    <p class="text-muted mb-0">À la une</p>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-star text-warning fs-4"></i>
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
                                    <h3 class="mb-0" id="categoriesPlaces">0</h3>
                                    <p class="text-muted mb-0">Catégories</p>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-tags text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Rechercher des lieux par nom, adresse, catégorie..." id="placesSearchInput">
                    <button class="btn btn-outline-secondary" type="button" id="clearPlacesSearchBtn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading Spinner -->
            <div class="text-center py-5" id="placesLoadingSpinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
            
            <!-- Table Container -->
            <div id="placesTableContainer" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Lieu</th>
                                <th>Catégorie</th>
                                @if(!$country)
                                <th>Pays</th>
                                @endif
                                <th>Adresse</th>
                                <th>Note</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="placesTableBody">
                            <!-- Places will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Empty State -->
            <div class="text-center py-5" id="placesEmptyState" style="display: none;">
                <div class="mb-3">
                    <i class="fas fa-map-marker-alt fa-3x text-muted"></i>
                </div>
                <h4 class="text-muted">Aucun lieu trouvé</h4>
                <p class="text-muted mb-4">Commencez par ajouter votre premier lieu.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlaceModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un lieu
                </button>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer" id="placesPaginationContainer" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <div id="placesPaginationInfo"></div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0" id="placesPagination">
                        <!-- Pagination will be loaded here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- ADD PLACE MODAL -->
<div class="modal fade" id="addPlaceModal" tabindex="-1" aria-labelledby="addPlaceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlaceModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un lieu
                    @if($country)
                    <small class="text-muted">pour {{ $country->name }}</small>
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPlaceForm" enctype="multipart/form-data">
                    @csrf
                    
                    @if($country)
                    <input type="hidden" name="country_id" value="{{ $country->id }}">
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="placeName" class="form-label">Nom du lieu *</label>
                            <input type="text" class="form-control" id="placeName" name="name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="placeCategory" class="form-label">Catégorie *</label>
                            <select class="form-select" id="placeCategory" name="category" required>
                                <option value="">Sélectionnez</option>
                                <option value="restaurant">Restaurant</option>
                                <option value="hotel">Hôtel</option>
                                <option value="museum">Musée</option>
                                <option value="park">Parc</option>
                                <option value="beach">Plage</option>
                                <option value="shopping">Shopping</option>
                                <option value="attraction">Attraction</option>
                                <option value="historic">Historique</option>
                                <option value="religious">Religieux</option>
                                <option value="natural">Naturel</option>
                                <option value="cultural">Culturel</option>
                                <option value="sport">Sport</option>
                                <option value="entertainment">Divertissement</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="placeDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="placeDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="placeAddress" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="placeAddress" name="address">
                        </div>
                        <div class="col-md-3">
                            <label for="placePhone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="placePhone" name="phone">
                        </div>
                        <div class="col-md-3">
                            <label for="placeWebsite" class="form-label">Site web</label>
                            <input type="url" class="form-control" id="placeWebsite" name="website">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="placeEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="placeEmail" name="email">
                        </div>
                        <div class="col-md-3">
                            <label for="placeOpeningHours" class="form-label">Heure d'ouverture</label>
                            <input type="time" class="form-control" id="placeOpeningHours" name="opening_hours">
                        </div>
                        <div class="col-md-3">
                            <label for="placeClosingHours" class="form-label">Heure de fermeture</label>
                            <input type="time" class="form-control" id="placeClosingHours" name="closing_hours">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="placeLatitude" class="form-label">Latitude</label>
                            <input type="number" step="0.000001" class="form-control" id="placeLatitude" name="latitude">
                        </div>
                        <div class="col-md-4">
                            <label for="placeLongitude" class="form-label">Longitude</label>
                            <input type="number" step="0.000001" class="form-control" id="placeLongitude" name="longitude">
                        </div>
                        <div class="col-md-4">
                            <label for="placePriceRange" class="form-label">Prix moyen (€)</label>
                            <input type="number" step="0.01" class="form-control" id="placePriceRange" name="price_range">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="placeVideoUrl" class="form-label">URL vidéo (YouTube)</label>
                        <input type="url" class="form-control" id="placeVideoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    
                    @if(!$country)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="placeCountry" class="form-label">Pays *</label>
                            <select class="form-select" id="placeCountry" name="country_id" required>
                                <option value="">Sélectionnez un pays</option>
                                @foreach($countries ?? [] as $countryItem)
                                <option value="{{ $countryItem->id }}">{{ $countryItem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="placeActivity" class="form-label">Activité associée</label>
                            <select class="form-select" id="placeActivity" name="activity_id">
                                <option value="">Aucune activité spécifique</option>
                                @foreach($activities ?? [] as $activity)
                                <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="placeActivity" class="form-label">Activité associée</label>
                        <select class="form-select" id="placeActivity" name="activity_id">
                            <option value="">Aucune activité spécifique</option>
                            @foreach($activities ?? [] as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="placeImages" class="form-label">Images</label>
                        <input type="file" class="form-control" id="placeImages" name="images[]" multiple accept="image/*">
                        <div class="form-text">Vous pouvez sélectionner plusieurs images (Max: 50MB par image)</div>
                        <div class="mt-2" id="placeImagesPreview"></div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="placeTags" class="form-label">Tags (séparés par des virgules)</label>
                            <input type="text" class="form-control" id="placeTags" name="tags" placeholder="Ex: wifi, parking, terrasse, vue">
                        </div>
                        <div class="col-md-3">
                            <label for="placeRating" class="form-label">Note (1-5)</label>
                            <select class="form-select" id="placeRating" name="rating">
                                <option value="">Sans note</option>
                                <option value="1">1 ★</option>
                                <option value="2">2 ★★</option>
                                <option value="3">3 ★★★</option>
                                <option value="4">4 ★★★★</option>
                                <option value="5">5 ★★★★★</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="placeIsActive" class="form-label">Statut</label>
                            <select class="form-select" id="placeIsActive" name="is_active">
                                <option value="1" selected>Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="submitPlaceBtn">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter le lieu
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT PLACE MODAL -->
<div class="modal fade" id="editPlaceModal" tabindex="-1" aria-labelledby="editPlaceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlaceModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier le lieu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPlaceForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editPlaceId" name="id">
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="editPlaceName" class="form-label">Nom du lieu *</label>
                            <input type="text" class="form-control" id="editPlaceName" name="name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="editPlaceCategory" class="form-label">Catégorie *</label>
                            <select class="form-select" id="editPlaceCategory" name="category" required>
                                <option value="">Sélectionnez</option>
                                <option value="restaurant">Restaurant</option>
                                <option value="hotel">Hôtel</option>
                                <option value="museum">Musée</option>
                                <option value="park">Parc</option>
                                <option value="beach">Plage</option>
                                <option value="shopping">Shopping</option>
                                <option value="attraction">Attraction</option>
                                <option value="historic">Historique</option>
                                <option value="religious">Religieux</option>
                                <option value="natural">Naturel</option>
                                <option value="cultural">Culturel</option>
                                <option value="sport">Sport</option>
                                <option value="entertainment">Divertissement</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editPlaceDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editPlaceDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editPlaceAddress" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="editPlaceAddress" name="address">
                        </div>
                        <div class="col-md-3">
                            <label for="editPlacePhone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="editPlacePhone" name="phone">
                        </div>
                        <div class="col-md-3">
                            <label for="editPlaceWebsite" class="form-label">Site web</label>
                            <input type="url" class="form-control" id="editPlaceWebsite" name="website">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editPlaceEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editPlaceEmail" name="email">
                        </div>
                        <div class="col-md-3">
                            <label for="editPlaceOpeningHours" class="form-label">Heure d'ouverture</label>
                            <input type="time" class="form-control" id="editPlaceOpeningHours" name="opening_hours">
                        </div>
                        <div class="col-md-3">
                            <label for="editPlaceClosingHours" class="form-label">Heure de fermeture</label>
                            <input type="time" class="form-control" id="editPlaceClosingHours" name="closing_hours">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editPlaceLatitude" class="form-label">Latitude</label>
                            <input type="number" step="0.000001" class="form-control" id="editPlaceLatitude" name="latitude">
                        </div>
                        <div class="col-md-4">
                            <label for="editPlaceLongitude" class="form-label">Longitude</label>
                            <input type="number" step="0.000001" class="form-control" id="editPlaceLongitude" name="longitude">
                        </div>
                        <div class="col-md-4">
                            <label for="editPlacePriceRange" class="form-label">Prix moyen (€)</label>
                            <input type="number" step="0.01" class="form-control" id="editPlacePriceRange" name="price_range">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editPlaceVideoUrl" class="form-label">URL vidéo (YouTube)</label>
                        <input type="url" class="form-control" id="editPlaceVideoUrl" name="video_url">
                    </div>
                    
                    <div class="mb-3">
                        <label for="editPlaceActivity" class="form-label">Activité associée</label>
                        <select class="form-select" id="editPlaceActivity" name="activity_id">
                            <option value="">Aucune activité spécifique</option>
                            @foreach($activities ?? [] as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Current Images -->
                    <div class="mb-3" id="currentPlaceImagesContainer">
                        <label class="form-label">Images actuelles</label>
                        <div id="currentPlaceImages" class="d-flex flex-wrap gap-2 mb-2">
                            <!-- Images will be loaded here -->
                        </div>
                    </div>
                    
                    <!-- New Images -->
                    <div class="mb-3">
                        <label for="editPlaceImages" class="form-label">Nouvelles images</label>
                        <input type="file" class="form-control" id="editPlaceImages" name="images[]" multiple accept="image/*">
                        <div class="form-text">Sélectionnez de nouvelles images à ajouter</div>
                        <div class="mt-2" id="editPlaceImagesPreview"></div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editPlaceTags" class="form-label">Tags (séparés par des virgules)</label>
                            <input type="text" class="form-control" id="editPlaceTags" name="tags">
                        </div>
                        <div class="col-md-3">
                            <label for="editPlaceRating" class="form-label">Note (1-5)</label>
                            <select class="form-select" id="editPlaceRating" name="rating">
                                <option value="">Sans note</option>
                                <option value="1">1 ★</option>
                                <option value="2">2 ★★</option>
                                <option value="3">3 ★★★</option>
                                <option value="4">4 ★★★★</option>
                                <option value="5">5 ★★★★★</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editPlaceIsActive" class="form-label">Statut</label>
                            <select class="form-select" id="editPlaceIsActive" name="is_active">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editPlaceIsFeatured" class="form-label">À la une</label>
                            <select class="form-select" id="editPlaceIsFeatured" name="is_featured">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="editPlaceSortOrder" class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control" id="editPlaceSortOrder" name="sort_order" min="0" value="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updatePlaceBtn">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE PLACE MODAL -->
<div class="modal fade" id="deletePlaceModal" tabindex="-1" aria-labelledby="deletePlaceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePlaceModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Supprimer le lieu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce lieu ?</p>
                
                <div class="place-to-delete" id="placeToDeleteInfo">
                    <!-- Place info will be loaded here -->
                </div>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Attention :</strong> Cette action supprimera également toutes les images associées.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeletePlaceBtn">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MAP MODAL -->
<div class="modal fade" id="placesMapModal" tabindex="-1" aria-labelledby="placesMapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="placesMapModalLabel">
                    <i class="fas fa-map me-2"></i>Carte des lieux
                    @if($country)
                    <small class="text-muted">pour {{ $country->name }}</small>
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="placesMapContainer" style="height: 500px; width: 100%;"></div>
                <div class="mt-3" id="placesMapLegend"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Places Management -->
<script>
// Configuration
let currentPlacesPage = 1;
let currentPlacesFilters = {};
let allPlaces = [];
let placeToDelete = null;
let placesMap = null;
const paysId = {{ $country->id ?? 'null' }};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('places-tab-pane')) {
        // Load places when places tab is shown
        document.getElementById('places-tab').addEventListener('shown.bs.tab', function() {
            loadPlaces();
            loadPlacesStatistics();
        });
        
        setupPlacesEventListeners();
        setupPlacesUploads();
        
        // Load Leaflet CSS if not already loaded
        if (!document.querySelector('link[href*="leaflet.css"]')) {
            const leafletCSS = document.createElement('link');
            leafletCSS.rel = 'stylesheet';
            leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            leafletCSS.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
            leafletCSS.crossOrigin = '';
            document.head.appendChild(leafletCSS);
        }
    }
});

// Load places
const loadPlaces = (page = 1, filters = {}) => {
    showPlacesLoading();
    
    const searchTerm = document.getElementById('placesSearchInput')?.value || '';
    
    let url = '/countries/places';
    const params = {
        page: page,
        search: searchTerm,
        ...filters
    };
    
    if (paysId) {
        params.country_id = paysId;
    }
    
    $.ajax({
        url: url,
        type: 'GET',
        data: params,
        success: function(response) {
            if (response.success) {
                allPlaces = response.data.data || [];
                renderPlaces(allPlaces);
                renderPlacesPagination(response.data);
                hidePlacesLoading();
                
                // Update places count
                document.getElementById('placesCount').textContent = response.data.total || 0;
            } else {
                showAlert('danger', 'Erreur lors du chargement des lieux');
            }
        },
        error: function(xhr) {
            hidePlacesLoading();
            showAlert('danger', 'Erreur de connexion au serveur');
            console.error('Error:', xhr.responseText);
        }
    });
};

// Load places statistics
const loadPlacesStatistics = () => {
    let url = '/countries/places/statistics';
    const data = {};
    
    if (paysId) {
        data.country_id = paysId;
    }
    
    $.ajax({
        url: url,
        type: 'GET',
        data: data,
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                document.getElementById('totalPlaces').textContent = stats.total || 0;
                document.getElementById('activePlaces').textContent = stats.active || 0;
                document.getElementById('featuredPlaces').textContent = stats.featured || 0;
                document.getElementById('categoriesPlaces').textContent = Object.keys(stats.by_category || {}).length;
            }
        },
        error: function(xhr, status, error) {
            console.error('Places statistics AJAX error:', error);
        }
    });
};

// Render places in table
const renderPlaces = (places) => {
    const tbody = document.getElementById('placesTableBody');
    tbody.innerHTML = '';
    
    if (!places || !Array.isArray(places) || places.length === 0) {
        document.getElementById('placesEmptyState').style.display = 'block';
        document.getElementById('placesTableContainer').style.display = 'none';
        document.getElementById('placesPaginationContainer').style.display = 'none';
        return;
    }
    
    places.forEach((place, index) => {
        const row = document.createElement('tr');
        row.id = `place-row-${place.id}`;
        row.style.animationDelay = `${index * 0.05}s`;
        
        // Get category icon
        const categoryIcons = {
            'restaurant': 'fas fa-utensils',
            'hotel': 'fas fa-hotel',
            'museum': 'fas fa-landmark',
            'park': 'fas fa-tree',
            'beach': 'fas fa-umbrella-beach',
            'shopping': 'fas fa-shopping-bag',
            'attraction': 'fas fa-camera',
            'historic': 'fas fa-monument',
            'religious': 'fas fa-church',
            'natural': 'fas fa-mountain',
            'cultural': 'fas fa-theater-masks',
            'sport': 'fas fa-futbol',
            'entertainment': 'fas fa-film'
        };
        
        const categoryIcon = categoryIcons[place.category] || 'fas fa-map-marker-alt';
        const categoryLabel = getCategoryLabel(place.category);
        
        // Format rating
        let ratingStars = '';
        if (place.rating) {
            ratingStars = '★'.repeat(place.rating) + '☆'.repeat(5 - place.rating);
        }
        
        row.innerHTML = `
            <td class="place-name-cell">
                <div class="d-flex align-items-center">
                    <div class="place-icon me-3">
                        <i class="${categoryIcon} text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="place-name-text">${place.name}</div>
                        <small class="text-muted">${place.activity?.name || 'Pas d\'activité'}</small>
                    </div>
                    ${place.is_featured ? '<i class="fas fa-star text-warning ms-2" title="À la une"></i>' : ''}
                </div>
            </td>
            <td>
                <span class="badge bg-light text-dark">${categoryLabel}</span>
            </td>
            ${!paysId ? `
            <td>
                <small>${place.country?.name || 'N/A'}</small>
            </td>
            ` : ''}
            <td>
                <small>${place.address || 'N/A'}</small>
            </td>
            <td>
                ${ratingStars || '<span class="text-muted">N/A</span>'}
            </td>
            <td>
                <div class="status-toggle-container">
                    <div class="toggle-switch ${place.is_active ? 'active' : ''}" 
                         onclick="togglePlaceStatus(${place.id}, ${place.is_active})">
                        <div class="toggle-slider"></div>
                    </div>
                    <span class="status-text ${place.is_active ? 'text-success' : 'text-danger'}">
                        ${place.is_active ? 'Actif' : 'Inactif'}
                    </span>
                </div>
            </td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="viewPlaceDetails(${place.id})" title="Voir">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="openEditPlaceModal(${place.id})" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="showDeletePlaceConfirmation(${place.id})" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    document.getElementById('placesEmptyState').style.display = 'none';
    document.getElementById('placesTableContainer').style.display = 'block';
    document.getElementById('placesPaginationContainer').style.display = 'flex';
};

// Get category label
const getCategoryLabel = (category) => {
    const labels = {
        'restaurant': 'Restaurant',
        'hotel': 'Hôtel',
        'museum': 'Musée',
        'park': 'Parc',
        'beach': 'Plage',
        'shopping': 'Shopping',
        'attraction': 'Attraction',
        'historic': 'Historique',
        'religious': 'Religieux',
        'natural': 'Naturel',
        'cultural': 'Culturel',
        'sport': 'Sport',
        'entertainment': 'Divertissement'
    };
    return labels[category] || category;
};

// Toggle place status
const togglePlaceStatus = (placeId, currentStatus) => {
    const toggleElement = document.querySelector(`#place-row-${placeId} .toggle-switch`);
    const statusText = document.querySelector(`#place-row-${placeId} .status-text`);
    
    if (!toggleElement || !statusText) return;
    
    toggleElement.style.pointerEvents = 'none';
    toggleElement.classList.add('loading');
    
    const newStatus = !currentStatus;
    
    $.ajax({
        url: `/countries/places/${placeId}/toggle-status`,
        type: 'POST',
        data: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function(response) {
            if (response.success) {
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
                
                const placeIndex = allPlaces.findIndex(p => p.id === placeId);
                if (placeIndex !== -1) {
                    allPlaces[placeIndex].is_active = newStatus;
                }
                
                showAlert('success', `Lieu ${newStatus ? 'activé' : 'désactivé'} avec succès !`);
                
                // Update statistics
                loadPlacesStatistics();
            } else {
                showAlert('danger', response.message || 'Erreur lors de la mise à jour du statut');
                toggleElement.style.pointerEvents = 'auto';
                toggleElement.classList.remove('loading');
            }
        },
        error: function(xhr, status, error) {
            showAlert('danger', 'Erreur lors de la mise à jour du statut: ' + error);
            toggleElement.style.pointerEvents = 'auto';
            toggleElement.classList.remove('loading');
        },
        complete: function() {
            setTimeout(() => {
                toggleElement.style.pointerEvents = 'auto';
                toggleElement.classList.remove('loading');
            }, 500);
        }
    });
};

// Open edit place modal
const openEditPlaceModal = (placeId) => {
    const place = allPlaces.find(p => p.id === placeId);
    
    if (!place) {
        showAlert('danger', 'Lieu non trouvé');
        return;
    }
    
    // Load full place details
    $.ajax({
        url: `/countries/places/${placeId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const place = response.data;
                
                document.getElementById('editPlaceId').value = place.id;
                document.getElementById('editPlaceName').value = place.name;
                document.getElementById('editPlaceCategory').value = place.category;
                document.getElementById('editPlaceDescription').value = place.description || '';
                document.getElementById('editPlaceAddress').value = place.address || '';
                document.getElementById('editPlacePhone').value = place.phone || '';
                document.getElementById('editPlaceWebsite').value = place.website || '';
                document.getElementById('editPlaceEmail').value = place.email || '';
                document.getElementById('editPlaceOpeningHours').value = place.opening_hours || '';
                document.getElementById('editPlaceClosingHours').value = place.closing_hours || '';
                document.getElementById('editPlaceLatitude').value = place.latitude || '';
                document.getElementById('editPlaceLongitude').value = place.longitude || '';
                document.getElementById('editPlacePriceRange').value = place.price_range || '';
                document.getElementById('editPlaceVideoUrl').value = place.video_url || '';
                document.getElementById('editPlaceTags').value = place.tags ? place.tags.join(', ') : '';
                document.getElementById('editPlaceRating').value = place.rating || '';
                document.getElementById('editPlaceIsActive').value = place.is_active ? '1' : '0';
                document.getElementById('editPlaceIsFeatured').value = place.is_featured ? '1' : '0';
                document.getElementById('editPlaceSortOrder').value = place.sort_order || 0;
                
                // Set activity
                const activitySelect = document.getElementById('editPlaceActivity');
                if (activitySelect) {
                    activitySelect.value = place.activity_id || '';
                }
                
                // Display current images
                const imagesContainer = document.getElementById('currentPlaceImages');
                imagesContainer.innerHTML = '';
                
                if (place.images && place.images.length > 0) {
                    document.getElementById('currentPlaceImagesContainer').style.display = 'block';
                    
                    place.images.forEach((image, index) => {
                        const imageUrl = place.image_urls && place.image_urls[index] 
                            ? place.image_urls[index] 
                            : '/images/default-place.jpg';
                        
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'position-relative';
                        imageDiv.style.width = '100px';
                        
                        imageDiv.innerHTML = `
                            <img src="${imageUrl}" 
                                 alt="Image ${index + 1}" 
                                 class="img-thumbnail"
                                 style="width: 100px; height: 70px; object-fit: cover;">
                            <button type="button" 
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                    onclick="removePlaceImage('${image}', ${place.id})"
                                    style="transform: translate(30%, -30%);">
                                <i class="fas fa-times"></i>
                            </button>
                            <input type="hidden" name="remove_images[]" value="${image}">
                        `;
                        
                        imagesContainer.appendChild(imageDiv);
                    });
                } else {
                    document.getElementById('currentPlaceImagesContainer').style.display = 'none';
                }
                
                new bootstrap.Modal(document.getElementById('editPlaceModal')).show();
            }
        },
        error: function(xhr, status, error) {
            showAlert('danger', 'Erreur lors du chargement du lieu');
        }
    });
};

// Remove place image
const removePlaceImage = (imagePath, placeId) => {
    if (!confirm('Voulez-vous vraiment supprimer cette image ?')) {
        return;
    }
    
    // Just hide it for now, it will be removed when form is submitted
    const imageElement = document.querySelector(`button[onclick="removePlaceImage('${imagePath}', ${placeId})"]`);
    if (imageElement) {
        imageElement.parentElement.remove();
    }
    
    // Update the UI
    const imagesContainer = document.getElementById('currentPlaceImages');
    if (imagesContainer.children.length === 0) {
        document.getElementById('currentPlaceImagesContainer').style.display = 'none';
    }
};

// Show delete place confirmation
const showDeletePlaceConfirmation = (placeId) => {
    const place = allPlaces.find(p => p.id === placeId);
    
    if (!place) {
        showAlert('danger', 'Lieu non trouvé');
        return;
    }
    
    placeToDelete = place;
    
    document.getElementById('placeToDeleteInfo').innerHTML = `
        <div class="place-info">
            <div class="d-flex align-items-center gap-3">
                <div class="place-info-icon">
                    <i class="${place.category_icon} fa-2x text-primary"></i>
                </div>
                <div>
                    <div class="place-info-name">${place.name}</div>
                    <div class="place-info-category">${getCategoryLabel(place.category)} • ${place.address || 'Sans adresse'}</div>
                </div>
            </div>
        </div>
    `;
    
    new bootstrap.Modal(document.getElementById('deletePlaceModal')).show();
};

// Store place
const storePlace = () => {
    const form = document.getElementById('addPlaceForm');
    const submitBtn = document.getElementById('submitPlaceBtn');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    submitBtn.classList.add('btn-processing');
    submitBtn.innerHTML = `
        <span class="btn-text" style="display: none;">
            <i class="fas fa-plus-circle me-2"></i>Ajouter le lieu
        </span>
        <div class="spinner-border spinner-border-sm text-light" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        Ajout en cours...
    `;
    submitBtn.disabled = true;
    
    const formData = new FormData(form);
    
    $.ajax({
        url: '/countries/places',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter le lieu
                </span>
            `;
            submitBtn.disabled = false;
            
            if (response.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addPlaceModal'));
                modal.hide();
                
                form.reset();
                document.getElementById('placeImagesPreview').innerHTML = '';
                
                loadPlaces(1, currentPlacesFilters);
                loadPlacesStatistics();
                
                showAlert('success', 'Lieu ajouté avec succès !');
            } else {
                showAlert('danger', response.message || 'Erreur lors de l\'ajout');
            }
        },
        error: function(xhr, status, error) {
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter le lieu
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
                showAlert('danger', 'Erreur lors de l\'ajout: ' + error);
            }
        }
    });
};

// Update place
const updatePlace = () => {
    const form = document.getElementById('editPlaceForm');
    const submitBtn = document.getElementById('updatePlaceBtn');
    const placeId = document.getElementById('editPlaceId').value;
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
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
    
    $.ajax({
        url: `/countries/places/${placeId}`,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </span>
            `;
            submitBtn.disabled = false;
            
            if (response.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editPlaceModal'));
                modal.hide();
                
                loadPlaces(currentPlacesPage, currentPlacesFilters);
                loadPlacesStatistics();
                
                showAlert('success', 'Lieu mis à jour avec succès !');
            } else {
                showAlert('danger', response.message || 'Erreur lors de la mise à jour');
            }
        },
        error: function(xhr, status, error) {
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-save me-2"></i>Enregistrer
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

// Delete place
const deletePlace = () => {
    if (!placeToDelete) {
        showAlert('danger', 'Aucun lieu à supprimer');
        return;
    }
    
    const placeId = placeToDelete.id;
    const deleteBtn = document.getElementById('confirmDeletePlaceBtn');
    
    deleteBtn.innerHTML = `
        <span class="btn-text" style="display: none;">
            <i class="fas fa-trash me-2"></i>Supprimer
        </span>
        <div class="spinner-border spinner-border-sm text-light" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        Suppression...
    `;
    deleteBtn.disabled = true;
    
    $.ajax({
        url: `/countries/places/${placeId}`,
        type: 'DELETE',
        data: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function(response) {
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deletePlaceModal'));
            deleteModal.hide();
            
            if (response.success) {
                allPlaces = allPlaces.filter(p => p.id !== placeId);
                
                loadPlacesStatistics();
                
                showAlert('success', response.message || 'Lieu supprimé avec succès !');
                
                setTimeout(() => {
                    loadPlaces(currentPlacesPage, currentPlacesFilters);
                }, 500);
            } else {
                showAlert('danger', response.message || 'Erreur lors de la suppression');
            }
        },
        error: function(xhr, status, error) {
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deletePlaceModal'));
            deleteModal.hide();
            
            if (xhr.status === 404) {
                showAlert('danger', 'Lieu non trouvé.');
                loadPlaces(currentPlacesPage, currentPlacesFilters);
            } else {
                showAlert('danger', 'Erreur lors de la suppression: ' + error);
            }
        },
        complete: function() {
            placeToDelete = null;
        }
    });
};

// View place details
const viewPlaceDetails = (placeId) => {
    window.location.href = `/places/${placeId}`;
};

// Show places map
const showPlacesMap = () => {
    // Load map data
    $.ajax({
        url: '/countries/places/map-data',
        data: paysId ? { country_id: paysId } : {},
        success: function(response) {
            if (response.success) {
                initPlacesMap(response.data);
                new bootstrap.Modal(document.getElementById('placesMapModal')).show();
            }
        },
        error: function(xhr, status, error) {
            showAlert('danger', 'Erreur lors du chargement de la carte');
        }
    });
};

// Initialize places map
const initPlacesMap = (places) => {
    const mapContainer = document.getElementById('placesMapContainer');
    mapContainer.innerHTML = ''; // Clear previous map
    
    // Load Leaflet JS dynamically
    if (typeof L === 'undefined') {
        const leafletScript = document.createElement('script');
        leafletScript.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        leafletScript.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
        leafletScript.crossOrigin = '';
        leafletScript.onload = () => renderMap(places);
        document.head.appendChild(leafletScript);
    } else {
        renderMap(places);
    }
};

// Render the map
const renderMap = (places) => {
    const mapContainer = document.getElementById('placesMapContainer');
    
    // Default center (France if no places)
    let center = [46.603354, 1.888334];
    let zoom = 6;
    
    if (places.length > 0) {
        // Calculate center based on places
        const lats = places.map(p => p.position.lat);
        const lngs = places.map(p => p.position.lng);
        center = [
            (Math.min(...lats) + Math.max(...lats)) / 2,
            (Math.min(...lngs) + Math.max(...lngs)) / 2
        ];
        
        // Adjust zoom based on spread
        const latSpread = Math.max(...lats) - Math.min(...lats);
        const lngSpread = Math.max(...lngs) - Math.min(...lngs);
        const maxSpread = Math.max(latSpread, lngSpread);
        zoom = maxSpread > 10 ? 5 : maxSpread > 5 ? 6 : maxSpread > 2 ? 8 : 10;
    }
    
    // Create map
    placesMap = L.map('placesMapContainer').setView(center, zoom);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(placesMap);
    
    // Category icons
    const categoryIcons = {
        'restaurant': L.divIcon({
            html: '<i class="fas fa-utensils text-danger"></i>',
            iconSize: [30, 30],
            className: 'custom-div-icon'
        }),
        'hotel': L.divIcon({
            html: '<i class="fas fa-hotel text-primary"></i>',
            iconSize: [30, 30],
            className: 'custom-div-icon'
        }),
        'museum': L.divIcon({
            html: '<i class="fas fa-landmark text-warning"></i>',
            iconSize: [30, 30],
            className: 'custom-div-icon'
        }),
        'park': L.divIcon({
            html: '<i class="fas fa-tree text-success"></i>',
            iconSize: [30, 30],
            className: 'custom-div-icon'
        }),
        'beach': L.divIcon({
            html: '<i class="fas fa-umbrella-beach text-info"></i>',
            iconSize: [30, 30],
            className: 'custom-div-icon'
        }),
        'default': L.divIcon({
            html: '<i class="fas fa-map-marker-alt text-secondary"></i>',
            iconSize: [30, 30],
            className: 'custom-div-icon'
        })
    };
    
    // Add markers
    places.forEach(place => {
        const icon = categoryIcons[place.category] || categoryIcons['default'];
        
        const marker = L.marker([place.position.lat, place.position.lng], { icon })
            .addTo(placesMap)
            .bindPopup(`
                <div class="place-popup">
                    <h6>${place.name}</h6>
                    <p class="mb-1"><strong>Catégorie:</strong> ${getCategoryLabel(place.category)}</p>
                    <p class="mb-1"><strong>Statut:</strong> ${place.is_active ? 'Actif' : 'Inactif'}</p>
                    ${place.is_featured ? '<p class="mb-1"><i class="fas fa-star text-warning"></i> À la une</p>' : ''}
                    <div class="text-end mt-2">
                        <button class="btn btn-sm btn-primary" onclick="openEditPlaceModal(${place.id})">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                    </div>
                </div>
            `);
    });
    
    // Add legend
    const legend = document.getElementById('placesMapLegend');
    legend.innerHTML = `
        <div class="d-flex flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-utensils text-danger me-2"></i> Restaurant
            </div>
            <div class="d-flex align-items-center">
                <i class="fas fa-hotel text-primary me-2"></i> Hôtel
            </div>
            <div class="d-flex align-items-center">
                <i class="fas fa-landmark text-warning me-2"></i> Musée
            </div>
            <div class="d-flex align-items-center">
                <i class="fas fa-tree text-success me-2"></i> Parc
            </div>
            <div class="d-flex align-items-center">
                <i class="fas fa-umbrella-beach text-info me-2"></i> Plage
            </div>
            <div class="d-flex align-items-center">
                <i class="fas fa-map-marker-alt text-secondary me-2"></i> Autre
            </div>
        </div>
    `;
};

// Setup places uploads
const setupPlacesUploads = () => {
    // Images preview for add modal
    const placeImagesInput = document.getElementById('placeImages');
    const placeImagesPreview = document.getElementById('placeImagesPreview');
    
    if (placeImagesInput) {
        placeImagesInput.addEventListener('change', function(e) {
            placeImagesPreview.innerHTML = '';
            Array.from(e.target.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail me-2 mb-2';
                        img.style.width = '100px';
                        img.style.height = '70px';
                        img.style.objectFit = 'cover';
                        placeImagesPreview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    }
    
    // Images preview for edit modal
    const editPlaceImagesInput = document.getElementById('editPlaceImages');
    const editPlaceImagesPreview = document.getElementById('editPlaceImagesPreview');
    
    if (editPlaceImagesInput) {
        editPlaceImagesInput.addEventListener('change', function(e) {
            editPlaceImagesPreview.innerHTML = '';
            Array.from(e.target.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail me-2 mb-2';
                        img.style.width = '100px';
                        img.style.height = '70px';
                        img.style.objectFit = 'cover';
                        editPlaceImagesPreview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    }
};

// Render places pagination
const renderPlacesPagination = (response) => {
    const pagination = document.getElementById('placesPagination');
    const paginationInfo = document.getElementById('placesPaginationInfo');
    
    const start = (response.current_page - 1) * response.per_page + 1;
    const end = Math.min(response.current_page * response.per_page, response.total);
    paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} lieux`;
    
    let paginationHtml = '';
    
    if (response.prev_page_url) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePlacesPage(${response.current_page - 1})">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;
    } else {
        paginationHtml += `
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
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
                    <span class="page-link">${i}</span>
                </li>
            `;
        } else {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="changePlacesPage(${i})">${i}</a>
                </li>
            `;
        }
    }
    
    if (response.next_page_url) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePlacesPage(${response.current_page + 1})">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;
    } else {
        paginationHtml += `
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
            </li>
        `;
    }
    
    pagination.innerHTML = paginationHtml;
};

// Change places page
const changePlacesPage = (page) => {
    currentPlacesPage = page;
    loadPlaces(page, currentPlacesFilters);
};

// Show places loading state
const showPlacesLoading = () => {
    document.getElementById('placesLoadingSpinner').style.display = 'flex';
    document.getElementById('placesTableContainer').style.display = 'none';
    document.getElementById('placesEmptyState').style.display = 'none';
    document.getElementById('placesPaginationContainer').style.display = 'none';
};

// Hide places loading state
const hidePlacesLoading = () => {
    document.getElementById('placesLoadingSpinner').style.display = 'none';
};

// Setup places event listeners
const setupPlacesEventListeners = () => {
    // Search input
    const searchInput = document.getElementById('placesSearchInput');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadPlaces(1, currentPlacesFilters);
            }, 500);
        });
    }
    
    // Clear search
    const clearSearchBtn = document.getElementById('clearPlacesSearchBtn');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            loadPlaces(1, currentPlacesFilters);
        });
    }
    
    // Show map button
    const showMapBtn = document.getElementById('showPlacesMapBtn');
    if (showMapBtn) {
        showMapBtn.addEventListener('click', showPlacesMap);
    }
    
    // Toggle filter section
    const toggleFilterBtn = document.getElementById('togglePlacesFilterBtn');
    const filterSection = document.getElementById('placesFilterSection');
    
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
    const applyFiltersBtn = document.getElementById('applyPlacesFiltersBtn');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', () => {
            currentPlacesFilters = {
                category: document.getElementById('placesFilterCategory').value,
                status: document.getElementById('placesFilterStatus').value,
                featured: document.getElementById('placesFilterFeatured').value,
                country_id: document.getElementById('placesFilterCountry')?.value || '',
                activity_id: document.getElementById('placesFilterActivity').value,
                sort_by: document.getElementById('placesFilterSortBy').value,
                sort_order: document.getElementById('placesFilterSortOrder').value
            };
            loadPlaces(1, currentPlacesFilters);
        });
    }
    
    // Clear filters
    const clearFiltersBtn = document.getElementById('clearPlacesFiltersBtn');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
            document.getElementById('placesFilterCategory').value = '';
            document.getElementById('placesFilterStatus').value = '';
            document.getElementById('placesFilterFeatured').value = '';
            if (document.getElementById('placesFilterCountry')) {
                document.getElementById('placesFilterCountry').value = '';
            }
            document.getElementById('placesFilterActivity').value = '';
            document.getElementById('placesFilterSortBy').value = 'name';
            document.getElementById('placesFilterSortOrder').value = 'asc';
            currentPlacesFilters = {};
            loadPlaces(1);
        });
    }
    
    // Submit place form
    const submitPlaceBtn = document.getElementById('submitPlaceBtn');
    if (submitPlaceBtn) {
        submitPlaceBtn.addEventListener('click', storePlace);
    }
    
    // Update place form
    const updatePlaceBtn = document.getElementById('updatePlaceBtn');
    if (updatePlaceBtn) {
        updatePlaceBtn.addEventListener('click', updatePlace);
    }
    
    // Confirm delete place button
    const confirmDeletePlaceBtn = document.getElementById('confirmDeletePlaceBtn');
    if (confirmDeletePlaceBtn) {
        confirmDeletePlaceBtn.addEventListener('click', deletePlace);
    }
    
    // Reset add modal when hidden
    const addModal = document.getElementById('addPlaceModal');
    if (addModal) {
        addModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('addPlaceForm').reset();
            document.getElementById('placeImagesPreview').innerHTML = '';
            const submitBtn = document.getElementById('submitPlaceBtn');
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter le lieu
                </span>
            `;
            submitBtn.disabled = false;
        });
    }
    
    // Reset edit modal when hidden
    const editModal = document.getElementById('editPlaceModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('editPlaceForm').reset();
            document.getElementById('currentPlaceImagesContainer').style.display = 'none';
            document.getElementById('editPlaceImagesPreview').innerHTML = '';
            const submitBtn = document.getElementById('updatePlaceBtn');
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </span>
            `;
            submitBtn.disabled = false;
        });
    }
    
    // Reset delete modal when hidden
    const deleteModal = document.getElementById('deletePlaceModal');
    if (deleteModal) {
        deleteModal.addEventListener('hidden.bs.modal', function() {
            placeToDelete = null;
            const deleteBtn = document.getElementById('confirmDeletePlaceBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </span>
            `;
            deleteBtn.disabled = false;
        });
    }
    
    // Reset map modal when hidden
    const mapModal = document.getElementById('placesMapModal');
    if (mapModal) {
        mapModal.addEventListener('hidden.bs.modal', function() {
            if (placesMap) {
                placesMap.remove();
                placesMap = null;
            }
        });
    }
};
</script>

<style>
/* Place-specific styles */
.place-name-cell {
    min-width: 200px;
}

.place-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.place-name-text {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 2px;
}

/* Map styles */
.custom-div-icon {
    background: transparent;
    border: none;
}

.custom-div-icon i {
    font-size: 24px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.place-popup {
    min-width: 200px;
}

.place-popup h6 {
    margin-bottom: 8px;
    color: #333;
}

.place-popup p {
    margin-bottom: 4px;
    font-size: 0.9rem;
}

/* Image preview styles */
.img-thumbnail {
    transition: all 0.3s ease;
}

.img-thumbnail:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Category badges */
.badge.bg-light {
    border: 1px solid #dee2e6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .place-name-cell {
        min-width: auto;
    }
    
    .btn-group-sm {
        flex-direction: column;
    }
    
    .btn-group-sm .btn {
        margin-bottom: 5px;
    }
}
</style>