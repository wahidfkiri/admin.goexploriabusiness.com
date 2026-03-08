@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-bars"></i></span>
                Gestion des Menus
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Menu
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
                    <label for="filterType" class="form-label-modern">Type</label>
                    <select class="form-select-modern" id="filterType">
                        <option value="">Tous les types</option>
                        <option value="custom">Personnalisé</option>
                        <option value="category">Catégorie</option>
                        <option value="activity">Activité</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterParent" class="form-label-modern">Niveau</label>
                    <select class="form-select-modern" id="filterParent">
                        <option value="">Tous les niveaux</option>
                        <option value="root">Menus principaux</option>
                        <option value="child">Sous-menus</option>
                        <option value="subchild">Sous-sous-menus</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="order">Ordre</option>
                        <option value="title">Titre</option>
                        <option value="created_at">Date de création</option>
                        <option value="type">Type</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalMenus">0</div>
                        <div class="stats-label-modern">Total Menus</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeMenus">0</div>
                        <div class="stats-label-modern">Menus Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="mainMenus">0</div>
                        <div class="stats-label-modern">Menus Principaux</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="subMenus">0</div>
                        <div class="stats-label-modern">Sous-menus</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-sitemap"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Structure des Menus</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un menu..." id="searchInput">
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Menu Tree View -->
                <div class="menu-tree-container" id="menuTreeContainer">
                    <!-- Menu tree will be loaded here via AJAX -->
                </div>
                
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
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Niveau</th>
                                <th>Parent</th>
                                <th>Ordre</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                                <th>Pages</th>
                            </tr>
                        </thead>
                        <tbody id="menusTableBody">
                            <!-- Menus will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-bars"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun menu trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier menu.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un menu
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createMenuModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
   <!-- CREATE MENU MODAL -->
