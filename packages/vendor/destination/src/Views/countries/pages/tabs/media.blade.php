<!-- Dans votre template Blade existant, remplacez le contenu du tab-pane media-tab-pane -->

<!-- MEDIA TAB -->
<div class="tab-pane fade" id="media-tab-pane" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-images me-2"></i>Bibliothèque des Médias
                    @if($country)
                    <small class="text-muted">pour {{ $country->name }}</small>
                    @endif
                </h5>
                <div>
                    <button class="btn btn-outline-secondary me-2" id="toggleMediaFilterBtn">
                        <i class="fas fa-sliders-h me-2"></i>Filtres
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMediaModal">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter un média
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Filter Section -->
            <div class="card mb-4" id="mediaFilterSection" style="display: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Filtres Médias</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-2" id="clearMediaFiltersBtn">
                                <i class="fas fa-times me-1"></i>Effacer
                            </button>
                            <button class="btn btn-sm btn-primary" id="applyMediaFiltersBtn">
                                <i class="fas fa-check me-1"></i>Appliquer
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="mediaFilterType" class="form-label">Type</label>
                            <select class="form-select" id="mediaFilterType">
                                <option value="">Tous les types</option>
                                <option value="image">Images</option>
                                <option value="video_local">Vidéos locales</option>
                                <option value="video_youtube">YouTube</option>
                                <option value="video_vimeo">Vimeo</option>
                                <option value="video_dailymotion">Dailymotion</option>
                                <option value="video_other">Autres vidéos</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="mediaFilterStatus" class="form-label">Statut</label>
                            <select class="form-select" id="mediaFilterStatus">
                                <option value="">Tous</option>
                                <option value="active">Actifs</option>
                                <option value="inactive">Inactifs</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="mediaFilterFeatured" class="form-label">À la une</label>
                            <select class="form-select" id="mediaFilterFeatured">
                                <option value="">Tous</option>
                                <option value="featured">À la une</option>
                                <option value="not_featured">Pas à la une</option>
                            </select>
                        </div>
                        @if(!$country)
                        <div class="col-md-3 mb-3">
                            <label for="mediaFilterCountry" class="form-label">Pays</label>
                            <select class="form-select" id="mediaFilterCountry">
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
                            <label for="mediaFilterActivity" class="form-label">Activité</label>
                            <select class="form-select" id="mediaFilterActivity">
                                <option value="">Toutes les activités</option>
                                @foreach($activities ?? [] as $activity)
                                <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mediaFilterSortBy" class="form-label">Trier par</label>
                            <select class="form-select" id="mediaFilterSortBy">
                                <option value="created_at">Date d'ajout</option>
                                <option value="title">Titre</option>
                                <option value="file_size">Taille</option>
                                <option value="sort_order">Ordre manuel</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" id="totalMedias">0</h3>
                                    <p class="text-muted mb-0">Total</p>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-images text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" id="imageMedias">0</h3>
                                    <p class="text-muted mb-0">Images</p>
                                </div>
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-image text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" id="videoMedias">0</h3>
                                    <p class="text-muted mb-0">Vidéos</p>
                                </div>
                                <div class="bg-success bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-video text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" id="activeMedias">0</h3>
                                    <p class="text-muted mb-0">Actifs</p>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-check-circle text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" id="featuredMedias">0</h3>
                                    <p class="text-muted mb-0">À la une</p>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-star text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" id="storageSize">0MB</h3>
                                    <p class="text-muted mb-0">Stockage</p>
                                </div>
                                <div class="bg-secondary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-hdd text-secondary"></i>
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
                    <input type="text" class="form-control" placeholder="Rechercher des médias par titre, description..." id="mediaSearchInput">
                    <button class="btn btn-outline-secondary" type="button" id="clearMediaSearchBtn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading Spinner -->
            <div class="text-center py-5" id="mediaLoadingSpinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
            
            <!-- Media Grid Container -->
            <div id="mediaGridContainer" style="display: none;">
                <!-- View Toggle -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="viewMode" id="gridView" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="gridView">
                            <i class="fas fa-th-large"></i> Grille
                        </label>
                        <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off">
                        <label class="btn btn-outline-primary" for="listView">
                            <i class="fas fa-list"></i> Liste
                        </label>
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="selectAllMedias">
                        <label class="form-check-label" for="selectAllMedias">Tout sélectionner</label>
                    </div>
                </div>
                
                <!-- Grid View -->
                <div class="row g-3" id="mediaGridView">
                    <!-- Médias chargés via AJAX -->
                </div>
                
                <!-- List View -->
                <div class="table-responsive d-none" id="mediaListView">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="selectAllMediasList">
                                </th>
                                <th>Média</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Taille</th>
                                <th>Statut</th>
                                <th>À la une</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="mediaListBody">
                            <!-- Médias chargés via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Bulk Actions -->
                <div class="card mt-3" id="bulkActionsCard" style="display: none;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span id="selectedCount">0</span> média(s) sélectionné(s)
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    Actions groupées
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="bulkUpdateStatus(true)"><i class="fas fa-check me-2"></i>Activer</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="bulkUpdateStatus(false)"><i class="fas fa-times me-2"></i>Désactiver</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="bulkUpdateFeatured(true)"><i class="fas fa-star me-2"></i>Mettre à la une</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="bulkUpdateFeatured(false)"><i class="fas fa-star-o me-2"></i>Retirer de la une</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="bulkDeleteMedias()"><i class="fas fa-trash me-2"></i>Supprimer</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Empty State -->
            <div class="text-center py-5" id="mediaEmptyState" style="display: none;">
                <div class="mb-3">
                    <i class="fas fa-images fa-3x text-muted"></i>
                </div>
                <h4 class="text-muted">Aucun média trouvé</h4>
                <p class="text-muted mb-4">Commencez par ajouter votre premier média.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMediaModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un média
                </button>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer" id="mediaPaginationContainer" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <div id="mediaPaginationInfo"></div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0" id="mediaPagination">
                        <!-- Pagination will be loaded here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- ADD MEDIA MODAL -->
