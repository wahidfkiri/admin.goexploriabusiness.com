@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-eye"></i></span>
                Détails du produit/service
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
                <button type="button" class="btn btn-danger" id="deleteProductBtn">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>
            </div>
        </div>

        <!-- Product Navigation Tabs -->
        <div class="product-tabs-modern">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                        <i class="fas fa-info-circle me-2"></i>Aperçu général
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants" type="button" role="tab">
                        <i class="fas fa-code-branch me-2"></i>Variantes
                        @if($product->variants->count() > 0)
                            <span class="badge bg-primary ms-2">{{ $product->variants->count() }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab">
                        <i class="fas fa-images me-2"></i>Galerie
                        @if($product->gallery_images && count(json_decode($product->gallery_images)) > 0)
                            <span class="badge bg-primary ms-2">{{ count(json_decode($product->gallery_images)) }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab">
                        <i class="fas fa-chart-line me-2"></i>Ventes & Statistiques
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="productTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <!-- Left Column - Main Info -->
                    <div class="col-lg-8">
                        <!-- Product Header Card -->
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-box me-2"></i>{{ $product->name }}
                                </h3>
                                <div class="product-badges">
                                    @if($product->is_available_for_sale)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Disponible</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Non disponible</span>
                                    @endif
                                    
                                    @if($product->is_public)
                                        <span class="badge bg-info"><i class="fas fa-globe me-1"></i>Public</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-lock me-1"></i>Privé</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="product-show-header">
                                    <div class="product-show-image">
                                        @if($product->main_image)
                                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="img-fluid">
                                        @else
                                            <div class="no-image-placeholder">
                                                <i class="fas fa-image fa-4x"></i>
                                                <p>Aucune image</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="product-show-quick-info">
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <span class="info-label">Référence</span>
                                                <span class="info-value">{{ $product->reference }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">SKU</span>
                                                <span class="info-value">{{ $product->sku ?? 'N/A' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Type</span>
                                                <span class="info-value">{!! \Vendor\Ecommerce\Helpers\ProductHelpers::getTypeBadge($product->main_type) !!}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Famille</span>
                                                <span class="info-value">{{ $product->family->name ?? 'Non catégorisé' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Catégorie</span>
                                                <span class="info-value">{{ $product->category->name ?? 'Non catégorisé' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Créé le</span>
                                                <span class="info-value">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Dernière modification</span>
                                                <span class="info-value">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Vues</span>
                                                <span class="info-value">{{ $product->views_count ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Card -->
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                @if($product->short_description)
                                    <div class="short-description mb-4">
                                        <h5>Description courte</h5>
                                        <p>{{ $product->short_description }}</p>
                                    </div>
                                @endif
                                
                                @if($product->long_description)
                                    <div class="long-description">
                                        <h5>Description détaillée</h5>
                                        <div class="description-content">
                                            {!! $product->long_description !!}
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">Aucune description fournie.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Pricing & Details -->
                    <div class="col-lg-4">
                        <!-- Pricing Card -->
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-tag me-2"></i>Prix
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="price-display">
                                    <div class="price-current">{{ number_format($product->price_ttc, 2) }} €</div>
                                    <div class="price-details">
                                        <div class="price-detail-item">
                                            <span>Prix HT</span>
                                            <span>{{ number_format($product->price_ht, 2) }} €</span>
                                        </div>
                                        <div class="price-detail-item">
                                            <span>TVA</span>
                                            <span>{{ $product->tax_rate }}%</span>
                                        </div>
                                        @if($product->purchase_price_ht)
                                        <div class="price-detail-item">
                                            <span>Prix d'achat HT</span>
                                            <span>{{ number_format($product->purchase_price_ht, 2) }} €</span>
                                        </div>
                                        <div class="price-detail-item highlight">
                                            <span>Marge</span>
                                            <span>{{ number_format($product->price_ht - $product->purchase_price_ht, 2) }} €</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="info-list">
                                    <div class="info-list-item">
                                        <i class="fas fa-clock me-2 text-muted"></i>
                                        <span>Unité de facturation : <strong>{{ \Vendor\Ecommerce\Helpers\ProductHelpers::getBillingUnitText($product->billing_unit) }}</strong></span>
                                    </div>
                                    
                                    @if($product->commission_percentage > 0)
                                    <div class="info-list-item">
                                        <i class="fas fa-percent me-2 text-muted"></i>
                                        <span>Commission : <strong>{{ $product->commission_percentage }}%</strong></span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stock Card (for physical products) -->
                        @if(in_array($product->main_type, ['produit_physique', 'produit_numerique']))
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-cubes me-2"></i>Stock
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="stock-info">
                                    <div class="stock-status">
                                        @if($product->stock_management == 'non')
                                            <span class="badge bg-info">Stock non géré</span>
                                        @elseif($product->stock_management == 'sur_commande')
                                            <span class="badge bg-warning">Sur commande</span>
                                        @else
                                            @if($product->current_stock <= 0)
                                                <span class="badge bg-danger">Rupture de stock</span>
                                            @elseif($product->current_stock <= $product->minimum_stock)
                                                <span class="badge bg-warning">Stock faible</span>
                                            @else
                                                <span class="badge bg-success">En stock</span>
                                            @endif
                                        @endif
                                    </div>
                                    
                                    <div class="stock-details mt-3">
                                        @if($product->stock_management == 'oui')
                                        <div class="stock-detail-item">
                                            <span>Stock actuel</span>
                                            <span class="stock-value">{{ $product->current_stock }}</span>
                                        </div>
                                        <div class="stock-detail-item">
                                            <span>Stock minimum</span>
                                            <span class="stock-value">{{ $product->minimum_stock }}</span>
                                        </div>
                                        @if($product->maximum_stock)
                                        <div class="stock-detail-item">
                                            <span>Stock maximum</span>
                                            <span class="stock-value">{{ $product->maximum_stock }}</span>
                                        </div>
                                        @endif
                                        @if($product->stock_location)
                                        <div class="stock-detail-item">
                                            <span>Emplacement</span>
                                            <span class="stock-value">{{ $product->stock_location }}</span>
                                        </div>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Service Details Card (for services) -->
                        @if(in_array($product->main_type, ['service', 'prestation']))
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-cogs me-2"></i>Détails du service
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="service-details">
                                    @if($product->estimated_duration_minutes)
                                    <div class="service-detail-item">
                                        <i class="fas fa-hourglass-half me-2"></i>
                                        <span>Durée estimée : <strong>{{ $product->estimated_duration_minutes }} minutes</strong></span>
                                    </div>
                                    @endif
                                    
                                    <div class="service-detail-item">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        <span>Rendez-vous : 
                                            <strong>{{ $product->requires_appointment ? 'Requis' : 'Non requis' }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Subscription Details Card (for subscriptions) -->
                        @if($product->main_type == 'abonnement')
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-sync-alt me-2"></i>Détails de l'abonnement
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="subscription-details">
                                    <div class="subscription-detail-item">
                                        <span>Période de facturation</span>
                                        <span class="subscription-value">{{ getBillingPeriodText($product->billing_period) }}</span>
                                    </div>
                                    
                                    <div class="subscription-detail-item">
                                        <span>Engagement</span>
                                        <span class="subscription-value">
                                            @if($product->has_commitment)
                                                {{ $product->commitment_months }} mois
                                            @else
                                                Sans engagement
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- SEO Card -->
                        @if($product->meta_title || $product->meta_description || $product->meta_keywords)
                        <div class="main-card-modern mb-4">
                            <div class="card-header-modern">
                                <h3 class="card-title-modern">
                                    <i class="fas fa-search me-2"></i>SEO
                                </h3>
                            </div>
                            
                            <div class="card-body-modern">
                                <div class="seo-details">
                                    @if($product->meta_title)
                                    <div class="seo-detail-item">
                                        <span class="seo-label">Meta titre</span>
                                        <span class="seo-value">{{ $product->meta_title }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($product->meta_description)
                                    <div class="seo-detail-item">
                                        <span class="seo-label">Meta description</span>
                                        <span class="seo-value">{{ $product->meta_description }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($product->meta_keywords)
                                    <div class="seo-detail-item">
                                        <span class="seo-label">Mots-clés</span>
                                        <span class="seo-value">{{ $product->meta_keywords }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($product->slug)
                                    <div class="seo-detail-item">
                                        <span class="seo-label">Slug</span>
                                        <span class="seo-value">{{ $product->slug }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Variants Tab -->
            <div class="tab-pane fade" id="variants" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-code-branch me-2"></i>Variantes du produit
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        @if($product->variants->count() > 0)
                            <div class="variants-grid">
                                @foreach($product->variants as $variant)
                                <div class="variant-card">
                                    <div class="variant-card-header">
                                        @if($variant->image)
                                            <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->name }}" class="variant-image">
                                        @else
                                            <div class="variant-no-image">
                                                <i class="fas fa-cube"></i>
                                            </div>
                                        @endif
                                        <div class="variant-title">
                                            <h4>{{ $variant->name }}</h4>
                                            @if($variant->is_default)
                                                <span class="badge bg-primary">Par défaut</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="variant-card-body">
                                        <div class="variant-detail">
                                            <span>SKU</span>
                                            <span>{{ $variant->sku ?? 'N/A' }}</span>
                                        </div>
                                        <div class="variant-detail">
                                            <span>Prix</span>
                                            <span class="variant-price">{{ number_format($variant->final_price, 2) }} €</span>
                                        </div>
                                        @if($variant->price_adjustment != 0)
                                        <div class="variant-detail">
                                            <span>Ajustement</span>
                                            <span class="{{ $variant->price_adjustment > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $variant->price_adjustment > 0 ? '+' : '' }}{{ number_format($variant->price_adjustment, 2) }} €
                                            </span>
                                        </div>
                                        @endif
                                        <div class="variant-detail">
                                            <span>Stock</span>
                                            <span>
                                                @if($variant->stock > 0)
                                                    <span class="badge bg-success">{{ $variant->stock }} unités</span>
                                                @else
                                                    <span class="badge bg-danger">Rupture</span>
                                                @endif
                                            </span>
                                        </div>
                                        
                                        @if($variant->attributes && count($variant->attributes) > 0)
                                        <div class="variant-attributes">
                                            <strong>Attributs:</strong>
                                            <div class="attributes-list">
                                                @foreach($variant->attributes as $key => $value)
                                                    <span class="attribute-tag">{{ $key }}: {{ $value }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-code-branch fa-4x mb-3 text-muted"></i>
                                <h4>Aucune variante</h4>
                                <p>Ce produit n'a pas de variantes configurées.</p>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Ajouter des variantes
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Gallery Tab -->
            <div class="tab-pane fade" id="gallery" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-images me-2"></i>Galerie d'images
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        @if($product->gallery_images && count($gallery = json_decode($product->gallery_images)) > 0)
                            <div class="gallery-grid">
                                @foreach($gallery as $index => $image)
                                <div class="gallery-item">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Gallery image {{ $index + 1 }}" class="gallery-image">
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-images fa-4x mb-3 text-muted"></i>
                                <h4>Aucune image</h4>
                                <p>Ce produit n'a pas d'images dans sa galerie.</p>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Ajouter des images
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sales Tab -->
            <div class="tab-pane fade" id="sales" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-card-modern">
                            <div class="stats-header-modern">
                                <div>
                                    <div class="stats-value-modern">{{ $product->sales_count ?? 0 }}</div>
                                    <div class="stats-label-modern">Ventes totales</div>
                                </div>
                                <div class="stats-icon-modern" style="background: linear-gradient(135deg, #45b7d1, #3a9bb8);">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stats-card-modern">
                            <div class="stats-header-modern">
                                <div>
                                    <div class="stats-value-modern">{{ $product->views_count ?? 0 }}</div>
                                    <div class="stats-label-modern">Vues</div>
                                </div>
                                <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stats-card-modern">
                            <div class="stats-header-modern">
                                <div>
                                    <div class="stats-value-modern">
                                        {{ $product->sales_count > 0 ? number_format(($product->sales_count * $product->price_ttc), 2) : 0 }} €
                                    </div>
                                    <div class="stats-label-modern">Chiffre d'affaires</div>
                                </div>
                                <div class="stats-icon-modern" style="background: linear-gradient(135deg, #feca57, #ffb347);">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent invoices would go here -->
                <div class="main-card-modern mt-4">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-file-invoice me-2"></i>Dernières factures
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <div class="empty-state small">
                            <i class="fas fa-file-invoice fa-3x mb-2 text-muted"></i>
                            <p>Aucune facture associée à ce produit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <div class="fab-modern" id="helpFab" data-bs-toggle="tooltip" title="Options">
            <i class="fas fa-ellipsis-v"></i>
        </div>

        <!-- Quick Actions Menu -->
        <div class="quick-actions-menu" id="quickActionsMenu" style="display: none;">
            <a href="{{ route('products.edit', $product->id) }}" class="quick-action-item">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <button class="quick-action-item text-danger" id="quickDeleteBtn">
                <i class="fas fa-trash"></i>
                <span>Supprimer</span>
            </button>
            <a href="{{ route('products.duplicate', $product->id) }}" class="quick-action-item">
                <i class="fas fa-copy"></i>
                <span>Dupliquer</span>
            </a>
            <a href="#" class="quick-action-item" onclick="window.print()">
                <i class="fas fa-print"></i>
                <span>Imprimer</span>
            </a>
        </div>
    </main>

    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce produit/service ?</p>
                    <p class="fw-bold">{{ $product->name }}</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Cette action est irréversible et peut affecter les factures et devis associés.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- IMAGE VIEWER MODAL -->
    <div class="modal fade" id="imageViewerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" alt="Product image" id="viewerImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Floating action button menu
            const helpFab = document.getElementById('helpFab');
            const quickActionsMenu = document.getElementById('quickActionsMenu');

            if (helpFab && quickActionsMenu) {
                helpFab.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = quickActionsMenu.style.display === 'block';
                    quickActionsMenu.style.display = isVisible ? 'none' : 'block';
                });

                document.addEventListener('click', function() {
                    quickActionsMenu.style.display = 'none';
                });

                quickActionsMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Delete product
            const deleteProductBtn = document.getElementById('deleteProductBtn');
            const quickDeleteBtn = document.getElementById('quickDeleteBtn');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));

            if (deleteProductBtn) {
                deleteProductBtn.addEventListener('click', function() {
                    deleteModal.show();
                });
            }

            if (quickDeleteBtn) {
                quickDeleteBtn.addEventListener('click', function() {
                    deleteModal.show();
                });
            }

            // Image viewer
            const imageViewerModal = new bootstrap.Modal(document.getElementById('imageViewerModal'));
            const viewerImage = document.getElementById('viewerImage');

            document.querySelectorAll('.product-show-image img, .gallery-image').forEach(img => {
                img.addEventListener('click', function() {
                    viewerImage.src = this.src;
                    imageViewerModal.show();
                });
            });

            // Variant image viewer
            document.querySelectorAll('.variant-image').forEach(img => {
                img.addEventListener('click', function() {
                    viewerImage.src = this.src;
                    imageViewerModal.show();
                });
            });

            // Print functionality
            window.print = function() {
                window.print();
            };
        });
    </script>

    <style>
        /* Product Show Specific Styles */
        .product-show-header {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            align-items: start;
        }

        .product-show-image {
            width: 100%;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #eaeaea;
            cursor: pointer;
            transition: all 0.3s;
        }

        .product-show-image:hover {
            border-color: var(--primary-color);
            transform: scale(1.02);
        }

        .product-show-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image-placeholder {
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ccc;
        }

        .product-badges {
            display: flex;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 3px;
        }

        .info-value {
            font-weight: 600;
            color: #333;
        }

        /* Price Display */
        .price-display {
            text-align: center;
            margin-bottom: 20px;
        }

        .price-current {
            font-size: 2.5rem;
            font-weight: 700;
            color: #06b48a;
            line-height: 1.2;
        }

        .price-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }

        .price-detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eaeaea;
        }

        .price-detail-item:last-child {
            border-bottom: none;
        }

        .price-detail-item.highlight {
            font-weight: 600;
            color: #06b48a;
        }

        /* Info List */
        .info-list {
            margin-top: 15px;
        }

        .info-list-item {
            padding: 8px 0;
            border-bottom: 1px solid #eaeaea;
        }

        .info-list-item:last-child {
            border-bottom: none;
        }

        /* Stock Info */
        .stock-info {
            text-align: center;
        }

        .stock-details {
            text-align: left;
        }

        .stock-detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eaeaea;
        }

        .stock-detail-item:last-child {
            border-bottom: none;
        }

        .stock-value {
            font-weight: 600;
            color: #333;
        }

        /* Service Details */
        .service-detail-item,
        .subscription-detail-item {
            padding: 10px 0;
            border-bottom: 1px solid #eaeaea;
        }

        .service-detail-item:last-child,
        .subscription-detail-item:last-child {
            border-bottom: none;
        }

        .subscription-detail-item {
            display: flex;
            justify-content: space-between;
        }

        .subscription-value {
            font-weight: 600;
            color: #333;
        }

        /* SEO Details */
        .seo-detail-item {
            padding: 10px 0;
            border-bottom: 1px solid #eaeaea;
        }

        .seo-detail-item:last-child {
            border-bottom: none;
        }

        .seo-label {
            display: block;
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 3px;
        }

        .seo-value {
            font-weight: 500;
            color: #333;
        }

        /* Variants Grid */
        .variants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .variant-card {
            background: white;
            border: 2px solid #eaeaea;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .variant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }

        .variant-card-header {
            padding: 15px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 2px solid #eaeaea;
        }

        .variant-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            cursor: pointer;
        }

        .variant-no-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 1.5rem;
        }

        .variant-title h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0 0 5px 0;
        }

        .variant-card-body {
            padding: 15px;
        }

        .variant-detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eaeaea;
        }

        .variant-detail:last-child {
            border-bottom: none;
        }

        .variant-price {
            font-weight: 600;
            color: #06b48a;
        }

        .variant-attributes {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eaeaea;
        }

        .attributes-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 5px;
        }

        .attribute-tag {
            background: #e9ecef;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            color: #666;
        }

        /* Gallery Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid #eaeaea;
            transition: all 0.3s;
        }

        .gallery-item:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .empty-state.small {
            padding: 30px;
        }

        /* Product Tabs */
        .product-tabs-modern {
            margin-bottom: 20px;
        }

        .product-tabs-modern .nav-tabs {
            border: none;
            background: white;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .product-tabs-modern .nav-link {
            border: none;
            color: #666;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .product-tabs-modern .nav-link:hover {
            background: #f8f9fa;
            color: #333;
        }

        .product-tabs-modern .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            color: white;
        }

        .product-tabs-modern .nav-link .badge {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* Quick Actions Menu */
        .quick-actions-menu {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
            z-index: 999;
            min-width: 150px;
        }

        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }

        .quick-action-item:hover {
            background: #f8f9fa;
        }

        .quick-action-item.text-danger:hover {
            background: #fee;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .product-show-header {
                grid-template-columns: 1fr;
            }
            
            .product-show-image {
                height: 250px;
            }
            
            .info-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .variants-grid {
                grid-template-columns: 1fr;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .product-tabs-modern .nav-link {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
        }

        @media print {
            .page-actions,
            .fab-modern,
            .quick-actions-menu,
            .btn-close,
            .nav-tabs {
                display: none !important;
            }
            
            .product-show-header {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@php
// Helper functions for the view
if (!function_exists('getTypeBadge')) {
    function getTypeBadge($type) {
        $badges = [
            'produit_physique' => '<span class="badge bg-info"><i class="fas fa-cube me-1"></i>Produit physique</span>',
            'produit_numerique' => '<span class="badge bg-primary"><i class="fas fa-file-download me-1"></i>Produit numérique</span>',
            'service' => '<span class="badge bg-success"><i class="fas fa-cogs me-1"></i>Service</span>',
            'prestation' => '<span class="badge bg-warning"><i class="fas fa-briefcase me-1"></i>Prestation</span>',
            'forfait' => '<span class="badge bg-danger"><i class="fas fa-gift me-1"></i>Forfait</span>',
            'abonnement' => '<span class="badge bg-secondary"><i class="fas fa-sync-alt me-1"></i>Abonnement</span>',
            'licence' => '<span class="badge bg-dark"><i class="fas fa-certificate me-1"></i>Licence</span>',
            'hebergement' => '<span class="badge bg-purple"><i class="fas fa-server me-1"></i>Hébergement</span>',
            'maintenance' => '<span class="badge bg-orange"><i class="fas fa-wrench me-1"></i>Maintenance</span>',
            'formation' => '<span class="badge bg-teal"><i class="fas fa-chalkboard-teacher me-1"></i>Formation</span>'
        ];
        return $badges[$type] ?? '<span class="badge bg-secondary">Autre</span>';
    }
}

if (!function_exists('getBillingUnitText')) {
    function getBillingUnitText($unit) {
        $units = [
            'unite' => 'À l\'unité',
            'heure' => 'À l\'heure',
            'jour' => 'Par jour',
            'mois' => 'Par mois',
            'an' => 'Par an',
            'forfait' => 'Forfait',
            'projet' => 'Par projet'
        ];
        return $units[$unit] ?? $unit;
    }
}

if (!function_exists('getBillingPeriodText')) {
    function getBillingPeriodText($period) {
        $periods = [
            'mensuel' => 'Mensuel',
            'trimestriel' => 'Trimestriel',
            'semestriel' => 'Semestriel',
            'annuel' => 'Annuel'
        ];
        return $periods[$period] ?? $period;
    }
}
@endphp