<div class="modal fade" id="createMenuModal" tabindex="-1" aria-labelledby="createMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern" id="createMenuModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Créer un nouveau menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <form id="createMenuForm">
                    @csrf
                    
                    <!-- Menu Level Selection -->
                    <div class="form-section-modern">
                        <h6 class="section-title-modern">
                            <i class="fas fa-layer-group me-2"></i>Niveau du menu
                            <i class="fas fa-question-circle ms-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top"
                               title="Sélectionnez le niveau hiérarchique du menu. Les sous-menus seront placés sous leurs parents respectifs."></i>
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="level-selector">
                                    <div class="level-option" data-level="0">
                                        <div class="level-icon">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div class="level-info">
                                            <div class="level-title">Menu Principal</div>
                                            <div class="level-description">Niveau supérieur du menu</div>
                                        </div>
                                        <div class="level-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="level-option" data-level="1">
                                        <div class="level-icon">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div class="level-info">
                                            <div class="level-title">Sous-menu</div>
                                            <div class="level-description">Premier niveau enfant</div>
                                        </div>
                                        <div class="level-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="level-option" data-level="2">
                                        <div class="level-icon">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div class="level-info">
                                            <div class="level-title">Sous-sous-menu</div>
                                            <div class="level-description">Deuxième niveau enfant</div>
                                        </div>
                                        <div class="level-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="selectedLevel" name="level" value="0">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Parent Selection (for submenus) -->
                    <div class="form-section-modern" id="parentSelectionSection" style="display: none;">
                        <h6 class="section-title-modern">
                            <i class="fas fa-sitemap me-2"></i>Menu Parent
                            <i class="fas fa-question-circle ms-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top"
                               title="Sélectionnez le menu parent sous lequel ce sous-menu sera placé. Seuls les menus de niveau supérieur sont disponibles."></i>
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div id="parentSelectContainer">
                                    <!-- Parent selection will be loaded dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Basic Information -->
                    <div class="form-section-modern">
                        <h6 class="section-title-modern">
                            <i class="fas fa-info-circle me-2"></i>Informations de base
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="menuTitle" class="form-label-modern">
                                    Titre *
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Le nom du menu tel qu'il apparaîtra dans la navigation. Doit être clair et descriptif. Ex: 'Accueil', 'Nos Produits', 'Contactez-nous'."></i>
                                </label>
                                <input type="text" class="form-control-modern" id="menuTitle" name="title" 
                                       placeholder="Ex: Accueil, Produits, Contact..." required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="menuSlug" class="form-label-modern">
                                    Slug *
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Identifiant unique utilisé dans les URLs. Généré automatiquement à partir du titre, mais modifiable. Doit être en minuscules sans accents ni espaces. Ex: 'nos-produits', 'contactez-nous'."></i>
                                </label>
                                <input type="text" class="form-control-modern" id="menuSlug" name="slug" 
                                       placeholder="Ex: accueil, produits, contact..." required>
                                <div class="form-text-modern">Identifiant unique pour le menu</div>
                            </div>
                        </div>

                        <!-- Image upload section - visible only for submenus -->
                        <div class="form-section-modern" id="imageUploadSection" style="display: none;">
                            <h6 class="section-title-modern">
                                <i class="fas fa-image me-2"></i>Image du sous-menu
                                <i class="fas fa-question-circle ms-1" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top"
                                   title="Image optionnelle pour les sous-menus. Formats supportés: JPG, PNG, GIF, SVG, WebP. Taille maximale: 2MB. Dimensions recommandées: 300x200px."></i>
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="image-upload-container">
                                        <!-- Image preview -->
                                        <div class="image-preview mb-3" id="imagePreview" style="display: none;">
                                            <img id="previewImage" src="" alt="Aperçu de l'image" class="img-fluid rounded" style="max-height: 200px;">
                                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                                <i class="fas fa-trash me-1"></i>Supprimer l'image
                                            </button>
                                        </div>
                                        
                                        <!-- Upload input -->
                                        <div class="upload-area" id="uploadArea">
                                            <div class="upload-placeholder">
                                                <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                                                <h5>Glissez-déposez votre image ici</h5>
                                                <p class="text-muted">ou cliquez pour parcourir</p>
                                                <p class="small text-muted">Formats supportés: JPG, PNG, GIF, SVG (max 2MB)</p>
                                            </div>
                                            <input type="file" 
                                                   class="form-control-modern d-none" 
                                                   id="menuImage" 
                                                   name="image"
                                                   accept=".jpg,.jpeg,.png,.gif,.svg,.webp">
                                            <input type="hidden" id="removeImageFlag" name="remove_image" value="0">
                                        </div>
                                        
                                        <!-- Progress bar (hidden by default) -->
                                        <div class="progress mt-3" id="uploadProgress" style="display: none; height: 10px;">
                                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                                 role="progressbar" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="menuType" class="form-label-modern">
                                    Type de menu *
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Définit le comportement du menu. Personnalisé: lien libre vers n'importe quelle URL. Catégorie: lie automatiquement à une catégorie existante. Activité: lie automatiquement à une activité existante."></i>
                                </label>
                                <select class="form-select-modern" id="menuType" name="type" required>
                                    <option value="custom">Personnalisé</option>
                                    <option value="category">Catégorie</option>
                                    <option value="activity">Activité</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="menuOrder" class="form-label-modern">
                                    Ordre
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Détermine la position dans le menu. Plus le nombre est bas, plus le menu apparaît tôt. Ex: 0 = premier, 1 = deuxième, etc. Les menus avec le même ordre sont triés alphabétiquement."></i>
                                </label>
                                <input type="number" class="form-control-modern" id="menuOrder" name="order" 
                                       value="0" min="0">
                                <div class="form-text-modern">Position dans le menu</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content Selection (for category/activity types) -->
                    <div class="form-section-modern" id="contentSelectionSection" style="display: none;">
                        <h6 class="section-title-modern">
                            <i class="fas fa-link me-2"></i>Sélection du contenu
                            <i class="fas fa-question-circle ms-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top"
                               title="Sélectionnez un contenu existant (catégorie ou activité) auquel le menu sera lié automatiquement. L'URL sera générée automatiquement en fonction du contenu sélectionné."></i>
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div id="contentSelectContainer">
                                    <!-- Content selection will be loaded dynamically based on type -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- URL Configuration -->
                    <div class="form-section-modern d-none" id="urlConfigurationSection">
                        <h6 class="section-title-modern">
                            <i class="fas fa-link me-2"></i>Configuration de l'URL
                            <i class="fas fa-question-circle ms-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top"
                               title="URL personnalisée pour le menu. Laissez vide pour une URL automatique basée sur le type de contenu. Pour les menus personnalisés uniquement."></i>
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="menuUrl" class="form-label-modern">
                                    URL personnalisée
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Peut être une URL relative (/page) ou absolue (https://...). Pour les liens externes, utilisez 'https://'. Pour les pages internes, utilisez '/chemin/vers/page'. Ex: '/a-propos', 'https://exemple.com', '/categorie/produits'."></i>
                                </label>
                                <input type="text" class="form-control-modern" id="menuUrl" name="url" 
                                       placeholder="Ex: /about, /products, https://example.com">
                                <div class="form-text-modern">Laisser vide pour URL automatique</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Options -->
                    <div class="form-section-modern">
                        <h6 class="section-title-modern">
                            <i class="fas fa-cog me-2"></i>Options avancées
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="menuStatus" class="form-label-modern">
                                    Statut
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Activez ou désactivez l'affichage du menu dans la navigation. 'Actif': menu visible. 'Inactif': menu caché mais conservé dans la base de données (peut être réactivé ultérieurement)."></i>
                                </label>
                                <select class="form-select-modern" id="menuStatus" name="is_active">
                                    <option value="1">Actif</option>
                                    <option value="0">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modal-footer-modern">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary btn-pulse" id="submitMenuBtn">
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le menu
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
    
    <!-- EDIT MENU MODAL -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern" id="editMenuModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier le menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <form id="editMenuForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editMenuId" name="id">
                    
                    <!-- Basic Information -->
                    <div class="form-section-modern">
                        <h6 class="section-title-modern">
                            <i class="fas fa-info-circle me-2"></i>Informations de base
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editMenuTitle" class="form-label-modern">
                                    Titre *
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Modifiez le nom du menu tel qu'il apparaîtra dans la navigation. Assurez-vous qu'il reste clair et descriptif pour les utilisateurs."></i>
                                </label>
                                <input type="text" class="form-control-modern" id="editMenuTitle" name="title" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editMenuSlug" class="form-label-modern">
                                    Slug *
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Modifiez l'identifiant unique utilisé dans les URLs. Attention: changer le slug peut affecter les liens existants et le référencement. Utilisez des traits d'union, pas d'espaces ni d'accents."></i>
                                </label>
                                <input type="text" class="form-control-modern" id="editMenuSlug" name="slug" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editMenuType" class="form-label-modern">
                                    Type de menu *
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Changez le type de menu si nécessaire. Attention: changer de type peut modifier le comportement et l'URL du menu. 'Personnalisé' permet une URL libre, 'Catégorie/Activité' lie à un contenu existant."></i>
                                </label>
                                <select class="form-select-modern" id="editMenuType" name="type" required>
                                    <option value="custom">Personnalisé</option>
                                    <option value="category">Catégorie</option>
                                    <option value="activity">Activité</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editMenuOrder" class="form-label-modern">
                                    Ordre
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Ajustez la position dans le menu. Les menus sont triés par ordre croissant (0 = premier). Modifiez cette valeur pour réorganiser l'affichage des menus dans la navigation."></i>
                                </label>
                                <input type="number" class="form-control-modern" id="editMenuOrder" name="order" min="0">
                            </div>
                        </div>

                        <!-- Image upload section for editing -->
                        <div class="form-section-modern" id="editImageUploadSection" style="display: none;">
                            <h6 class="section-title-modern">
                                <i class="fas fa-image me-2"></i>Image du sous-menu
                                <i class="fas fa-question-circle ms-1" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top"
                                   title="Modifiez l'image associée au sous-menu. Téléchargez une nouvelle image ou supprimez l'image existante. Formats supportés: JPG, PNG, GIF, SVG, WebP (max 2MB)."></i>
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="image-upload-container">
                                        <!-- Image preview for editing -->
                                        <div class="image-preview mb-3" id="editImagePreview">
                                            <!-- Will be populated with existing image if any -->
                                        </div>
                                        
                                        <!-- Upload input for editing -->
                                        <div class="upload-area" id="editUploadArea">
                                            <div class="upload-placeholder">
                                                <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                                                <h5>Cliquez pour changer l'image</h5>
                                                <p class="text-muted">Glissez-déposez ou cliquez pour parcourir</p>
                                                <p class="small text-muted">Formats supportés: JPG, PNG, GIF, SVG (max 2MB)</p>
                                            </div>
                                            <input type="file" 
                                                   class="form-control-modern d-none" 
                                                   id="editMenuImage" 
                                                   name="image"
                                                   accept=".jpg,.jpeg,.png,.gif,.svg,.webp">
                                            <input type="hidden" id="editRemoveImageFlag" name="remove_image" value="0">
                                            <input type="hidden" id="editCurrentImage" name="current_image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editMenuParent" class="form-label-modern">
                                    Menu Parent
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Changez le menu parent pour déplacer ce menu dans la hiérarchie. Sélectionnez 'Aucun' pour en faire un menu principal. Attention: déplacer un menu peut affecter ses sous-menus."></i>
                                </label>
                                <select class="form-select-modern" id="editMenuParent" name="parent_id">
                                    <option value="">Aucun (menu principal)</option>
                                    <!-- Parent options will be loaded dynamically -->
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editMenuReference" class="form-label-modern">
                                    Référence
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Changez la référence (catégorie ou activité) liée à ce menu. Disponible uniquement pour les types 'Catégorie' et 'Activité'. La sélection détermine le contenu affiché quand on clique sur le menu."></i>
                                </label>
                                <select class="form-select-modern" id="editMenuReference" name="reference_id">
                                    <option value="">Sélectionner...</option>
                                    <!-- Reference options will be loaded dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- URL Configuration -->
                    <div class="form-section-modern" id="editUrlConfigurationSection">
                        <h6 class="section-title-modern">
                            <i class="fas fa-link me-2"></i>Configuration de l'URL
                            <i class="fas fa-question-circle ms-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top"
                               title="Modifiez l'URL du menu. Pour les menus personnalisés, vous pouvez définir une URL libre. Pour les menus de type 'Catégorie' ou 'Activité', l'URL est généralement générée automatiquement à partir de la référence."></i>
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="editMenuUrl" class="form-label-modern">
                                    URL personnalisée
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="URL complète ou relative. Pour les liens internes: '/chemin/vers/page'. Pour les liens externes: 'https://domaine.com/chemin'. Laissez vide pour une URL automatique basée sur la référence."></i>
                                </label>
                                <input type="text" class="form-control-modern" id="editMenuUrl" name="url">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Options -->
                    <div class="form-section-modern">
                        <h6 class="section-title-modern">
                            <i class="fas fa-cog me-2"></i>Options avancées
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="editMenuStatus" class="form-label-modern">
                                    Statut
                                    <i class="fas fa-question-circle ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Activez ou désactivez l'affichage du menu. 'Inactif' masque le menu de la navigation sans le supprimer. Utile pour cacher temporairement un menu tout en conservant sa configuration."></i>
                                </label>
                                <select class="form-select-modern" id="editMenuStatus" name="is_active">
                                    <option value="1">Actif</option>
                                    <option value="0">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modal-footer-modern">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateMenuBtn">
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
                <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce menu ? Tous les sous-menus seront également supprimés.</p>
                
                <div class="menu-to-delete" id="menuToDeleteInfo">
                    <!-- Menu info will be loaded here -->
                </div>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Attention :</strong> Cette action est irréversible et supprimera tous les sous-menus.
                    <i class="fas fa-question-circle ms-1" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="top"
                       title="La suppression d'un menu principal entraîne automatiquement la suppression de tous ses sous-menus. Cette action ne peut pas être annulée. Assurez-vous de ne plus avoir besoin de ce menu ou de ses sous-menus."></i>
                </div>
                
                <div class="alert alert-info mt-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Conseil :</strong> Pour cacher temporairement un menu, utilisez plutôt le statut "Inactif" dans les options du menu.
                    <i class="fas fa-question-circle ms-1" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="top"
                       title="Si vous souhaitez seulement masquer le menu sans le supprimer définitivement, modifiez le menu et changez son statut en 'Inactif'. Vous pourrez le réactiver ultérieurement sans perdre sa configuration."></i>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelDeleteBtn">
                    <i class="fas fa-times me-2"></i>Annuler
                    <i class="fas fa-question-circle ms-1" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="top"
                       title="Annule la suppression et retourne à la liste des menus. Aucune modification ne sera effectuée."></i>
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <span class="btn-text">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        <i class="fas fa-question-circle ms-1" 
                           data-bs-toggle="tooltip" 
                           data-bs-placement="top"
                           title="Confirme la suppression définitive du menu et de tous ses sous-menus. Cette action est irréversible et supprime également toutes les configurations associées."></i>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allMenus = [];
        let menuToDelete = null;
        let categories = [];
        let activities = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadMenus();
            loadStatistics();
            loadCategories();
            loadActivities();
            setupEventListeners();
            setupMenuTypeHandlers();
            setupLevelSelector();
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

        // Load menus
        const loadMenus = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("destinations.menus.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    console.log('Menus response:', response);
                    
                    if (response.success) {
                        allMenus = response.data || [];
                        renderMenus(allMenus);
                        renderPagination(response);
                        updateMenuTree(allMenus);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des menus');
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
                url: '{{ route("destinations.menus.statistics") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalMenus').textContent = stats.total_menus || 0;
                        document.getElementById('activeMenus').textContent = stats.active_menus || 0;
                        document.getElementById('mainMenus').textContent = stats.main_menus || 0;
                        document.getElementById('subMenus').textContent = stats.sub_menus || 0;
                    }
                },
                error: function(xhr) {
                    console.error('Statistics error:', xhr.responseText);
                }
            });
        };

        // Load categories
        const loadCategories = () => {
            $.ajax({
                url: '{{ route("destinations.menus.categories") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        categories = response.data || [];
                    }
                }
            });
        };

        // Load activities
        const loadActivities = () => {
            $.ajax({
                url: '{{ route("destinations.menus.activities") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        activities = response.data || [];
                    }
                }
            });
        };

        // Render menus table
        const renderMenus = (menus) => {
            const tbody = document.getElementById('menusTableBody');
            tbody.innerHTML = '';
            
            if (!menus || menus.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            menus.forEach((menu, index) => {
                const row = document.createElement('tr');
                row.id = `menu-row-${menu.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                const level = menu.parent_id ? (menu.parent?.parent_id ? 'Sous-sous-menu' : 'Sous-menu') : 'Principal';
                const levelClass = menu.parent_id ? (menu.parent?.parent_id ? 'level-2' : 'level-1') : 'level-0';
                
                row.innerHTML = `
                    <td>
                        <div class="menu-name-cell">
                            <div class="menu-icons ${menu.icon || 'default-icon'}">
                                <i class="${menu.icon || 'fas fa-link'}"></i>
                            </div>
                            <div>
                                <div class="menu-name-text">${menu.title}</div>
                                <small class="text-muted">/${menu.slug}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="menu-type-badge ${menu.type}">
                            ${getTypeLabel(menu.type)}
                        </span>
                    </td>
                    <td>
                        <span class="level-badge ${levelClass}">${level}</span>
                    </td>
                    <td>
                        ${menu.parent ? menu.parent.title : '<em>Aucun</em>'}
                    </td>
                    <td>
                        <span class="order-badge">${menu.order}</span>
                    </td>
                    <td>
                        <span class="status-badge ${menu.is_active ? 'active' : 'inactive'}">
                            ${menu.is_active ? 'Actif' : 'Inactif'}
                        </span>
                    </td>
                    <td>
                        <div class="menu-actions-modern">
                            <button class="action-btn-modern move-up-btn-modern" title="Déplacer vers le haut" 
                                    onclick="moveMenu(${menu.id}, 'up')">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <button class="action-btn-modern move-down-btn-modern" title="Déplacer vers le bas" 
                                    onclick="moveMenu(${menu.id}, 'down')">
                                <i class="fas fa-arrow-down"></i>
                            </button>
                            <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                    onclick="openEditModal(${menu.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteConfirmation(${menu.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                // Dans la fonction renderMenus(), ajoutez une colonne pour les pages
row.innerHTML += `
    <td>
        <div class="page-management">
            <div class="page-status-badge ${menu.has_page ? 'has-page' : 'no-page'}">
                ${menu.has_page ? '📄 Page' : '❌ Pas de page'}
            </div>
            ${menu.has_page ? `
                <div class="page-actions">
                    <a href="{{ url('menus/template/view') }}/${menu.id}" target="_blank" class="btn btn-sm btn-outline-primary" title="Voir la page">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ url('menus/template/edit') }}/${menu.id}" class="btn btn-sm btn-outline-success" title="Éditer la page" target="_blank">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-warning toggle-page-btn" 
                            data-id="${menu.id}" 
                            title="${menu.has_page ? 'Désactiver la page' : 'Activer la page'}">
                        <i class="fas ${menu.has_page ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
                    </button>
                </div>
            ` : `
                <button class="btn btn-sm btn-outline-success create-page-btn" data-id="${menu.id}">
                    <i class="fas fa-plus"></i> Créer une page
                </button>
            `}
        </div>
    </td>
`;

// Gestionnaires d'événements pour les boutons de page
$(document).on('click', '.toggle-page-btn', function() {
    const menuId = $(this).data('id');
    togglePage(menuId);
});

$(document).on('click', '.create-page-btn', function() {
    const menuId = $(this).data('id');
    createPage(menuId);
});

const togglePage = (menuId) => {
    $.ajax({
        url: `/menus/${menuId}/toggle-page`,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                loadMenus(currentPage, currentFilters);
                showAlert('success', response.message);
            }
        }
    });
};

