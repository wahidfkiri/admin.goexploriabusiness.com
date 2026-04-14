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
                                <th>Catégorie</th>
                                <th>Activité</th>
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
/// ==================== VARIABLES GLOBALES ====================
let currentPage = 1;
let currentFilters = {};
let allActivities = [];
let selectedActivities = new Set();
let activityToDelete = null;
let editingActivityId = null;
let isSubmitting = false;

// ==================== INITIALISATION ====================
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

// ==================== FONCTIONS DE NETTOYAGE DES MODALS ====================
const cleanupModals = () => {
    // Supprimer tous les backdrops
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Restaurer le body
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // Réactiver le scroll sur le body
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
};

const safelyHideModal = (modalElement) => {
    if (!modalElement) return;
    
    try {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    } catch (e) {
        console.warn('Error hiding modal:', e);
    }
    
    // Attendre un peu puis nettoyer
    setTimeout(() => {
        cleanupModals();
    }, 150);
};

// ==================== CHARGEMENT DES ACTIVITÉS ====================
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

// ==================== GESTION DU SLUG (CREATE) ====================

// Générer le slug à partir du nom
const generateSlugFromName = () => {
    const nameInput = document.getElementById('createActivityName');
    const slugInput = document.getElementById('createActivitySlug');
    
    if (!nameInput || !slugInput) return;
    
    const name = nameInput.value.trim();
    
    if (!name) {
        slugInput.value = '';
        resetSlugStatus();
        enableSubmitButton(false);
        return;
    }
    
    let slug = name
        .toLowerCase()
        .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
        .replace(/[^\w\s]/gi, '')
        .replace(/\s+/g, '-')
        .replace(/--+/g, '-')
        .replace(/^-|-$/g, '');
    
    slugInput.value = slug;
    
    if (slug.length > 0) {
        checkSlugAvailability();
    } else {
        resetSlugStatus();
        enableSubmitButton(false);
    }
};

// Vérifier la disponibilité du slug (CREATE)
let checkSlugTimeout = null;

const checkSlugAvailability = () => {
    const slugInput = document.getElementById('createActivitySlug');
    const slug = slugInput ? slugInput.value.trim() : '';
    
    if (!slug) {
        resetSlugStatus();
        enableSubmitButton(false);
        return;
    }
    
    if (checkSlugTimeout) {
        clearTimeout(checkSlugTimeout);
    }
    
    checkSlugTimeout = setTimeout(() => {
        showSlugStatus('checking');
        
        $.ajax({
            url: '{{ route("activities.check-slug") }}',
            type: 'GET',
            data: { slug: slug },
            dataType: 'json',
            success: function(response) {
                if (response.available) {
                    showSlugStatus('available');
                    enableSubmitButton(true);
                } else {
                    showSlugStatus('unavailable');
                    enableSubmitButton(false);
                }
            },
            error: function(xhr) {
                console.error('Error checking slug:', xhr.responseText);
                showSlugStatus('unavailable');
                enableSubmitButton(false);
            }
        });
    }, 500);
};

// Afficher le statut du slug
const showSlugStatus = (status) => {
    const checkingText = document.getElementById('slugCheckingText');
    const availableText = document.getElementById('slugAvailableText');
    const unavailableText = document.getElementById('slugUnavailableText');
    
    if (checkingText) checkingText.classList.add('d-none');
    if (availableText) availableText.classList.add('d-none');
    if (unavailableText) unavailableText.classList.add('d-none');
    
    switch(status) {
        case 'checking':
            if (checkingText) checkingText.classList.remove('d-none');
            break;
        case 'available':
            if (availableText) availableText.classList.remove('d-none');
            break;
        case 'unavailable':
            if (unavailableText) unavailableText.classList.remove('d-none');
            break;
    }
};

// Réinitialiser le statut du slug
const resetSlugStatus = () => {
    const checkingText = document.getElementById('slugCheckingText');
    const availableText = document.getElementById('slugAvailableText');
    const unavailableText = document.getElementById('slugUnavailableText');
    
    if (checkingText) checkingText.classList.add('d-none');
    if (availableText) availableText.classList.add('d-none');
    if (unavailableText) unavailableText.classList.add('d-none');
};

// Activer/désactiver le bouton de soumission
const enableSubmitButton = (enabled) => {
    const submitBtn = document.getElementById('submitActivityBtn');
    if (submitBtn) {
        submitBtn.disabled = !enabled;
    }
};

