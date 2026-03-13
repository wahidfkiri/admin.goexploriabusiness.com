{{-- resources/views/map-points/create.blade.php --}}
@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-plus-circle"></i></span>
                Ajouter un Point sur la Carte
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('map-points.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
        
        <!-- Main Form Card -->
        <div class="main-card-modern form-card">
            <form action="{{ route('map-points.store') }}" method="POST" enctype="multipart/form-data" id="mapPointForm">
                @csrf
                
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
                                               value="{{ old('title') }}"
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
                                            <option value="restaurant" {{ old('category') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                            <option value="hotel" {{ old('category') == 'hotel' ? 'selected' : '' }}>Hôtel</option>
                                            <option value="commerce" {{ old('category') == 'commerce' ? 'selected' : '' }}>Commerce</option>
                                            <option value="sante" {{ old('category') == 'sante' ? 'selected' : '' }}>Santé</option>
                                            <option value="education" {{ old('category') == 'education' ? 'selected' : '' }}>Éducation</option>
                                            <option value="culture" {{ old('category') == 'culture' ? 'selected' : '' }}>Culture</option>
                                            <option value="sport" {{ old('category') == 'sport' ? 'selected' : '' }}>Sport</option>
                                            <option value="loisirs" {{ old('category') == 'loisirs' ? 'selected' : '' }}>Loisirs</option>
                                            <option value="transport" {{ old('category') == 'transport' ? 'selected' : '' }}>Transport</option>
                                            <option value="immobilier" {{ old('category') == 'immobilier' ? 'selected' : '' }}>Immobilier</option>
                                            <option value="service" {{ old('category') == 'service' ? 'selected' : '' }}>Service</option>
                                            <option value="autre" {{ old('category') == 'autre' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <!-- Après le select category -->
                                        <div class="category-preview" id="categoryPreview" style="display: none;">
                                            <span class="category-badge" id="categoryPreviewBadge">
                                                <i class="fas" id="categoryPreviewIcon"></i>
                                                <span id="categoryPreviewLabel"></span>
                                            </span>
                                        </div>
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
                                          placeholder="Brève description qui apparaîtra dans le popup...">{{ old('description') }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="form-text-modern">Maximum 200 caractères</small>
                                    <small class="text-muted" id="charCount">0/200</small>
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
                                    Cliquez sur la carte pour placer le point
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
                                               value="{{ old('latitude', '45.5089') }}"
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
                                               value="{{ old('longitude', '-73.5617') }}"
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
                                               value="{{ old('adresse') }}"
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
                                               value="{{ old('ville') }}"
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
                                               value="{{ old('code_postal') }}"
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
                                <div class="image-preview-container" id="mainImagePreviewContainer" style="display: none;">
                                    <img id="mainImagePreview" src="" alt="Preview" class="img-preview">
                                    <button type="button" class="btn-remove-image" id="removeMainImage">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                
                                <div class="upload-area" id="mainImageUploadArea">
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
                                               value="{{ old('youtube_url') }}"
                                               placeholder="https://www.youtube.com/watch?v=...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="video-preview" id="videoPreviewContainer" style="display: none;">
                                        <img id="videoThumbnail" src="" alt="Video thumbnail" class="video-thumbnail">
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
                                <!-- Conteneur pour les previews -->
                                <div class="gallery-grid" id="galleryPreview"></div>
                                
                                <!-- Zone d'upload -->
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
                                           {{ old('has_details_page') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="has_details_page">
                                        <strong>Créer une page de détails dédiée</strong>
                                    </label>
                                </div>
                            </div>
                            
                            <div id="detailsFields" style="{{ old('has_details_page') ? 'display: block;' : 'display: none;' }}">
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
                                                   value="{{ old('details.phone') }}"
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
                                                   value="{{ old('details.email') }}"
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
                                           value="{{ old('details.website') }}"
                                           placeholder="https://www.example.com">
                                </div>
                                
                                <div class="form-group-modern">
                                    <label for="details_long_description" class="form-label-modern">
                                        Description détaillée (WYSIWYG)
                                    </label>
                                    <textarea class="form-control-modern" 
                                              id="details_long_description" 
                                              name="details[long_description]" 
                                              rows="8">{{ old('details.long_description') }}</textarea>
                                </div>
                                
                                <!-- Réseaux sociaux - Version complète -->
                                <div class="social-media-section mt-4">
                                    <h4 class="form-subsection-title">
                                        <i class="fas fa-share-alt me-2" style="color: var(--primary-color);"></i>
                                        Réseaux sociaux
                                    </h4>
                                    
                                    <div class="row">
                                        <!-- Facebook -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_facebook" class="form-label-modern">
                                                    <i class="fab fa-facebook me-1" style="color: #1877f2;"></i> Facebook
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_facebook" 
                                                       name="details[facebook]" 
                                                       value="{{ old('details.facebook') }}"
                                                       placeholder="https://facebook.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Instagram -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_instagram" class="form-label-modern">
                                                    <i class="fab fa-instagram me-1" style="color: #e4405f;"></i> Instagram
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_instagram" 
                                                       name="details[instagram]" 
                                                       value="{{ old('details.instagram') }}"
                                                       placeholder="https://instagram.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Twitter/X -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_twitter" class="form-label-modern">
                                                    <i class="fab fa-x-twitter me-1" style="color: #000000;"></i> X (Twitter)
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_twitter" 
                                                       name="details[twitter]" 
                                                       value="{{ old('details.twitter') }}"
                                                       placeholder="https://twitter.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- LinkedIn -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_linkedin" class="form-label-modern">
                                                    <i class="fab fa-linkedin me-1" style="color: #0a66c2;"></i> LinkedIn
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_linkedin" 
                                                       name="details[linkedin]" 
                                                       value="{{ old('details.linkedin') }}"
                                                       placeholder="https://linkedin.com/company/...">
                                            </div>
                                        </div>
                                        
                                        <!-- YouTube -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_youtube" class="form-label-modern">
                                                    <i class="fab fa-youtube me-1" style="color: #ff0000;"></i> YouTube
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_youtube" 
                                                       name="details[youtube]" 
                                                       value="{{ old('details.youtube') }}"
                                                       placeholder="https://youtube.com/c/...">
                                            </div>
                                        </div>
                                        
                                        <!-- TikTok -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_tiktok" class="form-label-modern">
                                                    <i class="fab fa-tiktok me-1" style="color: #000000;"></i> TikTok
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_tiktok" 
                                                       name="details[tiktok]" 
                                                       value="{{ old('details.tiktok') }}"
                                                       placeholder="https://tiktok.com/@...">
                                            </div>
                                        </div>
                                        
                                        <!-- Pinterest -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_pinterest" class="form-label-modern">
                                                    <i class="fab fa-pinterest me-1" style="color: #bd081c;"></i> Pinterest
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_pinterest" 
                                                       name="details[pinterest]" 
                                                       value="{{ old('details.pinterest') }}"
                                                       placeholder="https://pinterest.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Snapchat -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_snapchat" class="form-label-modern">
                                                    <i class="fab fa-snapchat me-1" style="color: #fffc00;"></i> Snapchat
                                                </label>
                                                <input type="text" 
                                                       class="form-control-modern" 
                                                       id="details_snapchat" 
                                                       name="details[snapchat]" 
                                                       value="{{ old('details.snapchat') }}"
                                                       placeholder="@username">
                                            </div>
                                        </div>
                                        
                                        <!-- WhatsApp -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_whatsapp" class="form-label-modern">
                                                    <i class="fab fa-whatsapp me-1" style="color: #25d366;"></i> WhatsApp
                                                </label>
                                                <input type="text" 
                                                       class="form-control-modern" 
                                                       id="details_whatsapp" 
                                                       name="details[whatsapp]" 
                                                       value="{{ old('details.whatsapp') }}"
                                                       placeholder="+221 77 123 45 67">
                                            </div>
                                        </div>
                                        
                                        <!-- Telegram -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_telegram" class="form-label-modern">
                                                    <i class="fab fa-telegram me-1" style="color: #0088cc;"></i> Telegram
                                                </label>
                                                <input type="text" 
                                                       class="form-control-modern" 
                                                       id="details_telegram" 
                                                       name="details[telegram]" 
                                                       value="{{ old('details.telegram') }}"
                                                       placeholder="@username">
                                            </div>
                                        </div>
                                        
                                        <!-- Discord -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_discord" class="form-label-modern">
                                                    <i class="fab fa-discord me-1" style="color: #5865f2;"></i> Discord
                                                </label>
                                                <input type="text" 
                                                       class="form-control-modern" 
                                                       id="details_discord" 
                                                       name="details[discord]" 
                                                       value="{{ old('details.discord') }}"
                                                       placeholder="https://discord.gg/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Twitch -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_twitch" class="form-label-modern">
                                                    <i class="fab fa-twitch me-1" style="color: #9146ff;"></i> Twitch
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_twitch" 
                                                       name="details[twitch]" 
                                                       value="{{ old('details.twitch') }}"
                                                       placeholder="https://twitch.tv/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Reddit -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_reddit" class="form-label-modern">
                                                    <i class="fab fa-reddit me-1" style="color: #ff4500;"></i> Reddit
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_reddit" 
                                                       name="details[reddit]" 
                                                       value="{{ old('details.reddit') }}"
                                                       placeholder="https://reddit.com/user/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Github (pour établissements tech) -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_github" class="form-label-modern">
                                                    <i class="fab fa-github me-1" style="color: #333;"></i> GitHub
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_github" 
                                                       name="details[github]" 
                                                       value="{{ old('details.github') }}"
                                                       placeholder="https://github.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Medium -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_medium" class="form-label-modern">
                                                    <i class="fab fa-medium me-1" style="color: #00ab6c;"></i> Medium
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_medium" 
                                                       name="details[medium]" 
                                                       value="{{ old('details.medium') }}"
                                                       placeholder="https://medium.com/@...">
                                            </div>
                                        </div>
                                        
                                        <!-- Tumblr -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_tumblr" class="form-label-modern">
                                                    <i class="fab fa-tumblr me-1" style="color: #34526f;"></i> Tumblr
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_tumblr" 
                                                       name="details[tumblr]" 
                                                       value="{{ old('details.tumblr') }}"
                                                       placeholder="https://tumblr.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Vimeo -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_vimeo" class="form-label-modern">
                                                    <i class="fab fa-vimeo me-1" style="color: #1ab7ea;"></i> Vimeo
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_vimeo" 
                                                       name="details[vimeo]" 
                                                       value="{{ old('details.vimeo') }}"
                                                       placeholder="https://vimeo.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Dribbble -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_dribbble" class="form-label-modern">
                                                    <i class="fab fa-dribbble me-1" style="color: #ea4c89;"></i> Dribbble
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_dribbble" 
                                                       name="details[dribbble]" 
                                                       value="{{ old('details.dribbble') }}"
                                                       placeholder="https://dribbble.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Behance -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_behance" class="form-label-modern">
                                                    <i class="fab fa-behance me-1" style="color: #1769ff;"></i> Behance
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_behance" 
                                                       name="details[behance]" 
                                                       value="{{ old('details.behance') }}"
                                                       placeholder="https://behance.net/...">
                                            </div>
                                        </div>
                                        
                                        <!-- SoundCloud -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_soundcloud" class="form-label-modern">
                                                    <i class="fab fa-soundcloud me-1" style="color: #ff5500;"></i> SoundCloud
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_soundcloud" 
                                                       name="details[soundcloud]" 
                                                       value="{{ old('details.soundcloud') }}"
                                                       placeholder="https://soundcloud.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Spotify -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_spotify" class="form-label-modern">
                                                    <i class="fab fa-spotify me-1" style="color: #1db954;"></i> Spotify
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_spotify" 
                                                       name="details[spotify]" 
                                                       value="{{ old('details.spotify') }}"
                                                       placeholder="https://open.spotify.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- TripAdvisor -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_tripadvisor" class="form-label-modern">
                                                    <i class="fab fa-tripadvisor me-1" style="color: #00af87;"></i> TripAdvisor
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_tripadvisor" 
                                                       name="details[tripadvisor]" 
                                                       value="{{ old('details.tripadvisor') }}"
                                                       placeholder="https://tripadvisor.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Foursquare -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_foursquare" class="form-label-modern">
                                                    <i class="fab fa-foursquare me-1" style="color: #f94877;"></i> Foursquare
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_foursquare" 
                                                       name="details[foursquare]" 
                                                       value="{{ old('details.foursquare') }}"
                                                       placeholder="https://foursquare.com/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Yelp -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_yelp" class="form-label-modern">
                                                    <i class="fab fa-yelp me-1" style="color: #d32323;"></i> Yelp
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_yelp" 
                                                       name="details[yelp]" 
                                                       value="{{ old('details.yelp') }}"
                                                       placeholder="https://yelp.com/biz/...">
                                            </div>
                                        </div>
                                        
                                        <!-- Google Maps (établissement) -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group-modern">
                                                <label for="details_google_maps" class="form-label-modern">
                                                    <i class="fab fa-google me-1" style="color: #4285f4;"></i> Google Maps
                                                </label>
                                                <input type="url" 
                                                       class="form-control-modern" 
                                                       id="details_google_maps" 
                                                       name="details[google_maps]" 
                                                       value="{{ old('details.google_maps') }}"
                                                       placeholder="https://goo.gl/maps/...">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <small class="form-text-modern text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Remplissez uniquement les réseaux sociaux pertinents pour ce point d'intérêt.
                                    </small>
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
                                                    <option value="{{ $etablissement->id }}" {{ old('etablissement_id') == $etablissement->id ? 'selected' : '' }}>
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
                                                       {{ old('is_featured') ? 'checked' : '' }}>
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
                                                       checked>
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
                                       value="{{ old('details_url') }}"
                                       placeholder="mon-point-d-interet"
                                       readonly>
                                <small class="form-text-modern">Généré automatiquement</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FORM ACTIONS - BOUTONS TOUJOURS VISIBLES -->
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
                        <i class="fas fa-save me-2"></i>Créer le point
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

        // Couleurs et icônes par catégorie
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

            // Déclencher le changement si une valeur est déjà sélectionnée (old value)
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

            // Initialiser le compteur
            descriptionInput.dispatchEvent(new Event('input'));
        }

        // ==================== GESTION DES TABS ====================
        const tabs = document.querySelectorAll('.nav-link-modern');
        const tabPanes = document.querySelectorAll('.tab-pane-modern');
        const prevBtn = document.getElementById('prevTabBtn');
        const nextBtn = document.getElementById('nextTabBtn');
        const submitBtn = document.getElementById('submitBtn');

        // Trouver l'index de l'onglet actif
        let currentTabIndex = 0;
        tabs.forEach((tab, index) => {
            if (tab.classList.contains('active')) {
                currentTabIndex = index;
            }
        });

        // Fonction pour changer d'onglet
        function switchToTab(index) {
            if (index < 0 || index >= tabs.length) return;
            
            // Mettre à jour les boutons d'onglet
            tabs.forEach((tab, i) => {
                if (i === index) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });
            
            // Mettre à jour les panneaux
            tabPanes.forEach((pane, i) => {
                if (i === index) {
                    pane.classList.add('show', 'active');
                } else {
                    pane.classList.remove('show', 'active');
                }
            });
            
            // Afficher/masquer les boutons Previous/Next
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

        // Bouton précédent
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (currentTabIndex > 0) {
                    switchToTab(currentTabIndex - 1);
                }
            });
        }

        // Bouton suivant
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (currentTabIndex < tabs.length - 1) {
                    // Validation basique avant de passer à l'onglet suivant
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

        // Clic sur les onglets
        tabs.forEach((tab, index) => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                switchToTab(index);
            });
        });

        // Initialiser l'affichage des boutons
        if (currentTabIndex === 0 && prevBtn) {
            prevBtn.style.display = 'none';
        }
        if (currentTabIndex === tabs.length - 1 && nextBtn) {
            nextBtn.style.display = 'none';
        }

        // ==================== MAP PICKER ====================
        let map, marker;
        
        function initMap() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            const lat = parseFloat(latInput.value) || 45.5089;
            const lng = parseFloat(lngInput.value) || -73.5617;
            
            map = L.map('locationPickerMap').setView([lat, lng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Marqueur personnalisé
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
            
            map.on('click', function(e) {
                updatePosition(e.latlng.lat, e.latlng.lng);
            });
            
            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                updatePosition(position.lat, position.lng);
            });
        }
        
        function updatePosition(lat, lng) {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            marker.setLatLng([lat, lng]);
        }
        
        // Initialiser la carte si l'élément existe
        if (document.getElementById('locationPickerMap')) {
            initMap();
        }

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
                    reader.onload = function(e) {
                        mainImagePreview.src = e.target.result;
                        mainImagePreviewContainer.style.display = 'block';
                        uploadArea.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            removeMainImageBtn.addEventListener('click', function() {
                mainImageInput.value = '';
                mainImagePreviewContainer.style.display = 'none';
                uploadArea.style.display = 'block';
            });
            
            // Drag & drop
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
                    reader.onload = function(e) {
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
                const url = this.value;
                const videoId = extractYoutubeId(url);
                
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
        
        if (galleryInput && galleryPreview && galleryUploadArea) {
            // Ouvrir le sélecteur de fichiers au clic sur la zone
            galleryUploadArea.addEventListener('click', function() {
                galleryInput.click();
            });
            
            // Gérer la sélection des fichiers
            galleryInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                
                if (files.length === 0) return;
                
                // Vider la prévisualisation
                galleryPreview.innerHTML = '';
                
                // Afficher chaque image
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
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
                
                // Cacher la zone d'upload
                galleryUploadArea.style.display = 'none';
            });
            
            // Supprimer une image de la galerie
            galleryPreview.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.btn-remove-gallery');
                
                if (removeBtn) {
                    // Supprimer l'élément de la prévisualisation
                    removeBtn.closest('.gallery-preview-item').remove();
                    
                    // Si plus d'images, réafficher la zone d'upload
                    if (galleryPreview.children.length === 0) {
                        galleryUploadArea.style.display = 'flex';
                        galleryInput.value = ''; // Reset input
                    } else {
                        alert('Note: Pour supprimer définitivement, veuillez re-sélectionner les images.');
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
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // enlève les accents
                    .replace(/[^\w\s-]/g, '') // enlève les caractères spéciaux
                    .replace(/\s+/g, '-') // remplace les espaces par -
                    .replace(/--+/g, '-') // remplace les multiples - par un seul
                    .trim();
                
                slugInput.value = slug;
            });
        }

        // ==================== VALIDATION FORMULAIRE ====================
        const mapPointForm = document.getElementById('mapPointForm');
        
        if (mapPointForm) {
            mapPointForm.addEventListener('submit', function(e) {
                const title = document.getElementById('title').value;
                const category = document.getElementById('category').value;
                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;
                
                if (!title || !category || !lat || !lng) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires (titre, catégorie, latitude, longitude)');
                    
                    // Aller à l'onglet concerné
                    if (!title || !category) switchToTab(0);
                    else if (!lat || !lng) switchToTab(1);
                    
                    return false;
                }

                // Désactiver le bouton submit pour éviter les doubles soumissions
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
                }
            });
        }
    });
