@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-sliders-h"></i></span>
                Gestion des Sliders
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSliderModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Slider
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
            <!-- Filter Section - Remplacer la ligne des filtres par ceci -->
<div class="row">
    <div class="col-md-3">
        <label for="filterStatus" class="form-label-modern">Statut</label>
        <select class="form-select-modern" id="filterStatus">
            <option value="">Tous les statuts</option>
            <option value="active">Actif</option>
            <option value="inactive">Inactif</option>
            <option value="deleted">Supprimé</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterType" class="form-label-modern">Type</label>
        <select class="form-select-modern" id="filterType">
            <option value="">Tous les types</option>
            <option value="image">Image</option>
            <option value="video">Vidéo</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterCountry" class="form-label-modern">Pays</label>
        <select class="form-select-modern" id="filterCountry">
            <option value="">Tous les pays</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterProvince" class="form-label-modern">Province</label>
        <select class="form-select-modern" id="filterProvince" disabled>
            <option value="">Toutes les provinces</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterRegion" class="form-label-modern">Région</label>
        <select class="form-select-modern" id="filterRegion" disabled>
            <option value="">Toutes les régions</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterVille" class="form-label-modern">Ville</label>
        <select class="form-select-modern" id="filterVille" disabled>
            <option value="">Toutes les villes</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterDateFrom" class="form-label-modern">Date de début</label>
        <input type="date" class="form-control-modern" id="filterDateFrom">
    </div>
    <div class="col-md-3">
        <label for="filterDateTo" class="form-label-modern">Date de fin</label>
        <input type="date" class="form-control-modern" id="filterDateTo">
    </div>
</div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalSliders">0</div>
                        <div class="stats-label-modern">Total Sliders</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeSliders">0</div>
                        <div class="stats-label-modern">Sliders Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="imageSliders">0</div>
                        <div class="stats-label-modern">Sliders Images</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-image"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="videoSliders">0</div>
                        <div class="stats-label-modern">Sliders Vidéos</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-video"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Management Card -->
        <div class="main-card-modern mb-4">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Gestion de l'ordre des sliders</h3>
                <div class="card-actions">
                    <button class="btn btn-sm btn-outline-secondary" id="toggleOrderView">
                        <i class="fas fa-sort me-1"></i>Vue par ordre
                    </button>
                    <button class="btn btn-sm btn-success" id="saveOrderBtn" style="display: none;">
                        <i class="fas fa-save me-1"></i>Sauvegarder l'ordre
                    </button>
                </div>
            </div>
            
            <div class="card-body-modern">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Astuce :</strong> Glissez-déposez les sliders pour modifier leur ordre d'affichage. L'ordre est automatiquement sauvegardé.
                </div>
                
                <!-- Order View -->
                <div class="order-container" id="orderContainer" style="display: none;">
                    <div class="sortable-list" id="sortableList">
                        <!-- Sliders will be loaded here for sorting -->
                    </div>
                    <div class="order-actions mt-3">
                        <button class="btn btn-primary" id="saveOrderBtn2">
                            <i class="fas fa-save me-2"></i>Sauvegarder l'ordre
                        </button>
                        <button class="btn btn-secondary" id="cancelOrderBtn">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                    </div>
                </div>
                
                <!-- Table View -->
                <div id="tableView">
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
        <th style="width: 50px;">Ordre</th>
        <th>Slider</th>
        <th>Type</th>
        <th>Localisation</th>
        <th>Statut</th>
        <th>Créé le</th>
        <th style="text-align: center;">Actions</th>
    </tr>
</thead>
                            <tbody id="slidersTableBody">
                                <!-- Sliders will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Empty State -->
                    <div class="empty-state-modern" id="emptyState" style="display: none;">
                        <div class="empty-icon-modern">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <h3 class="empty-title-modern">Aucun slider trouvé</h3>
                        <p class="empty-text-modern">Commencez par créer votre premier slider.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSliderModal">
                            <i class="fas fa-plus-circle me-2"></i>Créer un slider
                        </button>
                    </div>
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createSliderModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE SLIDER MODAL -->
    <div class="modal fade" id="createSliderModal" tabindex="-1" aria-labelledby="createSliderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createSliderModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau slider
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createSliderForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="sliderName" class="form-label-modern">Nom du slider *</label>
                                <input type="text" class="form-control-modern" id="sliderName" name="name" placeholder="Ex: Bienvenue au Canada" required>
                                <div class="form-text-modern">Nom descriptif du slider</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="sliderDescription" class="form-label-modern">Description</label>
                                <textarea class="form-control-modern" id="sliderDescription" name="description" rows="2" placeholder="Description du slider..."></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="sliderType" class="form-label-modern">Type de contenu *</label>
                                <select class="form-select-modern" id="sliderType" name="type" required>
                                    <option value="image">Image</option>
                                    <option value="video">Vidéo</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="sliderOrder" class="form-label-modern">Ordre d'affichage</label>
                                <input type="number" class="form-control-modern" id="sliderOrder" name="order" min="1" value="1">
                                <div class="form-text-modern">Position dans le slider (1 = premier)</div>
                            </div>
                        </div>

                        

<!-- Localisation Section - À ajouter après le champ order -->
<div class="row">
    <div class="col-md-12 mb-4">
        <label class="form-label-modern">Recherche rapide de localisation</label>
        <input type="text" class="form-control-modern" id="locationSearchInput" 
               placeholder="Rechercher par pays, province, région ou ville...">
        <div class="form-text-modern">Commencez à taper pour rechercher une localisation</div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <label for="sliderCountry" class="form-label-modern">Pays</label>
        <select class="form-select-modern" id="sliderCountry" name="country_id">
            <option value="">Sélectionnez un pays...</option>
        </select>
    </div>
    
    <div class="col-md-3 mb-4">
        <label for="sliderProvince" class="form-label-modern">Province</label>
        <select class="form-select-modern" id="sliderProvince" name="province_id" disabled>
            <option value="">Sélectionnez d'abord un pays...</option>
        </select>
    </div>
    
    <div class="col-md-3 mb-4">
        <label for="sliderRegion" class="form-label-modern">Région</label>
        <select class="form-select-modern" id="sliderRegion" name="region_id" disabled>
            <option value="">Sélectionnez d'abord une province...</option>
        </select>
    </div>
    
    <div class="col-md-3 mb-4">
        <label for="sliderVille" class="form-label-modern">Ville</label>
        <select class="form-select-modern" id="sliderVille" name="ville_id" disabled>
            <option value="">Sélectionnez d'abord une région...</option>
        </select>
    </div>
</div>
                        
                        <!-- Image Upload Section -->
                        <div class="upload-section" id="imageUploadSection">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="sliderImage" class="form-label-modern">Image *</label>
                                    <input type="file" class="form-control-modern" id="sliderImage" name="image" accept="image/*" required>
                                    <div class="form-text-modern">Format: JPEG, PNG, GIF, WebP - Max: 5MB</div>
                                    <div class="image-preview mt-2" id="imagePreview" style="display: none;">
                                        <img id="previewImage" class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video Upload Section (hidden by default) -->
<div class="upload-section" id="videoUploadSection" style="display: none;">
    <div class="row">
        <div class="col-md-12 mb-4">
            <label class="form-label-modern">Source de la vidéo *</label>
            <div class="d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="video_source" id="videoSourceUrl" value="url" checked>
                    <label class="form-check-label" for="videoSourceUrl">
                        <i class="fas fa-link me-1"></i> URL (YouTube, Vimeo, Autre)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="video_source" id="videoSourceUpload" value="upload">
                    <label class="form-check-label" for="videoSourceUpload">
                        <i class="fas fa-upload me-1"></i> Upload local
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- YouTube/Vimeo/Autre URL Section -->
    <div class="row" id="videoUrlSection">
        <div class="col-md-12 mb-4">
            <label for="videoPlatform" class="form-label-modern">Plateforme vidéo *</label>
            <select class="form-select-modern" id="videoPlatform" name="video_platform">
                <option value="youtube">YouTube</option>
                <option value="vimeo">Vimeo</option>
                <option value="other">Autre URL</option>
            </select>
            <div class="form-text-modern">Sélectionnez la plateforme de votre vidéo</div>
        </div>
        
        <div class="col-md-12 mb-4">
            <label for="videoUrl" class="form-label-modern">URL de la vidéo *</label>
            <input type="url" class="form-control-modern" id="videoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=... ou https://vimeo.com/... ou autre URL">
            <div class="form-text-modern" id="videoUrlHelp">Collez l'URL complète de la vidéo</div>
            
            <!-- Aperçu de l'URL -->
            <div class="video-url-preview mt-2" id="videoUrlPreview" style="display: none;">
                <div class="alert alert-info">
                    <i class="fas fa-link me-2" id="videoPreviewIcon"></i>
                    <span id="videoUrlPreviewText"></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Video File Upload Section -->
    <div class="row" id="videoFileSection" style="display: none;">
        <div class="col-md-12 mb-4">
            <label for="videoFile" class="form-label-modern">Fichier vidéo *</label>
            <input type="file" class="form-control-modern" id="videoFile" name="video_file" accept="video/*">
            <div class="form-text-modern">Format: MP4, AVI, MOV, WMV - Max: 100MB</div>
            
            <!-- Aperçu du fichier vidéo -->
            <div class="video-file-preview mt-2" id="videoFilePreview" style="display: none;">
                <div class="alert alert-info">
                    <i class="fas fa-file-video me-2"></i>
                    <span id="videoFileName"></span>
                    <span id="videoFileSize" class="ms-2 text-muted"></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Video Thumbnail -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <label for="videoThumbnail" class="form-label-modern">Image de prévisualisation</label>
            <input type="file" class="form-control-modern" id="videoThumbnail" name="image" accept="image/*">
            <div class="form-text-modern">Image affichée avant la lecture de la vidéo (optionnel)</div>
            <div class="image-preview mt-2" id="videoThumbnailPreview" style="display: none;">
                <img id="previewVideoThumbnail" class="img-thumbnail" style="max-width: 300px;">
            </div>
        </div>
    </div>