<div class="modal fade" id="addMediaModal" tabindex="-1" aria-labelledby="addMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMediaModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un média
                    @if($country)
                    <small class="text-muted">pour {{ $country->name }}</small>
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addMediaForm" enctype="multipart/form-data">
                    @csrf
                    
                    @if($country)
                    <input type="hidden" name="country_id" value="{{ $country->id }}">
                    @endif
                    
                    <!-- Type Selection -->
                    <div class="mb-4">
                        <label class="form-label">Type de média *</label>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-check media-type-option">
                                    <input class="form-check-input" type="radio" name="type" id="typeImage" value="image" checked>
                                    <label class="form-check-label" for="typeImage">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <i class="fas fa-image fa-2x mb-2 text-primary"></i>
                                                <h6 class="card-title">Image</h6>
                                                <small class="text-muted">JPG, PNG, GIF</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check media-type-option">
                                    <input class="form-check-input" type="radio" name="type" id="typeVideoLocal" value="video_local">
                                    <label class="form-check-label" for="typeVideoLocal">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <i class="fas fa-file-video fa-2x mb-2 text-success"></i>
                                                <h6 class="card-title">Vidéo locale</h6>
                                                <small class="text-muted">MP4, AVI, MOV</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check media-type-option">
                                    <input class="form-check-input" type="radio" name="type" id="typeYouTube" value="video_youtube">
                                    <label class="form-check-label" for="typeYouTube">
                                        <div class="card text-center bg-danger bg-opacity-10">
                                            <div class="card-body">
                                                <i class="fab fa-youtube fa-2x mb-2 text-danger"></i>
                                                <h6 class="card-title">YouTube</h6>
                                                <small class="text-muted">Lien YouTube</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check media-type-option">
                                    <input class="form-check-input" type="radio" name="type" id="typeVimeo" value="video_vimeo">
                                    <label class="form-check-label" for="typeVimeo">
                                        <div class="card text-center bg-info bg-opacity-10">
                                            <div class="card-body">
                                                <i class="fab fa-vimeo-v fa-2x mb-2 text-info"></i>
                                                <h6 class="card-title">Vimeo</h6>
                                                <small class="text-muted">Lien Vimeo</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content based on type -->
                    <div id="imageUploadSection">
                        <div class="mb-3">
                            <label for="imageFile" class="form-label">Image *</label>
                            <div class="border rounded p-4 text-center upload-area" id="imageUploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5>Glissez-déposez votre image ici</h5>
                                <p class="text-muted">ou cliquez pour parcourir</p>
                                <input type="file" class="d-none" id="imageFile" name="image_file" accept="image/*">
                                <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('imageFile').click()">
                                    <i class="fas fa-folder-open me-2"></i>Parcourir
                                </button>
                            </div>
                            <div class="form-text">Formats supportés: JPG, PNG, GIF, WebP (Max: 50MB)</div>
                            <div class="mt-2" id="imagePreview" style="display: none;">
                                <img id="previewImage" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                <button type="button" class="btn btn-sm btn-danger ms-2" onclick="clearImagePreview()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="videoLocalUploadSection" style="display: none;">
                        <div class="mb-3">
                            <label for="videoFile" class="form-label">Fichier vidéo *</label>
                            <div class="border rounded p-4 text-center upload-area" id="videoUploadArea">
                                <i class="fas fa-file-video fa-3x text-muted mb-3"></i>
                                <h5>Glissez-déposez votre vidéo ici</h5>
                                <p class="text-muted">ou cliquez pour parcourir</p>
                                <input type="file" class="d-none" id="videoFile" name="video_file" accept="video/*">
                                <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('videoFile').click()">
                                    <i class="fas fa-folder-open me-2"></i>Parcourir
                                </button>
                            </div>
                            <div class="form-text">Formats supportés: MP4, AVI, MOV, WMV, FLV, MKV (Max: 500MB)</div>
                            <div class="mt-2" id="videoPreview" style="display: none;">
                                <video id="previewVideo" class="img-thumbnail" style="max-width: 200px; max-height: 150px;" controls>
                                    Votre navigateur ne supporte pas la lecture vidéo.
                                </video>
                                <button type="button" class="btn btn-sm btn-danger ms-2" onclick="clearVideoPreview()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="videoUrlSection" style="display: none;">
                        <div class="mb-3">
                            <label for="videoUrl" class="form-label">URL de la vidéo *</label>
                            <input type="url" class="form-control" id="videoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=... ou https://vimeo.com/...">
                            <div class="form-text">Collez l'URL complète de la vidéo YouTube ou Vimeo</div>
                            <div class="mt-2" id="videoUrlPreview" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    L'URL sera validée et une miniature sera automatiquement générée.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="videoUrl" class="form-label">Image de la vidéo *</label>
                            <input type="file" class="form-control" id="videoImage" name="video_image" accept="image/*">
                           
                        </div>
                    </div>
                    
                    <!-- Common Fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="mediaTitle" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="mediaTitle" name="title" placeholder="Titre du média">
                        </div>
                        <div class="col-md-6">
                            <label for="mediaAltText" class="form-label">Texte alternatif (SEO)</label>
                            <input type="text" class="form-control" id="mediaAltText" name="alt_text" placeholder="Description pour le SEO">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mediaDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="mediaDescription" name="description" rows="3" placeholder="Description détaillée du média..."></textarea>
                    </div>
                    
                    @if(!$country)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="mediaCountry" class="form-label">Pays</label>
                            <select class="form-select" id="mediaCountry" name="country_id" required>
                                <option value="">Sélectionnez un pays</option>
                                @foreach($countries ?? [] as $countryItem)
                                <option value="{{ $countryItem->id }}">{{ $countryItem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="mediaActivity" class="form-label">Activité associée (optionnel)</label>
                            <select class="form-select" id="mediaActivity" name="activity_id">
                                <option value="">Aucune activité spécifique</option>
                                @foreach($activities ?? [] as $activity)
                                <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="mediaActivity" class="form-label">Activité associée (optionnel)</label>
                        <select class="form-select" id="mediaActivity" name="activity_id">
                            <option value="">Aucune activité spécifique</option>
                            @foreach($activities ?? [] as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="mediaTags" class="form-label">Tags (séparés par des virgules)</label>
                            <input type="text" class="form-control" id="mediaTags" name="tags" placeholder="Ex: paysage, nature, tourisme, culture">
                        </div>
                        <div class="col-md-3">
                            <label for="mediaIsFeatured" class="form-label">À la une</label>
                            <select class="form-select" id="mediaIsFeatured" name="is_featured">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="mediaIsActive" class="form-label">Statut</label>
                            <select class="form-select" id="mediaIsActive" name="is_active">
                                <option value="1" selected>Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="submitMediaBtn">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter le média
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT MEDIA MODAL -->
<div class="modal fade" id="editMediaModal" tabindex="-1" aria-labelledby="editMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMediaModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier le média
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMediaForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editMediaId" name="id">
                    
                    <!-- Current Media Preview -->
                    <div class="mb-3" id="currentMediaPreviewContainer">
                        <!-- Chargé dynamiquement -->
                    </div>
                    
                    <!-- Type Selection (disabled for editing) -->
                    <div class="mb-3">
                        <label class="form-label">Type de média</label>
                        <input type="text" class="form-control" id="editMediaTypeDisplay" readonly>
                        <input type="hidden" id="editMediaType" name="type">
                    </div>
                    
                    <!-- Content based on type -->
                    <div id="editImageUploadSection" style="display: none;">
                        <div class="mb-3">
                            <label for="editImageFile" class="form-label">Nouvelle image (optionnel)</label>
                            <div class="border rounded p-4 text-center upload-area" id="editImageUploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5>Glissez-déposez une nouvelle image</h5>
                                <p class="text-muted">ou cliquez pour parcourir</p>
                                <input type="file" class="d-none" id="editImageFile" name="image_file" accept="image/*">
                                <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('editImageFile').click()">
                                    <i class="fas fa-folder-open me-2"></i>Parcourir
                                </button>
                            </div>
                            <div class="form-text">Laissez vide pour conserver l'image actuelle</div>
                        </div>
                    </div>
                    
                    <div id="editVideoLocalUploadSection" style="display: none;">
                        <div class="mb-3">
                            <label for="editVideoFile" class="form-label">Nouvelle vidéo (optionnel)</label>
                            <div class="border rounded p-4 text-center upload-area" id="editVideoUploadArea">
                                <i class="fas fa-file-video fa-3x text-muted mb-3"></i>
                                <h5>Glissez-déposez une nouvelle vidéo</h5>
                                <p class="text-muted">ou cliquez pour parcourir</p>
                                <input type="file" class="d-none" id="editVideoFile" name="video_file" accept="video/*">
                                <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('editVideoFile').click()">
                                    <i class="fas fa-folder-open me-2"></i>Parcourir
                                </button>
                            </div>
                            <div class="form-text">Laissez vide pour conserver la vidéo actuelle</div>
                        </div>
                    </div>
                    
                    <div id="editVideoUrlSection" style="display: none;">
                        <div class="mb-3">
                            <label for="editVideoUrl" class="form-label">URL de la vidéo</label>
                            <input type="url" class="form-control" id="editVideoUrl" name="video_url">
                        </div>
                    </div>
                    
                    <!-- Common Fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editMediaTitle" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="editMediaTitle" name="title">
                        </div>
                        <div class="col-md-6">
                            <label for="editMediaAltText" class="form-label">Texte alternatif (SEO)</label>
                            <input type="text" class="form-control" id="editMediaAltText" name="alt_text">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editMediaDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editMediaDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editMediaActivity" class="form-label">Activité associée (optionnel)</label>
                            <select class="form-select" id="editMediaActivity" name="activity_id">
                                <option value="">Aucune activité spécifique</option>
                                @foreach($activities ?? [] as $activity)
                                <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editMediaTags" class="form-label">Tags (séparés par des virgules)</label>
                            <input type="text" class="form-control" id="editMediaTags" name="tags">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="editMediaIsFeatured" class="form-label">À la une</label>
                            <select class="form-select" id="editMediaIsFeatured" name="is_featured">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editMediaIsActive" class="form-label">Statut</label>
                            <select class="form-select" id="editMediaIsActive" name="is_active">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editMediaSortOrder" class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control" id="editMediaSortOrder" name="sort_order" min="0" value="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateMediaBtn">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE MEDIA MODAL -->
<div class="modal fade" id="deleteMediaModal" tabindex="-1" aria-labelledby="deleteMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMediaModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Supprimer le média
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce média ?</p>
                
                <div class="media-to-delete" id="mediaToDeleteInfo">
                    <!-- Media info will be loaded here -->
                </div>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Attention :</strong> Cette action supprimera également le fichier physique du serveur.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteMediaBtn">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- VIEW MEDIA MODAL -->
<div class="modal fade" id="viewMediaModal" tabindex="-1" aria-labelledby="viewMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMediaModalLabel">
                    <i class="fas fa-eye me-2"></i>Détails du média
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="viewMediaContent">
                    <!-- Media content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Media Management -->
<script>
// Configuration
let currentMediaPage = 1;
let currentMediaFilters = {};
let allMedias = [];
let selectedMedias = new Set();
let mediaToDelete = null;
const countrieId = {{ $country->id ?? 'null' }};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('media-tab-pane')) {
        // Load medias when media tab is shown
        document.getElementById('media-tab').addEventListener('shown.bs.tab', function() {
            loadMedias();
            loadMediaStatistics();
        });
        
        setupMediaEventListeners();
        setupMediaUploads();
    }
});