// Image upload handling for create form
const setupImageUpload = () => {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('menuImage');
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const imageSection = document.getElementById('imageUploadSection');
    
    if (uploadArea && fileInput) {
        // Click to select file
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });
        
        // Drag and drop
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
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleImageSelection(fileInput.files[0]);
            }
        });
        
        // File input change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleImageSelection(e.target.files[0]);
            }
        });
    }
};

// Handle image selection
const handleImageSelection = (file) => {
    if (!file.type.match('image.*')) {
        showAlert('danger', 'Veuillez sélectionner une image valide');
        return;
    }
    
    if (file.size > 2 * 1024 * 1024) { // 2MB
        showAlert('danger', 'L\'image est trop volumineuse (max 2MB)');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = (e) => {
        const preview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        const uploadArea = document.getElementById('uploadArea');
        
        previewImage.src = e.target.result;
        preview.style.display = 'block';
        uploadArea.style.display = 'none';
    };
    reader.readAsDataURL(file);
};

// Remove image in create form
const removeImage = () => {
    const fileInput = document.getElementById('menuImage');
    const preview = document.getElementById('imagePreview');
    const uploadArea = document.getElementById('uploadArea');
    
    fileInput.value = '';
    preview.style.display = 'none';
    uploadArea.style.display = 'block';
    
    // Set flag to remove image on server
    document.getElementById('removeImageFlag').value = '1';
};

// Setup image upload for edit form
const setupEditImageUpload = () => {
    const uploadArea = document.getElementById('editUploadArea');
    const fileInput = document.getElementById('editMenuImage');
    
    if (uploadArea && fileInput) {
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleEditImageSelection(e.target.files[0]);
            }
        });
    }
};

// Handle edit image selection
const handleEditImageSelection = (file) => {
    if (!file.type.match('image.*')) {
        showAlert('danger', 'Veuillez sélectionner une image valide');
        return;
    }
    
    if (file.size > 2 * 1024 * 1024) {
        showAlert('danger', 'L\'image est trop volumineuse (max 2MB)');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = (e) => {
        const preview = document.getElementById('editImagePreview');
        const previewImage = document.getElementById('editPreviewImage');
        const uploadArea = document.getElementById('editUploadArea');
        
        previewImage.src = e.target.result;
        preview.style.display = 'block';
        uploadArea.style.display = 'none';
    };
    reader.readAsDataURL(file);
};

// Remove new image in edit form
const removeNewImage = () => {
    const fileInput = document.getElementById('editMenuImage');
    const preview = document.getElementById('editImagePreview');
    const uploadArea = document.getElementById('editUploadArea');
    
    fileInput.value = '';
    preview.style.display = 'none';
    uploadArea.style.display = 'block';
};

// Remove current image in edit form
const removeCurrentImage = () => {
    const currentImageContainer = document.getElementById('currentImageContainer');
    const removeFlag = document.getElementById('editRemoveImageFlag');
    
    currentImageContainer.style.display = 'none';
    removeFlag.value = '1';
    
    showAlert('warning', 'L\'image actuelle sera supprimée lors de l\'enregistrement');
};

// Show/hide image section based on level
const toggleImageSection = (level) => {
    const imageSection = document.getElementById('imageUploadSection');
    
    if (level > 0) {
        imageSection.style.display = 'block';
    } else {
        imageSection.style.display = 'none';
        removeImage(); // Clear image if switching to main menu
    }
};

// Load current image in edit form
const loadCurrentImage = (menu) => {
    const container = document.getElementById('currentImageContainer');
    const preview = document.getElementById('currentImagePreview');
    const link = document.getElementById('currentImageLink');
    
    if (menu.image_url) {
        preview.src = menu.image_url;
        link.href = menu.image_url;
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
};

// Initialize image upload in DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...
    setupImageUpload();
    setupEditImageUpload();
    
    // Add image toggle to level selector
    const levelOptions = document.querySelectorAll('.level-option');
    levelOptions.forEach(option => {
        option.addEventListener('click', function() {
            const level = parseInt(this.dataset.level);
            toggleImageSection(level);
        });
    });
});

const createPage = (menuId) => {
    window.location.href = `/menus/${menuId}/page`;
};
                tbody.appendChild(row);
            });
            
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
            document.getElementById('paginationContainer').style.display = 'flex';
        };

        // Update menu tree view
        const updateMenuTree = (menus) => {
            const container = document.getElementById('menuTreeContainer');
            const rootMenus = menus.filter(menu => !menu.parent_id);
            
            let treeHtml = '<div class="menu-tree">';
            
            rootMenus.forEach(menu => {
                treeHtml += buildTreeItem(menu, menus);
            });
            
            treeHtml += '</div>';
            container.innerHTML = treeHtml;
        };

        // Build tree item recursively
        const buildTreeItem = (menu, allMenus, level = 0) => {
            const children = allMenus.filter(m => m.parent_id === menu.id);
            const hasChildren = children.length > 0;
            
            let html = `
                <div class="tree-item level-${level}" data-id="${menu.id}">
                    <div class="tree-item-header">
                        <div class="tree-item-toggle ${hasChildren ? 'has-children' : ''}" onclick="toggleTreeItem(${menu.id})">
                            ${hasChildren ? '<i class="fas fa-chevron-right"></i>' : ''}
                        </div>
                        <div class="tree-item-content">
                            <div class="tree-item-icon">
                                <i class="${menu.icon || 'fas fa-link'}"></i>
                            </div>
                            
                            <div class="tree-item-info">
                                <div class="tree-item-title">${menu.title}</div>
                                <div class="tree-item-details">
                                    <span class="badge badge-sm ${menu.type}">${getTypeLabel(menu.type)}</span>
                                    <span class="badge badge-sm order">Ordre: ${menu.order}</span>
                                    ${menu.is_active ? '<span class="badge badge-sm active">Actif</span>' : '<span class="badge badge-sm inactive">Inactif</span>'}
                                </div>
                            </div>
                        </div>
                        <div class="tree-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${menu.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="showDeleteConfirmation(${menu.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                    <a href="{{ url('menus/template/view') }}/${menu.id}" target="_blank" class="btn btn-sm btn-outline-primary" title="Voir la page">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ url('menus/template/edit') }}/${menu.id}" class="btn btn-sm btn-outline-success" title="Éditer la page" target="_blank">
                        <i class="fas fa-edit"></i>
                    </a>
                        </div>
                    </div>
            `;
            
            if (hasChildren) {
                html += '<div class="tree-item-children" style="display: none;">';
                children.forEach(child => {
                    html += buildTreeItem(child, allMenus, level + 1);
                });
                html += '</div>';
            }
            
            html += '</div>';
            return html;
        };

        // Toggle tree item
        const toggleTreeItem = (menuId) => {
            const item = document.querySelector(`.tree-item[data-id="${menuId}"]`);
            if (!item) return;
            
            const children = item.querySelector('.tree-item-children');
            const toggle = item.querySelector('.tree-item-toggle');
            
            if (children) {
                if (children.style.display === 'none') {
                    children.style.display = 'block';
                    toggle.querySelector('i').className = 'fas fa-chevron-down';
                } else {
                    children.style.display = 'none';
                    toggle.querySelector('i').className = 'fas fa-chevron-right';
                }
            }
        };

        // Get type label
        const getTypeLabel = (type) => {
            const labels = {
                'custom': 'Personnalisé',
                'category': 'Catégorie',
                'activity': 'Activité'
            };
            return labels[type] || type;
        };

        // Setup level selector
        const setupLevelSelector = () => {
            const levelOptions = document.querySelectorAll('.level-option');
            const parentSection = document.getElementById('parentSelectionSection');
            
            levelOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove active class from all options
                    levelOptions.forEach(opt => opt.classList.remove('active'));
                    
                    // Add active class to clicked option
                    this.classList.add('active');
                    
                    // Update hidden input
                    const level = this.dataset.level;
                    document.getElementById('selectedLevel').value = level;
                    
                    // Show/hide parent selection
                    if (level > 0) {
                        parentSection.style.display = 'block';
                        loadParentOptions(level);
                    } else {
                        parentSection.style.display = 'none';
                    }
                });
            });
            
            // Select first level by default
            levelOptions[0].classList.add('active');
        };

        // Load parent options based on level
        const loadParentOptions = (level) => {
            const container = document.getElementById('parentSelectContainer');
            
            let url = '{{ route("destinations.menus.parents") }}';
            if (level == 2) {
                url = '{{ route("destinations.menus.subparents") }}';
            }
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const parents = response.data;
                        
                        let options = '<select class="form-select-modern" id="menuParent" name="parent_id" required>';
                        options += '<option value="">Sélectionnez un menu parent</option>';
                        
                        parents.forEach(parent => {
                            const prefix = level == 2 ? '-- ' : '';
                            options += `<option value="${parent.id}">${prefix}${parent.title}</option>`;
                        });
                        
                        options += '</select>';
                        container.innerHTML = options;
                    }
                }
            });
        };

        // Setup menu type handlers
        const setupMenuTypeHandlers = () => {
            const menuType = document.getElementById('menuType');
            const contentSection = document.getElementById('contentSelectionSection');
            const urlSection = document.getElementById('urlConfigurationSection');
            
            if (menuType) {
                menuType.addEventListener('change', function() {
                    const type = this.value;
                    
                    if (type === 'custom') {
                        contentSection.style.display = 'none';
                        urlSection.style.display = 'block';
                    } else {
                        contentSection.style.display = 'block';
                        urlSection.style.display = 'none';
                        loadContentOptions(type);
                    }
                });
            }
            
            // Handle edit modal type change
            const editMenuType = document.getElementById('editMenuType');
            if (editMenuType) {
                editMenuType.addEventListener('change', function() {
                    const type = this.value;
                    loadEditReferenceOptions(type);
                });
            }
        };

        // Load content options based on type
        const loadContentOptions = (type) => {
            const container = document.getElementById('contentSelectContainer');
            
            let items = [];
            let label = '';
            
            if (type === 'category') {
                items = categories;
                label = 'Catégorie';
            } else if (type === 'activity') {
                items = activities;
                label = 'Activité';
            }
            
            let html = `
                <label class="form-label-modern">Sélectionner une ${label}</label>
                <select class="form-select-modern" id="menuReference" name="reference_id" required>
                    <option value="">Sélectionnez une ${label}</option>
            `;
            
            items.forEach(item => {
                html += `<option value="${item.id}">${item.name || item.title}</option>`;
            });
            
            html += '</select>';
            container.innerHTML = html;
        };

        // Load edit reference options
        const loadEditReferenceOptions = (type) => {
            const select = document.getElementById('editMenuReference');
            if (!select) return;
            
            select.innerHTML = '<option value="">Sélectionner...</option>';
            
            if (type === 'category') {
                categories.forEach(category => {
                    select.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                });
            } else if (type === 'activity') {
                activities.forEach(activity => {
                    select.innerHTML += `<option value="${activity.id}">${activity.name} (${activity.category?.name})</option>`;
                });
            }
        };

    // Store menu with image upload
