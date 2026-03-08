@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-running"></i></span>
                Gestion des Activités
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createActivityModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Activité
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
                    <label for="filterCategory" class="form-label-modern">Catégorie</label>
                    <select class="form-select-modern" id="filterCategory">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories ?? [] as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actives</option>
                        <option value="inactive">Inactives</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="participants_count">Participants</option>
                        <option value="bookings_count">Réservations</option>
                        <option value="created_at">Date de création</option>
                    </select>
                </div>
            </div>
        </div>
        
        
        <!-- Bulk Actions -->
        <div class="bulk-actions-modern" id="bulkActions" style="display: none;">
            <div class="bulk-actions-content">
                <span id="selectedCount">0 activité(s) sélectionnée(s)</span>
                <div class="bulk-actions-buttons">
                    <select class="form-select-modern bulk-select" id="bulkActionSelect">
                        <option value="">Actions groupées...</option>
                        <option value="activate">Activer</option>
                        <option value="deactivate">Désactiver</option>
                        <option value="delete">Supprimer</option>
                    </select>
                    <button class="btn btn-sm btn-primary" id="applyBulkActionBtn">
                        <i class="fas fa-play me-1"></i>Appliquer
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="clearSelectionBtn">
                        <i class="fas fa-times me-1"></i>Effacer la sélection
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Activités</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une activité..." id="searchInput">
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
                                <th style="width: 50px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                                    </div>
                                </th>
                                <th>Activité</th>
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="activitiesTableBody">
                            <!-- Activities will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-running"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune activité trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première activité.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createActivityModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer une activité
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
        
        <!-- Advanced Stats Section -->
        <div class="advanced-stats-section" id="advancedStatsSection" style="display: none;">
            <div class="section-header-modern">
                <h3 class="section-title-modern">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques Avancées
                </h3>
                <button class="btn btn-sm btn-outline-secondary" id="refreshStatsBtn">
                    <i class="fas fa-sync-alt me-1"></i>Actualiser
                </button>
            </div>
            
            <div id="advancedStats" class="advanced-stats-grid">
                <!-- Advanced stats will be loaded here -->
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createActivityModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- Modals -->
    @include('activitie::activities.create-modal')
    @include('activitie::activities.edit-modal')
    @include('activitie::activities.delete-modal')

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
// Configuration pour les activités
let currentPage = 1;
let currentFilters = {};
let allActivities = [];
let selectedActivities = new Set();
let activityToDelete = null;
let editingActivityId = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupAjax();
    loadActivities();
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

// Load activities
const loadActivities = (page = 1, filters = {}) => {
    showLoading();
    
    const searchTerm = document.getElementById('searchInput')?.value || '';
    
    $.ajax({
        url: '{{ route("activities.index") }}',
        type: 'GET',
        data: {
            page: page,
            search: searchTerm,
            ...filters,
            ajax: true
        },
        success: function(response) {
            if (response.success) {
                allActivities = response.data || [];
                renderActivities(allActivities);
                renderPagination(response);
                hideLoading();
            } else {
                showError('Erreur lors du chargement des activités');
            }
        },
        error: function(xhr) {
            hideLoading();
            showError('Erreur de connexion au serveur');
            console.error('Error:', xhr.responseText);
        }
    });
};