</script>

    <style>
        /* Form Card Styles */
        .form-card {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        /* Tabs Modern */
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
            transform: translateY(-2px);
        }

        .nav-link-modern.active {
            background: #3a56e4;
            color: white;
            box-shadow: 0 5px 15px rgba(27, 79, 107, 0.3);
        }

        /* Tab Content */
        .tab-content-modern {
            min-height: 400px;
        }

        .tab-pane-modern {
            display: none;
        }

        .tab-pane-modern.show.active {
            display: block;
        }

        /* Form Sections */
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

        /* Form Controls */
        .form-group-modern {
            margin-bottom: 20px;
        }

        .form-label-modern {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .form-label-modern.required::after {
            content: '*';
            color: #ef476f;
            margin-left: 4px;
        }

        .form-control-modern {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e9f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: white;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(27, 79, 107, 0.1);
        }

        .form-select-modern {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e9f0;
            border-radius: 12px;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
        }

        .form-text-modern {
            display: block;
            margin-top: 5px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Map */
        .map-picker-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .custom-marker {
            background: transparent;
            border: none;
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .marker-icon i {
            transform: rotate(45deg);
        }

        /* Image Upload */
        .main-image-upload {
            border: 2px dashed #e0e9f0;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            background: white;
            transition: all 0.3s ease;
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

        .upload-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .upload-text {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .file-input {
            display: none;
        }

        .image-preview-container {
            position: relative;
            display: inline-block;
            margin: 0 auto;
        }

        .img-preview {
            max-width: 300px;
            max-height: 200px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Gallery */
        .gallery-upload {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .gallery-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            min-height: 100px;
        }

        .gallery-preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .gallery-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-remove-gallery {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #ef476f;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 10px;
            padding: 0;
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
            transition: all 0.3s ease;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .upload-area-small:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .upload-area-small i {
            font-size: 1.5rem;
        }

        /* Video Preview */
        .video-preview {
            position: relative;
            display: inline-block;
        }

        .video-thumbnail {
            width: 100%;
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
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
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        /* Custom Checkbox */
        .custom-control {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
        }

        .custom-control-input {
            position: absolute;
            opacity: 0;
        }

        .custom-control-label {
            cursor: pointer;
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

        /* Settings */
        .settings-options {
            background: white;
            padding: 15px;
            border-radius: 12px;
            border: 2px solid #eef4f9;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eef4f9;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #06b48a, #049a72);
            color: white;
        }

        .btn-secondary {
            background: #e9ecef;
            color: #495057;
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

        .ms-auto {
            margin-left: auto;
        }
        
        /* Centrage des boutons */
        .btn-group-center {
            display: flex;
            gap: 10px;
            margin: 0 auto;
        }

        /* Social Media Section */
        .social-media-section {
            background: #f8fbfe;
            border-radius: 16px;
            padding: 20px;
            margin-top: 25px;
        }

        .form-subsection-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eef4f9;
        }

        /* Amélioration des champs de réseaux sociaux */
        .col-lg-4 .form-group-modern {
            margin-bottom: 15px;
        }

        .col-lg-4 .form-label-modern {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .col-lg-4 .form-label-modern i {
            font-size: 1.2rem;
            width: 24px;
        }
    </style>
@endsection