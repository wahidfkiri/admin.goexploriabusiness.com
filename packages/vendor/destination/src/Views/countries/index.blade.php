@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-flag"></i></span>
                Gestion des Pays
            </h1>
            
            <!-- Dans la section page-actions (après le bouton Nouveau Pays) -->
<div class="page-actions">
    <button class="btn btn-outline-secondary" id="toggleFilterBtn">
        <i class="fas fa-sliders-h me-2"></i>Filtres
    </button>
    <!-- Bouton pour créer une activité -->
    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#createActivityModal">
        <i class="fas fa-hiking me-2"></i>Nouvelle Activité
    </button>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCountryModal">
        <i class="fas fa-plus-circle me-2"></i>Nouveau Pays
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
                    <label for="filterContinent" class="form-label-modern">Continent</label>
                    <select class="form-select-modern" id="filterContinent">
                        <option value="">Tous les continents</option>
                        @foreach($continents ?? [] as $continent)
                            <option value="{{ $continent->code }}">{{ $continent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterRegion" class="form-label-modern">Région</label>
                    <input type="text" class="form-control-modern" id="filterRegion" placeholder="Ex: Europe de l'Ouest">
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
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalCountries">0</div>
                        <div class="stats-label-modern">Total Pays</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-flag"></i>
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
                        <div class="stats-value-modern" id="totalProvinces">0</div>
                        <div class="stats-label-modern">Total Provinces</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-map"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Pays</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un pays..." id="searchInput">
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
                                <th>Pays</th>
                                <th>Continent</th>
                                <th>Capital</th>
                                <th>Population</th>
                                <th>Superficie</th>
                                 <th>Status</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="countriesTableBody">
                            <!-- Countries will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-flag"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun pays trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier pays.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCountryModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un pays
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createCountryModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE COUNTRY MODAL -->
    <div class="modal fade" id="createCountryModal" tabindex="-1" aria-labelledby="createCountryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createCountryModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau pays
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createCountryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="countryName" class="form-label-modern">Nom du pays *</label>
                                <input type="text" class="form-control-modern" id="countryName" name="name" 
                                       placeholder="Ex: France, Canada, Japon..." required>
                                <div class="form-text-modern">Nom complet du pays</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="countryCode" class="form-label-modern">Code (3 lettres) *</label>
                                <input type="text" class="form-control-modern" id="countryCode" name="code" 
                                       placeholder="Ex: FRA, CAN, JPN" maxlength="3" required>
                                <div class="form-text-modern">Code ISO à 3 lettres</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="countryIso2" class="form-label-modern">Code (2 lettres)</label>
                                <input type="text" class="form-control-modern" id="countryIso2" name="iso2" 
                                       placeholder="Ex: FR, CA, JP" maxlength="2">
                                <div class="form-text-modern">Code ISO à 2 lettres</div>
                            </div>
                            <!-- In CREATE COUNTRY MODAL, replace the flag URL input -->
<div class="col-md-12 mb-3">
    <label for="countryFlag" class="form-label-modern">Drapeau (Image)</label>
    <input type="file" class="form-control-modern" id="countryFlag" name="image" accept="image/*">
    <div class="form-text-modern">Formats acceptés: JPG, PNG, GIF, SVG (Max: 2MB)</div>
    <div class="image-preview mt-2" id="flagPreview" style="display: none;">
        <img id="previewFlagImage" class="preview-image" style="max-width: 100px; max-height: 60px;">
    </div>
</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="countryContinent" class="form-label-modern">Continent *</label>
                                <select class="form-select-modern" id="countryContinent" name="continent_id" required>
                                    <option value="">Sélectionnez un continent</option>
                                    @foreach($continents ?? [] as $continent)
                                        <option value="{{ $continent->id }}">{{ $continent->name }} ({{ $continent->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="countryCapital" class="form-label-modern">Capitale</label>
                                <input type="text" class="form-control-modern" id="countryCapital" name="capital" 
                                       placeholder="Ex: Paris, Ottawa, Tokyo">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="countryPopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="countryPopulation" 
                                       name="population" placeholder="Ex: 67000000" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="countryArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="countryArea" name="area" 
                                       placeholder="Ex: 551695" min="0" step="0.01">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="countryRegion" class="form-label-modern">Région</label>
                                <input type="text" class="form-control-modern" id="countryRegion" name="region" 
                                       placeholder="Ex: Europe de l'Ouest">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="countryCurrency" class="form-label-modern">Devise</label>
                                <input type="text" class="form-control-modern" id="countryCurrency" name="currency" 
                                       placeholder="Ex: Euro, Dollar canadien">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="countryCurrencySymbol" class="form-label-modern">Symbole devise</label>
                                <input type="text" class="form-control-modern" id="countryCurrencySymbol" name="currency_symbol" 
                                       placeholder="Ex: €, $, ¥" maxlength="10">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="countryPhoneCode" class="form-label-modern">Code téléphonique</label>
                                <input type="text" class="form-control-modern" id="countryPhoneCode" name="phone_code" 
                                       placeholder="Ex: +33, +1, +81">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="countryOfficialLanguage" class="form-label-modern">Langue officielle</label>
                                <input type="text" class="form-control-modern" id="countryOfficialLanguage" name="official_language" 
                                       placeholder="Ex: Français, Anglais, Japonais">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="countryFlag" class="form-label-modern">Drapeau (URL)</label>
                                <input type="text" class="form-control-modern" id="countryFlag" name="flag" 
                                       placeholder="Ex: fr-flag.svg, ca-flag.svg">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="countryLatitude" class="form-label-modern">Latitude</label>
                                <input type="text" class="form-control-modern" id="countryLatitude" name="latitude" 
                                       placeholder="Ex: 46.2276">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="countryLongitude" class="form-label-modern">Longitude</label>
                                <input type="text" class="form-control-modern" id="countryLongitude" name="longitude" 
                                       placeholder="Ex: 2.2137">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="countryTimezones" class="form-label-modern">Fuseaux horaires</label>
                            <select class="form-select-modern" id="countryTimezones" name="timezones[]" multiple>
                                <option value="UTC-12:00">UTC-12:00</option>
                                <option value="UTC-11:00">UTC-11:00</option>
                                <option value="UTC-10:00">UTC-10:00</option>
                                <option value="UTC-09:00">UTC-09:00</option>
                                <option value="UTC-08:00">UTC-08:00</option>
                                <option value="UTC-07:00">UTC-07:00</option>
                                <option value="UTC-06:00">UTC-06:00</option>
                                <option value="UTC-05:00">UTC-05:00</option>
                                <option value="UTC-04:00">UTC-04:00</option>
                                <option value="UTC-03:30">UTC-03:30</option>
                                <option value="UTC-03:00">UTC-03:00</option>
                                <option value="UTC-02:00">UTC-02:00</option>
                                <option value="UTC-01:00">UTC-01:00</option>
                                <option value="UTC±00:00">UTC±00:00</option>
                                <option value="UTC+01:00">UTC+01:00</option>
                                <option value="UTC+02:00">UTC+02:00</option>
                                <option value="UTC+03:00">UTC+03:00</option>
                                <option value="UTC+03:30">UTC+03:30</option>
                                <option value="UTC+04:00">UTC+04:00</option>
                                <option value="UTC+04:30">UTC+04:30</option>
                                <option value="UTC+05:00">UTC+05:00</option>
                                <option value="UTC+05:30">UTC+05:30</option>
                                <option value="UTC+05:45">UTC+05:45</option>
                                <option value="UTC+06:00">UTC+06:00</option>
                                <option value="UTC+06:30">UTC+06:30</option>
                                <option value="UTC+07:00">UTC+07:00</option>
                                <option value="UTC+08:00">UTC+08:00</option>
                                <option value="UTC+08:45">UTC+08:45</option>
                                <option value="UTC+09:00">UTC+09:00</option>
                                <option value="UTC+09:30">UTC+09:30</option>
                                <option value="UTC+10:00">UTC+10:00</option>
                                <option value="UTC+10:30">UTC+10:30</option>
                                <option value="UTC+11:00">UTC+11:00</option>
                                <option value="UTC+12:00">UTC+12:00</option>
                                <option value="UTC+12:45">UTC+12:45</option>
                                <option value="UTC+13:00">UTC+13:00</option>
                                <option value="UTC+14:00">UTC+14:00</option>
                            </select>
                            <div class="form-text-modern">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs fuseaux</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="countryDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="countryDescription" name="description" 
                                      rows="3" placeholder="Description du pays..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitCountryBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le pays
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT COUNTRY MODAL -->
    <div class="modal fade" id="editCountryModal" tabindex="-1" aria-labelledby="editCountryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editCountryModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier le pays
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editCountryForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editCountryId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editCountryName" class="form-label-modern">Nom du pays *</label>
                                <input type="text" class="form-control-modern" id="editCountryName" name="name" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editCountryCode" class="form-label-modern">Code (3 lettres) *</label>
                                <input type="text" class="form-control-modern" id="editCountryCode" name="code" maxlength="3" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editCountryIso2" class="form-label-modern">Code (2 lettres)</label>
                                <input type="text" class="form-control-modern" id="editCountryIso2" name="iso2" maxlength="2">
                            </div>
                            <!-- In EDIT COUNTRY MODAL, replace the flag URL input -->
<div class="col-md-12 mb-3">
    <label for="editCountryFlag" class="form-label-modern">Drapeau (Image)</label>
    
    <!-- Current image preview -->
    <div class="current-image-preview mb-2" id="currentFlagPreview" style="display: none;">
        <img id="currentFlagImage" class="preview-image" style="max-width: 100px; max-height: 60px;">
        <small class="text-muted d-block">Image actuelle</small>
    </div>
    
    <input type="file" class="form-control-modern" id="editCountryFlag" name="image" accept="image/*">
    <div class="form-text-modern">Laissez vide pour conserver l'image actuelle</div>
    <div class="image-preview mt-2" id="editFlagPreview" style="display: none;">
        <img id="editPreviewFlagImage" class="preview-image" style="max-width: 100px; max-height: 60px;">
        <small class="text-muted d-block">Nouvelle image</small>
    </div>
</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editCountryContinent" class="form-label-modern">Continent *</label>
                                <select class="form-select-modern" id="editCountryContinent" name="continent_id" required>
                                    <option value="">Sélectionnez un continent</option>
                                    @foreach($continents ?? [] as $continent)
                                        <option value="{{ $continent->id }}">{{ $continent->name }} ({{ $continent->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editCountryCapital" class="form-label-modern">Capitale</label>
                                <input type="text" class="form-control-modern" id="editCountryCapital" name="capital">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editCountryPopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="editCountryPopulation" name="population" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editCountryArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="editCountryArea" name="area" min="0" step="0.01">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editCountryRegion" class="form-label-modern">Région</label>
                                <input type="text" class="form-control-modern" id="editCountryRegion" name="region">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editCountryCurrency" class="form-label-modern">Devise</label>
                                <input type="text" class="form-control-modern" id="editCountryCurrency" name="currency">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editCountryCurrencySymbol" class="form-label-modern">Symbole devise</label>
                                <input type="text" class="form-control-modern" id="editCountryCurrencySymbol" name="currency_symbol" maxlength="10">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editCountryPhoneCode" class="form-label-modern">Code téléphonique</label>
                                <input type="text" class="form-control-modern" id="editCountryPhoneCode" name="phone_code">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editCountryOfficialLanguage" class="form-label-modern">Langue officielle</label>
                                <input type="text" class="form-control-modern" id="editCountryOfficialLanguage" name="official_language">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editCountryFlag" class="form-label-modern">Drapeau (URL)</label>
                                <input type="text" class="form-control-modern" id="editCountryFlag" name="flag">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editCountryLatitude" class="form-label-modern">Latitude</label>
                                <input type="text" class="form-control-modern" id="editCountryLatitude" name="latitude">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editCountryLongitude" class="form-label-modern">Longitude</label>
                                <input type="text" class="form-control-modern" id="editCountryLongitude" name="longitude">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editCountryTimezones" class="form-label-modern">Fuseaux horaires</label>
                            <select class="form-select-modern" id="editCountryTimezones" name="timezones[]" multiple>
                                <option value="UTC-12:00">UTC-12:00</option>
                                <option value="UTC-11:00">UTC-11:00</option>
                                <option value="UTC-10:00">UTC-10:00</option>
                                <option value="UTC-09:00">UTC-09:00</option>
                                <option value="UTC-08:00">UTC-08:00</option>
                                <option value="UTC-07:00">UTC-07:00</option>
                                <option value="UTC-06:00">UTC-06:00</option>
                                <option value="UTC-05:00">UTC-05:00</option>
                                <option value="UTC-04:00">UTC-04:00</option>
                                <option value="UTC-03:30">UTC-03:30</option>
                                <option value="UTC-03:00">UTC-03:00</option>
                                <option value="UTC-02:00">UTC-02:00</option>
                                <option value="UTC-01:00">UTC-01:00</option>
                                <option value="UTC±00:00">UTC±00:00</option>
                                <option value="UTC+01:00">UTC+01:00</option>
                                <option value="UTC+02:00">UTC+02:00</option>
                                <option value="UTC+03:00">UTC+03:00</option>
                                <option value="UTC+03:30">UTC+03:30</option>
                                <option value="UTC+04:00">UTC+04:00</option>
                                <option value="UTC+04:30">UTC+04:30</option>
                                <option value="UTC+05:00">UTC+05:00</option>
                                <option value="UTC+05:30">UTC+05:30</option>
                                <option value="UTC+05:45">UTC+05:45</option>
                                <option value="UTC+06:00">UTC+06:00</option>
                                <option value="UTC+06:30">UTC+06:30</option>
                                <option value="UTC+07:00">UTC+07:00</option>
                                <option value="UTC+08:00">UTC+08:00</option>
                                <option value="UTC+08:45">UTC+08:45</option>
                                <option value="UTC+09:00">UTC+09:00</option>
                                <option value="UTC+09:30">UTC+09:30</option>
                                <option value="UTC+10:00">UTC+10:00</option>
                                <option value="UTC+10:30">UTC+10:30</option>
                                <option value="UTC+11:00">UTC+11:00</option>
                                <option value="UTC+12:00">UTC+12:00</option>
                                <option value="UTC+12:45">UTC+12:45</option>
                                <option value="UTC+13:00">UTC+13:00</option>
                                <option value="UTC+14:00">UTC+14:00</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editCountryDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="editCountryDescription" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateCountryBtn">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce pays ? Toutes les provinces associées seront également supprimées.</p>
                    
                    <div class="country-to-delete" id="countryToDeleteInfo">
                        <!-- Country info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible et supprimera toutes les provinces de ce pays.
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

    <!-- CREATE ACTIVITY MODAL -->
<div class="modal fade" id="createActivityModal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern" id="createActivityModalLabel">
                    <i class="fas fa-hiking me-2"></i>Créer une nouvelle activité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <form id="createActivityForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="activityName" class="form-label-modern">Nom de l'activité *</label>
                            <input type="text" class="form-control-modern" id="activityName" name="name" 
                                   placeholder="Ex: Randonnée, Ski, Visite culturelle..." required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="activityCategory" class="form-label-modern">Catégorie *</label>
                            <select class="form-select-modern" id="activityCategory" name="category" required>
                                <option value="">Sélectionnez une catégorie</option>
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
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="activityDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="activityDescription" name="description" 
                                      rows="3" placeholder="Description détaillée de l'activité..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="activityDifficulty" class="form-label-modern">Difficulté</label>
                            <select class="form-select-modern" id="activityDifficulty" name="difficulty">
                                <option value="">Niveau de difficulté</option>
                                <option value="facile">Facile</option>
                                <option value="moyen">Moyen</option>
                                <option value="difficile">Difficile</option>
                                <option value="extreme">Extrême</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="activityDuration" class="form-label-modern">Durée moyenne (heures)</label>
                            <input type="number" class="form-control-modern" id="activityDuration" name="duration" 
                                   placeholder="Ex: 2, 4, 8..." min="0" step="0.5">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="activityImage" class="form-label-modern">Image de l'activité</label>
                            <input type="file" class="form-control-modern" id="activityImage" name="image" accept="image/*">
                            <div class="form-text-modern">Formats acceptés: JPG, PNG, GIF, SVG (Max: 2MB)</div>
                            <div class="image-preview mt-2" id="activityImagePreview" style="display: none;">
                                <img id="previewActivityImage" class="preview-image" style="max-width: 150px; max-height: 100px;">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sélection des localisations -->
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Localisations associées
                                <small class="text-muted ms-2">(Optionnel - vous pouvez ajouter plus tard)</small>
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Continents</label>
                                    <div class="location-select-container">
                                        @foreach($continents ?? [] as $continent)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
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
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Pays</label>
                                    <select class="form-select-modern" id="activityCountries" name="countries[]" multiple style="height: 150px;">
                                        <option value="">Sélectionnez les pays</option>
                                        @foreach($countries ?? [] as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text-modern">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-text-modern">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Vous pourrez ajouter des régions, provinces et villes après la création.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Métadonnées additionnelles -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="activitySeason" class="form-label-modern">Saison idéale</label>
                            <select class="form-select-modern" id="activitySeason" name="season" multiple>
                                <option value="printemps">Printemps</option>
                                <option value="ete">Été</option>
                                <option value="automne">Automne</option>
                                <option value="hiver">Hiver</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="activityPriceRange" class="form-label-modern">Fourchette de prix</label>
                            <select class="form-select-modern" id="activityPriceRange" name="price_range">
                                <option value="">Sélectionnez</option>
                                <option value="economique">Économique ($)</option>
                                <option value="moyen">Moyen ($$)</option>
                                <option value="luxe">Luxe ($$$)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="activityIsActive" class="form-label-modern">Statut</label>
                            <select class="form-select-modern" id="activityIsActive" name="is_active">
                                <option value="1" selected>Activé</option>
                                <option value="0">Désactivé</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="activityTags" class="form-label-modern">Tags (séparés par des virgules)</label>
                            <input type="text" class="form-control-modern" id="activityTags" name="tags" 
                                   placeholder="Ex: extérieur, famille, sportif, culturel...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="activityIcon" class="form-label-modern">Icône (FontAwesome)</label>
                            <input type="text" class="form-control-modern" id="activityIcon" name="icon" 
                                   placeholder="Ex: fas fa-hiking, fas fa-skiing, fas fa-monument">
                            <div class="form-text-modern">
                                <a href="https://fontawesome.com/icons" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-external-link-alt me-1"></i>Voir les icônes disponibles
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modal-footer-modern">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-info btn-pulse" id="submitActivityBtn">
                    <span class="btn-text">
                        <i class="fas fa-plus-circle me-2"></i>Créer l'activité
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Configuration
    let currentPage = 1;
    let currentFilters = {};
    let allCountries = [];
    let countryToDelete = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setupAjax();
        loadCountries();
        loadStatistics();
        setupEventListeners();
        setupImagePreview();
        setupActivityImagePreview();
        resetActivityForm();
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

    // Load countries
    const loadCountries = (page = 1, filters = {}) => {
        showLoading();
        
        const searchTerm = document.getElementById('searchInput')?.value || '';
        
        $.ajax({
            url: '{{ route("countries.index") }}',
            type: 'GET',
            data: {
                page: page,
                search: searchTerm,
                ...filters,
                ajax: true
            },
            success: function(response) {
                console.log('Countries response:', response);
                
                if (response.success) {
                    allCountries = response.data || [];
                    renderCountries(allCountries);
                    renderPagination(response);
                    hideLoading();
                } else {
                    showError('Erreur lors du chargement des pays');
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
            url: '{{ route("countries.statistics") }}',
            type: 'GET',
            success: function(response) {
                console.log('Statistics response:', response);
                
                if (response.success) {
                    const stats = response.data;
                    document.getElementById('totalCountries').textContent = stats.total_countries || 0;
                    document.getElementById('totalPopulation').textContent = formatNumber(stats.total_population || 0);
                    document.getElementById('totalArea').textContent = formatNumber(stats.total_area || 0);
                    
                    // Calculer le total des provinces si disponible
                    let totalProvinces = 0;
                    if (stats.by_continent) {
                        stats.by_continent.forEach(continent => {
                            // Pour simplifier, on peut estimer ou charger les provinces séparément
                        });
                    }
                    document.getElementById('totalProvinces').textContent = '...';
                    
                    // Optionnel: Mettre à jour les statistiques avancées
                    updateAdvancedStats(stats);
                } else {
                    console.error('Error loading statistics:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Statistics AJAX error:', xhr.responseText, status, error);
                
                // Valeurs par défaut
                document.getElementById('totalCountries').textContent = '0';
                document.getElementById('totalPopulation').textContent = '0';
                document.getElementById('totalArea').textContent = '0';
                document.getElementById('totalProvinces').textContent = '0';
            }
        });
    };

    // Setup image preview for country flags
    const setupImagePreview = () => {
        // Preview image for create form
        const flagInput = document.getElementById('countryFlag');
        const preview = document.getElementById('flagPreview');
        const previewImage = document.getElementById('previewFlagImage');
        
        if (flagInput) {
            flagInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Check file size (max 20MB)
                    if (file.size > 20 * 1024 * 1024) {
                        showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
                        this.value = '';
                        preview.style.display = 'none';
                        return;
                    }
                    
                    // Check file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                    if (!validTypes.includes(file.type)) {
                        showAlert('danger', 'Format d\'image non supporté. Utilisez JPG, PNG, GIF ou SVG.');
                        this.value = '';
                        preview.style.display = 'none';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });
        }
        
        // For edit form
        const editFlagInput = document.getElementById('editCountryFlag');
        const editPreview = document.getElementById('editFlagPreview');
        const editPreviewImage = document.getElementById('editPreviewFlagImage');
        
        if (editFlagInput) {
            editFlagInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Check file size (max 20MB)
                    if (file.size > 20 * 1024 * 1024) {
                        showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
                        this.value = '';
                        editPreview.style.display = 'none';
                        return;
                    }
                    
                    // Check file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                    if (!validTypes.includes(file.type)) {
                        showAlert('danger', 'Format d\'image non supporté. Utilisez JPG, PNG, GIF ou SVG.');
                        this.value = '';
                        editPreview.style.display = 'none';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        editPreviewImage.src = e.target.result;
                        editPreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    editPreview.style.display = 'none';
                }
            });
        }
    };

    // Setup image preview for activity
    const setupActivityImagePreview = () => {
        const imageInput = document.getElementById('activityImage');
        const preview = document.getElementById('activityImagePreview');
        const previewImage = document.getElementById('previewActivityImage');
        
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Check file size (max 20MB)
                    if (file.size > 20 * 1024 * 1024) {
                        showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
                        this.value = '';
                        preview.style.display = 'none';
                        return;
                    }
                    
                    // Check file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                    if (!validTypes.includes(file.type)) {
                        showAlert('danger', 'Format d\'image non supporté. Utilisez JPG, PNG, GIF ou SVG.');
                        this.value = '';
                        preview.style.display = 'none';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });
        }
    };

    // Show current image in edit modal
    const showCurrentImageInEdit = (country) => {
        const currentPreview = document.getElementById('currentFlagPreview');
        const currentImage = document.getElementById('currentFlagImage');
        
        if (country.image) {
            // Direct path to storage
            currentImage.src = `/storage/${country.image}`;
            currentPreview.style.display = 'block';
        } else {
            currentPreview.style.display = 'none';
        }
    };

    // Render countries with modern design
    const renderCountries = (countries) => {
        const tbody = document.getElementById('countriesTableBody');
        tbody.innerHTML = '';
        
        if (!countries || !Array.isArray(countries) || countries.length === 0) {
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            return;
        }
        
        countries.forEach((country, index) => {
            const row = document.createElement('tr');
            row.id = `country-row-${country.id}`;
            row.style.animationDelay = `${index * 0.05}s`;
            
            // Format population and area
            const population = country.population ? formatNumber(country.population) : 'N/A';
            const area = country.area ? formatNumber(country.area) : 'N/A';
            const isActive = country.is_active ? true : false;
            
            // Determine flag URL
            let flagUrl = '';
            if (country.image) {
                flagUrl = 'storage/' + country.image;
            }
            
            row.innerHTML = `
                <td class="country-name-cell">
                    <div class="country-name-modern">
                        <div class="country-flag-modern">
                            ${flagUrl ? `<img src="${flagUrl}" alt="${country.name}" class="flag-img" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\"fas fa-flag\"></i>';" style="width: 40px; height: 40px; object-fit: cover;">` : `<i class="fas fa-flag"></i>`}
                        </div>
                        <div>
                            <div class="country-name-text">${country.name}</div>
                            <small class="text-muted">${country.code}${country.iso2 ? ' | ' + country.iso2 : ''}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="continent-badge" style="background: ${getContinentColor(country.continent?.code)}">
                        ${country.continent?.name || 'N/A'}
                    </div>
                </td>
                <td>
                    <div>${country.capital || 'N/A'}</div>
                    <small class="text-muted">${country.currency ? country.currency + ' ' + (country.currency_symbol || '') : ''}</small>
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
                             onclick="toggleCountryStatus(${country.id}, ${isActive})">
                            <div class="toggle-slider"></div>
                        </div>
                        <span class="status-text ${isActive ? 'text-success' : 'text-danger'}">
                            ${isActive ? 'Actif' : 'Inactif'}
                        </span>
                    </div>
                </td>
                <td>
                    <div class="country-actions-modern">
                        <a href="{{ url('countrie') }}/${country.code}" target="_blank"
                           class="action-btn-modern view-btn-modern" title="Afficher la page">
                            <i class="fas fa-globe"></i>
                        </a>
                        <a href="{{ url('countries/activities') }}/${country.id}"
                           class="action-btn-modern view-btn-modern" title="Gérer la page">
                             <i class="fa-solid fa-cog"></i>
                        </a>
                        <a href="#" 
                           class="action-btn-modern view-btn-modern" title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                onclick="openEditModal(${country.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                onclick="showDeleteConfirmation(${country.id})">
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

    // Toggle country status
const toggleCountryStatus = (countryId, currentStatus) => {
    // Find the toggle element
    const toggleElement = document.querySelector(`#country-row-${countryId} .toggle-switch`);
    const statusText = document.querySelector(`#country-row-${countryId} .status-text`);
    
    if (!toggleElement || !statusText) return;
    
    // Get the actual current status from the DOM, not from the parameter
    const isCurrentlyActive = toggleElement.classList.contains('active');
    const newStatus = !isCurrentlyActive;
    
    // Disable toggle during request
    toggleElement.style.pointerEvents = 'none';
    toggleElement.classList.add('loading');
    
    console.log('Toggle country status - Début:', {
        countryId,
        currentStatus: isCurrentlyActive,
        newStatus,
        passedStatus: currentStatus
    });
    
    // Send AJAX request
    $.ajax({
        url: `/countries/${countryId}/toggle-status`,
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
                
                // Update the country in the array
                const countryIndex = allCountries.findIndex(c => c.id === countryId);
                if (countryIndex !== -1) {
                    allCountries[countryIndex].is_active = newStatus;
                }
                
                // Show success message
                showAlert('success', `Pays ${newStatus ? 'activé' : 'désactivé'} avec succès !`);
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
    // Get continent color
    const getContinentColor = (continentCode) => {
        const continentColors = {
            'AF': '#ff6b6b', // Afrique
            'AS': '#4ecdc4', // Asie
            'EU': '#45b7d1', // Europe
            'NA': '#96ceb4', // Amérique du Nord
            'SA': '#feca57', // Amérique du Sud
            'OC': '#ff9ff3', // Océanie
            'AN': '#c8d6e5'  // Antarctique
        };
        
        return continentColors[continentCode] || '#6c757d';
    };

    // Show delete confirmation modal
    const showDeleteConfirmation = (countryId) => {
        const country = allCountries.find(c => c.id === countryId);
        
        if (!country) {
            showAlert('danger', 'Pays non trouvé');
            return;
        }
        
        countryToDelete = country;
        
        // Determine flag URL for modal
        let flagUrl = '';
        if (country.flag) {
            if (country.flag.startsWith('http') || country.flag.startsWith('https')) {
                flagUrl = country.flag;
            } else if (country.flag.includes('storage')) {
                flagUrl = `/storage/${country.flag.replace('storage/', '')}`;
            } else if (country.flag.includes('flags/')) {
                flagUrl = `/storage/${country.flag}`;
            } else {
                flagUrl = country.flag;
            }
        }
        
        document.getElementById('countryToDeleteInfo').innerHTML = `
            <div class="country-info">
                <div class="country-info-flag">
                    ${flagUrl ? `<img src="${flagUrl}" alt="${country.name}" class="flag-img-large" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\"fas fa-flag fa-2x\"></i>';">` : `<i class="fas fa-flag fa-2x"></i>`}
                </div>
                <div>
                    <div class="country-info-name">${country.name}</div>
                    <div class="country-info-code">${country.code}${country.iso2 ? ' (' + country.iso2 + ')' : ''}</div>
                </div>
            </div>
            <div class="row small text-muted">
                <div class="col-6">
                    <div><strong>Continent:</strong> ${country.continent?.name || 'N/A'}</div>
                    <div><strong>Population:</strong> ${formatNumber(country.population)}</div>
                </div>
                <div class="col-6">
                    <div><strong>Superficie:</strong> ${formatNumber(country.area)} km²</div>
                    <div><strong>Capitale:</strong> ${country.capital || 'N/A'}</div>
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

    // Delete country
    const deleteCountry = () => {
        if (!countryToDelete) {
            showAlert('danger', 'Aucun pays à supprimer');
            return;
        }
        
        const countryId = countryToDelete.id;
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
        const row = document.getElementById(`country-row-${countryId}`);
        if (row) {
            row.classList.add('deleting-row');
        }
        
        // Send DELETE request
        $.ajax({
            url: `/countries/${countryId}`,
            type: 'DELETE',
            dataType: 'json',
            success: function(response) {
                // Hide modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                deleteModal.hide();
                
                if (response.success) {
                    // Remove country from array
                    allCountries = allCountries.filter(c => c.id !== countryId);
                    
                    // Update statistics
                    loadStatistics();
                    
                    // Show success message
                    showAlert('success', response.message || 'Pays supprimé avec succès !');
                    
                    // Remove row after animation
                    if (row) {
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if table is now empty
                            const tbody = document.getElementById('countriesTableBody');
                            if (tbody.children.length === 0) {
                                document.getElementById('emptyState').style.display = 'block';
                                document.getElementById('tableContainer').style.display = 'none';
                                document.getElementById('paginationContainer').style.display = 'none';
                            }
                        }, 300);
                    } else {
                        // Reload table
                        setTimeout(() => {
                            loadCountries(currentPage, currentFilters);
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
                const row = document.getElementById(`country-row-${countryId}`);
                if (row) {
                    row.classList.remove('deleting-row');
                }
                
                if (xhr.status === 404) {
                    showAlert('danger', 'Pays non trouvé.');
                    loadCountries(currentPage, currentFilters);
                } else {
                    showAlert('danger', 'Erreur lors de la suppression: ' + error);
                }
            },
            complete: function() {
                countryToDelete = null;
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
        paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} pays`;
        
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
        loadCountries(page, currentFilters);
    };

    // Store country
    const storeCountry = () => {
        const form = document.getElementById('createCountryForm');
        const submitBtn = document.getElementById('submitCountryBtn');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Check file size if present
        const fileInput = document.getElementById('countryFlag');
        if (fileInput.files[0] && fileInput.files[0].size > 20 * 1024 * 1024) {
            showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
            return;
        }
        
        // Show processing animation
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = `
            <span class="btn-text" style="display: none;">
                <i class="fas fa-save me-2"></i>Créer le pays
            </span>
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Création en cours...
        `;
        submitBtn.disabled = true;
        
        // Use FormData to handle file upload
        const formData = new FormData(form);
        
        // Add timezones
        const timezonesSelect = document.getElementById('countryTimezones');
        if (timezonesSelect) {
            const selectedTimezones = Array.from(timezonesSelect.selectedOptions)
                .map(option => option.value);
            selectedTimezones.forEach(timezone => {
                formData.append('timezones[]', timezone);
            });
        }
        
        $.ajax({
            url: '{{ route("countries.store") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le pays
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (response.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createCountryModal'));
                    modal.hide();
                    
                    // Reset form
                    form.reset();
                    document.getElementById('flagPreview').style.display = 'none';
                    
                    // Reload countries
                    loadCountries(1, currentFilters);
                    loadStatistics();
                    
                    // Show success message
                    showAlert('success', 'Pays créé avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr, status, error) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le pays
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

    // Update country
    const updateCountry = () => {
        const form = document.getElementById('editCountryForm');
        const submitBtn = document.getElementById('updateCountryBtn');
        const countryId = document.getElementById('editCountryId').value;
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Check file size if present
        const fileInput = document.getElementById('editCountryFlag');
        if (fileInput.files[0] && fileInput.files[0].size > 20 * 1024 * 1024) {
            showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
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
        
        // Use FormData to handle file upload
        const formData = new FormData(form);
        
        // Add timezones
        const timezonesSelect = document.getElementById('editCountryTimezones');
        if (timezonesSelect) {
            const selectedTimezones = Array.from(timezonesSelect.selectedOptions)
                .map(option => option.value);
            selectedTimezones.forEach(timezone => {
                formData.append('timezones[]', timezone);
            });
        }
        
        // Add the method override for PUT
        formData.append('_method', 'PUT');
        
        $.ajax({
            url: `/countries/${countryId}`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editCountryModal'));
                    modal.hide();
                    
                    // Reload countries
                    loadCountries(currentPage, currentFilters);
                    loadStatistics();
                    
                    // Show success message
                    showAlert('success', 'Pays mis à jour avec succès !');
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

    // Store activity
    const storeActivity = () => {
        const form = document.getElementById('createActivityForm');
        const submitBtn = document.getElementById('submitActivityBtn');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Check file size if present
        const fileInput = document.getElementById('activityImage');
        if (fileInput.files[0] && fileInput.files[0].size > 20 * 1024 * 1024) {
            showAlert('danger', 'L\'image ne doit pas dépasser 20MB');
            return;
        }
        
        // Show processing animation
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
        
        // Use FormData to handle file upload
        const formData = new FormData(form);
        
        // Add selected continents
        const continentCheckboxes = document.querySelectorAll('input[name="continents[]"]:checked');
        continentCheckboxes.forEach(checkbox => {
            formData.append('continents[]', checkbox.value);
        });
        
        // Add selected countries
        const countriesSelect = document.getElementById('activityCountries');
        if (countriesSelect) {
            const selectedCountries = Array.from(countriesSelect.selectedOptions)
                .map(option => option.value);
            selectedCountries.forEach(countryId => {
                formData.append('countries[]', countryId);
            });
        }
        
        // Add selected seasons
        const seasonsSelect = document.getElementById('activitySeason');
        if (seasonsSelect) {
            const selectedSeasons = Array.from(seasonsSelect.selectedOptions)
                .map(option => option.value);
            selectedSeasons.forEach(season => {
                formData.append('seasons[]', season);
            });
        }
        
        $.ajax({
            url: '{{ route("activities.store") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-plus-circle me-2"></i>Créer l'activité
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (response.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createActivityModal'));
                    modal.hide();
                    
                    // Reset form
                    form.reset();
                    document.getElementById('activityImagePreview').style.display = 'none';
                    
                    // Show success message
                    showAlert('success', 'Activité créée avec succès !');
                    
                    // Option: Reload activities if you have a list
                    // loadActivities();
                    
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr, status, error) {
                // Reset button state
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

    // Open edit modal
    const openEditModal = (countryId) => {
        const country = allCountries.find(c => c.id === countryId);
        
        if (country) {
            document.getElementById('editCountryId').value = country.id;
            document.getElementById('editCountryName').value = country.name;
            document.getElementById('editCountryCode').value = country.code;
            document.getElementById('editCountryIso2').value = country.iso2 || '';
            document.getElementById('editCountryContinent').value = country.continent_id || '';
            document.getElementById('editCountryCapital').value = country.capital || '';
            document.getElementById('editCountryPopulation').value = country.population || '';
            document.getElementById('editCountryArea').value = country.area || '';
            document.getElementById('editCountryRegion').value = country.region || '';
            document.getElementById('editCountryCurrency').value = country.currency || '';
            document.getElementById('editCountryCurrencySymbol').value = country.currency_symbol || '';
            document.getElementById('editCountryPhoneCode').value = country.phone_code || '';
            document.getElementById('editCountryOfficialLanguage').value = country.official_language || '';
            
            // Clear file input
            document.getElementById('editCountryFlag').value = '';
            
            document.getElementById('editCountryLatitude').value = country.latitude || '';
            document.getElementById('editCountryLongitude').value = country.longitude || '';
            document.getElementById('editCountryDescription').value = country.description || '';
            
            // Show current image
            showCurrentImageInEdit(country);
            
            // Hide new image preview
            document.getElementById('editFlagPreview').style.display = 'none';
            
            // Set timezones
            const timezonesSelect = document.getElementById('editCountryTimezones');
            if (timezonesSelect && country.timezones && Array.isArray(country.timezones)) {
                Array.from(timezonesSelect.options).forEach(option => {
                    option.selected = country.timezones.includes(option.value);
                });
            }
            
            new bootstrap.Modal(document.getElementById('editCountryModal')).show();
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
                    <div class="advanced-stat-title">Pays le plus peuplé</div>
                    <div class="advanced-stat-value">
                        ${stats.most_populous ? stats.most_populous.name : 'N/A'}
                    </div>
                    <div class="advanced-stat-subtext">
                        ${stats.most_populous ? formatNumber(stats.most_populous.population) + ' habitants' : ''}
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">Pays le plus grand</div>
                    <div class="advanced-stat-value">
                        ${stats.largest ? stats.largest.name : 'N/A'}
                    </div>
                    <div class="advanced-stat-subtext">
                        ${stats.largest ? formatNumber(stats.largest.area) + ' km²' : ''}
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">Pays le plus petit</div>
                    <div class="advanced-stat-value">
                        ${stats.smallest ? stats.smallest.name : 'N/A'}
                    </div>
                    <div class="advanced-stat-subtext">
                        ${stats.smallest ? formatNumber(stats.smallest.area) + ' km²' : ''}
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">Régions différentes</div>
                    <div class="advanced-stat-value">
                        ${stats.regions || 0}
                    </div>
                    <div class="advanced-stat-subtext">régions géographiques</div>
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
        
        // Insert after page header
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
        // Search input with debounce
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadCountries(1, currentFilters);
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
                    continent: document.getElementById('filterContinent').value,
                    region: document.getElementById('filterRegion').value,
                    sort_by: document.getElementById('filterSortBy').value,
                    sort_direction: document.getElementById('filterSortDirection').value
                };
                loadCountries(1, currentFilters);
            });
        }
        
        // Clear filters
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                document.getElementById('filterContinent').value = '';
                document.getElementById('filterRegion').value = '';
                document.getElementById('filterSortBy').value = 'name';
                document.getElementById('filterSortDirection').value = 'asc';
                currentFilters = {};
                loadCountries(1);
            });
        }
        
        // Submit country form
        const submitCountryBtn = document.getElementById('submitCountryBtn');
        if (submitCountryBtn) {
            submitCountryBtn.addEventListener('click', storeCountry);
        }
        
        // Update country form
        const updateCountryBtn = document.getElementById('updateCountryBtn');
        if (updateCountryBtn) {
            updateCountryBtn.addEventListener('click', updateCountry);
        }
        
        // Submit activity form
        const submitActivityBtn = document.getElementById('submitActivityBtn');
        if (submitActivityBtn) {
            submitActivityBtn.addEventListener('click', storeActivity);
        }
        
        // Confirm delete button
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', deleteCountry);
        }
        
        // Reset delete modal when hidden
        const deleteModal = document.getElementById('deleteConfirmationModal');
        if (deleteModal) {
            deleteModal.addEventListener('hidden.bs.modal', function() {
                countryToDelete = null;
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
        const createModal = document.getElementById('createCountryModal');
        if (createModal) {
            createModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createCountryForm').reset();
                document.getElementById('flagPreview').style.display = 'none';
                const submitBtn = document.getElementById('submitCountryBtn');
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le pays
                    </span>
                `;
                submitBtn.disabled = false;
            });
        }
        
        // Reset edit form when modal is hidden
        const editModal = document.getElementById('editCountryModal');
        if (editModal) {
            editModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('editCountryForm').reset();
                document.getElementById('currentFlagPreview').style.display = 'none';
                document.getElementById('editFlagPreview').style.display = 'none';
                const submitBtn = document.getElementById('updateCountryBtn');
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

    // Reset activity form when modal is hidden
    const resetActivityForm = () => {
        const activityModal = document.getElementById('createActivityModal');
        if (activityModal) {
            activityModal.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('createActivityForm');
                if (form) {
                    form.reset();
                    document.getElementById('activityImagePreview').style.display = 'none';
                    const submitBtn = document.getElementById('submitActivityBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-plus-circle me-2"></i>Créer l'activité
                        </span>
                    `;
                    submitBtn.disabled = false;
                }
            });
        }
    };

    // Additional utility functions for activities
    const loadActivities = () => {
        // Function to load activities list if you have one
        // Implement as needed
        console.log('Loading activities...');
    };

    const openEditActivityModal = (activityId) => {
        // Function to open edit activity modal
        // Implement as needed
        console.log('Opening edit activity modal for ID:', activityId);
    };

    const deleteActivity = (activityId) => {
        // Function to delete activity
        // Implement as needed
        console.log('Deleting activity with ID:', activityId);
    };
</script>
    <style>
        /* Styles spécifiques pour la page pays */
        .country-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .country-flag-modern {
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

        .country-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .continent-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .country-actions-modern {
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
/* Add these styles to your existing CSS */

.preview-image {
    border-radius: 8px;
    border: 2px solid #eaeaea;
    padding: 4px;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.current-image-preview {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
}

.image-preview {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
}

/* File input styling */
.form-control-modern[type="file"] {
    padding: 10px;
    background: white;
    border: 2px dashed #e0e0e0;
    transition: all 0.3s ease;
}

.form-control-modern[type="file"]:hover {
    border-color: var(--primary-color);
    background: #f8f9ff;
}

.form-control-modern[type="file"]::file-selector-button {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    margin-right: 10px;
    transition: background 0.3s ease;
}

.form-control-modern[type="file"]::file-selector-button:hover {
    background: var(--primary-dark);
}
    </style>
@endsection