// Render activities - Version simplifiée
const renderActivities = (activities) => {
    const tbody = document.getElementById('activitiesTableBody');
    tbody.innerHTML = '';
    
    if (!activities || !Array.isArray(activities) || activities.length === 0) {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
        document.getElementById('bulkActions').style.display = 'none';
        return;
    }
    
    activities.forEach((activity, index) => {
        const row = document.createElement('tr');
        row.id = `activity-row-${activity.id}`;
        row.setAttribute('style', `animation-delay: ${index * 0.05}s`);
        
        const isSelected = selectedActivities.has(activity.id);
        const statusClass = activity.is_active ? 'status-active' : 'status-inactive';
        const statusText = activity.is_active ? 'Actif' : 'Inactif';
        
        row.innerHTML = `
            <td>
                <div class="form-check">
                    <input class="form-check-input row-checkbox" type="checkbox" 
                           value="${activity.id}" ${isSelected ? 'checked' : ''}
                           onchange="toggleActivitySelection(${activity.id}, this.checked)">
                </div>
            </td>
            <td class="activity-name-cell">
                <div class="activity-name-modern">
                    <div class="activity-icon-modern">
                        <i class="fas fa-running"></i>
                    </div>
                    <div>
                        <div class="activity-name-text">${activity.name}</div>
                        <div class="activity-slug-text text-muted small">${activity.slug || 'Pas de slug'}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="categorie-badge">
                    <i class="fas fa-tag me-1"></i>
                    ${activity.categorie?.name || 'Non catégorisé'}
                </div>
            </td>
            <td>
                <span class="status-badge ${statusClass}">${statusText}</span>
            </td>
            <td>
                <div class="activity-actions-modern">
                    <button class="action-btn-modern status-btn-modern" title="Changer le statut" 
                            onclick="toggleActivityStatus(${activity.id})">
                        <i class="fas fa-power-off"></i>
                    </button>
                    <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                            onclick="openEditModal(${activity.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                            onclick="showDeleteConfirmation(${activity.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    // Update bulk actions
    updateBulkActions();
    
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('tableContainer').style.display = 'block';
    document.getElementById('paginationContainer').style.display = 'flex';
};

// Render pagination
const renderPagination = (response) => {
    const pagination = document.getElementById('pagination');
    const paginationInfo = document.getElementById('paginationInfo');
    
    // Update pagination info
    const start = (response.current_page - 1) * response.per_page + 1;
    const end = Math.min(response.current_page * response.per_page, response.total);
    paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} activités`;
    
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
    loadActivities(page, currentFilters);
};

// Toggle activity selection
const toggleActivitySelection = (activityId, isChecked) => {
    if (isChecked) {
        selectedActivities.add(activityId);
    } else {
        selectedActivities.delete(activityId);
    }
    
    updateSelectAllCheckbox();
    updateBulkActions();
};

// Update select all checkbox
const updateSelectAllCheckbox = () => {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        const allCheckboxes = document.querySelectorAll('.row-checkbox');
        const allChecked = allCheckboxes.length > 0 && 
            Array.from(allCheckboxes).every(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = !allChecked && selectedActivities.size > 0;
    }
};

// Update bulk actions
const updateBulkActions = () => {
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedActivities.size > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = `${selectedActivities.size} activité(s) sélectionnée(s)`;
    } else {
        bulkActions.style.display = 'none';
    }
};

// Select all activities
const selectAllActivities = (isChecked) => {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    
    checkboxes.forEach(checkbox => {
        const activityId = parseInt(checkbox.value);
        checkbox.checked = isChecked;
        
        if (isChecked) {
            selectedActivities.add(activityId);
        } else {
            selectedActivities.delete(activityId);
        }
    });
    
    updateBulkActions();
};

// Apply bulk action
const applyBulkAction = () => {
    const action = document.getElementById('bulkActionSelect').value;
    
    if (!action || selectedActivities.size === 0) {
        showAlert('warning', 'Veuillez sélectionner une action et des activités');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedActivities.size} activité(s) ?`)) {
            return;
        }
    }
    
    const data = {
        ids: Array.from(selectedActivities),
        action: action
    };
    
    $.ajax({
        url: '{{ route("activities.bulk-update") }}',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                selectedActivities.clear();
                loadActivities(currentPage, currentFilters);
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function(xhr, status, error) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                for (const field in errors) {
                    errorMessage += `- ${errors[field].join('<br>')}<br>`;
                }
                showAlert('danger', errorMessage);
            } else {
                showAlert('danger', 'Erreur lors de l\'opération: ' + error);
            }
        }
    });
};

// Toggle activity status
const toggleActivityStatus = (activityId) => {
    if (!confirm('Êtes-vous sûr de vouloir changer le statut de cette activité ?')) {
        return;
    }
    
    $.ajax({
        url: `/activities/${activityId}/toggle-status`,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                loadActivities(currentPage, currentFilters);
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function(xhr, status, error) {
            showAlert('danger', 'Erreur lors du changement de statut: ' + error);
        }
    });
};

// Show delete confirmation modal
const showDeleteConfirmation = (activityId) => {
    const activity = allActivities.find(a => a.id === activityId);
    
    if (!activity) {
        showAlert('danger', 'Activité non trouvée');
        return;
    }
    
    activityToDelete = activity;
    
    document.getElementById('activityToDeleteInfo').innerHTML = `
        <div class="activity-info">
            <div class="activity-info-icon">
                <i class="fas fa-running fa-2x"></i>
            </div>
            <div>
                <div class="activity-info-name">${activity.name}</div>
                <div class="activity-info-categorie">Catégorie: ${activity.categorie?.name || 'Non catégorisé'}</div>
                <div class="activity-info-slug">${activity.slug ? 'Slug: ' + activity.slug : ''}</div>
            </div>
        </div>
        <div class="row small text-muted">
            <div class="col-6">
                <div><strong>Participants:</strong> ${activity.participants_count || 0}</div>
                <div><strong>Réservations:</strong> ${activity.bookings_count || 0}</div>
                <div><strong>Durée:</strong> ${activity.duration || 'N/A'} min</div>
            </div>
            <div class="col-6">
                <div><strong>Prix:</strong> ${activity.price ? activity.price + ' €' : 'Gratuit'}</div>
                <div><strong>Statut:</strong> ${activity.is_active ? 'Actif' : 'Inactif'}</div>
                <div><strong>Lieu:</strong> ${activity.location || 'N/A'}</div>
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

// Delete activity
const deleteActivity = () => {
    if (!activityToDelete) {
        showAlert('danger', 'Aucune activité à supprimer');
        return;
    }
    
    const activityId = activityToDelete.id;
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
    const row = document.getElementById(`activity-row-${activityId}`);
    if (row) {
        row.classList.add('deleting-row');
    }
    
    // Send DELETE request
    $.ajax({
        url: `/activities/${activityId}`,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            // Hide modal
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            deleteModal.hide();
            
            if (response.success) {
                // Remove activity from array
                allActivities = allActivities.filter(a => a.id !== activityId);
                selectedActivities.delete(activityId);
                
                // Show success message
                showAlert('success', response.message || 'Activité supprimée avec succès !');
                
                // Remove row after animation
                if (row) {
                    setTimeout(() => {
                        row.remove();
                        
                        // Check if table is now empty
                        const tbody = document.getElementById('activitiesTableBody');
                        if (tbody.children.length === 0) {
                            document.getElementById('emptyState').style.display = 'block';
                            document.getElementById('tableContainer').style.display = 'none';
                            document.getElementById('paginationContainer').style.display = 'none';
                        }
                    }, 300);
                } else {
                    // Reload table
                    setTimeout(() => {
                        loadActivities(currentPage, currentFilters);
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
            const row = document.getElementById(`activity-row-${activityId}`);
            if (row) {
                row.classList.remove('deleting-row');
            }
            
            if (xhr.status === 404) {
                showAlert('danger', 'Activité non trouvée.');
                loadActivities(currentPage, currentFilters);
            } else {
                showAlert('danger', 'Erreur lors de la suppression: ' + error);
            }
        },
        complete: function() {
            activityToDelete = null;
        }
    });
};

// GENERATION ET VERIFICATION DU SLUG (CREATE)

// Générer le slug à partir du nom
const generateSlugFromName = () => {
    const nameInput = document.getElementById('createActivityName');
    const slugInput = document.getElementById('createActivitySlug');
    
    if (nameInput && slugInput) {
        const name = nameInput.value.trim();
        let slug = name
            .toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Supprimer les accents
            .replace(/[^\w\s]/gi, '') // Supprimer les caractères spéciaux
            .replace(/\s+/g, '-') // Remplacer les espaces par des tirets
            .replace(/--+/g, '-') // Supprimer les tirets multiples
            .replace(/^-|-$/g, ''); // Supprimer les tirets au début et à la fin
        
        slugInput.value = slug;
        
        // Vérifier la disponibilité du slug si non vide
        if (slug.length > 0) {
            checkSlugAvailability();
        } else {
            resetSlugStatus();
            document.getElementById('submitActivityBtn').disabled = true;
        }
    }
};

// Vérifier la disponibilité du slug (CREATE)
const checkSlugAvailability = () => {
    const slugInput = document.getElementById('createActivitySlug');
    const slug = slugInput ? slugInput.value.trim() : '';
    
    if (!slug) {
        resetSlugStatus();
        return;
    }
    
    // Afficher le statut de vérification
    showSlugStatus('checking');
    
    $.ajax({
        url: '{{ route("activities.check-slug") }}',
        type: 'GET',
        data: { slug: slug },
        dataType: 'json',
        success: function(response) {
            if (response.available) {
                showSlugStatus('available');
                document.getElementById('submitActivityBtn').disabled = false;
            } else {
                showSlugStatus('unavailable');
                document.getElementById('submitActivityBtn').disabled = true;
            }
        },
        error: function(xhr) {
            console.error('Error checking slug:', xhr.responseText);
            showSlugStatus('unavailable');
            document.getElementById('submitActivityBtn').disabled = true;
        }
    });
};

// Afficher le statut du slug (CREATE)
const showSlugStatus = (status) => {
    // Cacher tous les messages
    document.getElementById('slugCheckingText')?.classList.add('d-none');
    document.getElementById('slugAvailableText')?.classList.add('d-none');
    document.getElementById('slugUnavailableText')?.classList.add('d-none');
    
    // Afficher le message approprié
    switch(status) {
        case 'checking':
            document.getElementById('slugCheckingText')?.classList.remove('d-none');
            break;
        case 'available':
            document.getElementById('slugAvailableText')?.classList.remove('d-none');
            break;
        case 'unavailable':
            document.getElementById('slugUnavailableText')?.classList.remove('d-none');
            break;
    }
};

// Réinitialiser le statut du slug (CREATE)
const resetSlugStatus = () => {
    showSlugStatus('checking');
    document.getElementById('slugCheckingText')?.classList.add('d-none');
    document.getElementById('slugAvailableText')?.classList.add('d-none');
    document.getElementById('slugUnavailableText')?.classList.add('d-none');
};

// GENERATION ET VERIFICATION DU SLUG (EDIT)

// Générer le slug à partir du nom pour l'édition
const generateEditSlugFromName = () => {
    const nameInput = document.getElementById('editActivityName');
    const slugInput = document.getElementById('editActivitySlug');
    
    if (nameInput && slugInput) {
        const name = nameInput.value.trim();
        let slug = name
            .toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Supprimer les accents
            .replace(/[^\w\s]/gi, '') // Supprimer les caractères spéciaux
            .replace(/\s+/g, '-') // Remplacer les espaces par des tirets
            .replace(/--+/g, '-') // Supprimer les tirets multiples
            .replace(/^-|-$/g, ''); // Supprimer les tirets au début et à la fin
        
        slugInput.value = slug;
        
        // Vérifier la disponibilité du slug si non vide
        if (slug.length > 0) {
            checkEditSlugAvailability();
        } else {
            resetEditSlugStatus();
        }
    }
};

// Vérifier la disponibilité du slug (EDIT)
const checkEditSlugAvailability = () => {
    const slugInput = document.getElementById('editActivitySlug');
    const slug = slugInput ? slugInput.value.trim() : '';
    const activityId = document.getElementById('editActivityId')?.value || null;
    
    if (!slug) {
        resetEditSlugStatus();
        return;
    }
    
    // Afficher le statut de vérification
    showEditSlugStatus('checking');
    
    $.ajax({
        url: '{{ route("activities.check-slug") }}',
        type: 'GET',
        data: { 
            slug: slug,
            activity_id: activityId // Envoyer l'ID de l'activité en cours d'édition
        },
        dataType: 'json',
        success: function(response) {
            if (response.available) {
                showEditSlugStatus('available');
            } else {
                showEditSlugStatus('unavailable');
            }
        },
        error: function(xhr) {
            console.error('Error checking edit slug:', xhr.responseText);
            showEditSlugStatus('unavailable');
        }
    });
};

// Afficher le statut du slug (EDIT)
const showEditSlugStatus = (status) => {
    // Cacher tous les messages
    document.getElementById('editSlugCheckingText')?.classList.add('d-none');
    document.getElementById('editSlugAvailableText')?.classList.add('d-none');
    document.getElementById('editSlugUnavailableText')?.classList.add('d-none');
    
    // Afficher le message approprié
    switch(status) {
        case 'checking':
            document.getElementById('editSlugCheckingText')?.classList.remove('d-none');
            break;
        case 'available':
            document.getElementById('editSlugAvailableText')?.classList.remove('d-none');
            break;
        case 'unavailable':
            document.getElementById('editSlugUnavailableText')?.classList.remove('d-none');
            break;
    }
};

// Réinitialiser le statut du slug (EDIT)
const resetEditSlugStatus = () => {
    showEditSlugStatus('checking');
    document.getElementById('editSlugCheckingText')?.classList.add('d-none');
    document.getElementById('editSlugAvailableText')?.classList.add('d-none');
    document.getElementById('editSlugUnavailableText')?.classList.add('d-none');
};

// Réinitialiser le formulaire de création
const resetCreateForm = () => {
    const form = document.getElementById('createActivityForm');
    if (form) {
        form.reset();
    }
    
    // Réinitialiser le statut du slug
    resetSlugStatus();
    
    // Réactiver le bouton de soumission
    const submitBtn = document.getElementById('submitActivityBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.remove('btn-processing');
        submitBtn.innerHTML = `
            <span class="btn-text">
                <i class="fas fa-save me-2"></i>Créer l'activité
            </span>
        `;
    }
};

// Réinitialiser le formulaire d'édition
const resetEditForm = () => {
    const form = document.getElementById('editActivityForm');
    if (form) {
        form.reset();
    }
    
    // Réinitialiser le statut du slug
    resetEditSlugStatus();
    
    // Réactiver le bouton de soumission
    const updateBtn = document.getElementById('updateActivityBtn');
    if (updateBtn) {
        updateBtn.classList.remove('btn-processing');
        updateBtn.innerHTML = `
            <span class="btn-text">
                <i class="fas fa-save me-2"></i>Enregistrer les modifications
            </span>
        `;
        updateBtn.disabled = false;
    }
    
    editingActivityId = null;
};

// Open edit modal - Version simplifiée
const openEditModal = (activityId) => {
    const activity = allActivities.find(a => a.id === activityId);
    
    if (activity) {
        editingActivityId = activityId;
        
        // Remplir les champs du formulaire (seulement nom, catégorie, slug, statut)
        document.getElementById('editActivityId').value = activity.id;
        document.getElementById('editActivityName').value = activity.name;
        document.getElementById('editActivityCategorieId').value = activity.categorie_id;
        document.getElementById('editActivitySlug').value = activity.slug || '';
        document.getElementById('editActivityIsActive').checked = activity.is_active;
        
        // Réinitialiser le statut du slug
        resetEditSlugStatus();
        
        // Afficher la modal
        new bootstrap.Modal(document.getElementById('editActivityModal')).show();
    }
};

// Store activity
const storeActivity = () => {
    const form = document.getElementById('createActivityForm');
    const submitBtn = document.getElementById('submitActivityBtn');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Vérifier que le slug est disponible
    const slugAvailable = !document.getElementById('slugUnavailableText')?.classList.contains('d-none');
    if (!slugAvailable) {
        showAlert('warning', 'Veuillez vérifier la disponibilité du slug avant de continuer.');
        return;
    }
    
    // Show processing animation
    submitBtn.classList.add('btn-processing');
    submitBtn.innerHTML = `
        <span class="btn-text" style="display: none;">
            <i class="fas fa-save me-2"></i>Créer l'activité
        </span>
        <div class="spinner-border spinner-border-sm text-light" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        Création en cours...
    `;
    submitBtn.disabled = true;
    
    const formData = new FormData(form);
    
    // Convert FormData to object
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    // Convert checkbox value to boolean
    data.is_active = form.querySelector('#createActivityIsActive').checked;
    
    $.ajax({
        url: '{{ route("activities.store") }}',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            // Reset button state
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-save me-2"></i>Créer l'activité
                </span>
            `;
            submitBtn.disabled = false;
            
            if (response.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createActivityModal'));
                modal.hide();
                
                // Reset form
                resetCreateForm();
                
                // Reload activities
                loadActivities(1, currentFilters);
                
                // Show success message
                showAlert('success', 'Activité créée avec succès !');
            } else {
                showAlert('danger', response.message || 'Erreur lors de la création');
            }
        },
        error: function(xhr, status, error) {
            // Reset button state
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-save me-2"></i>Créer l'activité
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
                showAlert('danger', 'Erreur lors de la création: ' + error);
            }
        }
    });
};

// Update activity
const updateActivity = () => {
    const form = document.getElementById('editActivityForm');
    const submitBtn = document.getElementById('updateActivityBtn');
    const activityId = document.getElementById('editActivityId').value;
    
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
    
    // Convert FormData to object
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    data._method = 'PUT';
    data.is_active = form.querySelector('#editActivityIsActive').checked;
    
    $.ajax({
        url: `/activities/${activityId}`,
        type: 'POST',
        data: data,
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editActivityModal'));
                modal.hide();
                
                // Reset form
                resetEditForm();
                
                // Reload activities
                loadActivities(currentPage, currentFilters);
                
                // Show success message
                showAlert('success', 'Activité mise à jour avec succès !');
            } else {
                showAlert('danger', response.message || 'Erreur lors de la mise à jour');
            }
        },
        error: function(xhr, status, error) {
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
                showAlert('danger', 'Erreur lors de la mise à jour: ' + error);
            }
        }
    });
};

// Update advanced stats
const updateAdvancedStats = (stats) => {
    const advancedStatsContainer = document.getElementById('advancedStats');
    
    const html = `
        <div class="advanced-stat-card">
            <div class="advanced-stat-title">
                <i class="fas fa-star"></i>
                Activité la plus populaire
            </div>
            <div class="advanced-stat-value">
                ${stats.most_popular ? stats.most_popular.name : 'N/A'}
            </div>
            <div class="advanced-stat-subtext">
                ${stats.most_popular ? stats.most_popular.participants_count + ' participants' : ''}
            </div>
        </div>
        
        <div class="advanced-stat-card">
            <div class="advanced-stat-title">
                <i class="fas fa-chart-line"></i>
                Répartition par catégorie
            </div>
            <div class="advanced-stat-value">
                ${stats.activities_by_categorie?.length || 0}
            </div>
            <div class="advanced-stat-subtext">
                Catégories différentes
            </div>
        </div>
        
        <div class="advanced-stat-card">
            <div class="advanced-stat-title">
                <i class="fas fa-euro-sign"></i>
                Chiffre d'affaires
            </div>
            <div class="advanced-stat-value">
                ${formatNumber(stats.total_revenue || 0)} €
            </div>
            <div class="advanced-stat-subtext">
                Total généré
            </div>
        </div>
        
        <div class="advanced-stat-card">
            <div class="advanced-stat-title">
                <i class="fas fa-exclamation-circle"></i>
                Activités sans participants
            </div>
            <div class="advanced-stat-value">
                ${stats.activities_without_participants || 0}
            </div>
            <div class="advanced-stat-subtext">
                Aucun participant
            </div>
        </div>
    `;
    
    advancedStatsContainer.innerHTML = html;
};

// Show loading state
const showLoading = () => {
    document.getElementById('loadingSpinner').style.display = 'flex';
    document.getElementById('tableContainer').style.display = 'none';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('paginationContainer').style.display = 'none';
    document.getElementById('bulkActions').style.display = 'none';
};

// Hide loading state
const hideLoading = () => {
    document.getElementById('loadingSpinner').style.display = 'none';
};

// Format number
const formatNumber = (num) => {
    if (num === null || num === undefined) return 'N/A';
    const number = typeof num === 'string' ? parseFloat(num) : num;
    if (isNaN(number)) return 'N/A';
    return new Intl.NumberFormat('fr-FR').format(number);
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
                loadActivities(1, currentFilters);
            }, 500);
        });
    }
    
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            selectAllActivities(this.checked);
        });
    }
    
    // Apply bulk action button
    const applyBulkActionBtn = document.getElementById('applyBulkActionBtn');
    if (applyBulkActionBtn) {
        applyBulkActionBtn.addEventListener('click', applyBulkAction);
    }
    
    // Clear selection button
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', () => {
            selectedActivities.clear();
            loadActivities(currentPage, currentFilters);
        });
    }
    
    // Submit activity form
    const submitActivityBtn = document.getElementById('submitActivityBtn');
    if (submitActivityBtn) {
        submitActivityBtn.addEventListener('click', storeActivity);
    }
    
    // Update activity form
    const updateActivityBtn = document.getElementById('updateActivityBtn');
    if (updateActivityBtn) {
        updateActivityBtn.addEventListener('click', updateActivity);
    }
    
    // Confirm delete button
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', deleteActivity);
    }
    
    // Reset delete modal when hidden
    const deleteModal = document.getElementById('deleteConfirmationModal');
    if (deleteModal) {
        deleteModal.addEventListener('hidden.bs.modal', function() {
            activityToDelete = null;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                </span>
            `;
            deleteBtn.disabled = false;
        });
    }
    
    // Refresh stats button
    const refreshStatsBtn = document.getElementById('refreshStatsBtn');
    if (refreshStatsBtn) {
        refreshStatsBtn.addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Chargement...';
            btn.disabled = true;
            
            // Ici vous pourriez ajouter un appel AJAX pour recharger les stats
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1000);
        });
    }
    
    // Reset create form when modal is hidden
    const createModal = document.getElementById('createActivityModal');
    if (createModal) {
        createModal.addEventListener('hidden.bs.modal', function() {
            resetCreateForm();
        });
    }
    
    // Reset edit form when modal is hidden
    const editModal = document.getElementById('editActivityModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            resetEditForm();
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
                categorie_id: document.getElementById('filterCategory').value,
                status: document.getElementById('filterStatus').value,
                sort_by: document.getElementById('filterSortBy').value,
                sort_direction: document.getElementById('filterSortDirection')?.value || 'asc'
            };
            loadActivities(1, currentFilters);
        });
    }
    
    // Clear filters
   // Clear filters