// Load medias
const loadMedias = (page = 1, filters = {}) => {
    showMediaLoading();
    
    const searchTerm = document.getElementById('mediaSearchInput')?.value || '';
    
    let url = '/countries/country-medias';
    const params = {
        page: page,
        search: searchTerm,
        ...filters
    };
    
    if (countrieId) {
        params.country_id = countrieId;
    }
    
    $.ajax({
        url: url,
        type: 'GET',
        data: params,
        success: function(response) {
            if (response.success) {
                allMedias = response.data.data || [];
                renderMedias(allMedias);
                renderMediaPagination(response.data);
                hideMediaLoading();
            } else {
                showAlerts('danger', 'Erreur lors du chargement des médias');
            }
        },
        error: function(xhr) {
            hideMediaLoading();
            showAlerts('danger', 'Erreur de connexion au serveur');
            console.error('Error:', xhr.responseText);
        }
    });
};

// Load media statistics
const loadMediaStatistics = () => {
    let url = '/countries/country-medias/statistics';
    const data = {};
    
    if (countrieId) {
        data.country_id = countrieId;
    }
    
    $.ajax({
        url: url,
        type: 'GET',
        data: data,
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                document.getElementById('totalMedias').textContent = stats.total || 0;
                document.getElementById('imageMedias').textContent = stats.images || 0;
                document.getElementById('videoMedias').textContent = stats.videos || 0;
                document.getElementById('activeMedias').textContent = stats.active || 0;
                document.getElementById('featuredMedias').textContent = stats.featured || 0;
                
                // Calculate storage size (simplified)
                const totalSizeMB = (stats.total * 5).toFixed(1); // Assuming average 5MB per file
                document.getElementById('storageSize').textContent = totalSizeMB + 'MB';
            }
        },
        error: function(xhr, status, error) {
            console.error('Media statistics AJAX error:', error);
        }
    });
};

// Render medias in grid view
const renderMedias = (medias) => {
    const gridContainer = document.getElementById('mediaGridView');
    const listContainer = document.getElementById('mediaListBody');
    
    gridContainer.innerHTML = '';
    listContainer.innerHTML = '';
    selectedMedias.clear();
    updateBulkActions();
    
    if (!medias || !Array.isArray(medias) || medias.length === 0) {
        document.getElementById('mediaEmptyState').style.display = 'block';
        document.getElementById('mediaGridContainer').style.display = 'none';
        document.getElementById('mediaPaginationContainer').style.display = 'none';
        return;
    }
    
    medias.forEach((media, index) => {
        // Grid View Item
        const gridItem = createMediaGridItem(media);
        gridContainer.appendChild(gridItem);
        
        // List View Item
        const listItem = createMediaListItem(media);
        listContainer.appendChild(listItem);
    });
    
    document.getElementById('mediaEmptyState').style.display = 'none';
    document.getElementById('mediaGridContainer').style.display = 'block';
    document.getElementById('mediaPaginationContainer').style.display = 'flex';
};

