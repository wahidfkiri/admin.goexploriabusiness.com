@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-puzzle-piece"></i></span>
                Modules & Extensions
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#installModuleModal">
                    <i class="fas fa-download me-2"></i>Installer un module
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
                    <label for="filterCategory" class="form-label-modern">Catégorie</label>
                    <select class="form-select-modern" id="filterCategory">
                        <option value="">Toutes les catégories</option>
                        <option value="1">Analytics</option>
                        <option value="2">Marketing</option>
                        <option value="3">Sécurité</option>
                        <option value="4">E-commerce</option>
                        <option value="5">Productivité</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous</option>
                        <option value="active">Actifs</option>
                        <option value="inactive">Inactifs</option>
                        <option value="pending">En attente</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterType" class="form-label-modern">Type</label>
                    <select class="form-select-modern" id="filterType">
                        <option value="">Tous</option>
                        <option value="core">Cœur</option>
                        <option value="official">Officiel</option>
                        <option value="third-party">Tiers</option>
                        <option value="custom">Personnalisé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterPrice" class="form-label-modern">Prix</label>
                    <select class="form-select-modern" id="filterPrice">
                        <option value="">Tous</option>
                        <option value="free">Gratuit</option>
                        <option value="paid">Payant</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="installed_at">Date d'installation</option>
                        <option value="popularity">Popularité</option>
                        <option value="rating">Note</option>
                        <option value="updated_at">Dernière mise à jour</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalModules">12</div>
                        <div class="stats-label-modern">Total Modules</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeModules">8</div>
                        <div class="stats-label-modern">Modules Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="inactiveModules">3</div>
                        <div class="stats-label-modern">Modules Inactifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-pause-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="updatesAvailable">2</div>
                        <div class="stats-label-modern">Mises à jour</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="freeModules">7</div>
                        <div class="stats-label-modern">Modules Gratuits</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="search-bar-modern">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input-modern" placeholder="Rechercher un module..." id="searchInput">
            </div>
            <div class="view-options">
                <button class="view-option active" id="gridViewBtn">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="view-option" id="listViewBtn">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Tous les modules</h3>
                <div class="modules-count">
                    <span id="visibleModulesCount">12</span> modules
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Loading Spinner -->
                <div class="spinner-container" id="loadingSpinner" style="display: none;">
                    <div class="spinner-border text-primary spinner" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                
                <!-- Modules Grid (Cards View) -->
                <div class="modules-grid" id="modulesGridView">
                    <!-- E-commerce Produits & Services Module -->
<div class="module-card" data-module-id="9" data-category="4" data-status="inactive" data-type="official" data-price="paid">
    <div class="module-card-header">
        <div class="module-icon" style="background: linear-gradient(135deg, #f97316, #ea580c);">
            <i class="fas fa-store"></i>
        </div>
        <div class="module-badges">
            <span class="badge-official">Officiel</span>
            <span class="badge-paid">Payant</span>
        </div>
    </div>
    
    <div class="module-card-body">
        <h4 class="module-name">CommerceSuite Pro</h4>
        <p class="module-description">Gestion complète de produits et services : catalogue avancé, variations, stock multi-entrepôts, avis clients et recommandations personnalisées.</p>
        
        <div class="module-meta">
            <div class="meta-item">
                <i class="fas fa-code-branch"></i>
                <span>v3.2.1</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-user"></i>
                <span>Commerce Labs</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>10/04/2024</span>
            </div>
        </div>
        
        <div class="module-rating">
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <span class="rating-count">(312)</span>
        </div>
    </div>
    
    <div class="module-card-footer">
        <div class="module-status">
            <span class="status-badge status-inactive">
                <i class="fas fa-circle"></i> Inactif
            </span>
        </div>
        
        <div class="module-actions">
            <button class="action-btn activate-btn" onclick="activateModule(9)" title="Activer">
                <i class="fas fa-play"></i>
            </button>
            
            <a href="#" class="action-btn view-btn" title="Voir détails">
                <i class="fas fa-eye"></i>
            </a>
            
            <button class="action-btn settings-btn" onclick="openModuleSettings(9)" title="Paramètres">
                <i class="fas fa-cog"></i>
            </button>
            
            <button class="action-btn delete-btn" onclick="showDeleteConfirmation(9)" title="Désinstaller">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>
<!-- Facturation Module -->
<div class="module-card" data-module-id="10" data-category="5" data-status="inactive" data-type="official" data-price="paid">
    <div class="module-card-header">
        <div class="module-icon" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
            <i class="fas fa-file-invoice"></i>
        </div>
        <div class="module-badges">
            <span class="badge-official">Officiel</span>
            <span class="badge-paid">Payant</span>
        </div>
    </div>
    
    <div class="module-card-body">
        <h4 class="module-name">InvoiceFlow</h4>
        <p class="module-description">Gestion complète de facturation : devis, factures, avoirs, relances automatiques, paiements en ligne, TVA multi-taux et exports comptables.</p>
        
        <div class="module-meta">
            <div class="meta-item">
                <i class="fas fa-code-branch"></i>
                <span>v2.5.0</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-user"></i>
                <span>FinTech Solutions</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>05/04/2024</span>
            </div>
        </div>
        
        <div class="module-rating">
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <span class="rating-count">(245)</span>
        </div>
    </div>
    
    <div class="module-card-footer">
        <div class="module-status">
            <span class="status-badge status-inactive">
                <i class="fas fa-circle"></i> Inactif
            </span>
        </div>
        
        <div class="module-actions">
            <button class="action-btn activate-btn" onclick="activateModule(10)" title="Activer">
                <i class="fas fa-play"></i>
            </button>
            
            <a href="#" class="action-btn view-btn" title="Voir détails">
                <i class="fas fa-eye"></i>
            </a>
            
            <button class="action-btn settings-btn" onclick="openModuleSettings(10)" title="Paramètres">
                <i class="fas fa-cog"></i>
            </button>
            
            <button class="action-btn delete-btn" onclick="showDeleteConfirmation(10)" title="Désinstaller">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>