// ==================== RÉINITIALISATION DU FORMULAIRE ====================
const resetCreateForm = () => {
    const form = document.getElementById('createActivityForm');
    if (form) {
        form.reset();
    }
    
    const slugInput = document.getElementById('createActivitySlug');
    if (slugInput) slugInput.value = '';
    
    resetSlugStatus();
    enableSubmitButton(false);
    isSubmitting = false;
    
    // Réinitialiser le bouton
    const submitBtn = document.getElementById('submitActivityBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer l\'activité';
    }
};

// ==================== CRÉATION D'ACTIVITÉ ====================
const storeActivity = () => {
    if (isSubmitting) {
        showAlert('warning', 'Création en cours, veuillez patienter...');
        return;
    }
    
    const form = document.getElementById('createActivityForm');
    const submitBtn = document.getElementById('submitActivityBtn');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const unavailableText = document.getElementById('slugUnavailableText');
    const isUnavailable = unavailableText && !unavailableText.classList.contains('d-none');
    
    if (isUnavailable) {
        showAlert('warning', 'Ce slug n\'est pas disponible. Veuillez en choisir un autre.');
        return;
    }
    
    const checkingText = document.getElementById('slugCheckingText');
    const isChecking = checkingText && !checkingText.classList.contains('d-none');
    
    if (isChecking) {
        showAlert('warning', 'Veuillez attendre la vérification du slug.');
        return;
    }
    
    const slugInput = document.getElementById('createActivitySlug');
    if (!slugInput || !slugInput.value.trim()) {
        showAlert('warning', 'Veuillez générer un slug valide.');
        return;
    }
    
    isSubmitting = true;
    const originalButtonHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Création en cours...`;
    
    const formData = new FormData(form);
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    data.is_active = document.getElementById('createActivityIsActive')?.checked || false;
    
    $.ajax({
        url: '{{ route("activities.store") }}',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Fermer la modal proprement
                const modalElement = document.getElementById('createActivityModal');
                safelyHideModal(modalElement);
                
                // Réinitialiser le formulaire
                resetCreateForm();
                
                // Recharger la liste
                loadActivities(1, currentFilters);
                
                // Message de succès
                showAlert('success', response.message || 'Activité créée avec succès !');
            } else {
                showAlert('danger', response.message || 'Erreur lors de la création');
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalButtonHtml;
            }
        },
        error: function(xhr) {
            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonHtml;
            
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                for (const field in errors) {
                    errorMessage += `- ${errors[field].join('<br>')}<br>`;
                }
                showAlert('danger', errorMessage);
                
                if (errors.slug) {
                    checkSlugAvailability();
                }
            } else {
                showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de la création');
            }
        }
    });
};

// ==================== AUTRES FONCTIONS ====================

// Rendu des activités
const renderActivities = (activities) => {
    const tbody = document.getElementById('activitiesTableBody');
    const emptyState = document.getElementById('emptyState');
    const tableContainer = document.getElementById('tableContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    const bulkActions = document.getElementById('bulkActions');
    
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (!activities || activities.length === 0) {
        if (emptyState) emptyState.style.display = 'block';
        if (tableContainer) tableContainer.style.display = 'none';
        if (paginationContainer) paginationContainer.style.display = 'none';
        if (bulkActions) bulkActions.style.display = 'none';
        return;
    }
    
    if (emptyState) emptyState.style.display = 'none';
    if (tableContainer) tableContainer.style.display = 'block';
    if (paginationContainer) paginationContainer.style.display = 'flex';
    
    activities.forEach((activity, index) => {
        const row = document.createElement('tr');
        row.id = `activity-row-${activity.id}`;
        
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
            <td>
                <div class="activity-name-modern">
                    <div>
                        <div class="activity-name-text">${escapeHtml(activity.category_relation?.name || 'Non catégorisé')}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="categorie-badge d-block">
                    <i class="fas fa-tag me-1"></i>
                    ${activity.name}

                </div>
            </td>
            <td>
                <span class="status-badge ${statusClass}">${statusText}</span>
            </td>
            <td style="text-align: center;">
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
    
    updateBulkActions();
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
    
    if (selectedActivities.size > 0 && bulkActions) {
        bulkActions.style.display = 'block';
        if (selectedCount) selectedCount.textContent = `${selectedActivities.size} activité(s) sélectionnée(s)`;
    } else if (bulkActions) {
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
    const action = document.getElementById('bulkActionSelect')?.value;
    
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
        error: function(xhr) {
            showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de l\'opération');
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
        error: function(xhr) {
            showAlert('danger', xhr.responseJSON?.message || 'Erreur lors du changement de statut');
        }
    });
};

// Render pagination
const renderPagination = (response) => {
    const pagination = document.getElementById('pagination');
    const paginationInfo = document.getElementById('paginationInfo');
    
    if (!pagination || !paginationInfo) return;
    
    const start = (response.current_page - 1) * response.per_page + 1;
    const end = Math.min(response.current_page * response.per_page, response.total);
    paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} activités`;
    
    let paginationHtml = '';
    
    if (response.prev_page_url) {
        paginationHtml += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${response.current_page - 1})"><i class="fas fa-chevron-left"></i></a></li>`;
    } else {
        paginationHtml += `<li class="page-item disabled"><span class="page-link-modern"><i class="fas fa-chevron-left"></i></span></li>`;
    }
    
    const maxPages = 5;
    let startPage = Math.max(1, response.current_page - Math.floor(maxPages / 2));
    let endPage = Math.min(response.last_page, startPage + maxPages - 1);
    
    if (endPage - startPage + 1 < maxPages) {
        startPage = Math.max(1, endPage - maxPages + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === response.current_page) {
            paginationHtml += `<li class="page-item active"><span class="page-link-modern">${i}</span></li>`;
        } else {
            paginationHtml += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${i})">${i}</a></li>`;
        }
    }
    
    if (response.next_page_url) {
        paginationHtml += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${response.current_page + 1})"><i class="fas fa-chevron-right"></i></a></li>`;
    } else {
        paginationHtml += `<li class="page-item disabled"><span class="page-link-modern"><i class="fas fa-chevron-right"></i></span></li>`;
    }
    
    pagination.innerHTML = paginationHtml;
};