</div>
                        
                        <!-- Button Section -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="buttonText" class="form-label-modern">Texte du bouton</label>
                                <input type="text" class="form-control-modern" id="buttonText" name="button_text" placeholder="Ex: Découvrir">
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="buttonUrl" class="form-label-modern">URL du bouton</label>
                                <input type="text" class="form-control-modern" id="buttonUrl" name="button_url" placeholder="https://...">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sliderIsActive" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="sliderIsActive">Slider actif</label>
                                </div>
                            </div>
                        </div><input type="hidden" id="videoType" name="video_type" value="youtube">
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitSliderBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le slider
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
<!-- EDIT SLIDER MODAL -->
<div class="modal fade" id="editSliderModal" tabindex="-1" aria-labelledby="editSliderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern" id="editSliderModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier le slider
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <form id="editSliderForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editSliderId" name="id">
                    
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label for="editSliderName" class="form-label-modern">Nom du slider *</label>
                            <input type="text" class="form-control-modern" id="editSliderName" name="name" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label for="editSliderDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="editSliderDescription" name="description" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="editSliderType" class="form-label-modern">Type de contenu *</label>
                            <select class="form-select-modern" id="editSliderType" name="type" required>
                                <option value="image">Image</option>
                                <option value="video">Vidéo</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="editSliderOrder" class="form-label-modern">Ordre d'affichage</label>
                            <input type="number" class="form-control-modern" id="editSliderOrder" name="order" min="1">
                        </div>
                    </div>

                    <!-- Localisation Section -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label-modern">Recherche rapide de localisation</label>
                            <input type="text" class="form-control-modern" id="editLocationSearchInput" 
                                   placeholder="Rechercher par pays, province, région ou ville...">
                            <div class="form-text-modern">Commencez à taper pour rechercher une localisation</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <label for="editSliderCountry" class="form-label-modern">Pays</label>
                            <select class="form-select-modern" id="editSliderCountry" name="country_id">
                                <option value="">Sélectionnez un pays...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-4">
                            <label for="editSliderProvince" class="form-label-modern">Province</label>
                            <select class="form-select-modern" id="editSliderProvince" name="province_id" disabled>
                                <option value="">Sélectionnez d'abord un pays...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-4">
                            <label for="editSliderRegion" class="form-label-modern">Région</label>
                            <select class="form-select-modern" id="editSliderRegion" name="region_id" disabled>
                                <option value="">Sélectionnez d'abord une province...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-4">
                            <label for="editSliderVille" class="form-label-modern">Ville</label>
                            <select class="form-select-modern" id="editSliderVille" name="ville_id" disabled>
                                <option value="">Sélectionnez d'abord une région...</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Current Image Preview -->
                    <div class="row" id="currentImageSection">
                        <div class="col-md-12 mb-4">
                            <label class="form-label-modern">Image actuelle</label>
                            <div id="currentImagePreview" class="mb-2"></div>
                            <div class="form-text-modern">Télécharger une nouvelle image pour remplacer l'actuelle</div>
                        </div>
                    </div>
                    
                    <!-- Image Upload Section -->
                    <div class="upload-section" id="editImageUploadSection">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="editSliderImage" class="form-label-modern">Nouvelle image</label>
                                <input type="file" class="form-control-modern" id="editSliderImage" name="image" accept="image/*">
                                <div class="form-text-modern">Laisser vide pour conserver l'image actuelle</div>
                                <div class="image-preview mt-2" id="editImagePreview" style="display: none;">
                                    <img id="previewEditImage" class="img-thumbnail" style="max-width: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Video Upload Section -->
                    <div class="upload-section" id="editVideoUploadSection" style="display: none;">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-label-modern">Source de la vidéo *</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_video_source" id="editVideoSourceUrl" value="url" checked>
                                        <label class="form-check-label" for="editVideoSourceUrl">
                                            <i class="fas fa-link me-1"></i> URL (YouTube, Vimeo, Autre)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_video_source" id="editVideoSourceUpload" value="upload">
                                        <label class="form-check-label" for="editVideoSourceUpload">
                                            <i class="fas fa-upload me-1"></i> Upload local
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Current Video Preview -->
                        <div class="row" id="editCurrentVideoSection">
                            <div class="col-md-12 mb-4">
                                <label class="form-label-modern">Vidéo actuelle</label>
                                <div id="currentVideoPreview" class="mb-2"></div>
                            </div>
                        </div>
                        
                        <!-- YouTube/Vimeo/Autre URL Section -->
                        <div class="row" id="editVideoUrlSection">
                            <div class="col-md-12 mb-4">
                                <label for="editVideoPlatform" class="form-label-modern">Plateforme vidéo *</label>
                                <select class="form-select-modern" id="editVideoPlatform" name="edit_video_platform">
                                    <option value="youtube">YouTube</option>
                                    <option value="vimeo">Vimeo</option>
                                    <option value="other">Autre URL</option>
                                </select>
                                <div class="form-text-modern">Sélectionnez la plateforme de votre vidéo</div>
                            </div>
                            
                            <div class="col-md-12 mb-4">
                                <label for="editVideoUrl" class="form-label-modern">URL de la vidéo</label>
                                <input type="url" class="form-control-modern" id="editVideoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                                <div class="form-text-modern" id="editVideoUrlHelp">Collez l'URL complète de la vidéo</div>
                                
                                <!-- Aperçu de l'URL -->
                                <div class="video-url-preview mt-2" id="editVideoUrlPreview" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-link me-2" id="editVideoPreviewIcon"></i>
                                        <span id="editVideoUrlPreviewText"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video File Upload Section -->
                        <div class="row" id="editVideoFileSection" style="display: none;">
                            <div class="col-md-12 mb-4">
                                <label for="editVideoFile" class="form-label-modern">Nouveau fichier vidéo</label>
                                <input type="file" class="form-control-modern" id="editVideoFile" name="video_file" accept="video/*">
                                <div class="form-text-modern">Format: MP4, AVI, MOV, WMV - Max: 100MB</div>
                                
                                <!-- Aperçu du fichier vidéo -->
                                <div class="video-file-preview mt-2" id="editVideoFilePreview" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-file-video me-2"></i>
                                        <span id="editVideoFileName"></span>
                                        <span id="editVideoFileSize" class="ms-2 text-muted"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video Thumbnail -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-label-modern">Image de prévisualisation actuelle</label>
                                <div id="currentThumbnailPreview" class="mb-2"></div>
                                <label for="editVideoThumbnail" class="form-label-modern">Nouvelle image de prévisualisation</label>
                                <input type="file" class="form-control-modern" id="editVideoThumbnail" name="image" accept="image/*">
                                <div class="form-text-modern">Laisser vide pour conserver l'image actuelle</div>
                                <div class="image-preview mt-2" id="editVideoThumbnailPreview" style="display: none;">
                                    <img id="previewEditVideoThumbnail" class="img-thumbnail" style="max-width: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Button Section -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="editButtonText" class="form-label-modern">Texte du bouton</label>
                            <input type="text" class="form-control-modern" id="editButtonText" name="button_text">
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="editButtonUrl" class="form-label-modern">URL du bouton</label>
                            <input type="text" class="form-control-modern" id="editButtonUrl" name="button_url">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editSliderIsActive" name="is_active" value="1">
                                <label class="form-check-label" for="editSliderIsActive">Slider actif</label>
                            </div>
                        </div>
                    </div>
