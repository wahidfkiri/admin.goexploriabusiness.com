{{-- resources/views/map-points/index.blade.php --}}
@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-map-marked-alt"></i></span>
                Gestion des Points sur la Carte
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <a href="{{ route('map-points.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Point
                </a>
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
                <div class="col-md-2">
                    <label for="filterCategory" class="form-label-modern">Catégorie</label>
                    <select class="form-select-modern" id="filterCategory">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterVille" class="form-label-modern">Ville</label>
                    <select class="form-select-modern" id="filterVille">
                        <option value="">Toutes les villes</option>
                        @foreach($villes as $ville)
                            <option value="{{ $ville }}">{{ $ville }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterHasVideo" class="form-label-modern">Vidéo</label>
                    <select class="form-select-modern" id="filterHasVideo">
                        <option value="">Tous</option>
                        <option value="yes">Avec vidéo</option>
                        <option value="no">Sans vidéo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterHasDetails" class="form-label-modern">Page détails</label>
                    <select class="form-select-modern" id="filterHasDetails">
                        <option value="">Tous</option>
                        <option value="yes">Avec page</option>
                        <option value="no">Sans page</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="created_at">Date de création</option>
                        <option value="title">Titre</option>
                        <option value="category">Catégorie</option>
                        <option value="views">Vues</option>
                        <option value="ville">Ville</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterSortOrder" class="form-label-modern">Ordre</label>
                    <select class="form-select-modern" id="filterSortOrder">
                        <option value="desc">Descendant</option>
                        <option value="asc">Ascendant</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalPoints">0</div>
                        <div class="stats-label-modern">Total Points</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="withVideoPoints">0</div>
                        <div class="stats-label-modern">Avec Vidéo</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #FF6B6B, #ff5252);">
                        <i class="fas fa-video"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="withDetailsPoints">0</div>
                        <div class="stats-label-modern">Pages Détails</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #4ECDC4, #45b7b0);">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="featuredPoints">0</div>
                        <div class="stats-label-modern">Points à la une</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #FFD166, #ffb851);">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalViews">0</div>
                        <div class="stats-label-modern">Vues totales</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Advanced Stats (will be populated by JS) -->
        <div id="advancedStats"></div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Points d'Intérêt</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un point..." id="searchInput">
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
                                <th>Point</th>
                                <th>Catégorie</th>
                                <th>Localisation</th>
                                <th>Médias</th>
                                <th>Statistiques</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="mapPointsTableBody">
                            <!-- Points will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun point trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier point d'intérêt sur la carte.</p>
                    <a href="{{ route('map-points.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Créer un point
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
        
        <!-- Floating Action Button -->
        <a href="{{ route('map-points.create') }}" class="fab-modern">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce point de la carte ? Toutes les images et vidéos associées seront également supprimées.</p>
                    
                    <div class="point-to-delete" id="pointToDeleteInfo">
                        <!-- Point info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
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

    <!-- VIDEO PREVIEW MODAL -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aperçu Vidéo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="video-container">
                        <iframe id="youtubeIframe" width="100%" height="400" src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allPoints = [];
        let pointToDelete = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadPoints();
            setupEventListeners();
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

        // Load points
        const loadPoints = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("map-points.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allPoints = response.data || [];
                        renderPoints(allPoints);
                        renderPagination(response);
                        updateStats(response.stats);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des points');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showError('Erreur de connexion au serveur');
                    console.error('Error:', xhr.responseText);
                }
            });
        };

        // Render points with modern design
        const renderPoints = (points) => {
            const tbody = document.getElementById('mapPointsTableBody');
            tbody.innerHTML = '';
            
            if (!points || !Array.isArray(points) || points.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            points.forEach((point, index) => {
                const row = document.createElement('tr');
                row.id = `point-row-${point.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                row.innerHTML = `
                    <td>
                        <div class="point-name-cell">
                            <div class="point-name-modern">
                                <div class="point-icon-modern" style="background: ${point.category_color}">
                                    <i class="fas ${point.category_icon}"></i>
                                </div>
                                <div>
                                    <div class="point-name-text">
                                        ${point.title}
                                        ${point.is_featured ? '<i class="fas fa-star text-warning ms-1" title="À la une"></i>' : ''}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>${point.created_at}
                                        ${point.user ? `<span class="ms-2"><i class="fas fa-user me-1"></i>${point.user.name}</span>` : ''}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="category-badge" style="background: ${point.category_color}20; color: ${point.category_color}; border-left: 3px solid ${point.category_color};">
                            <i class="fas ${point.category_icon} me-1"></i>${point.category_label}
                        </span>
                    </td>
                    <td>
                        <div class="location-info">
                            <div><i class="fas fa-map-pin me-1" style="color: ${point.category_color};"></i> ${point.adresse || 'Adresse non spécifiée'}</div>
                            <small class="text-muted">
                                <i class="fas fa-city me-1"></i>${point.ville || 'Ville inconnue'} ${point.code_postal || ''}
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-globe me-1"></i>${point.latitude}, ${point.longitude}
                            </small>
                        </div>
                    </td>
                    <td>
                        <div class="media-info">
                            <div class="mb-1">
                                ${point.main_image ? `
                                    <button class="btn-media btn-image" onclick="previewImage('${point.main_image}')" title="Voir l'image">
                                        <i class="fas fa-image"></i> Image
                                    </button>
                                ` : ''}
                                ${point.has_video ? `
                                    <button class="btn-media btn-video" onclick="playVideo('${point.youtube_id}')" title="Lire la vidéo">
                                        <i class="fas fa-video"></i> Vidéo
                                    </button>
                                ` : ''}
                            </div>
                            <small class="text-muted">
                                ${point.images_count > 0 ? `<i class="fas fa-images me-1"></i>${point.images_count} photos` : ''}
                                ${point.videos_count > 0 ? `<span class="ms-2"><i class="fas fa-video me-1"></i>${point.videos_count} vidéos</span>` : ''}
                            </small>
                        </div>
                    </td>
                    <td>
                        <div class="stats-info">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-eye me-1 text-muted"></i>
                                <span>${point.views} vues</span>
                            </div>
                            <div class="d-flex align-items-center">
                                ${point.has_details_page ? `
                                    <a href="${point.details_url || '#'}" class="badge bg-info text-white text-decoration-none" target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i>Page dédiée
                                    </a>
                                ` : '<span class="badge bg-secondary">Sans page</span>'}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="point-actions-modern">
                            <button class="action-btn-modern view-btn-modern" 
                                    onclick="previewPoint(${point.id})" 
                                    title="Voir sur la carte">
                                <i class="fas fa-map"></i>
                            </button>
                            <a href="/map-points/${point.id}/edit"
                               class="action-btn-modern edit-btn-modern" 
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-btn-modern delete-btn-modern" 
                                    title="Supprimer" 
                                    onclick="showDeleteConfirmation(${point.id})">
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

        // Update statistics
        const updateStats = (stats) => {
            document.getElementById('totalPoints').textContent = stats.total || 0;
            document.getElementById('withVideoPoints').textContent = stats.with_video || 0;
            document.getElementById('withDetailsPoints').textContent = stats.with_details || 0;
            document.getElementById('featuredPoints').textContent = stats.featured || 0;
            
            // Calculate total views from all points
            const totalViews = allPoints.reduce((sum, point) => sum + (point.views || 0), 0);
            document.getElementById('totalViews').textContent = totalViews;
            
            // Advanced stats by category
            const advancedStats = document.getElementById('advancedStats');
            if (stats.by_category && Object.keys(stats.by_category).length > 0) {
                let categoryHtml = '';
                for (const [category, count] of Object.entries(stats.by_category)) {
                    const percentage = stats.total > 0 ? Math.round((count / stats.total) * 100) : 0;
                    categoryHtml += `
                        <div class="category-stat">
                            <span class="category-name">${getCategoryLabel(category)}</span>
                            <div class="progress" style="height: 8px; flex: 1; margin: 0 10px;">
                                <div class="progress-bar" style="width: ${percentage}%; background: ${getCategoryColor(category)}"></div>
                            </div>
                            <span class="category-count">${count} (${percentage}%)</span>
                        </div>
                    `;
                }
                
                let villesHtml = '';
                if (stats.by_ville) {
                    for (const [ville, count] of Object.entries(stats.by_ville)) {
                        villesHtml += `
                            <div class="ville-stat">
                                <span class="ville-name"><i class="fas fa-city me-1"></i>${ville}</span>
                                <span class="ville-count">${count}</span>
                            </div>
                        `;
                    }
                }
                
                advancedStats.innerHTML = `
                    <div class="advanced-stats-grid">
                        <div class="advanced-stats-section">
                            <h4 class="stats-section-title">
                                <i class="fas fa-chart-pie me-2"></i>Répartition par Catégorie
                            </h4>
                            <div class="category-stats">
                                ${categoryHtml}
                            </div>
                        </div>
                        
                        <div class="advanced-stats-section">
                            <h4 class="stats-section-title">
                                <i class="fas fa-city me-2"></i>Top Villes
                            </h4>
                            <div class="villes-stats">
                                ${villesHtml || '<p class="text-muted">Aucune ville</p>'}
                            </div>
                        </div>
                        
                        <div class="advanced-stats-section">
                            <h4 class="stats-section-title">
                                <i class="fas fa-info-circle me-2"></i>Informations
                            </h4>
                            <div class="global-stats">
                                <div class="global-stat">
                                    <span class="global-label">Points avec vidéo:</span>
                                    <span class="global-value">${stats.with_video} (${stats.total > 0 ? Math.round((stats.with_video/stats.total)*100) : 0}%)</span>
                                </div>
                                <div class="global-stat">
                                    <span class="global-label">Points avec page détails:</span>
                                    <span class="global-value">${stats.with_details} (${stats.total > 0 ? Math.round((stats.with_details/stats.total)*100) : 0}%)</span>
                                </div>
                                <div class="global-stat">
                                    <span class="global-label">Points à la une:</span>
                                    <span class="global-value">${stats.featured}</span>
                                </div>
                                <div class="global-stat">
                                    <span class="global-label">Moyenne vues/point:</span>
                                    <span class="global-value">${allPoints.length > 0 ? Math.round(totalViews / allPoints.length) : 0}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                advancedStats.innerHTML = '';
            }
        };

        // Helper functions for labels and colors
        const getCategoryLabel = (category) => {
            const labels = {
                'restaurant': 'Restaurant',
                'hotel': 'Hôtel',
                'commerce': 'Commerce',
                'sante': 'Santé',
                'education': 'Éducation',
                'culture': 'Culture',
                'sport': 'Sport',
                'loisirs': 'Loisirs',
                'transport': 'Transport',
                'immobilier': 'Immobilier',
                'service': 'Service',
                'autre': 'Autre'
            };
            return labels[category] || category;
        };

        const getCategoryColor = (category) => {
            const colors = {
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
                'service': '#B0C4DE'
            };
            return colors[category] || '#6c757d';
        };

        // Preview image
        const previewImage = (imageUrl) => {
            const modal = `
                <div class="modal fade" id="imagePreviewModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Aperçu Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="${imageUrl}" class="img-fluid" alt="Preview">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            const existingModal = document.getElementById('imagePreviewModal');
            if (existingModal) existingModal.remove();
            
            // Add new modal
            document.body.insertAdjacentHTML('beforeend', modal);
            
            // Show modal
            const imageModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            imageModal.show();
            
            // Remove modal after hidden
            document.getElementById('imagePreviewModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        };

        // Play video
        const playVideo = (youtubeId) => {
            const iframe = document.getElementById('youtubeIframe');
            iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1`;
            
            const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
            videoModal.show();
            
            // Stop video when modal is closed
            document.getElementById('videoModal').addEventListener('hidden.bs.modal', function() {
                iframe.src = '';
            });
        };

        // Preview point on map
        const previewPoint = (pointId) => {
            // This would open the map centered on this point
            // You can redirect to the map page with the point highlighted
            window.location.href = `/map?point=${pointId}`;
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (pointId) => {
            const point = allPoints.find(p => p.id === pointId);
            
            if (!point) {
                showAlert('danger', 'Point non trouvé');
                return;
            }
            
            pointToDelete = point;
            
            document.getElementById('pointToDeleteInfo').innerHTML = `
                <div class="point-info">
                    <div class="point-info-icon" style="background: ${point.category_color}">
                        <i class="fas ${point.category_icon} fa-2x"></i>
                    </div>
                    <div>
                        <div class="point-info-name">${point.title}</div>
                        <div class="point-info-details">
                            <div><strong>Catégorie:</strong> ${point.category_label}</div>
                            <div><strong>Localisation:</strong> ${point.ville || 'N/A'}</div>
                            <div><strong>Médias:</strong> ${point.images_count} images, ${point.videos_count} vidéos</div>
                            <div><strong>Vues:</strong> ${point.views}</div>
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

        // Delete point
        const deletePoint = () => {
            if (!pointToDelete) {
                showAlert('danger', 'Aucun point à supprimer');
                return;
            }
            
            const pointId = pointToDelete.id;
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
            const row = document.getElementById(`point-row-${pointId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/map-points/${pointId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove point from array
                        allPoints = allPoints.filter(p => p.id !== pointId);
                        
                        // Update statistics
                        loadPoints(currentPage, currentFilters);
                        
                        // Show success message
                        showAlert('success', response.message || 'Point supprimé avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('mapPointsTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadPoints(currentPage, currentFilters);
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
                    const row = document.getElementById(`point-row-${pointId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Point non trouvé.');
                        loadPoints(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    pointToDelete = null;
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
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} points`;
            
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
            loadPoints(page, currentFilters);
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
                        loadPoints(1, currentFilters);
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
                        category: document.getElementById('filterCategory').value,
                        ville: document.getElementById('filterVille').value,
                        has_video: document.getElementById('filterHasVideo').value,
                        has_details: document.getElementById('filterHasDetails').value,
                        sort_by: document.getElementById('filterSortBy').value,
                        sort_order: document.getElementById('filterSortOrder').value,
                    };
                    loadPoints(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterCategory').value = '';
                    document.getElementById('filterVille').value = '';
                    document.getElementById('filterHasVideo').value = '';
                    document.getElementById('filterHasDetails').value = '';
                    document.getElementById('filterSortBy').value = 'created_at';
                    document.getElementById('filterSortOrder').value = 'desc';
                    currentFilters = {};
                    loadPoints(1);
                });
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deletePoint);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    pointToDelete = null;
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                });
            }
            
            // Reset video modal when hidden
            const videoModal = document.getElementById('videoModal');
            if (videoModal) {
                videoModal.addEventListener('hidden.bs.modal', function() {
                    const iframe = document.getElementById('youtubeIframe');
                    iframe.src = '';
                });
            }
        };
    </script>

    <style>
        /* Styles spécifiques pour la page map-points */
        .point-name-modern {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .point-icon-modern {
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

        .point-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .category-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .location-info {
            display: flex;
            flex-direction: column;
        }

        .media-info {
            display: flex;
            flex-direction: column;
        }

        .btn-media {
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-right: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-image {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .btn-image:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
        }

        .btn-video {
            background: linear-gradient(135deg, #FF6B6B, #ff5252);
            color: white;
        }

        .btn-video:hover {
            background: linear-gradient(135deg, #ff5252, #e63e3e);
        }

        .stats-info {
            display: flex;
            flex-direction: column;
        }

        .point-actions-modern {
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

        .point-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .point-info-icon {
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

        .point-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .point-info-details {
            font-size: 0.9rem;
            color: #666;
        }

        .point-info-details div {
            margin-bottom: 2px;
        }

        /* Advanced stats grid */
        .advanced-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .advanced-stats-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .stats-section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .category-stats, .villes-stats {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .category-stat, .ville-stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .category-stat:last-child, .ville-stat:last-child {
            border-bottom: none;
        }

        .category-name, .ville-name {
            font-weight: 500;
            color: #333;
            min-width: 100px;
        }

        .category-count, .ville-count {
            font-weight: 600;
            color: #666;
            min-width: 60px;
            text-align: right;
        }

        .global-stats {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .global-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .global-stat:last-child {
            border-bottom: none;
        }

        .global-label {
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;
        }

        .global-value {
            font-weight: 600;
            color: #333;
        }

        /* Video container */
        .video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 */
            height: 0;
            overflow: hidden;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
        @media (max-width: 992px) {
            .advanced-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .advanced-stats-grid {
                grid-template-columns: 1fr;
            }
            
            .point-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .point-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .category-stat {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .category-stat .progress {
                width: 100%;
                margin: 5px 0;
            }
            
            .category-count {
                align-self: flex-end;
            }
        }
    </style>
@endsection