// Create grid item for media
const createMediaGridItem = (media) => {
    const col = document.createElement('div');
    col.className = 'col-md-3 col-sm-4 col-6';
    
    const isSelected = selectedMedias.has(media.id);
    const isVideo = media.type.startsWith('video_');
    const isFeatured = media.is_featured;
    const isActive = media.is_active;
    
    col.innerHTML = `
        <div class="card media-card ${isSelected ? 'selected' : ''} ${!isActive ? 'opacity-50' : ''}">
            <div class="card-img-top media-thumbnail" style="height: 150px; overflow: hidden; position: relative;">
                ${isVideo ? `
                    <div class="video-indicator">
                        <i class="fas fa-play-circle"></i>
                    </div>
                ` : ''}
                
                ${isFeatured ? `
                    <div class="featured-badge">
                        <i class="fas fa-star"></i> À la une
                    </div>
                ` : ''}
                
                <img src="/storage/${media.image_path || '/images/default-thumbnail.jpg'}" 
                     alt="${media.alt_text || media.title || 'Media'}"
                     class="img-fluid w-100 h-100 object-fit-cover">
                
                <div class="media-overlay">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input media-checkbox" 
                               data-media-id="${media.id}"
                               ${isSelected ? 'checked' : ''}
                               onchange="toggleMediaSelection(${media.id})">
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-light" onclick="viewMediaDetails(${media.id})" title="Voir">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-light" onclick="openEditMediaModal(${media.id})" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-light text-danger" onclick="showDeleteMediaConfirmation(${media.id})" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
                <h6 class="card-title mb-1 text-truncate" title="${media.title || 'Sans titre'}">
                    ${media.title || 'Sans titre'}
                </h6>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-${isVideo ? 'video' : 'image'} me-1"></i>
                        ${getMediaTypeLabel(media.type)}
                    </small>
                    <small class="text-${isActive ? 'success' : 'danger'}">
                        <i class="fas fa-circle"></i>
                    </small>
                </div>
            </div>
        </div>
    `;
    
    return col;
};

// Create list item for media
const createMediaListItem = (media) => {
    const isSelected = selectedMedias.has(media.id);
    const isVideo = media.type.startsWith('video_');
    const isFeatured = media.is_featured;
    const isActive = media.is_active;
    
    const row = document.createElement('tr');
    row.className = `${!isActive ? 'table-secondary' : ''}`;
    
    row.innerHTML = `
        <td>
            <input type="checkbox" class="form-check-input media-checkbox-list" 
                   data-media-id="${media.id}"
                   ${isSelected ? 'checked' : ''}
                   onchange="toggleMediaSelection(${media.id})">
        </td>
        <td>
            <div style="width: 60px; height: 40px; overflow: hidden;" class="rounded">
                <img src="/storage/${media.image_path || '/images/default-thumbnail.jpg'}" 
                     alt="${media.alt_text || media.title || 'Media'}"
                     class="img-fluid w-100 h-100 object-fit-cover"
                     >
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                ${isFeatured ? '<i class="fas fa-star text-warning me-1" title="À la une"></i>' : ''}
                <span class="${!isActive ? 'text-muted' : ''}">
                    ${media.title || 'Sans titre'}
                </span>
            </div>
            ${media.description ? `<small class="text-muted d-block">${media.description.substring(0, 50)}...</small>` : ''}
        </td>
        <td>
            <span class="badge bg-${getMediaTypeColor(media.type)}">
                ${getMediaTypeLabel(media.type)}
            </span>
        </td>
        <td>${media.file_size_formatted || 'N/A'}</td>
        <td>
            <span class="badge bg-${isActive ? 'success' : 'danger'}">
                ${isActive ? 'Actif' : 'Inactif'}
            </span>
        </td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" 
                       ${isFeatured ? 'checked' : ''}
                       onchange="toggleMediaFeatured(${media.id}, ${isFeatured})">
            </div>
        </td>
        <td>${formatDates(media.created_at)}</td>
        <td>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary" onclick="viewMediaDetails(${media.id})" title="Voir">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-outline-warning" onclick="openEditMediaModal(${media.id})" title="Modifier">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger" onclick="showDeleteMediaConfirmation(${media.id})" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    `;
    
    return row;
};

// Get media type label
const getMediaTypeLabel = (type) => {
    const labels = {
        'image': 'Image',
        'video_local': 'Vidéo locale',
        'video_youtube': 'YouTube',
        'video_vimeo': 'Vimeo',
        'video_dailymotion': 'Dailymotion',
        'video_other': 'Autre vidéo'
    };
    return labels[type] || type;
};

// Get media type color
const getMediaTypeColor = (type) => {
    const colors = {
        'image': 'primary',
        'video_local': 'success',
        'video_youtube': 'danger',
        'video_vimeo': 'info',
        'video_dailymotion': 'warning',
        'video_other': 'secondary'
    };
    return colors[type] || 'secondary';
};

// Toggle media selection
const toggleMediaSelection = (mediaId) => {
    if (selectedMedias.has(mediaId)) {
        selectedMedias.delete(mediaId);
    } else {
        selectedMedias.add(mediaId);
    }
    
    // Update checkboxes
    document.querySelectorAll(`.media-checkbox[data-media-id="${mediaId}"]`).forEach(cb => {
        cb.checked = selectedMedias.has(mediaId);
    });
    
    document.querySelectorAll(`.media-checkbox-list[data-media-id="${mediaId}"]`).forEach(cb => {
        cb.checked = selectedMedias.has(mediaId);
    });
    
    updateBulkActions();
};

// Update bulk actions display
const updateBulkActions = () => {
    const bulkActionsCard = document.getElementById('bulkActionsCard');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedMedias.size > 0) {
        selectedCount.textContent = selectedMedias.size;
        bulkActionsCard.style.display = 'block';
    } else {
        bulkActionsCard.style.display = 'none';
    }
    
    // Update select all checkboxes
    const allCheckboxes = document.querySelectorAll('.media-checkbox, .media-checkbox-list');
    const allChecked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(cb => cb.checked);
    
    document.getElementById('selectAllMedias').checked = allChecked;
    document.getElementById('selectAllMediasList').checked = allChecked;
};

// Select all medias
const selectAllMedias = () => {
    const selectAll = document.getElementById('selectAllMedias').checked;
    const selectAllList = document.getElementById('selectAllMediasList');
    
    selectAllList.checked = selectAll;
    
    if (selectAll) {
        allMedias.forEach(media => selectedMedias.add(media.id));
    } else {
        selectedMedias.clear();
    }
    
    // Update all checkboxes
    document.querySelectorAll('.media-checkbox, .media-checkbox-list').forEach(cb => {
        cb.checked = selectAll;
    });
    
    updateBulkActions();
};

