@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-tags"></i></span>
                Gestion des Catégories
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Catégorie
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
                        <option value="active">Actives</option>
                        <option value="inactive">Inactives</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="websites_count">Sites web</option>
                        <option value="templates_count">Templates</option>
                        <option value="created_at">Date de création</option>
                    </select>
                </div>
                <div class="col-md-4">
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
                        <div class="stats-value-modern" id="totalCategories">0</div>
                        <div class="stats-label-modern">Total Catégories</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeCategories">0</div>
                        <div class="stats-label-modern">Catégories Actives</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalWebsites">0</div>
                        <div class="stats-label-modern">Sites Web</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalTemplates">0</div>
                        <div class="stats-label-modern">Templates</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bulk Actions -->
        <div class="bulk-actions-modern" id="bulkActions" style="display: none;">
            <div class="bulk-actions-content">
                <span id="selectedCount">0 catégorie(s) sélectionnée(s)</span>
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
                <h3 class="card-title-modern">Liste des Catégories</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une catégorie..." id="searchInput">
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
                                <th>Type</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="categoriesTableBody">
                            <!-- Categories will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune catégorie trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première catégorie.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer une catégorie
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- Modals -->
    @include('activitie::categories.create-modal')
    @include('activitie::categories.edit-modal')
    @include('activitie::categories.delete-modal')

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration pour les catégories
        let currentPage = 1;
        let currentFilters = {};
        let allCategories = [];
        let selectedCategories = new Set();
        let categoryToDelete = null;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadCategories();
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

        // Load categories
        const loadCategories = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("categories.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allCategories = response.data || [];
                        renderCategories(allCategories);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des catégories');
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
                url: '{{ route("categories.statistics") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalCategories').textContent = stats.total_categories || 0;
                        document.getElementById('activeCategories').textContent = stats.active_categories || 0;
                        document.getElementById('totalWebsites').textContent = stats.total_websites_in_categories || 0;
                        document.getElementById('totalTemplates').textContent = stats.total_templates_in_categories || 0;
                        
                        // Show advanced stats section
                        document.getElementById('advancedStatsSection').style.display = 'block';
                        updateAdvancedStats(stats);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Statistics AJAX error:', xhr.responseText, status, error);
                }
            });
        };

        // Render categories
        const renderCategories = (categories) => {
            const tbody = document.getElementById('categoriesTableBody');
            tbody.innerHTML = '';
            
            if (!categories || !Array.isArray(categories) || categories.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                document.getElementById('bulkActions').style.display = 'none';
                return;
            }
            
            categories.forEach((category, index) => {
                const row = document.createElement('tr');
                row.id = `category-row-${category.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                const isSelected = selectedCategories.has(category.id);
                const totalItems = (category.websites_count || 0) + (category.templates_count || 0);
                const statusClass = category.is_active ? 'status-active' : 'status-inactive';
                const statusText = category.is_active ? 'Actif' : 'Inactif';
                
                row.innerHTML = `
                    <td>
                        <div class="form-check">
                            <input class="form-check-input row-checkbox" type="checkbox" 
                                   value="${category.id}" ${isSelected ? 'checked' : ''}
                                   onchange="toggleCategorySelection(${category.id}, this.checked)">
                        </div>
                    </td>
                    <td class="category-name-cell">
                        <div class="category-name-modern">
                            <div class="category-icon-modern">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div>
                                <div class="category-name-text">${category.name}</div>
                                <small class="text-muted">${category.slug || ''}</small>
                            </div>
                        </div>
                    </td>
                    <td class="category-name-cell">
                        <div class="category-name-modern">
                            
                            <div>
                                <div class="category-name-text"> ${category?.type?.name ?? 'Non défini'}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td>
                        <div class="category-actions-modern">
                            <button class="action-btn-modern status-btn-modern" title="Changer le statut" 
                                    onclick="toggleCategoryStatus(${category.id})">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <a href="{{ route('categories.show', '') }}/${category.id}" 
                               class="action-btn-modern view-btn-modern" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                    onclick="openEditModal(${category.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteConfirmation(${category.id})">
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
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} catégories`;
            
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
            loadCategories(page, currentFilters);
        };

        // Toggle category selection
        const toggleCategorySelection = (categoryId, isChecked) => {
            if (isChecked) {
                selectedCategories.add(categoryId);
            } else {
                selectedCategories.delete(categoryId);
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
                selectAllCheckbox.indeterminate = !allChecked && selectedCategories.size > 0;
            }
        };

        // Update bulk actions
        const updateBulkActions = () => {
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (selectedCategories.size > 0) {
                bulkActions.style.display = 'block';
                selectedCount.textContent = `${selectedCategories.size} catégorie(s) sélectionnée(s)`;
            } else {
                bulkActions.style.display = 'none';
            }
        };

        // Select all categories
        const selectAllCategories = (isChecked) => {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            
            checkboxes.forEach(checkbox => {
                const categoryId = parseInt(checkbox.value);
                checkbox.checked = isChecked;
                
                if (isChecked) {
                    selectedCategories.add(categoryId);
                } else {
                    selectedCategories.delete(categoryId);
                }
            });
            
            updateBulkActions();
        };

        // Apply bulk action
        const applyBulkAction = () => {
            const action = document.getElementById('bulkActionSelect').value;
            
            if (!action || selectedCategories.size === 0) {
                showAlert('warning', 'Veuillez sélectionner une action et des catégories');
                return;
            }
            
            if (action === 'delete') {
                if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedCategories.size} catégorie(s) ?`)) {
                    return;
                }
            }
            
            const data = {
                ids: Array.from(selectedCategories),
                action: action
            };
            
            $.ajax({
                url: '{{ route("categories.bulk-update") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        selectedCategories.clear();
                        loadCategories(currentPage, currentFilters);
                        loadStatistics();
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

        // Toggle category status
        const toggleCategoryStatus = (categoryId) => {
            if (!confirm('Êtes-vous sûr de vouloir changer le statut de cette catégorie ?')) {
                return;
            }
            
            $.ajax({
                url: `/categories/${categoryId}/toggle-status`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        loadCategories(currentPage, currentFilters);
                        loadStatistics();
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
        const showDeleteConfirmation = (categoryId) => {
            const category = allCategories.find(c => c.id === categoryId);
            
            if (!category) {
                showAlert('danger', 'Catégorie non trouvée');
                return;
            }
            
            categoryToDelete = category;
            
            document.getElementById('categoryToDeleteInfo').innerHTML = `
                <div class="category-info">
                    <div class="category-info-icon">
                        <i class="fas fa-tag fa-2x"></i>
                    </div>
                    <div>
                        <div class="category-info-name">${category.name}</div>
                        <div class="category-info-slug">${category.slug ? 'Slug: ' + category.slug : ''}</div>
                    </div>
                </div>
                <div class="row small text-muted">
                    <div class="col-6">
                        <div><strong>Sites web:</strong> ${category.websites_count || 0}</div>
                        <div><strong>Statut:</strong> ${category.is_active ? 'Actif' : 'Inactif'}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Templates:</strong> ${category.templates_count || 0}</div>
                        <div><strong>Total:</strong> ${(category.websites_count || 0) + (category.templates_count || 0)}</div>
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

        // Delete category
        const deleteCategory = () => {
            if (!categoryToDelete) {
                showAlert('danger', 'Aucune catégorie à supprimer');
                return;
            }
            
            const categoryId = categoryToDelete.id;
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
            const row = document.getElementById(`category-row-${categoryId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/categories/${categoryId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove category from array
                        allCategories = allCategories.filter(c => c.id !== categoryId);
                        selectedCategories.delete(categoryId);
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Catégorie supprimée avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('categoriesTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadCategories(currentPage, currentFilters);
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
                    const row = document.getElementById(`category-row-${categoryId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Catégorie non trouvée.');
                        loadCategories(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    categoryToDelete = null;
                }
            });
        };

        // Open edit modal
        const openEditModal = (categoryId) => {
            const category = allCategories.find(c => c.id === categoryId);
            
            if (category) {
                document.getElementById('editCategoryId').value = category.id;
                document.getElementById('editCategoryName').value = category.name;
                document.getElementById('editCategoryDescription').value = category.description || '';
                document.getElementById('editCategoryIsActive').checked = category.is_active;
                
                new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
            }
        };

        // Store category
        const storeCategory = () => {
            const form = document.getElementById('createCategoryForm');
            const submitBtn = document.getElementById('submitCategoryBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Créer la catégorie
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
            data.is_active = form.querySelector('#createCategoryIsActive').checked;
            
            $.ajax({
                url: '{{ route("categories.store") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la catégorie
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createCategoryModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        
                        // Reload categories
                        loadCategories(1, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Catégorie créée avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création');
                    }
                },
                error: function(xhr, status, error) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la catégorie
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

        // Update category
        const updateCategory = () => {
            const form = document.getElementById('editCategoryForm');
            const submitBtn = document.getElementById('updateCategoryBtn');
            const categoryId = document.getElementById('editCategoryId').value;
            
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
            data.is_active = form.querySelector('#editCategoryIsActive').checked;
            
            $.ajax({
                url: `/categories/${categoryId}`,
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
                        modal.hide();
                        
                        // Reload categories
                        loadCategories(currentPage, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Catégorie mise à jour avec succès !');
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
                        Catégorie la plus utilisée
                    </div>
                    <div class="advanced-stat-value">
                        ${stats.most_used ? stats.most_used.name : 'N/A'}
                    </div>
                    <div class="advanced-stat-subtext">
                        ${stats.most_used ? stats.most_used.websites_count + ' sites + ' + stats.most_used.templates_count + ' templates' : ''}
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">
                        <i class="fas fa-chart-line"></i>
                        Utilisation des catégories
                    </div>
                    <div class="advanced-stat-value">
                        ${stats.categories_with_websites || 0} / ${stats.total_categories || 0}
                    </div>
                    <div class="advanced-stat-subtext">
                        Catégories avec sites web
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">
                        <i class="fas fa-paint-brush"></i>
                        Templates par catégorie
                    </div>
                    <div class="advanced-stat-value">
                        ${stats.total_templates_in_categories || 0}
                    </div>
                    <div class="advanced-stat-subtext">
                        Templates dans toutes les catégories
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">
                        <i class="fas fa-exclamation-circle"></i>
                        Catégories inutilisées
                    </div>
                    <div class="advanced-stat-value">
                        ${stats.categories_without_items || 0}
                    </div>
                    <div class="advanced-stat-subtext">
                        Sans sites ni templates
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
                        loadCategories(1, currentFilters);
                    }, 500);
                });
            }
            
            // Select all checkbox
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    selectAllCategories(this.checked);
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
                    selectedCategories.clear();
                    loadCategories(currentPage, currentFilters);
                });
            }
            
            // Submit category form
            const submitCategoryBtn = document.getElementById('submitCategoryBtn');
            if (submitCategoryBtn) {
                submitCategoryBtn.addEventListener('click', storeCategory);
            }
            
            // Update category form
            const updateCategoryBtn = document.getElementById('updateCategoryBtn');
            if (updateCategoryBtn) {
                updateCategoryBtn.addEventListener('click', updateCategory);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteCategory);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    categoryToDelete = null;
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
                    
                    loadStatistics();
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 1000);
                });
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createCategoryModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createCategoryForm').reset();
                    const submitBtn = document.getElementById('submitCategoryBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer la catégorie
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editCategoryModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    const submitBtn = document.getElementById('updateCategoryBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    `;
                    submitBtn.disabled = false;
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
                        sort_by: document.getElementById('filterSortBy').value,
                        sort_direction: document.getElementById('filterSortDirection').value
                    };
                    loadCategories(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterSortBy').value = 'name';
                    document.getElementById('filterSortDirection').value = 'asc';
                    currentFilters = {};
                    loadCategories(1);
                });
            }
        };
    </script>
    
    <style>
        /* Styles spécifiques pour les catégories */
        .category-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .category-icon-modern {
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

        .category-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
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

        .total-count {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
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

        .category-actions-modern {
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

        /* Bulk Actions */
        .bulk-actions-modern {
            background: linear-gradient(135deg, #f8f9ff, #ffffff);
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(58, 86, 228, 0.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .bulk-actions-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .bulk-actions-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .bulk-select {
            min-width: 180px;
        }

        /* Category info */
        .category-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .category-info-icon {
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

        .category-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
        }

        .category-info-slug {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Form checkboxes */
        .form-check {
            margin: 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
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
            
            .category-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .category-icon-modern {
                width: 35px;
                height: 35px;
            }
            
            .category-actions-modern {
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