const storeMenu = () => {
    const form = document.getElementById('createMenuForm');
    const submitBtn = document.getElementById('submitMenuBtn');
    
    // Validation
    if (!validateCreateMenuForm()) {
        return;
    }
    
    // Désactiver le bouton et montrer le loader
    $(submitBtn).prop('disabled', true).html(
        '<div class="spinner-border spinner-border-sm me-2"></div>Création en cours...'
    );
    
    // Use FormData to handle file upload
    const formData = new FormData(form);
    
    // Get additional data
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('title', $('#menuTitle').val().trim());
    formData.append('slug', $('#menuSlug').val().trim());
    formData.append('type', $('#menuType').val());
    formData.append('level', $('#selectedLevel').val());
    formData.append('order', $('#menuOrder').val() || 0);
    formData.append('is_active', $('#menuStatus').val() || 1);
    
    // Add parent_id if level > 0
    const level = parseInt($('#selectedLevel').val());
    if (level > 0) {
        formData.append('parent_id', $('#menuParent').val());
    }
    
    // Add reference_id if not custom
    if ($('#menuType').val() !== 'custom') {
        formData.append('reference_id', $('#menuReference').val());
    }
    
    // Add url and route
    if ($('#menuUrl').val()) {
        formData.append('url', $('#menuUrl').val());
    }
    if ($('#menuRoute').val()) {
        formData.append('route', $('#menuRoute').val());
    }
    
    // Add icon
    if ($('#menuIcon').val()) {
        formData.append('icon', $('#menuIcon').val());
    }
    
    // Envoyer la requête avec FormData
    $.ajax({
        url: '{{ route("destinations.menus.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            
            // Upload progress
            xhr.upload.addEventListener('progress', function(evt) {
                if (evt.lengthComputable) {
                    const percentComplete = (evt.loaded / evt.total) * 100;
                    $('#progressBar').css('width', percentComplete + '%');
                }
            }, false);
            
            return xhr;
        },
        beforeSend: function() {
            $('#uploadProgress').show();
        },
        success: function(response) {
            if (response.success) {
                // Fermer le modal
                closeModalProperly('#createMenuModal');
                
                // Réinitialiser le formulaire
                form.reset();
                resetImageUpload();
                
                // Réinitialiser le level selector
                $('.level-option').removeClass('active');
                $('.level-option[data-level="0"]').addClass('active');
                $('#selectedLevel').val('0');
                $('#parentSelectionSection').hide();
                $('#imageUploadSection').hide();
                
                // Recharger les données
                loadMenus(1, currentFilters);
                loadStatistics();
                
                // Afficher le message de succès
                showAlert('success', 'Menu créé avec succès !');
            } else {
                showAlert('danger', response.message || 'Erreur lors de la création');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText);
            
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                for (const field in errors) {
                    errorMessage += `- ${errors[field].join('<br>')}<br>`;
                }
                showAlert('danger', errorMessage);
                
                // Marquer les champs invalides
                for (const field in errors) {
                    $(`[name="${field}"]`).addClass('is-invalid')
                        .after(`<div class="invalid-feedback">${errors[field].join('<br>')}</div>`);
                }
            } else {
                showAlert('danger', 'Erreur lors de la création: ' + error);
            }
        },
        complete: function() {
            // Cacher la barre de progression
            $('#uploadProgress').hide();
            $('#progressBar').css('width', '0%');
            
            // Réactiver le bouton
            $(submitBtn).prop('disabled', false).html(
                '<i class="fas fa-save me-2"></i>Créer le menu'
            );
        }
    });
};

// Reset image upload
const resetImageUpload = () => {
    const fileInput = document.getElementById('menuImage');
    const preview = document.getElementById('imagePreview');
    const uploadArea = document.getElementById('uploadArea');
    
    fileInput.value = '';
    preview.style.display = 'none';
    uploadArea.style.display = 'block';
    document.getElementById('removeImageFlag').value = '0';
};


// Validation pour le formulaire de création
const validateCreateMenuForm = () => {
    let isValid = true;
    let errorMessage = '';
    
    // Réinitialiser toutes les classes d'erreur
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Titre
    const title = $('#menuTitle').val().trim();
    if (!title) {
        isValid = false;
        errorMessage += '- Le titre est requis<br>';
        $('#menuTitle').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le titre est requis</div>');
    }
    
    // Slug
    const slug = $('#menuSlug').val().trim();
    if (!slug) {
        isValid = false;
        errorMessage += '- Le slug est requis<br>';
        $('#menuSlug').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le slug est requis</div>');
    }
    
    // Type
    const type = $('#menuType').val();
    if (!type) {
        isValid = false;
        errorMessage += '- Le type de menu est requis<br>';
        $('#menuType').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le type de menu est requis</div>');
    }
    
    // Vérifier le niveau
    const level = parseInt($('#selectedLevel').val());
    
    // Parent (seulement si level > 0)
    if (level > 0) {
        const parentSelect = $('#menuParent');
        if (!parentSelect.length || !parentSelect.val()) {
            isValid = false;
            errorMessage += '- Veuillez sélectionner un menu parent<br>';
            parentSelect.addClass('is-invalid')
                .after('<div class="invalid-feedback">Veuillez sélectionner un menu parent</div>');
        }
    }
    
    // Référence (seulement si type n'est pas custom)
    if (type !== 'custom') {
        const referenceSelect = $('#menuReference');
        if (!referenceSelect.length || !referenceSelect.val()) {
            isValid = false;
            const typeLabel = type === 'category' ? 'catégorie' : 'activité';
            errorMessage += `- Veuillez sélectionner une ${typeLabel}<br>`;
            referenceSelect.addClass('is-invalid')
                .after(`<div class="invalid-feedback">Veuillez sélectionner une ${typeLabel}</div>`);
        }
    }
    
    // Valider que le slug ne contient pas d'espaces
    if (slug && /\s/.test(slug)) {
        isValid = false;
        errorMessage += '- Le slug ne doit pas contenir d\'espaces<br>';
        $('#menuSlug').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le slug ne doit pas contenir d\'espaces</div>');
    }
    
    // Valider le format du slug
    if (slug && !/^[a-z0-9-]+$/.test(slug)) {
        isValid = false;
        errorMessage += '- Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets<br>';
        $('#menuSlug').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets</div>');
    }
    
    if (!isValid) {
        showAlert('danger', errorMessage);
        
        // Scroll vers le premier champ invalide
        const firstInvalid = $('.is-invalid').first();
        if (firstInvalid.length) {
            $('html, body').animate({
                scrollTop: firstInvalid.offset().top - 100
            }, 500);
            firstInvalid.focus();
        }
    }
    
    return isValid;
};

 // Helper function to close modal properly
    const closeModalProperly = (modalId) => {
        $(modalId).modal('hide');
        setTimeout(() => {
            cleanupModalBackdrop();
        }, 300);
    };

    // Cleanup modal backdrop
    const cleanupModalBackdrop = () => {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    };