<!-- GeoVideoMarker Module -->
<div class="module-card" data-module-id="11" data-category="5" data-status="inactive" data-type="official" data-price="paid">
    <div class="module-card-header">
        <div class="module-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
            <i class="fas fa-map-marked-alt"></i>
        </div>
        <div class="module-badges">
            <span class="badge-official">Officiel</span>
            <span class="badge-paid">Payant</span>
        </div>
    </div>
    
    <div class="module-card-body">
        <h4 class="module-name">GeoVideoMarker</h4>
        <p class="module-description">Cartographie interactive avec marqueurs vidéo. Placez des vidéos sur des points géolocalisés, itinéraires multimédia et storytelling géographique immersif.</p>
        
        <div class="module-meta">
            <div class="meta-item">
                <i class="fas fa-code-branch"></i>
                <span>v1.8.3</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-user"></i>
                <span>GeoMedia Labs</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>12/04/2024</span>
            </div>
        </div>
        
        <div class="module-rating">
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-filled"></i>
            <i class="fas fa-star star-half-alt"></i>
            <span class="rating-count">(89)</span>
        </div>
    </div>
    
    <div class="module-card-footer">
        <div class="module-status">
            <span class="status-badge status-inactive">
                <i class="fas fa-circle"></i> Inactif
            </span>
        </div>
        
        <div class="module-actions">
            <button class="action-btn activate-btn" onclick="activateModule(11)" title="Activer">
                <i class="fas fa-play"></i>
            </button>
            
            <a href="#" class="action-btn view-btn" title="Voir détails">
                <i class="fas fa-eye"></i>
            </a>
            
            <button class="action-btn settings-btn" onclick="openModuleSettings(11)" title="Paramètres">
                <i class="fas fa-cog"></i>
            </button>
            
            <button class="action-btn delete-btn" onclick="showDeleteConfirmation(11)" title="Désinstaller">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>
                    <!-- Analytics Pro Module -->
                    <div class="module-card" data-module-id="1" data-category="1" data-status="active" data-type="official" data-price="paid">
                        <div class="module-card-header">
                            <div class="module-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="module-badges">
                                <span class="badge-official">Officiel</span>
                                <span class="badge-paid">Payant</span>
                            </div>
                        </div>
                        
                        <div class="module-card-body">
                            <h4 class="module-name">Analytics Pro</h4>
                            <p class="module-description">Statistiques avancées, rapports personnalisés et tableaux de bord interactifs pour analyser vos données en temps réel.</p>
                            
                            <div class="module-meta">
                                <div class="meta-item">
                                    <i class="fas fa-code-branch"></i>
                                    <span>v2.1.0</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>Acme Corp</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>15/03/2024</span>
                                </div>
                            </div>
                            
                            <div class="module-rating">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <span class="rating-count">(128)</span>
                            </div>
                            
                            <div class="update-available">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Mise à jour v2.2.0 disponible</span>
                            </div>
                        </div>
                        
                        <div class="module-card-footer">
                            <div class="module-status">
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle"></i> Actif
                                </span>
                            </div>
                            
                            <div class="module-actions">
                                <button class="action-btn deactivate-btn" onclick="deactivateModule(1)" title="Désactiver">
                                    <i class="fas fa-pause"></i>
                                </button>
                                
                                <a href="#" class="action-btn view-btn" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn settings-btn" onclick="openModuleSettings(1)" title="Paramètres">
                                    <i class="fas fa-cog"></i>
                                </button>
                                
                                <button class="action-btn delete-btn" onclick="showDeleteConfirmation(1)" title="Désinstaller">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email Marketing Suite Module -->
                    <div class="module-card" data-module-id="2" data-category="2" data-status="active" data-type="official" data-price="free">
                        <div class="module-card-header">
                            <div class="module-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="module-badges">
                                <span class="badge-official">Officiel</span>
                            </div>
                        </div>
                        
                        <div class="module-card-body">
                            <h4 class="module-name">Email Marketing Suite</h4>
                            <p class="module-description">Créez et envoyez des campagnes email professionnelles, gérez vos listes de contacts et suivez vos taux d'ouverture.</p>
                            
                            <div class="module-meta">
                                <div class="meta-item">
                                    <i class="fas fa-code-branch"></i>
                                    <span>v1.5.2</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>Marketing Team</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>02/02/2024</span>
                                </div>
                            </div>
                            
                            <div class="module-rating">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-empty"></i>
                                <span class="rating-count">(89)</span>
                            </div>
                        </div>
                        
                        <div class="module-card-footer">
                            <div class="module-status">
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle"></i> Actif
                                </span>
                            </div>
                            
                            <div class="module-actions">
                                <button class="action-btn deactivate-btn" onclick="deactivateModule(2)" title="Désactiver">
                                    <i class="fas fa-pause"></i>
                                </button>
                                
                                <a href="#" class="action-btn view-btn" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn settings-btn" onclick="openModuleSettings(2)" title="Paramètres">
                                    <i class="fas fa-cog"></i>
                                </button>
                                
                                <button class="action-btn delete-btn" onclick="showDeleteConfirmation(2)" title="Désinstaller">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Plus Module -->
                    <div class="module-card" data-module-id="3" data-category="3" data-status="inactive" data-type="third-party" data-price="paid">
                        <div class="module-card-header">
                            <div class="module-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="module-badges">
                                <span class="badge-paid">Payant</span>
                            </div>
                        </div>
                        
                        <div class="module-card-body">
                            <h4 class="module-name">Security Plus</h4>
                            <p class="module-description">Protection avancée contre les intrusions, pare-feu applicatif et authentification à deux facteurs.</p>
                            
                            <div class="module-meta">
                                <div class="meta-item">
                                    <i class="fas fa-code-branch"></i>
                                    <span>v3.0.1</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>SecureSoft</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>10/01/2024</span>
                                </div>
                            </div>
                            
                            <div class="module-rating">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <span class="rating-count">(256)</span>
                            </div>
                        </div>
                        
                        <div class="module-card-footer">
                            <div class="module-status">
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-circle"></i> Inactif
                                </span>
                            </div>
                            
                            <div class="module-actions">
                                <button class="action-btn activate-btn" onclick="activateModule(3)" title="Activer">
                                    <i class="fas fa-play"></i>
                                </button>
                                
                                <a href="#" class="action-btn view-btn" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn settings-btn" onclick="openModuleSettings(3)" title="Paramètres">
                                    <i class="fas fa-cog"></i>
                                </button>
                                
                                <button class="action-btn delete-btn" onclick="showDeleteConfirmation(3)" title="Désinstaller">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- E-commerce Essentials Module -->
                    <div class="module-card" data-module-id="4" data-category="4" data-status="active" data-type="official" data-price="free">
                        <div class="module-card-header">
                            <div class="module-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="module-badges">
                                <span class="badge-official">Officiel</span>
                            </div>
                        </div>
                        
                        <div class="module-card-body">
                            <h4 class="module-name">E-commerce Essentials</h4>
                            <p class="module-description">Fonctionnalités de base pour votre boutique en ligne : panier, paiement, gestion des stocks et commandes.</p>
                            
                            <div class="module-meta">
                                <div class="meta-item">
                                    <i class="fas fa-code-branch"></i>
                                    <span>v1.2.3</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>E-commerce Team</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>20/03/2024</span>
                                </div>
                            </div>
                            
                            <div class="module-rating">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-empty"></i>
                                <span class="rating-count">(67)</span>
                            </div>
                        </div>
                        
                        <div class="module-card-footer">
                            <div class="module-status">
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle"></i> Actif
                                </span>
                            </div>
                            
                            <div class="module-actions">
                                <button class="action-btn deactivate-btn" onclick="deactivateModule(4)" title="Désactiver">
                                    <i class="fas fa-pause"></i>
                                </button>
                                
                                <a href="#" class="action-btn view-btn" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn settings-btn" onclick="openModuleSettings(4)" title="Paramètres">
                                    <i class="fas fa-cog"></i>
                                </button>
                                
                                <button class="action-btn delete-btn" onclick="showDeleteConfirmation(4)" title="Désinstaller">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Core System Module -->
                    <div class="module-card" data-module-id="5" data-category="5" data-status="active" data-type="core" data-price="free">
                        <div class="module-card-header">
                            <div class="module-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="module-badges">
                                <span class="badge-core">Cœur</span>
                            </div>
                        </div>
                        
                        <div class="module-card-body">
                            <h4 class="module-name">Core System</h4>
                            <p class="module-description">Module système essentiel pour le fonctionnement de base de l'application. Ne peut pas être désactivé.</p>
                            
                            <div class="module-meta">
                                <div class="meta-item">
                                    <i class="fas fa-code-branch"></i>
                                    <span>v4.0.2</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>System Team</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>01/01/2024</span>
                                </div>
                            </div>
                            
                            <div class="update-available">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Mise à jour v4.1.0 disponible</span>
                            </div>
                        </div>
                        
                        <div class="module-card-footer">
                            <div class="module-status">
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle"></i> Actif
                                </span>
                            </div>
                            
                            <div class="module-actions">
                                <button class="action-btn deactivate-btn" disabled style="opacity: 0.5; cursor: not-allowed;" title="Module système">
                                    <i class="fas fa-pause"></i>
                                </button>
                                
                                <a href="#" class="action-btn view-btn" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn settings-btn" onclick="openModuleSettings(5)" title="Paramètres">
                                    <i class="fas fa-cog"></i>
                                </button>
                                
                                <button class="action-btn delete-btn" disabled style="opacity: 0.5; cursor: not-allowed;" title="Module système">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Optimizer Module -->
                    <div class="module-card" data-module-id="6" data-category="5" data-status="pending" data-type="third-party" data-price="paid">
                        <div class="module-card-header">
                            <div class="module-icon" style="background: linear-gradient(135deg, #ec4899, #db2777);">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="module-badges">
                                <span class="badge-paid">Payant</span>
                            </div>
                        </div>
                        
                        <div class="module-card-body">
                            <h4 class="module-name">SEO Optimizer</h4>
                            <p class="module-description">Optimisez votre référencement naturel avec des outils d'analyse de mots-clés et de suggestions de contenu.</p>
                            
                            <div class="module-meta">
                                <div class="meta-item">
                                    <i class="fas fa-code-branch"></i>
                                    <span>v2.0.0</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>SEO Masters</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>05/03/2024</span>
                                </div>
                            </div>
                            
                            <div class="module-rating">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <span class="rating-count">(42)</span>
                            </div>
                        </div>
                        
                        <div class="module-card-footer">
                            <div class="module-status">
                                <span class="status-badge status-pending">
                                    <i class="fas fa-circle"></i> En attente
                                </span>
                            </div>
                            
                            <div class="module-actions">
                                <button class="action-btn activate-btn" onclick="activateModule(6)" title="Activer">
                                    <i class="fas fa-play"></i>
                                </button>
                                
                                <a href="#" class="action-btn view-btn" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn settings-btn" onclick="openModuleSettings(6)" title="Paramètres">
                                    <i class="fas fa-cog"></i>
                                </button>
                                
                                <button class="action-btn delete-btn" onclick="showDeleteConfirmation(6)" title="Désinstaller">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media Integration Module -->
                    <div class="module-card" data-module-id="7" data-category="2" data-status="inactive" data-type="third-party" data-price="free">
                        <div class="module-card-header">
                            <div class="module-icon" style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
                                <i class="fas fa-share-alt"></i>
                            </div>
                            <div class="module-badges">
                                <!-- No badges -->
                            </div>
                        </div>
                        
                        <div class="module-card-body">
                            <h4 class="module-name">Social Media Integration</h4>
                            <p class="module-description">Partagez automatiquement vos contenus sur les réseaux sociaux et affichez vos flux sociaux.</p>
                            
                            <div class="module-meta">
                                <div class="meta-item">
                                    <i class="fas fa-code-branch"></i>
                                    <span>v1.1.5</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>SocialTech</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>12/02/2024</span>
                                </div>
                            </div>
                            
                            <div class="module-rating">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-empty"></i>
                                <i class="fas fa-star star-empty"></i>
                                <span class="rating-count">(23)</span>
                            </div>
                        </div>
                        
                        <div class="module-card-footer">
                            <div class="module-status">
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-circle"></i> Inactif
                                </span>
                            </div>
                            
                            <div class="module-actions">
                                <button class="action-btn activate-btn" onclick="activateModule(7)" title="Activer">
                                    <i class="fas fa-play"></i>
                                </button>
                                
                                <a href="#" class="action-btn view-btn" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn settings-btn" onclick="openModuleSettings(7)" title="Paramètres">
                                    <i class="fas fa-cog"></i>
                                </button>
                                
                                <button class="action-btn delete-btn" onclick="showDeleteConfirmation(7)" title="Désinstaller">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Backup Manager Module -->
                    <div class="module-card" data-module-id="8" data-category="3" data-status="active" data-type="custom" data-price="free">
                        <div class="module-card-header">
                            <div class="module-icon" style="background: linear-gradient(135deg, #f43f5e, #e11d48);">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="module-badges">
                                <span class="badge-core">Personnalisé</span>
                            </div>
                        </div>
                        
                        <div class="module-card-body">
                            <h4 class="module-name">Backup Manager</h4>
                            <p class="module-description">Gérez vos sauvegardes automatiques, planifiez des backups et restaurez vos données en un clic.</p>
                            
                            <div class="module-meta">
                                <div class="meta-item">
                                    <i class="fas fa-code-branch"></i>
                                    <span>v2.3.1</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>IT Team</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>18/03/2024</span>
                                </div>
                            </div>
                            
                            <div class="module-rating">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-empty"></i>
                                <span class="rating-count">(34)</span>
                            </div>
                        </div>
                        
                        <div class="module-card-footer">
                            <div class="module-status">
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle"></i> Actif
                                </span>
                            </div>
                            
                            <div class="module-actions">
                                <button class="action-btn deactivate-btn" onclick="deactivateModule(8)" title="Désactiver">
                                    <i class="fas fa-pause"></i>
                                </button>
                                
                                <a href="#" class="action-btn view-btn" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn settings-btn" onclick="openModuleSettings(8)" title="Paramètres">
                                    <i class="fas fa-cog"></i>
                                </button>
                                
                                <button class="action-btn delete-btn" onclick="showDeleteConfirmation(8)" title="Désinstaller">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modules List (List View) -->
                <div class="modules-list" id="modulesListView" style="display: none;">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Version</th>
                                <th>Auteur</th>
                                <th>Installé le</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-module-id="1">
                                <td>
                                    <div class="list-module-info">
                                        <div class="list-module-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <div class="list-module-details">
                                            <div class="list-module-name">
                                                Analytics Pro
                                                <span class="badge-official-sm">Officiel</span>
                                            </div>
                                            <div class="list-module-description">Statistiques avancées et rapports personnalisés...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="version-info">
                                        <span class="current-version">v2.1.0</span>
                                        <span class="update-indicator" title="Mise à jour disponible">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>Acme Corp</td>
                                <td>15/03/2024</td>
                                <td>
                                    <span class="status-badge status-active">
                                        <i class="fas fa-circle"></i> Actif
                                    </span>
                                </td>
                                <td>
                                    <div class="list-actions">
                                        <button class="list-action-btn deactivate-btn" onclick="deactivateModule(1)" title="Désactiver">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                        
                                        <a href="#" class="list-action-btn view-btn" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <button class="list-action-btn settings-btn" onclick="openModuleSettings(1)" title="Paramètres">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        
                                        <button class="list-action-btn delete-btn" onclick="showDeleteConfirmation(1)" title="Désinstaller">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr data-module-id="2">
                                <td>
                                    <div class="list-module-info">
                                        <div class="list-module-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="list-module-details">
                                            <div class="list-module-name">
                                                Email Marketing Suite
                                                <span class="badge-official-sm">Officiel</span>
                                            </div>
                                            <div class="list-module-description">Campagnes email et newsletters automatisées...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>v1.5.2</td>
                                <td>Marketing Team</td>
                                <td>02/02/2024</td>
                                <td>
                                    <span class="status-badge status-active">
                                        <i class="fas fa-circle"></i> Actif
                                    </span>
                                </td>
                                <td>
                                    <div class="list-actions">
                                        <button class="list-action-btn deactivate-btn" onclick="deactivateModule(2)" title="Désactiver">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                        
                                        <a href="#" class="list-action-btn view-btn" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <button class="list-action-btn settings-btn" onclick="openModuleSettings(2)" title="Paramètres">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        
                                        <button class="list-action-btn delete-btn" onclick="showDeleteConfirmation(2)" title="Désinstaller">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr data-module-id="3">
                                <td>
                                    <div class="list-module-info">
                                        <div class="list-module-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div class="list-module-details">
                                            <div class="list-module-name">Security Plus</div>
                                            <div class="list-module-description">Sécurité renforcée et pare-feu...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>v3.0.1</td>
                                <td>SecureSoft</td>
                                <td>10/01/2024</td>
                                <td>
                                    <span class="status-badge status-inactive">
                                        <i class="fas fa-circle"></i> Inactif
                                    </span>
                                </td>
                                <td>
                                    <div class="list-actions">
                                        <button class="list-action-btn activate-btn" onclick="activateModule(3)" title="Activer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        
                                        <a href="#" class="list-action-btn view-btn" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <button class="list-action-btn settings-btn" onclick="openModuleSettings(3)" title="Paramètres">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        
                                        <button class="list-action-btn delete-btn" onclick="showDeleteConfirmation(3)" title="Désinstaller">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr data-module-id="4">
                                <td>
                                    <div class="list-module-info">
                                        <div class="list-module-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div class="list-module-details">
                                            <div class="list-module-name">
                                                E-commerce Essentials
                                                <span class="badge-official-sm">Officiel</span>
                                            </div>
                                            <div class="list-module-description">Fonctionnalités de base pour votre boutique en ligne...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>v1.2.3</td>
                                <td>E-commerce Team</td>
                                <td>20/03/2024</td>
                                <td>
                                    <span class="status-badge status-active">
                                        <i class="fas fa-circle"></i> Actif
                                    </span>
                                </td>
                                <td>
                                    <div class="list-actions">
                                        <button class="list-action-btn deactivate-btn" onclick="deactivateModule(4)" title="Désactiver">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                        
                                        <a href="#" class="list-action-btn view-btn" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <button class="list-action-btn settings-btn" onclick="openModuleSettings(4)" title="Paramètres">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        
                                        <button class="list-action-btn delete-btn" onclick="showDeleteConfirmation(4)" title="Désinstaller">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr data-module-id="5">
                                <td>
                                    <div class="list-module-info">
                                        <div class="list-module-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                        <div class="list-module-details">
                                            <div class="list-module-name">
                                                Core System
                                                <span class="badge-core-sm">Cœur</span>
                                            </div>
                                            <div class="list-module-description">Module système essentiel...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="version-info">
                                        <span class="current-version">v4.0.2</span>
                                        <span class="update-indicator" title="Mise à jour disponible">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>System Team</td>
                                <td>01/01/2024</td>
                                <td>
                                    <span class="status-badge status-active">
                                        <i class="fas fa-circle"></i> Actif
                                    </span>
                                </td>
                                <td>
                                    <div class="list-actions">
                                        <button class="list-action-btn deactivate-btn" disabled style="opacity: 0.5; cursor: not-allowed;" title="Module système">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                        
                                        <a href="#" class="list-action-btn view-btn" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <button class="list-action-btn settings-btn" onclick="openModuleSettings(5)" title="Paramètres">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        
                                        <button class="list-action-btn delete-btn" disabled style="opacity: 0.5; cursor: not-allowed;" title="Module système">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr data-module-id="6">
                                <td>
                                    <div class="list-module-info">
                                        <div class="list-module-icon" style="background: linear-gradient(135deg, #ec4899, #db2777);">
                                            <i class="fas fa-search"></i>
                                        </div>
                                        <div class="list-module-details">
                                            <div class="list-module-name">SEO Optimizer</div>
                                            <div class="list-module-description">Optimisez votre référencement naturel...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>v2.0.0</td>
                                <td>SEO Masters</td>
                                <td>05/03/2024</td>
                                <td>
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-circle"></i> En attente
                                    </span>
                                </td>
                                <td>
                                    <div class="list-actions">
                                        <button class="list-action-btn activate-btn" onclick="activateModule(6)" title="Activer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        
                                        <a href="#" class="list-action-btn view-btn" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <button class="list-action-btn settings-btn" onclick="openModuleSettings(6)" title="Paramètres">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        
                                        <button class="list-action-btn delete-btn" onclick="showDeleteConfirmation(6)" title="Désinstaller">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr data-module-id="7">
                                <td>
                                    <div class="list-module-info">
                                        <div class="list-module-icon" style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
                                            <i class="fas fa-share-alt"></i>
                                        </div>
                                        <div class="list-module-details">
                                            <div class="list-module-name">Social Media Integration</div>
                                            <div class="list-module-description">Partagez automatiquement vos contenus...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>v1.1.5</td>
                                <td>SocialTech</td>
                                <td>12/02/2024</td>
                                <td>
                                    <span class="status-badge status-inactive">
                                        <i class="fas fa-circle"></i> Inactif
                                    </span>
                                </td>
                                <td>
                                    <div class="list-actions">
                                        <button class="list-action-btn activate-btn" onclick="activateModule(7)" title="Activer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        
                                        <a href="#" class="list-action-btn view-btn" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <button class="list-action-btn settings-btn" onclick="openModuleSettings(7)" title="Paramètres">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        
                                        <button class="list-action-btn delete-btn" onclick="showDeleteConfirmation(7)" title="Désinstaller">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr data-module-id="8">
                                <td>
                                    <div class="list-module-info">
                                        <div class="list-module-icon" style="background: linear-gradient(135deg, #f43f5e, #e11d48);">
                                            <i class="fas fa-database"></i>
                                        </div>
                                        <div class="list-module-details">
                                            <div class="list-module-name">
                                                Backup Manager
                                                <span class="badge-core-sm">Personnalisé</span>
                                            </div>
                                            <div class="list-module-description">Gérez vos sauvegardes automatiques...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>v2.3.1</td>
                                <td>IT Team</td>
                                <td>18/03/2024</td>
                                <td>
                                    <span class="status-badge status-active">
                                        <i class="fas fa-circle"></i> Actif
                                    </span>
                                </td>
                                <td>
                                    <div class="list-actions">
                                        <button class="list-action-btn deactivate-btn" onclick="deactivateModule(8)" title="Désactiver">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                        
                                        <a href="#" class="list-action-btn view-btn" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <button class="list-action-btn settings-btn" onclick="openModuleSettings(8)" title="Paramètres">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        
                                        <button class="list-action-btn delete-btn" onclick="showDeleteConfirmation(8)" title="Désinstaller">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container-modern" id="paginationContainer">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                        </li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Category Navigation -->
        <div class="categories-nav">
            <button class="category-filter active" data-category="all">
                <i class="fas fa-th-large me-2"></i>Tous
            </button>
            <button class="category-filter" data-category="1">
                <i class="fas fa-chart-bar me-2"></i>Analytics
            </button>
            <button class="category-filter" data-category="2">
                <i class="fas fa-bullhorn me-2"></i>Marketing
            </button>
            <button class="category-filter" data-category="3">
                <i class="fas fa-shield-alt me-2"></i>Sécurité
            </button>
            <button class="category-filter" data-category="4">
                <i class="fas fa-shopping-cart me-2"></i>E-commerce
            </button>
            <button class="category-filter" data-category="5">
                <i class="fas fa-cog me-2"></i>Productivité
            </button>
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#installModuleModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- INSTALL MODULE MODAL -->
    <div class="modal fade" id="installModuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modern-modal">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-download me-2" style="color: var(--primary-color);"></i>
                        Installer un module
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Upload Section -->
                    <div class="upload-section">
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <h4>Glissez-déposez votre module</h4>
                            <p>ou</p>
                            <button class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-folder-open me-2"></i>Parcourir
                            </button>
                            <input type="file" id="fileInput" accept=".zip" style="display: none;">
                            <p class="upload-hint">Format accepté : ZIP (max 50 Mo)</p>
                        </div>
                    </div>
                    
                    <!-- OR Separator -->
                    <div class="separator">
                        <span>OU</span>
                    </div>
                    
                    <!-- Marketplace Section -->
                    <div class="marketplace-section">
                        <h4 class="marketplace-title">
                            <i class="fas fa-store me-2"></i>
                            Explorer le marketplace
                        </h4>
                        
                        <div class="marketplace-grid">
                            <div class="marketplace-card">
                                <div class="marketplace-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="marketplace-info">
                                    <h5>Analytics Pro</h5>
                                    <p>Statistiques avancées et rapports personnalisés</p>
                                    <div class="marketplace-meta">
                                        <span class="price">€49.99</span>
                                        <span class="rating">
                                            <i class="fas fa-star"></i>
                                            4.8
                                        </span>
                                    </div>
                                </div>
                                <button class="btn-install">Installer</button>
                            </div>
                            
                            <div class="marketplace-card">
                                <div class="marketplace-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="marketplace-info">
                                    <h5>Email Marketing Suite</h5>
                                    <p>Campagnes email et newsletters automatisées</p>
                                    <div class="marketplace-meta">
                                        <span class="price price-free">Gratuit</span>
                                        <span class="rating">
                                            <i class="fas fa-star"></i>
                                            4.5
                                        </span>
                                    </div>
                                </div>
                                <button class="btn-install">Installer</button>
                            </div>
                            
                            <div class="marketplace-card">
                                <div class="marketplace-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="marketplace-info">
                                    <h5>Security Plus</h5>
                                    <p>Sécurité renforcée et pare-feu</p>
                                    <div class="marketplace-meta">
                                        <span class="price">€29.99</span>
                                        <span class="rating">
                                            <i class="fas fa-star"></i>
                                            4.9
                                        </span>
                                    </div>
                                </div>
                                <button class="btn-install">Installer</button>
                            </div>
                            
                            <div class="marketplace-card">
                                <div class="marketplace-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="marketplace-info">
                                    <h5>E-commerce Essentials</h5>
                                    <p>Fonctionnalités de base pour boutique en ligne</p>
                                    <div class="marketplace-meta">
                                        <span class="price price-free">Gratuit</span>
                                        <span class="rating">
                                            <i class="fas fa-star"></i>
                                            4.6
                                        </span>
                                    </div>
                                </div>
                                <button class="btn-install">Installer</button>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="#" class="view-more-link">
                                Voir tous les modules <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade delete-confirm-modal" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 class="delete-title">Confirmer la désinstallation</h4>
                    <p class="delete-message">Êtes-vous sûr de vouloir désinstaller ce module ? Toutes les données associées seront supprimées.</p>
                    
                    <div class="module-to-delete" id="moduleToDeleteInfo">
                        <!-- Module info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Désinstaller
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- UPDATE MODAL -->
    <div class="modal fade" id="updateModuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sync-alt me-2" style="color: var(--primary-color);"></i>
                        Mise à jour disponible
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="updateModalContent">
                    <!-- Update content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Plus tard</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdateBtn">
                        <i class="fas fa-download me-2"></i>Mettre à jour
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Configuration
        let currentView = 'grid'; // 'grid' or 'list'
        let currentModuleToDelete = null;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            setupFileUpload();
        });
        
        // Setup event listeners
        function setupEventListeners() {
            // View toggle
            document.getElementById('gridViewBtn').addEventListener('click', () => switchView('grid'));
            document.getElementById('listViewBtn').addEventListener('click', () => switchView('list'));
            
            // Search
            document.getElementById('searchInput').addEventListener('input', debounce(handleSearch, 300));
            
            // Category filters
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.addEventListener('click', () => filterByCategory(btn.dataset.category));
            });
            
            // Filter buttons
            document.getElementById('applyFiltersBtn').addEventListener('click', applyFilters);
            document.getElementById('clearFiltersBtn').addEventListener('click', clearFilters);
            
            // Toggle filter section
            document.getElementById('toggleFilterBtn').addEventListener('click', toggleFilterSection);
            
            // Delete confirmation
            document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);
            
            // File upload
            document.getElementById('fileInput').addEventListener('change', handleFileSelect);
        }
        
        // Switch between grid and list view
        function switchView(view) {
            currentView = view;
            
            const gridView = document.getElementById('modulesGridView');
            const listView = document.getElementById('modulesListView');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');
            
            if (view === 'grid') {
                gridView.style.display = 'grid';
                listView.style.display = 'none';
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
            } else {
                gridView.style.display = 'none';
                listView.style.display = 'block';
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
            }
        }
        
        // Handle search with debounce
        function handleSearch(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            // Grid view search
            document.querySelectorAll('.module-card').forEach(card => {
                const moduleName = card.querySelector('.module-name').textContent.toLowerCase();
                const moduleDesc = card.querySelector('.module-description').textContent.toLowerCase();
                
                if (moduleName.includes(searchTerm) || moduleDesc.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // List view search
            document.querySelectorAll('#modulesListView tbody tr').forEach(row => {
                const moduleName = row.querySelector('.list-module-name').textContent.toLowerCase();
                const moduleDesc = row.querySelector('.list-module-description').textContent.toLowerCase();
                
                if (moduleName.includes(searchTerm) || moduleDesc.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            updateVisibleCount();
        }
        
        // Filter by category
        function filterByCategory(categoryId) {
            // Update active state
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.category === categoryId);
            });
            
            // Filter cards
            document.querySelectorAll('.module-card').forEach(card => {
                if (categoryId === 'all' || card.dataset.category === categoryId) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Filter list rows
            document.querySelectorAll('#modulesListView tbody tr').forEach(row => {
                if (categoryId === 'all' || row.dataset.category === categoryId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            updateVisibleCount();
        }
        
        // Apply all filters
        function applyFilters() {
            const category = document.getElementById('filterCategory').value;
            const status = document.getElementById('filterStatus').value;
            const type = document.getElementById('filterType').value;
            const price = document.getElementById('filterPrice').value;
            
            // Filter grid view
            document.querySelectorAll('.module-card').forEach(card => {
                let show = true;
                
                if (category && card.dataset.category !== category) show = false;
                if (status && card.dataset.status !== status) show = false;
                if (type && card.dataset.type !== type) show = false;
                if (price && card.dataset.price !== price) show = false;
                
                card.style.display = show ? 'block' : 'none';
            });
            
            // Filter list view
            document.querySelectorAll('#modulesListView tbody tr').forEach(row => {
                let show = true;
                
                if (category && row.dataset.category !== category) show = false;
                if (status && row.dataset.status !== status) show = false;
                if (type && row.dataset.type !== type) show = false;
                if (price && row.dataset.price !== price) show = false;
                
                row.style.display = show ? '' : 'none';
            });
            
            updateVisibleCount();
        }
        
        // Clear all filters
        function clearFilters() {
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterPrice').value = '';
            document.getElementById('filterSortBy').value = 'name';
            
            // Show all cards
            document.querySelectorAll('.module-card').forEach(card => {
                card.style.display = 'block';
            });
            
            // Show all list rows
            document.querySelectorAll('#modulesListView tbody tr').forEach(row => {
                row.style.display = '';
            });
            
            // Reset category filters
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.category === 'all');
            });
            
            updateVisibleCount();
        }
        
        // Update visible modules count
        function updateVisibleCount() {
            let visibleCount;
            
            if (currentView === 'grid') {
                visibleCount = document.querySelectorAll('.module-card[style="display: block"]').length;
            } else {
                visibleCount = document.querySelectorAll('#modulesListView tbody tr:not([style="display: none"])').length;
            }
            
            document.getElementById('visibleModulesCount').textContent = visibleCount || 0;
        }
        
        // Toggle filter section
        function toggleFilterSection() {
            const filterSection = document.getElementById('filterSection');
            const btn = document.getElementById('toggleFilterBtn');
            
            if (filterSection.style.display === 'none') {
                filterSection.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-times me-2"></i>Masquer les filtres';
            } else {
                filterSection.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-sliders-h me-2"></i>Filtres';
            }
        }
        
        // Debounce utility
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        // Setup file upload drag & drop
        function setupFileUpload() {
            const uploadArea = document.getElementById('uploadArea');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });
            
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });
            
            uploadArea.addEventListener('drop', handleDrop, false);
        }
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight() {
            document.getElementById('uploadArea').classList.add('highlight');
        }
        
        function unhighlight() {
            document.getElementById('uploadArea').classList.remove('highlight');
        }
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }
        
        function handleFileSelect(e) {
            const files = e.target.files;
            handleFiles(files);
        }
        
        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                if (file.name.endsWith('.zip')) {
                    showAlert('success', `Fichier "${file.name}" prêt à être installé.`);
                } else {
                    showAlert('danger', 'Veuillez sélectionner un fichier ZIP valide.');
                }
            }
        }
        
        // Module actions
        function activateModule(moduleId) {
            const moduleNames = {
                1: 'Analytics Pro',
                2: 'Email Marketing Suite',
                3: 'Security Plus',
                4: 'E-commerce Essentials',
                5: 'Core System',
                6: 'SEO Optimizer',
                7: 'Social Media Integration',
                8: 'Backup Manager'
            };
            
            const moduleName = moduleNames[moduleId] || 'Module';
            showAlert('success', `Module "${moduleName}" activé avec succès !`);
        }
        
        function deactivateModule(moduleId) {
            const moduleNames = {
                1: 'Analytics Pro',
                2: 'Email Marketing Suite',
                3: 'Security Plus',
                4: 'E-commerce Essentials',
                5: 'Core System',
                6: 'SEO Optimizer',
                7: 'Social Media Integration',
                8: 'Backup Manager'
            };
            
            const moduleName = moduleNames[moduleId] || 'Module';
            
            if (confirm(`Voulez-vous vraiment désactiver le module "${moduleName}" ?`)) {
                showAlert('info', `Module "${moduleName}" désactivé.`);
            }
        }
        
        function openModuleSettings(moduleId) {
            const moduleNames = {
                1: 'Analytics Pro',
                2: 'Email Marketing Suite',
                3: 'Security Plus',
                4: 'E-commerce Essentials',
                5: 'Core System',
                6: 'SEO Optimizer',
                7: 'Social Media Integration',
                8: 'Backup Manager'
            };
            
            const moduleName = moduleNames[moduleId] || 'Module';
            showAlert('info', `Ouverture des paramètres du module "${moduleName}"...`);
        }
        
        function showDeleteConfirmation(moduleId) {
            const modules = {
                1: { name: 'Analytics Pro', version: 'v2.1.0', author: 'Acme Corp', installed_at: '15/03/2024', icon: 'fas fa-chart-bar', icon_bg: 'linear-gradient(135deg, #6366f1, #8b5cf6)' },
                2: { name: 'Email Marketing Suite', version: 'v1.5.2', author: 'Marketing Team', installed_at: '02/02/2024', icon: 'fas fa-envelope', icon_bg: 'linear-gradient(135deg, #10b981, #059669)' },
                3: { name: 'Security Plus', version: 'v3.0.1', author: 'SecureSoft', installed_at: '10/01/2024', icon: 'fas fa-shield-alt', icon_bg: 'linear-gradient(135deg, #f59e0b, #d97706)' },
                4: { name: 'E-commerce Essentials', version: 'v1.2.3', author: 'E-commerce Team', installed_at: '20/03/2024', icon: 'fas fa-shopping-cart', icon_bg: 'linear-gradient(135deg, #3b82f6, #2563eb)' },
                5: { name: 'Core System', version: 'v4.0.2', author: 'System Team', installed_at: '01/01/2024', icon: 'fas fa-cog', icon_bg: 'linear-gradient(135deg, #8b5cf6, #6d28d9)' },
                6: { name: 'SEO Optimizer', version: 'v2.0.0', author: 'SEO Masters', installed_at: '05/03/2024', icon: 'fas fa-search', icon_bg: 'linear-gradient(135deg, #ec4899, #db2777)' },
                7: { name: 'Social Media Integration', version: 'v1.1.5', author: 'SocialTech', installed_at: '12/02/2024', icon: 'fas fa-share-alt', icon_bg: 'linear-gradient(135deg, #14b8a6, #0d9488)' },
                8: { name: 'Backup Manager', version: 'v2.3.1', author: 'IT Team', installed_at: '18/03/2024', icon: 'fas fa-database', icon_bg: 'linear-gradient(135deg, #f43f5e, #e11d48)' }
            };
            
            const module = modules[moduleId];
            if (!module) return;
            
            currentModuleToDelete = moduleId;
            
            const infoDiv = document.getElementById('moduleToDeleteInfo');
            infoDiv.innerHTML = `
                <div class="module-info">
                    <div class="module-info-icon" style="background: ${module.icon_bg};">
                        <i class="${module.icon}"></i>
                    </div>
                    <div>
                        <div class="module-info-name">${module.name}</div>
                        <div class="module-info-details">
                            <div><strong>Version:</strong> ${module.version}</div>
                            <div><strong>Auteur:</strong> ${module.author}</div>
                            <div><strong>Installé le:</strong> ${module.installed_at}</div>
                        </div>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            modal.show();
        }
        
        function confirmDelete() {
            if (!currentModuleToDelete) return;
            
            const modules = {
                1: 'Analytics Pro',
                2: 'Email Marketing Suite',
                3: 'Security Plus',
                4: 'E-commerce Essentials',
                5: 'Core System',
                6: 'SEO Optimizer',
                7: 'Social Media Integration',
                8: 'Backup Manager'
            };
            
            const moduleName = modules[currentModuleToDelete] || 'Module';
            
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            const originalText = deleteBtn.innerHTML;
            
            deleteBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-trash me-2"></i>Désinstaller
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Désinstallation...</span>
                </div>
                Désinstallation en cours...
            `;
            deleteBtn.disabled = true;
            
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                modal.hide();
                
                showAlert('success', `Module "${moduleName}" désinstallé avec succès !`);
                
                setTimeout(() => {
                    deleteBtn.innerHTML = originalText;
                    deleteBtn.disabled = false;
                    currentModuleToDelete = null;
                }, 500);
            }, 1500);
        }
        
        // Helper functions
        function showLoading() {
            document.getElementById('loadingSpinner').style.display = 'flex';
        }
        
        function hideLoading() {
            document.getElementById('loadingSpinner').style.display = 'none';
        }
        
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.style.minWidth = '300px';
            alertDiv.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>

    <style>
        /* Modules Grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }
        
        /* Module Card */
        .module-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #eaeaea;
            display: flex;
            flex-direction: column;
        }
        
        .module-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            border-color: transparent;
        }
        
        .module-card-header {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .module-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .module-badges {
            display: flex;
            gap: 6px;
        }
        
        .badge-core {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-official {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-paid {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-core-sm, .badge-official-sm {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.6rem;
            font-weight: 600;
            margin-left: 6px;
            vertical-align: middle;
        }
        
        .badge-core-sm {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
        }
        
        .badge-official-sm {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .module-card-body {
            padding: 20px;
            flex: 1;
        }
        
        .module-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .module-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 15px;
            min-height: 60px;
        }
        
        .module-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #666;
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 20px;
        }
        
        .meta-item i {
            color: var(--primary-color);
            font-size: 0.8rem;
        }
        
        .module-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 10px;
        }
        
        .star-filled {
            color: #ffd700;
        }
        
        .star-empty {
            color: #d1d5db;
        }
        
        .rating-count {
            color: #666;
            font-size: 0.8rem;
            margin-left: 6px;
        }
        
        .update-available {
            background: #fff3cd;
            color: #856404;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }
        
        .update-available i {
            color: #f59e0b;
        }
        
        .module-card-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }
        
        .status-active i {
            color: #10b981;
            font-size: 0.6rem;
        }
        
        .status-inactive {
            background: rgba(107, 114, 128, 0.1);
            color: #4b5563;
        }
        
        .status-inactive i {
            color: #6b7280;
            font-size: 0.6rem;
        }
        
        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }
        
        .status-pending i {
            color: #f59e0b;
            font-size: 0.6rem;
        }
        
        .module-actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            color: #666;
            border: 1px solid #eaeaea;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .activate-btn:hover {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }
        
        .deactivate-btn:hover {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }
        
        .view-btn:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .settings-btn:hover {
            background: #6b7280;
            border-color: #6b7280;
            color: white;
        }
        
        .delete-btn:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }
        
        /* Search Bar */
        .search-bar-modern {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 400px;
        }
        
        .search-input-modern {
            width: 100%;
            height: 40px;
            padding: 0 15px 0 40px;
            border: 1px solid #eaeaea;
            border-radius: 30px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .search-input-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .search-wrapper .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .view-options {
            display: flex;
            gap: 8px;
        }
        
        .view-option {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            background: white;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .view-option:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }
        
        .view-option.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Categories Navigation */
        .categories-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        
        .category-filter {
            padding: 8px 16px;
            border-radius: 30px;
            border: 1px solid #eaeaea;
            background: white;
            color: #666;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .category-filter:hover {
            background: #f8f9fa;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .category-filter.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* List View */
        .modules-list {
            overflow-x: auto;
        }
        
        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .modern-table th {
            text-align: left;
            padding: 15px;
            background: #f8f9fa;
            color: #666;
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 2px solid #eaeaea;
        }
        
        .modern-table td {
            padding: 15px;
            border-bottom: 1px solid #eaeaea;
            vertical-align: middle;
        }
        
        .modern-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .list-module-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .list-module-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .list-module-details {
            display: flex;
            flex-direction: column;
        }
        
        .list-module-name {
            font-weight: 500;
            color: #333;
            margin-bottom: 2px;
        }
        
        .list-module-description {
            font-size: 0.8rem;
            color: #666;
        }
        
        .version-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .current-version {
            font-weight: 500;
        }
        
        .update-indicator {
            color: #f59e0b;
            cursor: pointer;
        }
        
        .list-actions {
            display: flex;
            gap: 6px;
            justify-content: center;
        }
        
        .list-action-btn {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #666;
            text-decoration: none;
        }
        
        .list-action-btn:hover {
            transform: translateY(-2px);
        }
        
        /* Install Modal */
        .modern-modal .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }
        
        .upload-section {
            margin-bottom: 30px;
        }
        
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .upload-area.highlight {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.05);
        }
        
        .upload-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .upload-hint {
            color: #999;
            font-size: 0.8rem;
            margin-top: 15px;
        }
        
        .separator {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .separator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #eaeaea;
            z-index: 1;
        }
        
        .separator span {
            background: white;
            padding: 0 15px;
            color: #999;
            font-size: 0.9rem;
            position: relative;
            z-index: 2;
        }
        
        .marketplace-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        
        .marketplace-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }
        
        .marketplace-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            border: 1px solid #eaeaea;
        }
        
        .marketplace-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        }
        
        .marketplace-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .marketplace-info {
            flex: 1;
        }
        
        .marketplace-info h5 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .marketplace-info p {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 6px;
        }
        
        .marketplace-meta {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .price {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        .price-free {
            color: #10b981;
        }
        
        .rating {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #ffd700;
            font-size: 0.8rem;
        }
        
        .btn-install {
            background: white;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .btn-install:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .view-more-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .view-more-link:hover {
            text-decoration: underline;
        }
        
        /* Module info in delete modal */
        .module-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .module-info-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .module-info-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 5px;
        }
        
        .module-info-details {
            font-size: 0.9rem;
            color: #666;
        }
        
        /* Pagination */
        .pagination-container-modern {
            margin-top: 20px;
            padding: 20px;
            border-top: 1px solid #eaeaea;
        }
        
        .pagination {
            margin-bottom: 0;
        }
        
        .page-link {
            color: var(--primary-color);
            border: 1px solid #eaeaea;
            margin: 0 3px;
            border-radius: 8px !important;
        }
        
        .page-link:hover {
            background: #f8f9fa;
            color: var(--primary-color);
            border-color: #eaeaea;
        }
        
        .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stats-card-modern {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #eaeaea;
        }
        
        .stats-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stats-value-modern {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            line-height: 1.2;
        }
        
        .stats-label-modern {
            color: #666;
            font-size: 0.9rem;
        }
        
        .stats-icon-modern {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        /* Main Card */
        .main-card-modern {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
            overflow: hidden;
        }
        
        .card-header-modern {
            padding: 20px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title-modern {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .modules-count {
            color: #666;
            font-size: 0.9rem;
        }
        
        .card-body-modern {
            padding: 20px;
        }
        
        /* Filter Section */
        .filter-section-modern {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #eaeaea;
        }
        
        .filter-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .filter-title-modern {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .filter-actions-modern {
            display: flex;
            gap: 10px;
        }
        
        .form-label-modern {
            font-size: 0.85rem;
            font-weight: 500;
            color: #666;
            margin-bottom: 5px;
            display: block;
        }
        
        .form-select-modern {
            width: 100%;
            height: 40px;
            padding: 0 15px;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #333;
            background: white;
            cursor: pointer;
        }
        
        .form-select-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        /* Floating Action Button */
        .fab-modern {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .fab-modern:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.5);
        }
        
        /* Loading Spinner */
        .spinner-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
        }
        
        /* Empty State */
        .empty-state-modern {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon-modern {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }
        
        .empty-title-modern {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .empty-text-modern {
            color: #666;
            margin-bottom: 20px;
        }
        
        /* Delete Modal */
        .delete-confirm-modal .modal-content {
            border: none;
            border-radius: 16px;
        }
        
        .delete-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }
        
        .delete-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .delete-message {
            color: #666;
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .modules-grid {
                grid-template-columns: 1fr;
            }
            
            .search-bar-modern {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-wrapper {
                max-width: 100%;
            }
            
            .categories-nav {
                justify-content: center;
            }
            
            .marketplace-grid {
                grid-template-columns: 1fr;
            }
            
            .list-module-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .modern-table {
                font-size: 0.85rem;
            }
            
            .modern-table td, .modern-table th {
                padding: 10px;
            }
            
            .list-actions {
                flex-wrap: wrap;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-section-modern .row > div {
                margin-bottom: 10px;
            }
            
            .fab-modern {
                bottom: 20px;
                right: 20px;
                width: 48px;
                height: 48px;
                font-size: 1rem;
            }
        }
    </style>
@endsection