// Change page
const changePage = (page) => {
    currentPage = page;
    loadActivities(page, currentFilters);
};

// Show delete confirmation modal
const showDeleteConfirmation = (activityId) => {
    const activity = allActivities.find(a => a.id === activityId);
    
    if (!activity) {
        showAlert('danger', 'Activité non trouvée');
        return;
    }
    
    activityToDelete = activity;
    
    const infoContainer = document.getElementById('activityToDeleteInfo');
    if (infoContainer) {
        infoContainer.innerHTML = `
            <div class="activity-info">
                <div class="activity-info-icon">
                    <i class="fas fa-running fa-2x"></i>
                </div>
                <div>
                    <div class="activity-info-name">${escapeHtml(activity.name)}</div>
                    <div class="activity-info-categorie">Catégorie: ${escapeHtml(activity.categorie?.name || 'Non catégorisé')}</div>
                </div>
            </div>
        `;
    }
    
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
    
    if (deleteBtn) {
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Suppression...`;
    }
    
    $.ajax({
        url: `/activities/${activityId}`,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            const modalElement = document.getElementById('deleteConfirmationModal');
            safelyHideModal(modalElement);
            
            if (response.success) {
                showAlert('success', response.message);
                selectedActivities.delete(activityId);
                loadActivities(currentPage, currentFilters);
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function(xhr) {
            const modalElement = document.getElementById('deleteConfirmationModal');
            safelyHideModal(modalElement);
            showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de la suppression');
        },
        complete: function() {
            activityToDelete = null;
            if (deleteBtn) {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="fas fa-trash me-2"></i>Supprimer définitivement';
            }
        }
    });
};

// Open edit modal
const openEditModal = (activityId) => {
    const activity = allActivities.find(a => a.id === activityId);
    
    if (!activity) return;
    
    editingActivityId = activityId;
    
    const idInput = document.getElementById('editActivityId');
    const nameInput = document.getElementById('editActivityName');
    const categorieSelect = document.getElementById('editActivityCategorieId');
    const slugInput = document.getElementById('editActivitySlug');
    const activeCheckbox = document.getElementById('editActivityIsActive');
    
    if (idInput) idInput.value = activity.id;
    if (nameInput) nameInput.value = activity.name;
    if (categorieSelect) categorieSelect.value = activity.categorie_id;
    if (slugInput) slugInput.value = activity.slug || '';
    if (activeCheckbox) activeCheckbox.checked = activity.is_active;
    
    new bootstrap.Modal(document.getElementById('editActivityModal')).show();
};

// Update activity
const updateActivity = () => {
    const form = document.getElementById('editActivityForm');
    const submitBtn = document.getElementById('updateActivityBtn');
    const activityId = document.getElementById('editActivityId')?.value;
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
    }
    
    const formData = new FormData(form);
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    data._method = 'PUT';
    data.is_active = document.getElementById('editActivityIsActive')?.checked || false;
    
    $.ajax({
        url: `/activities/${activityId}`,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const modalElement = document.getElementById('editActivityModal');
                safelyHideModal(modalElement);
                
                showAlert('success', response.message);
                loadActivities(currentPage, currentFilters);
            } else {
                showAlert('danger', response.message);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer les modifications';
                }
            }
        },
        error: function(xhr) {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer les modifications';
            }
            
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Veuillez corriger les erreurs:<br>';
                for (const field in errors) {
                    errorMessage += `- ${errors[field].join('<br>')}<br>`;
                }
                showAlert('danger', errorMessage);
            } else {
                showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de la mise à jour');
            }
        }
    });
};

// ==================== UTILITAIRES ====================

// Show loading state
const showLoading = () => {
    const spinner = document.getElementById('loadingSpinner');
    const tableContainer = document.getElementById('tableContainer');
    const emptyState = document.getElementById('emptyState');
    const paginationContainer = document.getElementById('paginationContainer');
    
    if (spinner) spinner.style.display = 'flex';
    if (tableContainer) tableContainer.style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
    if (paginationContainer) paginationContainer.style.display = 'none';
};

// Hide loading state
const hideLoading = () => {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.style.display = 'none';
};

// Escape HTML
const escapeHtml = (text) => {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
};

// Show alert
const showAlert = (type, message) => {
    const existingAlert = document.querySelector('.alert-custom-modern');
    if (existingAlert) existingAlert.remove();
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-custom-modern alert-dismissible fade show`;
    alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
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

// ==================== EVENT LISTENERS ====================

const setupEventListeners = () => {
    // Search input
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
    
    // Bulk actions
    const applyBulkActionBtn = document.getElementById('applyBulkActionBtn');
    if (applyBulkActionBtn) {
        applyBulkActionBtn.addEventListener('click', applyBulkAction);
    }
    
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', () => {
            selectedActivities.clear();
            loadActivities(currentPage, currentFilters);
        });
    }
    
    // Submit activity
    const submitActivityBtn = document.getElementById('submitActivityBtn');
    if (submitActivityBtn) {
        submitActivityBtn.addEventListener('click', storeActivity);
    }
    
    // Update activity
    const updateActivityBtn = document.getElementById('updateActivityBtn');
    if (updateActivityBtn) {
        updateActivityBtn.addEventListener('click', updateActivity);
    }
    
    // Delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', deleteActivity);
    }
    
    // Reset modals when hidden - IMPORTANT: Utiliser les événements Bootstrap
    const createModal = document.getElementById('createActivityModal');
    if (createModal) {
        createModal.addEventListener('hidden.bs.modal', function() {
            resetCreateForm();
            cleanupModals();
        });
        
        // Nettoyer avant ouverture aussi
        createModal.addEventListener('show.bs.modal', function() {
            cleanupModals();
            resetCreateForm();
        });
    }
    
    const editModal = document.getElementById('editActivityModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            cleanupModals();
        });
    }
    
    const deleteModal = document.getElementById('deleteConfirmationModal');
    if (deleteModal) {
        deleteModal.addEventListener('hidden.bs.modal', function() {
            activityToDelete = null;
            cleanupModals();
        });
    }
    
    // Slug generation
    const createActivityName = document.getElementById('createActivityName');
    if (createActivityName) {
        createActivityName.addEventListener('input', generateSlugFromName);
    }
    
    const createActivitySlug = document.getElementById('createActivitySlug');
    if (createActivitySlug) {
        createActivitySlug.addEventListener('input', checkSlugAvailability);
    }
    
    // Filter section
    const toggleFilterBtn = document.getElementById('toggleFilterBtn');
    const filterSection = document.getElementById('filterSection');
    if (toggleFilterBtn && filterSection) {
        toggleFilterBtn.addEventListener('click', () => {
            const isVisible = filterSection.style.display === 'block';
            filterSection.style.display = isVisible ? 'none' : 'block';
        });
    }
    
    // Apply filters
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', () => {
            currentFilters = {
                categorie_id: document.getElementById('filterCategory')?.value || '',
                status: document.getElementById('filterStatus')?.value || ''
            };
            loadActivities(1, currentFilters);
        });
    }
    
    // Clear filters
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
            const filterCategory = document.getElementById('filterCategory');
            const filterStatus = document.getElementById('filterStatus');
            
            if (filterCategory) filterCategory.value = '';
            if (filterStatus) filterStatus.value = '';
            
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