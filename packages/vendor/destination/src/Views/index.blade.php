@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-map-marker-alt"></i></span>
                Gestion des Destinations Canada
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDestinationModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Destination
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
                <div class="col-md-4">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                        <option value="deleted">Supprimé</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterDateFrom" class="form-label-modern">Date de début</label>
                    <input type="date" class="form-control-modern" id="filterDateFrom">
                </div>
                <div class="col-md-4">
                    <label for="filterDateTo" class="form-label-modern">Date de fin</label>
                    <input type="date" class="form-control-modern" id="filterDateTo">
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalDestinations">0</div>
                        <div class="stats-label-modern">Total Destinations</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeDestinations">0</div>
                        <div class="stats-label-modern">Destinations Actives</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="inactiveDestinations">0</div>
                        <div class="stats-label-modern">Destinations Inactives</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="thisMonth">0</div>
                        <div class="stats-label-modern">Ajoutées ce mois</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Destinations</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une destination..." id="searchInput">
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
                                <th>Destination</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="destinationsTableBody">
                            <!-- Destinations will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune destination trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première destination canadienne.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDestinationModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer une destination
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createDestinationModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE DESTINATION MODAL -->
    <div class="modal fade" id="createDestinationModal" tabindex="-1" aria-labelledby="createDestinationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createDestinationModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle destination
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createDestinationForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="destinationName" class="form-label-modern">Nom de la destination *</label>
                                <input type="text" class="form-control-modern" id="destinationName" name="name" placeholder="Ex: Toronto, Montréal, Vancouver..." required>
                                <div class="form-text-modern">Nom complet de la destination canadienne</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="destinationImage" class="form-label-modern">Image</label>
                                <input type="file" class="form-control-modern" id="destinationImage" name="image" accept="image/*">
                                <div class="form-text-modern">Image représentative de la destination (JPEG, PNG, GIF - max 2MB)</div>
                                <div class="image-preview mt-2" id="imagePreview" style="display: none;">
                                    <img id="previewImage" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="destinationDescription" class="form-label-modern">Description</label>
                                <textarea class="form-control-modern" id="destinationDescription" name="description" rows="3" placeholder="Description détaillée de la destination..."></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="destinationIsActive" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="destinationIsActive">Destination active</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitDestinationBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la destination
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT DESTINATION MODAL -->
    <div class="modal fade" id="editDestinationModal" tabindex="-1" aria-labelledby="editDestinationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editDestinationModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier la destination
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editDestinationForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editDestinationId" name="id">
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="editDestinationName" class="form-label-modern">Nom de la destination *</label>
                                <input type="text" class="form-control-modern" id="editDestinationName" name="name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="editDestinationImage" class="form-label-modern">Image</label>
                                <input type="file" class="form-control-modern" id="editDestinationImage" name="image" accept="image/*">
                                <div class="form-text-modern">Laisser vide pour conserver l'image actuelle</div>
                                <div class="image-preview mt-2" id="editImagePreview">
                                    <img id="editPreviewImage" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="editDestinationDescription" class="form-label-modern">Description</label>
                                <textarea class="form-control-modern" id="editDestinationDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editDestinationIsActive" name="is_active" value="1">
                                    <label class="form-check-label" for="editDestinationIsActive">Destination active</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateDestinationBtn">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cette destination ? Cette action est irréversible.</p>
                    
                    <div class="destination-to-delete" id="destinationToDeleteInfo">
                        <!-- Destination info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> L'image associée à cette destination sera également supprimée.
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
                    <h4 class="restore-title">Restaurer la destination</h4>
                    <p class="restore-message">Voulez-vous restaurer cette destination ?</p>
                    
                    <div class="destination-to-restore" id="destinationToRestoreInfo">
                        <!-- Destination info will be loaded here -->
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
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allDestinations = [];
        let destinationToDelete = null;
        let destinationToRestore = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadDestinations();
            loadStatistics();
            setupEventListeners();
            setupImagePreview();
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

        // Load destinations
        const loadDestinations = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("destinations.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allDestinations = response.data || [];
                        renderDestinations(allDestinations);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des destinations');
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
                url: '{{ route("destinations.statistics") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalDestinations').textContent = stats.total;
                        document.getElementById('activeDestinations').textContent = stats.active;
                        document.getElementById('inactiveDestinations').textContent = stats.inactive;
                        document.getElementById('thisMonth').textContent = stats.this_month;
                    }
                }
            });
        };

        // Render destinations with modern design
        const renderDestinations = (destinations) => {
            const tbody = document.getElementById('destinationsTableBody');
            tbody.innerHTML = '';
            
            if (!destinations || !Array.isArray(destinations) || destinations.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            destinations.forEach((destination, index) => {
                const row = document.createElement('tr');
                row.id = `destination-row-${destination.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                // Format date
                const createdDate = new Date(destination.created_at);
                const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                
                // Truncate description
                const shortDescription = destination.description 
                    ? (destination.description.length > 100 
                        ? destination.description.substring(0, 100) + '...' 
                        : destination.description)
                    : 'Aucune description';
                
                // Status badge
                let statusClass = 'status-active-modern';
                let statusText = 'Actif';
                let statusIcon = 'fa-check-circle';
                
                if (!destination.is_active) {
                    statusClass = 'status-inactive-modern';
                    statusText = 'Inactif';
                    statusIcon = 'fa-ban';
                }
                
                if (destination.deleted_at) {
                    statusClass = 'status-deleted-modern';
                    statusText = 'Supprimé';
                    statusIcon = 'fa-trash';
                }
                
                // Image preview
                const imageUrl = destination.image 
                    ? `/storage/${destination.image}` 
                    : 'https://via.placeholder.com/50';
                
                row.innerHTML = `
                    <td class="destination-name-cell">
                        <div class="destination-name-modern">
                            <div class="destination-icon-modern">
                                <img src="${imageUrl}" alt="${destination.name}" class="destination-thumbnail">
                            </div>
                            <div>
                                <div class="destination-name-text">${destination.name}</div>
                                <small class="text-muted">ID: ${destination.id} | Slug: ${destination.slug}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="destination-description">${shortDescription}</div>
                    </td>
                    <td>
                        <span class="destination-status-modern ${statusClass}">
                            <i class="fas ${statusIcon} me-1"></i>
                            ${statusText}
                        </span>
                    </td>
                    <td>
                        <div>${formattedDate}</div>
                        <small class="text-muted">${formatTimeAgo(createdDate)}</small>
                    </td>
                    <td>
                        <div class="destination-actions-modern">
                            ${destination.deleted_at ? `
                                <button class="action-btn-modern restore-btn-modern" title="Restaurer" onclick="showRestoreConfirmation(${destination.id})">
                                    <i class="fas fa-undo"></i>
                                </button>
                            ` : `
                                <button class="action-btn-modern edit-btn-modern" title="Modifier" onclick="openEditModal(${destination.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn-modern status-btn-modern" title="Changer statut" onclick="toggleDestinationStatus(${destination.id})">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button class="action-btn-modern delete-btn-modern" title="Supprimer" onclick="showDeleteConfirmation(${destination.id})">
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

        // Show delete confirmation modal
        const showDeleteConfirmation = (destinationId) => {
            const destination = allDestinations.find(d => d.id === destinationId);
            
            if (!destination) {
                showAlert('danger', 'Destination non trouvée');
                return;
            }
            
            destinationToDelete = destination;
            
            // Format date
            const createdDate = new Date(destination.created_at);
            const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            document.getElementById('destinationToDeleteInfo').innerHTML = `
                <div class="destination-info">
                    <div class="destination-info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div class="destination-info-name">${destination.name}</div>
                        <div class="destination-info-description">${destination.description ? destination.description.substring(0, 100) + (destination.description.length > 100 ? '...' : '') : 'Aucune description'}</div>
                    </div>
                </div>
                <div class="row small text-muted">
                    <div class="col-6">
                        <div><strong>ID:</strong> ${destination.id}</div>
                        <div><strong>Slug:</strong> ${destination.slug}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Créé le:</strong> ${formattedDate}</div>
                        <div><strong>Statut:</strong> ${destination.is_active ? 'Actif' : 'Inactif'}</div>
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
        const showRestoreConfirmation = (destinationId) => {
            const destination = allDestinations.find(d => d.id === destinationId);
            
            if (!destination) {
                showAlert('danger', 'Destination non trouvée');
                return;
            }
            
            destinationToRestore = destination;
            
            document.getElementById('destinationToRestoreInfo').innerHTML = `
                <div class="destination-info">
                    <div class="destination-info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div class="destination-info-name">${destination.name}</div>
                    </div>
                </div>
            `;
            
            // Show modal
            const restoreModal = new bootstrap.Modal(document.getElementById('restoreConfirmationModal'));
            restoreModal.show();
        };

        // Delete destination
        const deleteDestination = () => {
            if (!destinationToDelete) {
                showAlert('danger', 'Aucune destination à supprimer');
                return;
            }
            
            const destinationId = destinationToDelete.id;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            
            // Show processing animation on delete button
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
            
            // Send DELETE request
            $.ajax({
                url: `/destinations/${destinationId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove destination from allDestinations array
                        allDestinations = allDestinations.filter(d => d.id !== destinationId);
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Destination supprimée avec succès !');
                        
                        // Reload destinations
                        loadDestinations(currentPage, currentFilters);
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la suppression de la destination');
                    }
                },
                error: function(xhr) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Destination non trouvée');
                        loadDestinations(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression de la destination');
                    }
                },
                complete: function() {
                    destinationToDelete = null;
                }
            });
        };

        // Restore destination
        const restoreDestination = () => {
            if (!destinationToRestore) {
                showAlert('danger', 'Aucune destination à restaurer');
                return;
            }
            
            const destinationId = destinationToRestore.id;
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
            
            // Send POST request
            $.ajax({
                url: `/destinations/${destinationId}/restore`,
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
                        showAlert('success', response.message || 'Destination restaurée avec succès !');
                        
                        // Reload destinations
                        loadDestinations(currentPage, currentFilters);
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la restauration de la destination');
                    }
                },
                error: function() {
                    const restoreModal = bootstrap.Modal.getInstance(document.getElementById('restoreConfirmationModal'));
                    restoreModal.hide();
                    showAlert('danger', 'Erreur lors de la restauration de la destination');
                },
                complete: function() {
                    destinationToRestore = null;
                }
            });
        };

        // Toggle destination status
        const toggleDestinationStatus = (destinationId) => {
            $.ajax({
                url: `/destinations/${destinationId}/toggle-status`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message || 'Statut modifié avec succès !');
                        loadDestinations(currentPage, currentFilters);
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

        // Update statistics
        const updateStats = (destinations) => {
            const total = destinations.length;
            const active = destinations.filter(d => d.is_active && !d.deleted_at).length;
            const inactive = destinations.filter(d => !d.is_active && !d.deleted_at).length;
            const deleted = destinations.filter(d => d.deleted_at).length;
            const thisMonth = destinations.filter(d => {
                const created = new Date(d.created_at);
                const now = new Date();
                return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
            }).length;
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} destination${response.total > 1 ? 's' : ''}`;
            
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
            loadDestinations(page, currentFilters);
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

        // Store destination
        const storeDestination = () => {
            const form = document.getElementById('createDestinationForm');
            const submitBtn = document.getElementById('submitDestinationBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Créer la destination
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Création en cours...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            $.ajax({
                url: '{{ route("destinations.store") }}',
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
                            <i class="fas fa-save me-2"></i>Créer la destination
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createDestinationModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        document.getElementById('imagePreview').style.display = 'none';
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Reload destinations
                        loadDestinations(1, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Destination créée avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création de la destination');
                    }
                },
                error: function(xhr) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la destination
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
                        showAlert('danger', 'Erreur lors de la création de la destination');
                    }
                }
            });
        };

        // Update destination
        const updateDestination = () => {
            const form = document.getElementById('editDestinationForm');
            const submitBtn = document.getElementById('updateDestinationBtn');
            const destinationId = document.getElementById('editDestinationId').value;
            
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
                url: `/destinations/${destinationId}`,
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editDestinationModal'));
                        modal.hide();
                        
                        // Reload destinations
                        loadDestinations(currentPage, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Destination mise à jour avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la mise à jour de la destination');
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
                        showAlert('danger', 'Erreur lors de la mise à jour de la destination');
                    }
                }
            });
        };

        // Open edit modal
        const openEditModal = (destinationId) => {
            const destination = allDestinations.find(d => d.id === destinationId);
            
            if (destination) {
                document.getElementById('editDestinationId').value = destination.id;
                document.getElementById('editDestinationName').value = destination.name;
                document.getElementById('editDestinationDescription').value = destination.description || '';
                document.getElementById('editDestinationIsActive').checked = destination.is_active;
                
                // Set image preview
                const preview = document.getElementById('editPreviewImage');
                const previewContainer = document.getElementById('editImagePreview');
                if (destination.image) {
                    preview.src = `/storage/${destination.image}`;
                    previewContainer.style.display = 'block';
                } else {
                    previewContainer.style.display = 'none';
                }
                
                new bootstrap.Modal(document.getElementById('editDestinationModal')).show();
            }
        };

        // Setup image preview
        const setupImagePreview = () => {
            // Preview for create modal
            const createImageInput = document.getElementById('destinationImage');
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
            
            // Preview for edit modal
            const editImageInput = document.getElementById('editDestinationImage');
            const editPreview = document.getElementById('editPreviewImage');
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
                    }
                });
            }
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
                        loadDestinations(1, currentFilters);
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
                        date_from: document.getElementById('filterDateFrom').value,
                        date_to: document.getElementById('filterDateTo').value
                    };
                    loadDestinations(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterDateFrom').value = '';
                    document.getElementById('filterDateTo').value = '';
                    currentFilters = {};
                    loadDestinations(1);
                });
            }
            
            // Submit destination form
            const submitDestinationBtn = document.getElementById('submitDestinationBtn');
            if (submitDestinationBtn) {
                submitDestinationBtn.addEventListener('click', storeDestination);
            }
            
            // Update destination form
            const updateDestinationBtn = document.getElementById('updateDestinationBtn');
            if (updateDestinationBtn) {
                updateDestinationBtn.addEventListener('click', updateDestination);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteDestination);
            }
            
            // Confirm restore button
            const confirmRestoreBtn = document.getElementById('confirmRestoreBtn');
            if (confirmRestoreBtn) {
                confirmRestoreBtn.addEventListener('click', restoreDestination);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    destinationToDelete = null;
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
                    destinationToRestore = null;
                });
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createDestinationModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createDestinationForm').reset();
                    document.getElementById('imagePreview').style.display = 'none';
                    const submitBtn = document.getElementById('submitDestinationBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la destination
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editDestinationModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    const submitBtn = document.getElementById('updateDestinationBtn');
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
    /* Additional CSS for destinations */
    .destination-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .destination-name-modern {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .destination-icon-modern {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
    }
    
    .destination-name-text {
        font-weight: 600;
        color: #333;
    }
    
    .destination-description {
        color: #666;
        font-size: 0.9em;
        line-height: 1.4;
    }
    
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
    
    .image-preview img {
        max-width: 200px;
        max-height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    
    .destination-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .destination-info-icon {
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
    
    .destination-info-name {
        font-weight: 600;
        color: #333;
        font-size: 1.1em;
    }
    
    .destination-info-description {
        color: #666;
        font-size: 0.9em;
        margin-top: 5px;
    }
</style>

@endsection