<input type="hidden" id="editVideoType" name="video_type" value="">
                </form>
            </div>
            <div class="modal-footer modal-footer-modern">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateSliderBtn">
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
    
    <!-- PREVIEW MODAL -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="previewModalLabel">
                        <i class="fas fa-eye me-2"></i>Aperçu du slider
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <div id="previewContent">
                        <!-- Preview content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce slider ? Cette action est irréversible.</p>
                    
                    <div class="slider-to-delete" id="sliderToDeleteInfo">
                        <!-- Slider info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Tous les fichiers associés à ce slider seront également supprimés.
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
    
    <!-- RESTORE CONFIRMATION MODAL -->
    <div class="modal fade" id="restoreConfirmationModal" tabindex="-1" aria-labelledby="restoreConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="restore-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h4 class="restore-title">Restaurer le slider</h4>
                    <p class="restore-message">Voulez-vous restaurer ce slider ?</p>
                    
                    <div class="slider-to-restore" id="sliderToRestoreInfo">
                        <!-- Slider info will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-success" id="confirmRestoreBtn">
                        <span class="btn-text">
                            <i class="fas fa-undo me-2"></i>Restaurer
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Ajouter TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<!-- Ajouter TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include SortableJS for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
   <script>
    // Configuration
    let currentPage = 1;
    let currentFilters = {};
    let allSliders = [];
    let sliderToDelete = null;
    let sliderToRestore = null;
    let sortable = null;
    let originalOrder = [];

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setupAjax();
        loadSliders();
        loadStatistics();
        loadLocations();
        setupEventListeners();
        setupImagePreview();
        setupVideoTypeToggle();
        setupLocationSearch();
        setupHierarchicalSelects();
        setupVideoSourceToggle();
        setupVideoUrlPreview();
        setupVideoFilePreview();
        setupVideoPlatformToggle();
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

    // ==================== LOCALISATION (HIÉRARCHIQUE) ====================
    const loadLocations = () => {
        $.ajax({
            url: '/api/locations/countries',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    populateCountrySelects(response.data);
                }
            },
            error: function() {
                console.error('Erreur lors du chargement des pays');
            }
        });
    };

    const populateCountrySelects = (countries) => {
        const selects = ['sliderCountry', 'editSliderCountry', 'filterCountry'];
        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                select.innerHTML = '<option value="">Sélectionnez un pays...</option>';
                countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.id;
                    option.textContent = country.name;
                    select.appendChild(option);
                });
            }
        });
    };

    const setupHierarchicalSelects = () => {
        // Création modal
        const countrySelect = document.getElementById('sliderCountry');
        const provinceSelect = document.getElementById('sliderProvince');
        const regionSelect = document.getElementById('sliderRegion');
        const villeSelect = document.getElementById('sliderVille');

        if (countrySelect) {
            countrySelect.addEventListener('change', function() {
                const countryId = this.value;
                if (countryId) {
                    loadProvinces(countryId, provinceSelect);
                    resetSelect(provinceSelect);
                    resetSelect(regionSelect);
                    resetSelect(villeSelect);
                } else {
                    resetSelect(provinceSelect);
                    resetSelect(regionSelect);
                    resetSelect(villeSelect);
                }
            });
        }

        if (provinceSelect) {
            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                if (provinceId) {
                    loadRegions(provinceId, regionSelect);
                    resetSelect(regionSelect);
                    resetSelect(villeSelect);
                } else {
                    resetSelect(regionSelect);
                    resetSelect(villeSelect);
                }
            });
        }

        if (regionSelect) {
            regionSelect.addEventListener('change', function() {
                const regionId = this.value;
                if (regionId) {
                    loadVilles(regionId, villeSelect);
                    resetSelect(villeSelect);
                } else {
                    resetSelect(villeSelect);
                }
            });
        }

        // Édition modal
        const editCountrySelect = document.getElementById('editSliderCountry');
        const editProvinceSelect = document.getElementById('editSliderProvince');
        const editRegionSelect = document.getElementById('editSliderRegion');
        const editVilleSelect = document.getElementById('editSliderVille');

        if (editCountrySelect) {
            editCountrySelect.addEventListener('change', function() {
                const countryId = this.value;
                if (countryId) {
                    loadProvinces(countryId, editProvinceSelect);
                    resetSelect(editProvinceSelect);
                    resetSelect(editRegionSelect);
                    resetSelect(editVilleSelect);
                } else {
                    resetSelect(editProvinceSelect);
                    resetSelect(editRegionSelect);
                    resetSelect(editVilleSelect);
                }
            });
        }

        if (editProvinceSelect) {
            editProvinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                if (provinceId) {
                    loadRegions(provinceId, editRegionSelect);
                    resetSelect(editRegionSelect);
                    resetSelect(editVilleSelect);
                } else {
                    resetSelect(editRegionSelect);
                    resetSelect(editVilleSelect);
                }
            });
        }

        if (editRegionSelect) {
            editRegionSelect.addEventListener('change', function() {
                const regionId = this.value;
                if (regionId) {
                    loadVilles(regionId, editVilleSelect);
                    resetSelect(editVilleSelect);
                } else {
                    resetSelect(editVilleSelect);
                }
            });
        }

        // Filtres
        const filterCountry = document.getElementById('filterCountry');
        const filterProvince = document.getElementById('filterProvince');
        const filterRegion = document.getElementById('filterRegion');
        const filterVille = document.getElementById('filterVille');

        if (filterCountry) {
            filterCountry.addEventListener('change', function() {
                const countryId = this.value;
                if (countryId) {
                    loadProvincesForFilter(countryId, filterProvince);
                    resetSelect(filterProvince);
                    resetSelect(filterRegion);
                    resetSelect(filterVille);
                } else {
                    resetSelect(filterProvince);
                    resetSelect(filterRegion);
                    resetSelect(filterVille);
                }
            });
        }

        if (filterProvince) {
            filterProvince.addEventListener('change', function() {
                const provinceId = this.value;
                if (provinceId) {
                    loadRegionsForFilter(provinceId, filterRegion);
                    resetSelect(filterRegion);
                    resetSelect(filterVille);
                } else {
                    resetSelect(filterRegion);
                    resetSelect(filterVille);
                }
            });
        }

        if (filterRegion) {
            filterRegion.addEventListener('change', function() {
                const regionId = this.value;
                if (regionId) {
                    loadVillesForFilter(regionId, filterVille);
                    resetSelect(filterVille);
                } else {
                    resetSelect(filterVille);
                }
            });
        }
    };

    const loadProvinces = (countryId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/countries/${countryId}/provinces`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Sélectionnez une province...</option>';
                    response.data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadRegions = (provinceId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/provinces/${provinceId}/regions`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Sélectionnez une région...</option>';
                    response.data.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.id;
                        option.textContent = region.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadVilles = (regionId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/regions/${regionId}/villes`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Sélectionnez une ville...</option>';
                    response.data.forEach(ville => {
                        const option = document.createElement('option');
                        option.value = ville.id;
                        option.textContent = ville.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadProvincesForFilter = (countryId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/countries/${countryId}/provinces`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Toutes les provinces</option>';
                    response.data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadRegionsForFilter = (provinceId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/provinces/${provinceId}/regions`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Toutes les régions</option>';
                    response.data.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.id;
                        option.textContent = region.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadVillesForFilter = (regionId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/regions/${regionId}/villes`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Toutes les villes</option>';
                    response.data.forEach(ville => {
                        const option = document.createElement('option');
                        option.value = ville.id;
                        option.textContent = ville.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const resetSelect = (selectElement) => {
        if (selectElement) {
            selectElement.innerHTML = '<option value="">Sélectionnez d\'abord...</option>';
            selectElement.disabled = true;
        }
    };

    const setupLocationSearch = () => {
        const searchInput = document.getElementById('locationSearchInput');
        if (!searchInput) return;

        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const keyword = this.value;
            if (keyword.length < 2) {
                hideLocationResults();
                return;
            }
            timeout = setTimeout(() => searchLocation(keyword), 300);
        });
    };

    const searchLocation = (keyword) => {
        $.ajax({
            url: `/api/locations/search?q=${encodeURIComponent(keyword)}`,
            type: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displayLocationResults(response.data);
                } else {
                    hideLocationResults();
                }
            }
        });
    };

    const displayLocationResults = (results) => {
        let resultsContainer = document.getElementById('locationSearchResults');
        if (!resultsContainer) {
            resultsContainer = document.createElement('div');
            resultsContainer.id = 'locationSearchResults';
            resultsContainer.className = 'location-search-results';
            const searchInput = document.getElementById('locationSearchInput');
            if (searchInput && searchInput.parentNode) {
                searchInput.parentNode.style.position = 'relative';
                searchInput.parentNode.appendChild(resultsContainer);
            }
        }

        resultsContainer.innerHTML = '';
        resultsContainer.style.display = 'block';

        results.forEach(result => {
            const item = document.createElement('div');
            item.className = 'location-result-item';
            item.innerHTML = `
                <div class="result-type-badge ${result.type}">${result.type_label}</div>
                <div class="result-name">${escapeHtml(result.name)}</div>
                <div class="result-hierarchy">${escapeHtml(result.hierarchy)}</div>
            `;
            item.addEventListener('click', () => selectLocation(result));
            resultsContainer.appendChild(item);
        });
    };

    const hideLocationResults = () => {
        const container = document.getElementById('locationSearchResults');
        if (container) container.style.display = 'none';
    };

    const selectLocation = (location) => {
        const createModal = document.getElementById('createSliderModal');
        const editModal = document.getElementById('editSliderModal');
        const isEditModal = editModal?.classList.contains('show');

        if (isEditModal) {
            switch(location.type) {
                case 'country':
                    document.getElementById('editSliderCountry').value = location.id;
                    document.getElementById('editSliderCountry').dispatchEvent(new Event('change'));
                    break;
                case 'province':
                    document.getElementById('editSliderProvince').value = location.id;
                    document.getElementById('editSliderProvince').dispatchEvent(new Event('change'));
                    break;
                case 'region':
                    document.getElementById('editSliderRegion').value = location.id;
                    document.getElementById('editSliderRegion').dispatchEvent(new Event('change'));
                    break;
                case 'ville':
                    document.getElementById('editSliderVille').value = location.id;
                    break;
            }
        } else {
            switch(location.type) {
                case 'country':
                    document.getElementById('sliderCountry').value = location.id;
                    document.getElementById('sliderCountry').dispatchEvent(new Event('change'));
                    break;
                case 'province':
                    document.getElementById('sliderProvince').value = location.id;
                    document.getElementById('sliderProvince').dispatchEvent(new Event('change'));
                    break;
                case 'region':
                    document.getElementById('sliderRegion').value = location.id;
                    document.getElementById('sliderRegion').dispatchEvent(new Event('change'));
                    break;
                case 'ville':
                    document.getElementById('sliderVille').value = location.id;
                    break;
            }
        }

        const searchInput = document.getElementById('locationSearchInput');
        if (searchInput) searchInput.value = location.hierarchy;
        hideLocationResults();
    };

    // Load sliders
    const loadSliders = (page = 1, filters = {}) => {
        showLoading();
        
        const searchTerm = document.getElementById('searchInput')?.value || '';
        
        $.ajax({
            url: '{{ route("sliders.index") }}',
            type: 'GET',
            data: {
                page: page,
                search: searchTerm,
                ...filters,
                ajax: true
            },
            success: function(response) {
                if (response.success) {
                    allSliders = response.data || [];
                    renderSliders(allSliders);
                    renderPagination(response);
                    hideLoading();
                } else {
                    showError('Erreur lors du chargement des sliders');
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
            url: '{{ route("sliders.statistics") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const stats = response.data;
                    document.getElementById('totalSliders').textContent = stats.total;
                    document.getElementById('activeSliders').textContent = stats.active;
                    document.getElementById('imageSliders').textContent = stats.images;
                    document.getElementById('videoSliders').textContent = stats.videos;
                }
            }
        });
    };

    // Render sliders
    const renderSliders = (sliders) => {
        const tbody = document.getElementById('slidersTableBody');
        tbody.innerHTML = '';
        
        if (!sliders || !Array.isArray(sliders) || sliders.length === 0) {
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            return;
        }
        
        sliders.forEach((slider, index) => {
            const row = document.createElement('tr');
            row.id = `slider-row-${slider.id}`;
            row.style.animationDelay = `${index * 0.05}s`;
            
            const createdDate = new Date(slider.created_at);
            const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            let typeClass = 'type-image-modern';
            let typeIcon = 'fa-image';
            let typeText = 'Image';
            
            if (slider.type === 'video') {
                typeClass = 'type-video-modern';
                typeIcon = 'fa-video';
                typeText = 'Vidéo';
                
                if (slider.video_type === 'youtube') {
                    typeText = 'YouTube';
                    typeIcon = 'fa-youtube';
                } else if (slider.video_type === 'vimeo') {
                    typeText = 'Vimeo';
                    typeIcon = 'fa-vimeo';
                } else if (slider.video_type === 'upload') {
                    typeText = 'Upload';
                    typeIcon = 'fa-upload';
                } else if (slider.video_type === 'other') {
                    typeText = 'Autre';
                    typeIcon = 'fa-link';
                }
            }
            
            let statusClass = 'status-active-modern';
            let statusText = 'Actif';
            let statusIcon = 'fa-check-circle';
            
            if (!slider.is_active) {
                statusClass = 'status-inactive-modern';
                statusText = 'Inactif';
                statusIcon = 'fa-ban';
            }
            
            if (slider.deleted_at) {
                statusClass = 'status-deleted-modern';
                statusText = 'Supprimé';
                statusIcon = 'fa-trash';
            }
            
            let previewContent = '';
            let imageUrl = '';
            
            if (slider.type === 'image') {
                if (slider.image_path) {
                    if (slider.image_path.startsWith('http')) {
                        imageUrl = slider.image_path;
                    } else {
                        imageUrl = `/storage/${slider.image_path}`;
                    }
                    previewContent = `<img src="${imageUrl}" alt="${slider.name}" class="slider-thumbnail" onerror="this.onerror=null; this.src='https://via.placeholder.com/60x60?text=Image'; this.classList.add('placeholder-image');">`;
                } else {
                    previewContent = `<div class="slider-icon-placeholder"><i class="fas fa-image"></i></div>`;
                }
            } else if (slider.type === 'video') {
                if (slider.thumbnail_path) {
                    if (slider.thumbnail_path.startsWith('http')) {
                        imageUrl = slider.thumbnail_path;
                    } else {
                        imageUrl = `/storage/${slider.thumbnail_path}`;
                    }
                    previewContent = `<img src="${imageUrl}" alt="${slider.name}" class="slider-thumbnail" onerror="this.onerror=null; this.src='https://via.placeholder.com/60x60?text=Video'; this.classList.add('placeholder-image');">`;
                } else if (slider.image_path) {
                    if (slider.image_path.startsWith('http')) {
                        imageUrl = slider.image_path;
                    } else {
                        imageUrl = `/storage/${slider.image_path}`;
                    }
                    previewContent = `<img src="${imageUrl}" alt="${slider.name}" class="slider-thumbnail" onerror="this.onerror=null; this.src='https://via.placeholder.com/60x60?text=Video'; this.classList.add('placeholder-image');">`;
                } else {
                    previewContent = `<div class="slider-icon-placeholder video-placeholder"><i class="fas fa-video"></i></div>`;
                }
            }
            
            let fullLocation = slider.full_location || '';
            if (!fullLocation) {
                const parts = [];
                if (slider.country) parts.push(slider.country.name);
                if (slider.province) parts.push(slider.province.name);
                if (slider.region) parts.push(slider.region.name);
                if (slider.ville) parts.push(slider.ville.name);
                fullLocation = parts.join(' › ') || 'Non assigné';
            }
            
            row.innerHTML = `
                <td style="width: 50px;"><div class="order-badge" data-id="${slider.id}"><i class="fas fa-arrows-alt me-1"></i>${slider.order}</div></td>
                <td class="slider-name-cell"><div class="slider-name-modern"><div class="slider-icon-modern">${previewContent}</div><div><div class="slider-name-text">${escapeHtml(slider.name)}</div><small class="text-muted">ID: ${slider.id}</small></div></div></td>
                <td><span class="slider-type-modern ${typeClass}"><i class="fab ${typeIcon} me-1"></i>${typeText}</span></td>
                <td><span class="slider-region-modern"><i class="fas fa-map-marker-alt me-1"></i>${escapeHtml(fullLocation)}</span></td>
                <td><span class="slider-status-modern ${statusClass}"><i class="fas ${statusIcon} me-1"></i>${statusText}</span></td>
                <td><div>${formattedDate}</div><small class="text-muted">${formatTimeAgo(createdDate)}</small></td>
                <td style="text-align: center;"><div class="slider-actions-modern">
                    <button class="action-btn-modern preview-btn-modern" title="Aperçu" onclick="previewSlider(${slider.id})"><i class="fas fa-eye"></i></button>
                    ${slider.deleted_at ? 
                        `<button class="action-btn-modern restore-btn-modern" title="Restaurer" onclick="showRestoreConfirmation(${slider.id})"><i class="fas fa-undo"></i></button>` :
                        `<button class="action-btn-modern edit-btn-modern" title="Modifier" onclick="openEditModal(${slider.id})"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-modern status-btn-modern" title="Changer statut" onclick="toggleSliderStatus(${slider.id})"><i class="fas fa-power-off"></i></button>
                        <button class="action-btn-modern delete-btn-modern" title="Supprimer" onclick="showDeleteConfirmation(${slider.id})"><i class="fas fa-trash"></i></button>`
                    }
                </div></td>
            `;
            tbody.appendChild(row);
        });
        
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('paginationContainer').style.display = 'flex';
    };

    const escapeHtml = (text) => {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    const setupSortable = (sliders) => {
        const sortableList = document.getElementById('sortableList');
        sortableList.innerHTML = '';
        
        const sortedSliders = [...sliders].sort((a, b) => a.order - b.order);
        originalOrder = sortedSliders.map(s => ({id: s.id, order: s.order}));
        
        sortedSliders.forEach(slider => {
            const item = document.createElement('div');
            item.className = 'sortable-item';
            item.dataset.id = slider.id;
            
            let typeIcon = 'fa-image';
            let typeText = 'Image';
            
            if (slider.type === 'video') {
                typeIcon = 'fa-video';
                typeText = 'Vidéo';
                if (slider.video_type === 'youtube') { typeIcon = 'fa-youtube'; typeText = 'YouTube'; }
                else if (slider.video_type === 'vimeo') { typeIcon = 'fa-vimeo'; typeText = 'Vimeo'; }
            }
            
            const imageUrl = slider.image_path ? `/storage/${slider.image_path}` : 'https://via.placeholder.com/60';
            
            item.innerHTML = `
                <div class="sortable-item-content">
                    <div class="sortable-handle"><i class="fas fa-arrows-alt"></i></div>
                    <div class="sortable-image"><img src="${imageUrl}" alt="${slider.name}"></div>
                    <div class="sortable-info">
                        <div class="sortable-name">${slider.name}</div>
                        <div class="sortable-details">
                            <span class="badge bg-secondary me-2"><i class="fas ${typeIcon} me-1"></i>${typeText}</span>
                            <span class="badge ${slider.is_active ? 'bg-success' : 'bg-secondary'}"><i class="fas ${slider.is_active ? 'fa-check' : 'fa-ban'} me-1"></i>${slider.is_active ? 'Actif' : 'Inactif'}</span>
                        </div>
                    </div>
                    <div class="sortable-order"><span class="order-badge">${slider.order}</span></div>
                </div>
            `;
            sortableList.appendChild(item);
        });
        
        if (sortable) sortable.destroy();
        sortable = new Sortable(sortableList, {
            animation: 150,
            handle: '.sortable-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: () => updateOrderNumbers()
        });
    };

    const updateOrderNumbers = () => {
        document.querySelectorAll('.sortable-item').forEach((item, index) => {
            item.querySelector('.order-badge').textContent = index + 1;
        });
    };

    const saveOrder = () => {
        const items = document.querySelectorAll('.sortable-item');
        const slidersData = [];
        items.forEach((item, index) => slidersData.push({ id: parseInt(item.dataset.id), order: index + 1 }));
        
        const saveBtn = document.getElementById('saveOrderBtn');
        const saveBtn2 = document.getElementById('saveOrderBtn2');
        const originalText = saveBtn.innerHTML;
        
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
        saveBtn2.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
        saveBtn.disabled = true;
        saveBtn2.disabled = true;
        
        $.ajax({
            url: '{{ route("sliders.update-order") }}',
            type: 'POST',
            data: { sliders: slidersData },
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Ordre sauvegardé avec succès !');
                    loadSliders(currentPage, currentFilters);
                    loadStatistics();
                    toggleOrderView();
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la sauvegarde');
                }
            },
            error: () => showAlert('danger', 'Erreur lors de la sauvegarde'),
            complete: () => {
                saveBtn.innerHTML = originalText;
                saveBtn2.innerHTML = originalText;
                saveBtn.disabled = false;
                saveBtn2.disabled = false;
            }
        });
    };

    const toggleOrderView = () => {
        const tableView = document.getElementById('tableView');
        const orderContainer = document.getElementById('orderContainer');
        const toggleBtn = document.getElementById('toggleOrderView');
        const saveBtn = document.getElementById('saveOrderBtn');
        
        if (tableView.style.display === 'none') {
            tableView.style.display = 'block';
            orderContainer.style.display = 'none';
            saveBtn.style.display = 'none';
            toggleBtn.innerHTML = '<i class="fas fa-sort me-1"></i>Vue par ordre';
        } else {
            if (allSliders.length === 0) {
                showAlert('info', 'Aucun slider à réorganiser');
                return;
            }
            tableView.style.display = 'none';
            orderContainer.style.display = 'block';
            saveBtn.style.display = 'inline-block';
            toggleBtn.innerHTML = '<i class="fas fa-list me-1"></i>Vue tableau';
            setupSortable(allSliders);
        }
    };

    const cancelOrder = () => {
        document.getElementById('tableView').style.display = 'block';
        document.getElementById('orderContainer').style.display = 'none';
        document.getElementById('saveOrderBtn').style.display = 'none';
        document.getElementById('toggleOrderView').innerHTML = '<i class="fas fa-sort me-1"></i>Vue par ordre';
        setupSortable(allSliders);
    };

    const previewSlider = (sliderId) => {
        $.ajax({
            url: `/sliders/${sliderId}/preview`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const slider = response.data;
                    const previewContent = document.getElementById('previewContent');
                    let content = '';
                    
                    if (slider.type === 'image') {
                        content = `
                            <div class="slider-preview text-center">
                                <h5>${escapeHtml(slider.name)}</h5>
                                <img src="${slider.image_url}" class="img-fluid rounded mb-3" style="max-height: 400px;">
                                ${slider.description ? `<p>${escapeHtml(slider.description)}</p>` : ''}
                                ${slider.button_text && slider.button_url ? `<a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>` : ''}
                            </div>`;
                    } else if (slider.type === 'video') {
                        if (slider.is_youtube && slider.youtube_id) {
                            content = `
                                <div class="slider-preview text-center">
                                    <h5>${escapeHtml(slider.name)}</h5>
                                    <div class="ratio ratio-16x9 mb-3">
                                        <iframe src="https://www.youtube.com/embed/${slider.youtube_id}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    ${slider.description ? `<p>${escapeHtml(slider.description)}</p>` : ''}
                                    ${slider.button_text && slider.button_url ? `<a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>` : ''}
                                </div>`;
                        } else if (slider.is_vimeo && slider.video_url) {
                            content = `
                                <div class="slider-preview text-center">
                                    <h5>${escapeHtml(slider.name)}</h5>
                                    <div class="ratio ratio-16x9 mb-3">
                                        <iframe src="${slider.video_url.replace('vimeo.com', 'player.vimeo.com/video')}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    ${slider.description ? `<p>${escapeHtml(slider.description)}</p>` : ''}
                                    ${slider.button_text && slider.button_url ? `<a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>` : ''}
                                </div>`;
                        } else if (slider.video_url) {
                            content = `
                                <div class="slider-preview text-center">
                                    <h5>${escapeHtml(slider.name)}</h5>
                                    <video controls style="width:100%; max-height:400px;" poster="${slider.thumbnail_url}">
                                        <source src="${slider.video_url}" type="video/mp4">
                                    </video>
                                    ${slider.description ? `<p>${escapeHtml(slider.description)}</p>` : ''}
                                    ${slider.button_text && slider.button_url ? `<a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>` : ''}
                                </div>`;
                        } else {
                            content = `<div class="slider-preview text-center"><h5>${escapeHtml(slider.name)}</h5><div class="preview-placeholder"><i class="fas fa-video fa-4x mb-3"></i><p>Vidéo non disponible</p></div></div>`;
                        }
                    }
                    previewContent.innerHTML = content;
                    new bootstrap.Modal(document.getElementById('previewModal')).show();
                }
            },
            error: () => showAlert('danger', 'Erreur lors du chargement de l\'aperçu')
        });
    };

    const showDeleteConfirmation = (sliderId) => {
        const slider = allSliders.find(s => s.id === sliderId);
        if (!slider) { showAlert('danger', 'Slider non trouvé'); return; }
        sliderToDelete = slider;
        
        const createdDate = new Date(slider.created_at);
        const formattedDate = createdDate.toLocaleDateString('fr-FR');
        
        document.getElementById('sliderToDeleteInfo').innerHTML = `
            <div class="slider-info"><div class="slider-info-icon"><i class="fas fa-sliders-h"></i></div>
            <div><div class="slider-info-name">${slider.name}</div><div class="slider-info-type"><span class="badge bg-secondary">${slider.type === 'image' ? 'Image' : 'Vidéo'}</span></div></div></div>
            <div class="row small text-muted"><div class="col-6"><strong>ID:</strong> ${slider.id}</div><div class="col-6"><strong>Ordre:</strong> ${slider.order}</div>
            <div class="col-6"><strong>Créé le:</strong> ${formattedDate}</div><div class="col-6"><strong>Statut:</strong> ${slider.is_active ? 'Actif' : 'Inactif'}</div></div>`;
        
        document.getElementById('confirmDeleteBtn').innerHTML = `<span class="btn-text"><i class="fas fa-trash me-2"></i>Supprimer définitivement</span>`;
        document.getElementById('confirmDeleteBtn').disabled = false;
        new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
    };

    const showRestoreConfirmation = (sliderId) => {
        const slider = allSliders.find(s => s.id === sliderId);
        if (!slider) { showAlert('danger', 'Slider non trouvé'); return; }
        sliderToRestore = slider;
        document.getElementById('sliderToRestoreInfo').innerHTML = `<div class="slider-info"><div class="slider-info-icon"><i class="fas fa-sliders-h"></i></div><div><div class="slider-info-name">${slider.name}</div></div></div>`;
        new bootstrap.Modal(document.getElementById('restoreConfirmationModal')).show();
    };

    const deleteSlider = () => {
        if (!sliderToDelete) return;
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.innerHTML = '<div class="spinner-border spinner-border-sm text-light me-2"></div>Suppression...';
        deleteBtn.disabled = true;
        
        $.ajax({
            url: `/sliders/${sliderToDelete.id}`,
            type: 'DELETE',
            success: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal')).hide();
                if (response.success) {
                    loadStatistics();
                    loadSliders(currentPage, currentFilters);
                    showAlert('success', response.message);
                }
            },
            error: () => showAlert('danger', 'Erreur lors de la suppression'),
            complete: () => { sliderToDelete = null; }
        });
    };

    const restoreSlider = () => {
        if (!sliderToRestore) return;
        const restoreBtn = document.getElementById('confirmRestoreBtn');
        restoreBtn.innerHTML = '<div class="spinner-border spinner-border-sm text-light me-2"></div>Restauration...';
        restoreBtn.disabled = true;
        
        $.ajax({
            url: `/sliders/${sliderToRestore.id}/restore`,
            type: 'POST',
            success: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('restoreConfirmationModal')).hide();
                if (response.success) {
                    loadStatistics();
                    loadSliders(currentPage, currentFilters);
                    showAlert('success', response.message);
                }
            },
            error: () => showAlert('danger', 'Erreur lors de la restauration'),
            complete: () => { sliderToRestore = null; }
        });
    };

    const toggleSliderStatus = (sliderId) => {
        $.ajax({
            url: `/sliders/${sliderId}/toggle-status`,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    loadSliders(currentPage, currentFilters);
                    loadStatistics();
                    showAlert('success', response.message);
                }
            },
            error: () => showAlert('danger', 'Erreur lors du changement de statut')
        });
    };

    const storeSlider = () => {
        const form = document.getElementById('createSliderForm');
        const submitBtn = document.getElementById('submitSliderBtn');
        
        if (!form.checkValidity()) { form.reportValidity(); return; }
        
        const type = document.getElementById('sliderType').value;
        const videoSourceUrl = document.getElementById('videoSourceUrl');
        const videoSourceUpload = document.getElementById('videoSourceUpload');
        const videoPlatform = document.getElementById('videoPlatform');
        let videoSource = '';
        let videoUrl = '';
        
        if (type === 'video') {
            if (videoSourceUrl && videoSourceUrl.checked) {
                videoSource = 'url';
                videoUrl = document.getElementById('videoUrl').value;
                if (!videoUrl) { showAlert('danger', 'Veuillez entrer l\'URL de la vidéo'); return; }
                if (videoPlatform && !videoPlatform.value) { showAlert('danger', 'Veuillez sélectionner la plateforme vidéo'); return; }
            } else if (videoSourceUpload && videoSourceUpload.checked) {
                videoSource = 'upload';
                const videoFile = document.getElementById('videoFile').files[0];
                if (!videoFile) { showAlert('danger', 'Veuillez sélectionner un fichier vidéo'); return; }
            } else {
                showAlert('danger', 'Veuillez choisir une source de vidéo');
                return;
            }
        }
        
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm text-light me-2"></div>Création en cours...';
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        if (type === 'video') {
            formData.append('video_source', videoSource);
            if (videoSource === 'url') {
                formData.append('video_url', videoUrl);
                if (videoPlatform) formData.append('video_platform', videoPlatform.value);
            }
        }
        
        $.ajax({
            url: '{{ route("sliders.store") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer le slider';
                submitBtn.disabled = false;
                
                if (response.success) {
                    bootstrap.Modal.getInstance(document.getElementById('createSliderModal')).hide();
                    form.reset();
                    document.getElementById('imagePreview').style.display = 'none';
                    document.getElementById('videoThumbnailPreview').style.display = 'none';
                    resetSelect(document.getElementById('sliderProvince'));
                    resetSelect(document.getElementById('sliderRegion'));
                    resetSelect(document.getElementById('sliderVille'));
                    if (videoSourceUrl) videoSourceUrl.checked = true;
                    document.getElementById('videoUrlSection').style.display = 'block';
                    document.getElementById('videoFileSection').style.display = 'none';
                    document.getElementById('videoUrl').value = '';
                    document.getElementById('videoFile').value = '';
                    loadStatistics();
                    loadSliders(1, currentFilters);
                    showAlert('success', 'Slider créé avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr) {
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer le slider';
                submitBtn.disabled = false;
                if (xhr.status === 422) {
                    let msg = 'Veuillez corriger les erreurs:<br>';
                    for (let field in xhr.responseJSON.errors) msg += `- ${xhr.responseJSON.errors[field].join('<br>')}<br>`;
                    showAlert('danger', msg);
                } else {
                    showAlert('danger', 'Erreur lors de la création');
                }
            }
        });
    };

    const updateSlider = () => {
        const form = document.getElementById('editSliderForm');
        const submitBtn = document.getElementById('updateSliderBtn');
        const sliderId = document.getElementById('editSliderId').value;
        
        if (!form.checkValidity()) { form.reportValidity(); return; }
        
        const type = document.getElementById('editSliderType').value;
        const editVideoSourceUrl = document.getElementById('editVideoSourceUrl');
        const editVideoSourceUpload = document.getElementById('editVideoSourceUpload');
        const editVideoPlatform = document.getElementById('editVideoPlatform');
        let videoSource = '';
        
        if (type === 'video') {
            if (editVideoSourceUrl && editVideoSourceUrl.checked) {
                videoSource = 'url';
                const videoUrl = document.getElementById('editVideoUrl').value;
                if (!videoUrl && !document.getElementById('currentVideoPreview').innerHTML.includes('URL')) {
                    showAlert('danger', 'Veuillez entrer l\'URL de la vidéo');
                    return;
                }
            } else if (editVideoSourceUpload && editVideoSourceUpload.checked) {
                videoSource = 'upload';
            }
        }
        
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm text-light me-2"></div>Enregistrement...';
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        if (type === 'video' && videoSource) {
            formData.append('edit_video_source', videoSource);
            if (videoSource === 'url' && editVideoPlatform) {
                formData.append('edit_video_platform', editVideoPlatform.value);
            }
        }
        
        $.ajax({
            url: `/sliders/${sliderId}`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer les modifications';
                submitBtn.disabled = false;
                
                if (response.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editSliderModal')).hide();
                    loadSliders(currentPage, currentFilters);
                    showAlert('success', 'Slider mis à jour avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la mise à jour');
                }
            },
            error: function(xhr) {
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer les modifications';
                submitBtn.disabled = false;
                if (xhr.status === 422) {
                    let msg = 'Veuillez corriger les erreurs:<br>';
                    for (let field in xhr.responseJSON.errors) msg += `- ${xhr.responseJSON.errors[field].join('<br>')}<br>`;
                    showAlert('danger', msg);
                } else {
                    showAlert('danger', 'Erreur lors de la mise à jour');
                }
            }
        });
    };

    const setupImagePreview = () => {
        const createImageInput = document.getElementById('sliderImage');
        if (createImageInput) {
            createImageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('previewImage').src = e.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        const videoThumbnailInput = document.getElementById('videoThumbnail');
        if (videoThumbnailInput) {
            videoThumbnailInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('previewVideoThumbnail').src = e.target.result;
                        document.getElementById('videoThumbnailPreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        const editImageInput = document.getElementById('editSliderImage');
        if (editImageInput) {
            editImageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('previewEditImage').src = e.target.result;
                        document.getElementById('editImagePreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    };

    const setupVideoTypeToggle = () => {
        const sliderType = document.getElementById('sliderType');
        if (sliderType) {
            sliderType.addEventListener('change', function() {
                if (this.value === 'image') {
                    document.getElementById('imageUploadSection').style.display = 'block';
                    document.getElementById('videoUploadSection').style.display = 'none';
                    document.getElementById('sliderImage').required = true;
                } else {
                    document.getElementById('imageUploadSection').style.display = 'none';
                    document.getElementById('videoUploadSection').style.display = 'block';
                    document.getElementById('sliderImage').required = false;
                }
            });
        }
        
        const editSliderType = document.getElementById('editSliderType');
        if (editSliderType) {
            editSliderType.addEventListener('change', function() {
                if (this.value === 'image') {
                    document.getElementById('editImageUploadSection').style.display = 'block';
                    document.getElementById('editVideoUploadSection').style.display = 'none';
                    document.getElementById('currentImageSection').style.display = 'block';
                    document.getElementById('currentVideoSection').style.display = 'none';
                } else {
                    document.getElementById('editImageUploadSection').style.display = 'none';
                    document.getElementById('editVideoUploadSection').style.display = 'block';
                    document.getElementById('currentImageSection').style.display = 'none';
                    document.getElementById('currentVideoSection').style.display = 'block';
                }
            });
        }
    };

    const toggleEditSections = (type) => {
        if (type === 'image') {
            document.getElementById('editImageUploadSection').style.display = 'block';
            document.getElementById('editVideoUploadSection').style.display = 'none';
            document.getElementById('currentImageSection').style.display = 'block';
            document.getElementById('currentVideoSection').style.display = 'none';
        } else {
            document.getElementById('editImageUploadSection').style.display = 'none';
            document.getElementById('editVideoUploadSection').style.display = 'block';
            document.getElementById('currentImageSection').style.display = 'none';
            document.getElementById('currentVideoSection').style.display = 'block';
        }
    };

    const setupVideoSourceToggle = () => {
        const videoSourceUrl = document.getElementById('videoSourceUrl');
        const videoSourceUpload = document.getElementById('videoSourceUpload');
        const videoUrlSection = document.getElementById('videoUrlSection');
        const videoFileSection = document.getElementById('videoFileSection');
        const videoUrlInput = document.getElementById('videoUrl');
        const videoFileInput = document.getElementById('videoFile');
        
        if (videoSourceUrl && videoSourceUpload) {
            const toggle = () => {
                if (videoSourceUrl.checked) {
                    videoUrlSection.style.display = 'block';
                    videoFileSection.style.display = 'none';
                    if (videoUrlInput) videoUrlInput.required = true;
                    if (videoFileInput) videoFileInput.required = false;
                    if (videoFileInput) videoFileInput.value = '';
                } else {
                    videoUrlSection.style.display = 'none';
                    videoFileSection.style.display = 'block';
                    if (videoUrlInput) videoUrlInput.required = false;
                    if (videoFileInput) videoFileInput.required = true;
                    if (videoUrlInput) videoUrlInput.value = '';
                }
            };
            videoSourceUrl.addEventListener('change', toggle);
            videoSourceUpload.addEventListener('change', toggle);
        }
        
        const editVideoSourceUrl = document.getElementById('editVideoSourceUrl');
        const editVideoSourceUpload = document.getElementById('editVideoSourceUpload');
        const editVideoUrlSection = document.getElementById('editVideoUrlSection');
        const editVideoFileSection = document.getElementById('editVideoFileSection');
        
        if (editVideoSourceUrl && editVideoSourceUpload) {
            const toggleEdit = () => {
                if (editVideoSourceUrl.checked) {
                    editVideoUrlSection.style.display = 'block';
                    editVideoFileSection.style.display = 'none';
                } else {
                    editVideoUrlSection.style.display = 'none';
                    editVideoFileSection.style.display = 'block';
                }
            };
            editVideoSourceUrl.addEventListener('change', toggleEdit);
            editVideoSourceUpload.addEventListener('change', toggleEdit);
        }
    };

    const setupVideoPlatformToggle = () => {
        const videoPlatform = document.getElementById('videoPlatform');
        const videoUrlHelp = document.getElementById('videoUrlHelp');
        const videoUrlInput = document.getElementById('videoUrl');
        
        if (videoPlatform) {
            videoPlatform.addEventListener('change', function() {
                const platform = this.value;
                let placeholder = '', helpText = '';
                
                switch(platform) {
                    case 'youtube':
                        placeholder = 'https://www.youtube.com/watch?v=xxxxxxxxxxx';
                        helpText = 'Collez l\'URL complète YouTube (ex: https://www.youtube.com/watch?v=dQw4w9WgXcQ)';
                        break;
                    case 'vimeo':
                        placeholder = 'https://vimeo.com/xxxxxxxxx';
                        helpText = 'Collez l\'URL complète Vimeo (ex: https://vimeo.com/123456789)';
                        break;
                    case 'other':
                        placeholder = 'https://...';
                        helpText = 'Collez l\'URL complète de votre vidéo';
                        break;
                }
                if (videoUrlInput) videoUrlInput.placeholder = placeholder;
                if (videoUrlHelp) videoUrlHelp.innerHTML = helpText;
            });
        }
        
        const editVideoPlatform = document.getElementById('editVideoPlatform');
        const editVideoUrlHelp = document.getElementById('editVideoUrlHelp');
        const editVideoUrlInput = document.getElementById('editVideoUrl');
        
        if (editVideoPlatform) {
            editVideoPlatform.addEventListener('change', function() {
                const platform = this.value;
                let placeholder = '', helpText = '';
                
                switch(platform) {
                    case 'youtube':
                        placeholder = 'https://www.youtube.com/watch?v=xxxxxxxxxxx';
                        helpText = 'Collez l\'URL complète YouTube (ex: https://www.youtube.com/watch?v=dQw4w9WgXcQ)';
                        break;
                    case 'vimeo':
                        placeholder = 'https://vimeo.com/xxxxxxxxx';
                        helpText = 'Collez l\'URL complète Vimeo (ex: https://vimeo.com/123456789)';
                        break;
                    case 'other':
                        placeholder = 'https://...';
                        helpText = 'Collez l\'URL complète de votre vidéo';
                        break;
                }
                if (editVideoUrlInput) editVideoUrlInput.placeholder = placeholder;
                if (editVideoUrlHelp) editVideoUrlHelp.innerHTML = helpText;
            });
        }
    };

    const setupVideoUrlPreview = () => {
        const videoUrlInput = document.getElementById('videoUrl');
        const videoUrlPreview = document.getElementById('videoUrlPreview');
        const videoUrlPreviewText = document.getElementById('videoUrlPreviewText');
        
        if (videoUrlInput) {
            videoUrlInput.addEventListener('input', function() {
                const url = this.value;
                if (url) {
                    let platform = 'URL';
                    if (url.includes('youtube.com') || url.includes('youtu.be')) platform = 'YouTube';
                    else if (url.includes('vimeo.com')) platform = 'Vimeo';
                    videoUrlPreviewText.innerHTML = `<i class="fab fa-${platform.toLowerCase()} me-1"></i> ${platform}: ${url}`;
                    videoUrlPreview.style.display = 'block';
                } else {
                    videoUrlPreview.style.display = 'none';
                }
            });
        }
        
        const editVideoUrlInput = document.getElementById('editVideoUrl');
        const editVideoUrlPreview = document.getElementById('editVideoUrlPreview');
        const editVideoUrlPreviewText = document.getElementById('editVideoUrlPreviewText');
        
        if (editVideoUrlInput) {
            editVideoUrlInput.addEventListener('input', function() {
                const url = this.value;
                if (url) {
                    let platform = 'URL';
                    if (url.includes('youtube.com') || url.includes('youtu.be')) platform = 'YouTube';
                    else if (url.includes('vimeo.com')) platform = 'Vimeo';
                    editVideoUrlPreviewText.innerHTML = `<i class="fab fa-${platform.toLowerCase()} me-1"></i> ${platform}: ${url}`;
                    editVideoUrlPreview.style.display = 'block';
                } else {
                    editVideoUrlPreview.style.display = 'none';
                }
            });
        }
    };

    const setupVideoFilePreview = () => {
        const videoFileInput = document.getElementById('videoFile');
        const videoFilePreview = document.getElementById('videoFilePreview');
        const videoFileName = document.getElementById('videoFileName');
        const videoFileSize = document.getElementById('videoFileSize');
        
        if (videoFileInput) {
            videoFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    videoFileName.textContent = file.name;
                    videoFileSize.textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    videoFilePreview.style.display = 'block';
                } else {
                    videoFilePreview.style.display = 'none';
                }
            });
        }
        
        const editVideoFileInput = document.getElementById('editVideoFile');
        const editVideoFilePreview = document.getElementById('editVideoFilePreview');
        const editVideoFileName = document.getElementById('editVideoFileName');
        const editVideoFileSize = document.getElementById('editVideoFileSize');
        
        if (editVideoFileInput) {
            editVideoFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    editVideoFileName.textContent = file.name;
                    editVideoFileSize.textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    editVideoFilePreview.style.display = 'block';
                } else {
                    editVideoFilePreview.style.display = 'none';
                }
            });
        }
    };

    const openEditModal = (sliderId) => {
    const slider = allSliders.find(s => s.id === sliderId);
    if (!slider) { showAlert('danger', 'Slider non trouvé'); return; }
    
    document.getElementById('editSliderId').value = slider.id;
    document.getElementById('editSliderName').value = slider.name;
    document.getElementById('editSliderDescription').value = slider.description || '';
    document.getElementById('editSliderType').value = slider.type;
    document.getElementById('editSliderOrder').value = slider.order;
    document.getElementById('editButtonText').value = slider.button_text || '';
    document.getElementById('editButtonUrl').value = slider.button_url || '';
    document.getElementById('editSliderIsActive').checked = slider.is_active;
    
    // Charger la hiérarchie de localisation
    if (slider.country_id) {
        document.getElementById('editSliderCountry').value = slider.country_id;
        document.getElementById('editSliderCountry').dispatchEvent(new Event('change'));
        
        setTimeout(() => {
            if (slider.province_id) {
                document.getElementById('editSliderProvince').value = slider.province_id;
                document.getElementById('editSliderProvince').dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    if (slider.region_id) {
                        document.getElementById('editSliderRegion').value = slider.region_id;
                        document.getElementById('editSliderRegion').dispatchEvent(new Event('change'));
                        
                        setTimeout(() => {
                            if (slider.ville_id) {
                                document.getElementById('editSliderVille').value = slider.ville_id;
                            }
                        }, 100);
                    }
                }, 100);
            }
        }, 100);
    }
    
    if (slider.type === 'video') {
        // Déterminer la source de la vidéo
        const isUploaded = slider.video_type === 'upload' || (slider.video_path && !slider.video_url);
        const editVideoTypeInput = document.getElementById('editVideoType');
        
        if (isUploaded && slider.video_path) {
            // Mode Upload
            document.getElementById('editVideoSourceUpload').checked = true;
            document.getElementById('editVideoUrlSection').style.display = 'none';
            document.getElementById('editVideoFileSection').style.display = 'block';
            if (editVideoTypeInput) editVideoTypeInput.value = 'upload';
            
            // Afficher la vidéo actuelle
            const currentVideoPreview = document.getElementById('currentVideoPreview');
            currentVideoPreview.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-file-video me-2"></i>
                    <strong>Vidéo uploadée:</strong> ${slider.video_path.split('/').pop()}
                </div>
            `;
        } else {
            // Mode URL
            document.getElementById('editVideoSourceUrl').checked = true;
            document.getElementById('editVideoUrlSection').style.display = 'block';
            document.getElementById('editVideoFileSection').style.display = 'none';
            
            // Définir le type de vidéo
            if (editVideoTypeInput) editVideoTypeInput.value = slider.video_type || 'youtube';
            
            // Définir la plateforme dans le select
            const platformSelect = document.getElementById('editVideoPlatform');
            if (platformSelect) {
                let platform = 'other';
                if (slider.video_type === 'youtube') platform = 'youtube';
                if (slider.video_type === 'vimeo') platform = 'vimeo';
                platformSelect.value = platform;
                platformSelect.dispatchEvent(new Event('change'));
            }
            
            // Définir l'URL
            const videoUrlInput = document.getElementById('editVideoUrl');
            if (videoUrlInput) videoUrlInput.value = slider.video_url || '';
            
            // Déclencher l'événement input pour l'aperçu
            if (videoUrlInput) {
                const inputEvent = new Event('input', { bubbles: true });
                videoUrlInput.dispatchEvent(inputEvent);
            }
            
            // Afficher la vidéo actuelle
            const currentVideoPreview = document.getElementById('currentVideoPreview');
            if (slider.video_url) {
                let platformIcon = 'fa-link';
                let platformName = 'URL';
                if (slider.video_type === 'youtube') { platformIcon = 'fa-youtube'; platformName = 'YouTube'; }
                else if (slider.video_type === 'vimeo') { platformIcon = 'fa-vimeo'; platformName = 'Vimeo'; }
                
                currentVideoPreview.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fab ${platformIcon} me-2"></i>
                        <strong>${platformName}:</strong> ${slider.video_url}
                    </div>
                `;
            } else {
                currentVideoPreview.innerHTML = '<div class="alert alert-warning">Aucune vidéo configurée</div>';
            }
        }
        
        // Afficher le thumbnail actuel
        const currentThumbnailPreview = document.getElementById('currentThumbnailPreview');
        if (currentThumbnailPreview) {
            if (slider.image_path) {
                const imageUrl = slider.image_path.startsWith('http') ? slider.image_path : `/storage/${slider.image_path}`;
                currentThumbnailPreview.innerHTML = `<img src="${imageUrl}" class="img-thumbnail" style="max-width: 200px;">`;
            } else {
                currentThumbnailPreview.innerHTML = '<div class="text-muted">Aucun thumbnail défini</div>';
            }
        }
    } else {
        // Afficher l'image actuelle
        const currentImagePreview = document.getElementById('currentImagePreview');
        if (currentImagePreview) {
            if (slider.image_path) {
                const imageUrl = slider.image_path.startsWith('http') ? slider.image_path : `/storage/${slider.image_path}`;
                currentImagePreview.innerHTML = `<img src="${imageUrl}" class="img-thumbnail" style="max-width: 300px;">`;
            } else {
                currentImagePreview.innerHTML = '<div class="text-muted">Aucune image</div>';
            }
        }
    }
    
    toggleEditSections(slider.type);
    new bootstrap.Modal(document.getElementById('editSliderModal')).show();
};

    const renderPagination = (response) => {
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('paginationInfo');
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(response.current_page * response.per_page, response.total);
        paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} slider${response.total > 1 ? 's' : ''}`;
        
        let html = '';
        if (response.prev_page_url) html += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${response.current_page - 1})"><i class="fas fa-chevron-left"></i></a></li>`;
        else html += `<li class="page-item disabled"><span class="page-link-modern"><i class="fas fa-chevron-left"></i></span></li>`;
        
        let startPage = Math.max(1, response.current_page - 2);
        let endPage = Math.min(response.last_page, startPage + 4);
        if (endPage - startPage + 1 < 5) startPage = Math.max(1, endPage - 4);
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === response.current_page) html += `<li class="page-item active"><span class="page-link-modern">${i}</span></li>`;
            else html += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${i})">${i}</a></li>`;
        }
        
        if (response.next_page_url) html += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${response.current_page + 1})"><i class="fas fa-chevron-right"></i></a></li>`;
        else html += `<li class="page-item disabled"><span class="page-link-modern"><i class="fas fa-chevron-right"></i></span></li>`;
        
        pagination.innerHTML = html;
    };

    const changePage = (page) => { currentPage = page; loadSliders(page, currentFilters); };
    const formatTimeAgo = (date) => {
        const diffDays = Math.floor((new Date() - date) / (1000 * 60 * 60 * 24));
        if (diffDays === 0) return "Aujourd'hui";
        if (diffDays === 1) return 'Hier';
        if (diffDays < 7) return `Il y a ${diffDays} jours`;
        if (diffDays < 30) return `Il y a ${Math.floor(diffDays / 7)} semaines`;
        if (diffDays < 365) return `Il y a ${Math.floor(diffDays / 30)} mois`;
        return `Il y a ${Math.floor(diffDays / 365)} ans`;
    };
    
    const showLoading = () => {
        document.getElementById('loadingSpinner').style.display = 'flex';
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
    };
    
    const hideLoading = () => document.getElementById('loadingSpinner').style.display = 'none';
    
    const showAlert = (type, message) => {
        const existingAlert = document.querySelector('.alert-custom-modern');
        if (existingAlert) existingAlert.remove();
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-custom-modern alert-dismissible fade show`;
        alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    };
    
    const showError = (message) => showAlert('danger', message);
    
    const setupEventListeners = () => {
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => loadSliders(1, currentFilters), 500);
            });
        }
        
        const toggleFilterBtn = document.getElementById('toggleFilterBtn');
        const filterSection = document.getElementById('filterSection');
        if (toggleFilterBtn && filterSection) {
            toggleFilterBtn.addEventListener('click', () => {
                const isVisible = filterSection.style.display === 'block';
                filterSection.style.display = isVisible ? 'none' : 'block';
                toggleFilterBtn.innerHTML = isVisible ? '<i class="fas fa-sliders-h me-2"></i>Filtres' : '<i class="fas fa-times me-2"></i>Masquer les filtres';
            });
        }
        
        const applyFiltersBtn = document.getElementById('applyFiltersBtn');
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', () => {
                currentFilters = {
                    status: document.getElementById('filterStatus').value,
                    type: document.getElementById('filterType').value,
                    country_id: document.getElementById('filterCountry')?.value || '',
                    province_id: document.getElementById('filterProvince')?.value || '',
                    region_id: document.getElementById('filterRegion')?.value || '',
                    ville_id: document.getElementById('filterVille')?.value || '',
                    date_from: document.getElementById('filterDateFrom').value,
                    date_to: document.getElementById('filterDateTo').value
                };
                loadSliders(1, currentFilters);
            });
        }
        
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                document.getElementById('filterStatus').value = '';
                document.getElementById('filterType').value = '';
                if (document.getElementById('filterCountry')) document.getElementById('filterCountry').value = '';
                const fp = document.getElementById('filterProvince');
                const fr = document.getElementById('filterRegion');
                const fv = document.getElementById('filterVille');
                if (fp) { fp.innerHTML = '<option value="">Toutes les provinces</option>'; fp.disabled = true; }
                if (fr) { fr.innerHTML = '<option value="">Toutes les régions</option>'; fr.disabled = true; }
                if (fv) { fv.innerHTML = '<option value="">Toutes les villes</option>'; fv.disabled = true; }
                document.getElementById('filterDateFrom').value = '';
                document.getElementById('filterDateTo').value = '';
                currentFilters = {};
                loadSliders(1);
            });
        }
        
        document.getElementById('submitSliderBtn')?.addEventListener('click', storeSlider);
        document.getElementById('updateSliderBtn')?.addEventListener('click', updateSlider);
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', deleteSlider);
        document.getElementById('confirmRestoreBtn')?.addEventListener('click', restoreSlider);
        document.getElementById('toggleOrderView')?.addEventListener('click', toggleOrderView);
        document.getElementById('saveOrderBtn')?.addEventListener('click', saveOrder);
        document.getElementById('saveOrderBtn2')?.addEventListener('click', saveOrder);
        document.getElementById('cancelOrderBtn')?.addEventListener('click', cancelOrder);
        
        document.getElementById('deleteConfirmationModal')?.addEventListener('hidden.bs.modal', () => {
            sliderToDelete = null;
            document.getElementById('confirmDeleteBtn').innerHTML = '<span class="btn-text"><i class="fas fa-trash me-2"></i>Supprimer définitivement</span>';
            document.getElementById('confirmDeleteBtn').disabled = false;
        });
        
        document.getElementById('restoreConfirmationModal')?.addEventListener('hidden.bs.modal', () => sliderToRestore = null);
        
        document.getElementById('createSliderModal')?.addEventListener('hidden.bs.modal', () => {
            document.getElementById('createSliderForm').reset();
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('videoThumbnailPreview').style.display = 'none';
            document.getElementById('imageUploadSection').style.display = 'block';
            document.getElementById('videoUploadSection').style.display = 'none';
            resetSelect(document.getElementById('sliderProvince'));
            resetSelect(document.getElementById('sliderRegion'));
            resetSelect(document.getElementById('sliderVille'));
            const submitBtn = document.getElementById('submitSliderBtn');
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer le slider';
            submitBtn.disabled = false;
        });
        
        document.getElementById('editSliderModal')?.addEventListener('hidden.bs.modal', () => {
            const submitBtn = document.getElementById('updateSliderBtn');
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer les modifications';
            submitBtn.disabled = false;
        });
    };
</script>
<style>
    /* Slider specific styles */
    .slider-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    
    .slider-name-modern {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .slider-icon-modern {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        overflow: hidden;
    }
    
    .slider-name-text {
        font-weight: 600;
        color: #333;
        font-size: 1em;
    }
    
    .slider-type-modern {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 500;
    }
    
    .type-image-modern {
        background-color: #e3f2fd;
        color: #1565c0;
    }
    
    .type-video-modern {
        background-color: #fff3e0;
        color: #ef6c00;
    }
    
    .order-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50%;
        font-weight: bold;
        font-size: 0.9em;
        cursor: move;
    }
    
    /* Sortable list styles */
    .sortable-list {
        min-height: 100px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #dee2e6;
    }
    
    .sortable-item {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 8px;
        padding: 12px;
        cursor: move;
        transition: all 0.3s ease;
    }
    
    .sortable-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .sortable-item-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .sortable-handle {
        color: #6c757d;
        cursor: grab;
        padding: 8px;
    }
    
    .sortable-handle:active {
        cursor: grabbing;
    }
    
    .sortable-image {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        overflow: hidden;
    }
    
    .sortable-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .sortable-info {
        flex: 1;
    }
    
    .sortable-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }
    
    .sortable-details {
        display: flex;
        gap: 8px;
    }
    
    .sortable-order {
        font-weight: bold;
        color: #667eea;
        font-size: 1.1em;
    }
    
    .sortable-ghost {
        opacity: 0.4;
        background: #c8c8c8;
    }
    
    .sortable-chosen {
        background: #f8f9fa;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .sortable-drag {
        opacity: 0.8;
        transform: rotate(3deg);
    }
    
    .order-container {
        padding: 20px;
    }
    
    .order-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    
    /* Preview styles */
    .slider-preview {
        padding: 20px;
        text-align: center;
    }
    
    .preview-image img {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .preview-button {
        margin-top: 20px;
    }
    
    /* Form sections */
    .upload-section {
        transition: all 0.3s ease;
    }
    
    .image-preview img {
        max-width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    
    /* Badge variations */
    .badge.bg-youtube {
        background-color: #ff0000 !important;
    }
    
    .badge.bg-vimeo {
        background-color: #1ab7ea !important;
    }
    
    .badge.bg-upload {
        background-color: #6f42c1 !important;
    }
    
    /* Status badges */
    .status-deleted-modern {
        background-color: #f8d7da;
        color: #721c24;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        display: inline-flex;
        align-items: center;
    }
    
    .restore-btn-modern {
        background-color: #198754;
        color: white;
    }
    
    .restore-btn-modern:hover {
        background-color: #157347;
    }
    
    .preview-btn-modern {
        background-color: #0d6efd;
        color: white;
    }
    
    .preview-btn-modern:hover {
        background-color: #0b5ed7;
    }
    
    .status-btn-modern {
        background-color: #6c757d;
        color: white;
    }
    
    .status-btn-modern:hover {
        background-color: #5c636a;
    }
    
    /* Restore modal */
    .restore-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #20c997, #198754);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 2rem;
    }
    
    .restore-title {
        color: #198754;
        margin-bottom: 10px;
    }
    
    .restore-message {
        color: #666;
        margin-bottom: 20px;
    }
    
    .slider-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .slider-info-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .slider-info-name {
        font-weight: 600;
        color: #333;
        font-size: 1.1em;
    }
    
    .slider-info-type {
        margin-top: 5px;
    }
        /* Slider icon placeholder styles */
    .slider-icon-placeholder {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        color: white;
        font-size: 1.5rem;
    }
    
    .slider-icon-placeholder.video-placeholder {
        background: linear-gradient(135deg, #ef476f, #d4335f);
    }
    
    .slider-icon-placeholder i,
    .slider-icon-placeholder.video-placeholder i {
        filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.2));
    }
    
    /* Placeholder image fallback */
    .slider-thumbnail.placeholder-image {
        object-fit: contain;
        background: #f8f9fa;
        padding: 10px;
    }
    
    /* Preview placeholder */
    .preview-placeholder {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        padding: 60px 20px;
        text-align: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .placeholder-content {
        color: #6c757d;
    }
    
    .placeholder-content i {
        color: #adb5bd;
    }
    
    .placeholder-content p {
        margin: 0;
        font-size: 1.1rem;
    }
    
    /* Badge styles for video types */
    .badge.bg-youtube {
        background-color: #ff0000 !important;
    }
    
    .badge.bg-vimeo {
        background-color: #1ab7ea !important;
    }
    
    .badge.bg-upload {
        background-color: #6f42c1 !important;
    }
    
    /* Status badge for deleted */
    .status-deleted-modern {
        background-color: #f8d7da;
        color: #721c24;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        display: inline-flex;
        align-items: center;
    }
    
    /* Restore button */
    .restore-btn-modern {
        background-color: #198754;
        color: white;
    }
    
    .restore-btn-modern:hover {
        background-color: #157347;
    }
    
    /* Preview button */
    .preview-btn-modern {
        background-color: #0d6efd;
        color: white;
    }
    
    .preview-btn-modern:hover {
        background-color: #0b5ed7;
    }
    
    /* Status toggle button */
    .status-btn-modern {
        background-color: #6c757d;
        color: white;
    }
    
    .status-btn-modern:hover {
        background-color: #5c636a;
    }
    
    /* Restore modal */
    .restore-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #20c997, #198754);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 2rem;
    }
    
    .restore-title {
        color: #198754;
        margin-bottom: 10px;
    }
    
    .restore-message {
        color: #666;
        margin-bottom: 20px;
    }
    
    /* Slider info in modals */
    .slider-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .slider-info-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .slider-info-name {
        font-weight: 600;
        color: #333;
        font-size: 1.1em;
    }
    

    .slider-info-type {
        margin-top: 5px;
    }

    /* ============================================
   STYLES COMPLETS POUR LES BOUTONS ET ÉLÉMENTS
   ============================================ */

/* Boutons d'action dans le tableau */
.slider-actions-modern {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.action-btn-modern {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

/* Bouton Modifier */
.edit-btn-modern {
    background-color: #ffc107;
    color: #856404;
}

.edit-btn-modern:hover {
    background-color: #e0a800;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

/* Bouton Aperçu */
.preview-btn-modern {
    background-color: #0d6efd;
    color: white;
}

.preview-btn-modern:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

/* Bouton Statut (activer/désactiver) */
.status-btn-modern {
    background-color: #6c757d;
    color: white;
}

.status-btn-modern:hover {
    background-color: #5c636a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

/* Bouton Supprimer */
.delete-btn-modern {
    background-color: #dc3545;
    color: white;
}

.delete-btn-modern:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Bouton Restaurer */
.restore-btn-modern {
    background-color: #198754;
    color: white;
}

.restore-btn-modern:hover {
    background-color: #157347;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

/* Boutons principaux */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 10px 24px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-primary .btn-text {
    position: relative;
    z-index: 1;
}

/* Bouton secondaire */
.btn-secondary {
    background-color: #6c757d;
    border: none;
    padding: 10px 24px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

/* Bouton danger */
.btn-danger {
    background: linear-gradient(135deg, #ef476f, #d4335f);
    border: none;
    padding: 10px 24px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 71, 111, 0.4);
    background: linear-gradient(135deg, #d4335f, #ef476f);
}

/* Bouton succès */
.btn-success {
    background: linear-gradient(135deg, #20c997, #198754);
    border: none;
    padding: 10px 24px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(32, 201, 151, 0.4);
    background: linear-gradient(135deg, #198754, #20c997);
}

/* Bouton outline */
.btn-outline-secondary {
    border: 2px solid #e9ecef;
    background: transparent;
    padding: 8px 20px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Bouton FAB (Floating Action Button) */
.fab-modern {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    z-index: 1000;
}

.fab-modern:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
}

.fab-modern:active {
    transform: scale(0.95);
}

/* Animation pulse pour le bouton principal */
.btn-pulse {
    position: relative;
    overflow: hidden;
}

.btn-pulse::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-pulse:active::before {
    width: 300px;
    height: 300px;
}

/* Bouton de traitement (loading) */
.btn-processing {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Pagination */
.modern-pagination {
    display: flex;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-link-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 8px;
    background: white;
    color: #667eea;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.page-link-modern:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
}

.page-item.active .page-link-modern {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-color: transparent;
}

.page-item.disabled .page-link-modern {
    color: #adb5bd;
    cursor: not-allowed;
    pointer-events: none;
}

/* Boutons de modal */
.modal-footer-modern .btn {
    padding: 8px 20px;
    font-size: 14px;
}

.modal-footer-modern .btn-secondary {
    background-color: #e9ecef;
    color: #6c757d;
}

.modal-footer-modern .btn-secondary:hover {
    background-color: #dee2e6;
    color: #495057;
}

.modal-footer-modern .btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

/* Toast/Alert styles */
.alert-custom-modern {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 450px;
    animation: slideInRight 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 12px;
    border: none;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.alert-custom-modern .btn-close {
    font-size: 10px;
}

/* Boutons dans les modals de confirmation */
.delete-confirm-modal .btn-secondary {
    background-color: #e9ecef;
    color: #6c757d;
    border: none;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.delete-confirm-modal .btn-secondary:hover {
    background-color: #dee2e6;
    transform: translateY(-2px);
}

.delete-confirm-modal .btn-danger {
    background: linear-gradient(135deg, #ef476f, #d4335f);
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 500;
}

/* Effet de hover sur les boutons d'action */
.slider-actions-modern .action-btn-modern {
    position: relative;
    overflow: hidden;
}

.slider-actions-modern .action-btn-modern::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.3s, height 0.3s;
}

.slider-actions-modern .action-btn-modern:active::after {
    width: 100px;
    height: 100px;
}

/* Responsive pour les boutons */
@media (max-width: 768px) {
    .slider-actions-modern {
        gap: 4px;
    }
    
    .action-btn-modern {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
    
    .btn-primary, .btn-secondary, .btn-danger, .btn-success {
        padding: 6px 16px;
        font-size: 13px;
    }
    
    .fab-modern {
        width: 48px;
        height: 48px;
        bottom: 20px;
        right: 20px;
        font-size: 18px;
    }
}

/* Animation pour les nouveaux sliders */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.slider-row {
    animation: fadeInUp 0.3s ease forwards;
}
/* Styles pour les régions */
.slider-region-modern {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    background-color: #e8f0fe;
    color: #1a73e8;
    font-weight: 500;
}

/* TomSelect personnalisé */
.ts-wrapper {
    margin-bottom: 0;
}

.ts-control {
    border-radius: 12px !important;
    border: 2px solid #e9ecef !important;
    padding: 10px 16px !important;
    background: white;
    transition: all 0.3s ease;
}

.ts-control:hover {
    border-color: #667eea !important;
}

.ts-dropdown {
    border-radius: 12px !important;
    border: 2px solid #e9ecef !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.ts-dropdown .option {
    padding: 10px 16px !important;
    transition: all 0.3s ease;
}

.ts-dropdown .option:hover {
    background: linear-gradient(135deg, #667eea10, #764ba210) !important;
}

.ts-dropdown .option.active {
    background: linear-gradient(135deg, #667eea20, #764ba220) !important;
    color: #667eea;
}

.region-option {
    display: flex;
    align-items: center;
}

.region-selected {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Responsive pour les régions */
@media (max-width: 768px) {
    .slider-region-modern {
        font-size: 0.75em;
        padding: 2px 8px;
    }
    
    .ts-control {
        padding: 6px 12px !important;
    }
}
    /* Styles pour la recherche de localisation */
    .location-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: none;
    }

    .location-result-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .location-result-item:hover {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    .result-type-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 8px;
    }

    .result-type-badge.country {
        background-color: #e3f2fd;
        color: #1565c0;
    }

    .result-type-badge.province {
        background-color: #fff3e0;
        color: #ef6c00;
    }

    .result-type-badge.region {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .result-type-badge.ville {
        background-color: #fce4ec;
        color: #c2185b;
    }

    .result-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .result-hierarchy {
        font-size: 0.75rem;
        color: #6c757d;
    }

    /* Style pour l'affichage de la localisation dans le tableau */
    .slider-region-modern {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        background-color: #e8f0fe;
        color: #1a73e8;
        font-weight: 500;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Style pour les selects désactivés */
    select[disabled] {
        background-color: #e9ecef;
        cursor: not-allowed;
        opacity: 0.7;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .slider-region-modern {
            font-size: 0.75em;
            padding: 2px 8px;
            max-width: 150px;
        }
        
        .location-result-item {
            padding: 8px 12px;
        }
        
        .result-type-badge {
            font-size: 0.65rem;
        }
        
        .result-name {
            font-size: 0.9rem;
        }
        
        .result-hierarchy {
            font-size: 0.7rem;
        }
    }
    /* Styles pour les boutons radio */
.form-check {
    margin-bottom: 0;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-check-label:hover {
    background-color: #f8f9fa;
}

/* Styles pour les aperçus */
.video-url-preview,
.video-file-preview {
    margin-top: 10px;
}

.video-url-preview .alert,
.video-file-preview .alert {
    padding: 8px 12px;
    font-size: 0.9rem;
}

/* Animation pour les sections */
#videoUrlSection,
#videoFileSection,
#editVideoUrlSection,
#editVideoFileSection {
    transition: all 0.3s ease;
}
</style>
@endsection