// Fonction pour générer le slug automatiquement
const generateSlug = (text) => {
    return text.toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '') // Supprimer les caractères spéciaux
        .replace(/[\s_-]+/g, '-') // Remplacer espaces et underscores par tirets
        .replace(/^-+|-+$/g, ''); // Supprimer les tirets en début et fin
};

// Auto-générer le slug à partir du titre
$('#menuTitle').on('input', function() {
    const title = $(this).val().trim();
    const slugInput = $('#menuSlug');
    
    if (title && (!slugInput.val() || slugInput.data('auto-generated'))) {
        const slug = generateSlug(title);
        slugInput.val(slug);
        slugInput.data('auto-generated', true);
    }
});

// Auto-générer le slug à partir du titre edit form
$('#editMenuTitle').on('input', function() {
    const title = $(this).val().trim();
    const slugInput = $('#editMenuSlug');
    
    if (title && (!slugInput.val() || slugInput.data('auto-generated'))) {
        const slug = generateSlug(title);
        slugInput.val(slug);
        slugInput.data('auto-generated', true);
    }
});

// Réinitialiser l'auto-génération quand l'utilisateur modifie le slug manuellement
$('#editMenuSlug').on('input', function() {
    $(this).data('auto-generated', false);
});

// Fonction pour réinitialiser les erreurs
const resetFormErrors = (formId) => {
    $(`#${formId} .is-invalid`).removeClass('is-invalid');
    $(`#${formId} .invalid-feedback`).remove();
};

// Fonction pour valider un champ spécifique
const validateField = (fieldId, rules) => {
    const field = $(`#${fieldId}`);
    const value = field.val().trim();
    let isValid = true;
    let message = '';
    
    field.removeClass('is-invalid');
    field.next('.invalid-feedback').remove();
    
    if (rules.required && !value) {
        isValid = false;
        message = rules.requiredMessage || 'Ce champ est requis';
    }
    
    if (rules.minLength && value.length < rules.minLength) {
        isValid = false;
        message = rules.minLengthMessage || `Minimum ${rules.minLength} caractères`;
    }
    
    if (rules.maxLength && value.length > rules.maxLength) {
        isValid = false;
        message = rules.maxLengthMessage || `Maximum ${rules.maxLength} caractères`;
    }
    
    if (rules.pattern && !rules.pattern.test(value)) {
        isValid = false;
        message = rules.patternMessage || 'Format invalide';
    }
    
    if (!isValid) {
        field.addClass('is-invalid');
        field.after(`<div class="invalid-feedback">${message}</div>`);
    }
    
    return isValid;
};

// Validation en temps réel
$('#menuTitle, #menuSlug, #menuType').on('blur', function() {
    const fieldId = $(this).attr('id');
    const rules = {};
    
    switch (fieldId) {
        case 'menuTitle':
            rules.required = true;
            rules.minLength = 2;
            rules.maxLength = 255;
            break;
        case 'menuSlug':
            rules.required = true;
            rules.pattern = /^[a-z0-9-]+$/;
            rules.patternMessage = 'Seulement lettres minuscules, chiffres et tirets';
            break;
        case 'menuType':
            rules.required = true;
            break;
    }
    
    validateField(fieldId, rules);
});



       // Fonction updateMenu corrigée
const updateMenu = () => {
    const form = document.getElementById('editMenuForm');
    const submitBtn = document.getElementById('updateMenuBtn');
    const menuId = document.getElementById('editMenuId').value;
    
    // Validation personnalisée
    if (!validateEditMenuForm()) {
        return;
    }
    
    // Désactiver le bouton et montrer le loader
    $(submitBtn).prop('disabled', true).html(
        '<div class="spinner-border spinner-border-sm me-2"></div>Enregistrement...'
    );
    
    // Créer l'objet data directement (éviter FormData pour éviter l'erreur)
    const data = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        _method: 'PUT', // Pour la méthode PUT avec Laravel
        title: $('#editMenuTitle').val().trim(),
        slug: $('#editMenuSlug').val().trim(),
        type: $('#editMenuType').val(),
        parent_id: $('#editMenuParent').val() || null,
        order: $('#editMenuOrder').val() || 0,
        url: $('#editMenuUrl').val() || '',
        route: $('#editMenuRoute').val() || '',
        icon: $('#editMenuIcon').val() || '',
        is_active: $('#editMenuStatus').val() || 1
    };
    
    // Ajouter reference_id si nécessaire
    const menuType = $('#editMenuType').val();
    if (menuType !== 'custom') {
        data.reference_id = $('#editMenuReference').val() || null;
    }
    
    console.log('Update data:', data); // Pour débogage
    
    // Envoyer la requête AJAX
    $.ajax({
        url: `/menus/${menuId}`,
        type: 'POST', // Laravel nécessite POST pour PUT avec _method
        data: data,
        dataType: 'json',
        success: function(response) {
            console.log('Update success:', response);
            
            if (response.success) {
                // Fermer le modal
                
                    closeModalProperly('#editMenuModal');
                
                // Recharger les données
                loadMenus(currentPage, currentFilters);
                loadStatistics();
                
                // Afficher le message de succès
                showAlert('success', 'Menu mis à jour avec succès !');
                
                // Réinitialiser le formulaire
                form.reset();
            } else {
                showAlert('danger', response.message || 'Erreur lors de la mise à jour');
            }
        },
        error: function(xhr, status, error) {
            console.error('Update error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
            
            let errorMessage = 'Erreur lors de la mise à jour';
            
            if (xhr.status === 422) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                    for (const field in errors) {
                        errorMessage += `- ${errors[field].join('<br>')}<br>`;
                    }
                    
                    // Marquer les champs invalides
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    
                    for (const field in errors) {
                        $(`[name="${field}"]`).addClass('is-invalid')
                            .after(`<div class="invalid-feedback">${errors[field].join('<br>')}</div>`);
                    }
                }
            } else if (xhr.status === 404) {
                errorMessage = 'Menu non trouvé. Veuillez rafraîchir la page.';
            } else if (xhr.status === 419) {
                errorMessage = 'Session expirée. Veuillez rafraîchir la page.';
            }
            
            showAlert('danger', errorMessage);
        },
        complete: function() {
            // Réactiver le bouton
            $(submitBtn).prop('disabled', false).html(
                '<i class="fas fa-save me-2"></i>Enregistrer les modifications'
            );
        }
    });
};

