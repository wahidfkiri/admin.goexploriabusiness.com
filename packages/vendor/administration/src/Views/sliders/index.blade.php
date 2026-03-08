@extends('editor::layouts.app')

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
                                    <label for="videoType" class="form-label-modern">Type de vidéo *</label>
                                    <select class="form-select-modern" id="videoType" name="video_type">
                                        <option value="youtube">YouTube</option>
                                        <option value="vimeo">Vimeo</option>
                                        <option value="upload">Upload</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- YouTube/Vimeo URL -->
                            <div class="row" id="videoUrlSection">
                                <div class="col-md-12 mb-4">
                                    <label for="videoUrl" class="form-label-modern">URL de la vidéo *</label>
                                    <input type="url" class="form-control-modern" id="videoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=... ou https://vimeo.com/...">
                                    <div class="form-text-modern">Collez l'URL complète de la vidéo YouTube ou Vimeo</div>
                                </div>
                            </div>
                            
                            <!-- Video File Upload -->
                            <div class="row" id="videoFileSection" style="display: none;">
                                <div class="col-md-12 mb-4">
                                    <label for="videoFile" class="form-label-modern">Fichier vidéo *</label>
                                    <input type="file" class="form-control-modern" id="videoFile" name="video_file" accept="video/*">
                                    <div class="form-text-modern">Format: MP4, AVI, MOV, WMV - Max: 10MB</div>
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
                        </div>
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
                        
                        <!-- Current Image Preview -->
                        <div class="row" id="currentImageSection">
                            <div class="col-md-12 mb-4">
                                <label class="form-label-modern">Image actuelle</label>
                                <div id="currentImagePreview" class="mb-2">
                                    <!-- Current image will be loaded here -->
                                </div>
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
                                    <label for="editVideoType" class="form-label-modern">Type de vidéo *</label>
                                    <select class="form-select-modern" id="editVideoType" name="video_type">
                                        <option value="youtube">YouTube</option>
                                        <option value="vimeo">Vimeo</option>
                                        <option value="upload">Upload</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Current Video Preview -->
                            <div class="row" id="currentVideoSection">
                                <div class="col-md-12 mb-4">
                                    <label class="form-label-modern">Vidéo actuelle</label>
                                    <div id="currentVideoPreview" class="mb-2">
                                        <!-- Current video info will be loaded here -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- YouTube/Vimeo URL -->
                            <div class="row" id="editVideoUrlSection">
                                <div class="col-md-12 mb-4">
                                    <label for="editVideoUrl" class="form-label-modern">URL de la vidéo</label>
                                    <input type="url" class="form-control-modern" id="editVideoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                                    <div class="form-text-modern">Nouvelle URL YouTube ou Vimeo</div>
                                </div>
                            </div>
                            
                            <!-- Video File Upload -->
                            <div class="row" id="editVideoFileSection" style="display: none;">
                                <div class="col-md-12 mb-4">
                                    <label for="editVideoFile" class="form-label-modern">Nouveau fichier vidéo</label>
                                    <input type="file" class="form-control-modern" id="editVideoFile" name="video_file" accept="video/*">
                                    <div class="form-text-modern">Remplacer la vidéo actuelle</div>
                                </div>
                            </div>
                            
                            <!-- Video Thumbnail -->
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label class="form-label-modern">Image de prévisualisation actuelle</label>
                                    <div id="currentThumbnailPreview" class="mb-2">
                                        <!-- Current thumbnail will be loaded here -->
                                    </div>
                                    <label for="editVideoThumbnail" class="form-label-modern">Nouvelle image de prévisualisation</label>
                                    <input type="file" class="form-control-modern" id="editVideoThumbnail" name="image" accept="image/*">
                                    <div class="form-text-modern">Laisser vide pour conserver l'image actuelle</div>
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
            setupEventListeners();
            setupImagePreview();
            setupVideoTypeToggle();
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
                
                // Format date
                const createdDate = new Date(slider.created_at);
                const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                
                // Type badge
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
                    }
                }
                
                // Status badge
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
                
                // Image preview
                const imageUrl = slider.image_path 
                    ? `/storage/${slider.image_path}` 
                    : 'https://via.placeholder.com/50';
                
                row.innerHTML = `
                    <td>
                        <div class="order-badge" data-id="${slider.id}">
                            <i class="fas fa-arrows-alt me-1"></i>
                            ${slider.order}
                        </div>
                    </td>
                    <td class="slider-name-cell">
                        <div class="slider-name-modern">
                            <div class="slider-icon-modern">
                                <img src="${imageUrl}" alt="${slider.name}" class="slider-thumbnail">
                            </div>
                            <div>
                                <div class="slider-name-text">${slider.name}</div>
                                <small class="text-muted">ID: ${slider.id}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="slider-type-modern ${typeClass}">
                            <i class="fab ${typeIcon} me-1"></i>
                            ${typeText}
                        </span>
                    </td>
                    <td>
                        <span class="slider-status-modern ${statusClass}">
                            <i class="fas ${statusIcon} me-1"></i>
                            ${statusText}
                        </span>
                    </td>
                    <td>
                        <div>${formattedDate}</div>
                        <small class="text-muted">${formatTimeAgo(createdDate)}</small>
                    </td>
                    <td>
                        <div class="slider-actions-modern">
                            <button class="action-btn-modern preview-btn-modern" title="Aperçu" onclick="previewSlider(${slider.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${slider.deleted_at ? `
                                <button class="action-btn-modern restore-btn-modern" title="Restaurer" onclick="showRestoreConfirmation(${slider.id})">
                                    <i class="fas fa-undo"></i>
                                </button>
                            ` : `
                                <button class="action-btn-modern edit-btn-modern" title="Modifier" onclick="openEditModal(${slider.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn-modern status-btn-modern" title="Changer statut" onclick="toggleSliderStatus(${slider.id})">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button class="action-btn-modern delete-btn-modern" title="Supprimer" onclick="showDeleteConfirmation(${slider.id})">
                                    <i class="fas fa-trash"></i>
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

        // Setup Sortable drag and drop
        const setupSortable = (sliders) => {
            const sortableList = document.getElementById('sortableList');
            
            // Clear existing list
            sortableList.innerHTML = '';
            
            // Sort sliders by order
            const sortedSliders = [...sliders].sort((a, b) => a.order - b.order);
            originalOrder = sortedSliders.map(s => ({id: s.id, order: s.order}));
            
            // Create sortable items
            sortedSliders.forEach(slider => {
                const item = document.createElement('div');
                item.className = 'sortable-item';
                item.dataset.id = slider.id;
                
                // Type badge
                let typeIcon = 'fa-image';
                let typeText = 'Image';
                
                if (slider.type === 'video') {
                    typeIcon = 'fa-video';
                    typeText = 'Vidéo';
                    
                    if (slider.video_type === 'youtube') {
                        typeIcon = 'fa-youtube';
                        typeText = 'YouTube';
                    } else if (slider.video_type === 'vimeo') {
                        typeIcon = 'fa-vimeo';
                        typeText = 'Vimeo';
                    }
                }
                
                // Image URL
                const imageUrl = slider.image_path 
                    ? `/storage/${slider.image_path}` 
                    : 'https://via.placeholder.com/60';
                
                item.innerHTML = `
                    <div class="sortable-item-content">
                        <div class="sortable-handle">
                            <i class="fas fa-arrows-alt"></i>
                        </div>
                        <div class="sortable-image">
                            <img src="${imageUrl}" alt="${slider.name}">
                        </div>
                        <div class="sortable-info">
                            <div class="sortable-name">${slider.name}</div>
                            <div class="sortable-details">
                                <span class="badge bg-secondary me-2">
                                    <i class="fas ${typeIcon} me-1"></i>${typeText}
                                </span>
                                <span class="badge ${slider.is_active ? 'bg-success' : 'bg-secondary'}">
                                    <i class="fas ${slider.is_active ? 'fa-check' : 'fa-ban'} me-1"></i>
                                    ${slider.is_active ? 'Actif' : 'Inactif'}
                                </span>
                            </div>
                        </div>
                        <div class="sortable-order">
                            <span class="order-badge">${slider.order}</span>
                        </div>
                    </div>
                `;
                
                sortableList.appendChild(item);
            });
            
            // Initialize Sortable
            if (sortable) {
                sortable.destroy();
            }
            
            sortable = new Sortable(sortableList, {
                animation: 150,
                handle: '.sortable-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    updateOrderNumbers();
                }
            });
        };

        // Update order numbers after drag and drop
        const updateOrderNumbers = () => {
            const items = document.querySelectorAll('.sortable-item');
            items.forEach((item, index) => {
                const orderBadge = item.querySelector('.order-badge');
                orderBadge.textContent = index + 1;
            });
        };

        // Save order to server
        const saveOrder = () => {
            const items = document.querySelectorAll('.sortable-item');
            const slidersData = [];
            
            items.forEach((item, index) => {
                const sliderId = item.dataset.id;
                slidersData.push({
                    id: parseInt(sliderId),
                    order: index + 1
                });
            });
            
            // Show loading on save button
            const saveBtn = document.getElementById('saveOrderBtn');
            const saveBtn2 = document.getElementById('saveOrderBtn2');
            const originalText = saveBtn.innerHTML;
            const originalText2 = saveBtn2.innerHTML;
            
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
            saveBtn2.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
            saveBtn.disabled = true;
            saveBtn2.disabled = true;
            
            $.ajax({
                url: '{{ route("sliders.update-order") }}',
                type: 'POST',
                data: {
                    sliders: slidersData
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Ordre sauvegardé avec succès !');
                        loadSliders(currentPage, currentFilters);
                        loadStatistics();
                        toggleOrderView(); // Return to table view
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la sauvegarde de l\'ordre');
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors de la sauvegarde de l\'ordre');
                },
                complete: function() {
                    saveBtn.innerHTML = originalText;
                    saveBtn2.innerHTML = originalText2;
                    saveBtn.disabled = false;
                    saveBtn2.disabled = false;
                }
            });
        };

        // Toggle between table view and order view
        const toggleOrderView = () => {
            const tableView = document.getElementById('tableView');
            const orderContainer = document.getElementById('orderContainer');
            const toggleBtn = document.getElementById('toggleOrderView');
            const saveBtn = document.getElementById('saveOrderBtn');
            
            if (tableView.style.display === 'none') {
                // Show table view
                tableView.style.display = 'block';
                orderContainer.style.display = 'none';
                saveBtn.style.display = 'none';
                toggleBtn.innerHTML = '<i class="fas fa-sort me-1"></i>Vue par ordre';
            } else {
                // Show order view
                if (allSliders.length === 0) {
                    showAlert('info', 'Aucun slider à réorganiser');
                    return;
                }
                
                tableView.style.display = 'none';
                orderContainer.style.display = 'block';
                saveBtn.style.display = 'inline-block';
                toggleBtn.innerHTML = '<i class="fas fa-list me-1"></i>Vue tableau';
                
                // Setup sortable with current sliders
                setupSortable(allSliders);
            }
        };

        // Cancel order editing
        const cancelOrder = () => {
            const tableView = document.getElementById('tableView');
            const orderContainer = document.getElementById('orderContainer');
            const toggleBtn = document.getElementById('toggleOrderView');
            const saveBtn = document.getElementById('saveOrderBtn');
            
            tableView.style.display = 'block';
            orderContainer.style.display = 'none';
            saveBtn.style.display = 'none';
            toggleBtn.innerHTML = '<i class="fas fa-sort me-1"></i>Vue par ordre';
            
            // Restore original order
            setupSortable(allSliders);
        };

        // Preview slider
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
                                <div class="slider-preview">
                                    <h5 class="mb-3">${slider.name}</h5>
                                    <div class="preview-image mb-3">
                                        <img src="${slider.image_url}" alt="${slider.name}" class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: cover;">
                                    </div>
                                    ${slider.description ? `<p class="mb-3">${slider.description}</p>` : ''}
                                    ${slider.button_text && slider.button_url ? `
                                        <div class="preview-button">
                                            <a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        } else if (slider.type === 'video') {
                            if (slider.is_youtube) {
                                content = `
                                    <div class="slider-preview">
                                        <h5 class="mb-3">${slider.name}</h5>
                                        <div class="ratio ratio-16x9 mb-3">
                                            <iframe src="${slider.video_url}" 
                                                    title="${slider.name}" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen>
                                            </iframe>
                                        </div>
                                        ${slider.description ? `<p class="mb-3">${slider.description}</p>` : ''}
                                        ${slider.button_text && slider.button_url ? `
                                            <div class="preview-button">
                                                <a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>
                                            </div>
                                        ` : ''}
                                    </div>
                                `;
                            } else if (slider.is_vimeo) {
                                content = `
                                    <div class="slider-preview">
                                        <h5 class="mb-3">${slider.name}</h5>
                                        <div class="ratio ratio-16x9 mb-3">
                                            <iframe src="${slider.video_url}" 
                                                    title="${slider.name}" 
                                                    frameborder="0" 
                                                    allow="autoplay; fullscreen; picture-in-picture" 
                                                    allowfullscreen>
                                            </iframe>
                                        </div>
                                        ${slider.description ? `<p class="mb-3">${slider.description}</p>` : ''}
                                        ${slider.button_text && slider.button_url ? `
                                            <div class="preview-button">
                                                <a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>
                                            </div>
                                        ` : ''}
                                    </div>
                                `;
                            } else {
                                content = `
                                    <div class="slider-preview">
                                        <h5 class="mb-3">${slider.name}</h5>
                                        <div class="mb-3">
                                            <video controls style="width: 100%; max-height: 400px;" poster="${slider.thumbnail_url}">
                                                <source src="${slider.video_url}" type="video/mp4">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        </div>
                                        ${slider.description ? `<p class="mb-3">${slider.description}</p>` : ''}
                                        ${slider.button_text && slider.button_url ? `
                                            <div class="preview-button">
                                                <a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>
                                            </div>
                                        ` : ''}
                                    </div>
                                `;
                            }
                        }
                        
                        previewContent.innerHTML = content;
                        
                        // Show modal
                        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
                        previewModal.show();
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors du chargement de l\'aperçu');
                }
            });
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (sliderId) => {
            const slider = allSliders.find(s => s.id === sliderId);
            
            if (!slider) {
                showAlert('danger', 'Slider non trouvé');
                return;
            }
            
            sliderToDelete = slider;
            
            // Format date
            const createdDate = new Date(slider.created_at);
            const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            document.getElementById('sliderToDeleteInfo').innerHTML = `
                <div class="slider-info">
                    <div class="slider-info-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <div class="slider-info-name">${slider.name}</div>
                        <div class="slider-info-type">
                            <span class="badge ${slider.type === 'image' ? 'bg-info' : 'bg-warning'}">
                                ${slider.type === 'image' ? 'Image' : 'Vidéo'}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row small text-muted">
                    <div class="col-6">
                        <div><strong>ID:</strong> ${slider.id}</div>
                        <div><strong>Ordre:</strong> ${slider.order}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Créé le:</strong> ${formattedDate}</div>
                        <div><strong>Statut:</strong> ${slider.is_active ? 'Actif' : 'Inactif'}</div>
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

        // Show restore confirmation modal
        const showRestoreConfirmation = (sliderId) => {
            const slider = allSliders.find(s => s.id === sliderId);
            
            if (!slider) {
                showAlert('danger', 'Slider non trouvé');
                return;
            }
            
            sliderToRestore = slider;
            
            document.getElementById('sliderToRestoreInfo').innerHTML = `
                <div class="slider-info">
                    <div class="slider-info-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <div class="slider-info-name">${slider.name}</div>
                        <div class="slider-info-type">
                            <span class="badge ${slider.type === 'image' ? 'bg-info' : 'bg-warning'}">
                                ${slider.type === 'image' ? 'Image' : 'Vidéo'}
                            </span>
                        </div>
                    </div>
                </div>
            `;
            
            // Show modal
            const restoreModal = new bootstrap.Modal(document.getElementById('restoreConfirmationModal'));
            restoreModal.show();
        };

        // Delete slider
        const deleteSlider = () => {
            if (!sliderToDelete) {
                showAlert('danger', 'Aucun slider à supprimer');
                return;
            }
            
            const sliderId = sliderToDelete.id;
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
            
            $.ajax({
                url: `/sliders/${sliderId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Slider supprimé avec succès !');
                        
                        // Reload sliders
                        loadSliders(currentPage, currentFilters);
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la suppression du slider');
                    }
                },
                error: function(xhr) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Slider non trouvé');
                        loadSliders(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression du slider');
                    }
                },
                complete: function() {
                    sliderToDelete = null;
                }
            });
        };

        // Restore slider
        const restoreSlider = () => {
            if (!sliderToRestore) {
                showAlert('danger', 'Aucun slider à restaurer');
                return;
            }
            
            const sliderId = sliderToRestore.id;
            const restoreBtn = document.getElementById('confirmRestoreBtn');
            
            // Show processing animation
            restoreBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-undo me-2"></i>Restaurer
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Restauration...</span>
                </div>
                Restauration en cours...
            `;
            restoreBtn.disabled = true;
            
            $.ajax({
                url: `/sliders/${sliderId}/restore`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const restoreModal = bootstrap.Modal.getInstance(document.getElementById('restoreConfirmationModal'));
                    restoreModal.hide();
                    
                    if (response.success) {
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Slider restauré avec succès !');
                        
                        // Reload sliders
                        loadSliders(currentPage, currentFilters);
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la restauration du slider');
                    }
                },
                error: function() {
                    const restoreModal = bootstrap.Modal.getInstance(document.getElementById('restoreConfirmationModal'));
                    restoreModal.hide();
                    showAlert('danger', 'Erreur lors de la restauration du slider');
                },
                complete: function() {
                    sliderToRestore = null;
                }
            });
        };

        // Toggle slider status
        const toggleSliderStatus = (sliderId) => {
            $.ajax({
                url: `/sliders/${sliderId}/toggle-status`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message || 'Statut modifié avec succès !');
                        loadSliders(currentPage, currentFilters);
                        loadStatistics();
                    } else {
                        showAlert('danger', response.message || 'Erreur lors du changement de statut');
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors du changement de statut');
                }
            });
        };

        // Store slider
        const storeSlider = () => {
            const form = document.getElementById('createSliderForm');
            const submitBtn = document.getElementById('submitSliderBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Validate video URL if needed
            const type = document.getElementById('sliderType').value;
            const videoType = document.getElementById('videoType').value;
            const videoUrl = document.getElementById('videoUrl').value;
            
            if (type === 'video' && (videoType === 'youtube' || videoType === 'vimeo') && !videoUrl) {
                showAlert('danger', 'Veuillez entrer l\'URL de la vidéo');
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Créer le slider
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Création en cours...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            $.ajax({
                url: '{{ route("sliders.store") }}',
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
                            <i class="fas fa-save me-2"></i>Créer le slider
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createSliderModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        document.getElementById('imagePreview').style.display = 'none';
                        document.getElementById('videoThumbnailPreview').style.display = 'none';
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Reload sliders
                        loadSliders(1, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Slider créé avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création du slider');
                    }
                },
                error: function(xhr) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le slider
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
                        showAlert('danger', 'Erreur lors de la création du slider');
                    }
                }
            });
        };

        // Update slider
        const updateSlider = () => {
            const form = document.getElementById('editSliderForm');
            const submitBtn = document.getElementById('updateSliderBtn');
            const sliderId = document.getElementById('editSliderId').value;
            
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
            
            $.ajax({
                url: `/sliders/${sliderId}`,
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editSliderModal'));
                        modal.hide();
                        
                        // Reload sliders
                        loadSliders(currentPage, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Slider mis à jour avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la mise à jour du slider');
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
                        showAlert('danger', 'Erreur lors de la mise à jour du slider');
                    }
                }
            });
        };

        // Open edit modal
        const openEditModal = (sliderId) => {
            const slider = allSliders.find(s => s.id === sliderId);
            
            if (!slider) {
                showAlert('danger', 'Slider non trouvé');
                return;
            }
            
            // Set form values
            document.getElementById('editSliderId').value = slider.id;
            document.getElementById('editSliderName').value = slider.name;
            document.getElementById('editSliderDescription').value = slider.description || '';
            document.getElementById('editSliderType').value = slider.type;
            document.getElementById('editSliderOrder').value = slider.order;
            document.getElementById('editButtonText').value = slider.button_text || '';
            document.getElementById('editButtonUrl').value = slider.button_url || '';
            document.getElementById('editSliderIsActive').checked = slider.is_active;
            
            // Set video type if applicable
            if (slider.type === 'video') {
                document.getElementById('editVideoType').value = slider.video_type || 'youtube';
                document.getElementById('editVideoUrl').value = slider.video_url || '';
                
                // Show current video info
                const currentVideoPreview = document.getElementById('currentVideoPreview');
                if (slider.video_type === 'youtube' || slider.video_type === 'vimeo') {
                    currentVideoPreview.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-link me-2"></i>
                            <strong>URL actuelle:</strong> ${slider.video_url || 'Non définie'}
                        </div>
                    `;
                } else if (slider.video_type === 'upload' && slider.video_path) {
                    currentVideoPreview.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-file-video me-2"></i>
                            <strong>Vidéo uploadée:</strong> ${slider.video_path.split('/').pop()}
                        </div>
                    `;
                }
                
                // Show current thumbnail
                const currentThumbnailPreview = document.getElementById('currentThumbnailPreview');
                if (slider.image_path) {
                    currentThumbnailPreview.innerHTML = `
                        <img src="/storage/${slider.image_path}" alt="Thumbnail actuel" class="img-thumbnail" style="max-width: 200px;">
                    `;
                } else {
                    currentThumbnailPreview.innerHTML = '<div class="text-muted">Aucun thumbnail défini</div>';
                }
            } else {
                // Show current image
                const currentImagePreview = document.getElementById('currentImagePreview');
                if (slider.image_path) {
                    currentImagePreview.innerHTML = `
                        <img src="/storage/${slider.image_path}" alt="Image actuelle" class="img-thumbnail" style="max-width: 300px;">
                    `;
                }
            }
            
            // Toggle sections based on type
            toggleEditSections(slider.type);
            
            // Show modal
            const editModal = new bootstrap.Modal(document.getElementById('editSliderModal'));
            editModal.show();
        };

        // Setup image preview
        const setupImagePreview = () => {
            // Create modal image preview
            const createImageInput = document.getElementById('sliderImage');
            const createPreview = document.getElementById('previewImage');
            const createPreviewContainer = document.getElementById('imagePreview');
            
            if (createImageInput) {
                createImageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            createPreview.src = e.target.result;
                            createPreviewContainer.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        createPreviewContainer.style.display = 'none';
                    }
                });
            }
            
            // Create modal video thumbnail preview
            const videoThumbnailInput = document.getElementById('videoThumbnail');
            const videoThumbnailPreview = document.getElementById('previewVideoThumbnail');
            const videoThumbnailContainer = document.getElementById('videoThumbnailPreview');
            
            if (videoThumbnailInput) {
                videoThumbnailInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            videoThumbnailPreview.src = e.target.result;
                            videoThumbnailContainer.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        videoThumbnailContainer.style.display = 'none';
                    }
                });
            }
            
            // Edit modal image preview
            const editImageInput = document.getElementById('editSliderImage');
            const editPreview = document.getElementById('previewEditImage');
            const editPreviewContainer = document.getElementById('editImagePreview');
            
            if (editImageInput) {
                editImageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            editPreview.src = e.target.result;
                            editPreviewContainer.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        editPreviewContainer.style.display = 'none';
                    }
                });
            }
        };

        // Setup video type toggle
        const setupVideoTypeToggle = () => {
            // Create modal type toggle
            const sliderType = document.getElementById('sliderType');
            const imageUploadSection = document.getElementById('imageUploadSection');
            const videoUploadSection = document.getElementById('videoUploadSection');
            
            if (sliderType) {
                sliderType.addEventListener('change', function() {
                    if (this.value === 'image') {
                        imageUploadSection.style.display = 'block';
                        videoUploadSection.style.display = 'none';
                        document.getElementById('sliderImage').required = true;
                    } else {
                        imageUploadSection.style.display = 'none';
                        videoUploadSection.style.display = 'block';
                        document.getElementById('sliderImage').required = false;
                        toggleVideoType();
                    }
                });
            }
            
            // Create modal video type toggle
            const videoType = document.getElementById('videoType');
            
            if (videoType) {
                videoType.addEventListener('change', toggleVideoType);
            }
            
            // Edit modal type toggle
            const editSliderType = document.getElementById('editSliderType');
            const editImageUploadSection = document.getElementById('editImageUploadSection');
            const editVideoUploadSection = document.getElementById('editVideoUploadSection');
            const currentImageSection = document.getElementById('currentImageSection');
            
            if (editSliderType) {
                editSliderType.addEventListener('change', function() {
                    if (this.value === 'image') {
                        editImageUploadSection.style.display = 'block';
                        editVideoUploadSection.style.display = 'none';
                        currentImageSection.style.display = 'block';
                        document.getElementById('currentVideoSection').style.display = 'none';
                    } else {
                        editImageUploadSection.style.display = 'none';
                        editVideoUploadSection.style.display = 'block';
                        currentImageSection.style.display = 'none';
                        document.getElementById('currentVideoSection').style.display = 'block';
                        toggleEditVideoType();
                    }
                });
            }
            
            // Edit modal video type toggle
            const editVideoType = document.getElementById('editVideoType');
            
            if (editVideoType) {
                editVideoType.addEventListener('change', toggleEditVideoType);
            }
        };

        // Toggle video type sections (create modal)
        const toggleVideoType = () => {
            const videoType = document.getElementById('videoType').value;
            const videoUrlSection = document.getElementById('videoUrlSection');
            const videoFileSection = document.getElementById('videoFileSection');
            
            if (videoType === 'upload') {
                videoUrlSection.style.display = 'none';
                videoFileSection.style.display = 'block';
                document.getElementById('videoFile').required = true;
                document.getElementById('videoUrl').required = false;
            } else {
                videoUrlSection.style.display = 'block';
                videoFileSection.style.display = 'none';
                document.getElementById('videoFile').required = false;
                document.getElementById('videoUrl').required = true;
            }
        };

        // Toggle edit video type sections (edit modal)
        const toggleEditVideoType = () => {
            const videoType = document.getElementById('editVideoType').value;
            const editVideoUrlSection = document.getElementById('editVideoUrlSection');
            const editVideoFileSection = document.getElementById('editVideoFileSection');
            
            if (videoType === 'upload') {
                editVideoUrlSection.style.display = 'none';
                editVideoFileSection.style.display = 'block';
            } else {
                editVideoUrlSection.style.display = 'block';
                editVideoFileSection.style.display = 'none';
            }
        };

        // Toggle edit sections
        const toggleEditSections = (type) => {
            const editImageUploadSection = document.getElementById('editImageUploadSection');
            const editVideoUploadSection = document.getElementById('editVideoUploadSection');
            const currentImageSection = document.getElementById('currentImageSection');
            const currentVideoSection = document.getElementById('currentVideoSection');
            
            if (type === 'image') {
                editImageUploadSection.style.display = 'block';
                editVideoUploadSection.style.display = 'none';
                currentImageSection.style.display = 'block';
                currentVideoSection.style.display = 'none';
            } else {
                editImageUploadSection.style.display = 'none';
                editVideoUploadSection.style.display = 'block';
                currentImageSection.style.display = 'none';
                currentVideoSection.style.display = 'block';
            }
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} slider${response.total > 1 ? 's' : ''}`;
            
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
            loadSliders(page, currentFilters);
        };

        // Format time ago
        const formatTimeAgo = (date) => {
            const now = new Date();
            const diffMs = now - date;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            
            if (diffDays === 0) return "Aujourd'hui";
            if (diffDays === 1) return 'Hier';
            if (diffDays < 7) return `Il y a ${diffDays} jours`;
            if (diffDays < 30) return `Il y a ${Math.floor(diffDays / 7)} semaines`;
            if (diffDays < 365) return `Il y a ${Math.floor(diffDays / 30)} mois`;
            return `Il y a ${Math.floor(diffDays / 365)} ans`;
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
                        loadSliders(1, currentFilters);
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
                        status: document.getElementById('filterStatus').value,
                        type: document.getElementById('filterType').value,
                        date_from: document.getElementById('filterDateFrom').value,
                        date_to: document.getElementById('filterDateTo').value
                    };
                    loadSliders(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterType').value = '';
                    document.getElementById('filterDateFrom').value = '';
                    document.getElementById('filterDateTo').value = '';
                    currentFilters = {};
                    loadSliders(1);
                });
            }
            
            // Submit slider form
            const submitSliderBtn = document.getElementById('submitSliderBtn');
            if (submitSliderBtn) {
                submitSliderBtn.addEventListener('click', storeSlider);
            }
            
            // Update slider form
            const updateSliderBtn = document.getElementById('updateSliderBtn');
            if (updateSliderBtn) {
                updateSliderBtn.addEventListener('click', updateSlider);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteSlider);
            }
            
            // Confirm restore button
            const confirmRestoreBtn = document.getElementById('confirmRestoreBtn');
            if (confirmRestoreBtn) {
                confirmRestoreBtn.addEventListener('click', restoreSlider);
            }
            
            // Toggle order view
            const toggleOrderViewBtn = document.getElementById('toggleOrderView');
            if (toggleOrderViewBtn) {
                toggleOrderViewBtn.addEventListener('click', toggleOrderView);
            }
            
            // Save order buttons
            const saveOrderBtn = document.getElementById('saveOrderBtn');
            const saveOrderBtn2 = document.getElementById('saveOrderBtn2');
            if (saveOrderBtn) {
                saveOrderBtn.addEventListener('click', saveOrder);
            }
            if (saveOrderBtn2) {
                saveOrderBtn2.addEventListener('click', saveOrder);
            }
            
            // Cancel order button
            const cancelOrderBtn = document.getElementById('cancelOrderBtn');
            if (cancelOrderBtn) {
                cancelOrderBtn.addEventListener('click', cancelOrder);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    sliderToDelete = null;
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                });
            }
            
            // Reset restore modal when hidden
            const restoreModal = document.getElementById('restoreConfirmationModal');
            if (restoreModal) {
                restoreModal.addEventListener('hidden.bs.modal', function() {
                    sliderToRestore = null;
                });
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createSliderModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createSliderForm').reset();
                    document.getElementById('imagePreview').style.display = 'none';
                    document.getElementById('videoThumbnailPreview').style.display = 'none';
                    document.getElementById('imageUploadSection').style.display = 'block';
                    document.getElementById('videoUploadSection').style.display = 'none';
                    
                    const submitBtn = document.getElementById('submitSliderBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le slider
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editSliderModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    const submitBtn = document.getElementById('updateSliderBtn');
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
</style>
@endsection