// Bulk update status
const bulkUpdateStatus = (status) => {
    if (selectedMedias.size === 0) return;
    
    if (!confirm(`Voulez-vous vraiment ${status ? 'activer' : 'désactiver'} ${selectedMedias.size} média(s) ?`)) {
        return;
    }
    
    const mediaIds = Array.from(selectedMedias);
    
    // Send requests in parallel
    const requests = mediaIds.map(mediaId => {
        return $.ajax({
            url: `/countries/country-medias/${mediaId}/toggle-status`,
            type: 'POST',
            data: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    });
    
    Promise.all(requests)
        .then(responses => {
            showAlerts('success', `${selectedMedias.size} média(s) mis à jour avec succès`);
            selectedMedias.clear();
            loadMedias(currentMediaPage, currentMediaFilters);
            loadMediaStatistics();
        })
        .catch(error => {
            showAlerts('danger', 'Erreur lors de la mise à jour des médias');
        });
};

// Bulk update featured status
const bulkUpdateFeatured = (featured) => {
    if (selectedMedias.size === 0) return;
    
    if (!confirm(`Voulez-vous vraiment ${featured ? 'mettre à la une' : 'retirer de la une'} ${selectedMedias.size} média(s) ?`)) {
        return;
    }
    
    const mediaIds = Array.from(selectedMedias);
    
    // Send requests in parallel
    const requests = mediaIds.map(mediaId => {
        return $.ajax({
            url: `/countries/country-medias/${mediaId}/toggle-featured`,
            type: 'POST',
            data: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    });
    
    Promise.all(requests)
        .then(responses => {
            showAlerts('success', `${selectedMedias.size} média(s) mis à jour avec succès`);
            selectedMedias.clear();
            loadMedias(currentMediaPage, currentMediaFilters);
            loadMediaStatistics();
        })
        .catch(error => {
            showAlerts('danger', 'Erreur lors de la mise à jour des médias');
        });
};

// Bulk delete medias
const bulkDeleteMedias = () => {
    if (selectedMedias.size === 0) return;
    
    if (!confirm(`Voulez-vous vraiment supprimer ${selectedMedias.size} média(s) ? Cette action est irréversible.`)) {
        return;
    }
    
    const mediaIds = Array.from(selectedMedias);
    
    // Send requests in parallel
    const requests = mediaIds.map(mediaId => {
        return $.ajax({
            url: `/countries/country-medias/${mediaId}`,
            type: 'DELETE',
            data: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    });
    
    Promise.all(requests)
        .then(responses => {
            showAlerts('success', `${selectedMedias.size} média(s) supprimé(s) avec succès`);
            selectedMedias.clear();
            loadMedias(currentMediaPage, currentMediaFilters);
            loadMediaStatistics();
        })
        .catch(error => {
            showAlerts('danger', 'Erreur lors de la suppression des médias');
        });
};

// Toggle media featured status
const toggleMediaFeatured = (mediaId, currentFeatured) => {
    $.ajax({
        url: `/countries/country-medias/${mediaId}/toggle-featured`,
        type: 'POST',
        data: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function(response) {
            if (response.success) {
                showAlerts('success', `Média ${response.data.is_featured ? 'mis à la une' : 'retiré de la une'} avec succès`);
                loadMedias(currentMediaPage, currentMediaFilters);
                loadMediaStatistics();
            }
        },
        error: function(xhr, status, error) {
            showAlerts('danger', 'Erreur lors de la mise à jour du statut');
        }
    });
};

// View media details
const viewMediaDetails = (mediaId) => {
    $.ajax({
        url: `/countries/country-medias/${mediaId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const media = response.data;
                const content = document.getElementById('viewMediaContent');
                
                let mediaHtml = '';
                
                if (media.type === 'image') {
                    mediaHtml = `
                        <div class="text-center mb-4">
                            <img src="${media.image_url}" 
                                 alt="${media.alt_text || media.title}"
                                 class="img-fluid rounded" 
                                 style="max-height: 500px;">
                        </div>
                    `;
                } else if (media.type === 'video_local') {
                    mediaHtml = `
                        <div class="text-center mb-4">
                            <video controls class="w-100 rounded" style="max-height: 500px;">
                                <source src="${media.video_url}" type="${media.mime_type}">
                                Votre navigateur ne supporte pas la lecture vidéo.
                            </video>
                        </div>
                    `;
                } else if (media.type === 'video_youtube' && media.video_id) {
                    mediaHtml = `
                        <div class="ratio ratio-16x9 mb-4">
                            <iframe src="https://www.youtube.com/embed/${media.video_id}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                    `;
                } else if (media.type === 'video_vimeo' && media.video_id) {
                    mediaHtml = `
                        <div class="ratio ratio-16x9 mb-4">
                            <iframe src="https://player.vimeo.com/video/${media.video_id}" 
                                    frameborder="0" 
                                    allow="autoplay; fullscreen; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                    `;
                } else {
                    mediaHtml = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Prévisualisation non disponible pour ce type de média.
                        </div>
                    `;
                }
                
                mediaHtml += `
                    <div class="row">
                        <div class="col-md-8">
                            <h4>${media.title || 'Sans titre'}</h4>
                            ${media.description ? `<p class="text-muted">${media.description}</p>` : ''}
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6>Informations</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Type:</strong> ${getMediaTypeLabel(media.type)}</li>
                                        <li><strong>Taille:</strong> ${media.file_size_formatted}</li>
                                        ${media.duration ? `<li><strong>Durée:</strong> ${media.duration_formatted}</li>` : ''}
                                        <li><strong>Ajouté le:</strong> ${formatDates(media.created_at)}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Statut</h6>
                                    <ul class="list-unstyled">
                                        <li>
                                            <strong>Statut:</strong> 
                                            <span class="badge bg-${media.is_active ? 'success' : 'danger'}">
                                                ${media.is_active ? 'Actif' : 'Inactif'}
                                            </span>
                                        </li>
                                        <li>
                                            <strong>À la une:</strong> 
                                            <span class="badge bg-${media.is_featured ? 'warning' : 'secondary'}">
                                                ${media.is_featured ? 'Oui' : 'Non'}
                                            </span>
                                        </li>
                                        <li><strong>Ordre:</strong> ${media.sort_order}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Métadonnées</h6>
                                    ${media.alt_text ? `<p><strong>Texte alternatif:</strong> ${media.alt_text}</p>` : ''}
                                    
                                    ${media.tags && media.tags.length > 0 ? `
                                        <p><strong>Tags:</strong></p>
                                        <div class="d-flex flex-wrap gap-1">
                                            ${media.tags.map(tag => `<span class="badge bg-light text-dark">${tag}</span>`).join('')}
                                        </div>
                                    ` : ''}
                                    
                                    ${media.country ? `
                                        <p class="mt-3"><strong>Pays:</strong> ${media.country.name}</p>
                                    ` : ''}
                                    
                                    ${media.activity ? `
                                        <p><strong>Activité:</strong> ${media.activity.name}</p>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                content.innerHTML = mediaHtml;
                new bootstrap.Modal(document.getElementById('viewMediaModal')).show();
            }
        },
        error: function(xhr, status, error) {
            showAlerts('danger', 'Erreur lors du chargement des détails du média');
        }
    });
};

// Open edit media modal
const openEditMediaModal = (mediaId) => {
    $.ajax({
        url: `/countries/country-medias/${mediaId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const media = response.data;
                
                document.getElementById('editMediaId').value = media.id;
                document.getElementById('editMediaType').value = media.type;
                document.getElementById('editMediaTypeDisplay').value = getMediaTypeLabel(media.type);
                document.getElementById('editMediaTitle').value = media.title || '';
                document.getElementById('editMediaAltText').value = media.alt_text || '';
                document.getElementById('editMediaDescription').value = media.description || '';
                document.getElementById('editMediaTags').value = media.tags ? media.tags.join(', ') : '';
                document.getElementById('editMediaIsFeatured').value = media.is_featured ? '1' : '0';
                document.getElementById('editMediaIsActive').value = media.is_active ? '1' : '0';
                document.getElementById('editMediaSortOrder').value = media.sort_order || 0;
                document.getElementById('editVideoUrl').value = media.video_url || '';
                
                // Set activity
                const activitySelect = document.getElementById('editMediaActivity');
                if (activitySelect) {
                    activitySelect.value = media.activity_id || '';
                }
                
                // Show/hide appropriate sections
                const previewContainer = document.getElementById('currentMediaPreviewContainer');
                let previewHtml = '';
                
                if (media.type === 'image') {
                    previewHtml = `
                        <div class="mb-3">
                            <label class="form-label">Image actuelle</label>
                            <div>
                                <img src="${media.image_url}" 
                                     alt="${media.alt_text || media.title}"
                                     class="img-thumbnail" 
                                     style="max-width: 200px;">
                            </div>
                        </div>
                    `;
                    document.getElementById('editImageUploadSection').style.display = 'block';
                    document.getElementById('editVideoLocalUploadSection').style.display = 'none';
                    document.getElementById('editVideoUrlSection').style.display = 'none';
                } else if (media.type === 'video_local') {
                    previewHtml = `
                        <div class="mb-3">
                            <label class="form-label">Vidéo actuelle</label>
                            <div>
                                <video controls class="img-thumbnail" style="max-width: 200px;">
                                    <source src="${media.video_url}" type="${media.mime_type}">
                                </video>
                            </div>
                        </div>
                    `;
                    document.getElementById('editImageUploadSection').style.display = 'none';
                    document.getElementById('editVideoLocalUploadSection').style.display = 'block';
                    document.getElementById('editVideoUrlSection').style.display = 'none';
                } else if (media.type.startsWith('video_')) {
                    previewHtml = `
                        <div class="mb-3">
                            <label class="form-label">Vidéo actuelle</label>
                            <div>
                                <img src="/storage/${media.image_path || '/images/default-thumbnail.jpg'}" 
                                     alt="${media.alt_text || media.title}"
                                     class="img-thumbnail" 
                                     style="max-width: 200px;">
                                <div class="mt-2">
                                    <a href="${media.video_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>Ouvrir la vidéo
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById('editImageUploadSection').style.display = 'none';
                    document.getElementById('editVideoLocalUploadSection').style.display = 'none';
                    document.getElementById('editVideoUrlSection').style.display = 'block';
                }
                
                previewContainer.innerHTML = previewHtml;
                new bootstrap.Modal(document.getElementById('editMediaModal')).show();
            }
        },
        error: function(xhr, status, error) {
            showAlerts('danger', 'Erreur lors du chargement du média');
        }
    });
};

// Show delete media confirmation
const showDeleteMediaConfirmation = (mediaId) => {
    const media = allMedias.find(m => m.id === mediaId);
    
    if (!media) {
        showAlerts('danger', 'Média non trouvé');
        return;
    }
    
    mediaToDelete = media;
    
    document.getElementById('mediaToDeleteInfo').innerHTML = `
        <div class="media-info">
            <div class="d-flex align-items-center gap-3">
                <div class="media-info-image" style="width: 80px; height: 60px; overflow: hidden;">
                    <img src="/storage/${media.image_path || '/images/default-thumbnail.jpg'}" 
                         alt="${media.alt_text || media.title}"
                         class="img-fluid w-100 h-100 object-fit-cover">
                </div>
                <div>
                    <div class="media-info-name">${media.title || 'Sans titre'}</div>
                    <div class="media-info-type">${getMediaTypeLabel(media.type)} • ${formatDates(media.created_at)}</div>
                </div>
            </div>
        </div>
    `;
    
    new bootstrap.Modal(document.getElementById('deleteMediaModal')).show();
};

// Store media
const storeMedia = () => {
    const form = document.getElementById('addMediaForm');
    const submitBtn = document.getElementById('submitMediaBtn');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const type = document.querySelector('input[name="type"]:checked').value;
    
    // Validate based on type
    if (type === 'image') {
        const imageFile = document.getElementById('imageFile').files[0];
        if (!imageFile) {
            showAlerts('danger', 'Veuillez sélectionner une image');
            return;
        }
    } else if (type === 'video_local') {
        const videoFile = document.getElementById('videoFile').files[0];
        if (!videoFile) {
            showAlerts('danger', 'Veuillez sélectionner une vidéo');
            return;
        }
    } else if (type.startsWith('video_') && type !== 'video_local') {
        const videoUrl = document.getElementById('videoUrl').value;
        if (!videoUrl) {
            showAlerts('danger', 'Veuillez entrer une URL de vidéo');
            return;
        }
    }
    
    submitBtn.classList.add('btn-processing');
    submitBtn.innerHTML = `
        <span class="btn-text" style="display: none;">
            <i class="fas fa-plus-circle me-2"></i>Ajouter le média
        </span>
        <div class="spinner-border spinner-border-sm text-light" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        Ajout en cours...
    `;
    submitBtn.disabled = true;
    
    const formData = new FormData(form);
    
    $.ajax({
        url: '/countries/country-medias',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter le média
                </span>
            `;
            submitBtn.disabled = false;
            
            if (response.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addMediaModal'));
                modal.hide();
                
                form.reset();
                document.getElementById('imagePreview').style.display = 'none';
                document.getElementById('videoPreview').style.display = 'none';
                document.getElementById('videoUrlPreview').style.display = 'none';
                
                // Reset to image type
                document.getElementById('typeImage').checked = true;
                showMediaTypeSection('image');
                
                loadMedias(1, currentMediaFilters);
                loadMediaStatistics();
                
                showAlerts('success', 'Média ajouté avec succès !');
            } else {
                showAlerts('danger', response.message || 'Erreur lors de l\'ajout');
            }
        },
        error: function(xhr, status, error) {
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter le média
                </span>
            `;
            submitBtn.disabled = false;
            
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                for (const field in errors) {
                    errorMessage += `- ${errors[field].join('<br>')}<br>`;
                }
                showAlerts('danger', errorMessage);
            } else {
                showAlerts('danger', 'Erreur lors de l\'ajout: ' + error);
            }
        }
        // complete: function() {
        //     //
        // }
    });
};

// Update media
const updateMedia = () => {
    const form = document.getElementById('editMediaForm');
    const submitBtn = document.getElementById('updateMediaBtn');
    const mediaId = document.getElementById('editMediaId').value;
    
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
        url: `/countries/country-medias/${mediaId}`,
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editMediaModal'));
                modal.hide();
                
                loadMedias(currentMediaPage, currentMediaFilters);
                loadMediaStatistics();
                
                showAlerts('success', 'Média mis à jour avec succès !');
            } else {
                showAlerts('danger', response.message || 'Erreur lors de la mise à jour');
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
                showAlerts('danger', errorMessage);
            } else {
                showAlerts('danger', 'Erreur lors de la mise à jour: ' + error);
            }
        }
    });
};

// Delete media
const deleteMedia = () => {
    if (!mediaToDelete) {
        showAlerts('danger', 'Aucun média à supprimer');
        return;
    }
    
    const mediaId = mediaToDelete.id;
    const deleteBtn = document.getElementById('confirmDeleteMediaBtn');
    
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
        url: `/countries/country-medias/${mediaId}`,
        type: 'DELETE',
        data: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function(response) {
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteMediaModal'));
            deleteModal.hide();
            
            if (response.success) {
                allMedias = allMedias.filter(m => m.id !== mediaId);
                selectedMedias.delete(mediaId);
                
                loadMediaStatistics();
                
                showAlerts('success', response.message || 'Média supprimé avec succès !');
                
                setTimeout(() => {
                    loadMedias(currentMediaPage, currentMediaFilters);
                }, 500);
            } else {
                showAlerts('danger', response.message || 'Erreur lors de la suppression');
            }
        },
        error: function(xhr, status, error) {
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteMediaModal'));
            deleteModal.hide();
            
            if (xhr.status === 404) {
                showAlerts('danger', 'Média non trouvé.');
                loadMedias(currentMediaPage, currentMediaFilters);
            } else {
                showAlerts('danger', 'Erreur lors de la suppression: ' + error);
            }
        },
        complete: function() {
            mediaToDelete = null;
        }
    });
};

// Setup media uploads
const setupMediaUploads = () => {
    // Image upload
    const imageFileInput = document.getElementById('imageFile');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const imageUploadArea = document.getElementById('imageUploadArea');
    
    if (imageFileInput) {
        imageFileInput.addEventListener('change', function(e) {
            handleImageUpload(e, imagePreview, previewImage);
        });
        
        // Drag and drop for image
        setupDragAndDrop(imageUploadArea, imageFileInput, (file) => {
            handleFilePreview(file, imagePreview, previewImage, 'image');
        });
    }
    
    // Video upload
    const videoFileInput = document.getElementById('videoFile');
    const videoPreview = document.getElementById('videoPreview');
    const previewVideo = document.getElementById('previewVideo');
    const videoUploadArea = document.getElementById('videoUploadArea');
    
    if (videoFileInput) {
        videoFileInput.addEventListener('change', function(e) {
            handleVideoUpload(e, videoPreview, previewVideo);
        });
        
        // Drag and drop for video
        setupDragAndDrop(videoUploadArea, videoFileInput, (file) => {
            handleFilePreview(file, videoPreview, previewVideo, 'video');
        });
    }
    
    // Edit image upload
    const editImageFileInput = document.getElementById('editImageFile');
    const editImageUploadArea = document.getElementById('editImageUploadArea');
    
    if (editImageFileInput) {
        setupDragAndDrop(editImageUploadArea, editImageFileInput);
    }
    
    // Edit video upload
    const editVideoFileInput = document.getElementById('editVideoFile');
    const editVideoUploadArea = document.getElementById('editVideoUploadArea');
    
    if (editVideoFileInput) {
        setupDragAndDrop(editVideoUploadArea, editVideoFileInput);
    }
    
    // Media type selection
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            showMediaTypeSection(this.value);
        });
    });
};

// Show appropriate media type section
const showMediaTypeSection = (type) => {
    document.getElementById('imageUploadSection').style.display = type === 'image' ? 'block' : 'none';
    document.getElementById('videoLocalUploadSection').style.display = type === 'video_local' ? 'block' : 'none';
    document.getElementById('videoUrlSection').style.display = 
        (type === 'video_youtube' || type === 'video_vimeo' || 
         type === 'video_dailymotion' || type === 'video_other') ? 'block' : 'none';
};

// Handle image upload
const handleImageUpload = (e, previewContainer, previewElement) => {
    const file = e.target.files[0];
    if (file) {
        // Check file size (max 50MB)
        if (file.size > 50 * 1024 * 1024) {
            showAlerts('danger', 'L\'image ne doit pas dépasser 50MB');
            e.target.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            showAlerts('danger', 'Format d\'image non supporté. Utilisez JPG, PNG, GIF ou WebP.');
            e.target.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewElement.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
};

// Handle video upload
const handleVideoUpload = (e, previewContainer, previewElement) => {
    const file = e.target.files[0];
    if (file) {
        // Check file size (max 500MB)
        if (file.size > 500 * 1024 * 1024) {
            showAlerts('danger', 'La vidéo ne doit pas dépasser 500MB');
            e.target.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        // Check file type
        const validTypes = ['video/mp4', 'video/avi', 'video/quicktime', 'video/x-ms-wmv', 'video/x-flv', 'video/x-matroska'];
        if (!validTypes.includes(file.type)) {
            showAlerts('danger', 'Format de vidéo non supporté. Utilisez MP4, AVI, MOV, WMV, FLV ou MKV.');
            e.target.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewElement.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
};

// Setup drag and drop
const setupDragAndDrop = (dropArea, fileInput, previewCallback = null) => {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropArea.classList.add('bg-primary', 'bg-opacity-10');
    }
    
    function unhighlight() {
        dropArea.classList.remove('bg-primary', 'bg-opacity-10');
    }
    
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            
            if (previewCallback && files[0]) {
                previewCallback(files[0]);
            }
            
            // Trigger change event
            fileInput.dispatchEvent(new Event('change'));
        }
    }
};

// Clear image preview
const clearImagePreview = () => {
    document.getElementById('imageFile').value = '';
    document.getElementById('imagePreview').style.display = 'none';
};

// Clear video preview
const clearVideoPreview = () => {
    document.getElementById('videoFile').value = '';
    document.getElementById('videoPreview').style.display = 'none';
};

// Render media pagination
const renderMediaPagination = (response) => {
    const pagination = document.getElementById('mediaPagination');
    const paginationInfo = document.getElementById('mediaPaginationInfo');
    
    const start = (response.current_page - 1) * response.per_page + 1;
    const end = Math.min(response.current_page * response.per_page, response.total);
    paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} médias`;
    
    let paginationHtml = '';
    
    if (response.prev_page_url) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changeMediaPage(${response.current_page - 1})">
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
                    <a class="page-link" href="#" onclick="changeMediaPage(${i})">${i}</a>
                </li>
            `;
        }
    }
    
    if (response.next_page_url) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changeMediaPage(${response.current_page + 1})">
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

// Change media page
const changeMediaPage = (page) => {
    currentMediaPage = page;
    loadMedias(page, currentMediaFilters);
};

// View mode toggle
document.querySelectorAll('input[name="viewMode"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const isGridView = this.id === 'gridView';
        document.getElementById('mediaGridView').style.display = isGridView ? 'flex' : 'none';
        document.getElementById('mediaListView').classList.toggle('d-none', isGridView);
        document.getElementById('mediaListView').classList.toggle('d-block', !isGridView);
    });
});

// Show media loading state
const showMediaLoading = () => {
    document.getElementById('mediaLoadingSpinner').style.display = 'flex';
    document.getElementById('mediaGridContainer').style.display = 'none';
    document.getElementById('mediaEmptyState').style.display = 'none';
    document.getElementById('mediaPaginationContainer').style.display = 'none';
};

// Hide media loading state
const hideMediaLoading = () => {
    document.getElementById('mediaLoadingSpinner').style.display = 'none';
};

// Setup media event listeners
const setupMediaEventListeners = () => {
    // Search input
    const searchInput = document.getElementById('mediaSearchInput');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadMedias(1, currentMediaFilters);
            }, 500);
        });
    }
    
    // Clear search
    const clearSearchBtn = document.getElementById('clearMediaSearchBtn');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            loadMedias(1, currentMediaFilters);
        });
    }
    
    // Toggle filter section
    const toggleFilterBtn = document.getElementById('toggleMediaFilterBtn');
    const filterSection = document.getElementById('mediaFilterSection');
    
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
    const applyFiltersBtn = document.getElementById('applyMediaFiltersBtn');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', () => {
            currentMediaFilters = {
                type: document.getElementById('mediaFilterType').value,
                status: document.getElementById('mediaFilterStatus').value,
                featured: document.getElementById('mediaFilterFeatured').value,
                country_id: document.getElementById('mediaFilterCountry')?.value || '',
                activity_id: document.getElementById('mediaFilterActivity').value,
                sort_by: document.getElementById('mediaFilterSortBy').value
            };
            loadMedias(1, currentMediaFilters);
        });
    }
    
    // Clear filters
    const clearFiltersBtn = document.getElementById('clearMediaFiltersBtn');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
            document.getElementById('mediaFilterType').value = '';
            document.getElementById('mediaFilterStatus').value = '';
            document.getElementById('mediaFilterFeatured').value = '';
            if (document.getElementById('mediaFilterCountry')) {
                document.getElementById('mediaFilterCountry').value = '';
            }
            document.getElementById('mediaFilterActivity').value = '';
            document.getElementById('mediaFilterSortBy').value = 'created_at';
            currentMediaFilters = {};
            loadMedias(1);
        });
    }
    
    // Submit media form
    const submitMediaBtn = document.getElementById('submitMediaBtn');
    if (submitMediaBtn) {
        submitMediaBtn.addEventListener('click', storeMedia);
    }
    
    // Update media form
    const updateMediaBtn = document.getElementById('updateMediaBtn');
    if (updateMediaBtn) {
        updateMediaBtn.addEventListener('click', updateMedia);
    }
    
    // Confirm delete media button
    const confirmDeleteMediaBtn = document.getElementById('confirmDeleteMediaBtn');
    if (confirmDeleteMediaBtn) {
        confirmDeleteMediaBtn.addEventListener('click', deleteMedia);
    }
    
    // Select all medias
    const selectAllCheckbox = document.getElementById('selectAllMedias');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', selectAllMedias);
    }
    
    const selectAllListCheckbox = document.getElementById('selectAllMediasList');
    if (selectAllListCheckbox) {
        selectAllListCheckbox.addEventListener('change', function() {
            document.getElementById('selectAllMedias').checked = this.checked;
            selectAllMedias();
        });
    }
    
    // Reset add modal when hidden
    const addModal = document.getElementById('addMediaModal');
    if (addModal) {
        addModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('addMediaForm').reset();
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('videoPreview').style.display = 'none';
            document.getElementById('videoUrlPreview').style.display = 'none';
            
            // Reset to image type
            document.getElementById('typeImage').checked = true;
            showMediaTypeSection('image');
            
            const submitBtn = document.getElementById('submitMediaBtn');
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter le média
                </span>
            `;
            submitBtn.disabled = false;
        });
    }
    
    // Reset edit modal when hidden
    const editModal = document.getElementById('editMediaModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('editMediaForm').reset();
            const submitBtn = document.getElementById('updateMediaBtn');
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
    const deleteModal = document.getElementById('deleteMediaModal');
    if (deleteModal) {
        deleteModal.addEventListener('hidden.bs.modal', function() {
            mediaToDelete = null;
            const deleteBtn = document.getElementById('confirmDeleteMediaBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </span>
            `;
            deleteBtn.disabled = false;
        });
    }
};

// Helper function to format date
const formatDates = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR');
};

// Helper function to show alert
const showAlerts = (type, message) => {
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
</script>

<style>
/* Media Card Styles */
.media-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.media-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.media-card.selected {
    border-color: var(--primary-color);
    background-color: rgba(var(--primary-rgb), 0.05);
}

.media-thumbnail {
    position: relative;
}

.media-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 10px;
}

.media-card:hover .media-overlay {
    opacity: 1;
}

.video-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.featured-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: var(--warning);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.upload-area {
    border: 2px dashed #dee2e6;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: var(--primary-color);
    background-color: rgba(var(--primary-rgb), 0.05);
}

.media-type-option .form-check-input {
    display: none;
}

.media-type-option .form-check-label .card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.media-type-option .form-check-input:checked + .form-check-label .card {
    border-color: var(--primary-color);
    background-color: rgba(var(--primary-rgb), 0.1);
}

.media-checkbox {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
}

.object-fit-cover {
    object-fit: cover;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .media-card .btn-group {
        flex-direction: column;
    }
    
    .media-card .btn-group .btn {
        margin-bottom: 5px;
    }
}

/* Animation for view mode change */
#mediaGridView, #mediaListView {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Bulk actions animation */
#bulkActionsCard {
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { 
        transform: translateY(20px);
        opacity: 0;
    }
    to { 
        transform: translateY(0);
        opacity: 1;
    }
}
</style>