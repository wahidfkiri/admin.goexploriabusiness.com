@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-plus-circle"></i></span>
                Créer un nouveau produit/service
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
                <button type="button" class="btn btn-outline-primary" id="saveDraftBtn">
                    <i class="fas fa-save me-2"></i>Enregistrer brouillon
                </button>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps-modern">
            <div class="step-item active" id="step1">
                <div class="step-number">1</div>
                <div class="step-label">Type & Catégorie</div>
            </div>
            <div class="step-item" id="step2">
                <div class="step-number">2</div>
                <div class="step-label">Informations générales</div>
            </div>
            <div class="step-item" id="step3">
                <div class="step-number">3</div>
                <div class="step-label">Prix & Stock</div>
            </div>
            <div class="step-item" id="step4">
                <div class="step-number">4</div>
                <div class="step-label">Média & SEO</div>
            </div>
        </div>

        <!-- Main Form -->
        <form id="productForm" enctype="multipart/form-data">
            @csrf
            
            <!-- Step 1: Type & Category -->
            <div class="form-step active" id="step1-content">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-tag me-2"></i>Type de produit/service
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-label-modern required">Type principal</label>
                                <div class="type-selector-grid">
                                    <!-- Produit physique -->
                                    <div class="type-card" data-type="produit_physique">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #45b7d1, #3a9bb8);">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                        <div class="type-name">Produit physique</div>
                                        <div class="type-desc">Articles avec stock, livraison</div>
                                    </div>
                                    
                                    <!-- Produit numérique -->
                                    <div class="type-card" data-type="produit_numerique">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #96ceb4, #7dba9a);">
                                            <i class="fas fa-file-download"></i>
                                        </div>
                                        <div class="type-name">Produit numérique</div>
                                        <div class="type-desc">E-books, logiciels, téléchargements</div>
                                    </div>
                                    
                                    <!-- Service -->
                                    <div class="type-card" data-type="service">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #feca57, #ffb347);">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="type-name">Service</div>
                                        <div class="type-desc">Prestations, consulting</div>
                                    </div>
                                    
                                    <!-- Prestation -->
                                    <div class="type-card" data-type="prestation">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #ff6b6b, #ee5253);">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <div class="type-name">Prestation</div>
                                        <div class="type-desc">Forfaits, missions</div>
                                    </div>
                                    
                                    <!-- Forfait -->
                                    <div class="type-card" data-type="forfait">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <div class="type-name">Forfait</div>
                                        <div class="type-desc">Pack de services combinés</div>
                                    </div>
                                    
                                    <!-- Abonnement -->
                                    <div class="type-card" data-type="abonnement">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #1abc9c, #16a085);">
                                            <i class="fas fa-sync-alt"></i>
                                        </div>
                                        <div class="type-name">Abonnement</div>
                                        <div class="type-desc">Récurrence mensuelle/annuelle</div>
                                    </div>
                                    
                                    <!-- Licence -->
                                    <div class="type-card" data-type="licence">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #34495e, #2c3e50);">
                                            <i class="fas fa-certificate"></i>
                                        </div>
                                        <div class="type-name">Licence</div>
                                        <div class="type-desc">Droits d'utilisation</div>
                                    </div>
                                    
                                    <!-- Hébergement -->
                                    <div class="type-card" data-type="hebergement">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #e67e22, #d35400);">
                                            <i class="fas fa-server"></i>
                                        </div>
                                        <div class="type-name">Hébergement</div>
                                        <div class="type-desc">Serveurs, domaines</div>
                                    </div>
                                    
                                    <!-- Maintenance -->
                                    <div class="type-card" data-type="maintenance">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #95a5a6, #7f8c8d);">
                                            <i class="fas fa-wrench"></i>
                                        </div>
                                        <div class="type-name">Maintenance</div>
                                        <div class="type-desc">Support technique, SAV</div>
                                    </div>
                                    
                                    <!-- Formation -->
                                    <div class="type-card" data-type="formation">
                                        <div class="type-icon" style="background: linear-gradient(135deg, #f1c40f, #f39c12);">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <div class="type-name">Formation</div>
                                        <div class="type-desc">Cours, ateliers</div>
                                    </div>
                                </div>
                                <input type="hidden" name="main_type" id="main_type" required>
                                <div class="invalid-feedback" id="type-error">Veuillez sélectionner un type</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label-modern">Famille</label>
                                <select class="form-select-modern" name="product_family_id" id="product_family_id">
                                    <option value="">Sélectionner une famille</option>
                                    @foreach($families as $family)
                                        <option value="{{ $family->id }}">{{ $family->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label-modern">Catégorie</label>
                                <select class="form-select-modern" name="product_category_id" id="product_category_id">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" data-family="{{ $category->product_family_id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer-modern">
                        <button type="button" class="btn btn-primary next-step" data-next="2">
                            Étape suivante <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: General Information -->
            <div class="form-step" id="step2-content" style="display: none;">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-info-circle me-2"></i>Informations générales
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label-modern required">Nom du produit/service</label>
                                    <input type="text" class="form-control-modern" name="name" id="name" 
                                           placeholder="Ex: Création site web, Pack office, etc." required>
                                    <div class="invalid-feedback">Le nom est requis</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label-modern">Référence interne</label>
                                    <input type="text" class="form-control-modern" name="reference" id="reference" 
                                           placeholder="Généré automatiquement">
                                    <small class="text-muted">Laissez vide pour génération auto</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-modern">SKU / Code barre</label>
                                    <input type="text" class="form-control-modern" name="sku" id="sku" 
                                           placeholder="SKU-12345">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-modern">Code barre (UPC/EAN)</label>
                                    <input type="text" class="form-control-modern" name="barcode" id="barcode" 
                                           placeholder="123456789012">
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label-modern">Description courte</label>
                                    <textarea class="form-control-modern" name="short_description" id="short_description" 
                                              rows="2" placeholder="Brève description (max 255 caractères)"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label-modern">Description longue</label>
                                    <textarea class="form-control-modern" name="long_description" id="long_description" 
                                              rows="10" placeholder="Description détaillée..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer-modern d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">
                            <i class="fas fa-arrow-left me-2"></i>Étape précédente
                        </button>
                        <button type="button" class="btn btn-primary next-step" data-next="3">
                            Étape suivante <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Price & Stock -->
            <div class="form-step" id="step3-content" style="display: none;">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-euro-sign me-2"></i>Prix et gestion
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <!-- Price Section -->
                        <div id="priceSection">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label-modern required">Prix de vente TTC</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" class="form-control-modern" name="price_ttc" id="price_ttc" required>
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Prix de vente HT</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" class="form-control-modern" name="price_ht" id="price_ht" readonly>
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Taux TVA</label>
                                        <select class="form-select-modern" name="tax_rate" id="tax_rate">
                                            <option value="20.00">TVA 20%</option>
                                            <option value="10.00">TVA 10%</option>
                                            <option value="5.50">TVA 5.5%</option>
                                            <option value="0.00">TVA 0%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Unité de facturation</label>
                                        <select class="form-select-modern" name="billing_unit" id="billing_unit">
                                            <option value="unite">À l'unité</option>
                                            <option value="heure">À l'heure</option>
                                            <option value="jour">Par jour</option>
                                            <option value="mois">Par mois</option>
                                            <option value="an">Par an</option>
                                            <option value="forfait">Forfait</option>
                                            <option value="projet">Par projet</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Prix d'achat HT (coût)</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" class="form-control-modern" name="purchase_price_ht" id="purchase_price_ht">
                                            <span class="input-group-text">€</span>
                                        </div>
                                        <small class="text-muted">Pour calculer la marge</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Physical product fields -->
                        <div class="physical-fields" style="display: none;">
                            <h4 class="section-subtitle">Gestion de stock</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Gestion du stock</label>
                                        <select class="form-select-modern" name="stock_management" id="stock_management">
                                            <option value="oui">Oui</option>
                                            <option value="non">Non</option>
                                            <option value="sur_commande">Sur commande</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Stock actuel</label>
                                        <input type="number" class="form-control-modern" name="current_stock" id="current_stock" value="0">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Stock minimum</label>
                                        <input type="number" class="form-control-modern" name="minimum_stock" id="minimum_stock" value="0">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Emplacement</label>
                                        <input type="text" class="form-control-modern" name="stock_location" id="stock_location" 
                                               placeholder="Aisle 1, Row 2">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service fields -->
                        <div class="service-fields" style="display: none;">
                            <h4 class="section-subtitle">Paramètres du service</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Durée estimée (minutes)</label>
                                        <input type="number" class="form-control-modern" name="estimated_duration_minutes" id="estimated_duration_minutes">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check-modern mt-4">
                                            <input type="checkbox" class="form-check-input" name="requires_appointment" id="requires_appointment" value="1">
                                            <label class="form-check-label" for="requires_appointment">
                                                Nécessite un rendez-vous
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription fields -->
                        <div class="subscription-fields" style="display: none;">
                            <h4 class="section-subtitle">Paramètres d'abonnement</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Période de facturation</label>
                                        <select class="form-select-modern" name="billing_period" id="billing_period">
                                            <option value="mensuel">Mensuel</option>
                                            <option value="trimestriel">Trimestriel</option>
                                            <option value="semestriel">Semestriel</option>
                                            <option value="annuel">Annuel</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check-modern mt-4">
                                            <input type="checkbox" class="form-check-input" name="has_commitment" id="has_commitment" value="1">
                                            <label class="form-check-label" for="has_commitment">
                                                Avec engagement
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Durée engagement (mois)</label>
                                        <input type="number" class="form-control-modern" name="commitment_months" id="commitment_months" value="12" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Variants section -->
                        <div class="variants-section">
                            <h4 class="section-subtitle">
                                <i class="fas fa-code-branch me-2"></i>Variantes (options)
                                <button type="button" class="btn btn-sm btn-outline-primary ms-3" id="addVariantBtn">
                                    <i class="fas fa-plus"></i> Ajouter une variante
                                </button>
                            </h4>
                            
                            <div id="variants-container"></div>
                            
                            <div class="variant-template d-none">
                                <div class="variant-item card mt-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control-modern" placeholder="Nom (ex: Rouge, XL)" name="variants[INDEX][name]">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control-modern" placeholder="SKU" name="variants[INDEX][sku]">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" step="0.01" class="form-control-modern" placeholder="Prix +" name="variants[INDEX][price_adjustment]" value="0">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control-modern" placeholder="Stock" name="variants[INDEX][stock]" value="0">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="file" class="form-control-modern" name="variants[INDEX][image]" accept="image/*">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-variant">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="variants[INDEX][attributes]" value='{"generated":true}'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer-modern d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                            <i class="fas fa-arrow-left me-2"></i>Étape précédente
                        </button>
                        <button type="button" class="btn btn-primary next-step" data-next="4">
                            Étape suivante <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 4: Media & SEO -->
            <div class="form-step" id="step4-content" style="display: none;">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-image me-2"></i>Média et référencement
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-modern">Image principale</label>
                                    <div class="image-upload-area" id="mainImageArea">
                                        <input type="file" class="image-upload-input" name="main_image" id="main_image" accept="image/*">
                                        <div class="image-upload-placeholder">
                                            <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                            <p>Cliquez ou glissez une image</p>
                                            <small class="text-muted">JPG, PNG, GIF max 2MB</small>
                                        </div>
                                        <div class="image-preview" style="display: none;">
                                            <img src="" alt="Preview">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-modern">Galerie d'images</label>
                                    <div class="gallery-upload-area" id="galleryArea">
                                        <input type="file" class="gallery-upload-input" name="gallery_images[]" id="gallery_images" multiple accept="image/*">
                                        <div class="gallery-upload-placeholder">
                                            <i class="fas fa-images fa-3x mb-2"></i>
                                            <p>Cliquez pour ajouter plusieurs images</p>
                                        </div>
                                        <div class="gallery-preview" id="galleryPreview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="section-subtitle mt-4">SEO - Optimisation pour les moteurs de recherche</h4>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label-modern">Meta titre</label>
                                    <input type="text" class="form-control-modern" name="meta_title" id="meta_title" 
                                           placeholder="Titre pour les moteurs de recherche">
                                    <small class="text-muted">Laissez vide pour utiliser le nom du produit</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label-modern">Slug (URL)</label>
                                    <input type="text" class="form-control-modern" name="slug" id="slug" 
                                           placeholder="nom-du-produit">
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label-modern">Meta description</label>
                                    <textarea class="form-control-modern" name="meta_description" id="meta_description" 
                                              rows="3" placeholder="Description pour les moteurs de recherche (max 160 caractères)"></textarea>
                                    <div class="char-counter">
                                        <span id="meta_desc_count">0</span>/160
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label-modern">Mots-clés (séparés par des virgules)</label>
                                    <input type="text" class="form-control-modern" name="meta_keywords" id="meta_keywords" 
                                           placeholder="mot-clé1, mot-clé2, mot-clé3">
                                </div>
                            </div>
                        </div>

                        <!-- Status switches -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="status-switch">
                                    <label class="switch-label">
                                        <span class="switch-text">Disponible à la vente</span>
                                        <div class="custom-switch">
                                            <input type="checkbox" name="is_available_for_sale" id="is_available_for_sale" value="1" checked>
                                            <span class="switch-slider"></span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="status-switch">
                                    <label class="switch-label">
                                        <span class="switch-text">Visible sur le site</span>
                                        <div class="custom-switch">
                                            <input type="checkbox" name="is_public" id="is_public" value="1" checked>
                                            <span class="switch-slider"></span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="status-switch">
                                    <label class="switch-label">
                                        <span class="switch-text">Produit taxable</span>
                                        <div class="custom-switch">
                                            <input type="checkbox" name="is_taxable" id="is_taxable" value="1" checked>
                                            <span class="switch-slider"></span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="status-switch">
                                    <label class="switch-label">
                                        <span class="switch-text">Commission (%)</span>
                                        <input type="number" step="0.1" class="form-control-modern form-control-sm" name="commission_percentage" id="commission_percentage" value="0">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer-modern d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-prev="3">
                            <i class="fas fa-arrow-left me-2"></i>Étape précédente
                        </button>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-check-circle me-2"></i>Créer le produit/service
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Floating Action Button -->
        <div class="fab-modern" id="helpFab" data-bs-toggle="tooltip" title="Besoin d'aide ?">
            <i class="fas fa-question"></i>
        </div>
    </main>

    <!-- HELP MODAL -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Guide de création</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6><i class="fas fa-tag text-primary me-2"></i>Type de produit</h6>
                    <p>Sélectionnez le type qui correspond le mieux à ce que vous vendez.</p>
                    
                    <h6><i class="fas fa-euro-sign text-success me-2"></i>Prix et TVA</h6>
                    <p>Le prix TTC est calculé automatiquement à partir du prix HT et de la TVA.</p>
                    
                    <h6><i class="fas fa-pen-fancy text-info me-2"></i>Éditeur WYSIWYG</h6>
                    <p>Utilisez l'éditeur pour formater votre description longue avec du texte enrichi.</p>
                    
                    <h6><i class="fas fa-cubes text-warning me-2"></i>Variantes</h6>
                    <p>Utilisez les variantes pour les produits avec plusieurs options (tailles, couleurs).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Help modal
            const helpModal = new bootstrap.Modal(document.getElementById('helpModal'));
            const helpFab = document.getElementById('helpFab');
            if (helpFab) {
                helpFab.addEventListener('click', function() {
                    helpModal.show();
                });
            }

            // Initialize CKEditor
            let editorInstance = null;
            
            ClassicEditor
                .create(document.querySelector('#long_description'), {
                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            '|',
                            'alignment',
                            '|',
                            'bulletedList',
                            'numberedList',
                            'outdent',
                            'indent',
                            '|',
                            'link',
                            'blockQuote',
                            'insertTable',
                            'mediaEmbed',
                            '|',
                            'undo',
                            'redo'
                        ]
                    },
                    table: {
                        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
                        ]
                    },
                    language: 'fr',
                    placeholder: 'Saisissez votre description détaillée ici...',
                })
                .then(editor => {
                    editorInstance = editor;
                    console.log('CKEditor initialized successfully');
                })
                .catch(error => {
                    console.error('Error initializing CKEditor:', error);
                });

            // Step navigation
            let currentStep = 1;

            // Type selection
            const typeCards = document.querySelectorAll('.type-card');
            const typeInput = document.getElementById('main_type');

            if (typeCards.length > 0 && typeInput) {
                typeCards.forEach(card => {
                    card.addEventListener('click', function() {
                        typeCards.forEach(c => c.classList.remove('selected'));
                        this.classList.add('selected');
                        typeInput.value = this.dataset.type;
                        const typeError = document.getElementById('type-error');
                        if (typeError) typeError.style.display = 'none';
                        
                        // Show/hide specific fields based on type
                        showTypeSpecificFields(this.dataset.type);
                    });
                });
            }

            // Next step buttons
            document.querySelectorAll('.next-step').forEach(btn => {
                btn.addEventListener('click', function() {
                    const nextStep = parseInt(this.dataset.next);
                    
                    // Validate current step before proceeding
                    if (validateStep(currentStep)) {
                        goToStep(nextStep);
                    }
                });
            });

            // Previous step buttons
            document.querySelectorAll('.prev-step').forEach(btn => {
                btn.addEventListener('click', function() {
                    const prevStep = parseInt(this.dataset.prev);
                    goToStep(prevStep);
                });
            });

            function goToStep(step) {
                // Hide all steps
                document.querySelectorAll('.form-step').forEach(el => {
                    el.style.display = 'none';
                });
                
                // Show selected step
                const stepContent = document.getElementById(`step${step}-content`);
                if (stepContent) stepContent.style.display = 'block';
                
                // Update progress steps
                document.querySelectorAll('.step-item').forEach((el, index) => {
                    if (index + 1 < step) {
                        el.classList.add('completed');
                        el.classList.remove('active');
                    } else if (index + 1 === step) {
                        el.classList.add('active');
                        el.classList.remove('completed');
                    } else {
                        el.classList.remove('active', 'completed');
                    }
                });
                
                currentStep = step;
            }

            function validateStep(step) {
                switch(step) {
                    case 1:
                        if (!typeInput || !typeInput.value) {
                            const typeError = document.getElementById('type-error');
                            if (typeError) typeError.style.display = 'block';
                            if (typeInput) typeInput.scrollIntoView({ behavior: 'smooth' });
                            return false;
                        }
                        return true;
                    case 2:
                        const name = document.getElementById('name');
                        if (!name || !name.value) {
                            showAlert('danger', 'Le nom est requis');
                            if (name) name.focus();
                            return false;
                        }
                        return true;
                    case 3:
                        const price = document.getElementById('price_ttc');
                        if (!price || !price.value || price.value <= 0) {
                            showAlert('danger', 'Le prix doit être supérieur à 0');
                            if (price) price.focus();
                            return false;
                        }
                        return true;
                    default:
                        return true;
                }
            }

            // Price calculation
            const priceTtc = document.getElementById('price_ttc');
            const priceHt = document.getElementById('price_ht');
            const taxRate = document.getElementById('tax_rate');

            if (priceTtc && priceHt && taxRate) {
                function calculatePriceHT() {
                    if (priceTtc.value && taxRate.value) {
                        const ttc = parseFloat(priceTtc.value);
                        const tax = parseFloat(taxRate.value);
                        const ht = ttc / (1 + tax / 100);
                        priceHt.value = ht.toFixed(2);
                    }
                }

                priceTtc.addEventListener('input', calculatePriceHT);
                taxRate.addEventListener('change', calculatePriceHT);
            }

            // Show type specific fields
            function showTypeSpecificFields(type) {
                const physicalFields = document.querySelector('.physical-fields');
                const serviceFields = document.querySelector('.service-fields');
                const subscriptionFields = document.querySelector('.subscription-fields');

                if (physicalFields) physicalFields.style.display = 'none';
                if (serviceFields) serviceFields.style.display = 'none';
                if (subscriptionFields) subscriptionFields.style.display = 'none';

                switch(type) {
                    case 'produit_physique':
                    case 'produit_numerique':
                        if (physicalFields) physicalFields.style.display = 'block';
                        break;
                    case 'service':
                    case 'prestation':
                        if (serviceFields) serviceFields.style.display = 'block';
                        break;
                    case 'abonnement':
                        if (subscriptionFields) subscriptionFields.style.display = 'block';
                        break;
                }
            }

            // Commitment checkbox
            const hasCommitment = document.getElementById('has_commitment');
            const commitmentMonths = document.getElementById('commitment_months');

            if (hasCommitment && commitmentMonths) {
                hasCommitment.addEventListener('change', function() {
                    commitmentMonths.disabled = !this.checked;
                    if (this.checked) {
                        commitmentMonths.value = 12;
                    } else {
                        commitmentMonths.value = '';
                    }
                });
            }

            // Variants management
            let variantIndex = 0;
            const addVariantBtn = document.getElementById('addVariantBtn');
            const variantsContainer = document.getElementById('variants-container');
            const variantTemplate = document.querySelector('.variant-template');

            if (addVariantBtn && variantsContainer && variantTemplate) {
                addVariantBtn.addEventListener('click', function() {
                    const template = variantTemplate.cloneNode(true);
                    template.classList.remove('variant-template', 'd-none');
                    
                    const html = template.innerHTML.replace(/INDEX/g, variantIndex);
                    const div = document.createElement('div');
                    div.innerHTML = html;
                    
                    const variantItem = div.firstElementChild;
                    variantItem.querySelector('.remove-variant').addEventListener('click', function() {
                        variantItem.remove();
                    });
                    
                    variantsContainer.appendChild(variantItem);
                    variantIndex++;
                });
            }

            // Image preview
            const mainImageInput = document.getElementById('main_image');
            const mainImageArea = document.getElementById('mainImageArea');
            
            if (mainImageArea && mainImageInput) {
                const imagePreview = mainImageArea.querySelector('.image-preview');
                const imagePlaceholder = mainImageArea.querySelector('.image-upload-placeholder');
                const previewImg = imagePreview ? imagePreview.querySelector('img') : null;
                const removeImageBtn = imagePreview ? imagePreview.querySelector('.remove-image') : null;

                if (mainImageInput && imagePreview && imagePlaceholder && previewImg && removeImageBtn) {
                    mainImageInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImg.src = e.target.result;
                                imagePlaceholder.style.display = 'none';
                                imagePreview.style.display = 'block';
                            }
                            reader.readAsDataURL(file);
                        }
                    });

                    removeImageBtn.addEventListener('click', function() {
                        mainImageInput.value = '';
                        imagePreview.style.display = 'none';
                        imagePlaceholder.style.display = 'block';
                    });
                }
            }

            // Gallery preview
            const galleryInput = document.getElementById('gallery_images');
            const galleryPreview = document.getElementById('galleryPreview');

            if (galleryInput && galleryPreview) {
                galleryInput.addEventListener('change', function(e) {
                    galleryPreview.innerHTML = '';
                    Array.from(e.target.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('div');
                            img.className = 'gallery-thumb';
                            img.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                            galleryPreview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    });
                });
            }

            // Slug generation
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    if (!slugInput.value) {
                        slugInput.value = this.value
                            .toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/\s+/g, '-');
                    }
                });
            }

            // Meta description counter
            const metaDesc = document.getElementById('meta_description');
            const metaDescCount = document.getElementById('meta_desc_count');

            if (metaDesc && metaDescCount) {
                metaDesc.addEventListener('input', function() {
                    metaDescCount.textContent = this.value.length;
                    if (this.value.length > 160) {
                        metaDescCount.style.color = '#ef476f';
                    } else {
                        metaDescCount.style.color = '#666';
                    }
                });
            }

            // Form submission
            const productForm = document.getElementById('productForm');
            const submitBtn = document.getElementById('submitBtn');

            if (productForm && submitBtn) {
                productForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Validate all steps
                    if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
                        return;
                    }
                    
                    // Show loading
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Création en cours...';
                    submitBtn.disabled = true;
                    
                    // Create FormData
                    const formData = new FormData(this);
                    
                    // Send AJAX request
                    $.ajax({
                        url: '{{ route("products.store") }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                showAlert('success', 'Produit créé avec succès !');
                                setTimeout(() => {
                                    window.location.href = '{{ route("products.index") }}';
                                }, 1500);
                            } else {
                                showAlert('danger', response.message || 'Erreur lors de la création');
                            }
                        },
                        error: function(xhr) {
                            let message = 'Erreur lors de la création';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                            }
                            showAlert('danger', message);
                        },
                        complete: function() {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    });
                });
            }

            // Save draft
            const saveDraftBtn = document.getElementById('saveDraftBtn');
            if (saveDraftBtn && productForm) {
                saveDraftBtn.addEventListener('click', function() {
                    const formData = new FormData(productForm);
                    formData.append('status', 'brouillon');
                    
                    $.ajax({
                        url: '{{ route("products.store") }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                showAlert('success', 'Brouillon sauvegardé !');
                            }
                        },
                        error: function(xhr) {
                            showAlert('danger', 'Erreur lors de la sauvegarde du brouillon');
                        }
                    });
                });
            }

            // Show alert function
            function showAlert(type, message) {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-custom-modern alert-dismissible fade show`;
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }

            // Category filtering by family
            const familySelect = document.getElementById('product_family_id');
            const categorySelect = document.getElementById('product_category_id');
            
            if (familySelect && categorySelect) {
                familySelect.addEventListener('change', function() {
                    const familyId = this.value;
                    
                    Array.from(categorySelect.options).forEach(option => {
                        if (option.value === '') return;
                        if (familyId === '' || option.dataset.family === familyId) {
                            option.style.display = '';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                    
                    categorySelect.value = '';
                });
            }
        });
    </script>

    <style>
        /* Progress Steps */
        .progress-steps-modern {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .step-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .step-item:not(:last-child):after {
            content: '';
            position: absolute;
            right: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 2px;
            background: #e0e0e0;
        }

        .step-item.active:not(:last-child):after {
            background: linear-gradient(90deg, var(--primary-color), #3a56e4);
        }

        .step-number {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #f0f0f0;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s;
        }

        .step-item.active .step-number {
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            color: white;
        }

        .step-item.completed .step-number {
            background: #06b48a;
            color: white;
        }

        .step-item.completed .step-number:after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
        }

        .step-label {
            font-weight: 500;
            color: #333;
        }

        .step-item.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Type Selector Grid */
        .type-selector-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .type-card {
            background: white;
            border: 2px solid #eaeaea;
            border-radius: 12px;
            padding: 20px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }

        .type-card.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(69, 183, 209, 0.05), rgba(58, 86, 228, 0.05));
        }

        .type-icon {
            width: 60px;
            height: 60px;
            border-radius: 30px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .type-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .type-desc {
            font-size: 0.8rem;
            color: #666;
        }

        /* Section subtitle */
        .section-subtitle {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 25px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        /* Image upload areas */
        .image-upload-area {
            position: relative;
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .image-upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(69, 183, 209, 0.05);
        }

        .image-upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .image-preview {
            position: relative;
            display: inline-block;
        }

        .image-preview img {
            max-height: 200px;
            border-radius: 8px;
        }

        .image-preview .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
        }

        .gallery-upload-area {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }

        .gallery-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }

        .gallery-thumb {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Status switches */
        .status-switch {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .switch-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            margin-bottom: 0;
        }

        .switch-text {
            font-weight: 500;
            color: #333;
        }

        .custom-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .switch-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .switch-slider {
            background: linear-gradient(135deg, #06b48a, #049a72);
        }

        input:checked + .switch-slider:before {
            transform: translateX(26px);
        }

        /* Character counter */
        .char-counter {
            text-align: right;
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        /* Card footer */
        .card-footer-modern {
            padding: 20px 25px;
            background: #f8f9fa;
            border-top: 1px solid #eaeaea;
        }

        /* Required field indicator */
        .form-label-modern.required:after {
            content: ' *';
            color: #ef476f;
        }

        /* CKEditor styles */
        .ck-editor__editable {
            min-height: 300px;
            max-height: 500px;
            border-radius: 0 0 8px 8px !important;
            border: 2px solid #eaeaea !important;
            border-top: none !important;
        }

        .ck-editor__editable:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(69, 183, 209, 0.1) !important;
        }

        .ck.ck-toolbar {
            border-radius: 8px 8px 0 0 !important;
            border: 2px solid #eaeaea !important;
            border-bottom: none !important;
            background: #f8f9fa !important;
        }

        .alert-custom-modern {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .progress-steps-modern {
                flex-direction: column;
                gap: 15px;
            }
            
            .step-item:not(:last-child):after {
                display: none;
            }
            
            .type-selector-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .type-card {
                padding: 15px 10px;
            }
            
            .type-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
        }
    </style>
@endsection