// Fonction validateEditMenuForm
const validateEditMenuForm = () => {
    let isValid = true;
    let errorMessage = '';
    
    // Réinitialiser les erreurs
    $('#editMenuForm .is-invalid').removeClass('is-invalid');
    $('#editMenuForm .invalid-feedback').remove();
    
    // Titre
    const title = $('#editMenuTitle').val().trim();
    if (!title) {
        isValid = false;
        errorMessage += '- Le titre est requis<br>';
        $('#editMenuTitle').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le titre est requis</div>');
    }
    
    // Slug
    const slug = $('#editMenuSlug').val().trim();
    if (!slug) {
        isValid = false;
        errorMessage += '- Le slug est requis<br>';
        $('#editMenuSlug').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le slug est requis</div>');
    }
    
    // Type
    const type = $('#editMenuType').val();
    if (!type) {
        isValid = false;
        errorMessage += '- Le type de menu est requis<br>';
        $('#editMenuType').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le type de menu est requis</div>');
    }
    
    // Référence (seulement si type n'est pas custom)
    if (type !== 'custom') {
        const referenceId = $('#editMenuReference').val();
        if (!referenceId) {
            isValid = false;
            const typeLabel = type === 'category' ? 'catégorie' : 'activité';
            errorMessage += `- Veuillez sélectionner une ${typeLabel}<br>`;
            $('#editMenuReference').addClass('is-invalid')
                .after(`<div class="invalid-feedback">Veuillez sélectionner une ${typeLabel}</div>`);
        }
    }
    
    // Valider le format du slug
    if (slug && !/^[a-z0-9-]+$/.test(slug)) {
        isValid = false;
        errorMessage += '- Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets<br>';
        $('#editMenuSlug').addClass('is-invalid')
            .after('<div class="invalid-feedback">Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets</div>');
    }
    
    if (!isValid) {
        showAlert('danger', errorMessage);
        
        // Scroll vers le premier champ invalide
        const firstInvalid = $('#editMenuForm .is-invalid').first();
        if (firstInvalid.length) {
            $('html, body').animate({
                scrollTop: firstInvalid.offset().top - 100
            }, 500);
            firstInvalid.focus();
        }
    }
    
    return isValid;
};

        // Open edit modal
        const openEditModal = (menuId) => {
            const menu = allMenus.find(m => m.id === menuId);
            
            if (menu) {
                document.getElementById('editMenuId').value = menu.id;
                document.getElementById('editMenuTitle').value = menu.title;
                document.getElementById('editMenuSlug').value = menu.slug;
                document.getElementById('editMenuType').value = menu.type;
                document.getElementById('editMenuOrder').value = menu.order;
                document.getElementById('editMenuParent').value = menu.parent_id || '';
                document.getElementById('editMenuReference').value = menu.reference_id || '';
                document.getElementById('editMenuUrl').value = menu.url || '';
                // document.getElementById('editMenuRoute').value = menu.route || '';
                // document.getElementById('editMenuIcon').value = menu.icon || '';
                document.getElementById('editMenuStatus').value = menu.is_active ? '1' : '0';
                
                // Load parent options
                loadEditParentOptions(menu.parent_id);
                
                // Load reference options based on type
                loadEditReferenceOptions(menu.type);
                
                new bootstrap.Modal(document.getElementById('editMenuModal')).show();
            }
        };

        // Load edit parent options
        const loadEditParentOptions = (selectedId) => {
            $.ajax({
                url: '{{ route("destinations.menus.all-parents") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const select = document.getElementById('editMenuParent');
                        let options = '<option value="">Aucun (menu principal)</option>';
                        
                        response.data.forEach(parent => {
                            const selected = parent.id == selectedId ? 'selected' : '';
                            options += `<option value="${parent.id}" ${selected}>${parent.title}</option>`;
                        });
                        
                        select.innerHTML = options;
                    }
                }
            });
        };

        // Move menu order
        const moveMenu = (menuId, direction) => {
            $.ajax({
                url: `/menus/${menuId}/move/${direction}`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadMenus(currentPage, currentFilters);
                        showAlert('success', 'Ordre mis à jour');
                    }
                }
            });
        };

        // Show delete confirmation
        const showDeleteConfirmation = (menuId) => {
            const menu = allMenus.find(m => m.id === menuId);
            
            if (!menu) {
                showAlert('danger', 'Menu non trouvé');
                return;
            }
            
            menuToDelete = menu;
            
            document.getElementById('menuToDeleteInfo').innerHTML = `
                <div class="menu-info">
                    <div class="menu-info-icon">
                        <i class="${menu.icon || 'fas fa-link'} fa-2x"></i>
                    </div>
                    <div>
                        <div class="menu-info-title">${menu.title}</div>
                        <div class="menu-info-details">
                            <span class="badge ${menu.type}">${getTypeLabel(menu.type)}</span>
                            <span class="badge level">Niveau: ${menu.parent_id ? (menu.parent?.parent_id ? '2' : '1') : '0'}</span>
                        </div>
                    </div>
                </div>
            `;
            
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                </span>
            `;
            deleteBtn.disabled = false;
            
            new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
        };

        // Delete menu
        const deleteMenu = () => {
            if (!menuToDelete) return;
            
            const menuId = menuToDelete.id;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            
            deleteBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Suppression...</span>
                </div>
                Suppression...
            `;
            deleteBtn.disabled = true;
            
            $.ajax({
                url: `/menus/${menuId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        allMenus = allMenus.filter(m => m.id !== menuId);
                        loadStatistics();
                        showAlert('success', response.message || 'Menu supprimé avec succès !');
                        
                        setTimeout(() => {
                            loadMenus(currentPage, currentFilters);
                        }, 500);
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Menu non trouvé');
                        loadMenus(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression');
                    }
                },
                complete: function() {
                    menuToDelete = null;
                }
            });
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            if (!response || !response.current_page) {
                paginationContainer.style.display = 'none';
                return;
            }
            
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} menus`;
            
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
            loadMenus(page, currentFilters);
        };

        // Show loading
        const showLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'flex';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
        };

        // Hide loading
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
            // Search input
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        loadMenus(1, currentFilters);
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
                        type: document.getElementById('filterType').value,
                        parent: document.getElementById('filterParent').value,
                        status: document.getElementById('filterStatus').value,
                        sort_by: document.getElementById('filterSortBy').value,
                        sort_direction: 'asc'
                    };
                    loadMenus(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterType').value = '';
                    document.getElementById('filterParent').value = '';
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterSortBy').value = 'order';
                    currentFilters = {};
                    loadMenus(1);
                });
            }
            
            // Submit menu form
            const submitMenuBtn = document.getElementById('submitMenuBtn');
            if (submitMenuBtn) {
                submitMenuBtn.addEventListener('click', storeMenu);
            }
            
            // Update menu form
            const updateMenuBtn = document.getElementById('updateMenuBtn');
            if (updateMenuBtn) {
                updateMenuBtn.addEventListener('click', updateMenu);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteMenu);
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createMenuModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createMenuForm').reset();
                    document.getElementById('parentSelectionSection').style.display = 'none';
                    document.getElementById('contentSelectionSection').style.display = 'none';
                    document.getElementById('urlConfigurationSection').style.display = 'block';
                    
                    // Reset level selector
                    document.querySelectorAll('.level-option').forEach(opt => opt.classList.remove('active'));
                    document.querySelector('.level-option[data-level="0"]').classList.add('active');
                    document.getElementById('selectedLevel').value = '0';
                });
            }
        };
    </script>

    <script>
        // Tooltip initialization code
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    function initTooltips() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover focus',
                placement: 'top',
                delay: { show: 300, hide: 100 },
                container: 'body',
                boundary: 'window'
            });
        });
        return tooltipList;
    }

    // Initialize tooltips on page load
    let tooltipInstances = initTooltips();

    // Reinitialize tooltips when modals are shown
    const createMenuModal = document.getElementById('createMenuModal');
    const editMenuModal = document.getElementById('editMenuModal');
    const deleteModal = document.getElementById('deleteConfirmationModal');

    if (createMenuModal) {
        createMenuModal.addEventListener('shown.bs.modal', function() {
            // Hide existing tooltips
            tooltipInstances.forEach(tooltip => tooltip.hide());
            // Reinitialize tooltips inside this modal
            const modalTooltips = this.querySelectorAll('[data-bs-toggle="tooltip"]');
            modalTooltips.forEach(el => {
                new bootstrap.Tooltip(el, {
                    trigger: 'hover focus',
                    placement: 'top',
                    delay: { show: 300, hide: 100 }
                });
            });
        });
    }

    if (editMenuModal) {
        editMenuModal.addEventListener('shown.bs.modal', function() {
            tooltipInstances.forEach(tooltip => tooltip.hide());
            const modalTooltips = this.querySelectorAll('[data-bs-toggle="tooltip"]');
            modalTooltips.forEach(el => {
                new bootstrap.Tooltip(el, {
                    trigger: 'hover focus',
                    placement: 'top',
                    delay: { show: 300, hide: 100 }
                });
            });
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('shown.bs.modal', function() {
            tooltipInstances.forEach(tooltip => tooltip.hide());
            const modalTooltips = this.querySelectorAll('[data-bs-toggle="tooltip"]');
            modalTooltips.forEach(el => {
                new bootstrap.Tooltip(el, {
                    trigger: 'hover focus',
                    placement: 'top',
                    delay: { show: 300, hide: 100 }
                });
            });
        });
    }

    // Hide tooltips when modals are hidden
    const modals = [createMenuModal, editMenuModal, deleteModal];
    modals.forEach(modal => {
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                tooltipInstances.forEach(tooltip => tooltip.hide());
            });
        }
    });

    // Function to initialize tooltips for dynamically added elements
    window.initTooltipsForElement = function(element) {
        const tooltips = element.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => {
            new bootstrap.Tooltip(el, {
                trigger: 'hover focus',
                placement: 'top',
                delay: { show: 300, hide: 100 }
            });
        });
    };
});
    </script>

    <style>
        /* Styles spécifiques pour la page menus */
        .menu-tree-container {
            margin-bottom: 20px;
        }

        .menu-tree {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #eaeaea;
        }

        .tree-item {
            margin-bottom: 5px;
        }

        .tree-item-header {
            display: flex;
            align-items: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            transition: all 0.3s ease;
        }

        .tree-item-header:hover {
            background: #f0f7ff;
            border-color: #cfe2ff;
        }

        .tree-item-toggle {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 10px;
            color: #6c757d;
        }

        .tree-item-toggle.has-children:hover {
            color: #007bff;
        }

        .tree-item-content {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tree-item-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 6px;
            color: #495057;
        }

        .tree-item-info {
            flex: 1;
        }

        .tree-item-title {
            font-weight: 600;
            color: #333;
        }

        .tree-item-details {
            display: flex;
            gap: 5px;
            margin-top: 3px;
        }

        .tree-item-actions {
            display: flex;
            gap: 5px;
        }

        .tree-item-children {
            margin-left: 34px;
            padding-left: 10px;
            border-left: 2px dashed #dee2e6;
        }

        .level-1 .tree-item-header {
            background: #f8f9fa;
        }

        .level-2 .tree-item-header {
            background: #e9ecef;
        }

        /* Menu cells */
        .menu-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-icons {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .menu-icons.default-icon {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .menu-name-text {
            font-weight: 600;
            color: #333;
        }

        /* Badges */
        .menu-type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .menu-type-badge.custom {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .menu-type-badge.category {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
        }

        .menu-type-badge.activity {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #333;
        }

        .level-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .level-badge.level-0 {
            background: #e3f2fd;
            color: #1565c0;
        }

        .level-badge.level-1 {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .level-badge.level-2 {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .order-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        /* Menu actions */
        .menu-actions-modern {
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

        .move-up-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .move-up-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
        }

        .move-down-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .move-down-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: #333;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #ff9e1a);
            transform: translateY(-2px);
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
        }

        /* Level selector */
        .level-selector {
            display: flex;
            gap: 10px;
        }

        .level-option {
            flex: 1;
            padding: 15px;
            border: 2px solid #eaeaea;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .level-option:hover {
            border-color: #cfe2ff;
            background: #f0f7ff;
        }

        .level-option.active {
            border-color: #007bff;
            background: #e7f1ff;
        }

        .level-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 8px;
            color: #495057;
        }

        .level-option.active .level-icon {
            background: #007bff;
            color: white;
        }

        .level-info {
            flex: 1;
        }

        .level-title {
            font-weight: 600;
            color: #333;
        }

        .level-description {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .level-check {
            color: #28a745;
            opacity: 0;
        }

        .level-option.active .level-check {
            opacity: 1;
        }

        /* Form sections */
        .form-section-modern {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eaeaea;
        }

        .form-section-modern:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title-modern {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Menu info for delete modal */
        .menu-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .menu-info-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .menu-info-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: #333;
        }

        .menu-info-details {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .level-selector {
                flex-direction: column;
            }
            
            .menu-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .tree-item-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .tree-item-content {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .tree-item-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        @media (max-width: 576px) {
            .menu-name-cell {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .tree-item-children {
                margin-left: 20px;
            }
        }

        /* Image upload styles */
.image-upload-container {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s ease;
}

.upload-area {
    cursor: pointer;
}

.upload-area:hover {
    background-color: #f8f9fa;
}

.upload-area.dragover {
    background-color: #e7f1ff;
    border-color: #007bff;
}

.upload-placeholder {
    text-align: center;
    color: #6c757d;
}

.image-preview, .current-image-preview {
    text-align: center;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background: #f8f9fa;
}

.image-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.new-image-upload {
    margin-top: 20px;
}

/* Responsive adjustments for image upload */
@media (max-width: 768px) {
    .image-upload-container {
        padding: 15px;
    }
    
    .upload-placeholder h5 {
        font-size: 1rem;
    }
    
    .image-actions {
        flex-direction: column;
    }
    
    .image-actions .btn {
        width: 100%;
    }
}

/* Tooltip Styles */
.fa-question-circle {
    color: #6c757d;
    font-size: 0.85em;
    cursor: help;
    transition: color 0.2s ease;
    margin-left: 4px;
}

.fa-question-circle:hover {
    color: #0d6efd;
}

/* Tooltip customization */
.tooltip {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 0.875rem;
    z-index: 9999;
}

.tooltip-inner {
    max-width: 300px;
    padding: 10px 14px;
    text-align: left;
    background-color: #1f2937;
    color: #f9fafb;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    line-height: 1.5;
    font-weight: 400;
}

.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: #1f2937;
}

.bs-tooltip-bottom .tooltip-arrow::before {
    border-bottom-color: #1f2937;
}

.bs-tooltip-start .tooltip-arrow::before {
    border-left-color: #1f2937;
}

.bs-tooltip-end .tooltip-arrow::before {
    border-right-color: #1f2937;
}

/* Tooltip for form labels */
.form-label-modern .fa-question-circle {
    vertical-align: middle;
    margin-top: -2px;
}

/* Tooltip for buttons */
.btn .fa-question-circle {
    font-size: 0.8em;
    margin-left: 6px;
    vertical-align: baseline;
}

/* Tooltip in alerts */
.alert .fa-question-circle {
    vertical-align: middle;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tooltip-inner {
        max-width: 250px;
        font-size: 0.8125rem;
        padding: 8px 12px;
    }
    
    .fa-question-circle {
        font-size: 0.8em;
    }
}
    </style>
@endsection