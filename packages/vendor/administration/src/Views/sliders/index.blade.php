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
                    <div class="sortable-list" id="sortableList"></div>
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
                    <div class="spinner-container" id="loadingSpinner">
                        <div class="spinner-border text-primary spinner" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                    
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
                            <tbody id="slidersTableBody"></tbody>
                        </table>
                    </div>
                    
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
            
            <div class="pagination-container-modern" id="paginationContainer" style="display: none;">
                <div class="pagination-info-modern" id="paginationInfo"></div>
                <nav aria-label="Page navigation">
                    <ul class="modern-pagination" id="pagination"></ul>
                </nav>
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createSliderModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- ===================================================== -->
    <!-- CREATE SLIDER MODAL WITH INFO BULLS (TOOLTIPS) -->
    <!-- ===================================================== -->
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
                        
                        <!-- NOM DU SLIDER -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="label-with-info">
                                    <label for="sliderName" class="form-label-modern">Nom du slider *</label>
                                    <div class="info-bull" data-tooltip="Le nom interne du slider pour l'identifier dans l'administration. Ce nom n'est pas visible par les visiteurs.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control-modern" id="sliderName" name="name" placeholder="Ex: Bienvenue au Canada" required>
                                <div class="form-text-modern">Nom descriptif du slider</div>
                            </div>
                        </div>
                        
                        <!-- DESCRIPTION -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="label-with-info">
                                    <label for="sliderDescription" class="form-label-modern">Description</label>
                                    <div class="info-bull" data-tooltip="Description optionnelle du slider. Peut être utilisée comme sous-titre ou texte additionnel sur la bannière.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <textarea class="form-control-modern" id="sliderDescription" name="description" rows="2" placeholder="Description du slider..."></textarea>
                                <div class="form-text-modern">Texte court pour décrire le contenu</div>
                            </div>
                        </div>
                        
                        <!-- TYPE + ORDRE -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="label-with-info">
                                    <label for="sliderType" class="form-label-modern">Type de contenu *</label>
                                    <div class="info-bull" data-tooltip="Choisissez 'Image' pour une bannière statique ou 'Vidéo' pour un contenu animé (YouTube, Vimeo ou upload local).">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="sliderType" name="type" required>
                                    <option value="image">Image</option>
                                    <option value="video">Vidéo</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="label-with-info">
                                    <label for="sliderOrder" class="form-label-modern">Ordre d'affichage</label>
                                    <div class="info-bull" data-tooltip="Position dans le carousel. Plus le chiffre est petit, plus le slider apparaît en premier. L'ordre 1 = premier élément affiché.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="number" class="form-control-modern" id="sliderOrder" name="order" min="1" value="1">
                                <div class="form-text-modern">Position dans le slider (1 = premier)</div>
                            </div>
                        </div>

                        <!-- RECHERCHE LOCALISATION -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="label-with-info">
                                    <label class="form-label-modern">Recherche rapide de localisation</label>
                                    <div class="info-bull" data-tooltip="Recherchez un pays, province, région ou ville par nom. La sélection remplira automatiquement les champs de localisation ci-dessous.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control-modern" id="locationSearchInput" 
                                       placeholder="Rechercher par pays, province, région ou ville...">
                                <div class="form-text-modern">Commencez à taper pour rechercher une localisation</div>
                            </div>
                        </div>

                        <!-- LOCALISATION -->
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <div class="label-with-info">
                                    <label for="sliderCountry" class="form-label-modern">Pays</label>
                                    <div class="info-bull" data-tooltip="Sélectionnez le pays où ce slider sera affiché. Laissez vide pour un affichage global (tous les pays).">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="sliderCountry" name="country_id">
                                    <option value="">Sélectionnez un pays...</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-4">
                                <div class="label-with-info">
                                    <label for="sliderProvince" class="form-label-modern">Province</label>
                                    <div class="info-bull" data-tooltip="Filtrage par province. Dépend du pays sélectionné. Laissez vide pour toutes les provinces.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="sliderProvince" name="province_id" disabled>
                                    <option value="">Sélectionnez d'abord un pays...</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-4">
                                <div class="label-with-info">
                                    <label for="sliderRegion" class="form-label-modern">Région</label>
                                    <div class="info-bull" data-tooltip="Filtrage par région. Dépend de la province sélectionnée. Laissez vide pour toutes les régions.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="sliderRegion" name="region_id" disabled>
                                    <option value="">Sélectionnez d'abord une province...</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-4">
                                <div class="label-with-info">
                                    <label for="sliderVille" class="form-label-modern">Ville</label>
                                    <div class="info-bull" data-tooltip="Filtrage par ville. Dépend de la région sélectionnée. Laissez vide pour toutes les villes.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="sliderVille" name="ville_id" disabled>
                                    <option value="">Sélectionnez d'abord une région...</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- IMAGE UPLOAD SECTION -->
                        <div class="upload-section" id="imageUploadSection">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label for="sliderImage" class="form-label-modern">Image *</label>
                                        <div class="info-bull" data-tooltip="Formats acceptés : JPEG, PNG, GIF, WebP. Taille maximale : 5MB. Résolution recommandée : 1920x1080px.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control-modern" id="sliderImage" name="image" accept="image/*" required>
                                    <div class="form-text-modern">Format: JPEG, PNG, GIF, WebP - Max: 5MB</div>
                                    <div class="image-preview mt-2" id="imagePreview" style="display: none;">
                                        <img id="previewImage" class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- VIDEO UPLOAD SECTION -->
                        <div class="upload-section" id="videoUploadSection" style="display: none;">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label class="form-label-modern">Source de la vidéo *</label>
                                        <div class="info-bull" data-tooltip="Vous pouvez soit coller une URL (YouTube, Vimeo) soit uploader un fichier vidéo local (MP4, AVI, MOV).">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
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
                            
                            <!-- Video URL Section -->
                            <div class="row" id="videoUrlSection">
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label for="videoPlatform" class="form-label-modern">Plateforme vidéo *</label>
                                        <div class="info-bull" data-tooltip="Sélectionnez la plateforme d'hébergement de votre vidéo pour un affichage optimisé.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <select class="form-select-modern" id="videoPlatform" name="video_platform">
                                        <option value="youtube">YouTube</option>
                                        <option value="vimeo">Vimeo</option>
                                        <option value="other">Autre URL</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label for="videoUrl" class="form-label-modern">URL de la vidéo *</label>
                                        <div class="info-bull" data-tooltip="Collez l'URL complète de la vidéo. Exemple: https://www.youtube.com/watch?v=... ou https://vimeo.com/...">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <input type="url" class="form-control-modern" id="videoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
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
                                    <div class="label-with-info">
                                        <label for="videoFile" class="form-label-modern">Fichier vidéo *</label>
                                        <div class="info-bull" data-tooltip="Formats acceptés : MP4, AVI, MOV, WMV. Taille maximale : 100MB. Recommandé : MP4 pour une meilleure compatibilité.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control-modern" id="videoFile" name="video_file" accept="video/*">
                                    <div class="form-text-modern">Format: MP4, AVI, MOV, WMV - Max: 100MB</div>
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
                                    <div class="label-with-info">
                                        <label for="videoThumbnail" class="form-label-modern">Image de prévisualisation</label>
                                        <div class="info-bull" data-tooltip="Image affichée avant le chargement/lecture de la vidéo. Si non fournie, une miniature automatique sera générée (si possible).">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control-modern" id="videoThumbnail" name="image" accept="image/*">
                                    <div class="form-text-modern">Image affichée avant la lecture de la vidéo (optionnel)</div>
                                    <div class="image-preview mt-2" id="videoThumbnailPreview" style="display: none;">
                                        <img id="previewVideoThumbnail" class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- BOUTON SECTION -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="label-with-info">
                                    <label for="buttonText" class="form-label-modern">Texte du bouton</label>
                                    <div class="info-bull" data-tooltip="Texte affiché sur le bouton d'appel à l'action. Exemple: 'Découvrir', 'En savoir plus', 'Acheter maintenant'.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control-modern" id="buttonText" name="button_text" placeholder="Ex: Découvrir">
                                <div class="form-text-modern">Laissez vide pour ne pas afficher de bouton</div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="label-with-info">
                                    <label for="buttonUrl" class="form-label-modern">URL du bouton</label>
                                    <div class="info-bull" data-tooltip="Lien de destination du bouton. Doit commencer par http:// ou https:// pour les liens externes, ou / pour les liens internes.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control-modern" id="buttonUrl" name="button_url" placeholder="https://...">
                                <div class="form-text-modern">Lien de destination du bouton</div>
                            </div>
                        </div>
                        
                        <!-- STATUT ACTIF -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sliderIsActive" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="sliderIsActive">
                                        Slider actif
                                        <div class="info-bull-inline" data-tooltip="Si activé, le slider sera visible sur le site. Si désactivé, il sera masqué mais restera dans la base de données.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" id="videoType" name="video_type" value="youtube">
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
    
    <!-- ===================================================== -->
    <!-- EDIT SLIDER MODAL WITH INFO BULLS (TOOLTIPS) -->
    <!-- ===================================================== -->
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
                        
                        <!-- NOM DU SLIDER -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="label-with-info">
                                    <label for="editSliderName" class="form-label-modern">Nom du slider *</label>
                                    <div class="info-bull" data-tooltip="Le nom interne du slider pour l'identifier dans l'administration. Ce nom n'est pas visible par les visiteurs.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control-modern" id="editSliderName" name="name" required>
                            </div>
                        </div>
                        
                        <!-- DESCRIPTION -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="label-with-info">
                                    <label for="editSliderDescription" class="form-label-modern">Description</label>
                                    <div class="info-bull" data-tooltip="Description optionnelle du slider. Peut être utilisée comme sous-titre ou texte additionnel sur la bannière.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <textarea class="form-control-modern" id="editSliderDescription" name="description" rows="2"></textarea>
                            </div>
                        </div>
                        
                        <!-- TYPE + ORDRE -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="label-with-info">
                                    <label for="editSliderType" class="form-label-modern">Type de contenu *</label>
                                    <div class="info-bull" data-tooltip="Choisissez 'Image' pour une bannière statique ou 'Vidéo' pour un contenu animé (YouTube, Vimeo ou upload local).">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="editSliderType" name="type" required>
                                    <option value="image">Image</option>
                                    <option value="video">Vidéo</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="label-with-info">
                                    <label for="editSliderOrder" class="form-label-modern">Ordre d'affichage</label>
                                    <div class="info-bull" data-tooltip="Position dans le carousel. Plus le chiffre est petit, plus le slider apparaît en premier.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="number" class="form-control-modern" id="editSliderOrder" name="order" min="1">
                            </div>
                        </div>

                        <!-- RECHERCHE LOCALISATION -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="label-with-info">
                                    <label class="form-label-modern">Recherche rapide de localisation</label>
                                    <div class="info-bull" data-tooltip="Recherchez un pays, province, région ou ville par nom. La sélection remplira automatiquement les champs ci-dessous.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control-modern" id="editLocationSearchInput" 
                                       placeholder="Rechercher par pays, province, région ou ville...">
                            </div>
                        </div>

                        <!-- LOCALISATION -->
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <div class="label-with-info">
                                    <label for="editSliderCountry" class="form-label-modern">Pays</label>
                                    <div class="info-bull" data-tooltip="Sélectionnez le pays où ce slider sera affiché. Laissez vide pour un affichage global.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="editSliderCountry" name="country_id">
                                    <option value="">Sélectionnez un pays...</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-4">
                                <div class="label-with-info">
                                    <label for="editSliderProvince" class="form-label-modern">Province</label>
                                    <div class="info-bull" data-tooltip="Filtrage par province. Dépend du pays sélectionné.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="editSliderProvince" name="province_id" disabled>
                                    <option value="">Sélectionnez d'abord un pays...</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-4">
                                <div class="label-with-info">
                                    <label for="editSliderRegion" class="form-label-modern">Région</label>
                                    <div class="info-bull" data-tooltip="Filtrage par région. Dépend de la province sélectionnée.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="editSliderRegion" name="region_id" disabled>
                                    <option value="">Sélectionnez d'abord une province...</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-4">
                                <div class="label-with-info">
                                    <label for="editSliderVille" class="form-label-modern">Ville</label>
                                    <div class="info-bull" data-tooltip="Filtrage par ville. Dépend de la région sélectionnée.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <select class="form-select-modern" id="editSliderVille" name="ville_id" disabled>
                                    <option value="">Sélectionnez d'abord une région...</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- CURRENT IMAGE PREVIEW -->
                        <div class="row" id="currentImageSection">
                            <div class="col-md-12 mb-4">
                                <div class="label-with-info">
                                    <label class="form-label-modern">Image actuelle</label>
                                    <div class="info-bull" data-tooltip="Image actuellement utilisée pour ce slider. Vous pouvez la remplacer en téléchargeant une nouvelle image ci-dessous.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <div id="currentImagePreview" class="mb-2"></div>
                            </div>
                        </div>
                        
                        <!-- NEW IMAGE UPLOAD -->
                        <div class="upload-section" id="editImageUploadSection">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label for="editSliderImage" class="form-label-modern">Nouvelle image</label>
                                        <div class="info-bull" data-tooltip="Téléchargez une nouvelle image pour remplacer l'actuelle. Laissez vide pour conserver l'image existante.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control-modern" id="editSliderImage" name="image" accept="image/*">
                                    <div class="form-text-modern">Laisser vide pour conserver l'image actuelle</div>
                                    <div class="image-preview mt-2" id="editImagePreview" style="display: none;">
                                        <img id="previewEditImage" class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- VIDEO SECTION EDIT -->
                        <div class="upload-section" id="editVideoUploadSection" style="display: none;">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label class="form-label-modern">Source de la vidéo</label>
                                        <div class="info-bull" data-tooltip="Changez la source de la vidéo si nécessaire. Laissez vide pour conserver la vidéo actuelle.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
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
                                    <div class="label-with-info">
                                        <label class="form-label-modern">Vidéo actuelle</label>
                                        <div class="info-bull" data-tooltip="Vidéo actuellement utilisée pour ce slider.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <div id="currentVideoPreview" class="mb-2"></div>
                                </div>
                            </div>
                            
                            <!-- Video URL Edit Section -->
                            <div class="row" id="editVideoUrlSection">
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label for="editVideoPlatform" class="form-label-modern">Plateforme vidéo</label>
                                        <div class="info-bull" data-tooltip="Sélectionnez la plateforme d'hébergement de votre nouvelle vidéo.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <select class="form-select-modern" id="editVideoPlatform" name="edit_video_platform">
                                        <option value="youtube">YouTube</option>
                                        <option value="vimeo">Vimeo</option>
                                        <option value="other">Autre URL</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label for="editVideoUrl" class="form-label-modern">Nouvelle URL vidéo</label>
                                        <div class="info-bull" data-tooltip="Collez la nouvelle URL de la vidéo. Laissez vide pour conserver la vidéo actuelle.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <input type="url" class="form-control-modern" id="editVideoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                                    <div class="edit-video-url-preview mt-2" id="editVideoUrlPreview" style="display: none;">
                                        <div class="alert alert-info">
                                            <i class="fas fa-link me-2" id="editVideoPreviewIcon"></i>
                                            <span id="editVideoUrlPreviewText"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Video File Upload Edit Section -->
                            <div class="row" id="editVideoFileSection" style="display: none;">
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label for="editVideoFile" class="form-label-modern">Nouveau fichier vidéo</label>
                                        <div class="info-bull" data-tooltip="Téléchargez un nouveau fichier vidéo pour remplacer l'actuel. Laissez vide pour conserver la vidéo existante.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control-modern" id="editVideoFile" name="video_file" accept="video/*">
                                    <div class="form-text-modern">Format: MP4, AVI, MOV, WMV - Max: 100MB</div>
                                    <div class="video-file-preview mt-2" id="editVideoFilePreview" style="display: none;">
                                        <div class="alert alert-info">
                                            <i class="fas fa-file-video me-2"></i>
                                            <span id="editVideoFileName"></span>
                                            <span id="editVideoFileSize" class="ms-2 text-muted"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Video Thumbnail Edit -->
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="label-with-info">
                                        <label class="form-label-modern">Image de prévisualisation actuelle</label>
                                        <div class="info-bull" data-tooltip="Miniature actuellement utilisée pour cette vidéo.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <div id="currentThumbnailPreview" class="mb-2"></div>
                                    
                                    <label for="editVideoThumbnail" class="form-label-modern mt-2">Nouvelle image de prévisualisation</label>
                                    <div class="info-bull" data-tooltip="Téléchargez une nouvelle miniature pour remplacer l'actuelle.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <input type="file" class="form-control-modern" id="editVideoThumbnail" name="image" accept="image/*">
                                    <div class="form-text-modern">Laisser vide pour conserver l'image actuelle</div>
                                    <div class="image-preview mt-2" id="editVideoThumbnailPreview" style="display: none;">
                                        <img id="previewEditVideoThumbnail" class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- BUTTON SECTION EDIT -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="label-with-info">
                                    <label for="editButtonText" class="form-label-modern">Texte du bouton</label>
                                    <div class="info-bull" data-tooltip="Texte affiché sur le bouton d'appel à l'action.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control-modern" id="editButtonText" name="button_text">
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="label-with-info">
                                    <label for="editButtonUrl" class="form-label-modern">URL du bouton</label>
                                    <div class="info-bull" data-tooltip="Lien de destination du bouton.">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control-modern" id="editButtonUrl" name="button_url">
                            </div>
                        </div>
                        
                        <!-- STATUS EDIT -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editSliderIsActive" name="is_active" value="1">
                                    <label class="form-check-label" for="editSliderIsActive">
                                        Slider actif
                                        <div class="info-bull-inline" data-tooltip="Si activé, le slider sera visible sur le site. Si désactivé, il sera masqué.">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </label>
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
                    <div id="previewContent"></div>
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
                    
                    <div class="slider-to-delete" id="sliderToDeleteInfo"></div>
                    
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
                    
                    <div class="slider-to-restore" id="sliderToRestoreInfo"></div>
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
    
    <!-- CSS and JS dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- Package Assets -->
    <link href="{{ asset('vendor/administration/css/slider.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/administration/js/slider.js') }}" defer></script>
    
@endsection