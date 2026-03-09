{{-- resources/views/map-points/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-edit"></i></span>
                Modifier le Point: {{ $mapPoint->title }}
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('map-points.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
        
        <!-- Main Form Card -->
        <div class="main-card-modern form-card">
            <form action="{{ route('map-points.update', $mapPoint->id) }}" method="POST" enctype="multipart/form-data" id="mapPointForm">
                @csrf
                @method('PUT')
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs-modern" id="formTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link-modern active" id="basic-info-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Informations de base
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link-modern" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab">
                            <i class="fas fa-map-marker-alt me-2"></i>Localisation
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link-modern" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">
                            <i class="fas fa-images me-2"></i>Médias
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link-modern" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                            <i class="fas fa-file-alt me-2"></i>Page détails
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link-modern" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                            <i class="fas fa-cog me-2"></i>Paramètres
                        </button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content-modern">
                    <!-- Basic Info Tab -->
                    <div class="tab-pane-modern fade show active" id="basic-info" role="tabpanel">
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-info-circle me-2" style="color: var(--primary-color);"></i>
                                Informations générales
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group-modern">
                                        <label for="title" class="form-label-modern required">
                                            Titre du point
                                        </label>
                                        <input type="text" 
                                               class="form-control-modern @error('title') is-invalid @enderror" 
                                               id="title" 
                                               name="title" 
                                               value="{{ old('title', $mapPoint->title) }}"
                                               placeholder="Ex: Restaurant Le Gourmet, Parc Mont-Royal..."
                                               required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label for="category" class="form-label-modern required">
                                            Catégorie
                                        </label>
                                        <select class="form-select-modern @error('category') is-invalid @enderror" 
                                                id="category" 
                                                name="category" 
                                                required>
                                            <option value="">Sélectionner une catégorie</option>
                                            <option value="restaurant" {{ old('category', $mapPoint->category) == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                            <option value="hotel" {{ old('category', $mapPoint->category) == 'hotel' ? 'selected' : '' }}>Hôtel</option>
                                            <option value="commerce" {{ old('category', $mapPoint->category) == 'commerce' ? 'selected' : '' }}>Commerce</option>
                                            <option value="sante" {{ old('category', $mapPoint->category) == 'sante' ? 'selected' : '' }}>Santé</option>
                                            <option value="education" {{ old('category', $mapPoint->category) == 'education' ? 'selected' : '' }}>Éducation</option>
                                            <option value="culture" {{ old('category', $mapPoint->category) == 'culture' ? 'selected' : '' }}>Culture</option>
                                            <option value="sport" {{ old('category', $mapPoint->category) == 'sport' ? 'selected' : '' }}>Sport</option>
                                            <option value="loisirs" {{ old('category', $mapPoint->category) == 'loisirs' ? 'selected' : '' }}>Loisirs</option>
                                            <option value="transport" {{ old('category', $mapPoint->category) == 'transport' ? 'selected' : '' }}>Transport</option>
                                            <option value="immobilier" {{ old('category', $mapPoint->category) == 'immobilier' ? 'selected' : '' }}>Immobilier</option>
                                            <option value="service" {{ old('category', $mapPoint->category) == 'service' ? 'selected' : '' }}>Service</option>
                                            <option value="autre" {{ old('category', $mapPoint->category) == 'autre' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Category Preview -->
                                    <div class="category-preview" id="categoryPreview" style="display: none;">
                                        <span class="category-badge" id="categoryPreviewBadge">
                                            <i class="fas" id="categoryPreviewIcon"></i>
                                            <span id="categoryPreviewLabel"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group-modern">
                                <label for="description" class="form-label-modern">
                                    Description courte <small class="text-muted">(pour le popup)</small>
                                </label>
                                <textarea class="form-control-modern @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3"
                                          maxlength="200"
                                          placeholder="Brève description qui apparaîtra dans le popup...">{{ old('description', $mapPoint->description) }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="form-text-modern">Maximum 200 caractères</small>
                                    <small class="text-muted" id="charCount">{{ strlen(old('description', $mapPoint->description ?? '')) }}/200</small>
                                </div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Location Tab -->
                    <div class="tab-pane-modern fade" id="location" role="tabpanel">
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-map-marker-alt me-2" style="color: var(--primary-color);"></i>
                                Coordonnées géographiques
                            </h3>
                            
                            <!-- Map picker -->
                            <div class="map-picker-container">
                                <div id="locationPickerMap" style="height: 350px; border-radius: 12px; margin-bottom: 20px;"></div>
                                <p class="text-muted text-center mb-3">
                                    <i class="fas fa-hand-pointer me-1"></i>
                                    Cliquez sur la carte pour déplacer le point
                                </p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="latitude" class="form-label-modern required">
                                            Latitude
                                        </label>
                                        <input type="number" 
                                               step="any" 
                                               class="form-control-modern @error('latitude') is-invalid @enderror" 
                                               id="latitude" 
                                               name="latitude" 
                                               value="{{ old('latitude', $mapPoint->latitude) }}"
                                               required>
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="longitude" class="form-label-modern required">
                                            Longitude
                                        </label>
                                        <input type="number" 
                                               step="any" 
                                               class="form-control-modern @error('longitude') is-invalid @enderror" 
                                               id="longitude" 
                                               name="longitude" 
                                               value="{{ old('longitude', $mapPoint->longitude) }}"
                                               required>
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-5">
                                    <div class="form-group-modern">
                                        <label for="adresse" class="form-label-modern">
                                            Adresse
                                        </label>
                                        <input type="text" 
                                               class="form-control-modern" 
                                               id="adresse" 
                                               name="adresse" 
                                               value="{{ old('adresse', $mapPoint->adresse) }}"
                                               placeholder="123 Rue Principale">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label for="ville" class="form-label-modern">
                                            Ville
                                        </label>
                                        <input type="text" 
                                               class="form-control-modern" 
                                               id="ville" 
                                               name="ville" 
                                               value="{{ old('ville', $mapPoint->ville) }}"
                                               placeholder="Montréal">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group-modern">
                                        <label for="code_postal" class="form-label-modern">
                                            Code postal
                                        </label>
                                        <input type="text" 
                                               class="form-control-modern" 
                                               id="code_postal" 
                                               name="code_postal" 
                                               value="{{ old('code_postal', $mapPoint->code_postal) }}"
                                               placeholder="H2X 1Y4">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-outline-primary" id="geocodeBtn">
                                    <i class="fas fa-search-location me-2"></i>
                                    Rechercher l'adresse sur la carte
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Media Tab -->
                    <div class="tab-pane-modern fade" id="media" role="tabpanel">
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-image me-2" style="color: var(--primary-color);"></i>
                                Image principale
                            </h3>
                            
                            <div class="main-image-upload">
                                @if($mapPoint->main_image)
                                <div class="image-preview-container" id="mainImagePreviewContainer" style="display: block;">
                                    <img id="mainImagePreview" src="{{ asset('storage/'.$mapPoint->main_image) }}" alt="Preview" class="img-preview">
                                    <button type="button" class="btn-remove-image" id="removeMainImage">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="upload-area" id="mainImageUploadArea" style="display: none;">
                                @else
                                <div class="image-preview-container" id="mainImagePreviewContainer" style="display: none;">
                                    <img id="mainImagePreview" src="" alt="Preview" class="img-preview">
                                    <button type="button" class="btn-remove-image" id="removeMainImage">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="upload-area" id="mainImageUploadArea">
                                @endif
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <h4 class="upload-title">Cliquez ou glissez une image</h4>
                                    <p class="upload-text">PNG, JPG, JPEG jusqu'à 2MB</p>
                                    <input type="file" 
                                           class="file-input" 
                                           id="main_image" 
                                           name="main_image" 
                                           accept="image/png, image/jpg, image/jpeg">
                                    <button type="button" class="btn btn-outline-primary mt-3" onclick="document.getElementById('main_image').click()">
                                        <i class="fas fa-upload me-2"></i>Choisir une image
                                    </button>
                                </div>
                            </div>
                            
                            <h3 class="form-section-title mt-4">
                                <i class="fas fa-video me-2" style="color: var(--primary-color);"></i>
                                Vidéo YouTube (optionnel)
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group-modern">
                                        <label for="youtube_url" class="form-label-modern">
                                            Lien YouTube
                                        </label>
                                        <input type="url" 
                                               class="form-control-modern" 
                                               id="youtube_url" 
                                               name="youtube_url" 
                                               value="{{ old('youtube_url', $mapPoint->youtube_url) }}"
                                               placeholder="https://www.youtube.com/watch?v=...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="video-preview" id="videoPreviewContainer" style="{{ $mapPoint->youtube_id ? 'display: block;' : 'display: none;' }}">
                                        <img id="videoThumbnail" src="{{ $mapPoint->youtube_id ? 'https://img.youtube.com/vi/'.$mapPoint->youtube_id.'/hqdefault.jpg' : '' }}" alt="Video thumbnail" class="video-thumbnail">
                                        <button type="button" class="btn-remove-video" id="removeVideo">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="form-section-title mt-4">
                                <i class="fas fa-images me-2" style="color: var(--primary-color);"></i>
                                Galerie d'images (optionnel)
                            </h3>
                            
                            <div class="gallery-upload">
                                <!-- Conteneur pour les previews existantes et nouvelles -->
                                <div class="gallery-grid" id="galleryPreview">
                                    @if($mapPoint->images->count() > 0)
                                        @foreach($mapPoint->images as $index => $image)
                                        <div class="gallery-preview-item existing" data-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/'.$image->image) }}" alt="Gallery Image">
                                            <button type="button" class="btn-remove-gallery-existing" data-id="{{ $image->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                
                                <!-- Zone d'upload pour nouvelles images -->
                                <div class="upload-area-small" id="galleryUploadArea">
                                    <i class="fas fa-plus"></i>
                                    <span>Ajouter</span>
                                    <input type="file" 
                                           class="file-input" 
                                           id="additional_images" 
                                           name="additional_images[]" 
                                           accept="image/png, image/jpg, image/jpeg"
                                           multiple
                                           style="display: none;">
                                </div>
                            </div>
                            <small class="form-text-modern">Vous pouvez sélectionner plusieurs images (Ctrl+clic)</small>
                            
                            <!-- Conteneur pour les IDs d'images à supprimer -->
                            <div id="imagesToDelete"></div>
                        </div>
                    </div>
                    
                    <!-- Details Tab -->
                    <div class="tab-pane-modern fade" id="details" role="tabpanel">
                        <div class="form-section">
                            <div class="form-group-modern">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="has_details_page" 
                                           name="has_details_page" 
                                           value="1"
                                           {{ old('has_details_page', $mapPoint->has_details_page) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="has_details_page">
                                        <strong>Activer la page de détails dédiée</strong>
                                    </label>
                                </div>
                            </div>
                            
                            <div id="detailsFields" style="{{ old('has_details_page', $mapPoint->has_details_page) ? 'display: block;' : 'display: none;' }}">
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="details_phone" class="form-label-modern">
                                                Téléphone
                                            </label>
                                            <input type="text" 
                                                   class="form-control-modern" 
                                                   id="details_phone" 
                                                   name="details[phone]" 
                                                   value="{{ old('details.phone', $mapPoint->details->phone ?? '') }}"
                                                   placeholder="+1 514-555-0123">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="details_email" class="form-label-modern">
                                                Email
                                            </label>
                                            <input type="email" 
                                                   class="form-control-modern" 
                                                   id="details_email" 
                                                   name="details[email]" 
                                                   value="{{ old('details.email', $mapPoint->details->email ?? '') }}"
                                                   placeholder="contact@example.com">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group-modern">
                                    <label for="details_website" class="form-label-modern">
                                        Site web
                                    </label>
                                    <input type="url" 
                                           class="form-control-modern" 
                                           id="details_website" 
                                           name="details[website]" 
                                           value="{{ old('details.website', $mapPoint->details->website ?? '') }}"
                                           placeholder="https://www.example.com">
                                </div>
                                
                                <div class="form-group-modern">
                                    <label for="details_long_description" class="form-label-modern">
                                        Description détaillée (WYSIWYG)
                                    </label>
                                    <textarea class="form-control-modern" 
                                              id="details_long_description" 
                                              name="details[long_description]" 
                                              rows="8">{{ old('details.long_description', $mapPoint->details->long_description ?? '') }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="details_facebook" class="form-label-modern">
                                                <i class="fab fa-facebook me-1" style="color: #1877f2;"></i> Facebook
                                            </label>
                                            <input type="url" 
                                                   class="form-control-modern" 
                                                   id="details_facebook" 
                                                   name="details[facebook]" 
                                                   value="{{ old('details.facebook', $mapPoint->details->facebook ?? '') }}"
                                                   placeholder="https://facebook.com/...">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="details_instagram" class="form-label-modern">
                                                <i class="fab fa-instagram me-1" style="color: #e4405f;"></i> Instagram
                                            </label>
                                            <input type="url" 
                                                   class="form-control-modern" 
                                                   id="details_instagram" 
                                                   name="details[instagram]" 
                                                   value="{{ old('details.instagram', $mapPoint->details->instagram ?? '') }}"
                                                   placeholder="https://instagram.com/...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Settings Tab -->
                    <div class="tab-pane-modern fade" id="settings" role="tabpanel">
                        <div class="form-section">
                            <h3 class="form-section-title">
                                <i class="fas fa-cog me-2" style="color: var(--primary-color);"></i>
                                Options avancées
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="etablissement_id" class="form-label-modern">
                                            Lier à un établissement
                                        </label>
                                        <select class="form-select-modern" id="etablissement_id" name="etablissement_id">
                                            <option value="">Aucun établissement lié</option>
                                            @if(isset($etablissements) && $etablissements->count() > 0)
                                                @foreach($etablissements as $etablissement)
                                                    <option value="{{ $etablissement->id }}" {{ old('etablissement_id', $mapPoint->etablissement_id) == $etablissement->id ? 'selected' : '' }}>
                                                        {{ $etablissement->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">Options d'affichage</label>
                                        <div class="settings-options">
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="is_featured" 
                                                       name="is_featured" 
                                                       value="1"
                                                       {{ old('is_featured', $mapPoint->is_featured) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_featured">
                                                    <i class="fas fa-star text-warning me-1"></i>
                                                    Mettre en avant
                                                </label>
                                            </div>
                                            
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="is_active" 
                                                       name="is_active" 
                                                       value="1"
                                                       {{ old('is_active', $mapPoint->is_active) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                    Actif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group-modern mt-3">
                                <label for="slug" class="form-label-modern">
                                    URL personnalisée (slug)
                                </label>
                                <input type="text" 
                                       class="form-control-modern" 
                                       id="slug" 
                                       name="details_url" 
                                       value="{{ old('details_url', $mapPoint->details->slug ?? $mapPoint->details_url ?? '') }}"
                                       placeholder="mon-point-d-interet"
                                       readonly>
                                <small class="form-text-modern">Généré automatiquement à partir du titre</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FORM ACTIONS -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('map-points.index') }}'">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    
                    <div class="btn-group-center">
                        <button type="button" class="btn btn-outline-primary" id="prevTabBtn" style="display: none;">
                            <i class="fas fa-chevron-left me-2"></i>Précédent
                        </button>
                        
                        <button type="button" class="btn btn-outline-primary" id="nextTabBtn">
                            Suivant <i class="fas fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                    
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== INITIALISATION CKEDITOR ====================
            let editor;
            ClassicEditor
                .create(document.querySelector('#details_long_description'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', '|', 'undo', 'redo'],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
                        ]
                    }
                })
                .then(newEditor => {
                    editor = newEditor;
                })
                .catch(error => {
                    console.error(error);
                });

            // ==================== CATEGORY BADGE PREVIEW ====================
            const categorySelect = document.getElementById('category');
            const categoryPreview = document.getElementById('categoryPreview');
            const categoryPreviewIcon = document.getElementById('categoryPreviewIcon');
            const categoryPreviewLabel = document.getElementById('categoryPreviewLabel');
            const categoryPreviewBadge = document.getElementById('categoryPreviewBadge');

            const categoryColors = {
                'restaurant': '#FF6B6B',
                'hotel': '#4ECDC4',
                'commerce': '#45B7D1',
                'sante': '#96CEB4',
                'education': '#FFE194',
                'culture': '#DDA0DD',
                'sport': '#FFA07A',
                'loisirs': '#90EE90',
                'transport': '#A9A9A9',
                'immobilier': '#1b4f6b',
                'service': '#B0C4DE',
                'autre': '#6c757d'
            };

            const categoryIcons = {
                'restaurant': 'fa-utensils',
                'hotel': 'fa-hotel',
                'commerce': 'fa-shop',
                'sante': 'fa-hospital',
                'education': 'fa-school',
                'culture': 'fa-museum',
                'sport': 'fa-dumbbell',
                'loisirs': 'fa-tree',
                'transport': 'fa-bus',
                'immobilier': 'fa-building',
                'service': 'fa-gear',
                'autre': 'fa-map-pin'
            };

            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    const category = this.value;
                    
                    if (category) {
                        const color = categoryColors[category] || '#6c757d';
                        const icon = categoryIcons[category] || 'fa-map-pin';
                        const label = this.options[this.selectedIndex].text;
                        
                        categoryPreviewIcon.className = `fas ${icon}`;
                        categoryPreviewLabel.textContent = label;
                        categoryPreviewBadge.style.background = `${color}20`;
                        categoryPreviewBadge.style.color = color;
                        categoryPreviewBadge.style.borderLeftColor = color;
                        categoryPreview.style.display = 'block';
                    } else {
                        categoryPreview.style.display = 'none';
                    }
                });

                // Déclencher pour la valeur existante
                if (categorySelect.value) {
                    categorySelect.dispatchEvent(new Event('change'));
                }
            }

            // ==================== COMPTEUR DE CARACTÈRES ====================
            const descriptionInput = document.getElementById('description');
            const charCount = document.getElementById('charCount');

            if (descriptionInput && charCount) {
                descriptionInput.addEventListener('input', function() {
                    const count = this.value.length;
                    charCount.textContent = `${count}/200`;
                    
                    if (count > 180) {
                        charCount.style.color = '#ef476f';
                    } else {
                        charCount.style.color = '#6c757d';
                    }
                });
            }

            // ==================== GESTION DES TABS ====================
            const tabs = document.querySelectorAll('.nav-link-modern');
            const tabPanes = document.querySelectorAll('.tab-pane-modern');
            const prevBtn = document.getElementById('prevTabBtn');
            const nextBtn = document.getElementById('nextTabBtn');

            let currentTabIndex = 0;
            tabs.forEach((tab, index) => {
                if (tab.classList.contains('active')) {
                    currentTabIndex = index;
                }
            });

            function switchToTab(index) {
                if (index < 0 || index >= tabs.length) return;
                
                tabs.forEach((tab, i) => {
                    if (i === index) {
                        tab.classList.add('active');
                    } else {
                        tab.classList.remove('active');
                    }
                });
                
                tabPanes.forEach((pane, i) => {
                    if (i === index) {
                        pane.classList.add('show', 'active');
                    } else {
                        pane.classList.remove('show', 'active');
                    }
                });
                
                if (index === 0) {
                    if (prevBtn) prevBtn.style.display = 'none';
                } else {
                    if (prevBtn) prevBtn.style.display = 'inline-flex';
                }
                
                if (index === tabs.length - 1) {
                    if (nextBtn) nextBtn.style.display = 'none';
                } else {
                    if (nextBtn) nextBtn.style.display = 'inline-flex';
                }
                
                currentTabIndex = index;
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentTabIndex > 0) switchToTab(currentTabIndex - 1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (currentTabIndex < tabs.length - 1) {
                        if (currentTabIndex === 0) {
                            const title = document.getElementById('title').value;
                            const category = document.getElementById('category').value;
                            if (!title || !category) {
                                alert('Veuillez remplir le titre et la catégorie');
                                return;
                            }
                        }
                        if (currentTabIndex === 1) {
                            const lat = document.getElementById('latitude').value;
                            const lng = document.getElementById('longitude').value;
                            if (!lat || !lng) {
                                alert('Veuillez sélectionner un point sur la carte');
                                return;
                            }
                        }
                        switchToTab(currentTabIndex + 1);
                    }
                });
            }

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', (e) => {
                    e.preventDefault();
                    switchToTab(index);
                });
            });

            if (currentTabIndex === 0 && prevBtn) prevBtn.style.display = 'none';
            if (currentTabIndex === tabs.length - 1 && nextBtn) nextBtn.style.display = 'none';

            // ==================== MAP PICKER ====================
            let map, marker;
            
            function initMap() {
                const lat = parseFloat(document.getElementById('latitude').value) || 45.5089;
                const lng = parseFloat(document.getElementById('longitude').value) || -73.5617;
                
                map = L.map('locationPickerMap').setView([lat, lng], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                const customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: '<div class="marker-icon"><i class="fas fa-map-pin"></i></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                });
                
                marker = L.marker([lat, lng], {
                    draggable: true,
                    icon: customIcon
                }).addTo(map);
                
                map.on('click', (e) => updatePosition(e.latlng.lat, e.latlng.lng));
                marker.on('dragend', (e) => {
                    const pos = marker.getLatLng();
                    updatePosition(pos.lat, pos.lng);
                });
            }
            
            function updatePosition(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
                marker.setLatLng([lat, lng]);
            }
            
            if (document.getElementById('locationPickerMap')) initMap();

            // ==================== GEOCODING ====================
            const geocodeBtn = document.getElementById('geocodeBtn');
            if (geocodeBtn) {
                geocodeBtn.addEventListener('click', function() {
                    const adresse = document.getElementById('adresse').value;
                    const ville = document.getElementById('ville').value;
                    const codePostal = document.getElementById('code_postal').value;
                    const query = [adresse, ville, codePostal].filter(Boolean).join(', ');
                    
                    if (!query) {
                        alert('Veuillez saisir une adresse');
                        return;
                    }
                    
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Recherche...';
                    
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lng = parseFloat(data[0].lon);
                                updatePosition(lat, lng);
                                map.setView([lat, lng], 15);
                                alert('Adresse trouvée !');
                            } else {
                                alert('Adresse non trouvée');
                            }
                        })
                        .catch(error => {
                            console.error('Geocoding error:', error);
                            alert('Erreur lors de la recherche');
                        })
                        .finally(() => {
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-search-location me-2"></i>Rechercher l\'adresse sur la carte';
                        });
                });
            }

            // ==================== IMAGE PRINCIPALE ====================
            const mainImageInput = document.getElementById('main_image');
            const mainImagePreviewContainer = document.getElementById('mainImagePreviewContainer');
            const mainImagePreview = document.getElementById('mainImagePreview');
            const uploadArea = document.getElementById('mainImageUploadArea');
            const removeMainImageBtn = document.getElementById('removeMainImage');
            
            if (mainImageInput && mainImagePreviewContainer && mainImagePreview && uploadArea && removeMainImageBtn) {
                mainImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            mainImagePreview.src = e.target.result;
                            mainImagePreviewContainer.style.display = 'block';
                            uploadArea.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                removeMainImageBtn.addEventListener('click', function() {
                    if (confirm('Supprimer l\'image principale ?')) {
                        mainImageInput.value = '';
                        mainImagePreviewContainer.style.display = 'none';
                        uploadArea.style.display = 'block';
                        // Ajouter un champ caché pour signaler la suppression
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'remove_main_image';
                        input.value = '1';
                        document.getElementById('mapPointForm').appendChild(input);
                    }
                });
                
                uploadArea.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    uploadArea.classList.add('dragover');
                });
                
                uploadArea.addEventListener('dragleave', () => {
                    uploadArea.classList.remove('dragover');
                });
                
                uploadArea.addEventListener('drop', (e) => {
                    e.preventDefault();
                    uploadArea.classList.remove('dragover');
                    const file = e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        mainImageInput.files = e.dataTransfer.files;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            mainImagePreview.src = e.target.result;
                            mainImagePreviewContainer.style.display = 'block';
                            uploadArea.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // ==================== VIDÉO YOUTUBE ====================
            const youtubeUrlInput = document.getElementById('youtube_url');
            const videoPreviewContainer = document.getElementById('videoPreviewContainer');
            const videoThumbnail = document.getElementById('videoThumbnail');
            const removeVideoBtn = document.getElementById('removeVideo');
            
            function extractYoutubeId(url) {
                if (!url) return null;
                const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                const match = url.match(regExp);
                return (match && match[2].length === 11) ? match[2] : null;
            }
            
            if (youtubeUrlInput && videoPreviewContainer && videoThumbnail && removeVideoBtn) {
                youtubeUrlInput.addEventListener('input', function() {
                    const videoId = extractYoutubeId(this.value);
                    if (videoId) {
                        videoThumbnail.src = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;
                        videoPreviewContainer.style.display = 'block';
                    } else {
                        videoPreviewContainer.style.display = 'none';
                    }
                });
                
                removeVideoBtn.addEventListener('click', function() {
                    youtubeUrlInput.value = '';
                    videoPreviewContainer.style.display = 'none';
                });
            }

            // ==================== GALERIE D'IMAGES ====================
            const galleryInput = document.getElementById('additional_images');
            const galleryPreview = document.getElementById('galleryPreview');
            const galleryUploadArea = document.getElementById('galleryUploadArea');
            const imagesToDelete = document.getElementById('imagesToDelete');
            
            if (galleryInput && galleryPreview && galleryUploadArea) {
                galleryUploadArea.addEventListener('click', () => galleryInput.click());
                
                galleryInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    if (files.length === 0) return;
                    
                    files.forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const previewItem = document.createElement('div');
                                previewItem.className = 'gallery-preview-item';
                                previewItem.innerHTML = `
                                    <img src="${e.target.result}" alt="Preview ${index + 1}">
                                    <button type="button" class="btn-remove-gallery" data-index="${index}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `;
                                galleryPreview.appendChild(previewItem);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                    
                    galleryUploadArea.style.display = 'none';
                });
                
                // Supprimer une nouvelle image
                galleryPreview.addEventListener('click', function(e) {
                    const btn = e.target.closest('.btn-remove-gallery');
                    if (btn) {
                        btn.closest('.gallery-preview-item').remove();
                        if (galleryPreview.children.length === 0) {
                            galleryUploadArea.style.display = 'flex';
                            galleryInput.value = '';
                        }
                    }
                });
                
                // Supprimer une image existante
                galleryPreview.addEventListener('click', function(e) {
                    const btn = e.target.closest('.btn-remove-gallery-existing');
                    if (btn) {
                        const imageId = btn.dataset.id;
                        if (confirm('Supprimer cette image ?')) {
                            btn.closest('.gallery-preview-item').remove();
                            // Ajouter l'ID à la liste des images à supprimer
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'delete_images[]';
                            input.value = imageId;
                            imagesToDelete.appendChild(input);
                            
                            if (galleryPreview.children.length === 0) {
                                galleryUploadArea.style.display = 'flex';
                            }
                        }
                    }
                });
            }

            // ==================== PAGE DÉTAILS TOGGLE ====================
            const hasDetailsCheckbox = document.getElementById('has_details_page');
            const detailsFields = document.getElementById('detailsFields');
            
            if (hasDetailsCheckbox && detailsFields) {
                hasDetailsCheckbox.addEventListener('change', function() {
                    detailsFields.style.display = this.checked ? 'block' : 'none';
                });
            }

            // ==================== SLUG GÉNÉRATION ====================
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');
            
            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function() {
                    const slug = this.value
                        .toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/--+/g, '-')
                        .trim();
                    
                    slugInput.value = slug;
                });
            }

            // ==================== VALIDATION FORMULAIRE ====================
            document.getElementById('mapPointForm').addEventListener('submit', function(e) {
                const title = document.getElementById('title').value;
                const category = document.getElementById('category').value;
                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;
                
                if (!title || !category || !lat || !lng) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires');
                    if (!title || !category) switchToTab(0);
                    else if (!lat || !lng) switchToTab(1);
                    return false;
                }
                
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour...';
                }
            });
        });
    </script>

    <style>
        .form-card {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .nav-tabs-modern {
            display: flex;
            gap: 10px;
            border-bottom: 2px solid #eef4f9;
            padding-bottom: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .nav-link-modern {
            border: none;
            background: none;
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .nav-link-modern:hover {
            background: #f0f5fa;
            color: var(--primary-color);
        }

        .nav-link-modern.active {
            background: var(--primary-color) !important;
            color: white !important;
            box-shadow: 0 5px 15px rgba(27, 79, 107, 0.3);
        }

        .tab-content-modern {
            min-height: 400px;
        }

        .tab-pane-modern {
            display: none;
        }

        .tab-pane-modern.show.active {
            display: block;
        }

        .form-section {
            background: #f8fbfe;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .form-section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef4f9;
        }

        .form-group-modern {
            margin-bottom: 20px;
        }

        .form-label-modern {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }

        .form-label-modern.required::after {
            content: '*';
            color: #ef476f;
            margin-left: 4px;
        }

        .form-control-modern, .form-select-modern {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e9f0;
            border-radius: 12px;
            font-size: 0.95rem;
            background: white;
        }

        .form-control-modern:focus, .form-select-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(27, 79, 107, 0.1);
        }

        .category-preview {
            margin-top: 10px;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            border-left: 3px solid;
        }

        .map-picker-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .custom-marker {
            background: transparent;
        }

        .marker-icon {
            width: 30px;
            height: 30px;
            background: var(--primary-color);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .marker-icon i {
            transform: rotate(45deg);
        }

        .main-image-upload {
            border: 2px dashed #e0e9f0;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            background: white;
        }

        .upload-area {
            padding: 30px;
            cursor: pointer;
        }

        .upload-area.dragover {
            background: rgba(27, 79, 107, 0.05);
            border-color: var(--primary-color);
        }

        .upload-icon {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 15px;
        }

        .file-input {
            display: none;
        }

        .image-preview-container {
            position: relative;
            display: inline-block;
        }

        .img-preview {
            max-width: 300px;
            max-height: 200px;
            border-radius: 12px;
        }

        .btn-remove-image {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ef476f;
            color: white;
            border: none;
            cursor: pointer;
        }

        .video-preview {
            position: relative;
        }

        .video-thumbnail {
            max-width: 200px;
            border-radius: 8px;
        }

        .btn-remove-video {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #ef476f;
            color: white;
            border: none;
            cursor: pointer;
        }

        .gallery-upload {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .gallery-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .gallery-preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
        }

        .gallery-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-remove-gallery, .btn-remove-gallery-existing {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #ef476f;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-area-small {
            width: 100px;
            height: 100px;
            border: 2px dashed #e0e9f0;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            color: #6c757d;
        }

        .upload-area-small:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .upload-area-small i {
            font-size: 1.5rem;
        }

        .custom-control {
            padding-left: 30px;
            position: relative;
        }

        .custom-control-input {
            position: absolute;
            opacity: 0;
        }

        .custom-control-label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            width: 20px;
            height: 20px;
            border: 2px solid #e0e9f0;
            border-radius: 5px;
            background: white;
        }

        .custom-control-input:checked + .custom-control-label::before {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .custom-control-label::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 4px;
            top: 2px;
            color: white;
            font-size: 12px;
            opacity: 0;
        }

        .custom-control-input:checked + .custom-control-label::after {
            opacity: 1;
        }

        .settings-options {
            background: white;
            padding: 15px;
            border-radius: 12px;
            border: 2px solid #eef4f9;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eef4f9;
        }

        .btn-group-center {
            display: flex;
            gap: 10px;
            margin: 0 auto;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-success {
            background: #06b48a;
            color: white;
        }

        .btn-success:hover {
            background: #049a72;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            background: transparent;
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background: #e9ecef;
            color: #495057;
        }

        .ms-auto {
            margin-left: auto;
        }
    </style>
@endsection