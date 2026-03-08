@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-building"></i></span>
                Gestion des Établissements
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <a href="{{ route('etablissements.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvel Établissement
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
                <div class="col-md-4">
                    <label for="filterVille" class="form-label-modern">Ville</label>
                    <select class="form-select-modern" id="filterVille">
                        <option value="">Toutes les villes</option>
                        @foreach($villes ?? [] as $ville)
                            <option value="{{ $ville }}">{{ $ville }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="ville">Ville</option>
                        <option value="created_at">Date de création</option>
                        <option value="activities_count">Nombre d'activités</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterSortDirection" class="form-label-modern">Ordre</label>
                    <select class="form-select-modern" id="filterSortDirection">
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalEtablissements">0</div>
                        <div class="stats-label-modern">Total Établissements</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeEtablissements">0</div>
                        <div class="stats-label-modern">Établissements Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalActivities">0</div>
                        <div class="stats-label-modern">Activités Totales</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="averageActivities">0</div>
                        <div class="stats-label-modern">Moyenne d'Activités</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Établissements</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un établissement..." id="searchInput">
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
                                <th>Établissement</th>
                                <th>Ville</th>
                                <th>Responsable</th>
                                <th>Contact</th>
                                <th>Activités</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="etablissementsTableBody">
                            <!-- Établissements will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun établissement trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier établissement.</p>
                    <a href="{{ route('etablissements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Créer un établissement
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
        <a href="{{ route('etablissements.create') }}" class="fab-modern">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cet établissement ? Toutes les activités associées seront également détachées.</p>
                    
                    <div class="etablissement-to-delete" id="etablissementToDeleteInfo">
                        <!-- Établissement info will be loaded here -->
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allEtablissements = [];
        let etablissementToDelete = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadEtablissements();
            loadStatistics();
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

        // Load établissements
        const loadEtablissements = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("etablissements.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allEtablissements = response.data || [];
                        renderEtablissements(allEtablissements);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des établissements');
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
                url: '{{ url("etablissements/statistics/data") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalEtablissements').textContent = stats.total || 0;
                        document.getElementById('activeEtablissements').textContent = stats.active || 0;
                        document.getElementById('totalActivities').textContent = stats.total_activities || 0;
                        
                        // Calculer la moyenne d'activités
                        const avg = stats.total > 0 ? Math.round((stats.total_activities / stats.total) * 10) / 10 : 0;
                        document.getElementById('averageActivities').textContent = avg;
                        
                        // Optionnel: Mettre à jour les statistiques avancées
                        updateAdvancedStats(stats);
                    } else {
                        console.error('Error loading statistics:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Statistics AJAX error:', xhr.responseText, status, error);
                    
                    // Valeurs par défaut
                    document.getElementById('totalEtablissements').textContent = '0';
                    document.getElementById('activeEtablissements').textContent = '0';
                    document.getElementById('totalActivities').textContent = '0';
                    document.getElementById('averageActivities').textContent = '0';
                }
            });
        };

        // Render établissements with modern design
        const renderEtablissements = (etablissements) => {
            const tbody = document.getElementById('etablissementsTableBody');
            tbody.innerHTML = '';
            
            if (!etablissements || !Array.isArray(etablissements) || etablissements.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            etablissements.forEach((etablissement, index) => {
                const row = document.createElement('tr');
                row.id = `etablissement-row-${etablissement.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                row.innerHTML = `
                    <td>
                        <div class="etablissement-name-cell">
                            <div class="etablissement-name-modern">
                                <div class="etablissement-icon-modern">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <div class="etablissement-name-text">${etablissement.name}</div>
                                    <small class="text-muted">${etablissement.lname || ''}</small>
                                    <div class="small text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>${etablissement.adresse}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="ville-badge" style="background: ${getVilleColor(etablissement.ville)}">
                            ${etablissement.ville}
                        </div>
                        <small class="text-muted d-block">${etablissement.zip_code}</small>
                    </td>
                    <td>
                        ${etablissement.user ? `
                            <div>${etablissement.user.name}</div>
                            <small class="text-muted">${etablissement.user.email}</small>
                        ` : 'N/A'}
                    </td>
                    <td>
                        <div>${etablissement.phone || 'N/A'}</div>
                        ${etablissement.website ? `<small class="text-muted"><i class="fas fa-globe me-1"></i>${etablissement.website}</small>` : ''}
                    </td>
                    <td>
                        <div class="activities-count">
                            <span class="badge bg-primary">${etablissement.activities_count || 0}</span>
                            <small class="text-muted d-block mt-1">activités</small>
                        </div>
                    </td>
                    <td>
                        ${getStatusBadge(etablissement.is_active)}
                    </td>
                    <td>
                        <div class="etablissement-actions-modern">
                            <a href="${etablissement.website || '#'}" target="_blank"
                               class="action-btn-modern view-btn-modern" title="Visiter le site" ${!etablissement.website ? 'style="opacity:0.5; pointer-events:none;"' : ''}>
                                <i class="fas fa-globe"></i>
                            </a>
                            <a href="{{ route('etablissements.show', '') }}/${etablissement.id}" 
                               class="action-btn-modern view-btn-modern" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('etablissements.edit', '') }}/${etablissement.id}"
                               class="action-btn-modern edit-btn-modern" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteConfirmation(${etablissement.id})">
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

        // Get ville color
        const getVilleColor = (ville) => {
            // Hash function pour générer une couleur basée sur le nom de la ville
            let hash = 0;
            for (let i = 0; i < ville.length; i++) {
                hash = ville.charCodeAt(i) + ((hash << 5) - hash);
            }
            
            const colors = [
                '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
                '#9b59b6', '#3498db', '#1abc9c', '#e74c3c'
            ];
            
            return colors[Math.abs(hash) % colors.length];
        };

        // Get status badge
        const getStatusBadge = (isActive) => {
            if (isActive) {
                return `<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Actif</span>`;
            } else {
                return `<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>Inactif</span>`;
            }
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (etablissementId) => {
            const etablissement = allEtablissements.find(e => e.id === etablissementId);
            
            if (!etablissement) {
                showAlert('danger', 'Établissement non trouvé');
                return;
            }
            
            etablissementToDelete = etablissement;
            
            document.getElementById('etablissementToDeleteInfo').innerHTML = `
                <div class="etablissement-info">
                    <div class="etablissement-info-icon">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <div>
                        <div class="etablissement-info-name">${etablissement.name}</div>
                        <div class="etablissement-info-details">
                            <div><strong>Ville:</strong> ${etablissement.ville}</div>
                            <div><strong>Responsable:</strong> ${etablissement.user?.name || 'N/A'}</div>
                            <div><strong>Activitiés:</strong> ${etablissement.activities_count || 0}</div>
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

        // Delete établissement
        const deleteEtablissement = () => {
            if (!etablissementToDelete) {
                showAlert('danger', 'Aucun établissement à supprimer');
                return;
            }
            
            const etablissementId = etablissementToDelete.id;
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
            const row = document.getElementById(`etablissement-row-${etablissementId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/etablissements/${etablissementId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove établissement from array
                        allEtablissements = allEtablissements.filter(e => e.id !== etablissementId);
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Établissement supprimé avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('etablissementsTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadEtablissements(currentPage, currentFilters);
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
                    const row = document.getElementById(`etablissement-row-${etablissementId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Établissement non trouvé.');
                        loadEtablissements(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    etablissementToDelete = null;
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
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} établissements`;
            
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
            loadEtablissements(page, currentFilters);
        };

        // Format number
        const formatNumber = (num) => {
            if (num === null || num === undefined) return 'N/A';
            
            const number = typeof num === 'string' ? parseFloat(num) : num;
            
            if (isNaN(number)) return 'N/A';
            
            return new Intl.NumberFormat('fr-FR').format(number);
        };

        // Update advanced stats
        const updateAdvancedStats = (stats) => {
            const advancedStatsContainer = document.getElementById('advancedStats') || createAdvancedStatsContainer();
            
            if (stats.by_ville && stats.by_ville.length > 0) {
                let villesHtml = '';
                stats.by_ville.slice(0, 3).forEach(item => {
                    villesHtml += `
                        <div class="ville-stat">
                            <span class="ville-name">${item.ville}</span>
                            <span class="ville-count">${item.total}</span>
                        </div>
                    `;
                });
                
                advancedStatsContainer.innerHTML = `
                    <div class="advanced-stats-section">
                        <h4 class="stats-section-title">Top Villes</h4>
                        <div class="villes-stats">
                            ${villesHtml}
                        </div>
                    </div>
                `;
            }
        };

        // Create advanced stats container
        const createAdvancedStatsContainer = () => {
            const container = document.createElement('div');
            container.id = 'advancedStats';
            container.className = 'advanced-stats-section';
            
            const statsGrid = document.querySelector('.stats-grid');
            if (statsGrid) {
                statsGrid.parentNode.insertBefore(container, statsGrid.nextSibling);
            }
            
            return container;
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
                        loadEtablissements(1, currentFilters);
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
                        ville: document.getElementById('filterVille').value,
                        is_active: document.getElementById('filterStatus').value,
                        sort_by: document.getElementById('filterSortBy').value,
                        sort_direction: document.getElementById('filterSortDirection').value
                    };
                    loadEtablissements(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterVille').value = '';
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterSortBy').value = 'created_at';
                    document.getElementById('filterSortDirection').value = 'desc';
                    currentFilters = {};
                    loadEtablissements(1);
                });
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteEtablissement);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    etablissementToDelete = null;
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
    </script>

    <style>
        /* Styles spécifiques pour la page établissements */
        .etablissement-name-modern {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .etablissement-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 2px 4px rgba(69, 183, 209, 0.3);
        }

        .etablissement-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .ville-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 4px;
        }

        .activities-count {
            text-align: center;
        }

        .etablissement-actions-modern {
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

        .view-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .view-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 71, 111, 0.3);
        }

        .etablissement-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .etablissement-info-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .etablissement-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .etablissement-info-details {
            font-size: 0.9rem;
            color: #666;
        }

        .etablissement-info-details div {
            margin-bottom: 2px;
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

        /* Advanced stats */
        .advanced-stats-section {
            margin-top: 30px;
            margin-bottom: 30px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .stats-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .villes-stats {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .ville-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .ville-stat:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .ville-name {
            font-weight: 600;
            color: #333;
        }

        .ville-count {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .etablissement-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .etablissement-icon-modern {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            
            .etablissement-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
        }
    </style>
@endsection