const clearFiltersBtn = document.getElementById('clearFiltersBtn');
if (clearFiltersBtn) {
    clearFiltersBtn.addEventListener('click', () => {
        // CORRECTION : Utiliser une vérification conditionnelle standard
        const filterCategory = document.getElementById('filterCategory');
        const filterStatus = document.getElementById('filterStatus');
        const filterSortBy = document.getElementById('filterSortBy');
        const filterSortDirection = document.getElementById('filterSortDirection');
        
        if (filterCategory) filterCategory.value = '';
        if (filterStatus) filterStatus.value = '';
        if (filterSortBy) filterSortBy.value = 'name';
        if (filterSortDirection) filterSortDirection.value = 'asc';
        
        currentFilters = {};
        loadActivities(1);
    });
}
};
    </script>
    
    <style>
        /* Styles spécifiques pour les activités */
        .activity-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .activity-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            color: white;
            font-size: 1.2rem;
        }

        .activity-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .categorie-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            background: #e8f4fd;
            color: #1179c9;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .count-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            background: #f8f9fa;
            color: #495057;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .price-badge {
            font-weight: 600;
            color: var(--accent-color);
            font-size: 1rem;
            padding: 4px 10px;
            background: #e7f7f2;
            border-radius: 8px;
            display: inline-block;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: linear-gradient(135deg, #06b48a, #059672);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .activity-actions-modern {
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

        .status-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .status-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
        }

        .view-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .view-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: #333;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #ff9a2d);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 209, 102, 0.3);
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

        /* Activity info */
        .activity-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .activity-info-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            color: white;
            font-size: 1.5rem;
        }

        .activity-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
        }

        .activity-info-categorie {
            font-size: 0.9rem;
            color: #1179c9;
        }

        .activity-info-slug {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .bulk-actions-content {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            
            .bulk-actions-buttons {
                flex-wrap: wrap;
            }
            
            .activity-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .activity-icon-modern {
                width: 35px;
                height: 35px;
            }
            
            .activity-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
        }
        /* Styles pour les messages de statut du slug */
#slugCheckingText,
#slugAvailableText,
#slugUnavailableText {
    font-size: 0.8rem;
    display: block;
}

.d-none {
    display: none !important;
}
.input-group {
    flex-wrap: nowrap !important;
}
    </style>
@endsection