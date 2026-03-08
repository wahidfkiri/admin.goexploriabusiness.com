@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-box"></i></span>
                Gestion des Produits & Services
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <div class="btn-group me-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" id="exportPdf"><i class="fas fa-file-pdf me-2 text-danger"></i>PDF</a></li>
                        <li><a class="dropdown-item" href="#" id="exportExcel"><i class="fas fa-file-excel me-2 text-success"></i>Excel</a></li>
                        <li><a class="dropdown-item" href="#" id="exportCsv"><i class="fas fa-file-csv me-2 text-info"></i>CSV</a></li>
                    </ul>
                </div>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Produit/Service
                </a>
            </div>
        </div>
        
        <!-- Filter Section (Initially Hidden) -->
        <div class="filter-section-modern" id="filterSection" style="display: none;">
            <div class="filter-header-modern">
                <h3 class="filter-title-modern">Filtres avancés</h3>
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
                    <label class="form-label-modern">Type</label>
                    <select class="form-select-modern" id="filterType">
                        <option value="">Tous les types</option>
                        <option value="produit_physique">Produit physique</option>
                        <option value="produit_numerique">Produit numérique</option>
                        <option value="service">Service</option>
                        <option value="prestation">Prestation</option>
                        <option value="forfait">Forfait</option>
                        <option value="abonnement">Abonnement</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Famille</label>
                    <select class="form-select-modern" id="filterFamily">
                        <option value="">Toutes les familles</option>
                        @foreach($families ?? [] as $family)
                            <option value="{{ $family->id }}">{{ $family->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Catégorie</label>
                    <select class="form-select-modern" id="filterCategory">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Disponibilité</label>
                    <select class="form-select-modern" id="filterAvailable">
                        <option value="">Tous</option>
                        <option value="1">Disponible</option>
                        <option value="0">Non disponible</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Prix min</label>
                    <input type="number" class="form-control-modern" id="filterPriceMin" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Prix max</label>
                    <input type="number" class="form-control-modern" id="filterPriceMax" placeholder="1000">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Stock</label>
                    <select class="form-select-modern" id="filterStock">
                        <option value="">Tous</option>
                        <option value="in_stock">En stock</option>
                        <option value="out_of_stock">Rupture</option>
                        <option value="low_stock">Stock faible</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="created_at">Date de création</option>
                        <option value="name">Nom</option>
                        <option value="price_ttc">Prix (croissant)</option>
                        <option value="price_ttc_desc">Prix (décroissant)</option>
                        <option value="sales_count">Popularité</option>
                        <option value="updated_at">Dernière modification</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalProducts">0</div>
                        <div class="stats-label-modern">Total Produits/Services</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalProductsPhysique">0</div>
                        <div class="stats-label-modern">Produits physiques</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                        <i class="fas fa-cube"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalServices">0</div>
                        <div class="stats-label-modern">Services</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-cogs"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalSubscriptions">0</div>
                        <div class="stats-label-modern">Abonnements</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalValue">0 €</div>
                        <div class="stats-label-modern">Valeur totale</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Produits & Services</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un produit/service..." id="searchInput">
                </div>
            </div>
            
            <div class="card-body-modern">
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
                                <th>Produit/Service</th>
                                <th>Type</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Statut</th>
                                <th>Ventes</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <!-- Products will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun produit/service trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier produit ou service.</p>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Créer un produit/service
                    </a>
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
        
        <!-- Quick Stats Cards -->
        <div class="quick-stats-grid" id="quickStats">
            <!-- Will be populated by AJAX -->
        </div>
        
        <!-- Floating Action Button -->
        <a href="{{ route('products.create') }}" class="fab-modern">
            <i class="fas fa-plus"></i>
        </a>
    </main>
    
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce produit/service ? Cette action est irréversible.</p>
                    
                    <div class="product-to-delete" id="productToDeleteInfo">
                        <!-- Product info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> La suppression affectera les factures et devis associés.
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

    <!-- VIEW MODAL -->
    <div class="modal fade" id="productViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails du produit/service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="productViewContent">
                    <!-- Product details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allProducts = [];
        let productToDelete = null;
        let productViewModal = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadProducts();
            loadStatistics();
            setupEventListeners();
            
            // Initialize modals
            productViewModal = new bootstrap.Modal(document.getElementById('productViewModal'));
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

        // Load products
        const loadProducts = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("products.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allProducts = response.data || [];
                        renderProducts(allProducts);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des produits');
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
                url: '{{ route("products.statistics") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalProducts').textContent = stats.total || 0;
                        document.getElementById('totalProductsPhysique').textContent = stats.physical || 0;
                        document.getElementById('totalServices').textContent = stats.services || 0;
                        document.getElementById('totalSubscriptions').textContent = stats.subscriptions || 0;
                        document.getElementById('totalValue').textContent = formatCurrency(stats.total_value || 0);
                        
                        // Update advanced stats
                        updateAdvancedStats(stats);
                    } else {
                        console.error('Error loading statistics:', response.message);
                        
                        // Default values
                        document.getElementById('totalProducts').textContent = '0';
                        document.getElementById('totalProductsPhysique').textContent = '0';
                        document.getElementById('totalServices').textContent = '0';
                        document.getElementById('totalSubscriptions').textContent = '0';
                        document.getElementById('totalValue').textContent = '0 €';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Statistics AJAX error:', xhr.responseText, status, error);
                    
                    // Default values
                    document.getElementById('totalProducts').textContent = '0';
                    document.getElementById('totalProductsPhysique').textContent = '0';
                    document.getElementById('totalServices').textContent = '0';
                    document.getElementById('totalSubscriptions').textContent = '0';
                    document.getElementById('totalValue').textContent = '0 €';
                }
            });
        };

        // Render products with modern design
        const renderProducts = (products) => {
            const tbody = document.getElementById('productsTableBody');
            tbody.innerHTML = '';
            
            if (!products || !Array.isArray(products) || products.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            products.forEach((product, index) => {
                const row = document.createElement('tr');
                row.id = `product-row-${product.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                const price = product.price_ttc || 0;
                const formattedPrice = formatCurrency(price);
                const stockStatus = getStockStatus(product);
                
                row.innerHTML = `
                    <td>
                        <div class="product-name-cell">
                            <div class="product-name-modern">
                                <div class="product-icon-modern" style="background: ${getProductColor(product.name)}">
                                    <i class="fas ${getProductIcon(product.main_type)}"></i>
                                </div>
                                <div>
                                    <div class="product-name-text" onclick="viewProduct(${product.id})" style="cursor: pointer;">
                                        ${product.name.length > 30 ? product.name.substring(0, 30) + '...' : product.name}
                                        <small class="text-muted ms-2">#${product.reference}</small>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-tag me-1"></i>${product.sku || product.reference}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        ${getTypeBadge(product.main_type)}
                        ${product.billing_period ? `<small class="d-block text-muted mt-1">${getBillingPeriodText(product.billing_period)}</small>` : ''}
                    </td>
                    <td>
                        <div class="category-info">
                            <div class="category-name">${product.category_name || 'Non catégorisé'}</div>
                            ${product.family_name ? `<small class="text-muted">${product.family_name}</small>` : ''}
                        </div>
                    </td>
                    <td>
                        <div class="price-info">
                            <div class="current-price ${product.has_sale ? 'text-danger' : ''}">
                                ${formattedPrice}
                                ${product.price_ht ? `<small class="text-muted d-block">HT: ${formatCurrency(product.price_ht)}</small>` : ''}
                            </div>
                            ${product.has_sale ? `
                                <small class="text-muted text-decoration-line-through">
                                    ${formatCurrency(product.price_regular)}
                                </small>
                            ` : ''}
                        </div>
                    </td>
                    <td>
                        ${stockStatus.badge}
                        ${product.main_type === 'produit_physique' ? `
                            <small class="d-block text-muted mt-1">
                                Stock: ${product.current_stock || 0}
                                ${product.minimum_stock ? ` / Min: ${product.minimum_stock}` : ''}
                            </small>
                        ` : product.main_type === 'service' ? `
                            <small class="d-block text-muted mt-1">
                                <i class="far fa-clock me-1"></i>${product.estimated_duration_minutes || 0} min
                            </small>
                        ` : ''}
                    </td>
                    <td>
                        ${getStatusBadge(product.is_available_for_sale, product.is_public)}
                    </td>
                    <td>
                        <div class="sales-info">
                            <div class="sales-count">${product.sales_count || 0}</div>
                            <small class="text-muted">${product.views_count || 0} vues</small>
                        </div>
                    </td>
                    <td>
                        <div class="product-actions-modern">
                            <button class="action-btn-modern view-btn-modern" title="Voir détails" 
                                    onclick="viewProduct(${product.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="/ecommerce/products/${product.id}/edit"
                               class="action-btn-modern edit-btn-modern" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-btn-modern duplicate-btn-modern" title="Dupliquer" 
                                    onclick="duplicateProduct(${product.id})">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteConfirmation(${product.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
            document.getElementById('paginationContainer').style.display = 'flex';
        };

        // Helper functions
        const getInitials = (name) => {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        };

        const getProductColor = (productName) => {
            let hash = 0;
            for (let i = 0; i < productName.length; i++) {
                hash = productName.charCodeAt(i) + ((hash << 5) - hash);
            }
            
            const colors = [
                '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
                '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
                '#34495e', '#f1c40f', '#2ecc71', '#e67e22'
            ];
            
            return colors[Math.abs(hash) % colors.length];
        };

        const getProductIcon = (type) => {
            const icons = {
                'produit_physique': 'fa-cube',
                'produit_numerique': 'fa-file-download',
                'service': 'fa-cogs',
                'prestation': 'fa-briefcase',
                'forfait': 'fa-gift',
                'abonnement': 'fa-sync-alt',
                'licence': 'fa-certificate',
                'hebergement': 'fa-server',
                'maintenance': 'fa-wrench',
                'formation': 'fa-chalkboard-teacher'
            };
            return icons[type] || 'fa-box';
        };

        const getTypeBadge = (type) => {
            const badges = {
                'produit_physique': '<span class="badge bg-info"><i class="fas fa-cube me-1"></i>Produit physique</span>',
                'produit_numerique': '<span class="badge bg-primary"><i class="fas fa-file-download me-1"></i>Produit numérique</span>',
                'service': '<span class="badge bg-success"><i class="fas fa-cogs me-1"></i>Service</span>',
                'prestation': '<span class="badge bg-warning"><i class="fas fa-briefcase me-1"></i>Prestation</span>',
                'forfait': '<span class="badge bg-danger"><i class="fas fa-gift me-1"></i>Forfait</span>',
                'abonnement': '<span class="badge bg-secondary"><i class="fas fa-sync-alt me-1"></i>Abonnement</span>',
                'licence': '<span class="badge bg-dark"><i class="fas fa-certificate me-1"></i>Licence</span>',
                'hebergement': '<span class="badge bg-purple"><i class="fas fa-server me-1"></i>Hébergement</span>',
                'maintenance': '<span class="badge bg-orange"><i class="fas fa-wrench me-1"></i>Maintenance</span>',
                'formation': '<span class="badge bg-teal"><i class="fas fa-chalkboard-teacher me-1"></i>Formation</span>'
            };
            return badges[type] || '<span class="badge bg-secondary">Autre</span>';
        };

        const getBillingPeriodText = (period) => {
            const periods = {
                'mensuel': '/mois',
                'trimestriel': '/trimestre',
                'semestriel': '/semestre',
                'annuel': '/an'
            };
            return periods[period] || '';
        };

        const getStockStatus = (product) => {
            if (product.main_type !== 'produit_physique') {
                return {
                    badge: '<span class="badge bg-info">N/A</span>'
                };
            }
            
            if (product.stock_management === 'non') {
                return {
                    badge: '<span class="badge bg-success">Illimité</span>'
                };
            }
            
            if (product.stock_management === 'sur_commande') {
                return {
                    badge: '<span class="badge bg-warning">Sur commande</span>'
                };
            }
            
            const stock = product.current_stock || 0;
            const minStock = product.minimum_stock || 0;
            
            if (stock <= 0) {
                return {
                    badge: '<span class="badge bg-danger">Rupture</span>'
                };
            }
            
            if (stock <= minStock) {
                return {
                    badge: '<span class="badge bg-warning">Stock faible</span>'
                };
            }
            
            return {
                badge: '<span class="badge bg-success">En stock</span>'
            };
        };

        const getStatusBadge = (isAvailable, isPublic) => {
            if (!isAvailable) {
                return '<span class="badge bg-danger">Non disponible</span>';
            }
            if (!isPublic) {
                return '<span class="badge bg-warning">Masqué</span>';
            }
            return '<span class="badge bg-success">Actif</span>';
        };

        // View product details
        const viewProduct = (productId) => {
            const product = allProducts.find(p => p.id === productId);
            
            if (!product) {
                showAlert('danger', 'Produit non trouvé');
                return;
            }
            
            const modalContent = document.getElementById('productViewContent');
            modalContent.innerHTML = `
                <div class="product-view">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="product-image-large">
                                ${product.main_image ? 
                                    `<img src="${product.main_image}" alt="${product.name}" class="img-fluid">` : 
                                    `<div class="no-image"><i class="fas fa-box fa-4x"></i></div>`
                                }
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="product-view-title">${product.name}</h4>
                            <p class="product-view-reference">Réf: ${product.reference} | SKU: ${product.sku || 'N/A'}</p>
                            
                            <div class="product-view-price">
                                <span class="price-current">${formatCurrency(product.price_ttc)}</span>
                                ${product.price_ht ? `<span class="price-ht">HT: ${formatCurrency(product.price_ht)}</span>` : ''}
                                <span class="price-tax">TVA: ${product.tax_rate || 0}%</span>
                            </div>
                            
                            <div class="product-view-description">
                                ${product.short_description || product.long_description || 'Aucune description'}
                            </div>
                            
                            <div class="product-view-details">
                                <div class="detail-item">
                                    <span class="detail-label">Type:</span>
                                    <span class="detail-value">${getTypeText(product.main_type)}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Catégorie:</span>
                                    <span class="detail-value">${product.category_name || 'Non catégorisé'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Famille:</span>
                                    <span class="detail-value">${product.family_name || 'N/A'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Unité facturation:</span>
                                    <span class="detail-value">${getUnitText(product.billing_unit)}</span>
                                </div>
                                ${product.main_type === 'abonnement' ? `
                                    <div class="detail-item">
                                        <span class="detail-label">Période:</span>
                                        <span class="detail-value">${getBillingPeriodText(product.billing_period)}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Engagement:</span>
                                        <span class="detail-value">${product.has_commitment ? product.commitment_months + ' mois' : 'Sans engagement'}</span>
                                    </div>
                                ` : ''}
                                ${product.main_type === 'service' ? `
                                    <div class="detail-item">
                                        <span class="detail-label">Durée estimée:</span>
                                        <span class="detail-value">${product.estimated_duration_minutes || 0} minutes</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Rendez-vous:</span>
                                        <span class="detail-value">${product.requires_appointment ? 'Oui' : 'Non'}</span>
                                    </div>
                                ` : ''}
                                ${product.main_type === 'produit_physique' ? `
                                    <div class="detail-item">
                                        <span class="detail-label">Stock:</span>
                                        <span class="detail-value">${product.current_stock || 0} unités</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Stock minimum:</span>
                                        <span class="detail-value">${product.minimum_stock || 0}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Emplacement:</span>
                                        <span class="detail-value">${product.stock_location || 'N/A'}</span>
                                    </div>
                                ` : ''}
                            </div>
                            
                            ${product.variants && product.variants.length > 0 ? `
                                <div class="product-view-variants">
                                    <h5>Variantes</h5>
                                    <div class="variants-list">
                                        ${product.variants.map(v => `
                                            <div class="variant-item">
                                                <span class="variant-name">${v.name}</span>
                                                <span class="variant-price">${formatCurrency(v.price_adjustment + product.price_ttc)}</span>
                                                <span class="variant-stock">Stock: ${v.stock || 0}</span>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
            
            productViewModal.show();
        };

        const getTypeText = (type) => {
            const types = {
                'produit_physique': 'Produit physique',
                'produit_numerique': 'Produit numérique',
                'service': 'Service',
                'prestation': 'Prestation',
                'forfait': 'Forfait',
                'abonnement': 'Abonnement',
                'licence': 'Licence',
                'hebergement': 'Hébergement',
                'maintenance': 'Maintenance',
                'formation': 'Formation'
            };
            return types[type] || type;
        };

        const getUnitText = (unit) => {
            const units = {
                'unite': 'À l\'unité',
                'heure': 'À l\'heure',
                'jour': 'Par jour',
                'mois': 'Par mois',
                'an': 'Par an',
                'forfait': 'Forfait',
                'projet': 'Par projet'
            };
            return units[unit] || unit;
        };

        // Duplicate product
        const duplicateProduct = (productId) => {
            if (!confirm('Voulez-vous dupliquer ce produit/service ?')) {
                return;
            }
            
            $.ajax({
                url: `/products/${productId}/duplicate`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Produit dupliqué avec succès !');
                        loadProducts(currentPage, currentFilters);
                        loadStatistics();
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la duplication');
                    }
                },
                error: function(xhr) {
                    showAlert('danger', 'Erreur lors de la duplication');
                    console.error('Error:', xhr.responseText);
                }
            });
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (productId) => {
            const product = allProducts.find(p => p.id === productId);
            
            if (!product) {
                showAlert('danger', 'Produit non trouvé');
                return;
            }
            
            productToDelete = product;
            
            document.getElementById('productToDeleteInfo').innerHTML = `
                <div class="product-info">
                    <div class="product-info-icon" style="background: ${getProductColor(product.name)}">
                        <i class="fas ${getProductIcon(product.main_type)} fa-2x"></i>
                    </div>
                    <div>
                        <div class="product-info-name">${product.name}</div>
                        <div class="product-info-details">
                            <div><strong>Référence:</strong> ${product.reference}</div>
                            <div><strong>Type:</strong> ${getTypeText(product.main_type)}</div>
                            <div><strong>Prix:</strong> ${formatCurrency(product.price_ttc)}</div>
                            <div><strong>Catégorie:</strong> ${product.category_name || 'N/A'}</div>
                        </div>
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

        // Delete product
        const deleteProduct = () => {
            if (!productToDelete) {
                showAlert('danger', 'Aucun produit à supprimer');
                return;
            }
            
            const productId = productToDelete.id;
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
            
            // Add deleting animation to table row
            const row = document.getElementById(`product-row-${productId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/ecommerce/products/${productId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove product from array
                        allProducts = allProducts.filter(p => p.id !== productId);
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Produit supprimé avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('productsTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadProducts(currentPage, currentFilters);
                            }, 500);
                        }
                    } else {
                        if (row) row.classList.remove('deleting-row');
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr, status, error) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    // Remove deleting animation
                    const row = document.getElementById(`product-row-${productId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Produit non trouvé.');
                        loadProducts(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    productToDelete = null;
                }
            });
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} produits/services`;
            
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
            loadProducts(page, currentFilters);
        };

        // Update advanced stats
        const updateAdvancedStats = (stats) => {
            const quickStatsContainer = document.getElementById('quickStats');
            
            let familiesHtml = '';
            if (stats.by_family && stats.by_family.length > 0) {
                stats.by_family.forEach(item => {
                    familiesHtml += `
                        <div class="quick-stat-item">
                            <span class="quick-stat-label">${item.family_name}</span>
                            <span class="quick-stat-value">${item.total}</span>
                        </div>
                    `;
                });
            }
            
            let categoriesHtml = '';
            if (stats.by_category && stats.by_category.length > 0) {
                stats.by_category.slice(0, 5).forEach(item => {
                    categoriesHtml += `
                        <div class="quick-stat-item">
                            <span class="quick-stat-label">${item.category_name}</span>
                            <span class="quick-stat-value">${item.total}</span>
                        </div>
                    `;
                });
            }
            
            let topProductsHtml = '';
            if (stats.top_products && stats.top_products.length > 0) {
                stats.top_products.forEach(item => {
                    topProductsHtml += `
                        <div class="quick-stat-item">
                            <span class="quick-stat-label">${item.name}</span>
                            <span class="quick-stat-value">${item.sales_count} ventes</span>
                        </div>
                    `;
                });
            }
            
            quickStatsContainer.innerHTML = `
                <div class="quick-stat-card">
                    <h4 class="quick-stat-title">
                        <i class="fas fa-layer-group me-2"></i>Répartition par Famille
                    </h4>
                    <div class="quick-stat-content">
                        ${familiesHtml || '<p class="text-muted">Aucune donnée</p>'}
                    </div>
                </div>
                
                <div class="quick-stat-card">
                    <h4 class="quick-stat-title">
                        <i class="fas fa-chart-pie me-2"></i>Top Catégories
                    </h4>
                    <div class="quick-stat-content">
                        ${categoriesHtml || '<p class="text-muted">Aucune donnée</p>'}
                    </div>
                </div>
                
                <div class="quick-stat-card">
                    <h4 class="quick-stat-title">
                        <i class="fas fa-chart-line me-2"></i>Meilleures ventes
                    </h4>
                    <div class="quick-stat-content">
                        ${topProductsHtml || '<p class="text-muted">Aucune donnée</p>'}
                    </div>
                </div>
            `;
        };

        // Format currency
        const formatCurrency = (num) => {
            if (num === null || num === undefined) return '0 €';
            return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(num);
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
                        loadProducts(1, currentFilters);
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
                        family_id: document.getElementById('filterFamily').value,
                        category_id: document.getElementById('filterCategory').value,
                        is_available: document.getElementById('filterAvailable').value,
                        price_min: document.getElementById('filterPriceMin').value,
                        price_max: document.getElementById('filterPriceMax').value,
                        stock_status: document.getElementById('filterStock').value,
                        sort_by: document.getElementById('filterSortBy').value,
                    };
                    loadProducts(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterType').value = '';
                    document.getElementById('filterFamily').value = '';
                    document.getElementById('filterCategory').value = '';
                    document.getElementById('filterAvailable').value = '';
                    document.getElementById('filterPriceMin').value = '';
                    document.getElementById('filterPriceMax').value = '';
                    document.getElementById('filterStock').value = '';
                    document.getElementById('filterSortBy').value = 'created_at';
                    currentFilters = {};
                    loadProducts(1);
                });
            }
            
            // Export buttons
            const exportPdf = document.getElementById('exportPdf');
            if (exportPdf) {
                exportPdf.addEventListener('click', (e) => {
                    e.preventDefault();
                    exportData('pdf');
                });
            }
            
            const exportExcel = document.getElementById('exportExcel');
            if (exportExcel) {
                exportExcel.addEventListener('click', (e) => {
                    e.preventDefault();
                    exportData('excel');
                });
            }
            
            const exportCsv = document.getElementById('exportCsv');
            if (exportCsv) {
                exportCsv.addEventListener('click', (e) => {
                    e.preventDefault();
                    exportData('csv');
                });
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteProduct);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    productToDelete = null;
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                });
            }
        };

        // Export data
        const exportData = (format) => {
            const filters = { ...currentFilters };
            if (document.getElementById('searchInput').value) {
                filters.search = document.getElementById('searchInput').value;
            }
            
            const queryString = Object.keys(filters)
                .filter(key => filters[key])
                .map(key => `${key}=${encodeURIComponent(filters[key])}`)
                .join('&');
            
            window.location.href = `/products/export/${format}?${queryString}`;
        };
    </script>

    <style>
        /* Styles spécifiques pour la page produits */
        .product-name-modern {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .product-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .product-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .product-name-text:hover {
            color: var(--primary-color);
        }

        .category-info {
            display: flex;
            flex-direction: column;
        }

        .category-name {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .price-info {
            display: flex;
            flex-direction: column;
        }

        .current-price {
            font-weight: 600;
            color: #06b48a;
        }

        .current-price.text-danger {
            color: #ef476f !important;
        }

        .sales-info {
            text-align: center;
        }

        .sales-count {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .product-actions-modern {
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
            text-decoration: none;
        }

        .view-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .view-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
            color: white;
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
            color: white;
        }

        .duplicate-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: white;
        }

        .duplicate-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #f39c12);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 209, 102, 0.3);
            color: white;
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 71, 111, 0.3);
            color: white;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .product-info-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .product-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .product-info-details {
            font-size: 0.9rem;
            color: #666;
        }

        .product-info-details div {
            margin-bottom: 2px;
        }

        /* Quick stats grid */
        .quick-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .quick-stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .quick-stat-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .quick-stat-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quick-stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .quick-stat-item:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .quick-stat-label {
            font-weight: 500;
            color: #333;
        }

        .quick-stat-value {
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Product view modal */
        .product-view {
            padding: 10px;
        }

        .product-image-large {
            width: 100%;
            height: 200px;
            background: #f8f9fa;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid #eaeaea;
        }

        .product-image-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            text-align: center;
            color: #ccc;
        }

        .product-view-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .product-view-reference {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .product-view-price {
            background: linear-gradient(135deg, #06b48a, #049a72);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: baseline;
            gap: 15px;
        }

        .price-current {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .price-ht {
            font-size: 1rem;
            opacity: 0.9;
        }

        .price-tax {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-left: auto;
        }

        .product-view-description {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #555;
        }

        .product-view-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .detail-item {
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
            margin-right: 5px;
        }

        .detail-value {
            color: #333;
        }

        .product-view-variants {
            margin-top: 20px;
        }

        .product-view-variants h5 {
            margin-bottom: 10px;
            color: #333;
        }

        .variants-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .variant-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .variant-name {
            font-weight: 500;
            color: #333;
        }

        .variant-price {
            color: #06b48a;
            font-weight: 600;
        }

        .variant-stock {
            font-size: 0.85rem;
            color: #666;
        }

        /* Badge colors */
        .bg-purple {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
        }

        .bg-orange {
            background: linear-gradient(135deg, #e67e22, #d35400);
        }

        .bg-teal {
            background: linear-gradient(135deg, #1abc9c, #16a085);
        }

        /* Animation for deleting row */
        .deleting-row {
            animation: slideOut 0.3s ease forwards;
            opacity: 0.5;
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .quick-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .product-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .product-actions-modern {
                flex-wrap: wrap;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) {
            .quick-stats-grid {
                grid-template-columns: 1fr;
            }
            
            .product-view-price {
                flex-wrap: wrap;
                gap: 5px;
            }
            
            .price-current {
                font-size: 1.4rem;
            }
            
            .product-view-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection